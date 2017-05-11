<?php

class DiscTable extends Doctrine_Table{

  const DISC_RECORD_LIMIT = 25;

  public static function getInstance(){
    return Doctrine_Core::getTable('Disc');
  }

  public function addDisc($post){
    $geo = $post['geo'];
    $disc = new Disc();
    $sec = intval($post['sec']);
    if($sec < 1){
      $sec = 1;
    }elseif( ($sec == fbLib::SEC_GROUP) and $post['group_id'] ){
      $disc->setGroupId($post['group_id']);	
    }
    $disc->setSec($sec);
    $disc->setLat($geo['lat']);
    $disc->setLon($geo['lon']);
    $disc->setLoc($geo['loc']);
    $disc->setUserId($post['user_id']);
    $content = strip_tags($post['text']);
    $content = trim($content);
    $disc->setContent($content);
    $cap = strip_tags($post['caption']);
    $cap = trim($cap);
    $disc->setCaption($cap);
    $disc->setCatId(intval($post['cat_id']));
    $disc->setCat(strip_tags($post['cat']));
    $disc->setWtype(intval($post['wtype']));
    $disc->setDateCreate(new Doctrine_Expression('NOW()'));
    if( $post['pid'] && is_numeric($post['pid']) ){
      $disc->setPid(intval($post['pid']));
    }
    $disc->save();
    $id = $disc->getId();
    $new_disc = $this->getDisc($id);
    return $new_disc;
  }

  public function editDisc($post){
    $geo = $post['geo'];
    if($disc = $this->find($post['id'])){
      if($post['sec']){
	$sec = intval($post['sec']);
	if($sec < 1){
	  $sec = 1;
	}elseif( ($sec == fbLib::SEC_GROUP) and $post['group_id'] ){
	  $disc->setGroupId($post['group_id']);	
	}
	$disc->setSec($sec);
      }
      $content = strip_tags($post['text']);
      $content = trim($content);
      if(strlen($content) > 0){
	$disc->setContent($content);
      }
      if( $post['fish_name']){
	$val = strip_tags($post['fish_name']);
	$val = trim($val);
	$disc->setFishName($val);
      }
      $cap = strip_tags($post['caption']);
      $cap = trim($cap);
      if($cap){
	$disc->setCaption($cap);
      }
      if($post['cat_id']){
	$disc->setCatId(intval($post['cat_id']));
      }
      if($post['wtype']){
	$disc->setWtype(intval($post['wtype']));
      }
      $disc->setCat(strip_tags($post['cat']));
      $disc->save();
      if($new_disc = $this->getDisc($post['id'])){
	return $new_disc;
      }
    }
    return False;
  }
  
  public function editGeo($post){
    $geo = $post['geo'];
    if($disc = $this->find($post['id'])){
      if($geo and $geo['lat'] and $geo['lon']){
	$disc->setLat($geo['lat']);
	$disc->setLon($geo['lon']);
      }
      $disc->save();
      if($new_disc = $this->getDisc($post['id'])){
	return $new_disc;
      }
    }
    return False;
  }
  
  public function getRecAllow($disc_id){
    if($rec = $this->getDisc($disc_id)){
      return fbLib::getRecAllow($rec);
    }
    return False;
  }

  public function getRec($disc_id){
    return $this->getDisc($disc_id);
  }
  
  public function getDisc($disc_id){
    $q = Doctrine_Query::create()
      ->select('d.id,d.group_id,d.sec,d.pid,d.lat,d.lon,YEAR(date_create) AS dyear,MONTH(date_create) AS dmonth,DAY(date_create) AS dday,DATE_FORMAT(d.date_create,\'%c/%e/%y@%h:%i%p\') AS date_create,DATE_FORMAT(d.date_create,\'%c/%e/%y\') AS ddate,d.content AS content,d.loc,d.caption,d.cat_id,d.wtype,d.cat,u.username,u.firstname,u.lastname,u.photo_id')
      ->from('Disc d')
      ->innerJoin('d.User u')
      ->where('d.id = ?',$disc_id);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $rec = array();
    $reply = new fbDiscReply();
    $file = new fbDiscFile();
    $fish = new fbDiscFish();
    if( count($rows) > 0){
      $rec = $this->sqlRow($rows[0]);
      $rec['reply_count'] = $reply->replyCount($rec['id']);
      $rec['photo_count'] = $file->photoCount($rec['id']);
      $rec['fish_count'] = $fish->fishCount($rec['id']);
    }
    return $rec;
  }
  
  private function sqlSelect(){
    $sql = 'd.id,d.group_id,d.sec,d.lat,d.lon,UNIX_TIMESTAMP(d.date_create) AS uts,DATE_FORMAT(d.date_create,\'%c/%e/%y@%H:%i %p\') AS date_create,DATE_FORMAT(d.date_create,\'%c/%e/%y\') AS ddate,d.content AS content,d.loc,d.caption,d.cat_id,d.cat,d.wtype,u.username,u.firstname,u.lastname,u.photo_id';
    return $sql;
  }

  private function sqlRow($row){
    $rec = array();
    $rec['id'] = intval($row['d_id']);
    $rec['group_id'] = intval($row['d_group_id']);
    $rec['pid'] = intval($row['d_pid']);
    $rec['sec'] = intval($row['d_sec']);
    $rec['lat'] = floatval($row['d_lat']);
    $rec['lon'] = floatval($row['d_lon']);
    $rec['date_create'] = $row['d_date_create'];
    $rec['date'] = $row['d_ddate'];
    $rec['date_time'] = $row['d_date_create'];
    $rec['uts'] = $row['d_uts'];
    $rec['year'] = $row['d_dyear'];
    $rec['month'] = $row['d_dmonth'];
    $rec['day'] = $row['d_dday'];
    $rec['loc'] = $row['d_loc'];
    $rec['caption'] = $row['d_caption'];
    $rec['cat_id'] = $row['d_cat_id'];
    $rec['cat'] = $row['d_cat'];
    $rec['wtype'] = $row['d_wtype'];
    $rec['content'] = trim($row['d_content']);
    $rec['username'] = $row['u_username'];
    $rec['first'] = $row['u_firstname'];
    $rec['last'] = $row['u_lastname'];
    $rec['photo_id'] = intval($row['u_photo_id']);
    $rec['fb_source'] = 'disc';
    return $rec;
  }
  
  public function getDiscsBBCount(){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS disc_count')
      ->from('Disc d')
      ->innerJoin('d.User u');
    fbLib::addBoundsSQL($q,'d');
    fbLib::addDateRangeSQL($q,'MONTH(d.date_create)');
    fbLib::addFishSQL($q, array('source'=>'d', 'fish_table'=>'FishForDisc', 'fish_table_alias'=>'ffa'));
    if( ! fbLib::addSecSQL($q,'d')){
      return 0;
    }
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['d_disc_count']);
  }
  
  public function getDiscsBB($offset = 0){
    $offset = intval($offset);
    $recs = array();
    $count = $this->getDiscsBBCount();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs, 
		 'record_offset' => $offset,'record_limit' => DiscTable::DISC_RECORD_LIMIT);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Disc d')
	->innerJoin('d.User u')
	->orderBy('d.date_create DESC')
	->limit(DiscTable::DISC_RECORD_LIMIT);
      fbLib::addBoundsSQL($q,'d');
      fbLib::addDateRangeSQL($q,'MONTH(d.date_create)');
      fbLib::addFishSQL($q, array('source'=>'d', 'fish_table'=>'FishForDisc', 'fish_table_alias'=>'ffa'));
      if($offset > 0){
	$q->offset($offset * DiscTable::DISC_RECORD_LIMIT);
      }
      fbLib::addSecSQL($q,'d');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $reply = new fbDiscReply();
      $file = new fbDiscFile();
      $fish = new fbDiscFish();
      foreach($rows as $i => $row){
	$rec = $this->sqlRow($row);
	$rec['reply_count'] = $reply->replyCount($rec['id']);
	$rec['photo_count'] = $file->photoCount($rec['id']);
	$rec['fish_count'] = $fish->fishCount($rec['id']);
	$recs[] = $rec;
      }
      $ret['count'] = count($recs);
      $ret['records'] = $recs;
    }
    return $ret;
  }

  public function getDiscsBBGWCount(){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS disc_count')
      ->from('Disc d')
      ->innerJoin('d.User u')
      ->where('d.sec = 1');
    fbLib::addBoundsSQL($q,'d');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['d_disc_count']);
  }
  
  public function getDiscsBBGW($p){
    if( ! $p['limit']){
      $p['limit'] = DiscTable::DISC_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $recs = array();
    $count = $this->getDiscsBBGWCount();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs, 
		 'record_offset' => $p['offset'], 'record_limit' => $p['limit']);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Disc d')
	->innerJoin('d.User u')
	->where('d.sec = 1')
	->orderBy('d.date_create DESC');
      fbLib::addBoundsSQL($q,'d');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $reply = new fbDiscReply();
      $file = new fbDiscFile();
      $fish = new fbDiscFish();
      foreach($rows as $i => $row){
	$rec = $this->sqlRow($row);
	$rec['reply_count'] = $reply->replyCount($rec['id']);
	$rec['photo_count'] = $file->photoCount($rec['id']);
	$rec['fish_count'] = $fish->fishCount($rec['id']);
	$recs[] = $rec;
      }
      $ret['count'] = count($recs);
      $ret['records'] = $recs;
    }
    return $ret;
  }

  public function discsByUserIdCount($user_id){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS disc_count')
      ->from('Disc d')
      ->innerJoin('d.User u')
      ->where('d.user_id = ?',$user_id)
      ->andWhere('d.sec = 1');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['d_disc_count']);
  }
  
  public function discsByUserId($p){
    $user_id = $p['user_id'];
    if( ! $p['limit']){
      $p['limit'] = DiscTable::DISC_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $recs = array();
    $count = $this->discsByUserIdCount($user_id);
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs, 
		 'record_offset' => $p['offset'], 'record_limit' => $p['limit']);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Disc d')
	->innerJoin('d.User u')
	->where('d.user_id = ?',$user_id)
	->andWhere('d.sec = 1')
	->orderBy('d.date_create DESC');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $reply = new fbDiscReply();
      $file = new fbDiscFile();
      $fish = new fbDiscFish();
      foreach($rows as $i => $row){
	$rec = $this->sqlRow($row);
	$rec['reply_count'] = $reply->replyCount($rec['id']);
	$rec['photo_count'] = $file->photoCount($rec['id']);
	$rec['fish_count'] = $fish->fishCount($rec['id']);
	$recs[] = $rec;
      }
      $ret['count'] = count($recs);
      $ret['records'] = $recs;
    }
    return $ret;
  }

  public function discsByGroupIdCount($group_id){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS disc_count')
      ->from('Disc d')
      ->innerJoin('d.User u')
      ->innerJoin('d.UserGroup g')
      ->where('d.group_id = ?',$group_id)
      ->andWhere('d.sec = 1');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['d_disc_count']);
  }
  
  public function discsByGroupId($p){
    $group_id = $p['group_id'];
    if( ! $p['limit']){
      $p['limit'] = DiscTable::DISC_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $recs = array();
    $count = $this->discsByGroupIdCount($group_id);
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs, 
		 'record_offset' => $p['offset'], 'record_limit' => $p['limit']);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Disc d')
	->innerJoin('d.User u')
	->innerJoin('d.UserGroup g')
	->where('d.group_id = ?',$group_id)
	->andWhere('d.sec = 1')
	->orderBy('d.date_create DESC');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $reply = new fbDiscReply();
      $file = new fbDiscFile();
      $fish = new fbDiscFish();
      foreach($rows as $i => $row){
	$rec = $this->sqlRow($row);
	$rec['reply_count'] = $reply->replyCount($rec['id']);
	$rec['photo_count'] = $file->photoCount($rec['id']);
	$rec['fish_count'] = $fish->fishCount($rec['id']);
	$recs[] = $rec;
      }
      $ret['count'] = count($recs);
      $ret['records'] = $recs;
    }
    return $ret;
  }

  public function discsByFishIdCount($fish_id){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS disc_count')
      ->from('Disc d')
      ->innerJoin('d.User u')
      ->innerJoin('d.FishForDisc ffd')
      ->where('ffd.fish_id = ?',$fish_id)
      ->andWhere('d.sec = 1');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['d_disc_count']);
  }
  
  public function discsByFishId($p){
    $fish_id = $p['fish_id'];
    if( ! $p['limit']){
      $p['limit'] = DiscTable::DISC_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $recs = array();
    $count = $this->discsByFishIdCount($fish_id);
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs, 
		 'record_offset' => $p['offset'], 'record_limit' => $p['limit']);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Disc d')
	->innerJoin('d.User u')
	->innerJoin('d.FishForDisc ffd')
	->where('ffd.fish_id = ?',$fish_id)
	->andWhere('d.sec = 1')
	->orderBy('d.date_create DESC');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $reply = new fbDiscReply();
      $file = new fbDiscFile();
      $fish = new fbDiscFish();
      foreach($rows as $i => $row){
	$rec = $this->sqlRow($row);
	$rec['reply_count'] = $reply->replyCount($rec['id']);
	$rec['photo_count'] = $file->photoCount($rec['id']);
	$rec['fish_count'] = $fish->fishCount($rec['id']);
	$recs[] = $rec;
      }
      $ret['count'] = count($recs);
      $ret['records'] = $recs;
    }
    return $ret;
  }

  public function discSearchCount($param){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS disc_count')
      ->from('Disc d')
      ->innerJoin('d.User u');
    fbLib::addBoundsSQL($q,'d');
    fbLib::addDateRangeSQL($q,'MONTH(d.date_create)');
    fbLib::addSearchSQL($q,$param);
    fbLib::addFishSQL($q, array('source'=>'d', 'fish_table'=>'FishForDisc', 'fish_table_alias'=>'ffa'));
    if( ! fbLib::addSecSQL($q,'d')){
      return 0;
    }
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['d_disc_count']);
  }
  
  public function discSearch($param,$offset = 0){
    $offset = intval($offset);
    $recs = array();
    $count = $this->discSearchCount($param);
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs, 
		 'record_offset' => $offset,'record_limit' => DiscTable::DISC_RECORD_LIMIT);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Disc d')
	->innerJoin('d.User u')
	->orderBy('d.date_create DESC')
	->limit(DiscTable::DISC_RECORD_LIMIT);
      fbLib::addBoundsSQL($q,'d');
      fbLib::addDateRangeSQL($q,'MONTH(d.date_create)');
      fbLib::addSearchSQL($q,$param);
      fbLib::addFishSQL($q, array('source'=>'d', 'fish_table'=>'FishForDisc', 'fish_table_alias'=>'ffa'));
      if($offset > 0){
	$q->offset($offset * DiscTable::DISC_RECORD_LIMIT);
      }
      fbLib::addSecSQL($q,'d');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $reply = new fbDiscReply();
      $file = new fbDiscFile();
      $fish = new fbDiscFish();
      foreach($rows as $i => $row){
	$rec = $this->sqlRow($row);
	$rec['reply_count'] = $reply->replyCount($rec['id']);
	$rec['photo_count'] = $file->photoCount($rec['id']);
	$rec['fish_count'] = $fish->fishCount($rec['id']);
	$recs[] = $rec;
      }
      $ret['count'] = count($recs);
      $ret['records'] = $recs;
    }
    return $ret;
  }
  
  public function getDiscsCount(){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS disc_count')
      ->from('Disc d')
      ->innerJoin('d.User u');
    if( ! fbLib::addSecSQL($q,'d')){
      return 0;
    }
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['d_disc_count']);
  }
  
  public function getDiscs($p){
    if( ! $p['limit']){
      $p['limit'] = DiscTable::DISC_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $recs = array();
    $count = $this->getDiscsCount();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs, 
		 'record_offset' => $p['offset'],'record_limit' => $p['limit']);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Disc d')
	->innerJoin('d.User u')
	->orderBy('d.date_create DESC')
	->limit($p['limit']);
      if($p['offset'] > 0){
	$q->offset($p['offset'] * $p['limit']);
      }
      fbLib::addSecSQL($q,'d');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $reply = new fbDiscReply();
      $file = new fbDiscFile();
      $fish = new fbDiscFish();
      foreach($rows as $i => $row){
	$rec = $this->sqlRow($row);
	$rec['reply_count'] = $reply->replyCount($rec['id']);
	$rec['photo_count'] = $file->photoCount($rec['id']);
	$rec['fish_count'] = $fish->fishCount($rec['id']);
	$recs[] = $rec;
      }
      $ret['count'] = count($recs);
      $ret['records'] = $recs;
    }
    return $ret;
  }

  public function deleteDisc($disc_id){
    return Doctrine_Query::create()
      ->delete()
      ->from('Disc d')
      ->where('d.id = ?', $disc_id)
      ->execute();
  }    
  
}