<?php

/**
 * photo actions.
 *
 * @package    fb
 * @subpackage photo
 * @author     Joe Junkin
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class photoActions extends sfActions{

  public function preExecute(){
    $this->fbLib = new fbLib($this->getRequest());
    $this->user = $this->fbLib->restoreUser();
  }

  public function executeIndex(sfWebRequest $request){
    $user_id = $this->user['id'];
    $loc = $this->fbLib->getLoc();
    $geo = $loc['geo'];
    $this->geo = $geo;
    $this->json_geo = json_encode($loc);
    $month_range = $this->fbLib->getMonthRange();
    $this->zoom = 10;
    $recStat = $this->fbLib->urlRecStat();
    $this->photo = False;
    if($recStat){
      $rec = Doctrine_Core::getTable('File')->getRecAllow($recStat['id']);
      if($rec){
	$recs = array($rec);
	$this->photo = array('lock' => True, 'count_total' => 1, 'count'=>1, 'records' => $recs, 'record_offset' => 0, 'record_limit' => FileTable::FILE_RECORD_LIMIT);
	$this->json_rec_stat = json_encode($recStat);
      }
    }
    if(! $this->photo){
      $this->photo = Doctrine_Core::getTable('File')->getPhotosBB();
    }
    $this->notes = Doctrine_Core::getTable('UserNotify')->getNotifies($this->user['id']);
    $this->json_photos = json_encode($this->photo);
    $this->json_notes = json_encode($this->notes);
    $this->getResponse()->setTitle('FishBlab Exploring All Fishing Photos in ' . $geo['input']);
    $this->cfg = array();
    $this->cfg['head_text'] = 'Local Fishing Photos';
    $this->cfg['page'] = 'photo';
    $this->cfg['about_onclick'] = 'photoAbout();';
    $cfg_js = array('page' => 'photo');
    $this->json_cfg = json_encode($cfg_js);
    $this->json_user = json_encode($this->fbLib->userSafe($this->user));
    #$this->alert = array('text' => 'Use the Tabs to view the Photos found in the current Map', 'id' => 'alertDataPhoto');
    $this->msg = array('text' => '', 'id' => 'msgPhoto', 'about_onclick' => 'photoAbout();');
    $this->param = array( 'template' => 'global/photos', 'geo' => $geo, 'user' => $this->user, 'data' => $this->photo);
  }

  # after a googlemap change event, call this ajax function with bounds
  public function executeAFetchPhotosBB(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( !$request->hasParameter('bounds') ){
      $json = 'bounds not found';
      return $this->renderText($json);
    }
    $offset = $request->getParameter('photoOffset');
    if(! $offset){
      $offset = intval($request->getParameter('offset'));
    }
    if(! $offset){
      $offset = 0;
    }
    $photos = Doctrine_Core::getTable('File')->getPhotosBB($offset);
    $json = json_encode($photos);    
    return $this->renderText($json);
  }

  public function executeAPhotoSearch(sfWebRequest $request){
    $ret = array('error' => True);
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( $request->hasParameter('caption') or $request->hasParameter('detail') ){
      $param = array(array('name' => 'caption', 'table' => 'f', 'value' => $request->getParameter('caption'), 'type' => 'contain'),
		     array('name' => 'detail', 'table' => 'f', 'value' => $request->getParameter('detail'), 'type' => 'contain')
		     );
      $ret = Doctrine_Core::getTable('File')->photoSearch($param);
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

  public function executeAGetPhoto(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if ( !$request->hasParameter('id') ){
      $ret['desc'] = 'photo id not found';
    }else{
      $file_id = $request->getParameter('id');
      $photo = Doctrine_Core::getTable('File')->getFile($file_id);
      if( ! $photo){
	$ret['desc'] = 'Photo not found';
      }else{
	$ret['error'] = False;
	$ret['record'] = $photo;
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

  public function executeAReplyGetAll(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbPhotoReply();
    $reply->getRepliesAjax();
    return $this->renderText($reply->jsonResponse());
  }

  public function executeAReplyPost(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbPhotoReply();
    $reply->create();
    return $this->renderText($reply->jsonResponse());
  }  
  public function executeAReplyEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbPhotoReply();
    $reply->edit();
    return $this->renderText($reply->jsonResponse());
  }

  public function executeAReplyDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbPhotoReply();
    $reply->delete();
    return $this->renderText($reply->jsonResponse());
  }

  ## ajax fish calls
  public function executeAFishGetAll(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbPhotoFish();
    $fish->parentFishAjax();
    return $this->renderText($fish->jsonResponse());
  }

  public function executeAFishPost(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbPhotoFish();
    $fish->create();
    return $this->renderText($fish->jsonResponse());
  }  
  public function executeAFishEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbPhotoFish();
    $fish->edit();
    return $this->renderText($fish->jsonResponse());
  }

  public function executeAFishDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbPhotoFish();
    $fish->delete();
    return $this->renderText($fish->jsonResponse());
  }

}
