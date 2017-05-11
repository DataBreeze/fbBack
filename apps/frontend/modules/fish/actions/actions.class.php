<?php

/**
 * fish actions.
 *
 * @package    fb
 * @subpackage fish
 * @author     Joe Junkin
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class fishActions extends sfActions
{
  const META_KEYWORDS = 'Fishing,saltwater fish,saltwater fish species,saltwater fish types,find saltwater fish,fish data,fish graphs,fish charts';  

  public function preExecute(){
    $this->fbLib = new fbLib($this->getRequest());
    $this->user = $this->fbLib->restoreUser();
  }

  private function siteIdArray($sites){
    $list = array();
    foreach ($sites as $i => $site){
      $list[$i] = $site['id'];
    }
    return $list;
  }
  
  public function executeIndex($req){
    $cfg_js = array('page' => 'fish');
    $res = $this->getResponse();
    $month_range = $this->fbLib->getMonthRange();
    $this->loc = $this->fbLib->getLoc($req);
    $this->geo = $this->loc;
    $geo = $this->loc['geo'];
    $template = 'fish_all';
    $fish_id = False;
    $msg_text = 'All Fish';
    $this->sites_top = array();
    $this->catch_annual = array();
    $fname = $req->getParameter('fname');
    $this->spots = False;
    $this->catch = False;
    $this->fish_wiki = array();
    $fishOne = $this->fishRestore( array('name' => $fname,'geo' => $geo) );
    $fish_id = intval($fishOne['id']);
    if($fish_id){
      $cfg_js['fish_id'] = $fish_id;
      $cfg_js['noaa_id'] = $fishOne['noaa_id'];
      if($geo_new = Doctrine_Core::getTable('CatchByMonth')->catchBoundsByFishId($fishOne['noaa_id'])){
	$geo = $geo_new;
	$geo['zoom'] = 6;
	$this->loc['geo'] = $geo;
      }
      $title = $fishOne['wiki_title'];
      $cfg_js['title'] = $title;
      if($title){
	$result = fbLib::getWiki($title);
	$cfg_js['wiki_err'] = $result['desc'];;
	if( ! $result['error']){
	  $content = $result['content'];
	  $this->fish_wiki[intval($fish_id)] = $content;
	  $fishOne['wiki_text'] = $content;
	}else{
	  $cfg_js['wiki_err'] = $result['desc'];;
	}
      }
      $this->catch['selected'] = $fishOne['id'];
      $this->fishOne = $fishOne;
    }else{
      $this->fishOne = array();
    }
    $this->spots = Doctrine_Core::getTable('Spot')->getSpotsBB($geo,$month_range);
    $this->catch = Doctrine_Core::getTable('Fish')->fishAllBB($geo);
    $this->notes = Doctrine_Core::getTable('UserNotify')->getNotifies($this->user['id']);
    $this->json_geo = json_encode($this->loc);
    $this->json_notes = json_encode($this->notes);
    $this->json_fish = json_encode($this->fish);
    $this->json_fish_wiki = json_encode($this->fish_wiki);
    $this->json_spots = json_encode($this->spots);
    $this->json_catch = json_encode($this->catch);
    $this->month_range = join($month_range,',');
    $area = $geo['input'];
    $response = $this->getResponse();
    $response->addMeta('keywords', fishActions::META_KEYWORDS .','. $area);
    $desc = 'Explore Popular Fishing Spots for Saltwater Fish Species';
    $title = 'Explore Fishing Spots for Saltwater Fish in ' . $area;
    $head = 'Saltwater Fish Species and Spots';
    if($fish['name']){
      $desc = 'Explore Popular Fishing Spots for ' . $fish['name'];
      $title = 'Exploring Popular Fishing Spots for ' . $fish['name'];
      $head = 'Popular Spots and Catch Totals for ' . $fish['name'];
    }
    $response->addMeta('description', $desc);
    $response->setTitle($title);
    $this->cfg = array();
    $this->cfg['head_text'] = $head;
    $this->cfg['page'] = 'fish';
    $this->cfg['about_onclick'] = 'fishAbout();';
    $this->json_cfg = json_encode($cfg_js);
    $this->json_user = json_encode($this->fbLib->userSafe($this->user));
    $this->msg = array('text' => $msg_text, 'id' => 'msgFish','about_onclick' => 'fishAbout();');
    $this->param = array('template' => $template,'spots' => $this->spots,'fish' => $this->fish, 'catch' => $this->catch, 'user' => $this->user, 'geo' => $this->geo);
  }

  public function fishRestore($param){
    $detail = False;
    $fish = array('error' => True,'name' => 'None','id' => False,'noaa_id' => False,'catch_annual' => array(),'catch_top_spots' => array());
    if($param['name']){
      $fish = Doctrine_Core::getTable('Fish')->fishByName($param['name']);
      $fish_id = $fish['id'];
    }elseif($param['fish_id']){
      $fish_id = $param['fish_id'];
    }
    if($fish_id){
      $detail = Doctrine_Core::getTable('Fish')->fishDetailAll($fish_id);
    }
    return $detail;
  }

  # fetch a single site given a siteID and fishID
  # used in the map info window
  public function executeAFetchSite(sfWebRequest $req){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( !$req->hasParameter('siteID') || !$req->hasParameter('fishID') ){
      $json = 'Site or Fish ID not found';
      return $this->renderText($json);
    }
    $site_id = $req->getParameter('siteID');
    $fish_id = $req->getParameter('fishID');
    $month_range = $this->fbLib->getMonthRange();
    $site = Doctrine_Core::getTable('CatchByMonth')->getCatchBySiteAndFish($site_id,$fish_id,$month_range);
    $json = json_encode($site);
    return $this->renderText($json);
  }

  # bounds have changed, refetch single fish stats
  public function executeAFetchFishBB(sfWebRequest $req){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( !$req->hasParameter('bounds') or !$req->hasParameter('center') ){
      $json = 'bounds not found';
      return $this->renderText($json);
    }
    if ( !$req->hasParameter('fishID') ){
      $json = 'Fish ID not found';
      return $this->renderText($json);
    }
    $geo = $this->fbLib->boundsFromRequest();
    $fish_id = $req->getParameter('fishID');
    $month_range = $this->fbLib->getMonthRange();
    $fish = Doctrine_Core::getTable('CatchByFish')->fishById($fish_id);
    $stats = Doctrine_Core::getTable('CatchByMonth')->getCatchByFishIdBB($fish_id,$geo,$month_range);
    if( ! $stats){
      $stats['fish_id'] = $fish['id'];
      $stats['name'] = $fish['name'];
      $stats['count'] = 0;
      $stats['avg_weight'] = 0;
      $stats['avg_length'] = 0;
    }
    $json = json_encode($stats);
    return $this->renderText($json);
  }

  public function executeAFishBB(sfWebRequest $req){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( !$req->hasParameter('bounds') ){
      $json = 'bounds not found';
      return $this->renderText($json);
    }
    $offset = $req->getParameter('offset');
    if(! $offset){
      $offset = 0;
    }
    $geo = $this->fbLib->boundsFromRequest();
    $catch = Doctrine_Core::getTable('Fish')->fishAllBB($geo,$offset);
    $json = json_encode($catch);
    return $this->renderText($json);
  }

  # bounds have changed, refetch all sites in viewport
  public function executeAFetchSitesBB(sfWebRequest $req){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( !$req->hasParameter('bounds') ){
      $json = 'bounds not found';
      return $this->renderText($json);
    }
    $month_range = $this->fbLib->getMonthRange();
    $geo = $this->fbLib->boundsFromRequest();
    $sites = Doctrine_Core::getTable('CatchByMonth')->getSitesBB($geo,$month_range);
    $json = json_encode($sites);
    return $this->renderText($json);
  }

  # bounds have changed, refetch all sites in viewport
  public function executeAFetchSitesByFishBB(sfWebRequest $req){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( !$req->hasParameter('bounds') or !$req->hasParameter('center') ){
      $json = 'bounds not found';
      return $this->renderText($json);
    }
    if ( !$req->hasParameter('fishID') ){
      $json = 'Fish ID not found';
      return $this->renderText($json);
    }
    $fish_id = $req->getParameter('fishID');
    $month_range = $this->fbLib->getMonthRange();
    $geo = $this->fbLib->boundsFromRequest();
    $sites = Doctrine_Core::getTable('CatchByMonth')->getSitesByFishBB($fish_id,$geo,$month_range);
    $json = json_encode($sites);
    return $this->renderText($json);
  }

  # bounds have changed, refetch top sites in viewport
  public function executeAFetchTopSitesBB(sfWebRequest $req){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( !$req->hasParameter('bounds') or !$req->hasParameter('center') ){
      $json = 'bounds not found';
      return $this->renderText($json);
    }
    if ( !$req->hasParameter('fishId') ){
      $json = 'Fish ID not found';
      return $this->renderText($json);
    }
    $geo = $this->fbLib->boundsFromRequest();
    $fish_id = $req->getParameter('fishId');
    $month_range = $this->fbLib->getMonthRange();
    $sites = Doctrine_Core::getTable('CatchByMonth')->getTopSitesByFishBB($fish_id,$geo);
    $json = json_encode($sites);
    return $this->renderText($json);
  }

  # bounds have changed, refetch annual monthly catch data by species for chart 
  public function executeAFetchCatchAnnualBB(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( !$request->hasParameter('fishId') ){
      $json = 'Fish ID not found';
      return $this->renderText($json);
    }
    $fish_id = $request->getParameter('fishId');
    $geo = $this->fbLib->boundsFromRequest();
    $catch_annual = Doctrine_Core::getTable('CatchByMonth')->getCatchByFishAnnualBB($fish_id,$geo);
    $json = json_encode($catch_annual);
    return $this->renderText($json);
  }

  # auto-lookup of fish name by first few chars in search box
  public function executeALookup(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( !$request->hasParameter('term') ){
      $json = array();
      return $this->renderText($json);
    }
    $substr = $request->getParameter('term');
    $recs = Doctrine_Core::getTable('CatchByFish')->fishByNameLike($substr);
    $json = json_encode($recs);
    return $this->renderText($json);
  }
  
  # verify the fish name is valid
  public function executeACheckFish(sfWebRequest $req){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error'=>True, 'found' => False);
    if ( !$req->hasParameter('fishName') ){
      $ret['desc'] = 'Parameter Fish Name not found';
    }else{
      $name = $req->getParameter('fishName');
      $name = strip_tags($name);
      $name = trim($name);
      if(strlen($name) < 1){
	$ret['desc'] = 'Parameter Fish Name empty';
      }else{
	$ret['error'] = False;
	$fish = Doctrine_Core::getTable('CatchByFish')->fishFind($name);
	if(! $fish){
	  $ret['desc'] = 'Fish not found';	  
	}else{
	  $ret['found'] = True;
	  $ret['fish'] = $fish;
	}
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  ## get wiki content for a fish
  public function executeAWiki(sfWebRequest $req){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error'=>True, 'content' => False,'title' => False);
    if ( !$req->hasParameter('fishId') ){
      $ret['desc'] = 'Parameter Fish ID not found';
    }else{
      $fish_id = $req->getParameter('fishId');
      $ret['fish_id'] = intval($fish_id);
      if($fish = Doctrine_Core::getTable('Fish')->fishById($fish_id)){
	if($fish['wiki_title']){
	  $ret = fbLib::getWiki($fish['wiki_title']);
	  $ret['fish_id'] = intval($fish_id);
	  if( ! $ret['error']){
	    $ret['content'] = $ret['content'];
	    $ret['error'] = False;
	    $ret['title'] = $fish['wiki_title'];
	  }
	}
      }else{
	$ret['desc'] = 'Fish not restored';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAAbout(sfWebRequest $request){
    $this->setLayout('empty_layout');
    $this->getResponse()->setHttpHeader('Content-type', 'text/html');
    return $this->renderPartial('about');
  }

  ## params used for new and edit
  private function paramInit(){
    $request = $this->getRequest();
    $param = array('user_id' => $this->user['id']);
    if($request->hasParameter('id')){
      $param['id'] = $request->getParameter('id');
    }
    if($request->hasParameter('name')){
      $param['name'] = $request->getParameter('name');
    }
    if($request->hasParameter('name_sci')){
      $param['name_sci'] = $request->getParameter('name_sci');
    }
    if($request->hasParameter('detail')){
      $param['detail'] = $request->getParameter('detail');
    }
    if($request->hasParameter('alias')){
      $param['alias'] = $request->getParameter('alias');
    }
    return $param;
  }

  public function executeANew(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True, 'status' => 'fail');
    if($this->user['id']){
      $post = $this->paramInit();
      $name = $post['name'];
      $name_sci = $post['name_sci'];
      if( ! $name){
	$ret['desc'] = 'Fish Name not submitted';
      }elseif($fish = Doctrine_Core::getTable('Fish')->fishByName($name)){
	$ret['desc'] = 'Fish Name exists';
      }elseif($fish = Doctrine_Core::getTable('Fish')->addFish($post)){
	$ret['error'] = False;
	$ret['desc'] = 'Insert success';
	$ret['record'] = $fish;
      }else{
	$ret['desc'] = 'Database Insert Failed';
      }
    }else{
      $ret['desc'] = 'User not logged in';
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True, 'status' => 'fail');
    if($this->user['id']){
      $post = $this->paramInit();
      if($post['id']){
	if($fish = Doctrine_Core::getTable('Fish')->getRec($post['id'])){
	  if(  fbLib::isAdmin() or ($fish['username'] == $this->user['username']) ){
	    if($fish = Doctrine_Core::getTable('Fish')->editFish($post)){
	      $ret['error'] = False;
	      $ret['desc'] = 'Fish edit success';
	      $ret['record'] = $fish;
	    }else{
	      $ret['desc'] = 'Database Edit Failed';
	    }
	  }else{
	    $ret['desc'] = 'User not permitted to edit';
	  }
	}else{
	  $ret['desc'] = 'Fish rec not restored';
	}
      }else{
	$ret['desc'] = 'rec id not found';
      }
    }else{
      $ret['desc'] = 'User not logged in';
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }
  
  public function executeAEditGeo(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if($this->user['id']){
      $post = $this->paramInit();
      if($post['id']){
	$geo = $this->fbLib->boundsFromRequest();
	$post['lat'] = $geo['lat'];
	$post['lon'] = $geo['lon'];
	if($post['lat'] and $post['lon']){	
	  if($fish = Doctrine_Core::getTable('Fish')->getRec($post['id'])){
	    if( fbLib::isAdmin() or ($fish['username'] == $this->user['username']) ){
	      if($fish = Doctrine_Core::getTable('Fish')->editFishGeo($post)){
		$ret['error'] = False;
		$ret['desc'] = 'Fish edit geo success';
		$ret['record'] = $fish;
	      }else{
		$ret['desc'] = 'Database Edit Failed';
	      }
	    }else{
	      $ret['desc'] = 'User not permitted to edit';
	    }
	  }else{
	    $ret['desc'] = 'Fish rec not restored';
	  }
	}else{
	  $ret['desc'] = 'lat/lon not found';
	}
      }else{
	$ret['desc'] = 'rec id not found';
      }
    }else{
      $ret['desc'] = 'User not logged in';
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }
  
  public function executeADelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True, 'desc' => 'fail');
    if($this->user['id']){
      $post = $this->paramInit();
      if($post['id']){
	if($fish = Doctrine_Core::getTable('Fish')->getRec($post['id'])){
	  if($fish['username'] == $this->user['username']){
	    if($fish = Doctrine_Core::getTable('Fish')->deleteFish($post)){
	      $ret['error'] = False;
	      $ret['desc'] = 'Fish delete success';
	      $ret['fish_id'] = $post['id'];
	    }else{
	      $ret['desc'] = 'Delete Failed ' . $fish;
	    }
	  }else{
	    $ret['desc'] = 'User not permitted to delete';
	  }
	}else{
	  $ret['desc'] = 'Fish rec not restored';
	}
      }else{
	$ret['desc'] = 'rec id not found';
      }
    }else{
      $ret['desc'] = 'User not logged in';
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }
  
  public function executeASearch(sfWebRequest $request){
    $ret = array('error' => True);
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( $request->hasParameter('name') or $request->hasParameter('name_sci') or $request->hasParameter('alias') ){
      $param = array(array('name' => 'name', 'table' => 'f', 'value' => $request->getParameter('name'), 'type' => 'contain'),
		     array('name' => 'name_sci', 'table' => 'f', 'value' => $request->getParameter('name_sci'), 'type' => 'contain'),
		     array('name' => 'alias', 'table' => 'f', 'value' => $request->getParameter('alias'), 'type' => 'contain')
		     );
      $ret = Doctrine_Core::getTable('Fish')->fishSearch($param);
      if($ret['error']){
	$ret['desc'] = 'database error';	
      }else{
	$ret['error'] = false;
      }
    }else{
      $ret['desc'] = 'No search parameters passed';
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAGetBB(sfWebRequest $req){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = Doctrine_Core::getTable('Fish')->fishBB();
    $json = json_encode($fish);
    return $this->renderText($json);
  }

}
