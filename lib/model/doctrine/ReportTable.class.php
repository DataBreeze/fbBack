<?php
  /**
   * ReportTable
   * This class has been auto-generated by the Doctrine ORM Framework
   * Report = Fish Catch
   */
class ReportTable extends Doctrine_Table{

  const REPORT_RECORD_LIMIT = 20;
  const REPORT_BY_FISH_RECORD_LIMIT = 100;
  
  public static function getInstance(){
    return Doctrine_Core::getTable('Report');
  }
  
  public function addReport($post){
    $geo = $post['geo'];
    $rep = new Report();
    $sec = intval($post['sec']);
    if($sec < 1){
      $sec = 1;
    }elseif( ($sec == fbLib::SEC_GROUP) and $post['group_id'] ){
      $rep->setGroupId($post['group_id']);	
    }
    $rep->setSec($sec);
    
    $rep->setLat($geo['lat']);
    $rep->setLon($geo['lon']);
    $rep->setUserId($post['user_id']);
    
    $post['content'] = strip_tags($post['content']);
    $post['content'] = trim($post['content']);
    $rep->setContent($post['content']);
    
    $post['caption'] = strip_tags($post['caption']);
    $post['caption'] = trim($post['caption']);
    $rep->setCaption($post['caption']);
    
    $post['loc'] = strip_tags($post['loc']);
    $post['loc'] = trim($post['loc']);
    $rep->setLoc($post['loc']);
    
    $rep->setWeight($post['weight']);
    $rep->setLength($post['length']);
    $rep->setCountFish($post['count']);
    
    if($post['date_catch']){
      $rep->setDateCatch($post['date_catch']);
    }else{
      $rep->setDateCatch(new Doctrine_Expression('NOW()'));
    }
    $rep->setDateCreate(new Doctrine_Expression('NOW()'));
    
    $post['fish_name'] = trim(strip_tags($post['fish_name']));
    $rep->setFishName($post['fish_name']);
    if($post['fish_id']){
      $rep->setFishId($post['fish_id']);
    }elseif($fish = Doctrine_Core::getTable('Fish')->fishByName($post['fish_name'])){
      if( $fish['id'] ){
	$rep->setFishId($fish['id']);
      }
    }
    $rep->save();
    $report_id = $rep->getId();
    $new_report = $this->getReport($report_id);
    return $new_report;
  }
  
  public function updateReport($post){
    $report_id = $post['report_id'];
    if($rep = $this->getInstance()->find($report_id)){	
      if($post['sec']){
	$sec = intval($post['sec']);
	if($sec < 1){
	  $sec = 1;
	}elseif( ($sec == fbLib::SEC_GROUP) and $post['group_id'] ){
	  $rep->setGroupId($post['group_id']);	
	}
	$rep->setSec($sec);
      }
      if($post['content'] != $rep->getContent()){
	$post['content'] = strip_tags($post['content']);
	$post['content'] = trim($post['content']);
	$rep->setContent($post['content']);
      }
      if($post['caption'] != $rep->getCaption()){
	$post['caption'] = strip_tags($post['caption']);
	$post['caption'] = trim($post['caption']);
	$rep->setCaption($post['caption']);
      }
      if($post['loc'] != $rep->getLoc()){
	$post['loc'] = strip_tags($post['loc']);
	$post['loc'] = trim($post['loc']);
	$rep->setLoc($post['loc']);
      }
      if( $post['fish_name'] ){
	$post['fish_name'] = trim(strip_tags($post['fish_name']));
	$rep->setFishName($post['fish_name']);
	if($post['fish_id']){
	  $rep->setFishId($post['fish_id']);
	}elseif($fish = Doctrine_Core::getTable('Fish')->fishByName($post['fish_name'])){
	  if( $fish['id'] ){
	    $rep->setFishId($fish['id']);
	  }
	}
      }
      $rep->setWeight($post['weight']);
      $rep->setLength($post['length']);
      $rep->setCountFish($post['count']);
      
      if($post['date_catch']){
	$rep->setDateCatch($post['date_catch']);
      }else{
	$rep->setDateCatch(new Doctrine_Expression('NOW()'));
      }
      $rep->save();
      $new_report = $this->getReport($report_id);
      return $new_report;
    }else{
      return False;
    }
  }

  public function editGeo($post){
    $geo = $post['geo'];
    $report_id = $post['report_id'];
    if($rep = $this->find($report_id)){	
      if($geo and $geo['lat'] and $geo['lon']){
	$rep->setLat($geo['lat']);
	$rep->setLon($geo['lon']);
	$rep->save();
	$new_report = $this->getReport($report_id);
	return $new_report;
      }
    }
    return False;
  }
  
  public function getRecAllow($report_id){
    if($rec = $this->getReport($report_id)){
      return fbLib::getRecAllow($rec);
    }
    return False;
  }

  public function getRec($report_id){
    return $this->getReport($report_id);
  }
  
  public function getReport($report_id){
    $q = Doctrine_Query::create()
      ->select($this->sqlSelect())
      ->from('Report r')
      ->leftJoin('r.Fish f')
      ->innerJoin('r.User u')
      ->where('r.id = ?',$report_id);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $rec = array();
    $reply = new fbReportReply();
    $file = new fbReportFile();
    $fish = new fbReportFish();
    if( count($rows) > 0){
      $rec = $this->sqlRow($rows[0]);
      $rec['reply_count'] = $reply->replyCount($report_id);
      $rec['photo_count'] = $file->photoCount($report_id);
      $rec['fish_count'] = $fish->fishCount($report_id);
    }
    return $rec;
  }

  public function getReportsBBCount(){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS report_count')
      ->from('Report r')
      ->innerJoin('r.User u');
    fbLib::addBoundsSQL($q,'r');
    fbLib::addDateRangeSQL($q,'MONTH(r.date_catch)');
    fbLib::addFishSQL($q, array('source'=>'r', 'fish_table'=>False));
    if( ! fbLib::addSecSQL($q,'r')){
      return 0;
    }
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['r_report_count']);
  }
  public function getReportsBB($offset = 0){
    $count = $this->getReportsBBCount();
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => intval($offset),
		 'record_limit' => ReportTable::REPORT_RECORD_LIMIT);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Report r')
	->innerJoin('r.User u')
	->leftJoin('r.Fish f')
	->orderBy('r.date_create DESC')
	->limit(ReportTable::REPORT_RECORD_LIMIT);
      fbLib::addBoundsSQL($q,'r');
      fbLib::addSecSQL($q,'r');
      fbLib::addFishSQL($q, array('source'=>'r', 'fish_table'=>False));
      if($offset > 0){
	$q->offset($offset * ReportTable::REPORT_RECORD_LIMIT);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $recs = array();
      $reply = new fbReportReply();
      $file = new fbReportFile();
      $fish = new fbReportFish();
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

  private function sqlSelect(){
    $sql = 'r.id,r.group_id,r.sec,r.lat,r.lon,UNIX_TIMESTAMP(r.date_create) AS uts,DATE_FORMAT(r.date_create,\'%c/%e/%y @ %r\') AS date_time_catch,DATE_FORMAT(r.date_create,\'%c/%e/%y\') AS date_catch,HOUR(r.date_catch) AS dhour,r.caption,r.content,r.fish_name,r.length,r.weight,r.count_fish,r.loc,r.user_id,u.username,u.photo_id,f.name,f.wiki_title,f.name_sci';
    return $sql;
  }

  private function sqlRow($row){
    $rec = array();
    $rec['id'] = intval($row['r_id']);
    $rec['group_id'] = intval($row['r_group_id']);
    $rec['sec'] = intval($row['r_sec']);
    $rec['lat'] = floatval($row['r_lat']);
    $rec['lon'] = floatval($row['r_lon']);
    $rec['date_catch'] = $row['r_date_catch'];
    $rec['date_create'] = $row['r_date_catch'];
    $rec['date_time_catch'] = $row['r_date_time_catch'];
    $rec['date_time'] = $row['r_date_time_catch'];
    $rec['date'] = $row['r_date_catch'];
    $rec['uts'] = intval($row['r_uts']);
    $rec['hour'] = intval($row['r_dhour']);
    $rec['year'] = $row['r_dyear'];
    $rec['month'] = $row['r_dmonth'];
    $rec['day'] = $row['r_dday'];
    $rec['loc'] = $row['r_loc'];
    $rec['location'] = $row['r_loc'];
    $rec['fish_name_orig'] = $row['r_fish_name'];
    $rec['caption'] = trim($row['r_caption']);
    $rec['content'] = trim($row['r_content']);
    $rec['weight'] = trim($row['r_weight']);
    $rec['length'] = trim($row['r_length']);
    $rec['count'] = trim($row['r_count_fish']);
    $rec['user_id'] = $row['r_user_id'];
    $rec['fish_name'] = ( $row['f_name'] ? $row['f_name'] : $row['r_fish_name']);
    $rec['wiki_title'] = $row['f_wiki_title'];
    $rec['noaa_id'] = $row['f_noaa_id'];
    $rec['username'] = $row['u_username'];
    $rec['photo_id'] = $row['u_photo_id'];
    $rec['fb_source'] = 'catch';
    return $rec;
  }
  
  public function getReportsBBGWCount(){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS report_count')
      ->from('Report r')
      ->innerJoin('r.User u')
      ->where('r.sec = 1');
    fbLib::addBoundsSQL($q,'r');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['r_report_count']);
  }

  public function getReportsBBGW($p){
    if( ! $p['limit']){
      $p['limit'] = ReportTable::REPORT_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $count = $this->getReportsBBGWCount();
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => $p['offset'], 'record_limit' => $p['limit']);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Report r')
	->innerJoin('r.User u')
	->leftJoin('r.Fish f')
	->where('r.sec = 1')
	->orderBy('r.date_create DESC');
      fbLib::addBoundsSQL($q,'r');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $recs = array();
      $reply = new fbReportReply();
      $file = new fbReportFile();
      $fish = new fbReportFish();
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

  public function reportsByUserIdCount($user_id){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS report_count')
      ->from('Report r')
      ->innerJoin('r.User u')
      ->where('r.user_id = ?',$user_id)
      ->andWhere('r.sec = 1');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['r_report_count']);
  }

  public function reportsByUserId($p){
    $user_id = $p['user_id'];
    if( ! $p['limit']){
      $p['limit'] = ReportTable::REPORT_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $count = $this->reportsByUserIdCount($user_id);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => $p['offset'], 'record_limit' => $p['limit']);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Report r')
	->innerJoin('r.User u')
	->leftJoin('r.Fish f')
	->where('r.user_id = ?',$user_id)
	->andWhere('r.sec = 1')
	->orderBy('r.date_create DESC');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $recs = array();
      $reply = new fbReportReply();
      $file = new fbReportFile();
      $fish = new fbReportFish();
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

  public function reportsByGroupIdCount($group_id){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS report_count')
      ->from('Report r')
      ->innerJoin('r.User u')
      ->innerJoin('r.UserGroup g')
      ->where('r.group_id = ?',$group_id)
      ->andWhere('r.sec = 1');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['r_report_count']);
  }

  public function reportsByGroupId($p){
    $group_id = $p['group_id'];
    if( ! $p['limit']){
      $p['limit'] = ReportTable::REPORT_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $count = $this->reportsByGroupIdCount($group_id);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => $p['offset'], 'record_limit' => $p['limit']);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Report r')
	->innerJoin('r.User u')
	->innerJoin('r.UserGroup g')
	->leftJoin('r.Fish f')
	->where('r.group_id = ?',$group_id)
	->andWhere('r.sec = 1')
	->orderBy('r.date_create DESC');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $recs = array();
      $reply = new fbReportReply();
      $file = new fbReportFile();
      $fish = new fbReportFish();
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

  public function reportsByFishIdCount($fish_id){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS report_count')
      ->from('Report r')
      ->innerJoin('r.User u')
      ->innerJoin('r.Fish f')
      ->where('r.fish_id = ?',$fish_id)
      ->andWhere('r.sec = 1');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['r_report_count']);
  }

  public function reportsByFishId($p){
    $fish_id = $p['fish_id'];
    if( ! $p['limit']){
      $p['limit'] = ReportTable::REPORT_RECORD_LIMIT;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $count = $this->reportsByFishIdCount($fish_id);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => $p['offset'], 'record_limit' => $p['limit']);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Report r')
	->innerJoin('r.User u')
	->innerJoin('r.Fish f')
	->where('r.fish_id = ?',$fish_id)
	->andWhere('r.sec = 1')
	->orderBy('r.date_create DESC');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $recs = array();
      $reply = new fbReportReply();
      $file = new fbReportFile();
      $fish = new fbReportFish();
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

  public function reportSearchCount($param){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS report_count')
      ->from('Report r')
      ->innerJoin('r.User u');
    fbLib::addBoundsSQL($q,'r');
    fbLib::addDateRangeSQL($q,'MONTH(r.date_catch)');
    fbLib::addSearchSQL($q,$param);
    fbLib::addFishSQL($q, array('source'=>'r', 'fish_table'=>False));
    if( ! fbLib::addSecSQL($q,'r')){
      return 0;
    }
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['r_report_count']);
  }
  
  public function reportSearch($param,$offset = 0){
    $count = $this->reportSearchCount($param);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => intval($offset),
		 'record_limit' => ReportTable::REPORT_RECORD_LIMIT);
    if($count){
      $q = Doctrine_Query::create()
	->select('r.id,r.group_id,r.sec,r.lat,r.lon,DATE_FORMAT(r.date_catch,\'%c/%e/%y @ %r\') AS date_time_catch,DATE_FORMAT(r.date_catch,\'%c/%e/%y\') AS date_catch,HOUR(r.date_catch) AS dhour,r.caption,r.content,r.fish_name,r.length,r.weight,r.count_fish,r.loc,r.user_id,u.username,u.photo_id,f.name,f.wiki_title,f.name_sci')
	->from('Report r')
	->innerJoin('r.User u')
	->leftJoin('r.Fish f')
	->orderBy('r.date_create DESC')
	->limit(ReportTable::REPORT_RECORD_LIMIT);
      fbLib::addBoundsSQL($q,'r');
      fbLib::addDateRangeSQL($q,'MONTH(r.date_catch)');
      fbLib::addSecSQL($q,'r');
      fbLib::addSearchSQL($q,$param);
      fbLib::addFishSQL($q, array('source'=>'r', 'fish_table'=>False));
      if($offset > 0){
	$q->offset($offset * ReportTable::REPORT_RECORD_LIMIT);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $recs = array();
      $reply = new fbReportReply();
      $file = new fbReportFile();
      $fish = new fbReportFish();
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

  public function getReportsCount(){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS report_count')
      ->from('Report r')
      ->innerJoin('r.User u');
    if( ! fbLib::addSecSQL($q,'r')){
      return 0;
    }
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['r_report_count']);
  }

  public function getReports($p){
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $count = $this->getReportsCount();
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => intval($p['offset']),
		 'record_limit' => ReportTable::REPORT_RECORD_LIMIT);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Report r')
	->innerJoin('r.User u')
	->leftJoin('r.Fish f')
	->orderBy('r.date_create DESC')
	->limit(ReportTable::REPORT_RECORD_LIMIT);
      fbLib::addSecSQL($q,'r');
      if($p['offset'] > 0){
	$q->offset($p['offset'] * ReportTable::REPORT_RECORD_LIMIT);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $recs = array();
      $reply = new fbReportReply();
      $file = new fbReportFile();
      $fish = new fbReportFish();
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

  public function deleteReport($report_id){
    return Doctrine_Query::create()
      ->delete()
      ->from('Report r')
      ->where('r.id = ?', $report_id)
      ->execute();
  }
  
  public function deletePhoto($report_id,$file_id){
    $q = Doctrine_Query::create()
      ->delete()
      ->from('FileForReport f')
      ->where('f.report_id = ?',$report_id)
      ->andWhere('f.file_id = ?',$file_id)
      ->execute();
  }
  
}