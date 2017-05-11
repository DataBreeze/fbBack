<?php

/**
 * page actions.
 *
 * @package    fb
 * @subpackage page
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class pageActions extends sfActions {

  const META_KEYWORDS = 'Fishing,fish,fish species,catch fish,find fish,locate fishing spots,fish data,fish species,fish graphs,fish charts';

  public function preExecute(){
    $this->fbLib = new fbLib();
    $this->user = $this->fbLib->restoreUser();
    $this->loc = $this->fbLib->getLoc();
    $this->geo = $this->loc['geo'];
    $this->cfg = array('page' => 'gate');
    $this->footer = array();
  }

  public function initFooter(){
    $this->footer['json_user'] = json_encode($this->fbLib->userSafe($this->user));
    $this->footer['json_cfg'] = json_encode($this->cfg);
    $this->footer['json_loc'] = json_encode($this->loc);
    $this->footer['json_notes'] = json_encode($this->notes);
  }

  public function initPage(){
    $this->notes = Doctrine_Core::getTable('UserNotify')->getNotifies($this->user['id']);
    $this->paramInit();
  }

  ## params used for new and edit
  private function paramInit(){
    $request = $this->getRequest();
    $this->param = array('user_id' => $this->user['id']);
    $this->param['offset'] = 0;
    if($request->hasParameter('offset')){
      $this->param['offset'] = $request->getParameter('offset');
    }
    $this->param['id'] = False;
    if($request->hasParameter('id')){
      $this->param['id'] = $request->getParameter('id');
    }
    if($request->hasParameter('username')){
      $this->param['username'] = $request->getParameter('username');
    }
    if($request->hasParameter('fishname')){
      $fishname = $request->getParameter('fishname');
      $fishname = preg_replace('/\//','',$fishname);
      $this->param['fishname'] = $fishname;
    }
    return $this->param;
  }

  ## this function inserts the current geo into the params so that the by-bounds (BB) sql searches will work  
  private function resetGeoParam($distance = 100){
    $req = sfContext::getInstance()->getRequest();
    $geo = $this->geo;
    $strBounds = $geo['sw_lat'] .','. $geo['sw_lon'] .','. $geo['ne_lat'] .','. $geo['ne_lon'];
    $req->setParameter('bounds',$strBounds);
    $strCenter = $geo['lat'] .','. $geo['lon'];
    $req->setParameter('center',$strCenter);
  }
  
  public $actHash = array('all' => array('key' => 'all', 'host' => 'www', 'name' => 'All Activity', 'namePlural' => 'All Activities',
					 'listLabelsMin' => array('Date','Caption','User'),
					 'listNamesMin' => array('date','caption','username'),
					 'listLabels' => array('Date','Caption','User'),
					 'listNames' => array('date','caption','username'),
					 'showReply' => False, 'showPhoto' => False, 'showFish' => False, 
					 'showMap' => True, 'showNew' => False, 'showEdit' => False,
					 'jsParam' => 'a','keyName' => 'id', 'displayName' => 'caption'
					 ),
			  'photo' => array('key' => 'photo', 'host' => 'photo', 'name' => 'Photo', 'namePlural' => 'Photos',
					   'listLabelsMin' => array('Caption','Date'),
					   'listNamesMin' => array('caption','date_create'),
					   'listLabels' => array('Caption','User','Date'),
					   'listNames' => array('caption','username','date_create'),
					   'detailLabels' => array('Caption','Date','Detail'),
					   'detailNames' => array('caption','date_create','detail'),
					   'showReply' => True, 'showPhoto' => False, 'showFish' => True, 
					   'showMap' => True, 'showNew' => True, 'showEdit' => True,
					   'jsParam' => 'p','keyName' => 'id', 'displayName' => 'id'
					   ),
			  'report' => array('key' => 'catch', 'host' => 'catch', 'name' => 'Catch', 'namePlural' => 'Fish Catches',
					    'listLabelsMin' => array('Fish','Date','Len','Wgt'),
					    'listNamesMin' => array('fish_name','date_catch','length','weight'),
					    'listLabels' => array('User','Fish','Date','Len','Wgt'),
					    'listNames' => array('username','fish_name','date_catch','length','weight'),
					    'detailLabels' => array('Fish','Date','Len','Wgt'),
					    'detailNames' => array('fish_name','date_catch','length','weight'),
					    'showReply' => True, 'showPhoto' => True, 'showFish' => False, 
					    'showMap' => True, 'showNew' => True, 'showEdit' => True,
					    'jsParam' => 'c','keyName' => 'id', 'displayName' => 'caption'
					    ),
			  'blog' => array('key' => 'report', 'host' => 'report', 'name' => 'Report', 'namePlural' => 'Reports',
					  'listLabelsMin' => array('Caption','Date'),
					  'listNamesMin' => array('caption','date_blog'),
					  'listLabels' => array('User','Caption','Date'),
					  'listNames' => array('username','caption','date_blog'),
					  'detailLabels' => array('Caption','Location','Website','Date','Detail'),
					  'detailNames' => array('caption','loc','url','date_blog','content'),
					  'showReply' => True, 'showPhoto' => True, 'showFish' => True, 
					  'showMap' => True, 'showNew' => True, 'showEdit' => True,
					  'jsParam' => 'r','keyName' => 'id', 'displayName' => 'caption'
					  ),
			  'spot' => array('key' => 'spot', 'host' => 'spot', 'name' => 'Spot', 'namePlural' => 'Spots',
					  'listLabelsMin' => array('Caption','Date'),
					  'listNamesMin' => array('caption','date_create'),
					  'listLabels' => array('Caption','User'),
					  'listNames' => array('caption','username'),
					  'detailLabels' => array('Caption','Location','Website','Date','Detail'),
					  'detailNames' => array('caption','loc','url','date_create','content'),
					  'showReply' => True, 'showPhoto' => True, 'showFish' => True, 
					  'showMap' => True, 'showNew' => True, 'showEdit' => True,
					  'jsParam' => 's','keyName' => 'id', 'displayName' => 'caption'
					  ),
			  #'listLabels' => array('Map','Caption','Date'),
			  #'listNames' => array('fbmap','caption','date_create'),
			  'disc' => array('key' => 'disc', 'host' => 'discuss', 'name' => 'Discussion', 'namePlural' => 'Discussions',
					  'listLabelsMin' => array('Caption','Date'),
					  'listNamesMin' => array('caption','date_create'),
					  'listLabels' => array('User','Caption','Date'),
					  'listNames' => array('username','caption','date_create'),
					  'detailLabels' => array('Caption','Location','Date','Detail'),
					  'detailNames' => array('caption','loc','date_create','content'),
					  'showReply' => True, 'showPhoto' => True, 'showFish' => True, 
					  'showMap' => True, 'showNew' => True, 'showEdit' => True,
					  'jsParam' => 'd','keyName' => 'id', 'displayName' => 'caption'
					  ),
			  'user' => array('key' => 'user', 'host' => 'user', 'name' => 'User', 'namePlural' => 'Users',
					  'displayName' => 'username',
					  'listLabelsMin' => array('Username','Joined'),
					  'listNamesMin' => array('username','date_create'),
					  'listLabels' => array('Username','Joined','First','Last'),
					  'listNames' => array('username','date_create','firstname','lastname'),
					  'detailLabels' => array('Username','Location','Website','Join Date','User Type','Detail'),
					  'detailNames' => array('username','location','website','date_create','utype_text','about'),
					  'showReply' => False, 'showPhoto' => False, 'showFish' => True,
					  'showSpot' => True,'showReport' => True,'showBlog' => True,
					  'showDisc' => True,'showGroup' => True,'showPhoto' => True,
					  'showUser' => False,'showMap' => True, 'showNew' => False, 'showEdit' => False,
					  'jsParam' => 'u','keyName' => 'username'
					  ),
			  'group' => array('key' => 'group', 'host' => 'group', 'name' => 'Group', 'namePlural' => 'Groups',
					   'listLabelsMin' => array('Caption','Date'),
					   'listNamesMin' => array('caption','date_create'),
					   'listLabels' => array('Caption','Date'),
					   'listNames' => array('caption','date_create'),
					   'detailLabels' => array('Caption','Location','Website','Date','Detail'),
					   'detailNames' => array('caption','loc','url','date_create','content'),
					   'showReply' => False, 'showPhoto' => False, 'showFish' => True, 
					   'showSpot' => True,'showReport' => True,'showBlog' => True,
					   'showDisc' => True,'showUser' => True,'showPhoto' => True,
					   'showUser' => True, 'showMap' => True, 'showNew' => True, 'showEdit' => True,
					   'jsParam' => 'g','keyName' => 'id','displayName' => 'name'
					   ),
			  'fish' => array('key' => 'fish', 'host' => 'fish', 'name' => 'Fish', 'namePlural' => 'Fish',
					  'listLabelsMin' => array('Name','Scientific'),
					  'listNamesMin' => array('name','name_sci'),
					  'listLabels' => array('Name','Scientific'),
					  'listNames' => array('name','name_sci'),
					  'detailLabels' => array('Name','Alias','Scientific','Rate','Avg Wgt(lbs)','Avg Len(in)'),
					  'detailNames' => array('name','alias','name_sci','count','weight','length'),
					  'showReply' => False, 'showPhoto' => False, 'showFish' => False, 
					  'showSpot' => True,'showReport' => True,'showBlog' => True,
					  'showDisc' => True,'showUser' => True,'showGroup' => True,
					  'showPhoto' => True,'showUser' => True, 'showMap' => False, 'showNew' => False, 
					  'showEdit' => True, 'jsParam' => 'f','keyName' => 'name','displayName' => 'name'
					  ),
			  'reply' => array('key' => 'reply', 'host' => 'map', 'name' => 'Comment', 'namePlural' => 'Comments',
					   'listLabelsMin' => array('Date','Comment'),
					   'listNamesMin' => array('date_create','content'),
					   'listLabels' => array('User','Date','Comment'),
					   'listNames' => array('username','date_create','content'),
					   'detailLabels' => array('Date','Comment'),
					   'detailNames' => array('date_create','comment'),
					   'showReply' => False, 'showPhoto' => False, 'showFish' => False, 
					   'showMap' => True, 'showNew' => True, 
					   'jsParam' => 're','keyName' => 'id', 'displayName' => 'date_create',
					   'parentName' => 'none', 'parentKeyName' => 0
					   ),
			  );

  private function getActivitiesBB($a){
    $this->resetGeoParam();
    $p = array('limit' => 10);
    if($a){
      if($a['limit']){
	$p['limit'] = $a['limit'];
      }
    }
    $act = array();
    $act['photos'] = Doctrine_Core::getTable('File')->getPhotosBBGW($p);
    $act['reports'] = Doctrine_Core::getTable('Report')->getReportsBBGW($p);
    $act['blogs'] = Doctrine_Core::getTable('Blog')->getBlogsBBGW($p);
    $act['spots'] = Doctrine_Core::getTable('Spot')->getSpotsBBGW($p);
    $act['discs'] = Doctrine_Core::getTable('Disc')->getDiscsBBGW($p);
    $act['users'] = Doctrine_Core::getTable('User')->getPubUsersBBGW($p);
    $act['groups'] = Doctrine_Core::getTable('UserGroup')->getGroupsBBGW($p);
    return $act;
  }
  
  private function getActivities($distance){
#    $this->resetGeoParam();
    $act = array();
    $act['photos'] = $this->getPhotos(array('distance'=>$distance,'limit'=>4));
    $act['reports'] = $this->getReports(array('distance'=>$distance,'limit'=>6));
    $act['blogs'] = $this->getBlogs(array('distance'=>$distance,'limit'=>6));
    $act['spots'] = $this->getSpots(array('distance'=>$distance,'limit'=>6));
    $act['discs'] = $this->getDiscs(array('distance'=>$distance,'limit'=>6));
    $act['users'] = $this->getUsers(array('distance'=>$distance,'limit'=>6));
    $act['groups'] = $this->getGroups(array('distance'=>$distance,'limit'=>6));
    return $act;
  }

  private function getPhotos($p){
    $photos = Doctrine_Core::getTable('File')->getPhotosBBGW($p);
    return $photos;
  }

  private function getReports($p){
    $reports = Doctrine_Core::getTable('Report')->getReportsBBGW($p);
    return $reports;
  }

  private function getBlogs($p){
    $blogs = Doctrine_Core::getTable('Blog')->getBlogsBBGW($p);
    return $blogs;
  }

  private function getSpots($p){
    $spots = Doctrine_Core::getTable('Spot')->getSpotsBBGW($p);
    return $spots;
  }

  private function getDiscs($p){
    $discs = Doctrine_Core::getTable('Disc')->getDiscsBBGW($p);
    return $discs;
  }

  private function getUsers($p){
    $users = Doctrine_Core::getTable('User')->getPubUsersBBGW($p);
    return $users;
  }

  private function getGroups($p){
    $groups = Doctrine_Core::getTable('UserGroup')->getGroupsBBGW($p);
    return $groups;
  }

  private function hasSavedBounds(){
    $req = $this->getRequest();
    if($req->getCookie('fbBounds') and $req->getCookie('fbCenter') ){
      return True;
    }
    return False;
  }

  private function getSavedBounds(){
    $req = $this->getRequest();
    $strBounds = $req->getCookie('fbBounds');
    $centerStr = $req->getCookie('fbCenter');
    $loc = $req->getCookie('fbInput');
    $zoom = $req->getCookie('fbZoom');
    $bounds = explode(',',$strBounds);
    $center = explode(',',$centerStr);
    $geo = array( 'lat'  => $center[0], 'lon' => $center[1],
		  'lat1' => $bounds[0], 'lon1' => $bounds[1],
		  'lat2' => $bounds[2], 'lon2' => $bounds[3],
		  'loc' => $loc, 'input' => $loc, 'zoom' => intval($zoom)
		  );
    return $geo;
  }

  private function buildClass($rows){
    $count = count($rows);
    if($count > 0){
      $classCount = 10;
      $modInc = $mod = intval($count/$classCount);
      foreach($rows as $i => $row){
	if($i < $modInc){
	  $rows[$i]['class'] = 'tc' . $classCount;
	}else{
	  $modInc += $mod;
	  $classCount--;
	  $classCount = ($classCount < 1 ? 1 : $classCount);
	  $rows[$i]['class'] = 'tc' . $classCount;
	}
      }      
    }
    return $rows;
  }

  function jrjSortbyName($a,$b){
    if ($a['name'] == $b['name']) {
      return 0;
    }
    return ($a['name'] < $b['name']) ? -1 : 1;
  }
  function jrjSortbyState($a,$b){
    if ($a['state_full'] == $b['state_full']) {
      return 0;
    }
    return ($a['state_full'] < $b['state_full']) ? -1 : 1;
  }
  function jrjSortCity($a,$b){
    if ($a['city'] == $b['city']) {
      return 0;
    }
    return ($a['city'] < $b['city']) ? -1 : 1;
  }

  # calulate the range of current 3 months
# note that the range cannot pass dec, only 1-12 (ex not 12-2)
  private function cur_months(){
    $cur = date('n');
    if($cur == 12){
      $min = 6;
      $max = 12;
    }else if($cur < 6){
      $min = 1;
      $max = 6;
    }else{
      $min = 6;
      $max = 12;
    }
    return array($min,$max);
  }
  
  private function processCatch(){
    $this->cur_month = date('F');
    $cur_months = $this->cur_months();
    $geo = $this->loc['geo'];
    $this->catch = Doctrine_Core::getTable('CatchByMonth')->catch2BB($geo,10);
    $fish_ids = array();
    for($i=0; $i < 5; $i++){
      $fish_ids[$i] = intval($this->catch[$i]['fish_id']);
    }
    $catch_annual = Doctrine_Core::getTable('CatchByMonth')->catchByFishesAnnualBB($geo,$fish_ids);
    $this->footer['json_chart_config'] = json_encode( array('width' => 280,'height' => 200,'legend' => 'bottom','monthMin' => $cur_months[0] -1,'monthMax' => $cur_months[1] - 1) );
    $this->footer['json_catch_annual'] = json_encode($catch_annual);
  }

  private function processCatchOne($fish_id){
    $months = array(1,12);
    $geo = $this->loc['geo'];
    $this->catch = Doctrine_Core::getTable('CatchByMonth')->getCatchByFishIdBB($fish_id,$geo,$months);
    $catch_annual = Doctrine_Core::getTable('CatchByMonth')->getCatchByFishAnnualBB($fish_id,$geo);
    $this->sites_top = Doctrine_Core::getTable('CatchByMonth')->getTopSitesByFishBB($fish_id,$geo);
    $this->cities_top = Doctrine_Core::getTable('CatchByMonth')->getTopCitiesByFishBB($fish_id,$geo);
    $this->footer['json_chart_config'] = json_encode( array('width' => 270,'height' => 180,'legend' => 'top','monthMin' => 0,'monthMax' => 11) );
    $this->footer['json_catch_annual'] = json_encode($catch_annual);
    #$this->footer['json_catch'] = json_encode($this->catch);
    #$this->footer['json_top_city'] = json_encode($this->cities_top);
  }

  private function process_js(){
    if($this->user['id']){
      $js_action = $this->getUser()->getFlash('js_action_login');
      if($js_action){
	$this->js_action = $js_action;
      }
    }
    if(! $js_action){
      $js_action = $this->getUser()->getFlash('js_action');
      if($js_action){
	$this->js_action = $js_action;
      }
    }
  }
  public function executeIndex(sfWebRequest $request){
    $this->initPage();
    $this->activities = $this->fbLib->getActAll(array('limit' => 100));
    $this->actAll = $this->actHash;
    $this->act = $this->actHash['all'];
    $fish_rand = Doctrine_Core::getTable('CatchByFish')->allFish(60);
    $fish_rand = $this->buildClass($fish_rand);
    shuffle($fish_rand);
    $this->fish_rand = $fish_rand;
    $state_rand = Doctrine_Core::getTable('CatchByState')->allStates(50);
    $state_rand = $this->buildClass($state_rand);
    shuffle($state_rand);
    $this->state_rand = $state_rand;
    $city_rand = Doctrine_Core::getTable('CatchByCity')->allCities(35);
    $city_rand = $this->buildClass($city_rand);
    shuffle($city_rand);
    $this->city_rand = $city_rand;
    $this->processCatch();
    $this->states = Doctrine_Core::getTable('CityStateZip')->allStates();
    $this->cfg['head_text'] = 'FishBlab Recreational Fishing Community';
    $this->cfg['pageName'] = 'home';
    $this->areaSelect = Doctrine_Core::getTable('CatchByState')->stateSelect();
    $this->footer['json_area_select'] = json_encode($this->areaSelect);
    $this->initFooter();
  }
  
  ###### START TOP LEVEL
  public function executeAllFish(sfWebRequest $request){
    $this->initPage();
    $this->cfg['page'] = 'allFish';
    $fish_alpha = $fish_rand = Doctrine_Core::getTable('CatchByFish')->allFish(200);
    $this->count = count($fish_alpha);
    $this->count_total = $this->count;
    $fish_rand = $this->buildClass($fish_rand);
    shuffle($fish_rand);
    usort($fish_alpha,array($this,'jrjSortByName'));
    $this->fish_rand = $fish_rand;
    $this->fish_alpha = $fish_alpha;
    $this->cfg['head_text'] = 'FishBlab Saltwater Fish Species';
    $this->initFooter();
  }
  public function executeAllCities(sfWebRequest $request){
    $this->initPage();
    $this->cfg['page'] = 'allCities';
    $city_alpha = $city_rand = Doctrine_Core::getTable('CatchByCity')->allCities(200);
    $this->count = count($city_alpha);
    $this->count_total = $this->count;
    $city_rand = $this->buildClass($city_rand);
    shuffle($city_rand);
    usort($city_alpha,array($this,'jrjSortCity'));
    $this->city_rand = $city_rand;
    $this->city_alpha = $city_alpha;
    $this->cfg['head_text'] = 'Popular Coastal Cities for Fishing';
    $this->initFooter();
  }
  public function executeAllStates(sfWebRequest $request){
    $this->initPage();
    $this->cfg['page'] = 'allStates';
    $this->states = Doctrine_Core::getTable('CityStateZip')->allStates();
    $this->areaSelect = Doctrine_Core::getTable('CatchByState')->stateSelect();
    $this->footer['json_area_select'] = json_encode($this->areaSelect);
    $this->cfg['head_text'] = 'US States';
    $this->initFooter();
  }
  ###### STOP TOP LEVEL

  ###### START 2ND LEVEL
  # one state

  public $state_ok = array('AL' => True, 'AK' => True, 'AZ' => True, 'AR' => True, 'CA' => True, 'CO' => True, 'CT' => True, 'DE' => True, 'FL' => True, 'GA' => True, 'HI' => True, 'ID' => True, 'IL' => True, 'IN' => True, 'IA' => True, 'KS' => True, 'KY' => True, 'LA' => True, 'ME' => True, 'MD' => True, 'MA' => True, 'MI' => True, 'MN' => True, 'MS' => True, 'MO' => True, 'MT' => True, 'NE' => True, 'NV' => True, 'NH' => True, 'NJ' => True, 'NC' => True, 'ND' => True, 'OH' => True, 'OK' => True, 'OR' => True, 'PA' => True, 'RI' => True, 'SC' => True, 'SD' => True, 'TN' => True, 'TX' => True, 'UT' => True, 'VT' => True, 'VA' => True, 'WA' => True, 'WV' => True, 'WI' => True, 'WY' => True);
  
  public function executeState(sfWebRequest $request){
    $this->initPage();
    $this->resetGeoParam();
    $this->activities = $this->fbLib->getActAllBB(array('limit' => 100));
    $this->act = $this->actHash['all'];
    $this->loc['geo']['zoom'] = 6;
    $state = $request->getParameter('state');
    $state = preg_replace('/\//','',$state);
    $this->offset = 0;
    if($request->hasParameter('off')){
      $this->offset = intval($request->getParameter('off'));
    }
    $this->state_param = $state;
    $good = $this->fbLib->stateOk($state);
    if($good){
      $state = $good;
    }else{
      $good = $this->fbLib->stateConvert($state);
      if($good){
	$state = $good;	
      }else{
	$state = 'FL';
      }
    }
    if(preg_match('/^(TX|CA|OR|MT)$/',$state)){
      $this->loc['geo']['zoom'] = 5;  
    }
    $this->geo_path = '/' . $state;
    $this->state = Doctrine_Core::getTable('CatchByState')->state($state);
    $fish_alpha = $fish_rand = Doctrine_Core::getTable('CatchByFishState')->fishByState($state,100);
    $fish_rand = $this->buildClass($fish_rand);
    shuffle($fish_rand);
    $this->fish_rand = $fish_rand;
    $city_rand = Doctrine_Core::getTable('CatchByCityState')->state($state,100);
    $city_rand = $city_weight = $this->buildClass($city_rand);
    shuffle($city_rand);
    $this->city_rand = $city_rand;
    $this->city_weight = $city_weight;
    $this->sites_weight = Doctrine_Core::getTable('CatchBySiteCity')->catchBySiteState($state,100);
    $this->processCatch();
    $markers = array_slice($this->sites_weight,0,200);
    $loc = $this->state['state_full'];
    $this->city_all = Doctrine_Core::getTable('CityStateZip')->allCities($state,$this->offset);
    $response = $this->getResponse();
    $response->addMeta('keywords', pageActions::META_KEYWORDS .','. $loc);
    $response->addMeta('description', 'FishBlab has reports, locations, discussion and analysis for Fishing in ' . $loc . '. Find out what you can catch, where and when in ' . $loc . ' using FishBlab catch data and charts.');
    $response->setTitle('FishBlab reports, locations, discussion and analysis for Fishing in ' . $loc);
    $this->cfg['head_text'] = 'FishBlab Recreational Fishing Community in ' . $loc;
    $this->cfg['pageName'] = 'state';
    $this->initFooter();
  }

  ## one city
  public function executeCity(sfWebRequest $request){
    $this->initPage();
    $this->resetGeoParam();
    $this->activities = $this->fbLib->getActAllBB(array('limit' => 100));
    $this->act = $this->actHash['all'];
    $this->actAll = $this->actHash;
    $this->loc['geo']['zoom'] = 11;
    $state = $request->getParameter('state');
    $city = $request->getParameter('city');
    $state = ( preg_match('/^[A-Z][A-Z]$/',$state) ? $state : NULL);
    $city = ( preg_match('/^[A-Za-z-_\s]+$/',$city) ? $city : NULL);
    if( ($state == NULL) or ($city == NULL) ){
      $state = 'FL';
      $city = 'Tampa';
    }
    $this->geo_path = '/' . $state .'/'. urlencode($city);
    $this->city = Doctrine_Core::getTable('CatchByCityState')->catchByCity($state,$city);
    #$this->activities = $this->getActivities(5000);
    $fish_alpha = $fish_rand = Doctrine_Core::getTable('CatchByFishCity')->fishByCity($state,$city,50);
    $fish_rand = $this->buildClass($fish_rand);
    shuffle($fish_rand);
    $this->fish_rand = $fish_rand;
    $sites_weight = Doctrine_Core::getTable('CatchBySiteCity')->catchBySiteCity($state,$city,50);
    $sites_rand = $this->buildClass($sites_weight);
    shuffle($sites_rand);
    $this->sites_weight = $sites_weight;
    $this->sites_rand = $sites_rand;
    $this->processCatch();
    $this->footer['json_sites'] = json_encode($this->sites_weight);
    $loc = $city .', '. $state;
    $response = $this->getResponse();
    $response->addMeta('keywords', pageActions::META_KEYWORDS .','. $loc);
    $response->addMeta('description', 'FishBlab has reports, locations, discussion and analysis for Fishing in ' . $loc . '. Find out what you can catch, where and when in ' . $loc . ' using FishBlab catch data and charts.');
    $response->setTitle('FishBlab reports, locations, discussion and analysis for Fishing in ' . $loc);
    $this->cfg['head_text'] = 'FishBlab Recreational Fishing Community in ' . $loc;
    $this->initFooter();
  }
  # one site
  public function executeSite(sfWebRequest $request){
    $this->initPage();
    $this->resetGeoParam();
    $this->activities = $this->fbLib->getActAllBB(array('limit' => 100));
    $this->act = $this->actHash['all'];
#    $this->activities = $this->getActivitiesBB();
    $this->actAll = $this->actHash;
    $this->loc['geo']['zoom'] = 10;
    $state = $request->getParameter('state');
    $city = $request->getParameter('city');
    $site = $request->getParameter('site');
    $state = ( preg_match('/^[A-Z][A-Z]$/',$state) ? $state : NULL);
    $city = ( preg_match('/^[A-Za-z-_\s]+$/',$city) ? $city : NULL);
    $site = ( preg_match('/^[A-Za-z-_-\s\'\"]+$/',$site) ? $site : NULL);
    if( ($state == NULL) or ($city == NULL) or ($site == NULL) ){
      $state = 'FL';
      $city = 'Tampa';
      $site = 'Tampa Harbor';
    }
    $this->geo_path = '/' . $state .'/'. urlencode($city);
    $this->site = Doctrine_Core::getTable('CatchBySiteCity')->catchBySite($state,$city,$site);
    $site_id = $this->site['site_id'];
    $fish_weight = Doctrine_Core::getTable('CatchByFishSite')->fishBySite($site_id,100);
    $fish_rand = $this->buildClass($fish_weight);
    shuffle($fish_rand);
    $this->fish_weight = $fish_weight;
    $this->fish_rand = $fish_rand;
    $this->processCatch();
    $this->footer['json_sites'] = json_encode(array($this->site));
    $loc = $site . ' - ' . $city .', '. $state;
    $response = $this->getResponse();
    $response->addMeta('keywords', pageActions::META_KEYWORDS .','. $loc);
    $response->addMeta('description', 'FishBlab has reports, locations, discussion and analysis for Fishing in ' . $loc . '. Find out what you can catch, where and when in ' . $loc . ' using FishBlab catch data and charts.');
    $response->setTitle('FishBlab reports, locations, discussion and analysis for Fishing in ' . $loc);
    $this->cfg['head_text'] = 'FishBlab Recreational Fishing Community in ' . $loc;
    $this->initFooter();
  }
  ## end 2nd level
  ################

  ##### NEW ACTIVITY ###########
  ##### LEVEL 1
  public function executePhotoAll(sfWebRequest $request){
    $this->initPage();
    $this->cfg['page'] = 'allPhotos';
    $this->act = $this->actHash['photo'];
    $this->resetGeoParam();
    $this->recs = $this->getPhotos(array('limit'=>20,'offset'=>$this->param['offset']));
    $this->initFooter();
  }

  public function executePhotoOne(sfWebRequest $request){
    $this->initPage();
    $this->act = $this->actHash['photo'];
    $this->actAll = $this->actHash;
    $this->rec = Doctrine_Core::getTable('File')->getRec($this->param['id']);
    if($this->rec and $this->rec['id']){
      $reply = new fbPhotoReply();
      $this->rec['replies'] = $reply->getReplies($this->rec['id']);
      $fish = new fbPhotoFish();
      $this->rec['fishes'] = $fish->parentFish($this->rec['id']);
    }
    $this->cfg['page'] = 'onePhoto';
    $this->cfg['pageType'] = 'one';
    $this->cfg['actName'] = $this->act['key'];
    $this->cfg['rec_id'] = $this->rec['id'];
    $this->initFooter();
  }

  public function executeReportAll(sfWebRequest $request){
    $this->initPage();
    $this->cfg['page'] = 'allReports';
    $this->act = $this->actHash['report'];
    $this->resetGeoParam();
    $this->recs = $this->getReports(array('limit'=>20,'offset'=>$this->param['offset']));
    $this->setTemplate('activityAll');
    $this->initFooter();
  }

  public function executeReportOne(sfWebRequest $request){
    $this->initPage();
    $this->act = $this->actHash['report'];
    $this->actAll = $this->actHash;
    $this->rec = Doctrine_Core::getTable('Report')->getRec($this->param['id']);
    if($this->rec and $this->rec['id']){
      $reply = new fbReportReply();
      $this->rec['replies'] = $reply->getReplies($this->rec['id']);
      $file = new fbReportFile();
      $this->rec['photos'] = $file->getPhotos($this->rec['id']);
    }
    $this->cfg['page'] = 'oneReport';
    $this->cfg['pageType'] = 'one';
    $this->cfg['actName'] = $this->act['key'];
    $this->cfg['rec_id'] = $this->rec['id'];
    $this->setTemplate('actOne');
    $this->initFooter();
  }

  public function executeBlogAll(sfWebRequest $request){
    $this->initPage();
    $this->cfg['page'] = 'allBlogs';
    $this->cfg['pageType'] = 'one';
    $this->act = $this->actHash['blog'];
    $this->resetGeoParam();
    $this->recs = $this->getBlogs(array('limit'=>20,'offset'=>$this->param['offset']));
    $this->setTemplate('activityAll');
    $this->initFooter();
  }

  public function executeBlogOne(sfWebRequest $request){
    $this->initPage();
    $this->act = $this->actHash['blog'];
    $this->actAll = $this->actHash;
    $this->rec = Doctrine_Core::getTable('Blog')->getRec($this->param['id']);
    if($this->rec and $this->rec['id']){
      $reply = new fbBlogReply();
      $this->rec['replies'] = $reply->getReplies($this->rec['id']);
      $file = new fbBlogFile();
      $this->rec['photos'] = $file->getPhotos($this->rec['id']);
      $fish = new fbBlogFish();
      $this->rec['fishes'] = $fish->parentFish($this->rec['id']);
    }
    $this->cfg['page'] = 'oneBlog';
    $this->cfg['pageType'] = 'one';
    $this->cfg['actName'] = $this->act['key'];
    $this->cfg['rec_id'] = $this->rec['id'];
    $this->cfg['test'] = 'meblog';
    $this->setTemplate('actOne');
    $this->initFooter();
  }

  public function executeSpotAll(sfWebRequest $request){
    $this->initPage();
    $this->cfg['page'] = 'allSpots';
    $this->act = $this->actHash['spot'];
    $this->resetGeoParam();
    $this->recs = $this->getSpots(array('limit'=>100,'offset'=>$this->param['offset']));
    $this->setTemplate('activityAll');
    $this->initFooter();
  }

  public function executeSpotOne(sfWebRequest $request){
    $this->initPage();
    $this->act = $this->actHash['spot'];
    $this->actAll = $this->actHash;
    $this->rec = Doctrine_Core::getTable('Spot')->getRec($this->param['id']);
    if($this->rec and $this->rec['id']){
      $reply = new fbSpotReply();
      $this->rec['replies'] = $reply->getReplies($this->rec['id']);
      $file = new fbSpotFile();
      $this->rec['photos'] = $file->getPhotos($this->rec['id']);
      $fish = new fbSpotFish();
      $this->rec['fishes'] = $fish->parentFish($this->rec['id']);
    }
    $this->cfg['page'] = 'oneSpot';
    $this->cfg['pageType'] = 'one';
    $this->cfg['actName'] = $this->act['key'];
    $this->cfg['rec_id'] = $this->rec['id'];
    $this->setTemplate('actOne');
    $this->initFooter();
  }

  public function executeDiscAll(sfWebRequest $request){
    $this->initPage();
    $this->cfg['page'] = 'allDiscs';
    $this->act = $this->actHash['disc'];
    $this->resetGeoParam();
    $this->recs = $this->getDiscs(array('limit'=>20,'offset'=>$this->param['offset']));
    $this->setTemplate('activityAll');
    $this->initFooter();
  }

  public function executeDiscOne(sfWebRequest $request){
    $this->initPage();
    $this->act = $this->actHash['disc'];
    $this->actAll = $this->actHash;
    $this->rec = Doctrine_Core::getTable('Disc')->getRec($this->param['id']);
    if($this->rec and $this->rec['id']){
      $reply = new fbDiscReply();
      $this->rec['replies'] = $reply->getReplies($this->rec['id']);
      $file = new fbDiscFile();
      $this->rec['photos'] = $file->getPhotos($this->rec['id']);
      $fish = new fbDiscFish();
      $this->rec['fishes'] = $fish->parentFish($this->rec['id']);
    }
    $this->setTemplate('actOne');
    $this->cfg['page'] = 'oneDisc';
    $this->cfg['pageType'] = 'one';
    $this->cfg['actName'] = $this->act['key'];
    $this->cfg['rec_id'] = $this->rec['id'];
    $this->initFooter();
  }

  public function executeUserAll(sfWebRequest $request){
    $this->initPage();
    $this->cfg['page'] = 'allUsers';
    $this->act = $this->actHash['user'];
    $this->resetGeoParam();
    $this->recs = $this->getUsers(array('limit'=>20,'offset'=>$this->param['offset']));
    $this->setTemplate('activityAll');
    $this->initFooter();
  }

  public function executeUserOne(sfWebRequest $request){
    $this->inactive = False;
    $this->initPage();
    $this->cfg['page'] = 'oneUser';
    $this->cfg['pageType'] = 'one';
    $this->act = $this->actHash['user'];
    $this->actAll = $this->actHash;
    $user = Doctrine_Core::getTable('User')->getUserGW($this->param['username']);
    if($user){
      $this->rec = $user;
      $fish = new fbUserFish();
      $this->rec['fishes'] = $fish->parentFish($this->rec['id']);
    }else{
      if($user_new = Doctrine_Core::getTable('User')->adminUserByUsername($this->param['username'])){
	if($user_new['fb_status'] = 1500){
	  $this->user_new = $user_new;
	  ## deactivated user found
	  $this->code = $request->getParameter('code');
	  $this->sent = False;
	  $this->setTemplate('userActivate');
	  $this->mustLogOut = False;
	  $this->status = 'This User has not been activated';
	  if($this->user['id']){
	    ## user is currently logged in - must logout
	    $this->mustLogOut = True;
	    $this->status = 'You must log out to activate this account';
	  }elseif($this->code){
	    ## at this point, the user is inactive and was created in the admin interface for promos
	    ## code was passed in url or form submission
	    $p = array('promo_user_id' => $user_new['id'],'code' => $this->code);
	    $this->status = 'puid:'.$p['promo_user_id'].' code:'.$p['code'];
	    if($sent = Doctrine_Core::getTable('PromoSent')->sentByUserAndCode($p)){
	      $this->sent = $sent;
	      $this->status = 'ACTIVATE SUCCESS! ' . $user_new['id'];
	      $this->user = $this->fbLib->resetUser($user_new['id']);
	      #$this->cfg['jrjInitSub'] = 'ownerEditPass';
	      $this->footer['json_js_init'] = 'ownerEditPass();';
#	      $this->redirect('http://user.fishblab.com/' . urlencode($user_new['username']));
	    }else{
	      $this->status = 'The Promotion for this user was not found';
	    }
	  }else{
	    $this->status = 'User Activation code not found';
	  }
	}
      }
    }
    $this->cfg['page'] = 'oneUser';
    $this->cfg['pageType'] = 'one';
    $this->cfg['actName'] = $this->act['key'];
    $this->cfg['rec_id'] = $this->rec['username'];
    $this->initFooter();
  }

  public function executeGroupAll(sfWebRequest $request){
    $this->initPage();
    $this->cfg['page'] = 'allGroups';
    $this->act = $this->actHash['group'];
    $this->resetGeoParam();
    $this->recs = $this->getGroups(array('limit'=>20,'offset'=>$this->param['offset']));
    $this->setTemplate('activityAll');
    $this->initFooter();
  }

  public function executeGroupOne(sfWebRequest $request){
    $this->initPage();
    $this->act = $this->actHash['group'];
    $this->actAll = $this->actHash;
    $this->rec = Doctrine_Core::getTable('UserGroup')->groupByIdGW($this->param['id']);
    if($this->rec and $this->rec['id']){
      $fish = new fbGroupFish();
      $this->rec['fishes'] = $fish->parentFish($this->rec['id']);
    }
    $this->cfg['page'] = 'oneGroup';
    $this->cfg['pageType'] = 'one';
    $this->cfg['actName'] = $this->act['key'];
    $this->cfg['rec_id'] = $this->rec['id'];
    $this->initFooter();
  }

  public function executeFishAll(sfWebRequest $request){
    $this->initPage();
    $this->cfg['page'] = 'allFish';
    $this->act = $this->actHash['fish'];
    $this->resetGeoParam();
    $offset = 0;
    if($this->param['offset']){
      $offset = $this->param['offset'];
    }
    $p = array('limit' => 100,'offset' => $offset);
    if($this->loc['state']){
      $p['state'] = $this->loc['state'];
      $this->recs = Doctrine_Core::getTable('CatchByFishState')->fishByState2($p);
    }else{
      $this->recs = Doctrine_Core::getTable('CatchByFish')->fishAll($p);
    }
    $this->initFooter();
  }

  public function executeFishOne(sfWebRequest $request){
    $this->initPage();
    $this->act = $this->actHash['fish'];
    $this->actAll = $this->actHash;
    $this->rec = Doctrine_Core::getTable('Fish')->fishByName($this->param['fishname']);
    $this->rec['wiki_text'] = False;
    $wiki_title = $this->rec['wiki_title'];
    if(False ){ #$wiki_title){
      $result = fbLib::getWiki($wiki_title);
      if( ! $result['error']){
	$this->rec['wiki_text'] = $result['content'];
      }
    }
    $detail = $this->rec['detail'];
    $this->rec['detail'] = preg_replace('/\n/','<br />',$detail);
    $this->cfg['page'] = 'oneFish';
    $this->cfg['pageType'] = 'one';
    $this->processCatchOne($this->rec['id']);
    $this->initFooter();
  }

  ##### END NEW ACTIVITY
  
  public function executeAPage(sfWebRequest $request){
    $this->setLayout('empty_layout');
    $this->getResponse()->setHttpHeader('Content-type', 'text/html');
    $page_name = $request->getParameter('pageName');
    if($page_name == 'fish_data'){
      return $this->renderPartial('page/fish_data');
    }elseif($page_name == 'corporate'){
      return $this->renderPartial('page/corporate');
    }elseif($page_name == 'contact'){
      return $this->renderPartial('page/contact');
    }elseif($page_name == 'terms'){
      return $this->renderPartial('page/terms');
    }elseif($page_name == 'privacy'){
      return $this->renderPartial('page/privacy');
    }else{
      return $this->renderPartial('page/fishblab');
    }
  }

}
