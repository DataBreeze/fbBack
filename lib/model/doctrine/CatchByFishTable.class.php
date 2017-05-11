<?php

/**
 * CatchByFishTable
 */
class CatchByFishTable extends Doctrine_Table{
  
  public static function getInstance(){
    return Doctrine_Core::getTable('CatchByFish');
  }

  public function fishAllCount(){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS fish_count')
      ->from('CatchByFish f');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['f_fish_count']);
  }
  
  public function fishAll($p){
    if( ! $p['limit']){
      $p['limit'] = 100;
    }
    if( ! $p['offset']){
      $p['offset'] = 0;
    }
    $count = $this->fishAllCount();
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => intval($p['offset']),
		 'record_limit' => $limit);
    if($count){
      $q = Doctrine_Query::create()
	->select('f.count_fish,f.fish_id,f.name,f.name_sci,f.rate,f.count_fish,f.avg_weight,f.avg_length')
	->from('CatchByFish f')
	->orderBy('count_fish DESC');
      if($p['offset'] > 0){
	$q->limit( (1 + $p['offset']) * $p['limit']);
      }else{
	$q->limit($p['limit']);
      }
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      foreach($rows as $i => $row){
	$recs[$i]['fish_id'] = intval($row['f_fish_id']);
	$recs[$i]['name'] = $row['f_name'];
	$recs[$i]['name_sci'] = $row['f_name_sci'];
	$recs[$i]['count'] = intval($row['f_count_fish']);
	$recs[$i]['rate'] = intval($row['f_rate']);
	$recs[$i]['class'] = 'tc5';
      }
      $ret['count'] = count($recs);
      $ret['records'] = $recs;
    }
    return $ret;
  }
  
  public function allFish($limit = 50){
    $q = Doctrine_Query::create()
      ->select('f.count_fish,f.fish_id,f.name,f.name_sci,f.rate,f.count_fish,f.avg_weight,f.avg_length')
      ->from('CatchByFish f')
      ->orderBy('count_fish DESC')
      ->limit($limit);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $ret = array();
    foreach($rows as $i => $row){
      $ret[$i]['fish_id'] = intval($row['f_fish_id']);
      $ret[$i]['name'] = $row['f_name'];
      $ret[$i]['name_sci'] = $row['f_name_sci'];
      $ret[$i]['count'] = intval($row['f_count_fish']);
      $ret[$i]['rate'] = intval($row['f_rate']);
      $ret[$i]['class'] = 'tc5';
    }
    return $ret;
  }    


  # find one fish by name
  public function fishByName($name){
    $q = Doctrine_Query::create()
      ->select('f.name,f.fish_id,fn.name')
      ->from('CatchByFish f')
      ->innerJoin('f.FishNames fn')
      ->where('fn.name = ?', $name)
      ->limit(1);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $row = $rows[0];
    $ret = array();
    $ret['name'] = $row['f_name'];
    $ret['label'] = $row['fn_name'];
    $ret['id'] = intval($row['f_fish_id']);
    return $ret;
  }
  
  # find all fish by substr
  public function fishByNameLike($substr){
    $q = Doctrine_Query::create()
      ->select('f.name,f.fish_id,fn.name')
      ->from('CatchByFish f')
      ->innerJoin('f.FishNames fn')
      ->where('fn.name LIKE ?', '%' . $substr . '%')
      ->orderBy('fn.name')
      ->limit(100);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $ret = array();
    foreach($rows as $i => $row){
      $ret[$i]['name'] = $row['f_name'];
      $ret[$i]['label'] = $row['fn_name'];
      $ret[$i]['id'] = $row['f_fish_id'];
    }
    return $ret;
  }

  public function fishById($fish_id){
    $q = Doctrine_Query::create()
      ->select('f.name,f.fish_id')
      ->from('CatchByFish f')
      ->where('f.fish_id = ?', $fish_id);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $row = $rows[0];
    if($row){
      $ret = array();
      $ret['name'] = $row['f_name'];
      $ret['id'] = intval($row['f_fish_id']);
      $ret['fish_id'] = intval($row['f_fish_id']);
      return $ret;
    }
    return False;
  }

  public function fishFind($name){
    $q = Doctrine_Query::create()
      ->select('f.name AS name,f.fish_id,fn.name AS aname')
      ->from('CatchByFish f')
      ->innerJoin('f.FishNames fn')
      ->where('fn.name = ?', $name)
      ->limit(1);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    if(count($rows) > 0){
      if($row = $rows[0]){
	$ret = array();
	$ret['name'] = $row['f_name'];
	$ret['label'] = $row['fn_aname'];
	$ret['id'] = $row['f_fish_id'];
	return $ret;
      }
    }
    return False;
  }
 
}