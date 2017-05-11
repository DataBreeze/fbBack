<?php

/**
 * spot actions.
 *
 * @package    fb
 * @subpackage spot
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class spotActions extends sfActions{

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
    $this->spot = False;
    if($recStat){
      $rec = Doctrine_Core::getTable('Spot')->getRecAllow($recStat['id']);
      if($rec){
	$recs = array($rec);
	$this->spot = array('lock' => True, 'count_total' => 1, 'count'=>1, 'records' => $recs, 'record_offset' => 0, 'record_limit' => SpotTable::SPOT_RECORD_LIMIT);
	$this->json_rec_stat = json_encode($recStat);
      }
    }
    if(! $this->spot){
      $this->spot = Doctrine_Core::getTable('Spot')->getSpotsBB();
    }
    $this->notes = Doctrine_Core::getTable('UserNotify')->getNotifies($this->user['id']);
    $this->json_spots = json_encode($this->spot);
    $this->json_notes = json_encode($this->notes);
    $this->getResponse()->setTitle('FishBlab Exploring All Fishing Spots In ' . $geo['input']);
    $this->cfg = array();
    $this->cfg['head_text'] = 'Local Fishing Spots';
    $this->cfg['page'] = 'spot';
    $cfg_js = array('page' => 'spot');
    $this->json_cfg = json_encode($cfg_js);
    $this->json_user = json_encode($this->fbLib->userSafe($this->user));
    $this->param = array('template' => 'global/spots', 'geo' => $this->geo, 'user' => $this->user, 'data' => $this->spot); 
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
    if($request->hasParameter('sec_photo')){
      $param['sec_photo'] = $request->getParameter('sec_photo');
    }
    if($request->hasParameter('content')){
      $param['content'] = $request->getParameter('content');
    }
    if($request->hasParameter('caption')){
      $param['caption'] = $request->getParameter('caption');
    }
    if($request->hasParameter('loc')){
      $param['loc'] = $request->getParameter('loc');
    }
    if($request->hasParameter('url')){
      $param['url'] = $request->getParameter('url');
    }
    if($request->hasParameter('url_caption')){
      $param['url_caption'] = $request->getParameter('url_caption');
    }
    return $param;
  }

  public function executeASpotPost(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True, 'status' => 'fail');
    if($this->user['id']){
      $post = $this->paramInit();
      $post['geo'] = $this->fbLib->boundsFromRequest();
      $spot = Doctrine_Core::getTable('Spot')->addSpot($post);    
      $spot_id = $spot['id'];
      if($spot_id > 0){
	$ret['error'] = False;
	$ret['status'] = 'ok';
	$ret['spot_id'] = $spot_id;
	$ret['record'] = $spot;
      }else{
	$ret['desc'] = 'Database Insert Failed';
      }
    }else{
      $ret['desc'] = 'UserId not found';
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  ## validate the params and that user can edit this spot
  public function validateEdit(){
    $request = $this->getRequest();
    $ret = array('error' => True);
    if($request->hasParameter('id')){
      $spot_id = intval($request->getParameter('id'));
      if($this->user['id']){
	if($user = $this->user){
	  if($spot = Doctrine_Core::getTable('Spot')->getSpot($spot_id)){
	    if($spot['username'] == $user['username']){
	      $ret['record'] = $spot;
	      $ret['error'] = False;
	    }else{
	      $ret['error_desc'] = 'spot_not_owner';
	      $ret['desc'] = 'Edit failed: User is not the owner';
	    }
	  }else{
	    $ret['error_desc'] = 'spot_not_found_db';
	    $ret['desc'] = 'Spot not found';
	  }
	}else{
	  $ret['error_desc'] = 'user_not_found_db';
	  $ret['desc'] = 'User not Found';
	}
      }else{
	$ret['error_desc'] = 'user_not_logged_in';
	$ret['desc'] = 'User not Logged in';
      }
    }else{
      $ret['error_desc'] = 'spot_id_not_found_parameter';
      $ret['desc'] = 'SpotId parameter not found';
    }
    return $ret;
  }

  public function executeASpotUpdate(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = $this->validateEdit();
    if(! $ret['error']){
      $post = $this->paramInit();
      $post['spot_id'] = intval($request->getParameter('id'));
      if($spot = Doctrine_Core::getTable('Spot')->updateSpot($post)){
	$ret['status'] = 'ok';
	$ret['error'] = False;
	$ret['spot_id'] = $spot_id;
	$ret['record'] = $spot;
      }else{
	$ret['error'] = True;
	$ret['error_desc'] = 'spot_update_failed';
	$ret['desc'] = 'Spot Update failed due to system error';		
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }
  
  public function executeASpotEditGeo(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = $this->validateEdit();
    if(! $ret['error']){
      $post = array('user_id' => $this->user['id']);
      $post['spot_id'] = intval($request->getParameter('id'));
      $post['geo'] = $this->fbLib->boundsFromRequest();
      if($spot = Doctrine_Core::getTable('Spot')->editGeo($post)){
	$ret['error'] = False;	  
	$ret['spot_id'] = $spot_id;
	$ret['record'] = $spot;
      }else{
	$ret['error'] = True;
	$ret['error_desc'] = 'spot_update_failed';
	$ret['desc'] = 'Spot Update failed due to system error';		
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAFetchSpotsBB(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $offset = $request->getParameter('spotOffset');
    if(! $offset){
      $offset = intval($request->getParameter('offset'));
    }
    if(! $offset){
      $offset = 0;
    }
    $month_range = $this->fbLib->getMonthRange();
    $geo = $this->fbLib->boundsFromRequest();
    $recs = Doctrine_Core::getTable('Spot')->getSpotsBB($offset);
    $json = json_encode($recs);
    return $this->renderText($json);
  }
  
  public function executeADeleteSpot(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = $this->validateEdit();
    if(! $ret['error']){
      $ret['error'] = True;
      $spot_id = intval($request->getParameter('id'));
      $spot = Doctrine_Core::getTable('Spot')->getSpot($spot_id);
      if($spot['reply_count'] > 0){
	$ret['desc'] = 'Spot has comments - cannot be deleted';
      }elseif($spot['photo_count'] > 0){
	$ret['desc'] = 'Spot has photos - cannot be deleted';
      }else{
	$ret['delete_status'] = Doctrine_Core::getTable('Spot')->deleteSpot($spot_id);
	$ret['error'] = False;
	$ret['desc'] = 'Spot deleted';
	$ret['id'] = $spot_id;
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }
  
  public function executeAFlagSpot(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    $spot_id = intval($request->getParameter('id'));
    $flag_id = intval($request->getParameter('flag'));
    if($spot_id <= 0){
      $ret['error_desc'] = 'spot_id_not_found_parameter';
      $ret['desc'] = 'SpotId parameter not found';
    }elseif($flag_id <= 0){
      $ret['error_desc'] = 'flag_not_found_parameter';
      $ret['desc'] = 'Flag parameter not found';
    }else{
      $ret['spot_id'] = $spot_id;
      $ret['flag'] = $flag_id;
      if($user_id = $this->getUser()->getAttribute('fbUserId')){
	if($user = Doctrine_Core::getTable('User')->getUserById($user_id)){
	  if($spot = Doctrine_Core::getTable('Spot')->getSpot($spot_id)){
	    $ret['error'] = false;
	    if($flag = Doctrine_Core::getTable('SpotForFlag')->getSpotFlag($spot_id,$user_id)){
	      ## flag exists, update
	      $flag_new = Doctrine_Core::getTable('SpotForFlag')->updateSpotFlag($spot_id,$user_id,$flag_id);
	    }else{
	      ## create a new flag
	      $flag_new = Doctrine_Core::getTable('SpotForFlag')->addSpotFlag($spot_id,$user_id,$flag_id);
	    }
	  }else{
	    $ret['error_desc'] = 'spot_not_found_db';
	    $ret['desc'] = 'Spot not found';
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

  # get a spot for editing
  public function executeAGetSpot(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if($spot_id = intval($request->getParameter('id')) ){
      if($spot = Doctrine_Core::getTable('Spot')->getRec($spot_id)){
      $ret['error'] = False;
      $ret['status'] = 'ok';
      $ret['desc'] = 'Spot found';
      $ret['record'] = $spot;
      }else{
	$ret['desc'] = 'Spot not restored';
      }
    }else{
      $ret['desc'] = 'SpotId not found';
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  # get a spot for reading
  public function executeAFetchSpot(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if($request->hasParameter('id')){
      $spot_id = intval($request->getParameter('id'));
      if($spot = Doctrine_Core::getTable('Spot')->getSpot($spot_id)){
	$ret['error'] = False;
	$ret['record'] = $spot;
      }else{
	$ret['desc'] = 'Spot not found in DB';
      }
    }else{
      $ret['desc'] = 'Spot ID param not found';
    }

    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeASpotSearch(sfWebRequest $request){
    $ret = array('error' => True);
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( $request->hasParameter('caption') or $request->hasParameter('location') or $request->hasParameter('content') ){
      $param = array(array('name' => 'caption', 'table' => 's', 'value' => $request->getParameter('caption'), 'type' => 'contain'),
		     array('name' => 'location', 'table' => 's', 'value' => $request->getParameter('location'), 'type' => 'start'),
		     array('name' => 'content', 'table' => 's', 'value' => $request->getParameter('content'), 'type' => 'contain')
		     );
      $ret = Doctrine_Core::getTable('Spot')->spotSearch($param);
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

  ## photo upload for spot - non-ajax
  public function executeUpload(sfWebRequest $request){
    $file = new fbSpotFile();
    $file->create();
    $this->json = $file->jsonResponse();
    $this->setLayout(null);
    $this->setTemplate(sfConfig::get('sf_app_template_dir').DIRECTORY_SEPARATOR . 'uploadResponse');
  }

  ## ajax file calls
  public function executeAPhotoGetAll(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $file = new fbSpotFile();
    $file->getPhotosAjax();
    return $this->renderText($file->jsonResponse());
  }

  public function executeAPhotoEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $file = new fbSpotFile();
    $file->edit();
    return $this->renderText($file->jsonResponse());
  }

  public function executeAPhotoDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $file = new fbSpotFile();
    $file->delete();
    return $this->renderText($file->jsonResponse());
  }

  ## ajax replay calls
  public function executeAReplyGetAll(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbSpotReply();
    $reply->getRepliesAjax();
    return $this->renderText($reply->jsonResponse());
  }

  public function executeAReplyPost(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbSpotReply();
    $reply->create();
    return $this->renderText($reply->jsonResponse());
  }  
  public function executeAReplyEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbSpotReply();
    $reply->edit();
    return $this->renderText($reply->jsonResponse());
  }

  public function executeAReplyDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbSpotReply();
    $reply->delete();
    return $this->renderText($reply->jsonResponse());
  }

  ## ajax fish calls
  public function executeAFishGetAll(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbSpotFish();
    $fish->parentFishAjax();
    return $this->renderText($fish->jsonResponse());
  }

  public function executeAFishPost(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbSpotFish();
    $fish->create();
    return $this->renderText($fish->jsonResponse());
  }  
  public function executeAFishEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbSpotFish();
    $fish->edit();
    return $this->renderText($fish->jsonResponse());
  }

  public function executeAFishDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbSpotFish();
    $fish->delete();
    return $this->renderText($fish->jsonResponse());
  }

}
