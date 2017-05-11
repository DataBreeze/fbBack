<?php
class CityStateZipTable extends Doctrine_Table{

  const RECORD_LIMIT = 100;

  public static function getInstance(){
    return Doctrine_Core::getTable('CityStateZip');
  }

  public function allStates(){
    $q = Doctrine_Query::create()
      ->select('c.state,c.state_full')
      ->from('CityStateZip c')
      ->groupBy('c.state')
      ->orderBy('c.state');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $recs = array();
    foreach($rows as $i => $row){
      $recs[$i]['state'] = $row['c_state'];
      $recs[$i]['state_full'] = $row['c_state_full'];
      $recs[$i]['state_full_esc'] = urlencode($row['c_state_full']);
    }
    return $recs;
  }

  public function allCities($state,$offset = 0){
    $q = Doctrine_Query::create()
      ->select('c.state,c.state_full,c.city,c.zip')
      ->from('CityStateZip c')
      ->groupBy('c.city')
      ->orderBy('c.city')
      ->where('c.state = ?',$state)
      ->limit(CityStateZipTable::RECORD_LIMIT);
    if($offset > 0){
      $q->offset($offset * CityStateZipTable::RECORD_LIMIT);
    }
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $recs = array();
    foreach($rows as $i => $row){
      $recs[$i]['state'] = $row['c_state'];
      $recs[$i]['state_full'] = $row['c_state_full'];
      $recs[$i]['city'] = $row['c_city'];
      $recs[$i]['city_esc'] = urlencode($row['c_city']);
      $recs[$i]['zip'] = $row['c_zip'];
    }
    return $recs;
  }

}