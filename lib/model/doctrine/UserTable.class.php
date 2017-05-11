<?php

class UserTable extends Doctrine_Table{

  const USER_RECORD_LIMIT = 25;

  public $adminMode = False;
  
  public static function getInstance(){
    return Doctrine_Core::getTable('User');
  }

  private function isAdmin($userSec){
    if($userSec){
      if( ($userSec >= 80) and ($userSec <= 90) ){
	return True;
      }
    }
    return False;
  }

  public function userNew($p){
    $p['fb_status'] = ($p['fb_status'] ? $p['fb_status'] : 0);
    $p['active'] = ($p['active'] ? 1 : 0);
    $user = new User;
    $user->setUsername(strip_tags($p['username']));
    $user->setEmail(strip_tags($p['email']));
    $hash = fbLIb::createPasswordHash($p['password']);
    $user->setPassword($hash);
    $user->setDateCreate(new Doctrine_Expression('NOW()'));
    $user->setFirstname(strip_tags($p['firstname']));
    $user->setLastname(strip_tags($p['lastname']));
    $user->setTitle(strip_tags($p['title']));
    $user->setWebsite(strip_tags($p['website']));
    $user->setLocation(strip_tags($p['location']));
    $user->setAbout(strip_tags($p['about']));
    $user->setCompany(strip_tags($p['company']));
    $user->setPhone(strip_tags($p['phone']));
    $user->setUtype(intval($p['utype']));
    $user->setLat(floatval($p['lat']));
    $user->setLon(floatval($p['lon']));
    $user->setMsgDisc(1);
    $user->setMsgReply(1);
    $user->setMsgUpdate(1);
    $user->setMsgStop(0);
    $user->setActive($p['active']);
    $user->setFbStatus($p['fb_status']);
    $user->save();
    $user_id = $user->getId();
    return $user_id;
  }
  
  public function addUser($p){
    $p['active'] = True;
    if($user_id = $this->userNew($p)){
      return $this->getUserById($user_id);
    }
    return False;
  }
  
  public function updateUser($p){
    if($user_id = $this->editUser($p)){
      return $this->getUserById($user_id);
    }
    return False;
  }

  public function editUser($p){
    if($user = $this->getInstance()->find($p['id'])){
      $val = trim(strip_tags($p['username']));
      if($val){
	$user->setUsername($val);
      }
      $val = trim(strip_tags($p['email']));
      if($val){
	$user->setEmail($val);
      }
      #if($p['password'] and (strlen($p['password']) > 1) ){
      #$hash = fbLib::createPasswordHash($p['password']);
      #$user->setPassword($hash);
      #}
      $val = trim(strip_tags($p['firstname']));
      $user->setFirstname($val);
      $val = trim(strip_tags($p['lastname']));
      $user->setLastname($val);
      $val = trim(strip_tags($p['title']));
      $user->setTitle($val);
      $val = trim(strip_tags($p['website']));
      $user->setWebsite($val);
      $val = trim(strip_tags($p['location']));
      $user->setLocation($val);
      $val = trim(strip_tags($p['about']));
      $user->setAbout($val);
      $user->setUtype(intval($p['utype']));
      $val = trim(strip_tags($p['company']));
      $user->setCompany($val);
      if($val = trim(strip_tags($p['phone']))){
	$user->setPhone($val);
      }
      if($p['fb_status']){
	$user->setFbStatus($p['fb_status']);
      }
      $user->save();
      $user_id = $user->getId();
      return $user_id;
    }
    return False;
  }


  public function updateUserGeo($post){
    if($user = $this->find($post['id'])){
      if($geo = $post['geo']){
	if($geo['lat'] and $geo['lon']){
	  $user->setLat(floatval($geo['lat']));
	  $user->setLon(floatval($geo['lon']));
	  $user->save();
	  $user_new = $this->getUserById($user['id']);
	  return $user_new;
	}
      }
    }
    return False;
  }
  
  public function activate($id){
    if($user = $this->find($id)){
      $user->setActive(1);
      $user->save();
      $user_new = $this->getUserById($id);
      return $user_new;
    }
    return False;
  }

  public function editGeo($p){
    if($user = $this->find($p['id'])){
      if($geo = $p['geo']){
	if($geo['lat'] and $geo['lon']){
	  $user->setLat(floatval($geo['lat']));
	  $user->setLon(floatval($geo['lon']));
	  $user->save();
	  return $user->getId();
	}
      }
    }
    return False;
  }

  public function updateUserPhoto($post){
    if($user = $this->find($post['user_id'])){
      if($post['photo_id']){
	$user->setPhotoId(intval($post['photo_id']));
	$user->save();
	return True;
      }
    }
    return False;
  }
  
  public function updateUserPassword($user_id,$password){
    if($user = $this->find($user_id)){
      if($password){
	$hash = fbLib::createPasswordHash($password);
	$user->setPassword($hash);
	$user->save();
	return True;
      }
    }
    return False;
  }

  # email notify settings
  public function updateUserMsg($param){
    if($user = $this->getInstance()->find($param['user_id'])){
      if($param['msg_disc']){
	$user->setMsgDisc(1);
      }else{
	$user->setMsgDisc(0);
      }
      if($param['msg_reply']){
	$user->setMsgReply(1);
      }else{
	$user->setMsgReply(0);
      }
      if($param['msg_update']){
	$user->setMsgUpdate(1);
      }else{
	$user->setMsgUpdate(0);
      }
      if($param['msg_stop']){
	$user->setMsgStop(1);
      }else{
	$user->setMsgStop(0);
      }
      $user->save();
      return True;
    }
    return False;
  }

    public function getUserSec($username){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('User u')
	->leftJoin('u.UserType ut')
	->where('u.username = ?', $username)
	->andWhere('u.active = 1');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $fish = new fbUserFish();
      $row = $rows[0];
      if($row){
	$rec = $this->sqlRow($row);
	$rec['jrjSec'] = $this->isAdmin($row['u_fb_status']);
	$rec['email'] = $row['u_email'];
	$rec['password'] = $row['u_password'];
	return $rec;
      }else{
	return False;
      }
    }

    public function getUser($username){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('User u')
	->leftJoin('u.UserType ut')
	->where('u.username = ?', $username)
	->andWhere('u.active = 1');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $fish = new fbUserFish();
      $row = $rows[0];
      if($row){
	$rec = $this->sqlRow($row);
	$rec['jrjSec'] = $this->isAdmin($row['u_fb_status']);
	$rec['msg_disc'] = intval( $row['u_msg_disc'] );
	$rec['msg_reply'] = intval( $row['u_msg_reply'] );
	$rec['msg_update'] = intval( $row['u_msg_update'] );
	$rec['msg_stop'] = intval( $row['u_msg_stop'] );
	return $rec;
      }
      return False;
    }

    public function getRec($user_id){
      return $this->getUserById($user_id);
    }

    public function getUserById($user_id){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('User u')
	->leftJoin('u.UserType ut')
	->where('u.id = ?', $user_id)
	->andWhere('u.active = 1');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $row = $rows[0];
      if($row){
	$rec = $this->sqlRow($row);
	#	$rec['groups'] = Doctrine_Core::getTable('UserGroup')->groupsByUserIdShort($rec['id']);
	# NONONO obj cause loop	$rec['fish_count'] = $fish->fishCount($rec['id']);
	$rec['jrjSec'] = $this->isAdmin($row['u_fb_status']);
	$rec['email'] = $row['u_email'];	
	$rec['msg_disc'] = intval( $row['u_msg_disc'] );
	$rec['msg_reply'] = intval( $row['u_msg_reply'] );
	$rec['msg_update'] = intval( $row['u_msg_update'] );
	$rec['msg_stop'] = intval( $row['u_msg_stop'] );
	return $rec;
      }
      return False;
    }

    public function getUserByEmail($email){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('User u')
	->leftJoin('u.UserType ut')
	->where('u.email = ?', $email)
	->andWhere('u.active = 1');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      #$fish = new fbUserFish();
      $row = $rows[0];
      if($row){
	$rec = $this->sqlRow($row);
	#	$rec['groups'] = Doctrine_Core::getTable('UserGroup')->groupsByUserIdShort($rec['id']);
	#$rec['fish_count'] = $fish->fishCount($rec['id']);
	$rec['jrjSec'] = $this->isAdmin($row['u_fb_status']);
	$rec['email'] = $row['u_email'];
	$rec['msg_disc'] = intval( $row['u_msg_disc'] );
	$rec['msg_reply'] = intval( $row['u_msg_reply'] );
	$rec['msg_update'] = intval( $row['u_msg_update'] );
	$rec['msg_stop'] = intval( $row['u_msg_stop'] );
	return $rec;
      }else{
	return False;
      }
    }

    public function userByNameLike($username){
      $q = Doctrine_Query::create()
	->select('u.username')
	->from('User u')
	->leftJoin('u.UserType ut')
	->where('u.username LIKE ?', $username . '%')
	->orderBy('u.username')
	->andWhere('u.active = 1');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $recs = array();
      if(count($rows) > 0 ){
	foreach($rows as $i => $row){
	  $recs[$i]['name'] = $row['u_username'];
	  $recs[$i]['label'] = $row['u_username'];
	  $recs[$i]['id'] = $row['u_username'];
	}
      }
      return $recs;
    }

    public function userByNameLikeCont($username){
      $q = Doctrine_Query::create()
	->select('u.id,u.username')
	->from('User u')
	->leftJoin('u.UserType ut')
	->where('u.username LIKE ?', '%' . $username . '%')
	->orderBy('u.username')
	->andWhere('u.active = 1');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $recs = array();
      if(count($rows) > 0 ){
	foreach($rows as $i => $row){
	  $recs[$i]['name'] = $row['u_username'];
	  $recs[$i]['label'] = $row['u_username'];
	  $recs[$i]['id'] = intval($row['u_id']);
	}
      }
      return $recs;
    }
    
    public function usernameExists($username){
      $q = Doctrine_Query::create()
	->select('id')
	->from('User u')
	->where('u.username = ?', $username);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      if(count($rows) > 0 ){
	return True;
      }else{
	return False;
      }
    }

    public function emailExists($email){
      $q = Doctrine_Query::create()
	->select('id')
	->from('User u')
	->where('u.email = ?', $email)
	->andWhere('u.active = 1');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      if(count($rows) > 0){
	return $rows[0]['u_id'];
      }else{
	return False;
      }
    }

    public function userDisabled($username){
      $q = Doctrine_Query::create()
	->select('id')
	->from('User u')
	->where('u.username = ?', $username)
	->andWhere('u.active = 0');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      if(count($rows) > 0 ){
	return True;
      }else{
	return False;
      }
    }

    public function deletePhoto($user_id){
      if($user = $this->find($user_id)){
	$user->setPhotoId(0);
	$user->save();
	return True;
      }
      return False;
    }

    public function sqlSelect(){
      $sql = 'u.id,u.username,u.email,UNIX_TIMESTAMP(u.date_create) AS uts,DATE_FORMAT(u.date_create,\'%c/%e/%y\') AS date_create,u.utype,u.firstname,u.lastname,u.title,u.website,u.location,u.about,u.lat,u.lon,u.msg_disc,u.msg_reply,u.msg_update,u.msg_stop,u.photo_id,ut.description,u.fb_status,u.company,u.phone,u.password';
      return $sql;
    }

    private function sqlRow($row){
      $rec = array();
      $rec['id'] = intval($row['u_id']);
      $rec['username'] = $row['u_username'];
      $rec['date_create'] = $row['u_date_create'];
      $rec['date'] = $row['u_date_create'];
      $rec['date_time'] = $row['u_date_create'];
      $rec['uts'] = $row['u_uts'];
      $rec['firstname'] = $row['u_firstname'];
      $rec['lastname'] = $row['u_lastname'];
      $rec['title'] = $row['u_title'];
      $rec['website'] = $row['u_website'];
      $rec['location'] = $row['u_location'];
      $rec['utype'] = intval($row['u_utype']);
      $rec['utype_text'] = $row['ut_description'];
      $rec['about'] = $row['u_about'];
      $rec['company'] = $row['u_company'];
      $rec['phone'] = $row['u_phone'];
      $rec['lat'] = floatval($row['u_lat']);
      $rec['lon'] = floatval($row['u_lon']);
      $rec['photo_id'] = intval($row['u_photo_id']);
      return $rec;
    }

    public function getPubUsers($limit = 10){
      $recs = array();
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('User u')
	->leftJoin('u.UserType ut')
	->andWhere('u.active = 1')
	->orderBy('u.date_create DESC')
	->limit($limit);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $user_id = fbLib::getUserId();
      $fish = new fbUserFish();
      if(count($rows) > 0 ){
	foreach($rows as $i => $row){
	  $rec = $this->sqlRow($row);
#	  $rec['groups'] = Doctrine_Core::getTable('UserGroup')->groupsByUserIdShort($rec['id']);
	  $rec['fish_count'] = $fish->fishCount($rec['id']);
	  $rec['jrjSec'] = $this->isAdmin($row['u_fb_status']);
	  $rec['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($user_id,$rec['id']);
	  $recs[] = $rec;
	}
      }
      return $recs;
    }
    
    public function getPubUsersBBCount(){
      $q = Doctrine_Query::create()
	->select('COUNT(*) AS user_count')
	->from('User u')
	->andWhere('u.active = 1');
      fbLib::addBoundsSQL($q,'u');
      fbLib::addFishSQL($q, array('source'=>'u', 'fish_table'=>'FishForUser', 'fish_table_alias'=>'ffa'));
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      return intval($rows[0]['u_user_count']);
    }

    public function getPubUsersBB($offset = 0){
      $count = $this->getPubUsersBBCount();
      $recs = array();
      $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		   'record_offset' => intval($offset),
		   'record_limit' => UserTable::USER_RECORD_LIMIT);
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('User u')
	->leftJoin('u.UserType ut')
	->andWhere('u.active = 1')
	->orderBy('u.date_create DESC')
	->limit(UserTable::USER_RECORD_LIMIT);
      fbLib::addBoundsSQL($q,'u');
      fbLib::addFishSQL($q, array('source'=>'u', 'fish_table'=>'FishForUser', 'fish_table_alias'=>'ffa'));
      if($offset > 0){
	$q->offset($offset * UserTable::USER_RECORD_LIMIT);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $user_id = fbLib::getUserId();
      $fish = new fbUserFish();
      if(count($rows) > 0 ){
	foreach($rows as $i => $row){
	  $rec = $this->sqlRow($row);
#	  $rec['groups'] = Doctrine_Core::getTable('UserGroup')->groupsByUserIdShort($rec['id']);
	  $rec['fish_count'] = $fish->fishCount($rec['id']);
	  $rec['jrjSec'] = $this->isAdmin($row['u_fb_status']);
	  $rec['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($user_id,$rec['id']);
	  $recs[] = $rec;
	}
	$ret['records'] = $recs;
	$ret['count'] = count($recs);
      }
      return $ret;
    }

    public function getPubUsersBBGWCount(){
      $q = Doctrine_Query::create()
	->select('COUNT(*) AS user_count')
	->from('User u')
	->andWhere('u.active = 1');
      fbLib::addBoundsSQL($q,'u');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      return intval($rows[0]['u_user_count']);
    }
    
    public function getPubUsersBBGW($p){
      if( ! $p['limit']){
	$p['limit'] = UserTable::USER_RECORD_LIMIT;
      }
      if( ! $p['offset']){
	$p['offset'] = 0;
      }
      $count = $this->getPubUsersBBGWCount();
      $recs = array();
      $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		   'record_offset' => $p['offset'], 'record_limit' => $p['limit']);
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('User u')
	->leftJoin('u.UserType ut')
	->andWhere('u.active = 1')
	->orderBy('u.date_create DESC');
      fbLib::addBoundsSQL($q,'u');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $user_id = fbLib::getUserId();
      $fish = new fbUserFish();
      if(count($rows) > 0 ){
	foreach($rows as $i => $row){
	  $rec = $this->sqlRow($row);
#	  $rec['groups'] = Doctrine_Core::getTable('UserGroup')->groupsByUserIdShort($rec['id']);
	  $rec['fish_count'] = $fish->fishCount($rec['id']);
	  $rec['jrjSec'] = $this->isAdmin($row['u_fb_status']);
	  $rec['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($user_id,$rec['id']);
	  $recs[] = $rec;
	}
	$ret['records'] = $recs;
	$ret['count'] = count($recs);
      }
      return $ret;
    }

    public function usersByFishIdCount($fish_id){
      $q = Doctrine_Query::create()
	->select('COUNT(*) AS user_count')
	->from('User u')
	->innerJoin('u.FishForUser ffu')
	->where('u.active = 1')
	->andWhere('ffu.fish_id = ?',$fish_id);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      return intval($rows[0]['u_user_count']);
    }
    
    public function usersByFishId($p){
      $fish_id = $p['fish_id'];
      if( ! $p['limit']){
	$p['limit'] = UserTable::USER_RECORD_LIMIT;
      }
      if( ! $p['offset']){
	$p['offset'] = 0;
      }
      $count = $this->usersByFishIdCount($fish_id);
      $recs = array();
      $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		   'record_offset' => $p['offset'], 'record_limit' => $p['limit']);
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('User u')
	->innerJoin('u.FishForUser ffu')
	->leftJoin('u.UserType ut')
	->where('u.active = 1')
	->andWhere('ffu.fish_id = ?',$fish_id)
	->orderBy('u.date_create DESC');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $user_id = fbLib::getUserId();
      if(count($rows) > 0 ){
	foreach($rows as $i => $row){
	  $rec = $this->sqlRow($row);
#	  $rec['groups'] = Doctrine_Core::getTable('UserGroup')->groupsByUserIdShort($rec['id']);
	  $rec['fish_count'] = $fish->fishCount($rec['id']);
	  $rec['jrjSec'] = $this->isAdmin($row['u_fb_status']);
	  $rec['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($user_id,$rec['id']);
	  $recs[] = $rec;
	}
	$ret['records'] = $recs;
	$ret['count'] = count($recs);
      }
      return $ret;
    }

    public function userSearchCount($param){
      $q = Doctrine_Query::create()
	->select('COUNT(*) AS user_count')
	->from('User u')
	->where('u.active = 1');
      fbLib::addBoundsSQL($q,'u');
      fbLib::addSearchSQL($q,$param);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      return intval($rows[0]['u_user_count']);
    }

    public function userSearch($param,$offset = 0){
      $count = $this->userSearchCount($param);
      $recs = array();
      $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		   'record_offset' => intval($offset),
		   'record_limit' => UserTable::USER_RECORD_LIMIT);
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('User u')
	->leftJoin('u.UserType ut')
	->where('u.active = 1')
	->orderBy('u.date_create DESC')
	->limit(UserTable::USER_RECORD_LIMIT);
      fbLib::addBoundsSQL($q,'u');
      fbLib::addSearchSQL($q,$param);
      if($offset > 0){
	$q->offset($offset * UserTable::USER_RECORD_LIMIT);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $user_id = fbLib::getUserId();
      $fish = new fbUserFish();
      if(count($rows) > 0 ){
	foreach($rows as $i => $row){
	  $rec = $this->sqlRow($row);
#	  $rec['groups'] = Doctrine_Core::getTable('UserGroup')->groupsByUserIdShort($rec['id']);
	  $rec['fish_count'] = $fish->fishCount($rec['id']);
	  $rec['jrjSec'] = $this->isAdmin($row['u_fb_status']);
	  $rec['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($user_id,$rec['id']);
	  $recs[] = $rec;
	}
      }
      $ret['records'] = $recs;
      $ret['count'] = count($recs);
      return $ret;
    }

    public function getFriendsCount($user_id,$bounds = true){
      $q = Doctrine_Query::create()
	->select('COUNT(*) AS user_count')
	->from('User u')
	->innerJoin('u.UserForFriend uff')
	->where('uff.user_id = ?', $user_id)
	->andWhere('u.active = 1');
      if($bounds){
	fbLib::addBoundsSQL($q,'u');
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      return intval($rows[0]['u_user_count']);
    }
    
    public function getFriends($user_id,$bounds = true){
      $count = $this->getFriendsCount($user_id,$bounds);
      $recs = array();
      $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		   'record_offset' => intval($offset),
		   'record_limit' => UserTable::USER_RECORD_LIMIT);
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('User u')
	->innerJoin('u.UserForFriend uff')
	->leftJoin('u.UserType ut')
	->orderBy('uff.ts DESC')
	->where('uff.user_id = ?', $user_id)
	->andWhere('u.active = 1');
      if($bounds){
	fbLib::addBoundsSQL($q,'u');
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $fish = new fbUserFish();
      if(count($rows) > 0 ){
	foreach($rows as $i => $row){
	  $rec = $this->sqlRow($row);
#	  $rec['groups'] = Doctrine_Core::getTable('UserGroup')->groupsByUserIdShort($rec['id']);
	  $rec['fish_count'] = $fish->fishCount($rec['id']);
	  $rec['jrjSec'] = $this->isAdmin($row['u_fb_status']);
	  $rec['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($user_id,$rec['id']);
	  $recs[] = $rec;
	}
	$ret['records'] = $recs;
	$ret['count'] = count($recs);
      }
      return $ret;
    }

    public function getFriendReqCount($user_id){
      $q = Doctrine_Query::create()
	->select('COUNT(*) AS user_count')
	->from('User u')
	->innerJoin('u.UserForFriendReq uff')
	->where('uff.friend_id = ?', $user_id)
	->andWhere('u.active = 1');
      fbLib::addBoundsSQL($q,'u');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      return intval($rows[0]['u_user_count']);
    }
    
    public function getFriendReq($user_id){
      $count = $this->getFriendReqCount($user_id);
      $recs = array();
      $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		   'record_offset' => intval($offset),
		   'record_limit' => UserTable::USER_RECORD_LIMIT);

      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('User u')
	->innerJoin('u.UserForFriendReq uff')
	->leftJoin('u.UserType ut')
	->orderBy('uff.ts DESC')
	->where('uff.friend_id = ?', $user_id)
	->andWhere('u.active = 1');
      fbLib::addBoundsSQL($q,'u');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $fish = new fbUserFish();
      if(count($rows) > 0 ){
	$recs = array();
	foreach($rows as $i => $row){
	  $rec = $this->sqlRow($row);
#	  $rec['groups'] = Doctrine_Core::getTable('UserGroup')->groupsByUserIdShort($rec['id']);
	  $rec['fish_count'] = $fish->fishCount($rec['id']);
	  $rec['jrjSec'] = $this->isAdmin($row['u_fb_status']);
	  $rec['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($user_id,$rec['id']);
	  $recs[] = $rec;
	}
	$ret['records'] = $recs;
	$ret['count'] = count($recs);
      }
      return $ret;
    }
    
    public function getFriendBlockCount($user_id){
      $q = Doctrine_Query::create()
	->select('COUNT(*) AS user_count')
	->from('User u')
	->innerJoin('u.UserForFriendBlock uff')
	->where('uff.user_id = ?', $user_id)
	->andWhere('u.active = 1');
      fbLib::addBoundsSQL($q,'u');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      return intval($rows[0]['u_user_count']);
    }
    
    public function getFriendBlock($user_id){
      $count = $this->getFriendBlockCount($user_id);
      $recs = array();
      $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		   'record_offset' => intval($offset),
		   'record_limit' => UserTable::USER_RECORD_LIMIT);
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('User u')
	->innerJoin('u.UserForFriendBlock uff')
	->leftJoin('u.UserType ut')
	->orderBy('uff.ts DESC')
	->where('uff.user_id = ?', $user_id)
	->andWhere('u.active = 1');
      fbLib::addBoundsSQL($q,'u');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $fish = new fbUserFish();
      if(count($rows) > 0 ){
	$recs = array();
	foreach($rows as $i => $row){
	  $rec = $this->sqlRow($row);
#	  $rec['groups'] = Doctrine_Core::getTable('UserGroup')->groupsByUserIdShort($rec['id']);
	  $rec['fish_count'] = $fish->fishCount($rec['id']);
	  $rec['jrjSec'] = $this->isAdmin($row['u_fb_status']);
	  $rec['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($user_id,$rec['id']);
	  $recs[] = $rec;
	}
	$ret['records'] = $recs;
	$ret['count'] = count($recs);
      }
      return $ret;
    }
    
    public function friendStatus($user_id,$friend_id){
      $status = 'none';
      if( (! $user_id) or ( ! $friend_id) ){
	return $status;
      }
      ## is the user being blocked by the potential friend?
      $q = Doctrine_Query::create()
	->select('user_id,friend_id,ts')
	->from('UserForFriendBlock u')
	->where('u.user_id = ?', $friend_id)
	->andWhere('u.friend_id = ?', $user_id);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      if(count($rows) > 0 ){
	$status = 'block_from';
      }else{
	## is the user blocking this potential friend?
	$q = Doctrine_Query::create()
	  ->select('user_id,friend_id,ts')
	  ->from('UserForFriendBlock u')
	  ->where('u.user_id = ?', $user_id)
	  ->andWhere('u.friend_id = ?', $friend_id);
	$rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
	if(count($rows) > 0 ){
	  $status = 'block_to';
	}else{
	  ## is this user a friend?
	  $q = Doctrine_Query::create()
	    ->select('user_id,friend_id,ts')
	    ->from('UserForFriend u')
	    ->where('u.user_id = ?', $user_id)
	    ->andWhere('u.friend_id = ?', $friend_id);
	  $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
	  if(count($rows) > 0 ){
	    $status = 'friend';
	  }else{
	    ## is there a request from this user to you?
	    $q = Doctrine_Query::create()
	      ->select('user_id,friend_id,ts')
	      ->from('UserForFriendReq r')
	      ->where('r.user_id = ?', $friend_id)
	      ->andWhere('r.friend_id = ?', $user_id);
	    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
	    if(count($rows) > 0 ){
	      $status = 'request_to';
	    }else{
	      ## is there a request from you to this user?
	      $q = Doctrine_Query::create()
		->select('user_id,friend_id,ts')
		->from('UserForFriendReq r')
		->where('r.user_id = ?', $user_id)
		->andWhere('r.friend_id = ?', $friend_id);
	      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
	      if(count($rows) > 0 ){
		$status = 'request_from';
	      }
	    }
	  }
	}
      }
      return $status;
    }

    public function getUserGW($username){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('User u')
	->leftJoin('u.UserType ut')
	->where('u.username = ?', $username)
	->andWhere('u.active = 1');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $fish = new fbUserFish();
      $p = array('limit' => 10, 'offset' => 0);
      $user_id = fbLib::getUserId();
      $row = $rows[0];
      if(count($rows) > 0 ){
	$rec = $this->sqlRow($row);
	$p['user_id'] = $rec['id'];
#	$rec['groups'] = Doctrine_Core::getTable('UserGroup')->groupsByUserIdShort($rec['id']);
	$rec['fish_count'] = $fish->fishCount($rec['id']);
	$rec['jrjSec'] = $this->isAdmin($row['u_fb_status']);
	$rec['blogs'] = Doctrine_Core::getTable('Blog')->blogsByUserId($p);
	$rec['photos'] = Doctrine_Core::getTable('File')->photosByUserId($p);
	$rec['reports'] = Doctrine_Core::getTable('Report')->reportsByUserId($p);
	$rec['spots'] = Doctrine_Core::getTable('Spot')->spotsByUserId($p);
	$rec['discs'] = Doctrine_Core::getTable('Disc')->discsByUserId($p);
	$rec['groups'] = Doctrine_Core::getTable('UserGroup')->groupsByUserId($rec['id']);
	$rec['friend_status'] = Doctrine_Core::getTable('User')->friendStatus($user_id,$rec['id']);
	return $rec;
      }
      return False;
    }

    private function sqlRowAdmin($row){
      $rec = $this->sqlRow($row);
      $rec['email'] = $row['u_email'];
      return $rec;
    }
    
    public function adminUsersBBCount(){
      $q = Doctrine_Query::create()
	->select('COUNT(*) AS user_count')
	->from('User u')
	->where('u.active = 0');
      fbLib::addBoundsSQL($q,'u');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      return intval($rows[0]['u_user_count']);
    }

    public function adminUsersBB($offset = 0){
      $count = $this->adminUsersBBCount();
      $recs = array();
      $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		   'record_offset' => intval($offset),
		   'record_limit' => UserTable::USER_RECORD_LIMIT);
      if($count){
	$q = Doctrine_Query::create()
	  ->select($this->sqlSelect())
	  ->from('User u')
	  ->leftJoin('u.UserType ut')
	  ->where('u.active = 0')
	  ->orderBy('u.date_create DESC')
	  ->limit(UserTable::USER_RECORD_LIMIT);
	fbLib::addBoundsSQL($q,'u');
	if($offset > 0){
	  $q->offset($offset * UserTable::USER_RECORD_LIMIT);
	}
	$rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
	if(count($rows) > 0 ){
	  foreach($rows as $i => $row){
	    $recs[$i] = $this->sqlRowAdmin($row);
	  }
	  $ret['records'] = $recs;
	  $ret['count'] = count($recs);
	}
      }
      return $ret;
    }

    public function adminNew($p){
      $p['active'] = False;
      if($user_id = $this->userNew($p)){
	return $this->adminById($user_id);
      }
      return False;
    }
 
    public function adminEdit($p){
      if($user_id = $this->editUser($p)){
	return $this->adminById($user_id);
      }
      return False;
    }
 
    public function adminEditGeo($p){
      if($user_id = $this->editGeo($p)){
	return $this->adminById($user_id);
      }
      return False;      
    }
    
    public function adminById($user_id){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('User u')
	->leftJoin('u.UserType ut')
	->andWhere('u.id = ?', $user_id);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $row = $rows[0];
      if($row){
	$rec = $this->sqlRowAdmin($row);
	return $rec;
      }
      return False;
    }

    public function adminUserByUsername($username){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('User u')
	->leftJoin('u.UserType ut')
	->andWhere('u.username = ?', $username);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $row = $rows[0];
      if($row){
	$rec = $this->sqlRowAdmin($row);
	return $rec;
      }
      return False;
    }

    public function adminUserByEmail($email){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('User u')
	->leftJoin('u.UserType ut')
	->andWhere('u.email = ?', $email);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $row = $rows[0];
      if($row){
	$rec = $this->sqlRowAdmin($row);
	return $rec;
      }
      return False;
    }

  public function adminActivate($user_id){
    if($user = $this->getInstance()->find($user_id)){
      $user->setActive(1);
      $user->save();
      $user_id = $user->getId();
      return $this->adminById($user_id);
    }
    return False;
  }

  public function adminDeactivate($user_id){
    if($user = $this->getInstance()->find($user_id)){
      $user->setActive(0);
      $user->save();
      $user_id = $user->getId();
      return $this->adminById($user_id);
    }
    return False;
  }

}