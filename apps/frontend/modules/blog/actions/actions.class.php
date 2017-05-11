<?php

/**
 * blog actions.
 *
 * @package    fb
 * @subpackage blog
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class blogActions extends sfActions{

  const MAX_BLOG = 20;
  
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
    $this->blog = False;
    if($recStat){
      $rec = Doctrine_Core::getTable('Blog')->getRecAllow($recStat['id']);
      if($rec){
	$recs = array($rec);
	$this->blog = array('lock' => True, 'count_total' => 1, 'count'=>1, 'records' => $recs, 'record_offset' => 0, 'record_limit' => BlogTable::BLOG_RECORD_LIMIT);
	$this->json_rec_stat = json_encode($recStat);
      }
    }
    if(! $this->blog){
      $this->blog = Doctrine_Core::getTable('Blog')->getBlogsBB();
    }
    $this->notes = Doctrine_Core::getTable('UserNotify')->getNotifies($this->user['id']);
    $this->json_blogs = json_encode($this->blog);
    $this->json_notes = json_encode($this->notes);
    $this->getResponse()->setTitle('FishBlab Exploring All Fishing Reports In ' . $geo['input']);
    $this->cfg = array();
    $this->cfg['head_text'] = 'Local Fishing Reports';
    $this->cfg['page'] = 'report';
    $cfg_js = array('page' => 'report');
    $this->json_cfg = json_encode($cfg_js);
    $this->json_user = json_encode($this->fbLib->userSafe($this->user));
    $this->param = array('geo' => $this->geo, 'user' => $this->user, 'data' => $this->blog, 'template' => 'global/blogs');
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
    $strDate = $request->getParameter('date_blog');
    if($strDate){
      $tsDate = strtotime($strDate);
      if($tsDate){
	$myDate = getdate($tsDate);
	$param['date_blog'] = $myDate['year'] .'-'. $myDate['mon'] .'-'. $myDate['mday'];
      }
    }
    return $param;
  }
  
  public function executeABlogPost(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True, 'status' => 'fail');
    if($this->user['id']){
      $post = $this->paramInit();
      $post['geo'] = $this->fbLib->boundsFromRequest();
      $blog = Doctrine_Core::getTable('Blog')->addBlog($post);    
      $blog_id = $blog['id'];
      if($blog_id > 0){
	$ret['error'] = False;
	$ret['status'] = 'ok';
	$ret['blog_id'] = $blog_id;
	$ret['record'] = $blog;
      }else{
	$ret['desc'] = 'Database Insert Failed';
      }
    }else{
      $ret['desc'] = 'UserId not found';
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  ## validate the params and that user can edit this blog
  public function validateEdit(){
    $request = $this->getRequest();
    $ret = array('error' => True);
    if($request->hasParameter('id')){
      $blog_id = intval($request->getParameter('id'));
      if($user_id = $this->user['id']){
	if($user = $this->user){
	  if($blog = Doctrine_Core::getTable('Blog')->getBlog($blog_id)){
	    if($blog['username'] == $user['username']){
	      $ret['record'] = $blog;
	      $ret['error'] = False;
	    }else{
	      $ret['error_desc'] = 'blog_not_owner';
	      $ret['desc'] = 'Edit failed: User is not the owner';
	    }
	  }else{
	    $ret['error_desc'] = 'blog_not_found_db';
	    $ret['desc'] = 'Blog not found';
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
      $ret['error_desc'] = 'blog_id_not_found_parameter';
      $ret['desc'] = 'BlogId parameter not found';
    }
    return $ret;
  }

  public function executeABlogUpdate(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = $this->validateEdit();
    if(! $ret['error']){
      $post = $this->paramInit();
      $post['blog_id'] = intval($request->getParameter('id'));
      if($blog = Doctrine_Core::getTable('Blog')->updateBlog($post)){
	$ret['status'] = 'ok';
	$ret['error'] = False;
	$ret['blog_id'] = $blog['id'];
	$ret['record'] = $blog;
      }else{
	$ret['error'] = True;
	$ret['error_desc'] = 'blog_update_failed';
	$ret['desc'] = 'Blog Update failed due to system error';		
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeABlogEditGeo(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = $this->validateEdit();
    if(! $ret['error']){
      $post = array('user_id' => $this->user['id']);
      $post['blog_id'] = intval($request->getParameter('id'));
      $post['geo'] = $this->fbLib->boundsFromRequest();
      if($blog = Doctrine_Core::getTable('Blog')->editGeo($post)){
	$ret['error'] = False;	  
	$ret['blog_id'] = $blog['id'];
	$ret['record'] = $blog;
      }else{
	$ret['error'] = True;
	$ret['error_desc'] = 'blog_update_failed';
	$ret['desc'] = 'Blog Update failed due to system error';		
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAFetchBlogsBB(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $offset = $request->getParameter('blogOffset');
    if(! $offset){
      $offset = intval($request->getParameter('offset'));
    }
    if(! $offset){
      $offset = 0;
    }
    $recs = Doctrine_Core::getTable('Blog')->getBlogsBB($offset);
    $json = json_encode($recs);
    return $this->renderText($json);
  }

  public function executeABlogSearch(sfWebRequest $request){
    $ret = array('error' => True);
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( $request->hasParameter('caption') or $request->hasParameter('loc') or $request->hasParameter('content') ){
      $param = array(array('name' => 'caption', 'table' => 'b', 'value' => $request->getParameter('caption'), 'type' => 'contain'),
		     array('name' => 'loc', 'table' => 'b', 'value' => $request->getParameter('loc'), 'type' => 'start'),
		     array('name' => 'content', 'table' => 'b', 'value' => $request->getParameter('content'), 'type' => 'contain')
		     );
      $ret = Doctrine_Core::getTable('Blog')->blogSearch($param);
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
  
  public function executeADeleteBlog(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = $this->validateEdit();
    if(! $stat['error']){
      $blog_id = $ret['record']['id'];
      $ret['delete_status'] = Doctrine_Core::getTable('Blog')->deleteBlog($blog_id);
      $ret['error'] = False;
      $ret['desc'] = 'Blog deleted';
      $ret['id'] = $blog_id;
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }
  
  public function executeAFlagBlog(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    $blog_id = intval($request->getParameter('id'));
    $flag_id = intval($request->getParameter('flag'));
    if($blog_id <= 0){
      $ret['error_desc'] = 'blog_id_not_found_parameter';
      $ret['desc'] = 'BlogId parameter not found';
    }elseif($flag_id <= 0){
      $ret['error_desc'] = 'flag_not_found_parameter';
      $ret['desc'] = 'Flag parameter not found';
    }else{
      $ret['blog_id'] = $blog_id;
      $ret['flag'] = $flag_id;
      if($user_id = $this->getUser()->getAttribute('fbUserId')){
	if($user = Doctrine_Core::getTable('User')->getUserById($user_id)){
	  if($blog = Doctrine_Core::getTable('Blog')->getBlog($blog_id)){
	    $ret['error'] = false;
	    if($flag = Doctrine_Core::getTable('BlogForFlag')->getBlogFlag($blog_id,$user_id)){
	      ## flag exists, update
	      $flag_new = Doctrine_Core::getTable('BlogForFlag')->updateBlogFlag($blog_id,$user_id,$flag_id);
	    }else{
	      ## create a new flag
	      $flag_new = Doctrine_Core::getTable('BlogForFlag')->addBlogFlag($blog_id,$user_id,$flag_id);
	    }
	  }else{
	    $ret['error_desc'] = 'blog_not_found_db';
	    $ret['desc'] = 'Blog not found';
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

  # get a blog for editing
  public function executeAGetBlog(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if($blog_id = intval($request->getParameter('id')) ){
      if($blog = Doctrine_Core::getTable('Blog')->getRec($blog_id)){
	$ret['error'] = False;
	$ret['status'] = 'ok';
	$ret['desc'] = 'Blog found';
	$ret['record'] = $blog;
      }else{
	$ret['desc'] = 'Blog not restored';
      }
    }else{
      $ret['desc'] = 'BlogId not found';
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  ## non-ajax upload
  public function executeUpload(sfWebRequest $request){
    $file = new fbBlogFile();
    $file->create();
    $this->json = $file->jsonResponse();
    $this->setTemplate(sfConfig::get('sf_app_template_dir').DIRECTORY_SEPARATOR . 'uploadResponse');
  }
  ## ajax photo
  public function executeAPhotoGetAll(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $file = new fbBlogFile();
    $file->getPhotosAjax();
    return $this->renderText($file->jsonResponse());
  }

  public function executeAPhotoEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $file = new fbBlogFile();
    $file->edit();
    return $this->renderText($file->jsonResponse());
  }

  public function executeAPhotoDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $file = new fbBlogFile();
    $file->delete();
    return $this->renderText($file->jsonResponse());
  }

  ## comments - all ajax
  public function executeAReplyGetAll(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbBlogReply();
    $reply->getRepliesAjax();
    return $this->renderText($reply->jsonResponse());
  }

  public function executeAReplyPost(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbBlogReply();
    $reply->create();
    return $this->renderText($reply->jsonResponse());
  }  
  public function executeAReplyEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbBlogReply();
    $reply->edit();
    return $this->renderText($reply->jsonResponse());
  }

  public function executeAReplyDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $reply = new fbBlogReply();
    $reply->delete();
    return $this->renderText($reply->jsonResponse());
  }

  ## ajax fish calls
  public function executeAFishGetAll(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbBlogFish();
    $fish->parentFishAjax();
    return $this->renderText($fish->jsonResponse());
  }

  public function executeAFishPost(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbBlogFish();
    $fish->create();
    return $this->renderText($fish->jsonResponse());
  }  
  public function executeAFishEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbBlogFish();
    $fish->edit();
    return $this->renderText($fish->jsonResponse());
  }

  public function executeAFishDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbBlogFish();
    $fish->delete();
    return $this->renderText($fish->jsonResponse());
  }
  
}
