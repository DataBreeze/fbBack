<?php

class CatchByMonthTable extends Doctrine_Table{

  const FISH_RECORD_LIMIT = 100;
  const MAX_SELECT_FISH = 6;
  const TAG_CLOUD_CLASS_COUNT = 10;

  public static function getInstance(){
    return Doctrine_Core::getTable('CatchByMonth');
  }
  
  public function getCatchByMonthAndSites($site_list,$month_range){
    if(count($site_list) < 1){
      return array();
    }
    $q = Doctrine_Query::create()
      ->select('c.cmonth AS month,c.fish_id AS fish_id,SUM(c.count_fish) AS count_fish,FORMAT(AVG(c.avg_length),1) AS avg_length,FORMAT(AVG(c.avg_weight),1) AS avg_weight,f.name_common AS name')
      ->from('CatchByMonth c')
      ->innerJoin('c.FishSpecies f')
      ->whereIn('c.int_site_id', $site_list)
      ->andWhere('c.cmonth >= ? AND c.cmonth <= ?', $month_range)
      ->groupBy('c.fish_id')
      ->orderBy('SUM(count_fish) DESC')
      ->limit(100);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $json = array();
    foreach($rows as $i => $row){
      $json[$i]['fish_id'] = intval($row['c_fish_id']);
      $json[$i]['name'] = $row['f_name'];
      $json[$i]['count'] = $row['c_count_fish'];
      $json[$i]['avg_weight'] = ($row['c_avg_weight'] ? $row['c_avg_weight'] : '');
      $json[$i]['avg_length'] = ($row['c_avg_length'] ? $row['c_avg_length'] : '');
    }
    return $json;
  }
  ## catch by bounds queried by month
  public function catch2BB($geo,$limit = 20){
    $q = Doctrine_Query::create()
      ->select('c.fish_id,SUM(c.count_fish) AS count_fish,FORMAT(AVG(c.avg_length),1) AS avg_length,FORMAT(AVG(c.avg_weight),1) AS avg_weight,f.rate,f.name_common')
      ->from('CatchByMonth c')
      ->innerJoin('c.FishSpecies f')     
      ->innerJoin('c.Site s')
      ->andWhere('s.lat > ? AND s.lat < ?', array($geo['sw_lat'],$geo['ne_lat']))
      ->andWhere('s.lon > ? AND s.lon < ?', array($geo['sw_lon'],$geo['ne_lon']))
      ->groupBy('c.fish_id')
      ->orderBy('SUM(count_fish) DESC')
      ->limit($limit);
    fbLib::addDateRangeSQL($q,'c.cmonth');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $json = array();
    foreach($rows as $i => $row){
      $json[$i]['fish_id'] = intval($row['c_fish_id']);
      $json[$i]['name'] = $row['f_name_common'];
      $json[$i]['rate'] = $row['f_rate'];
      $json[$i]['count'] = $row['c_count_fish'];
      $json[$i]['avg_weight'] = ($row['c_avg_weight'] ? $row['c_avg_weight'] : '');
      $json[$i]['avg_length'] = ($row['c_avg_length'] ? $row['c_avg_length'] : '');
    }
    return $json;
  }

  public function catchBBCount($geo){
    $q = Doctrine_Query::create()
      ->select('COUNT(DISTINCT f.id) AS fish_count')
      ->from('CatchByMonth c')
      ->innerJoin('c.FishSpecies f')     
      ->innerJoin('c.Site s')
      ->where('s.lat > ? AND s.lat < ?', array($geo['sw_lat'],$geo['ne_lat']))
      ->andWhere('s.lon > ? AND s.lon < ?', array($geo['sw_lon'],$geo['ne_lon']));
    fbLib::addDateRangeSQL($q,'c.cmonth');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['f_fish_count']);
  }

  # return array of ALL fish_ids. Used for counting when merging with non-noaa data
  public function fishIdBB($geo){
    $recs = array();
    $q = Doctrine_Query::create()
      ->select('c.fish_id')
      ->from('CatchByMonth c')
      ->innerJoin('c.FishSpecies f')     
      ->innerJoin('c.Site s')
      ->andWhere('s.lat > ? AND s.lat < ?', array($geo['sw_lat'],$geo['ne_lat']))
      ->andWhere('s.lon > ? AND s.lon < ?', array($geo['sw_lon'],$geo['ne_lon']))
      ->groupBy('c.fish_id');
    fbLib::addDateRangeSQL($q,'c.cmonth');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    foreach($rows as $i => $row){
      $recs[$i] = intval($row['c_fish_id']);
    }
    return $recs;
  }

  # catch by bounds queried by month
  public function catchBB($geo,$offset = 0){
    $count = $this->catchBBCount($geo);
    $recs = array();
    $hash = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs, 'hash' => $hash,
		 'record_offset' => intval($offset),
		 'record_limit' => CatchByMonthTable::FISH_RECORD_LIMIT);
    if($count){
      $q = Doctrine_Query::create()
	->select('c.fish_id,SUM(c.count_fish) AS count_fish,FORMAT(AVG(c.avg_length),1) AS avg_length,FORMAT(AVG(c.avg_weight),1) AS avg_weight,f.name_common,f.name_sci,f.wiki_title')
	->from('CatchByMonth c')
	->innerJoin('c.FishSpecies f')     
	->innerJoin('c.Site s')
	->andWhere('s.lat > ? AND s.lat < ?', array($geo['sw_lat'],$geo['ne_lat']))
	->andWhere('s.lon > ? AND s.lon < ?', array($geo['sw_lon'],$geo['ne_lon']))
	->groupBy('c.fish_id')
	->orderBy('SUM(count_fish) DESC')
	->limit(CatchByMonthTable::FISH_RECORD_LIMIT);
      fbLib::addDateRangeSQL($q,'c.cmonth');
      if($offset > 0){
	$q->offset($offset * CatchByMonthTable::FISH_RECORD_LIMIT);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      foreach($rows as $i => $row){
	$fish_id = intval($row['c_fish_id']);
	$recs[$i]['id'] = $fish_id;
	$recs[$i]['name'] = $row['f_name_common'];
	$recs[$i]['name_sci'] = $row['f_name_sci'];
	$recs[$i]['wiki_title'] = $row['f_wiki_title'];
	$recs[$i]['count'] = $row['c_count_fish'];
	$recs[$i]['avg_weight'] = ($row['c_avg_weight'] ? $row['c_avg_weight'] : '');
	$recs[$i]['avg_length'] = ($row['c_avg_length'] ? $row['c_avg_length'] : '');
	$hash[$fish_id] = $recs[$i];
      }
      $ret['count'] = count($recs);
      $ret['records'] = $recs;
      $ret['hash'] = $hash;
    }
    return $ret;
  }
  
  public function fishBBCount($geo){
    $q = Doctrine_Query::create()
      ->select('COUNT(DISTINCT c.fish_id) AS fish_count')
      ->from('CatchByMonth c')
      ->innerJoin('c.FishSpecies f')     
      ->innerJoin('c.Site s')
      ->andWhere('s.lat > ? AND s.lat < ?', array($geo['sw_lat'],$geo['ne_lat']))
      ->andWhere('s.lon > ? AND s.lon < ?', array($geo['sw_lon'],$geo['ne_lon']));
    fbLib::addDateRangeSQL($q,'c.cmonth');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['c_fish_count']);
  }

  # catch by bounds queried by month
  public function fishBB($geo,$offset = 0){
    $count = $this->fishBBCount($geo);
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => intval($offset),
		 'record_limit' => CatchByMonthTable::FISH_RECORD_LIMIT);
    if($count){
      $q = Doctrine_Query::create()
	->select('c.fish_id,SUM(c.count_fish) AS count_fish,FORMAT(AVG(c.avg_length),1) AS avg_length,FORMAT(AVG(c.avg_weight),1) AS avg_weight,f.rate,f.name_common,f.name_sci AS name_sci')
	->from('CatchByMonth c')
	->innerJoin('c.FishSpecies f')     
	->innerJoin('c.Site s')
	->andWhere('s.lat > ? AND s.lat < ?', array($geo['sw_lat'],$geo['ne_lat']))
	->andWhere('s.lon > ? AND s.lon < ?', array($geo['sw_lon'],$geo['ne_lon']))
	->groupBy('c.fish_id')
	->orderBy('SUM(count_fish) DESC')
	->limit(CatchByMonthTable::FISH_RECORD_LIMIT);
      fbLib::addDateRangeSQL($q,'c.cmonth');
      if($offset > 0){
	$q->offset($offset * CatchByMonthTable::FISH_RECORD_LIMIT);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      foreach($rows as $i => $row){
	$fish_id = intval($row['c_fish_id']);
	$recs[$i]['id'] = $fish_id;
	$recs[$i]['name'] = $row['f_name_common'];
	$recs[$i]['name_sci'] = $row['f_name_sci'];
	$recs[$i]['rate'] = $row['f_rate'];
	$recs[$i]['count'] = $row['c_count_fish'];
	$recs[$i]['avg_weight'] = ($row['c_avg_weight'] ? $row['c_avg_weight'] : '');
	$recs[$i]['avg_length'] = ($row['c_avg_length'] ? $row['c_avg_length'] : '');
	$recs[$i]['catch_annual'] = $this->getCatchByFishAnnualBB($fish_id,$geo);
	$recs[$i]['catch_top_sites'] = $this->getTopSitesByFishBB($fish_id,$geo);
      }
      $ret['count'] = count($recs);
      $ret['records'] = $recs;
    }
    return $ret;
  }
  
  public function getCatchByFishId($fish_id,$month_range){
    $q = Doctrine_Query::create()
      ->select('c.int_site_id,s.lat,s.lon')
      ->from('CatchByMonth c')
      ->innerJoin('c.Site s')
      ->where('c.fish_id = ?', $fish_id)
      ->andWhere('c.cmonth >= ? AND c.cmonth <= ?', $month_range)
      ->groupBy('c.int_site_id')
      ->limit(10000);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $ret = array();
    foreach($rows as $i => $row){
      $catch = array();
      $catch[] = intval($row['c_int_site_id']);
      $catch[] = floatval(number_format($row['s_lat'],4));
      $catch[] = floatval(number_format($row['s_lon'],4));
      $ret[] = $catch;
    }
    return $ret;
  }

    public function catchBoundsByFishId($fish_id){
      $q = Doctrine_Query::create()
	->select('MAX(s.lat) AS lat_max,MIN(s.lat) AS lat_min,MAX(s.lon) AS lon_max,MIN(s.lon) AS lon_min,AVG(s.lat) AS lat,AVG(s.lon) AS lon')
	->from('CatchByMonth c')
	->innerJoin('c.Site s')
	->where('c.fish_id = ?', $fish_id);
      fbLib::addDateRangeSQL($q,'c.cmonth');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $row = $rows[0];
      if($row){
	$rec = array();
	$rec['sw_lat'] = floatval($row['s_lat_min']);
	$rec['sw_lon'] = floatval($row['s_lon_min']);
	$rec['ne_lat'] = floatval($row['s_lat_max']);
	$rec['ne_lon'] = floatval($row['s_lon_max']);
	$rec['lat'] = floatval($row['s_lat']);
	$rec['lon'] = floatval($row['s_lon']);
	return $rec;
      }
      return False;
    }
    
    public function getCatchByFishTopSites($fish_id,$month_range){
      $q = Doctrine_Query::create()
	->select('s.id,s.name,s.city,s.state,s.county,s.lat,s.lon,c.fish_id,SUM(c.count_fish) AS count_fish,FORMAT(AVG(c.avg_length),1) AS avg_length,FORMAT(AVG(c.avg_weight),1) AS avg_weight')
	->from('CatchByMonth c')
	->innerJoin('c.FishSpecies f')
	->innerJoin('c.Site s')
	->where('c.fish_id = ?', $fish_id)
	->andWhere('c.cmonth >= ? AND c.cmonth <= ?', $month_range)
	->groupBy('c.int_site_id')
	->orderBy('SUM(count_fish) DESC')
	->limit(20);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $json = array();
      foreach($rows as $i => $row){
	$json[$i]['site_id'] = intval($row['s_id']);
	$json[$i]['name'] = $row['s_name'];
	$json[$i]['city'] = $row['s_city'];
	$json[$i]['state'] = $row['s_state'];
	$json[$i]['count'] = intval($row['c_count_fish']);
      }
      return $json;
    }
    
    public function getCatchByFishIdBB($fish_id,$geo,$month_range){
      $q = Doctrine_Query::create()
	->select('c.fish_id,SUM(c.count_fish) AS count_fish,FORMAT(AVG(c.avg_length),1) AS avg_length,FORMAT(AVG(c.avg_weight),1) AS avg_weight,f.name_common AS name,f.name_sci AS name_sci')
	->from('CatchByMonth c')
	->innerJoin('c.FishSpecies f')
	->innerJoin('c.Site s')
	->where('c.fish_id = ?', $fish_id)
	->andWhere('s.lat > ? AND s.lat < ?', array($geo['sw_lat'],$geo['ne_lat']))
	->andWhere('s.lon > ? AND s.lon < ?', array($geo['sw_lon'],$geo['ne_lon']))
	->groupBy('c.fish_id');
      #fbLib::addDateRangeSQL($q,'c.cmonth');
      $json = array();
      if($rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR)){
	if($row = $rows[0]){
	  $json['fish_id'] = intval($row['c_fish_id']);
	  $json['name'] = $row['f_name'];
	  $json['name_sci'] = $row['f_name_sci'];
	  $json['count'] = intval($row['c_count_fish']);
	  $json['avg_weight'] = ($row['c_avg_weight'] ? $row['c_avg_weight'] : '');
	  $json['avg_length'] = ($row['c_avg_length'] ? $row['c_avg_length'] : '');
	}
      }
      return $json;
    }
    
  /* given a list of catches build a hash (key by fish_id) of lists of fish catches by month */
    private function formatAnnualCatch($fish_id,$recs){
      if(count($recs) == 0){ return array(); }
      $list = array();
      $name = NULL;
      # convert from DB indexing (1-12) to JS indexing (0-11)
      for($j=0; $j<12; $j++){
	$db_index = $j + 1;
	$list[$j] = 0;
	foreach ($recs as $rec){
	  if(! $name){ $name = $rec['name']; }
	  if((int)$rec['month'] == $db_index){
	    $list[$j] = $rec['count'];
	    break;
	  }
	}
      }
      return array('fish_id' => $fish_id, 'name' => $name, 'month_list' => $list);
    }

    ## THESE QUERIES ARE COMING FROM OUTSIDE SOURCES
    # annual catches for a single fish
    public function getCatchByFishAnnual($fish_id){
      $recs = Doctrine_Core::getTable('CatchByFishMonth')->catchByFishAnnual($fish_id);
      $results = $this->formatAnnualCatch($fish_id,$recs);
      if(count($results) > 0){
	return array($results);
      }else{
	return array();
      }
    }
    # DONE OUTSIDE SOURCES
    # annual catches for all fish
    public function catchByFishesAnnualBB($geo,$fish_ids){
      $fish_array = array();
      for($i=0; $i<count($fish_ids); $i++){
	$fish_id = $fish_ids[$i];
	if(empty($fish_id)){ break; }
	$recs = $this->getCatchAnnualBB($fish_id,$geo);
	if(count($recs) > 0){
	    $fish_array[] = $this->formatAnnualCatch($fish_id,$recs);
	}
      }
      return $fish_array;
    }

    # annual catches for a single fish by bounds
    public function getCatchByFishAnnualBB($fish_id,$geo){
      $recs = $this->getCatchAnnualBB($fish_id,$geo);
      $results = $this->formatAnnualCatch($fish_id,$recs);
      if(count($results) > 0){
	return array($results);
      }else{
	return array();
      }
    }
    # annual catches for multiple fish by site
    public function catchAnnual($fish_ids,$site_ids){
      if( empty($site_ids) || empty($fish_ids) ){
	return array();
      }
      $fish_array = array();
      $count = count($fish_ids);
      $limit = ($count < CatchByMonthTable::MAX_SELECT_FISH ? $count : CatchByMonthTable::MAX_SELECT_FISH);
      $i = 0;
      for($i=0; $i<$limit; $i++){
	$fish_id = $fish_ids[$i];
	if(empty($fish_id)){ break; }
	$recs = $this->getCatchAnnual($fish_id,$site_ids);
	if(count($recs) > 0){
	  $fish_array[] = $this->formatAnnualCatch($fish_id,$recs);
	}
      }
      return $fish_array;
    }
    
    public function getCatchAnnual($fish_id,$site_ids){
      $q = Doctrine_Query::create()
	->select('c.cmonth AS month,SUM(c.count_fish) AS count_fish,c.fish_id AS fish_id,f.name_common AS name')
	->from('CatchByMonth c')
	->innerJoin('c.FishSpecies f')
	->innerJoin('c.Site s')
	->where('c.fish_id = ?', $fish_id)
	->andWhereIn('c.int_site_id', $site_ids)
	->groupBy('c.fish_id')
	->addGroupBy('c.cmonth')
	->orderBy('f.name_common,c.cmonth')
	->limit(20);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $json = array();
      foreach($rows as $i => $row){
	$json[$i]['fish_id'] = intval($row['c_fish_id']);
	$json[$i]['name'] = $row['f_name'];
	$json[$i]['month'] = $row['c_month'];
	$json[$i]['count'] = intval($row['c_count_fish']);
      }
      return $json;
    }
    
    public function getCatchAnnualBB($fish_id,$geo){
      $q = Doctrine_Query::create()
	->select('c.cmonth AS month,c.fish_id,SUM(c.count_fish) AS count_fish,f.name_common AS name')
	->from('CatchByMonth c')
	->innerJoin('c.FishSpecies f')
	->innerJoin('c.Site s')
	->where('c.fish_id = ?', $fish_id)
	->andWhere('s.lat > ? AND s.lat < ?', array($geo['sw_lat'],$geo['ne_lat']))
	->andWhere('s.lon > ? AND s.lon < ?', array($geo['sw_lon'],$geo['ne_lon']))
	->groupBy('c.fish_id')
	->addGroupBy('c.cmonth')
	->orderBy('c.fish_id,c.cmonth')
	->limit(12); 
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $json = array();
      foreach($rows as $i => $row){
	$json[$i]['fish_id'] = intval($row['c_fish_id']);
	$json[$i]['name'] = $row['f_name'];
	$json[$i]['month'] = $row['c_month'];
	$json[$i]['count'] = intval($row['c_count_fish']);
      }
      return $json;
    }

    public function getCatchBySiteAndFish($site_id,$fish_id,$month_range){
      $q = Doctrine_Query::create()
	->select('s.id,s.name,s.city,s.state,s.county,s.lat,s.lon,c.fish_id,f.name_common AS name,SUM(c.count_fish) AS count_fish,FORMAT(AVG(c.avg_length),1) AS avg_length,FORMAT(AVG(c.avg_weight),1) AS avg_weight')
	->from('CatchByMonth c')
	->innerJoin('c.FishSpecies f')
	->innerJoin('c.Site s')
	->where('c.fish_id = ?', $fish_id)
	->andWhere('c.int_site_id = ?', $site_id)
	->andWhere('c.cmonth >= ? AND c.cmonth <= ?', $month_range)
	->groupBy('c.int_site_id')
	->addGroupBy('c.fish_id')
	->limit(1);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $row = $rows[0];
      $json = array();
      $json['fish_id'] = intval($row['c_fish_id']);
      $json['fish_name'] = $row['f_name'];
      $json['count'] = intval($row['c_count_fish']);
      #$json['count_s2'] = 0;
      $json['avg_weight'] = intval($row['c_avg_weight'] ? $row['c_avg_weight'] : '');
      $json['avg_length'] = intval($row['c_avg_length'] ? $row['c_avg_length'] : '');
      $json['site_id'] = intval($row['s_id']);
      $json['name'] = $row['s_name'];
      $json['city'] = $row['s_city'];
      $json['state'] = $row['s_state'];
      $json['county'] = $row['s_county'];
      $json['lat'] = floatval(number_format($row['s_lat'],5));
      $json['lon'] = floatval(number_format($row['s_lon'],5));
      return $json;
    }

    ## get Sites By Bounds and By Fish
    public function getSitesByFishBB($fish_id,$geo,$month_range){
      $q = Doctrine_Query::create()
	->select('c.int_site_id,s.lat,s.lon')
	->from('CatchByMonth c')
	->innerJoin('c.Site s')
	->where('s.lat > ? AND s.lat < ?', array($geo['sw_lat'],$geo['ne_lat']))
	->andWhere('s.lon > ? AND s.lon < ?', array($geo['sw_lon'],$geo['ne_lon']))
	->andWhere('c.fish_id = ?', $fish_id)
	->andWhere('c.cmonth >= ? AND c.cmonth <= ?', $month_range)
	->groupBy('c.int_site_id')
	->limit(20000);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $json = array();
      foreach($rows as $i => $row){
	$catch = array();
	$catch[] = intval($row['c_int_site_id']);
	$catch[] = floatval(number_format($row['s_lat'],4));
	$catch[] = floatval(number_format($row['s_lon'],4));
	$json[] = $catch;
      }
      return $json;
    }
    
    ## get Sites By Bounds and By Fish
    public function sitesByFishBB($fish_id,$geo,$month_range){
      $q = Doctrine_Query::create()
	->select('s.spot_id,c.int_site_id,s.lat,s.lon')
	->from('CatchByMonth c')
	->innerJoin('c.Site s')
	->where('s.lat > ? AND s.lat < ?', array($geo['sw_lat'],$geo['ne_lat']))
	->andWhere('s.lon > ? AND s.lon < ?', array($geo['sw_lon'],$geo['ne_lon']))
	->andWhere('c.fish_id = ?', $fish_id)
	->andWhere('c.cmonth >= ? AND c.cmonth <= ?', $month_range)
	->groupBy('c.int_site_id')
	->limit(20000);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $recs = array();
      foreach($rows as $i => $row){
	$recs[$i]['id'] = intval($row['c_int_site_id']);
	$recs[$i]['lat'] = floatval(number_format($row['s_lat'],4));
	$recs[$i]['lon'] = floatval(number_format($row['s_lon'],4));
	$recs[$i]['spot_id'] = intval($row['s_spot_id']);
      }
      return $recs;
    }
    
    ## get Sites By Bounds
    public function getSitesBB($geo,$month_range){
      $q = Doctrine_Query::create()
	->select('s.spot_id,c.int_site_id,s.lat,s.lon')
	->from('CatchByMonth c')
	->innerJoin('c.Site s')
	->where('s.lat > ? AND s.lat < ?', array($geo['sw_lat'],$geo['ne_lat']))
	->andWhere('s.lon > ? AND s.lon < ?', array($geo['sw_lon'],$geo['ne_lon']))
	->andWhere('c.cmonth >= ? AND c.cmonth <= ?', $month_range)
	->groupBy('c.int_site_id')
	->limit(500);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $recs = array();
      foreach($rows as $i => $row){
	$recs[$i]['id'] = intval($row['c_int_site_id']);
	$recs[$i]['lat'] = floatval(number_format($row['s_lat'],4));
	$recs[$i]['lon'] = floatval(number_format($row['s_lon'],4));
	$recs[$i]['spot_id'] = intval($row['s_spot_id']);
      }
      return $recs;
  }

  ## get TOP Sites By Bounds and By Fish
    public function getTopSitesByFishBB($fish_id,$geo){
      $q = Doctrine_Query::create()
	->select('s.id,s.spot_id,s.name,s.city,s.state,s.county,c.fish_id,SUM(c.count_fish) AS count_fish,FORMAT(AVG(c.avg_length),1) AS avg_length,FORMAT(AVG(c.avg_weight),1) AS avg_weight')
	->from('CatchByMonth c')
	->innerJoin('c.Site s')
	->where('s.lat > ? AND s.lat < ?', array($geo['sw_lat'],$geo['ne_lat']))
	->andWhere('s.lon > ? AND s.lon < ?', array($geo['sw_lon'],$geo['ne_lon']))
	->andWhere('c.fish_id = ?', $fish_id)
	->groupBy('c.int_site_id')
	->orderBy('SUM(count_fish) DESC')
	->limit(20);
      #fbLib::addDateRangeSQL($q,'c.cmonth');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $json = array();
      foreach($rows as $i => $row){
	$json[$i]['site_id'] = intval($row['s_id']);
	$json[$i]['spot_id'] = intval($row['s_spot_id']);
	$json[$i]['name'] = $row['s_name'];
	$json[$i]['city'] = $row['s_city'];
	$json[$i]['state'] = $row['s_state'];
	$json[$i]['count'] = intval($row['c_count_fish']);
      }
      return $json;
    }
 
    ## get TOP Cities By Bounds and By Fish
    public function getTopCitiesByFishBB($fish_id,$geo){
      $q = Doctrine_Query::create()
	->select('s.city,s.state,s.county,c.fish_id,SUM(c.count_fish) AS count_fish,FORMAT(AVG(c.avg_length),1) AS avg_length,FORMAT(AVG(c.avg_weight),1) AS avg_weight')
	->from('CatchByMonth c')
	->innerJoin('c.Site s')
	->where('s.lat > ? AND s.lat < ?', array($geo['sw_lat'],$geo['ne_lat']))
	->andWhere('s.lon > ? AND s.lon < ?', array($geo['sw_lon'],$geo['ne_lon']))
	->andWhere('c.fish_id = ?', $fish_id)
	->groupBy('s.state')
	->addGroupBy('s.city')
	->orderBy('SUM(count_fish) DESC')
	->limit(20);
      #fbLib::addDateRangeSQL($q,'c.cmonth');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $json = array();
      foreach($rows as $i => $row){
	$json[$i]['city'] = $row['s_city'];
	$json[$i]['county'] = $row['s_county'];
	$json[$i]['state'] = $row['s_state'];
	$json[$i]['count'] = intval($row['c_count_fish']);
      }
      return $json;
    }
 
    public function fishDetail($fish_id){
      $q = Doctrine_Query::create()
	->select('c.fish_id,f.name_common,f.name_sci,f.wiki_title,SUM(c.count_fish) AS count_fish,FORMAT(AVG(c.avg_length),1) AS avg_length,FORMAT(AVG(c.avg_weight),1) AS avg_weight')
	->from('CatchByMonth c')
	->innerJoin('c.FishSpecies f')
	->where('c.fish_id = ?',$fish_id)
	->groupBy('c.fish_id');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      if($rows and ($row = $rows[0]) ){
	$fish_id = intval($row['c_fish_id']);
	$row['id'] = $fish_id;
	$row['name'] = $row['f_name_common'];
	$row['name_sci'] = $row['f_name_sci'];
	$row['wiki_title'] = $row['f_wiki_title'];
	$row['count'] = $row['c_count_fish'];
	$row['avg_weight'] = ($row['c_avg_weight'] ? $row['c_avg_weight'] : '');
	$row['avg_length'] = ($row['c_avg_length'] ? $row['c_avg_length'] : '');
	return $row;
      }
      return False;
    }
   
}