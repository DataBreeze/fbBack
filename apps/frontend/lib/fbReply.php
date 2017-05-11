<?php
  /**
   * base reply class used for all comments/replies
   * @author     Joe Junkin
   * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
   */

class fbReply{
  
  const MAX_REPLY = 20;
  
  public $response = array('error' => True, 'desc' => 'None');
  public $parentTableName = 'default';
  public $parentKey = 0;
  public $dateCol = 'date_create';
  public $replyTableName = 'default';
  public $offsetName = 'default';
  public $offset = 0;
  public $replyLimit = 20;
  public $type = 'default';
  public $mailURL = 'www.fishblab.com';
  
  function __construct($req){
    $fbLib = new fbLib();
    $this->user = $fbLib->restoreUser();
  }
  
  public function jsonResponse(){
    return json_encode($this->response);
  }

  public function validate(){
    $request = sfContext::getInstance()->getRequest();
    if($this->user['id']){
      return $this->validateParent();
    }else{
      $this->response['desc'] = 'User not logged in';
    }
    return False;
  }
  
  public function validateParent(){
    $request = sfContext::getInstance()->getRequest();
    if($request->hasParameter('pid')){
      $key = intval($request->getParameter('pid'));
      if($key > 0){
	return True;
      }else{
	$this->response['desc'] = 'Key parameter not valid';
      }
    }else{
      $this->response['desc'] = 'Key parameter not found';
    }
    return False;
  }
  
  public function validateEdit(){
    if($this->validate()){
      $request = sfContext::getInstance()->getRequest();
      if($request->hasParameter('id')){
	$reply_id = intval($request->getParameter('id'));
	if($reply_id > 0){
	  if($reply = Doctrine_Core::getTable($this->replyTableName)->getReply($reply_id)){
	    if($reply['username'] != $this->user['username']){
	      $this->response['desc'] = 'User not owner of comment';
	    }else{
	      return True;
	    }
	  }else{
	    $this->response['desc'] = 'Comment not found';
	  }
	}else{
	  $this->response['desc'] = 'reply id parameter not valid';
	}
      }
      $this->response['desc'] = 'reply id parameter not found';
    }
    return False;
  }
    
  public function create(){
    $request = sfContext::getInstance()->getRequest();
    if($this->validate()){
      $post = array('user_id' => $this->user['id']);
      $post['pid'] = $request->getParameter('pid');
      $post['text'] = $request->getParameter('content');
      if(strlen($post['text']) > 0){
	$reply = Doctrine_Core::getTable($this->replyTableName)->addReply($post);
	$reply_id = $reply['id'];
	if($reply_id > 0){
	  $this->response['desc'] = 'New comment saved';
	  $this->response['error'] = False;
	  $this->response['pid'] = $reply['pid'];
	  $this->response['id'] = $reply_id;
	  $this->response['record'] = $reply;
	  $this->notify($reply);
	  return True;
	}else{
	  $this->response['desc'] = 'Database Insert Failed for comment';
	}
      }else{
	$this->response['desc'] = 'Reply content is empty';
      }
    }
    return False;
  }

  public function edit(){
    $request = sfContext::getInstance()->getRequest();
    if($this->validateEdit()){
      $post = array('user_id' => $this->user['id']);
      $post['pid'] = intval($request->getParameter('pid'));
      $post['reply_id'] = intval($request->getParameter('id'));
      $post['text'] = $request->getParameter('content');
      if(strlen($post['text']) > 0){
	$reply = Doctrine_Core::getTable($this->replyTableName)->editReply($post);
	$reply_id = $reply['id'];
	if($reply_id > 0){
	  $this->response['desc'] = 'Comment saved';
	  $this->response['error'] = False;
	  $this->response['pid'] = $reply['pid'];
	  $this->response['id'] = $reply_id;
	  $this->response['record'] = $reply;
	  return True;
	}else{
	  $this->response['desc'] = 'Database Edit Failed for comment';
	}
      }else{
	$this->response['desc'] = 'Reply content is empty';
      }
    }
    return False;
  }
  
  public function delete(){
    $request = sfContext::getInstance()->getRequest();
    if($this->validateEdit()){
      $reply_id = intval($request->getParameter('id'));
      $status = Doctrine_Core::getTable($this->replyTableName)->deleteReply($reply_id);
      $this->response['desc'] = 'Comment deleted';
      $this->response['error'] = False;
      $this->response['pid'] = intval($request->getParameter('pid'));
      $this->response['id'] = $reply_id;
      return True;
    }
    return False;
  }

  public function getRepliesAjax(){
    $request = sfContext::getInstance()->getRequest();
    if($this->validateParent()){
      $pid = intval($request->getParameter('pid'));
      $offset = 0;
      if($request->hasParameter('reply_offset')){
	$offset = $request->getParameter('reply_offset');	
      }
      $this->response['result'] = $this->getReplies($pid);
      $this->response['pid'] = $pid;
      $this->response['error'] = False;
      $this->response['desc'] = 'Comments fetched';
      return True;
    }
    return False;
  }

  public function getReply($reply_id){
    $q = Doctrine_Query::create()
      ->select('r.id,r.pid,DATE_FORMAT(r.date_create,\'%c/%e/%y @ %r\') AS date_create,r.content,u.username,u.photo_id')
      ->from($this->replyTableName . ' r')
      ->innerJoin('r.User u')
      ->where('r.id = ?',$reply_id);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    if($rows && ($rec = $rows[0]) ){
      $rec['id'] = intval($row['r_id']);
      $rec['pid'] = intval($row['r_pid']);
      $rec['date_create'] = $row['r_date_create'];
      $rec['content'] = trim($row['r_content']);
      $rec['username'] = $row['u_username'];
      $rec['first'] = $row['u_firstname'];
      $rec['last'] = $row['u_lastname'];
      $rec['photo_id'] = intval($row['u_photo_id']);
      return $rec;
    }
    return False;
  }

  public function replyCount($pid){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS reply_count')
      ->from($this->replyTableName . ' r')
      ->where('r.pid = ?',$pid);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['r_reply_count']);
  }

  ## get all replies for an activity
  public function getReplies($pid){
    $count = $this->replyCount($pid);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => intval($this->offset), 'record_limit' => $this->replyLimit);
    if($count){
      $q = Doctrine_Query::create()
	->select('r.id,r.pid,DATE_FORMAT(r.date_create,\'%c/%e/%y @ %r\') AS date_create,r.content,u.username,u.photo_id')
	->from($this->replyTableName . ' r')
	->innerJoin('r.User u')
	->where('r.pid = ?',$pid)
	->orderBy('r.date_create DESC')
	->limit($this->replyLimit);
      if($this->offset > 0){
	$q->offset($this->offset * $this->replyLimit);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $recs = array();
      if($rows && (count($rows) > 0) ){
	foreach($rows as $i => $row){
	  $recs[$i]['id'] = intval($row['r_id']);
	  $recs[$i]['pid'] = intval($row['r_pid']);
	  $recs[$i]['date_create'] = $row['r_date_create'];
	  $recs[$i]['content'] = trim($row['r_content']);
	  $recs[$i]['username'] = $row['u_username'];
	  $recs[$i]['first'] = $row['u_firstname'];
	  $recs[$i]['last'] = $row['u_lastname'];
	  $recs[$i]['photo_id'] = intval($row['u_photo_id']);
	}
      }
      $ret['count'] = count($recs);
      $ret['records'] = $recs;
    }
   return $ret;
  }
  

  ## get count of replies and stats, but no records
  public function getRepliesEmpty($pid){
    $count = $this->replyCount($pid);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => intval($this->offset), 'record_limit' => $this->replyLimit);
    return $ret;
  }
  
  public function notify($reply){
    $parent = Doctrine_Core::getTable($this->parentTableName)->getRec($reply['pid']);
    ## note that this will not email users with 'no contact' settings
    $notifies = $this->getNotifies($reply['pid'],$reply['username']);
    $user_owner = Doctrine_Core::getTable('User')->getUser($parent['username']);
    if( ($user_owner['msg_stop'] != 1) and ($user_owner['msg_disc'] == 1) and ($user_owner['username'] != $reply['username']) ){
      $notifies[$user_owner['username']] = $user_owner['email'];
    }
    $mailer = sfContext::getInstance()->getMailer();
    $from = 'msg@fishblab.com';
    sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');
    $date = $reply[$this->dateCol];
    $user_reply = Doctrine_Core::getTable('User')->getUser($reply['username']);
    foreach($notifies as $username => $email){
      $user_notify = Doctrine_Core::getTable('User')->getUser($username);
      $caption = 'User ' . $user_reply['username'] . ' commented on ' . $this->type;
      $content = 'FishBlab User ' . $user_reply['username'] . ' has commented on the ' . $this->type .' on ' . $date;
      $url = 'http://' . $this->mailHost .'/'. $parent['year'] .'/'. $parent['month'] .'/'. $parent['day'] .'/'. $parent['id'];
      Doctrine_Core::getTable('UserNotify')->addNotify( array('user_from_id' => $user_reply['id'],'user_for_id' => $user_notify['id'], 'caption' => $caption,'content' => $content,'mtype' => 50, 'url' => $url) );
      $to = $email;
      $subject = 'FishBlab.com: Comment from ' . $reply['username'];
      $body = get_partial('global/replyNotifyMail',array('type' => $this->type, 'username' => $username,'email' => $email, 'parent' => $parent, 'reply' => $reply, 'mailHost' => $this->mailHost, 'date' => $date));
      $msg = $mailer->compose($from,$to,$subject,$body);
      $mailer->send($msg);
    }
    return True;
  }

  public function getNotifies($pid,$username){
    $q = Doctrine_Query::create()
      ->select('r.user_id,u.username,u.email,u.msg_disc,u.msg_reply,u.msg_update,u.msg_stop')
      ->from($this->replyTableName . ' r')
      ->innerJoin('r.User u')
      ->where('r.pid = ?',$pid)
      ->groupBy('u.username')
      ->orderBy('r.date_create DESC');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $hash = array();
    if($rows && (count($rows) > 0) ){
      foreach($rows as $i => $row){	
	if( ($row['u_msg_stop'] != 1) and ($row['u_msg_reply'] == 1) and ($row['u_username'] != $username) ){
	  $hash[$row['u_username']] = $row['u_email'];
	}
      }
    }
    return $hash;
  }
  
}