<?php


class FishSpeciesTable extends Doctrine_Table
{
    
  public static function getInstance(){
    return Doctrine_Core::getTable('FishSpecies');
  }

  # find one fish by name
  public function fishByName($name){
    $q = Doctrine_Query::create()
      ->select('f.name_common,f.id,fn.name')
      ->from('FishSpecies f')
      ->innerJoin('f.FishNames fn')
      ->where('fn.name = ?', $name)
      ->limit(1);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $row = $rows[0];
    $ret = array();
    $ret['name'] = $row['f_name_common'];
    $ret['label'] = $row['fn_name'];
    $ret['id'] = $row['f_id'];
    return $ret;
  }
  
  # find all fish by substr
  public function fishByNameLike($substr){
    $q = Doctrine_Query::create()
      ->select('f.name_common,f.id,fn.name')
      ->from('FishSpecies f')
      ->innerJoin('f.FishNames fn')
      ->where('fn.name LIKE ?', '%' . $substr . '%')
      ->orderBy('fn.name')
      ->limit(100);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $ret = array();
    foreach($rows as $i => $row){
      $ret[$i]['name'] = $row['f_name_common'];
      $ret[$i]['label'] = $row['fn_name'];
      $ret[$i]['id'] = $row['f_id'];
    }
    return $ret;
  }

  public function fishById($fish_id){
    $q = Doctrine_Query::create()
      ->select('f.name_common,f.id')
      ->from('FishSpecies f')
      ->where('f.id = ?', $fish_id);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $ret = array();
    foreach($rows as $i => $row){
      $ret[$i]['name'] = $row['f_name_common'];
      $ret[$i]['id'] = $row['f_id'];
    }
    return $ret;
  }

  public function fishFind($name){
    $q = Doctrine_Query::create()
      ->select('f.name_common AS name,f.id AS fish_id,fn.name AS aname')
      ->from('FishSpecies f')
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