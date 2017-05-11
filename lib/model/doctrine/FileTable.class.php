<?php

class FileTable extends Doctrine_Table{

  const UPLOAD_SIZE_LIMIT = 100000000;
  const FILE_RECORD_LIMIT = 10;
  
  public static function getInstance(){
    return Doctrine_Core::getTable('File');
  }
  
  public function createFile($param){
    $new = new File();
    $sec = intval($param['sec']);
    if($sec < 1){
      $sec = 1;
    }elseif( ($sec == fbLib::SEC_GROUP) and $param['group_id'] ){
      $new->setGroupId($param['group_id']);      
    }
    $new->setSec($sec);
    $val = strip_tags($param['caption']);
    $val = trim($val);
    if($val){
      $new->setCaption($val);
    }
    $val = strip_tags($param['keyword']);
    $val = trim($val);
    if($val){
      $new->setKeyword($val);
    }
    $val = strip_tags($param['detail']);
    $val = trim($val);
    if($val){
      $new->setDetail($val);
    }
    if($param['lat'] and $param['lon']){
      $new->setLat($param['lat']);
      $new->setLon($param['lon']);
    }
    $new->setUserId($param['user_id']);
    $new->setDateCreate(new Doctrine_Expression('NOW()'));
    if($param['date_create']){
      $tsDate = strtotime($param['date_create']);
      if($tsDate){
	$myDate = getdate($tsDate);
	$date = $myDate['year'] .'-'. $myDate['mon'] .'-'. $myDate['mday'];
	$new->setDateCreate($date);
      }
    }
    $new->setFsize($param['size']);
    $new->setStatus(1);
    $type = ($param['ftype'] ? $param['ftype'] : 1);
    $new->setFtype($type);
    $new->save();
    $file_id = $new->getId();
    $file_new = $this->getFile($file_id);
    return $file_new;
  }

  public function updateFile($updates){
    $file_id = $updates['id'];
    if($file = $this->find($file_id)){
      $sec = intval($updates['sec']);
      if($sec){
	if($sec < 1){
	  $sec = 1;
	}elseif( ($sec == fbLib::SEC_GROUP) and $updates['group_id'] ){
	  $file->setGroupId($updates['group_id']);      
	}
	$file->setSec($sec);
      }
      if($updates['caption']){
	$val = strip_tags($updates['caption']);
	$val = trim($val);
	if($val){
	  $file->setCaption($val);
	}
      }
      if($updates['keyword']){
	$val = strip_tags($updates['keyword']);
	$val = trim($val);
	if($val){
	  $file->setKeyword($val);
	}
      }
      if($updates['detail']){
	$val = strip_tags($updates['detail']);
	$val = trim($val);
	if($val){
	  $file->setDetail($val);
	}
      }
      if($updates['date_create']){
	$tsDate = strtotime($updates['date_create']);
	if($tsDate){
	  $myDate = getdate($tsDate);
	  $val = $myDate['year'] .'-'. $myDate['mon'] .'-'. $myDate['mday'];
	  $file->setDateCreate($val);
	}
      }      
      if($updates['status']){
	$file->setStatus($updates['status']);
      }
      $file->save();
      $file_new = $this->getFile($file_id);
      return $file_new;
    }
    return False;
  }
  
  public function editGeo($post){
    if($file = $this->find($post['id'])){
      $geo = $post['geo'];
      if($geo and $geo['lat'] and $geo['lon']){
	$file->setLat(floatval($geo['lat']));
	$file->setLon(floatval($geo['lon']));
	$file->save();
	$file_new = $this->getFile($post['id']);
	return $file_new;
      }
    }
    return False;
  }

  public function getRecAllow($file_id){
    if($rec = $this->getFile($file_id)){
      return fbLib::getRecAllow($rec);
    }
    return False;
  }
  
  public function getRec($file_id){
    return $this->getFile($file_id);
  }

  public function getFile($file_id){
    $q = Doctrine_Query::create()
      ->select($this->sqlSelect())
      ->from('File f')
      ->innerJoin('f.User u')  
      ->where('f.id = ?',$file_id)
      ->andWhere('f.status = 1');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $reply = new fbPhotoReply();
    $fish = new fbPhotoFish();
    if( count($rows) > 0){
      $rec = $this->sqlRow($rows[0]);
      $rec['reply_count'] = $reply->replyCount($rec['id']);
      $rec['fish_count'] = $fish->fishCount($rec['id']);
      return $rec;
    }else{
      return False;
    }
  }
  
  public function deleteFile($file_id){
    $q = Doctrine_Query::create()
      ->delete()
      ->from('File f')
      ->where('f.id = ?',$file_id)
      ->execute();
  }
 
  # get photos by lat/lon
  public function BADgetPhotosLL($lat,$lon,$month_range){
    $dist = 50;
    $geo = fbLib::boundsFromPoint($lat,$lon,$dist);
    return $this->getPhotosBB($geo,$month_range);
  }
  
  private function sqlSelect(){
    $sql = 'f.id,f.group_id,f.sec,f.lat,f.lon,f.caption,f.detail,f.keyword,f.user_id,UNIX_TIMESTAMP(f.date_create) AS uts,DATE_FORMAT(f.date_create,\'%c/%e/%y\') AS dc,DATE_FORMAT(f.date_create,\'%c/%e/%y @ %r\') AS date_time,f.ts,u.username,u.photo_id';
    return $sql;
  }

  private function sqlRow($row){
    $rec = array();
    $rec['id'] = intval($row['f_id']);
    $rec['group_id'] = intval($row['f_group_id']);
    $rec['sec'] = intval($row['f_sec']);
    $rec['username'] = $row['u_username'];
    $rec['photo_id'] = $row['u_photo_id'];
    $rec['dc'] = $row['f_dc'];
    $rec['date_create'] = $row['f_dc'];
    $rec['date'] = $row['f_dc'];
    $rec['uts'] = $row['f_uts'];
    $rec['year'] = $row['f_dyear'];
    $rec['month'] = $row['f_dmonth'];
    $rec['day'] = $row['f_dday'];
    $rec['ts'] = $row['f_ts'];
    $rec['size'] = $row['f_fsize'];
    $rec['status'] = $row['f_status'];
    $rec['caption'] = $row['f_caption'];
    $rec['keyword'] = $row['f_keyword'];
    $rec['detail'] = $row['f_detail'];
    $rec['lat'] = doubleval($row['f_lat']);
    $rec['lon'] = doubleval($row['f_lon']);
    $rec['fb_source'] = 'photo';
    return $rec;
  }

  public function getPhotosBBCount(){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS photo_count')
      ->from('File f')
      ->innerJoin('f.User u')
      ->where('f.ftype < 10');
    fbLib::addBoundsSQL($q,'f');
    fbLib::addDateRangeSQL($q,'MONTH(f.date_create)');
    fbLib::addFishSQL($q, array('source'=>'f', 'fish_table'=>'FishForFile', 'fish_table_alias'=>'ffa'));
    if( ! fbLib::addSecSQL($q,'f')){
      return 0;
    }
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['f_photo_count']);
  }

  public function getPhotosBB($offset=0){
    $count = $this->getPhotosBBCount();
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs, 
		 'record_offset' => intval($offset), 'record_limit' => FileTable::FILE_RECORD_LIMIT);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('File f')
	->innerJoin('f.User u')
	->where('f.ftype < 10')
	->orderBy('f.date_create DESC')
	->limit(FileTable::FILE_RECORD_LIMIT);
      fbLib::addBoundsSQL($q,'f');
      fbLib::addSecSQL($q,'f');
      fbLib::addDateRangeSQL($q,'MONTH(f.date_create)');
      fbLib::addFishSQL($q, array('source'=>'f', 'fish_table'=>'FishForFile', 'fish_table_alias'=>'ffa'));
      if($offset > 0){
	$q->offset($offset * FileTable::FILE_RECORD_LIMIT);
      }
      $reply = new fbPhotoReply();
      $fish = new fbPhotoFish();
      if($rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR)){
	foreach($rows as $i => $row){
	  $rec = $this->sqlRow($row);
	  $rec['reply_count'] = $reply->replyCount($rec['id']);
	  $rec['fish_count'] = $fish->fishCount($rec['id']);
	  $recs[] = $rec;
	}
	$ret['count'] = count($recs);
	$ret['records'] = $recs;
      }
    }
    return $ret;
  }

  public function getPhotosBBGWCount(){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS photo_count')
      ->from('File f')
      ->innerJoin('f.User u')
      ->where('f.ftype < 10')
      ->andWhere('f.sec = 1');
    fbLib::addBoundsSQL($q,'f');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['f_photo_count']);
  }
  
  public function getPhotosBBGW($p){
    if( ! $p['limit']){
      $p['limit'] = FileTable::FILE_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $count = $this->getPhotosBBGWCount();
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs, 
		 'record_offset' => intval($p['offset']), 'record_limit' => $p['limit'] );
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('File f')
	->innerJoin('f.User u')
	->where('f.ftype < 10')
	->andWhere('f.sec = 1')
	->orderBy('f.date_create DESC');
      fbLib::addBoundsSQL($q,'f');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      $reply = new fbPhotoReply();
      $fish = new fbPhotoFish();
      if($rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR)){
	foreach($rows as $i => $row){
	  $rec = $this->sqlRow($row);
	  $rec['reply_count'] = $reply->replyCount($rec['id']);
	  $rec['fish_count'] = $fish->fishCount($rec['id']);
	  $recs[] = $rec;
	}
	$ret['count'] = count($recs);
	$ret['records'] = $recs;
      }
    }
    return $ret;
  }

  public function photosByUserIdCount($user_id){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS photo_count')
      ->from('File f')
      ->innerJoin('f.User u')
      ->where('f.user_id = ?',$user_id)
      ->andWhere('f.ftype < 10')
      ->andWhere('f.sec = 1');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['f_photo_count']);
  }
  
  public function photosByUserId($p){
    $user_id = $p['user_id'];
    if( ! $p['limit']){
      $p['limit'] = FileTable::FILE_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $count = $this->photosByUserIdCount($user_id);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs, 
		 'record_offset' => intval($p['offset']), 'record_limit' => $p['limit'] );
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('File f')
	->innerJoin('f.User u')
	->where('f.user_id = ?',$user_id)
	->andWhere('f.ftype < 10')
	->andWhere('f.sec = 1')
	->orderBy('f.date_create DESC');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      $reply = new fbPhotoReply();
      $fish = new fbPhotoFish();
      if($rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR)){
	foreach($rows as $i => $row){
	  $rec = $this->sqlRow($row);
	  $rec['reply_count'] = $reply->replyCount($rec['id']);
	  $rec['fish_count'] = $fish->fishCount($rec['id']);
	  $recs[] = $rec;
	}
	$ret['count'] = count($recs);
	$ret['records'] = $recs;
      }
    }
    return $ret;
  }

  public function photosByGroupIdCount($group_id){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS photo_count')
      ->from('File f')
      ->innerJoin('f.User u')
      ->innerJoin('f.UserGroup g')
      ->where('f.group_id = ?',$group_id)
      ->andWhere('f.ftype < 10')
      ->andWhere('f.sec = 1');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['f_photo_count']);
  }
  
  public function photosByGroupId($p){
    $group_id = $p['group_id'];
    if( ! $p['limit']){
      $p['limit'] = FileTable::FILE_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $count = $this->photosByGroupIdCount($group_id);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs, 
		 'record_offset' => intval($p['offset']), 'record_limit' => $p['limit'] );
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('File f')
	->innerJoin('f.User u')
	->innerJoin('f.UserGroup g')
	->where('f.group_id = ?',$group_id)
	->andWhere('f.ftype < 10')
	->andWhere('f.sec = 1')
	->orderBy('f.date_create DESC');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      if($rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR)){
	foreach($rows as $i => $row){
	  $rec = $this->sqlRow($row);
	  $rec['reply_count'] = $reply->replyCount($rec['id']);
	  $rec['fish_count'] = $fish->fishCount($rec['id']);
	  $recs[] = $rec;
	}
	$ret['count'] = count($recs);
	$ret['records'] = $recs;
      }
    }
    return $ret;
  }

  public function photosByFishIdCount($fish_id){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS photo_count')
      ->from('File f')
      ->innerJoin('f.User u')
      ->innerJoin('f.FishForFile fff')
      ->where('fff.fish_id = ?',$fish_id)
      ->andWhere('f.ftype < 10')
      ->andWhere('f.sec = 1');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['f_photo_count']);
  }
  
  public function photosByFishId($p){
    $fish_id = $p['fish_id'];
    if( ! $p['limit']){
      $p['limit'] = FileTable::FILE_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $count = $this->photosByFishIdCount($fish_id);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs, 
		 'record_offset' => intval($p['offset']), 'record_limit' => $p['limit'] );
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('File f')
	->innerJoin('f.User u')
	->innerJoin('f.FishForFile fff')
	->where('fff.fish_id = ?',$fish_id)
	->andWhere('f.ftype < 10')
	->andWhere('f.sec = 1')
	->orderBy('f.date_create DESC');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      if($rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR)){
	foreach($rows as $i => $row){
	  $rec = $this->sqlRow($row);
	  $rec['reply_count'] = $reply->replyCount($rec['id']);
	  $rec['fish_count'] = $fish->fishCount($rec['id']);
	  $recs[] = $rec;
	}
	$ret['count'] = count($recs);
	$ret['records'] = $recs;
      }
    }
    return $ret;
  }

  public function photoSearchCount($param){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS photo_count')
      ->from('File f')
      ->innerJoin('f.User u')
      ->where('f.ftype < 10');
    fbLib::addBoundsSQL($q,'f');
    fbLib::addDateRangeSQL($q,'MONTH(f.date_create)');
    fbLib::addSearchSQL($q,$param);
    fbLib::addFishSQL($q, array('source'=>'f', 'fish_table'=>'FishForFile', 'fish_table_alias'=>'ffa'));
    if( ! fbLib::addSecSQL($q,'f')){
      return 0;
    }
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['f_photo_count']);
  }

  public function photoSearch($param,$offset=0){
    $count = $this->photoSearchCount($param);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs, 
		 'record_offset' => intval($offset), 'record_limit' => FileTable::FILE_RECORD_LIMIT);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('File f')
	->innerJoin('f.User u')
	->where('f.ftype < 10')
	->orderBy('f.date_create DESC')
	->limit(FileTable::FILE_RECORD_LIMIT);
      fbLib::addBoundsSQL($q,'f');
      fbLib::addSecSQL($q,'f');
      fbLib::addDateRangeSQL($q,'MONTH(f.date_create)');
      fbLib::addSearchSQL($q,$param);
      fbLib::addFishSQL($q, array('source'=>'f', 'fish_table'=>'FishForFile', 'fish_table_alias'=>'ffa'));
      if($offset > 0){
	$q->offset($offset * FileTable::FILE_RECORD_LIMIT);
      }
      $reply = new fbPhotoReply();
      $fish = new fbPhotoFish();
      if($rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR)){
	foreach($rows as $i => $row){
	  $rec = $this->sqlRow($row);
	  $rec['reply_count'] = $reply->replyCount($rec['id']);
	  $rec['fish_count'] = $fish->fishCount($rec['id']);
	  $recs[] = $rec;
	}
	$ret['count'] = count($recs);
	$ret['records'] = $recs;
      }
    }
    return $ret;
  }
 
  public function getPhotosCount(){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS photo_count')
      ->from('File f')
      ->innerJoin('f.User u')
      ->where('f.ftype < 10');
    if( ! fbLib::addSecSQL($q,'f')){
      return 0;
    }
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['f_photo_count']);
  }

  public function getPhotos($p){
    if( ! $p['limit']){
      $p['limit'] = FileTable::FILE_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $count = $this->getPhotosCount();
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs, 
		 'record_offset' => intval($p['offset']), 'record_limit' => FileTable::FILE_RECORD_LIMIT);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('File f')
	->innerJoin('f.User u')
	->where('f.ftype < 10')
	->orderBy('f.date_create DESC')
	->limit(FileTable::FILE_RECORD_LIMIT);
      fbLib::addSecSQL($q,'f');
      if($p['offset'] > 0){
	$q->offset($p['offset'] * FileTable::FILE_RECORD_LIMIT);
      }
      $reply = new fbPhotoReply();
      $fish = new fbPhotoFish();
      if($rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR)){
	foreach($rows as $i => $row){
	  $rec = $this->sqlRow($row);
	  $rec['reply_count'] = $reply->replyCount($rec['id']);
	  $rec['fish_count'] = $fish->fishCount($rec['id']);
	  $recs[] = $rec;
	}
	$ret['count'] = count($recs);
	$ret['records'] = $recs;
      }
    }
    return $ret;
  }
 
}