<?php

/** DiscReplyTable **/
class DiscReplyTable extends Doctrine_Table{

  const REPLY_RECORD_LIMIT = 25;

  public static function getInstance(){
    return Doctrine_Core::getTable('DiscReply');
  }
  
  public function addReply($post){
    $reply = new DiscReply();
    $reply->setPid(intval($post['pid']));
    $reply->setUserId(intval($post['user_id']));
    $content = strip_tags($post['text']);
    $content = trim($content);
    $reply->setContent($content);
    $reply->setDateCreate(new Doctrine_Expression('NOW()'));
    $reply->save();
    $reply_id = $reply->getId();
    $new_reply = $this->getReply($reply_id);
    return $new_reply;
  }
  
  public function editReply($post){
    $reply_id = $post['reply_id'];
    if($reply = $this->find($reply_id)){
      $content = strip_tags($post['text']);
      $content = trim($content);
      if(strlen($content) > 0){
	$reply->setContent($content);
      }
      $reply->save();
      if($new_reply = $this->getReply($reply_id)){
	return $new_reply;
      }
    }
    return False;
  }

  public function getReply($reply_id){
    $q = Doctrine_Query::create()
      ->select('r.id,r.pid,DATE_FORMAT(r.date_create,\'%c/%e/%y @ %r\') AS date_create,r.content,u.username,u.firstname,u.lastname,u.photo_id')
      ->from('DiscReply r')
      ->innerJoin('r.User u')
      ->where('r.id = ?',$reply_id);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    if($rows){
      if($row = $rows[0]){
	$rec = array();
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
    }
    return False;
  }

  public function getReplies($pid,$limit = DiscReplyTable::REPLY_RECORD_LIMIT,$offset = 0){
    $offset = intval($offset);
    $q = Doctrine_Query::create()
      ->select('r.id,r.pid,DATE_FORMAT(r.date_create,\'%c/%e/%y @ %r\') AS date_create,r.content,u.username,u.firstname,u.lastname,u.photo_id')
      ->from('DiscReply r')
      ->innerJoin('r.User u')
      ->where('r.pid = ?',$pid)
      ->orderBy('r.date_create DESC')
      ->limit($limit);
    if($offset > 0){
      $q->offset($offset * DiscReplyTable::REPLY_RECORD_LIMIT);
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
    return $recs;
  }
  
  public function getReplyCount($pid){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS reply_count')
      ->from('DiscReply r')
      ->innerJoin('r.User u')
      ->where('r.pid = ?',$pid);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['r_reply_count']);
  }

  public function deleteReply($reply_id){
    return Doctrine_Query::create()
      ->delete()
      ->from('DiscReply r')
      ->where('r.id = ?', $reply_id)
      ->execute();
  }

  public function getNotifies($pid,$username){
    $q = Doctrine_Query::create()
      ->select('r.user_id,u.username,u.email,u.msg_disc,u.msg_reply,u.msg_update,u.msg_stop')
      ->from('DiscReply r')
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