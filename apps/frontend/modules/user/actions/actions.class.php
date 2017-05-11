<?php

/**
 * user actions.
 *
 * @package    fb
 * @subpackage user
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class userActions extends sfActions{

  public function preExecute(){
    $this->fbLib = new fbLib($this->getRequest());
    $this->user = $this->fbLib->restoreUser();
  }

  public function loadJSConfig(){
    $cfg_js = array('page' => 'user');
    $request = $this->getRequest();
    if($request->hasParameter('notify-all')){
      $cfg_js['notify-all'] = $request->getParameter('notify-all');
    }else if($request->hasParameter('js-run')){
      $cfg_js['js-run'] = $request->getParameter('js-run');
      if($request->hasParameter('js-run-args')){
	if($args = $request->getParameter('js-run-args')){
	  $cfg_js['js-run-args'] = explode(',',$args);
	}
      }
    }
    if($request->hasParameter('username')){
      $username_pub = $request->getParameter('username');
      if($username_pub == $this->user['username']){
	$cfg_js['page'] = 'user_profile';
      }else{
	$cfg_js['page'] = 'user_profile_pub';
      }
    }
    if ($request->hasParameter('tab')){
      $tab = $request->getParameter('tab');
      if(preg_match('/^(friend|test)$/',$tab)){
	$cfg_js['tab'] = $tab;
      }
    }
    if($request->hasParameter('dialog-redirect')){
      $cfg_js['dialog-redirect'] = $request->getParameter('dialog-redirect');
    }
    $this->json_cfg = json_encode($cfg_js);
  }

  public function executeIndex(sfWebRequest $request){
    $this->user = $this->fbLib->restoreUser();
    $this->pub_users = False;
    $this->loadJSConfig();
    $loc = $this->fbLib->getLoc();
    $geo = $loc['geo'];
    $this->geo = $geo;
    $this->json_geo = json_encode($loc);
    $month_range = $this->fbLib->getMonthRange();
    $this->zoom = 10;
    $this->data_template = 'tabs_none';
    $this->groups = array();
    if($request->hasParameter('username')){
      $username_pub = $request->getParameter('username');
      if($username_pub == $this->user['username']){
	$this->data_template = 'tabs_owner';
	$this->data = $this->user;
	$this->friends = Doctrine_Core::getTable('User')->getFriends($this->user['id']);
	$this->groups = Doctrine_Core::getTable('UserGroup')->groupsByUserId($this->user['id']);
      }else{
	$this->data_template = 'tabs';
	$user_pub = Doctrine_Core::getTable('User')->getUser($username_pub);
	$user_pub['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($this->user['id'],$user_pub['id']);
	$this->friends = Doctrine_Core::getTable('User')->getFriends($user_pub['id']);
	$this->groups = Doctrine_Core::getTable('UserGroup')->groupsByUserId($user_pub['id']);
	$this->data = $user_pub;
	$users = array($user_pub);
	$this->json_users = json_encode($users);
      }
    }else{
      $this->users = Doctrine_Core::getTable('User')->getPubUsersBB();
      $this->data = $this->users;
      $this->json_users = json_encode($this->users);
    }
    $this->getResponse()->setTitle('FishBlab Exploring All Users ' . $geo['input']);
    $this->cfg = array();
    $this->cfg['head_text'] = 'Local FishBlab Fishing Users';
    $this->cfg['page'] = 'user';
    $this->cfg['about_onclick'] = 'userAbout();';
    $this->json_user = json_encode($this->fbLib->userSafe($this->user));
    $this->msg = array('text' => '', 'id' => 'msgUser', 'about_onclick' => 'userAbout();');
  }

  private function getParams(){
    $request = $this->getRequest();
    $p = array();
    $p['id'] = $request->getParameter('id');
    $p['username'] = $request->getParameter('username');
    $p['email'] = $request->getParameter('email');
    $p['firstname'] = $request->getParameter('firstname');
    $p['lastname'] = $request->getParameter('lastname');
    $p['title'] = $request->getParameter('title');
    $p['website'] = $request->getParameter('website');
    $p['location'] = $request->getParameter('location');
    $p['utype'] = $request->getParameter('utype');
    $p['about'] = $request->getParameter('about');
    $p['phone'] = $request->getParameter('phone');
    $p['company'] = $request->getParameter('company');
    return $p;
  }
  
  public function executeACreateUser(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if ( !$request->hasParameter('username') || !$request->hasParameter('email') || !$request->hasParameter('password') ){
      $ret['desc'] = "Username or Email or Password value missing";
      $ret['field'] = 'username';
    }else{
      $geo = $this->fbLib->boundsFromRequest();
      $post = array();
      $post['username'] = $request->getParameter('username');
      $post['email'] = $request->getParameter('email');
      $post['password'] = $request->getParameter('password');
      $post['firstname'] = $request->getParameter('firstname');
      $post['lastname'] = $request->getParameter('lastname');
      $post['title'] = $request->getParameter('title');
      $post['website'] = $request->getParameter('website');
      $post['location'] = $request->getParameter('location');
      $post['utype'] = $request->getParameter('utype');
      $post['about'] = $request->getParameter('about');
      $post['lat'] = $geo['lat'];
      $post['lon'] = $geo['lon'];
      if(Doctrine_Core::getTable('User')->usernameExists($post['username'])){
	$ret['desc'] = "Username exists, please try another";
	$ret['field'] = 'username';
      }elseif(Doctrine_Core::getTable('User')->emailExists($post['email'])){
	$ret['desc'] = "Email exists, please try another";
	$ret['field'] = 'email';
      }else{
	$user = Doctrine_Core::getTable('User')->addUser($post);
	if($user){
	  $this->getUser()->setAttribute('fbUserId', $user['id']);
	  $ret['error'] = False;
	  $ret['desc'] = "New User Added";
	  $ret['valid'] = True;
	  $ret['username'] = $post['username'];
	  $ret['record'] = $this->fbLib->userSafe($user);
	  $resp->setCookie('fbValid','true',time() + 31536000,'/','.fishblab.com');
	}else{
	  $ret['desc'] = "Database Error";
	  $ret['field'] = '';
	}
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeALoginUser(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if ( !$request->hasParameter('username') || !$request->hasParameter('password') ){
      $ret['desc'] = "Username or Email or Password value missing";
      $ret['field'] = 'username';
    }else{
      $post = array();
      $post['username'] = $request->getParameter('username');
      $post['password'] = $request->getParameter('password');
      if(Doctrine_Core::getTable('User')->userDisabled($post['username'])){
	$ret['desc'] = "User is disabled";	
      }else{
	if($user = Doctrine_Core::getTable('User')->getUserSec($post['username'])){	
	  if(fbLib::verifyPassword($post['password'],$user['password'])){
	    $this->getUser()->setAttribute('fbUserId', $user['id']);
	    $ret['error'] = False;
	    $ret['desc'] = "User Logged in";
	    $ret['valid'] = True;
	    $ret['username'] = $user['username'];
	    $ret['record'] = $this->fbLib->userSafe($user);
	    $resp->setCookie('fbValid','true',time() + 31536000,'/','.fishblab.com');
	  }else{
	    $ret['desc'] = "Password is invalid";
	    $ret['error_desc'] = "password_invalid";
	    $ret['field'] = 'password';
	  }
	}else{
	  $ret['error_desc'] = "user_not_found";
	  $ret['desc'] = "User not found";
	  $ret['field'] = 'username';
	}
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeASaveUser(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if($this->user['id']){
      $p = $this->getParams();
      $p['id'] = $this->user['id'];
      $ret = $this->fbLib->validateEdit($p);
      if( ! $ret['error']){
	$ret['error'] = True;
	if($user = Doctrine_Core::getTable('User')->updateUser($p)){
	  $ret['error'] = False;
	  $ret['desc'] = "User Saved";
	  $ret['record'] = $this->fbLib->userSafe($user);
	}else{
	  $ret['desc'] = "User not saved";
	}
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeASaveUserPassword(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    $post = array();
    $password = $request->getParameter('password');
    $post['password'] = $password;
    $post['id'] = $this->getUser()->getAttribute('fbUserId');
    if ( !$request->hasParameter('password') ){
      $ret['desc'] = "Password value missing";
      $ret['field'] = 'password';
    }elseif( strlen($password) < 4 ){
      $ret['error_desc'] = "pass_not_saved";
      $ret['desc'] = "Password must be at least 4 characters long";
      $ret['field'] = 'password';
    }elseif($password == 'undefined'){
      $ret['error_desc'] = "pass_not_saved";
      $ret['desc'] = "Password is undefined";
      $ret['field'] = 'password';
    }elseif( ! $post['id']){
      $ret['error_desc'] = "user_id_not_found_session";
      $ret['desc'] = "User not logged in: Did you logout from another page?";
      $ret['field'] = 'email';
    }else{
      if(Doctrine_Core::getTable('User')->updateUserPassword($post['id'],$post['password'])){
	if($user = Doctrine_Core::getTable('User')->getUserById($post['id'])){
	  $ret['error'] = False;
	  $ret['desc'] = "User Saved";
	  $ret['record'] = $this->fbLib->userSafe($user);
	}else{
	  $ret['error_desc'] = "user_not_restored";
	  $ret['desc'] = "User not restored";
	  $ret['field'] = 'email';
	}
      }else{
	$ret['error_desc'] = "user_not_saved";
	$ret['desc'] = "User pass not saved";
	$ret['field'] = 'email';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeAEditUserGeo(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    $user_id = $this->getUser()->getAttribute('fbUserId');
    if($user_id){
      $geo = $this->fbLib->boundsFromRequest();
      if($geo['lat'] && $geo['lon']){
	$post['id'] = $user_id;
	$post['geo'] = $geo;
	if($user = Doctrine_Core::getTable('User')->updateUserGeo($post)){
	  $ret['error'] = False;
	  $ret['desc'] = "User Geo Saved";
	  $ret['record'] = $this->fbLib->userSafe($user);
	}else{
	  $ret['desc'] = "User geo not updated";
	}
      }else{
	$ret['desc'] = "Lat/Lon value missing";
      }
    }else{
      $ret['desc'] = "UserId not found";
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }
  
  public function executeAGetUser(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $user_id = $this->getUser()->getAttribute('fbUserId');
    $ret = array('error' => True);
    if($user_id){
      if($user = Doctrine_Core::getTable('User')->getUserById($user_id)){
	$ret['error'] = False;
	$ret['record'] = $user;
      }else{
	$ret['error_desc'] = 'userid_not_found_db';
	$ret['desc'] = 'System Error: User not found in DB';
      }
    }else{
      $ret['desc'] = 'User not logged in: Did you logout from another page?';
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }
  
  public function executeALogoutUser(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $this->getUser()->setAttribute('fbUserId', NULL);
    $resp->setCookie('fbValid','false',time() + 31536000,'/','.fishblab.com');
    $resp->setCookie('fishblab','false',1,'/','.fishblab.com');
    $ret = array();
    $ret['status'] = 'ok';
    $ret['desc'] = 'User logged out';
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeAUserExists(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if ( !$request->hasParameter('username') ){
      $ret['desc'] = "Username value missing";
      $ret['field'] = 'username';
    }else{
      $username = $request->getParameter('username');
      if(Doctrine_Core::getTable('User')->usernameExists($username)){
	$ret['error'] = False;
	$ret['desc'] = "User (" . $username . ") Exists";
	$ret['username'] = $username;
      }else{
	$ret['desc'] = "User (" . $username . ") does not exist";
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeAEmailExists(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if ( !$request->hasParameter('email') ){
      $ret['desc'] = "Email value missing";
      $ret['field'] = 'email';
    }else{
      $email = $request->getParameter('email');
      $userid = $this->getUser()->getAttribute('fbUserId');
      if($check_user = Doctrine_Core::getTable('User')->emailExists($email)){
	if( $userid && ($userid == $check_userid) ){
	  $ret['error'] = False;
	  $ret['desc'] = "User Email submitted";
	  $ret['field'] = 'email';
	}else{
	  $ret['desc'] = "Email exists, please try another";
	  $ret['field'] = 'email';
	}
      }else{
	$ret['desc'] = "Email not found";
	$ret['valid'] = True;
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeAInitReset(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if ( !$request->hasParameter('email') ){
      $ret['desc'] = "Email value missing";
      $ret['field'] = 'email';
    }else{
      $to = $request->getParameter('email');
      $user = Doctrine_Core::getTable('User')->getUserByEmail($to);
      if(! $user){
	$ret['error_desc'] = 'email_not_found';
	$ret['desc'] = 'Email Address was not found';
      }else{
	$reset = Doctrine_Core::getTable('UserReset')->createReset($user['id']);
	if(! $reset){
	  $ret['error_desc'] = 'system_error_reset_not_created';
	  $ret['desc'] = 'A System Error occurred: Reset Code was not created';
	}else{
	  $ret['error'] = False;
	  $ret['desc'] = 'Account Info found: email Sent';
	  $ret['email'] = $to;
	  $mailer = $this->getMailer();
	  $from = 'msg@fishblab.com';
	  $subject = 'FishBlab.com: Request to reset password for ' . $user['username'];
	  $body = $this->getPartial('initResetMail',array('user'=>$user,'reset' => $reset));
	  $msg = $mailer->compose($from,$to,$subject,$body);
	  $mailer->send($msg);
	}
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }
  
  public function executeAResetTest(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if ( !$request->hasParameter('reset_id') or !$request->hasParameter('reset_code') ){
      $ret['desc'] = "Reset Parameter missing - url is incomplete";
    }else{
      $reset_id = $request->getParameter('reset_id');
      $reset_code = $request->getParameter('reset_code');
      $password = $request->getParameter('password');
      $reset = Doctrine_Core::getTable('UserReset')->getCurReset($reset_id,$reset_code);
      if(! $reset){
	$ret['desc'] = 'Reset not found';
      }else{
	if($reset['status'] != 1){
	  $ret['desc'] = 'This reset has been used. You must start the process again';
	}else{
	  $ret['error'] = False;
	  $ret['desc'] = 'Reset is valid';
	}
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAReset(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if ( !$request->hasParameter('reset_id') or !$request->hasParameter('reset_code')  or !$request->hasParameter('password') ){
      $ret['desc'] = "Parameter missing";
    }else{
      $reset_id = $request->getParameter('reset_id');
      $reset_code = $request->getParameter('reset_code');
      $password = $request->getParameter('password');
      $reset = Doctrine_Core::getTable('UserReset')->getCurReset($reset_id,$reset_code);
      if(! $reset){
	$ret['desc'] = 'Reset not found';
      }else{
	if($reset['status'] != 1){
	  $ret['desc'] = 'This reset has been used. You must start the process again';
	}else{
	  Doctrine_Core::getTable('UserReset')->updateResetStatus($reset_id,3);
	  if(! Doctrine_Core::getTable('User')->updateUserPassword($reset['user_id'],$password) ){
	    $ret['desc'] = 'Password not updated';
	  }else{
	    $ret['error'] = False;
	    $ret['desc'] = 'Password changed';
	  }
	}
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAMsgUpdate(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if(! $user_id = $this->getUser()->getAttribute('fbUserId')){
      $ret['desc'] = 'User not logged in';
    }else{
      $p = array('user_id' => $user_id);
      $p['msg_disc'] = $request->getParameter('msg_disc');
      $p['msg_reply'] = $request->getParameter('msg_reply');
      $p['msg_update'] = $request->getParameter('msg_update');
      $p['msg_stop'] = $request->getParameter('msg_stop');
      if(! Doctrine_Core::getTable('User')->updateUserMsg($p)){
	$ret['desc'] = 'Email setting update FAILED';
      }else{
	$user = Doctrine_Core::getTable('User')->getUserById($user_id);
	$ret['record'] = $this->fbLib->userSafe($user);
	$ret['error'] = False;
	$ret['desc'] = 'Email settings successfully updated';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAEmailStop(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    $email = $request->getParameter('email');
    $ret['desc'] = 'UNSUBSCRIBE FAIL';
    if($email){
      if($user = Doctrine_Core::getTable('User')->getUserByEmail($email)){
	$p = array('msg_stop' => True, 'user_id' => $user['id'], 'msg_disc' => $user['msg_disc'], 'msg_reply' => $user['msg_reply'], 'msg_update' => $user['msg_update']);
	if(Doctrine_Core::getTable('User')->updateUserMsg($p)){
	  $ret['error'] = False;
	  $ret['desc'] = $email . ' has been unsubscribed from ALL FishBlab messaging';
	}
      }else{
	$ret['desc'] = 'User email (' . $email . ') was not found in the database';
      }
    }else{
      $ret['desc'] = 'Email parameter was not found';
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeADeletePhoto(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if(! $this->user['id']){
      $ret['desc'] = 'User not logged in';
    }else{
      if(! Doctrine_Core::getTable('User')->deletePhoto($this->user['id'])){
	$ret['desc'] = 'User Photo remove FAILED';
      }else{
	$user = Doctrine_Core::getTable('User')->getUserById($this->user['id']);
	$ret['record'] = $this->fbLib->userSafe($user);
	$ret['error'] = False;
	$ret['desc'] = 'User Photo Removed';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAFeedSubmit(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    $ret['desc'] = 'Error receiving Feedback, sorry';
    if($request->hasParameter('feed')){
      $msg = $request->getParameter('feed');
      $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
      $sid = session_id();
      $param = array('msg'=>$msg,'ip'=>$ip,'session_id'=>$sid);
      if($this->user['id']){
	$param['user_id'] = $this->user['id'];
	$param['username'] = $this->user['username'];
      }
      if($feed = Doctrine_Core::getTable('Feed')->addFeed($param)){
	$ret['error'] = False;
	$ret['desc'] = 'Feedback received, Thanks!';
	$param['date'] = $feed['date_create'];
	$mailer = $this->getMailer();
	$from = 'feedback@fishblab.com';
	$to = 'feedback@fishblab.com';
	$subject = 'FishBlab.com: Feedback';
	$body = $this->getPartial('feedbackMail',array('param'=>$param));
	$mail = $mailer->compose($from,$to,$subject,$body);
	$mailer->send($mail);
      }
    }else{
      $ret['desc'] = 'Feedback parameter was not sent!';
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAGetUserPub(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    $pub_user_id = $request->getParameter('id');
    $username = $request->getParameter('username');
    if( (! $pub_user_id) and (! $username) ){
      if( ! $pub_user_id){
	$ret['error_desc'] = 'id_not_found_parameter';
	$ret['desc'] = 'Id parameter not found';
      }else{
	$ret['error_desc'] = 'username_not_found_parameter';
	$ret['desc'] = 'Username parameter not found';
      }
    }else{
      if($pub_user_id){
	$puser = Doctrine_Core::getTable('User')->getUserById($pub_user_id);
      }else{
	$puser = Doctrine_Core::getTable('User')->getUser($username);
      }
      if($puser){
	$ret['error'] = False;
	$pub_user = array();
	$pub_user['id'] = $puser['id'];
	$pub_user['username'] = $puser['username'];
	$pub_user['firstname'] = $puser['firstname'];
	$pub_user['lastname'] = $puser['lastname'];
	$pub_user['title'] = $puser['title'];
	$pub_user['date_create'] = $puser['date_create'];
	$pub_user['website'] = $puser['website'];
	$pub_user['location'] = $puser['location'];
	$pub_user['utype'] = $puser['utype'];
	$pub_user['about'] = $puser['about'];
	$pub_user['post_count'] = 1;
	$pub_user['lat'] = $puser['lat'];
	$pub_user['lon'] = $puser['lon'];
	$pub_user['photo_id'] = $puser['photo_id'];
	$pub_user['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($this->user['id'],$puser['id']);
	$pub_user['fish_count'] = $puser['fish_count'];
	$ret['record'] = $pub_user;
      }else{
	$ret['error_desc'] = 'user_not_found_db';
	$ret['desc'] = 'System Error: User not found in DB';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeAFetchUsersBB(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $offset = $request->getParameter('userOffset');
    if(! $offset){
      $offset = $request->getParameter('offset');
    }
    if(! $offset){
      $offset = 0;
    }
    $recs = Doctrine_Core::getTable('User')->getPubUsersBB($offset);
    $json = json_encode($recs);
    return $this->renderText($json);
  }

  public function executeAGetFriends(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if(! $this->user['id']){
      $ret['error_desc'] = 'user_not_logged_in';
      $ret['desc'] = 'User is not logged in';      
    }else{
      $ret = Doctrine_Core::getTable('User')->getFriends($this->user['id']);
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeAGetFriendsAll(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if(! $this->user['id']){
      $ret['error_desc'] = 'user_not_logged_in';
      $ret['desc'] = 'User is not logged in';      
    }else{
      $ret = Doctrine_Core::getTable('User')->getFriends($this->user['id'],False);
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  ## get all friend requests for a user by other users
  public function executeAGetRequests(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if(! $this->user['id']){
      $ret['error_desc'] = 'user_not_logged_in';
      $ret['desc'] = 'User is not logged in';      
    }else{
      $ret = Doctrine_Core::getTable('User')->getFriendReq($this->user['id']);
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }
  
  public function executeAGetBlocked(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    if(! $this->user['id']){
      $ret['error_desc'] = 'user_not_logged_in';
      $ret['desc'] = 'User is not logged in';      
    }else{
      $ret = Doctrine_Core::getTable('User')->getFriendBlock($this->user['id']);
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeAFriReqAdd(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    $user = $this->user;
    $user_id = $user['id'];
    if(! $user_id){
      $ret['error_desc'] = 'user_not_logged_in';
      $ret['desc'] = 'User is not logged in';      
    }else{
      if(! $request->hasParameter('username')){
	$ret['desc'] = 'Username parameter not found';
      }else{
	$username_req_to = $request->getParameter('username');
	$user_req_to = Doctrine_Core::getTable('User')->getUser($username_req_to);
	if(! $user_req_to){
	  $ret['desc'] = 'User '. $username_req_to .' was not found';
	}else{
	  if($friend = Doctrine_Core::getTable('UserForFriend')->getFriend($user_id,$user_req_to['id'])){
	    $ret['desc'] = 'User '. $username_req_to .' is already your friend';	    
	  }else{
	    if($status = Doctrine_Core::getTable('UserForFriendReq')->hasRequest($user_id,$user_req_to['id'])){
	      $ret['desc'] = 'Friend request has already been made';
	    }else{
	      $note = $request->getParameter('note');
	      $note = strip_tags($note);
	      if($status = Doctrine_Core::getTable('UserForFriendReq')->addRequest($user_id,$user_req_to['id'],$note)){
		$param = array('user_from' => $user,'user_to' => $user_req_to,'mtype' => 10);
		#Doctrine_Core::getTable('UserMsg')->addMsg($param);
		$ret['error'] = false;
		$ret['desc'] = 'Friend request sent';
		$ret['request_username'] = $username_req_to;
		$ret['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($user_id,$user_req_to['id']);
		$caption = 'Friend Request from ' . $user['username'];
		$content = 'FishBlab User ' . $user['username'] . ' has requested to be Friends with you on FishBlab.com.';
		Doctrine_Core::getTable('UserNotify')->addNotify( array('user_from_id' => $user_id,'user_for_id' => $user_req_to['id'], 'caption' => $caption,'content' => $content,'mtype' => 10) );
		if($user_req_to['msg_stop'] != 1){
		  $mailer = $this->getMailer();
		  $from = 'noreply@fishblab.com';
		  $to = $user_req_to['email'];
		  $subject = 'FishBlab.com: Friend Request from ' . $user['username'];
		  $body = $this->getPartial('friendRequestMail',array('to_username'=>$user_req_to['username'],'from_username'=>$user['username'],'email' => $user_req_to['email'],'note'=>$note));;
		  $mail = $mailer->compose($from,$to,$subject,$body);
		  $mailer->send($mail);
		}
	      }
	    }
	  }
	}
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeAFriDel(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    $user = $this->user;
    $user_id = $user['id'];
    if(! $user_id){
      $ret['error_desc'] = 'user_not_logged_in';
      $ret['desc'] = 'User is not logged in';      
    }else{
      if(! $request->hasParameter('username')){
	$ret['desc'] = 'Username parameter not found';
      }else{
	$username_req = $request->getParameter('username');
	$user_req = Doctrine_Core::getTable('User')->getUser($username_req);
	if(! $user_req){
	  $ret['desc'] = 'User '. $username_req .' was not found';
	}else{
	  $friend = Doctrine_Core::getTable('UserForFriend')->isFriend($user_id,$user_req['id']);
	  if(! $friend){
	    $ret['desc'] = 'User '. $username_req .' is not your friend';	    
	  }else{
	    $status = Doctrine_Core::getTable('UserForFriend')->delFriend($user_id,$user_req['id']);
	    if(! $status){
	      $ret['desc'] = 'Friend Removal Failed';
	    }else{
	      $param = array('user_from' => $user,'user_to' => $user_req,'mtype' => 10);
	      $ret['error'] = false;
	      $ret['desc'] = 'Friend Remove complete';
	      $ret['request_username'] = $username_req;
	      $ret['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($user_id,$user_req['id']);
	    }
	  }
	}
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }
  
  ## user has initiated an action to another users friend request
  public function executeAFriReqAct(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True);
    $user = $this->user;
    $user_id = $user['id'];
    if(! $user_id){
      $ret['error_desc'] = 'user_not_logged_in';
      $ret['desc'] = 'User is not logged in';      
    }elseif(! $request->hasParameter('username')){
      $ret['desc'] = 'Username parameter not found';
    }elseif(! $request->hasParameter('req_action')){
      $ret['desc'] = 'Request action parameter not found';
    }else{
      $username_req = $request->getParameter('username');
      $action = $request->getParameter('req_action');
      $user_req = Doctrine_Core::getTable('User')->getUser($username_req);
      if(! $user_req){
	$ret['desc'] = 'User '. $username_req .' was not found';
      }else{
	$user_req_id = $user_req['id'];
	if($friend = Doctrine_Core::getTable('UserForFriend')->getFriend($user_id,$user_req_id)){
	  $ret['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($user_id,$user_req_id);
	  $ret['desc'] = 'User '. $username_req .' is already your friend';	    
	}else{
	  if($action == 'unblock'){
	    $status = Doctrine_Core::getTable('UserForFriendBlock')->delBlock($user_id,$user_req_id);
	    if(!$status){
	      $ret['desc'] = 'System Error unblocking User';
	    }else{
	      $ret['error'] = false;
	      $ret['desc'] = 'User UnBlocked';
	      $ret['request_username'] = $username_req;
	      $ret['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($user_id,$user_req_id);
	    }
	  }else{
	    $request = Doctrine_Core::getTable('UserForFriendReq')->hasRequest($user_req_id,$user_id);
	    if(! $request){
	      $ret['desc'] = 'Request was not found';
	    }else{
	      if($action == 'allow'){
		$status = Doctrine_Core::getTable('UserForFriend')->addFriend($user_id,$user_req_id);
		$status2 = Doctrine_Core::getTable('UserForFriend')->addFriend($user_req_id,$user_id);
		if(!$status or !$status2){
		  $ret['desc'] = 'System Error adding New Friend';
		}else{
		  $status = Doctrine_Core::getTable('UserForFriendReq')->delRequest($user_req_id,$user_id);
		  $ret['error'] = false;
		  $ret['desc'] = $username_req . ' was added as your Friend on FishBlab';
		  $ret['request_username'] = $username_req;
		  $ret['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($user_id,$user_req_id);
		  $caption = 'Your Friend Request to ' . $user['username'] .  ' is confirmed';
		  $content = 'FishBlab User ' . $from_username . ' has confirmed your Friend request and is now your FishBlab.com Friend.';
		  Doctrine_Core::getTable('UserNotify')->addNotify( array('user_from_id' => $user_id,'user_for_id' => $user_req_id, 'caption' => $caption,'content' => $content,'mtype' => 15) );
		  if($user_req['msg_stop'] != 1){
		    $mailer = $this->getMailer();
		    $from = 'noreply@fishblab.com';
		    $to = $user_req['email'];
		    $subject = 'FishBlab.com: Your Friend Request to ' . $user['username'] .  ' has been confirmed';
		    $body = $this->getPartial('friendConfirmMail',array('to_username'=>$user_req['username'],'from_username'=>$user['username'],'email' => $user_req['email']));;
		    $mail = $mailer->compose($from,$to,$subject,$body);
		    $mailer->send($mail);
		  }
		}
	      }elseif($action == 'ignore'){
		$status = Doctrine_Core::getTable('UserForFriendReq')->delRequest($user_req_id,$user_id);
		if(!$status){
		  $ret['desc'] = 'System Error ignoring User';
		}else{
		  $ret['error'] = false;
		  $ret['desc'] = 'User Ignored';
		  $ret['request_username'] = $username_req;
		  $ret['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($user_id,$user_req_id);
		}
	      }elseif($action == 'block'){
		$status = Doctrine_Core::getTable('UserForFriendBlock')->addBlock($user_id,$user_req_id);
		if(!$status){
		  $ret['desc'] = 'System Error blocking User';
		}else{
		  $status = Doctrine_Core::getTable('UserForFriendReq')->delRequest($user_req_id,$user_id);
		  $ret['error'] = false;
		  $ret['desc'] = 'User Blocked';
		  $ret['request_username'] = $username_req;
		  $ret['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($user_id,$user_req_id);
		}
	      }
	    }
	  }
	}
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }
  
  public function getFriendRequest(){
    $req = $this->getRequest();
    $ret = array('error' => True);
    $user = $this->user;
    $user_id = $user['id'];
    $username_req = $req->getParameter('username');
    if( ! $username_req){
      $username_req = $req->getParameter('username_from');
    }
    if(! $user_id){
      $ret['error_desc'] = 'user_not_logged_in';
      $ret['desc'] = 'User is not logged in';      
    }elseif( ! $username_req){
      $ret['desc'] = 'Request Username parameter not found';      
    }else{
      $user_req = Doctrine_Core::getTable('User')->getUser($username_req);
      if(! $user_req){
	$ret['desc'] = 'User '. $username_req .' was not found';
      }else{
	$user_req_id = $user_req['id'];
	if($friend = Doctrine_Core::getTable('UserForFriend')->getFriend($user_id,$user_req_id)){
	  $ret['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($user_id,$user_req_id);
	  $ret['desc'] = 'User '. $username_req .' is already your friend';	    
	}else{
	  $request = Doctrine_Core::getTable('UserForFriendReq')->getRequest($user_req_id,$user_id);
	  if(! $request){
	    $ret['desc'] = 'Request was not found';
	  }else{
	    $ret['error'] = False;
	    $ret['request'] = $request;
	    $ret['req_user'] = $user_req;
	  }
	}
      }
    }
    return $ret;
  }

  ## get a request
  public function executeAGetFriReq(sfWebRequest $req){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    if($ret = $this->getFriendRequest()){
      $ret['request'] = true;
      $ret['record'] = $ret['req_user'];
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  # change the status of a notifiy to read so the user doesn't see it any more
  public function executeANoteRead(sfWebRequest $request){
    $ret = array('error' => True);
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $user_id = $this->user['id'];
    if(! $user_id){
      $ret['desc'] = 'User is not logged in';      
    }elseif( ! $request->hasParameter('note_id') ){
      $ret['desc'] = 'notify id parameter not found';
    }else{
      $note_id = intval($request->getParameter('note_id'));
      if( ! $notify = Doctrine_Core::getTable('UserNotify')->getNotify($note_id)){
	$ret['desc'] = 'Notify not found';
      }else{
	if($notify['user_id'] != $user_id){
	  $ret['desc'] = 'Error User is not owner of notification';
	}else{
	  if( ! Doctrine_Core::getTable('UserNotify')->updateNotifyStatus($notify['id'],100)){
	    $ret['desc'] = 'System error changing notification status';
	  }else{
	    $ret['error'] = False;
	    $ret['desc'] = 'Success: Notify status changed to read';
	    $ret['note_id'] = $note_id;
	  }
	}
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  # auto-lookup of username by first few chars in search box
  public function executeALookup(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( !$request->hasParameter('term') ){
      $json = array();
      return $this->renderText($json);
    }
    $substr = $request->getParameter('term');
    $recs = Doctrine_Core::getTable('User')->userByNameLike($substr);
    $json = json_encode($recs);
    return $this->renderText($json);
  }

  public function executeAUserSearch(sfWebRequest $request){
    $ret = array('error' => True);
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    if ( $request->hasParameter('username') or $request->hasParameter('firstname') or $request->hasParameter('lastname') or $request->hasParameter('loc') ){
      $param = array(array('name' => 'username', 'table' => 'u', 'value' => $request->getParameter('username'), 'type' => 'start'),
		     array('name' => 'firstname', 'table' => 'u', 'value' => $request->getParameter('firstname'), 'type' => 'start'),
		     array('name' => 'lastname', 'table' => 'u', 'value' => $request->getParameter('lastname'), 'type' => 'start'),
		     array('name' => 'location', 'table' => 'u', 'value' => $request->getParameter('location'), 'type' => 'contain')
		     );
      $ret = Doctrine_Core::getTable('User')->userSearch($param);
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

  public function executeAAbout(sfWebRequest $request){
    $this->setLayout('empty_layout');
    $this->getResponse()->setHttpHeader('Content-type', 'text/html');
    return $this->renderPartial('about');
  }

  public function executeAGetNotes(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True,'notes' => array());
    if(! $this->user['id']){
      $ret['error_desc'] = 'user_not_logged_in';
      $ret['desc'] = 'User is not logged in';      
    }else{

      if($notes = Doctrine_Core::getTable('UserNotify')->getNotifies($this->user['id'])){
	$ret['notes'] = $notes;
	$ret['error'] = false;
      }else{
	$ret['desc'] = 'database error';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  ## non-ajax upload
  public function executeUpload(sfWebRequest $request){
    $file = new fbUserFile();
    $file->create();
    $this->json = $file->jsonResponse();
  }

  ## ajax fish calls
  public function executeAFishGetAll(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbUserFish();
    $fish->parentFishAjax();
    return $this->renderText($fish->jsonResponse());
  }

  public function executeAFishPost(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbUserFish();
    $fish->create();
    return $this->renderText($fish->jsonResponse());
  }  
  public function executeAFishEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbUserFish();
    $fish->edit();
    return $this->renderText($fish->jsonResponse());
  }

  public function executeAFishDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $fish = new fbUserFish();
    $fish->delete();
    return $this->renderText($fish->jsonResponse());
  }
  
}
