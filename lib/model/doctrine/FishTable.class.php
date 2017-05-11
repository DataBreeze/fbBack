<?php
  /**
 * FishTable
 */
class FishTable extends Doctrine_Table{

  const FISH_RECORD_LIMIT = 100;

  public static function getInstance(){
    return Doctrine_Core::getTable('Fish');
  }

  public function addFish($p){
    $fish = new Fish();
    $fish->setUserId($p['user_id']);
    $p['name'] = trim(strip_tags($p['name']));
    $fish->setName($p['name']);
    $p['name_sci'] = strip_tags($p['name_sci']);
    $p['name_sci'] = trim($p['name_sci']);
    $fish->setNameSci($p['name_sci']);
    $p['detail'] = trim($p['detail']);
    $fish->setDetail($p['detail']);
    $p['alias'] = trim(strip_tags($p['alias']));
    $fish->setAlias($p['alias']);
    $fish->setDateCreate(new Doctrine_Expression('NOW()'));
    $fish->setStatus(0);
    $fish->setLat($p['lat']);
    $fish->setLon($p['lon']);
    $fish->save();
    $fish_id = $fish->getId();
    $new_fish = $this->getRec($fish_id);
    return $new_fish;
  }
  
  public function editFish($p){
    if($fish = $this->find($p['id'])){	
      if($p['name'] != $fish->getName()){
	$p['name'] = trim(strip_tags($p['name']));
	$fish->setName($p['name']);
      }
      if($p['name_sci'] != $fish->getNameSci()){
	$p['name_sci'] = trim(strip_tags($p['name_sci']));
	$fish->setNameSci($p['name_sci']);
      }
      if($p['detail'] != $fish->getDetail()){
	$p['detail'] = trim($p['detail']);
	$fish->setDetail($p['detail']);
      }
      if($p['alias'] != $fish->getAlias()){
	$p['alias'] = trim(strip_tags($p['alias']));
	$fish->setAlias($p['alias']);
      }
      $fish->save();
      $new_fish = $this->getRec($p['id']);
      return $new_fish;
    }
    return False;
  }


  public function editFishGeo($p){
    if($fish = $this->find($p['id'])){	
      if($p['lat'] and $p['lon']){
	$fish->setLat($p['lat']);
	$fish->setLon($p['lon']);
      }
      $fish->save();
      $new_fish = $this->getRec($p['id']);
      return $new_fish;
    }
    return False;
  }

  public function deleteFish($p){
    if($fish = $this->find($p['id'])){	
      $q = Doctrine_Query::create()
	->delete()
	->from('Fish f')
	->where('f.id = ?', $p['id']);
      $q->execute();
      return True;
    }
    return False;
  }

  private function sqlSelect(){
    return 'f.id,f.name,f.name_sci,f.detail,f.alias,f.wiki_title,f.lat,f.lon,f.count_fish,f.avg_weight,f.avg_length,u.username';
  }
  
  private function sqlRow($row){
    $rec = array();
    $rec['id'] = intval($row['f_id']);
    $rec['name'] = $row['f_name'];
    $rec['name_sci'] = $row['f_name_sci'];
    $rec['noaa_id'] = intval($row['f_noaa_id']);
    $rec['wiki_title'] = $row['f_wiki_title'];
    $rec['detail'] = $row['f_detail'];
    $rec['alias'] = $row['f_alias'];
    $rec['count'] = 0;
    $rec['avg_length'] = 0;
    $rec['avg_weight'] = 0;
    $rec['lat'] = floatval($row['f_lat']);
    $rec['lon'] = floatval($row['f_lon']);
    $rec['count'] = floatval($row['f_count_fish']);
    $rec['weight'] = floatval($row['f_avg_weight']);
    $rec['length'] = floatval($row['f_avg_length']);
    $rec['username'] = $row['u_username'];
    return $rec;
  }
    

  ## find one fish by name
  public function fishByName($name){
    $q = Doctrine_Query::create()
      ->select($this->sqlSelect())
      ->from('Fish f')
      ->innerJoin('f.User u')
      ->where('f.name = ?', $name)
      ->andWhere('f.status = 0');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $row = $rows[0];
    $p = array('limit' => 10, 'offset' => 0);
    if($row){
      $rec = $this->sqlRow($row);
      $p['fish_id'] = $rec['id'];
      $rec['blogs'] = Doctrine_Core::getTable('Blog')->blogsByFishId($p);
      $rec['photos'] = Doctrine_Core::getTable('File')->photosByFishId($p);
      $rec['reports'] = Doctrine_Core::getTable('Report')->reportsByFishId($p);
      $rec['spots'] = Doctrine_Core::getTable('Spot')->spotsByFishId($p);
      $rec['discs'] = Doctrine_Core::getTable('Disc')->discsByFishId($p);
      $rec['users'] = Doctrine_Core::getTable('User')->usersByFishId($p);
      $rec['groups'] = Doctrine_Core::getTable('UserGroup')->groupsByFishId($p);
      return $rec;
    }else{
      return False;
    }
  }
  
  public function getRec($fish_id){
    $q = Doctrine_Query::create()
      ->select($this->sqlSelect())
      ->from('Fish f')
      ->innerJoin('f.User u')
      ->where('f.id = ?', $fish_id)
      ->andWhere('f.status = 0');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $row = $rows[0];
    if($row){
      $rec = $this->sqlRow($row);
      return $rec;
    }else{
      return False;
    }
  }
  
  ## find one fish by ID
  public function fishById($fish_id){
    $q = Doctrine_Query::create()
      ->select($this->sqlSelect())
      ->from('Fish f')
      ->innerJoin('f.User u')
      ->where('f.id = ?', $fish_id)
      ->andWhere('f.status = 0');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $row = $rows[0];
    if($row){
      $rec = $this->sqlRow($row);
      return $rec;
    }else{
      return False;
    }
  }

  public function fishAllBBDELETE($geo,$offset){
    $both = array();
    $fU = $this->fishBB($geo,$offset);
    $fN = Doctrine_Core::getTable('CatchByMonth')->catchBB($geo,$offset);
    foreach ($fN['hash'] as $id => $fish){
      $fish['noaa'] = True;
      $both[$id] = $fish;
    }
    foreach ($fU['hash'] as $id => $fish){
      if($both[$id]){
	$both[$id]['count'] += $fish['count'];
	$both[$id]['avg_length'] = ($both['avg_length'] + $fish['avg_length']) / 2;
	$both[$id]['avg_weight'] = ($both['avg_weight'] + $fish['avg_weight']) / 2;
      }else{
	$fish['noaa'] = False;
	$both[$id] = $fish;
      }
    }
    $final = array();
    foreach ($both as $id => $fish){
      array_push($final,$fish);
    }
    $count = count($final);
    $fUIds = $this->fishIdBB($geo);
    $fNIds = Doctrine_Core::getTable('CatchByMonth')->fishIdBB($geo);
    $both = array();
    foreach ($fNIds as $i => $id){
      $both[$id] = $id;
    }
    foreach ($fUIds as $i => $id){
      $both[$id] = $id;
    }
    $count_total = count($both);
    $ret = array('count_total' => $count_total, 'count' => $count, 'records' => $final,
		 'record_offset' => $offset, 'record_limit' => FishTable::FISH_RECORD_LIMIT);
    return $ret;
  }

  ## array of fish ids used to create merged count with noaa fish
  public function fishIdBBDELETE($geo){
    $recs = array();
    $q = Doctrine_Query::create()
      ->select('f.id')
      ->from('Fish f')
      ->innerJoin('f.User u')
      ->innerJoin('f.Report r')
      ->where('r.lat > ? AND r.lat < ? AND r.lon > ? AND r.lon < ?', array($geo['sw_lat'],$geo['ne_lat'],$geo['sw_lon'],$geo['ne_lon']))
      ->andWhere('f.status = 0')
      ->groupBy('f.id');
    fbLib::addDateRangeSQL($q,'MONTH(r.date_catch)');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    foreach($rows as $i => $row){
      $recs[$i] = intval($row['f_id']);
    }
    return $recs;
  }
  
  public function fishDetailAllDELETE($fish_id){
    if($fish = $this->fishDetail($fish_id)){
      if($fN = Doctrine_Core::getTable('CatchByMonth')->fishDetail($fish_id)){
	$fish['count'] += $fN['count'];
	$fish['avg_length'] = ($fish['avg_length'] + $fN['avg_length']) / 2;
	$fish['avg_weight'] = ($fish['avg_weight'] + $fN['avg_weight']) / 2;
      }
      return $fish;
    }
    return False;
  }
  
  ## report fish (not noaa)
  public function fishDetail($fish_id){
    $q = Doctrine_Query::create()
      ->select('f.id,f.name,f.name_sci AS name_sci,f.noaa_id,f.wiki_title,SUM(r.count_fish) AS count_fish,FORMAT(AVG(r.length),1) AS avg_length,FORMAT(AVG(r.weight),1) AS avg_weight')
      ->from('Fish f')
      ->innerJoin('f.User u')
      ->innerJoin('f.Report r')
      ->where('f.id = ?', $fish_id)
      ->andWhere('f.status = 0');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    if($rows and ($row = $rows[0]) ){
      $rec = array();
      $fish_id = intval($row['f_id']);
      $rec['id'] = $fish_id;
      $rec['noaa_id'] = intval($row['f_noaa_id']);
      $rec['name'] = $row['f_name'];
      $rec['name_sci'] = $row['f_name_sci'];
      $rec['wiki_title'] = $row['f_wiki_title'];
      $rec['count'] = $row['r_count_fish'];
      $rec['avg_weight'] = ($row['r_avg_weight'] ? $row['r_avg_weight'] : '');
      $rec['avg_length'] = ($row['r_avg_length'] ? $row['r_avg_length'] : '');
      return $rec;
    }
    return False;
  }

  public function fishNoaaBBCount($geo){
    $q = Doctrine_Query::create()
      ->select('COUNT(DISTINCT f.id) AS fish_count')
      ->from('Fish f')
      ->innerJoin('f.User u')
      ->innerJoin('f.CatchByMonth c')
      ->innerJoin('c.Spot s')
      ->where('s.lat > ? AND s.lat < ? AND s.lon > ? AND s.lon < ?', array($geo['sw_lat'],$geo['ne_lat'],$geo['sw_lon'],$geo['ne_lon']))
      ->andWhere('f.status = 0');
    fbLib::addDateRangeSQL($q,'c.cmonth');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['c_fish_count']);
  }
  
  ## catch by bounds queried by month
  public function fishNoaaBB($geo,$offset = 0){
    $count = $this->fishBBCount($geo);
    $recs = array();
    $hash = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs, 'hash' => $hash,
		 'record_offset' => intval($offset),
		 'record_limit' => FishTable::FISH_RECORD_LIMIT);
    if($count){
      $q = Doctrine_Query::create()
	->select('f.id,f.name,f.name_sci AS name_sci,f.noaa_id,f.wiki_title,SUM(c.count_fish) AS count_fish,FORMAT(AVG(c.avg_length),1) AS avg_length,FORMAT(AVG(c.avg_weight),1) AS avg_weight')
	->from('Fish f')
	->innerJoin('f.User u')
	->innerJoin('f.CatchByMonth c')
	->innerJoin('c.Spot s')
	->where('s.lat > ? AND s.lat < ? AND s.lon > ? AND s.lon < ?', array($geo['sw_lat'],$geo['ne_lat'],$geo['sw_lon'],$geo['ne_lon']))
	->andWhere('f.status = 0')
	->groupBy('f.id')
	->orderBy('SUM(c.count_fish) DESC')
	->limit(FishTable::FISH_RECORD_LIMIT);
      fbLib::addDateRangeSQL($q,'c.cmonth');
      if($offset > 0){
	$q->offset($offset * FishTable::FISH_RECORD_LIMIT);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      foreach($rows as $i => $row){
	$fish_id = intval($row['f_id']);
	$recs[$i]['id'] = $fish_id;
	$recs[$i]['noaa_id'] = intval($row['f_noaa_id']);
	$recs[$i]['name'] = $row['f_name'];
	$recs[$i]['name_sci'] = $row['f_name_sci'];
	$recs[$i]['wiki_title'] = $row['f_wiki_title'];
	$recs[$i]['count'] = $row['c_count_fish'];
	$recs[$i]['avg_weight'] = ($row['c_avg_weight'] ? $row['c_avg_weight'] : '');
	$recs[$i]['avg_length'] = ($row['c_avg_length'] ? $row['c_avg_length'] : '');
	$recs[$i]['catch_annual'] = Doctrine_Core::getTable('CatchByMonth')->getCatchByFishAnnualBB($fish_id,$geo);
	$recs[$i]['catch_top_sites'] = Doctrine_Core::getTable('CatchByMonth')->getTopSitesByFishBB($fish_id,$geo);
	$hash[$fish_id] = $recs[$i];
	$ret['hash'] = $hash;
      }
      $ret['count'] = count($recs);
      $ret['records'] = $recs;
    }
    return $ret;
  }
 
  ## new 3-22-12
  public function fishBBCount(){
    $q = Doctrine_Query::create()
      ->select('COUNT(DISTINCT f.id) AS fish_count')
      ->from('Fish f')
      ->innerJoin('f.User u')
      ->where('f.status = 0');
    fbLib::addBoundsSQL($q,'f');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['f_fish_count']);
  }
  
  public function fishBB($offset = 0){
    $count = $this->fishBBCount();
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => intval($offset),
		 'record_limit' => FishTable::FISH_RECORD_LIMIT);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Fish f')
	->innerJoin('f.User u')
	->where('f.status = 0')
	->groupBy('f.id')
	->orderBy('f.name')
	->limit(FishTable::FISH_RECORD_LIMIT);
      fbLib::addBoundsSQL($q,'f');
      if($offset > 0){
	$q->offset($offset * FishTable::FISH_RECORD_LIMIT);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      foreach($rows as $i => $row){
	$fish_id = intval($row['f_id']);
	$recs[$i]['id'] = $fish_id;
	$recs[$i]['name'] = $row['f_name'];
	$recs[$i]['name_sci'] = $row['f_name_sci'];
	$recs[$i]['detail'] = $row['f_detail'];
	$recs[$i]['alias'] = $row['f_alias'];
	$recs[$i]['wiki_title'] = $row['f_wiki_title'];
	$recs[$i]['lat'] = $row['f_lat'];
	$recs[$i]['lon'] = $row['f_lon'];
	$recs[$i]['username'] = $row['u_username'];
      }
      $ret['count'] = count($recs);
      $ret['records'] = $recs;
    }
    return $ret;
  }

  public function fishSearchCount($param){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS fish_count')
      ->from('Fish f')
      ->innerJoin('f.User u');
    fbLib::addSearchSQL($q,$param);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['f_fish_count']);
  }

  public function fishSearch($param){
    $count = $this->fishSearchCount($param);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => intval($offset),
		 'record_limit' => FishTable::FISH_RECORD_LIMIT);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Fish f')
	->innerJoin('f.User u')
	->where('f.status = 0')
	->orderBy('f.name')
	->limit(FishTable::FISH_RECORD_LIMIT);
      if($offset > 0){
	$q->offset($offset * FishTable::FISH_RECORD_LIMIT);
      }
      fbLib::addSearchSQL($q,$param);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      foreach($rows as $i => $row){
	$recs[$i] = $this->sqlRow($row);
      }
      $ret['count'] = count($recs);
      $ret['records'] = $recs;
    }
    return $ret;
  }
  
}