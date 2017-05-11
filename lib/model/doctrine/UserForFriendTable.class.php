<?php

/**
 * UserForFriendTable
 */
class UserForFriendTable extends Doctrine_Table{
  public static function getInstance(){
    return Doctrine_Core::getTable('UserForFriend');
  }
  
  public function addFriend($user_id,$friend_id){
    $uff = new UserForFriend();
    $uff->setUserId($user_id);
    $uff->setFriendId($friend_id);
    $uff->save();
    return True;
  }

  public function isFriend($user_id,$friend_id){
    $q = Doctrine_Query::create()
      ->select('user_id,friend_id,ts')
      ->from('UserForFriend uff')
      ->where('uff.user_id = ?', $user_id)
      ->andWhere('uff.friend_id = ?', $friend_id);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    if($rows and (count($rows) > 0) ){
      return True;
    }
    return False;
  }

  public function getFriendIds($user_id){
    $q = Doctrine_Query::create()
      ->select('user_id,friend_id')
      ->from('UserForFriend uff')
      ->where('uff.user_id = ?', $user_id);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    if($rows and (count($rows) > 0) ){
      $recs = array();
      foreach($rows as $i => $row){
	$recs[$i] = $row['uff_friend_id'];
      }
      return $recs;
    }
    return False;
  }

  public function getFriend($user_id,$friend_id){
    $q = Doctrine_Query::create()
      ->select('user_id,friend_id,ts')
      ->from('UserForFriend uff')
      ->where('uff.user_id = ?', $user_id)
      ->andWhere('uff.friend_id = ?', $friend_id);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    if($rows and (count($rows) > 0) ){
      $row = $rows[0];
      $recs = array('user_id' => $row['uff_user_id'],'friend_id' => $row['uff_friend_id'],'ts' => $row['uff_ts']);
      return $recs;
    }
    return False;
  }


  public function delFriend($user_id,$friend_id){
    $q = Doctrine_Query::create()
      ->delete()
      ->from('UserForFriend uff')
      ->where('uff.user_id = ?', $user_id)
      ->andWhere('uff.friend_id = ?', $friend_id);
    $status1 = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $q = Doctrine_Query::create()
      ->delete()
      ->from('UserForFriend uff')
      ->where('uff.user_id = ?', $friend_id)
      ->andWhere('uff.friend_id = ?', $user_id);
    $status2 = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    if($status1 and $status2){
      return True;
    }
    return False;
  }

}