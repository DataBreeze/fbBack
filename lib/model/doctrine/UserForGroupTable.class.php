<?php

/**
 * UserForGroupTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class UserForGroupTable extends Doctrine_Table{
  const MEMBER_RECORD_LIMIT = 30;
  public static function getInstance(){
    return Doctrine_Core::getTable('UserForGroup');
  }

  public function add($p){
    $ufg = new UserForGroup();
    $ufg->setUserId($p['user_id']);
    $ufg->setGroupId($p['group_id']);
    $ufg->setSec($p['sec']);
    $ufg->save();
    return True;
  }
  
  public function edit($p){
    if($ufg = $this->getUserObj($p)){
      if($p[sec]){
	$ufg->setSec($p['sec']);
	$ufg->save();
	return True;
      }
    }
    return False;
  }
  
  public function getUser($p){
    $group_id = $p['group_id'];
    $user_id = $p['user_id'];
    if(! $user_id){
      $user_id = fbLib::getUserId();
    }
    if($user_id and $group_id){
      $q = Doctrine_Query::create()
	->select('group_id,user_id,sec,date_create')
	->from('UserForGroup ufg')
	->where('ufg.user_id = ?', $user_id)
	->andWhere('ufg.group_id = ?', $group_id);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      if($rows and (count($rows) > 0) ){
	return $rows[0];
      }
    }
    return False;
  }
  
  public function getUserObj($p){
    $group_id = $p['group_id'];
    $user_id = $p['user_id'];
    if(! $user_id){
      $user_id = fbLib::getUserId();
    }
    if($user_id and $group_id){
      $q = Doctrine_Query::create()
	->select('group_id,user_id,sec,date_create')
	->from('UserForGroup ufg')
	->where('ufg.user_id = ?', $user_id)
	->andWhere('ufg.group_id = ?', $group_id);
      $rows = $q->execute(array());
      if($rows and (count($rows) > 0) ){
	return $rows[0];
      }
    }
    return False;
  }
  
  public function isMember($group_id,$user_id){
    if(! $user_id){
      $user_id = fbLib::getUserId();
    }
    if($user_id and $group_id){
      $q = Doctrine_Query::create()
	->select('ufg.group_id,ufg.user_id,ufg.sec')
	->from('UserForGroup ufg')
	->where('ufg.user_id = ?', $user_id)
	->andWhere('ufg.group_id = ?', $group_id);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      if($rows and (count($rows) > 0) ){
	return True;
      }
    }
    return False;
  }

  public function isAdmin($group_id,$user_id){
    if(! $user_id){
      $user_id = fbLib::getUserId();
    }
    if($user_id and $group_id){
      $q = Doctrine_Query::create()
	->select('ufg.group_id,ufg.user_id,ufg.sec,ufg.date_create')
	->from('UserForGroup ufg')
	->where('ufg.user_id = ?', $user_id)
	->andWhere('ufg.group_id = ?', $group_id);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      if($rows and (count($rows) > 0) ){
	if($rows[0]['ufg_sec'] > 49){
	  return True;
	}
      }
    }
    return False;
  }

  public function getMemberCount($group_id){
    $recs = array();
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS member_count')
      ->from('User u')
      ->innerJoin('u.UserForGroup ufg')
      ->where('ufg.group_id = ?',$group_id);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['u_member_count']);
  }
  
  public function getMembers($group_id,$offset = 0){
    $recs = array();
    $count = $this->getMemberCount($group_id);
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_limit' => UserForGroupTable::MEMBER_RECORD_LIMIT);
    $q = Doctrine_Query::create()
      ->select('ufg.sec,u.id,u.username,u.email,DATE_FORMAT(u.date_create,\'%c/%e/%y\') AS date_create,u.utype,u.firstname,u.lastname,u.title,u.website,u.location,u.about,u.lat,u.lon,u.msg_disc,u.msg_reply,u.msg_update,u.msg_stop,u.photo_id,ut.description')
      ->from('User u')
      ->innerJoin('u.UserForGroup ufg')
      ->leftJoin('u.UserType ut')
      ->orderBy('u.date_create DESC')
      ->where('ufg.group_id = ?',$group_id)
      ->limit(UserForGroupTable::MEMBER_RECORD_LIMIT);
    if($offset > 0){
      $q->offset($offset * UserForGroupTable::MEMBER_RECORD_LIMIT);
    }
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    if(count($rows) > 0 ){
      foreach($rows as $i => $row){
	$user_id = $row['u_id'];
	$recs[$i]['id'] = $user_id;
	$recs[$i]['username'] = $row['u_username'];
	$recs[$i]['date_create'] = $row['u_date_create'];
	$recs[$i]['firstname'] = $row['u_firstname'];
	$recs[$i]['lastname'] = $row['u_lastname'];
	$recs[$i]['title'] = $row['u_title'];
	$recs[$i]['website'] = $row['u_website'];
	$recs[$i]['location'] = $row['u_location'];
	$recs[$i]['utype'] = intval($row['u_utype']);
	$recs[$i]['utype_text'] = $row['ut_description'];
	$recs[$i]['about'] = $row['u_about'];
	$recs[$i]['lat'] = floatval($row['u_lat']);
	$recs[$i]['lon'] = floatval($row['u_lon']);
	$recs[$i]['photo_id'] = intval($row['u_photo_id']);
	$recs[$i]['member_status'] = Doctrine_Core::getTable('UserGroup')->memberStatus($group_id,$user_id);
      }
      $ret['count'] = count($recs);
      $ret['records'] = $recs;
    }
    return $ret;
  }

  ## get any user by username and add the current group status
  public function getMember($group_id,$username){
    $q = Doctrine_Query::create()
      ->select('ufg.sec,u.id,u.username,u.email,DATE_FORMAT(u.date_create,\'%c/%e/%y\') AS date_create,u.utype,u.firstname,u.lastname,u.title,u.website,u.location,u.about,u.lat,u.lon,u.msg_disc,u.msg_reply,u.msg_update,u.msg_stop,u.photo_id,ut.description')
      ->from('User u')
      ->where('u.username = ?',$username);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    if(count($rows) > 0 ){
      $row = $rows[0];
      $user_id = $row['u_id'];
      $rec['id'] = $user_id;
      $rec['username'] = $row['u_username'];
      $rec['date_create'] = $row['u_date_create'];
      $rec['firstname'] = $row['u_firstname'];
      $rec['lastname'] = $row['u_lastname'];
      $rec['title'] = $row['u_title'];
      $rec['website'] = $row['u_website'];
      $rec['location'] = $row['u_location'];
      $rec['utype'] = intval($row['u_utype']);
      $rec['utype_text'] = $row['ut_description'];
      $rec['about'] = $row['u_about'];
      $rec['lat'] = floatval($row['u_lat']);
      $rec['lon'] = floatval($row['u_lon']);
      $rec['photo_id'] = intval($row['u_photo_id']);
      $rec['member_status'] = Doctrine_Core::getTable('UserGroup')->memberStatus($group_id,$user_id);
      return $rec;
    }
    return False;
  }
  
  public function memberStatus($group_id,$user_id){
    if(! $user_id){
      $user_id = fbLib::getUserId();
    }
    if($user_id and $group_id){
      $q = Doctrine_Query::create()
	->select('ufg.group_id,ufg.user_id,ufg.sec,ufg.date_create')
	->from('UserForGroup ufg')
	->where('ufg.user_id = ?', $user_id)
	->andWhere('ufg.group_id = ?', $group_id);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      if($rows and (count($rows) > 0) ){
	$row = $rows[0];
	$rec = array();
	$rec['group_id'] = $row['ufg_group_id'];
	$rec['user_id'] = $row['ufg_user_id'];
	$rec['date_create'] = $row['ufg_date_create'];
	$rec['sec'] = $row['ufg_sec'];
	return $rec;
      }
    }
    return False;
  }


  public function memberGroupIds($user_id){
    if($user_id){
      $q = Doctrine_Query::create()
	->select('ufg.group_id')
	->from('UserForGroup ufg')
	->where('ufg.user_id = ?', $user_id);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      if($rows and (count($rows) > 0) ){
	$group_ids = array();
	foreach($rows as $i => $row){
	  $group_ids[$i] = $row['ufg_group_id'];
	}
	return $group_ids;
      }
    }
    return False;
  }

  public function delete($group_id,$user_id){
    if($user_id and $group_id){
      return Doctrine_Query::create()
	->delete()
	->from('UserForGroup ufg')
	->where('ufg.user_id = ?', $user_id)
	->andWhere('ufg.group_id = ?', $group_id)
	->execute();
      return True;
    }
    return False;
  }
  
}