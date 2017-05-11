<?php

/**
 * SpotTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class SpotTable extends Doctrine_Table{

  const SPOT_RECORD_LIMIT = 50;

  public static function getInstance(){
    return Doctrine_Core::getTable('Spot');
  }

  public function addSpot($post){
    $geo = $post['geo'];
    $spot = new Spot();
    $sec = intval($post['sec']);
    if($sec < 1){
      $sec = 1;
    }elseif( ($sec == fbLib::SEC_GROUP) and $post['group_id'] ){
      $spot->setGroupId($post['group_id']);
    }
    $spot->setSec($sec);

    $sec = intval($post['sec_photo']);
    if($sec < 1){
      $sec = 1;
    }
    $spot->setSecPhoto($sec);
    
    $spot->setLat($geo['lat']);
    $spot->setLon($geo['lon']);
    $spot->setUserId($post['user_id']);
    
    $post['content'] = strip_tags($post['content']);
    $post['content'] = trim($post['content']);
    $spot->setContent($post['content']);
    
    $post['caption'] = strip_tags($post['caption']);
    $post['caption'] = trim($post['caption']);
    $spot->setCaption($post['caption']);

    $post['url'] = strip_tags($post['url']);
    $post['url'] = trim($post['url']);
    if($post['url']){
      $spot->setUrl($post['url']);
    }
    $post['url_caption'] = strip_tags($post['url_caption']);
    $post['url_caption'] = trim($post['url_caption']);
    if($post['url_caption']){
      $spot->setUrlCaption($post['url_caption']);
    }

    $post['loc'] = strip_tags($post['loc']);
    $post['loc'] = trim($post['loc']);
    $spot->setLoc($post['loc']);
    
    $post['keyword'] = strip_tags($post['keyword']);
    $post['keyword'] = trim($post['keyword']);
    if($post['keyword'] = trim($post['keyword'])){
      $spot->setKeyword($post['keyword']);
    }
    $spot->setDateCreate(new Doctrine_Expression('NOW()'));
    $spot->save();
    $spot_id = $spot->getId();
    $new_spot = $this->getSpot($spot_id);
    return $new_spot;
  }
  
  public function updateSpot($post){
    $geo = $post['geo'];
    $spot_id = $post['spot_id'];
    if($spot = $this->find($spot_id)){	
      if($post['sec']){
	$sec = intval($post['sec']);
	if($sec < 1){
	  $sec = 1;
	}elseif( ($sec == fbLib::SEC_GROUP) and $post['group_id'] ){
	  $spot->setGroupId($post['group_id']);	
	}
	$spot->setSec($sec);
      }
      if($post['sec_photo']){
	$sec = intval($post['sec_photo']);
	if($sec < 1){
	  $sec = 1;
	}
	$spot->setSecPhoto($sec);
      }
      if($post['content'] != $spot->getContent()){
	$post['content'] = strip_tags($post['content']);
	$post['content'] = trim($post['content']);
	$spot->setContent($post['content']);
      }
      if($post['caption'] != $spot->getCaption()){
	$post['caption'] = strip_tags($post['caption']);
	$post['caption'] = trim($post['caption']);
	$spot->setCaption($post['caption']);
      }
      if($post['loc'] != $spot->getLoc()){
	$post['loc'] = strip_tags($post['loc']);
	$post['loc'] = trim($post['loc']);
	$spot->setLoc($post['loc']);
      }
      $post['keyword'] = strip_tags($post['keyword']);
      $post['keyword'] = trim($post['keyword']);
      if($post['keyword'] = trim($post['keyword'])){
	$spot->setKeyword($post['keyword']);
      }
      $post['url'] = strip_tags($post['url']);
      $post['url'] = trim($post['url']);
      $spot->setUrl($post['url']);
      $post['url_caption'] = strip_tags($post['url_caption']);
      $post['url_caption'] = trim($post['url_caption']);
      $spot->setUrlCaption($post['url_caption']);

      $spot->save();
      $new_spot = $this->getSpot($spot_id);
      return $new_spot;
    }else{
      return False;
    }
  }

  public function editGeo($post){
    $geo = $post['geo'];
    $spot_id = $post['spot_id'];
    if($spot = $this->find($spot_id)){	
      if($geo and $geo['lat'] and $geo['lon']){
	$spot->setLat($geo['lat']);
	$spot->setLon($geo['lon']);
	$spot->save();
	$new_spot = $this->getSpot($spot_id);
	return $new_spot;
      }
    }
    return False;
  }
  
  public function getRecAllow($spot_id){
    $rec = $this->getSpot($spot_id);
    if($rec = $this->getSpot($spot_id)){
      return fbLib::getRecAllow($rec);
    }
    return False;
  }
  
  public function getRec($spot_id){
    return $this->getSpot($spot_id);
  }
    
  public function getSpot($spot_id){
    $q = Doctrine_Query::create()
      ->select('s.id,s.group_id,s.sec,s.sec_photo,s.lat,s.lon,DATE_FORMAT(s.date_create,\'%c/%e/%Y\') AS date_create,YEAR(date_create) AS syear,MONTH(date_create) AS smonth,DAY(date_create) AS sday,s.caption,s.content,s.loc,s.url,s.url_caption,u.username,u.photo_id')
      ->from('Spot s')
      ->innerJoin('s.User u')
      ->where('s.id = ?',$spot_id);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $rec = array();
    $reply = new fbSpotReply();
    $file = new fbSpotFile();
    $fish = new fbSpotFish();
    if( count($rows) > 0){
      $rec = $this->sqlRow($rows[0]);
      $rec['reply_count'] = $reply->replyCount($rec['id']);
      $rec['photo_count'] = $file->photoCount($rec['id']);
      $rec['fish_count'] = $fish->fishCount($rec['id']);
    }
    return $rec;
  }
  
  public function sqlSelect(){
    $sql = 's.id,s.group_id,s.sec,s.sec_photo,s.lat,s.lon,UNIX_TIMESTAMP(s.date_create) AS uts,DATE_FORMAT(s.date_create,\'%c/%e/%y\') AS date_create,DATE_FORMAT(s.date_create,\'%c/%e/%Y @ %r\') AS date_time,YEAR(date_create) AS syear,MONTH(date_create) AS smonth,DAY(date_create) AS sday,s.caption,s.content,s.loc,s.url,s.url_caption,u.username,u.photo_id,g.name';
    return $sql;
  }
  
  private function sqlRow($row){
    $rec = array();
    $rec['id'] = intval($row['s_id']);
    $rec['group_id'] = intval($row['s_group_id']);
    $rec['username'] = $row['u_username'];
    $rec['sec'] = intval($row['s_sec']);
    $rec['sec_photo'] = intval($row['s_sec_photo']);
    $rec['lat'] = floatval($row['s_lat']);
    $rec['lon'] = floatval($row['s_lon']);
    $rec['date_create'] = $row['s_date_create'];
    $rec['date'] = $row['s_date_create'];
    $rec['date_time'] = $row['s_date_time'];
    $rec['uts'] = intval($row['s_uts']);
    $rec['year'] = $row['s_syear'];
    $rec['month'] = $row['s_smonth'];
    $rec['day'] = $row['s_sday'];
    $rec['loc'] = $row['s_loc'];
    $rec['location'] = $row['s_loc'];
    $rec['caption'] = trim($row['s_caption']);
    $rec['content'] = trim($row['s_content']);
    $rec['url'] = trim($row['s_url']);
    $rec['url_caption'] = trim($row['s_url_caption']);
    $rec['username'] = $row['u_username'];
    $rec['photo_id'] = $row['u_photo_id'];
    $rec['fb_source'] = 'spot';
    return $rec;
  }
  
  public function getSpotsBBCount(){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS spot_count')
      ->from('Spot s')
      ->innerJoin('s.User u');
    fbLib::addBoundsSQL($q,'s');
    fbLib::addDateRangeSQL($q,'MONTH(s.date_create)');
    fbLib::addFishSQL($q, array('source'=>'s', 'fish_table'=>'FishForSpot', 'fish_table_alias'=>'ffa'));
    if( ! fbLib::addSecSQL($q,'s')){
      return 0;
    }
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['s_spot_count']);
  }
  
  public function getSpotsBB($offset = 0){
    $count = $this->getSpotsBBCount();
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => intval($offset),
		 'record_limit' => SpotTable::SPOT_RECORD_LIMIT);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Spot s')
	->innerJoin('s.User u')
	->leftJoin('s.UserGroup g')
	->orderBy('s.date_create DESC')
	->limit(SpotTable::SPOT_RECORD_LIMIT);
      fbLib::addBoundsSQL($q,'s');
      fbLib::addDateRangeSQL($q,'MONTH(s.date_create)');
      fbLib::addSecSQL($q,'s');
      fbLib::addFishSQL($q, array('source'=>'s', 'fish_table'=>'FishForSpot', 'fish_table_alias'=>'ffa'));
      if($offset > 0){
	$q->offset($offset * SpotTable::SPOT_RECORD_LIMIT);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $reply = new fbSpotReply();
      $file = new fbSpotFile();
      $fish = new fbSpotFish();
      #$ret['sql'] = $q->getSQLQuery();
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

  public function getSpotsBBGWCount(){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS spot_count')
      ->from('Spot s')
      ->innerJoin('s.User u')
      ->where('s.sec = 1');
    fbLib::addBoundsSQL($q,'s');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['s_spot_count']);
  }
  
  public function getSpotsBBGW($p){
    if( ! $p['limit']){
      $p['limit'] = SpotTable::SPOT_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $count = $this->getSpotsBBGWCount();
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => $p['offset'], 'record_limit' => $p['limit']);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Spot s')
	->innerJoin('s.User u')
	->leftJoin('s.UserGroup g')
	->where('s.sec = 1')
	->orderBy('s.date_create DESC');
      fbLib::addBoundsSQL($q,'s');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $reply = new fbSpotReply();
      $file = new fbSpotFile();
      $fish = new fbSpotFish();
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


  public function spotSearchCount($param){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS spot_count')
      ->from('Spot s')
      ->innerJoin('s.User u');
    fbLib::addBoundsSQL($q,'s');
    fbLib::addDateRangeSQL($q,'MONTH(s.date_create)');
    fbLib::addSearchSQL($q,$param);
    if( ! fbLib::addSecSQL($q,'s')){
      return 0;
    }
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['s_spot_count']);
  }

  public function spotSearch($param){
    $count = $this->spotSearchCount($param);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => intval($offset),
		 'record_limit' => SpotTable::SPOT_RECORD_LIMIT);
    if($count){
      $q = Doctrine_Query::create()
	->select('s.id,s.group_id,s.sec,s.sec_photo,s.lat,s.lon,DATE_FORMAT(s.date_create,\'%c/%e/%y\') AS date_create,YEAR(date_create) AS syear,MONTH(date_create) AS smonth,DAY(date_create) AS sday,s.caption,s.content,s.loc,s.url,s.url_caption,u.username,u.photo_id')
	->from('Spot s')
	->innerJoin('s.User u')
	->orderBy('s.date_create DESC')
	->limit(SpotTable::SPOT_RECORD_LIMIT);
      if($offset > 0){
	$q->offset($offset * SpotTable::SPOT_RECORD_LIMIT);
      }
      fbLib::addBoundsSQL($q,'s');
      fbLib::addDateRangeSQL($q,'MONTH(s.date_create)');
      fbLib::addSecSQL($q,'s');
      fbLib::addSearchSQL($q,$param);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $reply = new fbSpotReply();
      $file = new fbSpotFile();
      $fish = new fbSpotFish();
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

  public function spotsByUserIdCount($user_id){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS spot_count')
      ->from('Spot s')
      ->innerJoin('s.User u')
      ->where('s.user_id = ?',$user_id)
      ->andWhere('s.sec = 1');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['s_spot_count']);
  }
  
  public function spotsByUserId($p){
    $user_id = $p['user_id'];
    if( ! $p['limit']){
      $p['limit'] = SpotTable::SPOT_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $count = $this->spotsByUserIdCount($user_id);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => $p['offset'], 'record_limit' => $p['limit']);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Spot s')
	->innerJoin('s.User u')
	->leftJoin('s.UserGroup g')
	->where('s.user_id = ?',$user_id)
	->andWhere('s.sec = 1')
	->orderBy('s.date_create DESC');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $reply = new fbSpotReply();
      $file = new fbSpotFile();
      $fish = new fbSpotFish();
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
  
  public function spotsByGroupIdCount($group_id){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS spot_count')
      ->from('Spot s')
      ->innerJoin('s.User u')
      ->innerJoin('s.UserGroup g')
      ->where('s.group_id = ?',$group_id);
    if( ! fbLib::addSecSQL($q,'s')){
      return 0;
    } 
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['s_spot_count']);
  }
  
  public function spotsByGroupId($p){
    $group_id = $p['group_id'];
    if( ! $p['limit']){
      $p['limit'] = SpotTable::SPOT_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $count = $this->spotsByGroupIdCount($group_id);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => $p['offset'], 'record_limit' => $p['limit']);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Spot s')
	->innerJoin('s.User u')
	->innerJoin('s.UserGroup g')
	->where('s.group_id = ?',$group_id)
	->orderBy('s.date_create DESC');
      fbLib::addSecSQL($q,'s');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $reply = new fbSpotReply();
      $file = new fbSpotFile();
      $fish = new fbSpotFish();
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
    
  public function spotsByFishIdCount($fish_id){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS spot_count')
      ->from('Spot s')
      ->innerJoin('s.User u')
      ->innerJoin('s.FishForSpot ffs')
      ->where('ffs.fish_id = ?',$fish_id);
    if( ! fbLib::addSecSQL($q,'s')){
      return 0;
    }
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['s_spot_count']);
  }
  
  public function spotsByFishId($p){
    $fish_id = $p['fish_id'];
    if( ! $p['limit']){
      $p['limit'] = SpotTable::SPOT_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $count = $this->spotsByFishIdCount($fish_id);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => $p['offset'], 'record_limit' => $p['limit']);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Spot s')
	->innerJoin('s.User u')
	->innerJoin('s.FishForSpot ffs')
	->leftJoin('s.UserGroup g')
	->where('ffs.fish_id = ?',$fish_id)
	->orderBy('s.date_create DESC');
      fbLib::addSecSQL($q,'s');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $reply = new fbSpotReply();
      $file = new fbSpotFile();
      $fish = new fbSpotFish();
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

  public function getSpotsCount(){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS spot_count')
      ->from('Spot s')
      ->innerJoin('s.User u');
    if( ! fbLib::addSecSQL($q,'s')){
      return 0;
    } 
   $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['s_spot_count']);
  }
  
  public function getSpots($p){
    if( ! $p['limit']){
      $p['limit'] = SpotTable::SPOT_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $count = $this->getSpotsCount($fish_id);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => $p['offset'], 'record_limit' => $p['limit']);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Spot s')
	->innerJoin('s.User u')
	->leftJoin('s.UserGroup g')
	->orderBy('s.date_create DESC');
      fbLib::addSecSQL($q,'s');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $reply = new fbSpotReply();
      $file = new fbSpotFile();
      $fish = new fbSpotFish();
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
    
  public function deleteSpot($spot_id){
    if($spot_id){
      $q = Doctrine_Query::create()
	->delete()
	->from('Spot s')
	->where('s.id = ?', $spot_id);
      $q->execute();
      $this->deleteSpotPhotos($spot_id);
    }
  }
  
  private function deleteSpotPhotos($spot_id){
    if($spot_id){
      $q = Doctrine_Query::create()
	->delete()
	->from('FileForSpot ffs')
	->where('ffs.pid = ?', $spot_id);
      $q->execute();
    }
  }
  
  public function deletePhoto($spot_id,$file_id){
    $q = Doctrine_Query::create()
      ->delete()
      ->from('FileForSpot f')
      ->where('f.spot_id = ?',$spot_id)
      ->andWhere('f.file_id = ?',$file_id)
      ->execute();
  }

}