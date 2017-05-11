<?php

/**
 * report actions.
 *
 * @package    fb
 * @subpackage report
 * @author     Joe Junkin
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reportActions extends sfActions{
  const MAX_REPORT = 20;
  
  public function preExecute(){
    $this->fbLib = new fbLib($this->getRequest());
    $this->user = $this->fbLib->restoreUser();
  }
  
  public function executeIndex(sfWebRequest $request){
    $this->guest = True;
    $loc = $this->fbLib->getLoc();
    $geo = $loc['geo'];
    $this->geo = $geo;
    $this->json_geo = json_encode($loc);
    $month_range = $this->fbLib->getMonthRange();
    $recStat = $this->fbLib->urlRecStat();
    $this->report = False;
    if($recStat){
      $rec = Doctrine_Core::getTable('Report')->getRecAllow($recStat['id']);
      if($rec){
	$recs = array($rec);
	$this->report = array('lock' => True, 'count_total' => 1, 'count'=>1, 'records' => $recs, 'record_offset' => 0, 'record_limit' => ReportTable::REPORT_RECORD_LIMIT);
	$this->json_rec_stat = json_encode($recStat);
      }
    }
    if(! $this->report){
      $this->report = Doctrine_Core::getTable('Report')->getReportsBB();
    }
    $this->notes = Doctrine_Core::getTable('UserNotify')->getNotifies($this->user['id']);
    $this->json_reports = json_encode($this->report);
    $this->json_notes = json_encode($this->notes);
    $this->getResponse()->setTitle('FishBlab Exploring All Fish Catch Reports In ' . $geo['input']);
    $this->cfg = array();
    $this->cfg['head_text'] = 'Local Fish Catch Reports';
    $this->cfg['page'] = 'catch';
    $cfg_js = array('page' => 'catch');
    $this->json_cfg = json_encode($cfg_js);
    $this->json_user = json_encode($this->fbLib->userSafe($this->user));
    $this->param = array('geo' => $this->geo, 'user' => $this->user, 'data' => $this->report, 'template' => 'global/reports');
  }

  ## params used for new and edit
  private function paramInit(){
    $request = $this->getRequest();
    $param = array('user_id' => $this->user['id']);
    if($request->hasParameter('sec')){
      $param['sec'] = $request->getParameter('sec');
      if($param['sec'] == fbLib::SEC_GROUP){
	$param['group_id'] = intval($request->getParameter('group_id'));
      }
    }
    if($request->hasParameter('fish_name')){
      $param['fish_name'] = $request->getParameter('fish_name');
    }
    if($request->hasParameter('content')){
      $param['content'] = $request->getParameter('content');
    }
    if($request->hasParameter('caption')){
      $param['caption'] = $request->getParameter('caption');
    }
    if($request->hasParameter('length')){
      $param['length'] = $request->getParameter('length');
    }
    if($request->hasParameter('weight')){
      $param['weight'] = $request->getParameter('weight');
    }
    if($request->hasParameter('count')){
      $param['count'] = $request->getParameter('count');
    }
    if($request->hasParameter('loc')){
      $param['loc'] = $request->getParameter('loc');
    }
    $strDate = $request->getParameter('date_catch');
    if($strDate){
      $tsDate = strtotime($strDate);
      if($tsDate){
	$myDate = getdate($tsDate);
	$param['date_catch'] = $myDate['year'] .'-'. $myDate['mon'] .'-'. $myDate['mday'];
      }
    }
    $hour = $request->getParameter('hour');
    if($hour){
      $param['date_catch'] .= ' ' . $hour . ':00:00';
    }
    return $param;
  }

  public function executeAReportPost(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True, 'status' => 'fail');
    if($this->user['id']){
      $post = $this->paramInit();
      $post['geo'] = $this->fbLib->boundsFromRequest();
      $report = Doctrine_Core::getTable('Report')->addReport($post);    
      $report_id = $report['id'];
      if($report_id > 0){
	$ret['status'] = 'ok';
	$ret['error'] = False;
	$ret['report_id'] = $report_id;
	$ret['record'] = $report;
      }else{
	$ret['desc'] = 'Database Insert Failed';
      }
    }else{
      $ret['desc'] = 'User not logged in';
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  private function validateEdit(){
    $ret = array('error' => True);
    $request = $this->getRequest();
    if($request->hasParameter('id')){
      $report_id = intval($request->getParameter('id'));
      if($user_id = $this->user['id']){
	if($user = $this->user){
	  if($report = Doctrine_Core::getTable('Report')->getReport($report_id)){
	    if($report['username'] == $user['username']){
	      $ret['error'] = False;
	      $ret['record'] = $report;
	    }else{
	      $ret['desc'] = 'Edit Failed: You are not the owner of this Catch Report';
	    }
	  }else{
	    $ret['desc'] = 'Report not found in DB';
	  }
	}else{
	  $ret['desc'] = 'User not found in DB';
	}
      }else{
	$ret['desc'] = 'User not Logged in';
      }
    }else{
      $ret['desc'] = 'Report ID parameter not found';
    }
    return $ret;
  }
  
  public function executeAReportUpdate(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = $this->validateEdit();
    if( ! $ret['error']){
      $post = $this->paramInit();
      $post['report_id'] = intval($request->getParameter('id'));
      if($report = Doctrine_Core::getTable('Report')->updateReport($post)){
	$ret['error'] = False;
	$ret['report_id'] = $report['id'];
	$ret['record'] = $report;
      }else{
	$ret['error_desc'] = 'report_update_failed';
	$ret['desc'] = 'Report Update failed due to system error';		
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAReportEditGeo(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = $this->validateEdit();
    if( ! $ret['error']){
      $post = array('user_id' => $this->user['id']);
      $post['report_id'] = intval($request->getParameter('id'));
      $post['geo'] = $this->fbLib->boundsFromRequest();
      if($report = Doctrine_Core::getTable('Report')->editGeo($post)){
	$ret['error'] = False;
	$ret['report_id'] = $report_id;
	$ret['record'] = $report;
      }else{
	$ret['error_desc'] = 'report_update_failed';
	$ret['desc'] = 'Report Update failed due to system error';		
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }
  
  public function executeADeleteReport(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = $this->validateEdit();
    if( ! $ret['error']){
      $report_id = intval($request->getParameter('id'));
      $ret['delete_status'] = Doctrine_Core::getTable('Report')->deleteReport($report_id);
      $ret['error'] = False;
      $ret['desc'] = 'Report deleted';
      $ret['id'] = $report_id;
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }
  
  ## get a report for editing
  public function executeAGetReport(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if($report_id = intval($request->getParameter('id')) ){
      if($report = Doctrine_Core::getTable('Report')->getReport($report_id)){
	$ret['error'] = False;
	$ret['report_id'] = $report_id;
	$ret['record'] = $report;
	$ret['desc'] = 'Report found';
      }else{
	$ret['desc'] = 'Report not restored';	
      }
    }else{
      $ret['desc'] = 'ReportId not found';
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeAReportSearch(sfWebRequest $request){
    $ret = array('error' => True);
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( $request->hasParameter('caption') or $request->hasParameter('fish_name') or $request->hasParameter('content') ){
      $param = array(array('name' => 'caption', 'table' => 'r', 'value' => $request->getParameter('caption'), 'type' => 'contain'),
		     array('name' => 'fish_name', 'table' => 'r', 'value' => $request->getParameter('fish_name'), 'type' => 'start'),
		     array('name' => 'content', 'table' => 'r', 'value' => $request->getParameter('content'), 'type' => 'contain')
		     );
      $ret = Doctrine_Core::getTable('Report')->reportSearch($param);
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
  
  public function executeAFetchReportsBB(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $offset = $request->getParameter('reportOffset');
    if(! $offset){
      $offset = intval($request->getParameter('offset'));
    }
    if(! $offset){
      $offset = 0;
    }
    $recs = Doctrine_Core::getTable('Report')->getReportsBB($offset);
    $json = json_encode($recs);
    return $this->renderText($json);
  }

  public function executeAFlagReport(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    $report_id = intval($request->getParameter('id'));
    $flag_id = intval($request->getParameter('flag'));
    if($report_id <= 0){
      $ret['error_desc'] = 'report_id_not_found_parameter';
      $ret['desc'] = 'ReportId parameter not found';
    }elseif($flag_id <= 0){
      $ret['error_desc'] = 'flag_not_found_parameter';
      $ret['desc'] = 'Flag parameter not found';
    }else{
      $report_id = $request->getParameter('id');
      $ret['report_id'] = intval($report_id);
      $ret['flag'] = intval($flag_id);
      if($user_id = $this->getUser()->getAttribute('fbUserId')){
	if($user = Doctrine_Core::getTable('User')->getUserById($user_id)){
	  if($report = Doctrine_Core::getTable('Report')->getReport($report_id)){
	    $ret['error'] = False;
	    if($flag = Doctrine_Core::getTable('ReportForFlag')->getReportFlag($report_id,$user_id)){
              # flag exists, update
	      $flag_new = Doctrine_Core::getTable('ReportForFlag')->updateReportFlag($report_id,$user_id,$flag_id);
	    }else{
	      # create a new flag
	      $flag_new = Doctrine_Core::getTable('ReportForFlag')->addReportFlag($report_id,$user_id,$flag_id);
	    }
	  }else{
	    $ret['error_desc'] = 'report_not_found_db';
	    $ret['desc'] = 'Report not found';
	  }
	}else{
	  $ret['error_desc'] = 'user_not_found_db';
	  $ret['desc'] = 'User not Found';
	}
      }else{
	$ret['error_desc'] = 'user_not_logged_in';
	$ret['desc'] = 'User not Logged in';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeUpload(sfWebRequest $request){
    $file = new fbReportFile();
    $file->create();
    $this->json = $file->jsonResponse();
    $this->setTemplate(sfConfig::get('sf_app_template_dir').DIRECTORY_SEPARATOR . 'uploadResponse');
  }

  public function executeAPhotoGetAll(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $file = new fbReportFile();
    $file->getPhotosAjax();
    return $this->renderText($file->jsonResponse());
  }

  public function executeAPhotoEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $file = new fbReportFile();
    $file->edit();
    return $this->renderText($file->jsonResponse());
  }

  public function executeAPhotoDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $file = new fbReportFile();
    $file->delete();
    return $this->renderText($file->jsonResponse());
  }

  ## ajax reply calls
  public function executeAReplyGetAll(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbReportReply();
    $reply->getRepliesAjax();
    return $this->renderText($reply->jsonResponse());
  }

  public function executeAReplyPost(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbReportReply();
    $reply->create();
    return $this->renderText($reply->jsonResponse());
  }

  public function executeAReplyEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbReportReply();
    $reply->edit();
    return $this->renderText($reply->jsonResponse());
  }

  public function executeAReplyDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbReportReply();
    $reply->delete();
    return $this->renderText($reply->jsonResponse());
  }

  ## ajax fish calls
  public function executeAFishGetAll(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbReportFish();
    $fish->parentFishAjax();
    return $this->renderText($fish->jsonResponse());
  }

  public function executeAFishPost(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbReportFish();
    $fish->create();
    return $this->renderText($fish->jsonResponse());
  }  
  public function executeAFishEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbReportFish();
    $fish->edit();
    return $this->renderText($fish->jsonResponse());
  }

  public function executeAFishDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbReportFish();
    $fish->delete();
    return $this->renderText($fish->jsonResponse());
  }
  
}
