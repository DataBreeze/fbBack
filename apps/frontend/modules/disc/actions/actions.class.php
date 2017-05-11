<?php

/**
 * disc actions.
 *
 * @package    fb
 * @subpackage disc
 * @author     Joe Junkin
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class discActions extends sfActions{

  const META_KEYWORDS = 'Fishing Discussion,fishing forum,fish talk,fish blab,discuss fish,fish forum,talk about fish';
  const MAX_DISC = 10;

  public function preExecute(){
    $this->fbLib = new fbLib($this->getRequest());
    $this->user = $this->fbLib->restoreUser();
  }

  public function executeIndex(sfWebRequest $request){
    $param = array();
    $loc = $this->fbLib->getLoc($request);
    $geo = $loc['geo'];
    $this->geo = $geo;
    $this->json_geo = json_encode($loc);
    $month_range = $this->fbLib->getMonthRange();
    $this->zoom = 10;
    $recStat = $this->fbLib->urlRecStat();
    $this->discuss = False;
    if($recStat){
      $recs = array(Doctrine_Core::getTable('Disc')->getRecAllow($recStat['id']));
      $this->discuss = array('lock' => True, 'count_total' => 1, 'count'=>1, 'records' => $recs, 'record_offset' => 0, 'record_limit' => DiscTable::DISC_RECORD_LIMIT);
      $this->json_rec_stat = json_encode($recStat);
    }
    if(! $this->discuss){
      $this->discuss = Doctrine_Core::getTable('Disc')->getDiscsBB();
    }
    $this->json_discuss = json_encode($this->discuss);
    $this->notes = Doctrine_Core::getTable('UserNotify')->getNotifies($this->user['id']);
    $this->json_notes = json_encode($this->notes);
    $this->getResponse()->setTitle('FishBlab Exploring All Fishing Discussion In ' . $geo['input']);
    $this->cfg = array();
    $this->cfg['head_text'] = 'Map Based Fishing Discussion';
    $this->cfg['page'] = 'discuss';
    $this->cfg['about_onclick'] = 'discAbout();';
    $cfg_js = array('page' => 'disc');
    $this->json_cfg = json_encode($cfg_js);
    $this->json_user = json_encode($this->fbLib->userSafe($this->user));
    $loc = $loc['input'];
    $response = $this->getResponse();
    $response->addMeta('keywords', discActions::META_KEYWORDS .','. $loc);
    $response->addMeta('description', 'FishBlab has Fishing Discussion and Forums for Fishing in ' . $loc . '. Join local fisherman and talk about fishing in ' . $loc . ' using FishBlab Discussion.');
    $response->setTitle('FishBlab Discussion and Forum for Fishing in ' . $loc);
    #$this->alert = array('text' => 'Explore Discussions found in the current Map', 'id' => 'alertDataDisc');
    $this->msg = array('text' => 'Exploring all Fishing Discussion in Map', 'id' => 'msgDisc','about_onclick' => 'discAbout();');
    $this->param = array('geo' => $this->geo, 'user' => $this->user, 'data' => $this->discuss, 'template' => 'global/discussion');
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
    if($request->hasParameter('content')){
      $param['text'] = $request->getParameter('content');
    }
    if($request->hasParameter('caption')){
      $param['caption'] = $request->getParameter('caption');
    }
    if($request->hasParameter('pid')){
      $param['pid'] = $request->getParameter('pid');
    }
    if($request->hasParameter('fish_id')){
      $param['fish_id'] = $request->getParameter('fish_id');
    }
    if($request->hasParameter('cat_id')){
      $param['cat_id'] = $request->getParameter('cat_id');
    }
    if($request->hasParameter('cat')){
      $param['cat'] = $request->getParameter('cat');
    }
    if($request->hasParameter('wtype')){
      $param['wtype'] = $request->getParameter('wtype');
    }
    if($request->hasParameter('fish_name')){
      $param['fish_name'] = $request->getParameter('fish_name');
    }
    return $param;
  }

  public function executeADiscPost(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True, 'status' => 'fail');
    if($this->user['id']){
      $post = $this->paramInit();
      $post['geo'] = $this->fbLib->boundsFromRequest();
      $disc = Doctrine_Core::getTable('Disc')->addDisc($post);    
      $disc_id = $disc['id'];
      if($disc_id > 0){
	$ret['error'] = False;
	$ret['status'] = 'ok';
	$ret['disc_id'] = $disc_id;
	$ret['record'] = $disc;
      }else{
	$ret['desc'] = 'Database Insert Failed';
      }
    }else{
      $ret['desc'] = 'UserId not found';
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAFetchDiscsBB(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $offset = 0;
    if($request->hasParameter('discOffset')){
      $offset = intval($request->getParameter('discOffset'));
    }
    if(! $offset){
      $offset = intval($request->getParameter('offset'));
    }
    $fish_id = $request->getParameter('fishId');
    $recs = Doctrine_Core::getTable('Disc')->getDiscsBB($offset,$fish_id);    
    $json = json_encode($recs);
    return $this->renderText($json);
  }

  public function executeAFetchDisc(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if( ! $request->hasParameter('id')){
      $ret['desc'] = 'ID not found';
    }else{
      $disc_id = intval($request->getParameter('id'));
      $rec = Doctrine_Core::getTable('Disc')->getDisc($disc_id);
      if( ! $rec){
	$ret['desc'] = 'Record not found';	
      }else{
	$ret['error'] = false;
	$ret['record'] = $rec;
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeADiscSearch(sfWebRequest $request){
    $ret = array('error' => True);
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( $request->hasParameter('caption') or $request->hasParameter('content') ){
      $param = array(array('name' => 'caption', 'table' => 'd', 'value' => $request->getParameter('caption'), 'type' => 'start'),
		     array('name' => 'content', 'table' => 'd', 'value' => $request->getParameter('content'), 'type' => 'contain')
		     );
      $ret = Doctrine_Core::getTable('Disc')->discSearch($param);
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

  ## validate the edit or delete of a discussion
  private function validateEdit(){
    $ret = array( 'error' => True );
    $req = $this->getRequest();
    $disc_id = $req->getParameter('id');
    if(! $req->hasParameter('id')){
      $ret['error_desc'] = 'disc_id_not_found';
      $ret['desc'] = 'Disc Id parameter not found';
    }else if(! $this->user['id']){
      $ret['error_desc'] = 'user_not_logged_in';
      $ret['desc'] = 'User not Logged in';
    }else if(! $this->user){
      $ret['error_desc'] = 'user_not_found_db';
      $ret['desc'] = 'User not Found';
    }else if(! $disc = Doctrine_Core::getTable('Disc')->getDisc($disc_id)){      
      $ret['error_desc'] = 'discuss_not_found_db';
      $ret['desc'] = 'Discussion not found';
    }else if($disc['username'] != $this->user['username']){
      $ret['error_desc'] = 'discuss_not_owner';
      $ret['desc'] = 'Discussion not deleted because the user is not the owner';
    }else{
      $ret['user_id'] = $this->user['id'];
      $ret['record'] = $disc;
      $ret['disc_id'] = $disc['id'];
      $ret['pid'] = intval($disc['pid']);
      $ret['error'] = False;
      $ret['status'] = 'ok';
    }
    return $ret;
  }
  
  public function executeADiscEdit(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = $this->validateEdit();
    if( ! $ret['error']){
      $ret['error'] = True;
      $post = $this->paramInit();      
      $disc_id = intval($request->getParameter('id'));
      $post['id'] = $disc_id;
      if($disc = Doctrine_Core::getTable('Disc')->editDisc($post)){
	$ret['record'] = $disc;
	$ret['error'] = False;
	$ret['desc'] = 'Discussion edited';
      }else{
	$ret['desc'] = 'Error editing Discussion';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }
  
  public function executeAEditGeo(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = $this->validateEdit();
    $geo = $this->fbLib->boundsFromRequest();
    if( ! $ret['error']){
      if( ( ! $geo['lat']) || (! $geo['lon']) ){
	$ret['desc'] = "Lat/Lon value missing";
      }else{
	$disc_id = intval($request->getParameter('id'));
	$post['id'] = $disc_id;
	$post['geo'] = $geo;
	if(Doctrine_Core::getTable('Disc')->editGeo($post)){
	  if($disc = Doctrine_Core::getTable('Disc')->getRec($disc_id)){
	    $ret['error'] = False;
	    $ret['desc'] = "Disc Geo Saved";
	    $ret['record'] = $disc;
	  }else{
	    $ret['desc'] = "Disc not restored";
	  }
	}else{
	  $ret['desc'] = "Disc not saved";
	}
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }
  
  public function executeADeleteDisc(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = $this->validateEdit();
    if( ! $ret['error']){
      $ret['error'] = True;
      $disc_id = intval($request->getParameter('id'));
      $disc = $ret['rceord'];
      $replies = Doctrine_Core::getTable('DiscReply')->getReplies($disc_id,1);
      if( count($replies) == 0 ){
	$ret['delete_status'] = Doctrine_Core::getTable('Disc')->deleteDisc($disc_id);
	$ret['error'] = False;
	$ret['desc'] = 'Discussion deleted';
	$ret['id'] = $disc_id;
      }else{
	$ret['error_desc'] = 'discuss_has_replies';
	$ret['desc'] = 'Discussion not deleted because it has replies';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeAFlagDisc(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array();
    $ret['error'] = True;
    if(! $request->hasParameter('id')){
      $ret['error_desc'] = 'disc_id_not_found_parameter';
      $ret['desc'] = 'Disc_id parameter not found';
    }elseif(! $request->hasParameter('flag')){
      $ret['error_desc'] = 'flag_not_found_parameter';
      $ret['desc'] = 'Flag parameter not found';
    }else{
      $disc_id = $request->getParameter('id');
      $flag_id = $request->getParameter('flag');
      $ret['disc_id'] = intval($disc_id);
      $ret['flag'] = intval($flag_id);
      if($user_id = $this->user['id']){
	if($user = $this->user){
	  if($disc = Doctrine_Core::getTable('Disc')->getDisc($disc_id)){
	    $ret['error'] = False;
	    if($flag = Doctrine_Core::getTable('DiscForFlag')->getDiscFlag($disc_id,$user_id)){
              # flag exists, update
	      $flag_new = Doctrine_Core::getTable('DiscForFlag')->updateDiscFlag($disc_id,$user_id,$flag_id);
	    }else{
	      # create a new flag
	      $flag_new = Doctrine_Core::getTable('DiscForFlag')->addDiscFlag($disc_id,$user_id,$flag_id);
	    }
	  }else{
	    $ret['error_desc'] = 'disc_not_found_db';
	    $ret['desc'] = 'Disc not found';
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

  public function executeAAbout(sfWebRequest $request){
    $this->setLayout('empty_layout');
    $this->getResponse()->setHttpHeader('Content-type', 'text/html');
    return $this->renderPartial('about');
  }

  ## non-ajax upload
  public function executeUpload(sfWebRequest $request){
    $file = new fbDiscFile();
    $file->create();
    $this->json = $file->jsonResponse();
    $this->setTemplate(sfConfig::get('sf_app_template_dir').DIRECTORY_SEPARATOR . 'uploadResponse');
  }

  public function executeAPhotoGetAll(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $file = new fbDiscFile();
    $file->getPhotosAjax();
    return $this->renderText($file->jsonResponse());
  }

  public function executeAPhotoEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $file = new fbDiscFile();
    $file->edit();
    return $this->renderText($file->jsonResponse());
  }

  public function executeAPhotoDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $file = new fbDiscFile();
    $file->delete();
    return $this->renderText($file->jsonResponse());
  }

  ## ajax comments
  public function executeAReplyGetAll(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbDiscReply();
    $reply->getRepliesAjax();
    return $this->renderText($reply->jsonResponse());
  }

  public function executeAReplyPost(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbDiscReply();
    $reply->create();
    return $this->renderText($reply->jsonResponse());
  }  
  public function executeAReplyEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbDiscReply();
    $reply->edit();
    return $this->renderText($reply->jsonResponse());
  }

  public function executeAReplyDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbDiscReply();
    $reply->delete();
    return $this->renderText($reply->jsonResponse());
  }

  ## ajax fish calls
  public function executeAFishGetAll(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbDiscFish();
    $fish->parentFishAjax();
    return $this->renderText($fish->jsonResponse());
  }

  public function executeAFishPost(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbDiscFish();
    $fish->create();
    return $this->renderText($fish->jsonResponse());
  }  
  public function executeAFishEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbDiscFish();
    $fish->edit();
    return $this->renderText($fish->jsonResponse());
  }

  public function executeAFishDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbDiscFish();
    $fish->delete();
    return $this->renderText($fish->jsonResponse());
  }

}
