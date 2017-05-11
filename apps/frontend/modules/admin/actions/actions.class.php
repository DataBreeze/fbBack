<?php

/**
 * admin actions.
 *
 * @package    fb
 * @subpackage admin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class adminActions extends sfActions{

  public function preExecute(){
    $this->fbLib = new fbLib();
    $this->user = $this->fbLib->restoreUser();
    $this->loc = $this->fbLib->getLoc();
    $this->cfg = array('page' => 'admin');
    $this->footer = array();
  }

  public function initFooter(){
    $this->footer['json_user'] = json_encode($this->fbLib->userSafe($this->user));
    $this->footer['json_cfg'] = json_encode($this->cfg);
    $this->footer['json_loc'] = json_encode($this->loc);
  }

  public function executeIndex(sfWebRequest $request){
    $ret = $this->verify();
    $p = array();
    $this->p = $p;
    $this->status = $ret;
    $this->initFooter();
  }

  public function verify(){
    $ret = array('error' => True,'desc' => 'Error in promo');
    if($this->user['id']){
      if(fbLib::isAdmin()){
	$ret['error'] = False;
	$ret['desc'] = 'Permitted';
      }else{
	$ret['desc'] = 'User does not have access privileges';
      }
    }else{
      $ret['desc'] = 'User not logged in';
    }
    return $ret;
  }
  
  ####################
  ## admin user
  ####################

  private function userParam(){
    $p = array();
    $request = $this->getRequest();
    $p['id'] = intval($request->getParameter('id'));
    $p['username'] = $request->getParameter('username');
    $p['email'] = $request->getParameter('email');
    $p['password'] = $request->getParameter('password');
    $p['firstname'] = $request->getParameter('firstname');
    $p['lastname'] = $request->getParameter('lastname');
    $p['title'] = $request->getParameter('title');
    $p['website'] = $request->getParameter('website');
    $p['location'] = $request->getParameter('location');
    $p['utype'] = $request->getParameter('utype');
    $p['about'] = $request->getParameter('about');
    $p['company'] = $request->getParameter('company');
    $p['phone'] = $request->getParameter('phone');
    $p['lat'] = $geo['lat'];
    $p['lon'] = $geo['lon'];
    if($geo = $this->fbLib->boundsFromRequest()){
      $p['geo'] = $geo;
      $p['lat'] = $geo['lat'];
      $p['lon'] = $geo['lon'];
    }
    return $p;
  }
  
  public function executeAUserNew(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = $this->verify();
    if( ! $ret['error']){
      $ret['error'] = True;
      $p = $this->userParam();
      if ( $p['username'] and $p['email'] and $p['password'] ){
	if( ! Doctrine_Core::getTable('User')->usernameExists($p['username'])){
	  if( ! Doctrine_Core::getTable('User')->emailExists($p['email'])){
	    $p['fb_status'] = 1500;
	    if($user = Doctrine_Core::getTable('User')->adminNew($p)){
	      $ret['error'] = False;
	      $ret['desc'] = "New User Added";
	      $ret['record'] = $user;
	    }else{
	      $ret['desc'] = 'User Insert Failed';
	    }
	  }else{
	    $ret['desc'] = "Email exists, please try another";
	    $ret['field'] = 'email';
	  }
	}else{
	  $ret['desc'] = "Username exists, please try another";
	  $ret['field'] = 'username';
	}
      }else{
	$ret['desc'] = "Username or Email or Password value missing";
	$ret['field'] = 'username';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAUserEdit(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = $this->verify();
    if( ! $ret['error']){
      $p = $this->userParam();
      $ret = $this->fbLib->validateEdit($p);
      if( ! $ret['error']){
	$ret['error'] = True;
	if($user = Doctrine_Core::getTable('User')->adminEdit($p)){
	  $ret['error'] = False;
	  $ret['desc'] = "User Edited";
	  $ret['record'] = $user;
	}else{
	  $ret['desc'] = "User not saved";
	}
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }


  public function executeAUserEditGeo(sfWebRequest $request){
    $resp = $this->getResponse();
    $this->setLayout(null);
    $resp->setHttpHeader('Content-type', 'application/json');
    $ret = $this->verify();
    if( ! $ret['error']){
      $ret['error'] = True;
      $p = $this->userParam();
      if($user = Doctrine_Core::getTable('User')->adminEditGeo($p)){
	$ret['error'] = False;
	$ret['desc'] = "User Geo Edited";
	$ret['record'] = $user;
      }else{
	$ret['desc'] = "User not saved";
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);    
  }

  public function executeAUsersBB(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $offset = $request->getParameter('userOffset');
    if(! $offset){
      $offset = 0;
    }
    $recs = Doctrine_Core::getTable('User')->adminUsersBB($offset);
    $json = json_encode($recs);
    return $this->renderText($json);
  }

  #####################
  ## promo
  #####################

  private function promoParam(){
    $p = array();
    $request = $this->getRequest();
    $p['id'] = intval($request->getParameter('id'));
    $p['caption'] = $request->getParameter('caption');
    $p['email_from'] = $request->getParameter('email_from');
    $p['name_from'] = $request->getParameter('name_from');
    $p['title_from'] = $request->getParameter('title_from');
    $p['subject'] = $request->getParameter('subject');
    $p['user_id'] = $this->user['id'];
    if($geo = $this->fbLib->boundsFromRequest()){
      $p['lat'] = $geo['lat'];
      $p['lon'] = $geo['lon'];
    }
    return $p;
  }

  public function executeAPromoNew(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = $this->verify();
    if( ! $ret['error']){
      $ret['error'] = True;
      $p = $this->promoParam();
      if($promo = Doctrine_Core::getTable('Promo')->newPromo($p)){
	$ret['desc'] = 'promo insert ok';
	$ret['error'] = False;
	$ret['record'] = $promo;
      }else{
	$ret['desc'] = 'Promo Insert Failed';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAPromoEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True, 'desc' => 'Error');
    $ret = $this->verify();
    if( ! $ret['error']){
      $ret['error'] = True;
      $p = $this->promoParam();
      if($p['id']){
	if($promo = Doctrine_Core::getTable('Promo')->edit($p)){
	  $ret['desc'] = 'promo edit ok';
	  $ret['error'] = False;
	  $ret['record'] = $promo;
	}else{
	  $ret['desc'] = 'Promo Edit Failed';
	}
      }else{
	$ret['desc'] = 'id param not found';
      }  
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAPromoEditGeo(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True, 'desc' => 'Error');
    $ret = $this->verify();
    if( ! $ret['error']){
      $ret['error'] = True;
      $p = $this->promoParam();
      if($p['id']){
	if($promo = Doctrine_Core::getTable('Promo')->editGeo($p)){
	  $ret['desc'] = 'promo edit ok';
	  $ret['error'] = False;
	  $ret['record'] = $promo;
	}else{
	  $ret['desc'] = 'Promo Edit Geo Failed';
	}
      }else{
	$ret['desc'] = 'id param not found';
      }  
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAPromoBB(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True, 'desc' => 'Error');
    $ret = $this->verify();
    if( ! $ret['error']){
      $ret['error'] = True;
      $p = $this->promoParam();
      if($promo = Doctrine_Core::getTable('Promo')->getBB($p) ){
	$ret = $promo;
	$ret['error'] = False;
	$ret['desc'] = 'Promo BB OK';
      }else{
	$ret['desc'] = 'Fetch BB failed';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAPromoAll(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True, 'desc' => 'Error');
    $ret = $this->verify();
    if( ! $ret['error']){
      $ret['error'] = True;
      $p = $this->promoParam();
      if($promo = Doctrine_Core::getTable('Promo')->getAll($p) ){
	$ret = $promo;
	$ret['error'] = False;
	$ret['desc'] = 'Promo All OK';
      }else{
	$ret['desc'] = 'Fetch All failed';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAPromoDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True, 'desc' => 'Error');
    $ret = $this->verify();
    $p = array();
    if( ! $ret['error']){
      $ret['error'] = True;
      $p = $this->promoParam();
      if($p['id']){
	if($status = Doctrine_Core::getTable('Promo')->delete($p['id']) ){
	  $ret['error'] = False;
	  $ret['id'] = $p['id'];
	}else{
	  $ret['desc'] = 'Promo Delete failed';
	}
      }else{
	$ret['desc'] = 'id param not found';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }
  
  #####################
  ## promo sent
  #####################

  private function promoSentParam(){
    $p = array();
    $request = $this->getRequest();
    $p['pid'] = intval($request->getParameter('pid'));
    $p['id'] = intval($request->getParameter('id'));
    $p['promo_user_id'] = intval($request->getParameter('promo_user_id'));
    $p['user_id'] = $this->user['id'];
    return $p;
  }

  public function executeAPromoSentNew(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True, 'desc' => 'Error');
    $ret = $this->verify();
    if( ! $ret['error']){
      $ret['error'] = True;
      $p = $this->promoSentParam();
      if($p['pid']){
	if($p['promo_user_id']){
	  if($send = Doctrine_Core::getTable('PromoSent')->newSent($p)){
	    $ret['desc'] = 'insert ok';
	    $ret['error'] = False;
	    $ret['record'] = $send;
	  }else{
	    $ret['desc'] = 'Promo Sent Insert Failed';
	  }
	}else{
	  $ret['desc'] = 'promo user param not found';
	}
      }else{
	$ret['desc'] = 'pid param not found';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAPromoSentEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True, 'desc' => 'Error');
    $ret = $this->verify();
    if( ! $ret['error']){
      $ret['error'] = True;
      $p = $this->promoSentParam();
      if($p['id']){
	if($p['pid']){
	  if($p['promo_user_id']){
	    if($send = Doctrine_Core::getTable('PromoSent')->updateSent($p)){
	      $ret['desc'] = 'edit ok';
	      $ret['error'] = False;
	      $ret['record'] = $send;
	    }else{
	      $ret['desc'] = 'Promo Sent Insert Failed';
	    }
	  }else{
	    $ret['desc'] = 'promo user param not found';
	  }
	}else{
	  $ret['desc'] = 'pid param not found';
	}
      }else{
	$ret['desc'] = 'id param not found';
      }  
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAPromoSentByParent(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True, 'desc' => 'Error');
    $ret = $this->verify();
    if( ! $ret['error']){
      $ret['error'] = True;
      $p = $this->promoSentParam();
      if($p['pid']){
	if($send = Doctrine_Core::getTable('PromoSent')->getAllByParent($p) ){
	  $ret['error'] = False;
	  $ret['desc'] = 'OK';
	  $ret['result'] = $send;
	}else{
	  $ret['desc'] = 'Fetch all failed';
	}
      }else{
	$ret['desc'] = 'pid param not found';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAPromoSentByUser(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = array('error' => True, 'desc' => 'Error');
    $ret = $this->verify();
    if( ! $ret['error']){
      $ret['error'] = True;
      $p = $this->promoSentParam();
      if($p['pid']){
	## in this case pid is actuall promo_user_id
	$p['promo_user_id'] = $p['pid'];
	if($send = Doctrine_Core::getTable('PromoSent')->getAllByUser($p) ){
	  $ret['error'] = False;
	  $ret['desc'] = 'OK';
	  $ret['result'] = $send;
	}else{
	  $ret['desc'] = 'Fetch all failed';
	}
      }else{
	$ret['desc'] = 'pid param not found';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  public function executeAPromoSentDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = $this->verify();
    $p = array();
    if( ! $ret['error']){
      $ret['error'] = True;
      $p = $this->promoSentParam();
      if($p['id']){
	if($status = Doctrine_Core::getTable('PromoSent')->delete($p['id']) ){
	  $ret['error'] = False;
	  $ret['desc'] = 'Delete ok';
	  $ret['id'] = $p['id'];
	  $ret['pid'] = $p['pid'];
	}else{
	  $ret['desc'] = 'Delete failed';
	}
      }else{
	$ret['desc'] = 'id param not found';
      }
    }
    $json = json_encode($ret);
    return $this->renderText($json);
  }

  private function promoSendValidate(){
    $ret = $this->verify();
    if( ! $ret['error']){
      $ret['error'] = True;
      $p = $this->promoSentParam();
      if($p['id']){
	if($send = Doctrine_Core::getTable('PromoSent')->getRec($p['id'])){
	  if($send['promo_email']){
	    $prior_send = Doctrine_Core::getTable('PromoSent')->priorByEmail($send);
	    if( ! $prior_send){
	      if( ! Doctrine_Core::getTable('PromoStop')->getStop($send['promo_email'])){
		$ret['send'] = $send;
		$ret['error'] = False;
	      }else{
		$ret['desc'] = $send['promo_email'] . ' has unsubscribed';
	      }
	    }else{
	      $ret['desc'] = $send['promo_email'] . ' has already been sent this promo (' . $send['pid'] .')';
	    }
	  }else{
	    $ret['desc'] = 'Email to not found';
	  }
	}else{
	  $ret['desc'] = 'Promo Sent not restored';
	}
      }else{
	$ret['desc'] = 'Promo Sent id not found';
      }
    }
    return $ret;
  }
  
  public function executePromo(sfWebRequest $request){
    $ret = $this->promoSendValidate();
    if( ! $ret['error']){
      $send = $ret['send'];
      $send['code'] = fbLib::randomHash(5);
      $send['status'] = 5;
      $ret['error'] = False;
      $this->setTemplate('promoEmailPreview');
    }else{
      $this->setTemplate('promoEmailError');
    }
    $this->status = $ret;
    $this->p = $send;
  }
  
  public function executePromoSend(sfWebRequest $request){
    $ret = $this->promoSendValidate();
    if( ! $ret['error']){
      $ret['error'] = False;
      $ret['desc'] = 'Promo message sent!';
      $send = $ret['send'];
      $send['code'] = fbLib::randomHash(5);
      $send['status'] = 5;
      $send = Doctrine_Core::getTable('PromoSent')->updateSent($send);
      $mailer = $this->getMailer();
      $body = $this->getPartial('promoEmail1',array('p' => $send));
      $mail = $mailer->compose($send['email_from'],$send['promo_email'],$send['subject'],$body);
      $mail->setContentType('text/html');
      $mailer->send($mail);
      $this->setTemplate('promoEmailOk');
    }else{
      $this->setTemplate('promoEmailError');
    }
    $this->status = $ret;
    $this->p = $send;
  }

  public function executePromoStop(sfWebRequest $request){
    $ret = array('error' => True, 'desc' => 'Email parameter not found');
    $email = $request->getParameter('email');
    if($email){
      if( Doctrine_Core::getTable('PromoStop')->getStop($email) ){
	$ret['desc'] = 'The email address ' . $email . ' has already been added to the no contact list for FishBlab.com marketing campaigns';
      }else{
	Doctrine_Core::getTable('PromoStop')->addStop($email);
	$ret['desc'] = 'The email address ' . $email . ' has been added to the no contact list for FishBlab.com marketing campaigns';
	$ret['error'] = False;
      }
    }
    $this->status = $ret;
  }

}
