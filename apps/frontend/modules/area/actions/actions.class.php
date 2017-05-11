<?php

  /**
   * area actions.
   *
   * @package    fb
   * @subpackage area
   * @author     JRJ
   * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
   */
class areaActions extends sfActions{
  const MAX_SELECT_FISH = 6;
  const META_KEYWORDS = 'Fishing,saltwater fish,saltwater fish species,fishing sites,fishing spots,find fish,fish graphs,fish charts';  

  public function preExecute(){
    $this->fbLib = new fbLib($this->getRequest());
  }
  
  /* given a list of site objects, create and ret array of site id's */
  private function siteIdArray($sites){
    $list = array();
    foreach ($sites as $i => $site){
      $list[$i] = $site['id'];
    }
    return $list;
  }
  /* given a list of catches build a list of fish ids */
  private function defaultFishIdArray($catches){
    $list = array();
    $limit = (count($catches) > 4 ? 4 : count($catches));
    for($i=0; $i<$limit; $i++){
      array_push($list,$catches[$i]['fish_id']);
    }
    return $list;
  }
  
  public function executeIndex(sfWebRequest $request){
    $this->user = $this->fbLib->restoreUser();
    $this->guest = True;
    $site_limit = $request->getCookie('fbSiteLimit');
    $loc = $this->fbLib->getLoc();
    $geo = $loc['geo'];
    $this->geo = $geo;
    $this->month_range = $this->fbLib->getMonthRange();
    $this->zoom = 10;
    $sites = Doctrine_Core::getTable('Site')->getSitesBB($geo,$site_limit);
    $disc = Doctrine_Core::getTable('Disc')->getDiscsBB($geo,$this->month_range);
    $site_ids = $this->siteIdArray($sites);
    $catch = Doctrine_Core::getTable('CatchByMonth')->getCatchByMonthAndSites($site_ids,$this->month_range);
    $catch_annual = array();
    $this->saved_catch = True;
    $str = $request->getCookie('fbSelectFish');
    $fish_ids = array();
    if ( ! empty($str) ){
      $fish_ids = explode(',',$str);
      if(count($fish_ids) > 0){
	for($i=0; $i < count($fish_ids); $i++){
	  $fish_ids[$i] = intval($fish_ids[$i]);
	}
	$catch_annual = Doctrine_Core::getTable('CatchByMonth')->catchAnnual($fish_ids,$site_ids);
      }
    }
    # if there were no stored cookie fish or they were not in the current sites, fetch fish in cur sites
    if( ( count($catch_annual) == 0 ) and (count($catch) > 0) ){
      $this->saved_catch = False;
      $fish_ids = $this->defaultFishIdArray($catch);
      $catch_annual = Doctrine_Core::getTable('CatchByMonth')->catchAnnual($fish_ids,$site_ids);
    }
    $cbn = array();
    if(count($catch) > 0){
      foreach($catch as $fish){
	$cbn[$fish['fish_id']] = $fish['name'];
      }
      asort($cbn);
    }
    $this->catch_sort_by_name = $cbn;
    $this->fish = array('name' => '');
    $this->sites = $sites;
    $this->catch = $catch;
    $this->catch_annual = $catch_annual;
    $this->notes = Doctrine_Core::getTable('UserNotify')->getNotifies($this->user['id']);
    $this->json_notes = json_encode($this->notes);
    $this->json_geo = json_encode($loc);
    $this->json_fish_ids = json_encode($fish_ids);
    $this->json_sites = json_encode($sites);
    $this->json_catch = json_encode($catch);
    $this->json_catch_annual = json_encode($catch_annual);
    $this->json_discuss = json_encode($disc);
    $this->discuss = $disc;
    $area = $geo['input'];
    $response = $this->getResponse();
    $response->addMeta('keywords', areaActions::META_KEYWORDS .','. $area);
    $response->addMeta('description', 'Saltwater Fishing Spots and Fish Species: Find Coastal Fishing Sites/Spots in ' . $area . ' and explore the POpular Fish Species caught in the area.');
    $response->setTitle('Explore Marine Fishing Spots and Fish Species in ' . $area);
    $this->cfg = array();
    $this->cfg['head_text'] = 'Local Saltwater Fishing Spots and Fish Species';
    $this->cfg['page'] = 'area';
    $this->cfg['about_onclick'] = 'areaAbout();';
    $cfg_js = array('page' => 'area');
    $this->json_cfg = json_encode($cfg_js);
    $this->json_user = json_encode($this->fbLib->userSafe($this->user));
    $this->alert = array('text' => 'Saltwater Spots and Fish only!', 'id' => 'alertDataArea');
    $this->msg = array('text' => 'Saltwater Spots and Fish Species in Map', 'id' => 'msgArea','about_onclick' => 'areaAbout();');
  }
  
  # this is the first ajax call to retreive the sites given a lat/lon
  public function executeAFetchSites(sfWebRequest $request){
    $this->setLayout(null);
    $loc = $this->fbLib->getLoc($request);
    $geo = $loc['geo'];
    $site_limit = $request->getCookie('fbSiteLimit');
    $sites = Doctrine_Core::getTable('Site')->getSites($geo['lat'],$geo['lon'],$site_limit);
    $json = json_encode($sites);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    return $this->renderText($json);
  }

  # after a googlemap change event, call this ajax function with bounds
  public function executeAFetchSitesBB(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( !$request->hasParameter('bounds') or !$request->hasParameter('center') ){
      $json = 'bounds not found';
      return $this->renderText($json);
    }
    $geo = $this->fbLib->boundsFromRequest();
    $limit = (int)$request->getParameter('siteLimit');
    if( ($limit < 10) or ($limit > 100) ){
      $limit = (int)$request->getCookie('fbSiteLimit');
      if( ($limit < 10) or ($limit > 100) ){
	$limit = 20;
      }
    }
    $sites = Doctrine_Core::getTable('Site')->getSitesBB($geo,$limit);
    $json = json_encode($sites);
    # save the list of site ID's for the next ajax call for the fish catch statistics
    $site_ids = $this->siteIdArray($sites);
    return $this->renderText($json);
  }

  # this is the second ajax call to retreive the fish stats given a list of site ids
  public function executeAFetchCatch(sfWebRequest $request){
    $this->month_range = $this->fbLib->getMonthRange();
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $sitestr = $request->getParameter('siteIDs');
    $site_ids = explode(',',$sitestr);
    $catch = Doctrine_Core::getTable('CatchByMonth')->getCatchByMonthAndSites($site_ids,$this->month_range);
    $json = json_encode($catch);
    return $this->renderText($json);
  }

  # ajax call to fetch annual monthly catch data by species for chart 
  public function executeAFetchCatchAnnual(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $id_str = $request->getParameter('fishList');
    $fish_ids = explode(',',$id_str);
    $sitestr = $request->getParameter('siteIDs');
    $site_ids = explode(',',$sitestr);
    $catch_annual = Doctrine_Core::getTable('CatchByMonth')->catchAnnual($fish_ids,$site_ids);
    if(count($catch_annual) == 0){
      $this->month_range = $this->fbLib->getMonthRange();
      $catch = Doctrine_Core::getTable('CatchByMonth')->getCatchByMonthAndSites($site_ids,$this->month_range);
      $fish_ids = $this->defaultFishIdArray($catch);
      $catch_annual = Doctrine_Core::getTable('CatchByMonth')->catchAnnual($fish_ids,$site_ids);
    }
    $json = json_encode($catch_annual);
    return $this->renderText($json);
  }

  public function executeAAbout(sfWebRequest $request){
    $this->setLayout('empty_layout');
    $this->getResponse()->setHttpHeader('Content-type', 'text/html');
    return $this->renderPartial('about');
  }
  
}
