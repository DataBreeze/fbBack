<?php

/**
 * MetrocodeTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */

class MetrocodeTable extends Doctrine_Table{

  public static function getInstance(){
    return Doctrine_Core::getTable('Metrocode');
  }

  private function sqlSelect(){
    return 'm.id,m.state,m.state_full,m.metroname,m.metrocode';
  }
    
  private function sqlRow($row){
    $rec = array();
    $rec['id'] = intval($row['m_id']);
    $rec['state'] = intval($row['m_state']);
    $rec['state_full'] = intval($row['m_state_full']);
    $rec['name'] = intval($row['m_metrocode']);
    $rec['code'] = $row['m_metrocode'];
    return $rec;
  }
  
  public function getRec($code){
    $q = Doctrine_Query::create()
      ->select($this->sqlSelect())
      ->from('Metrocode m')
      ->where('m.metrocode = ?', $code);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $row = $rows[0];
    if($row){
      $rec = $this->sqlRow($row);
      return $rec;
    }
    return False;
  }
    
  public function allByState($state){
    $recs = array();
    $q = Doctrine_Query::create()
      ->select($this->sqlSelect())
      ->from('Metrocode m')
      ->where('m.state = ?',$state);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    if($rows){
      foreach($rows as $i => $row){
	$recs[$i] = $this->sqlRow($row);
      }
    }
    return $recs;
  }
 
  public function stateCitySelect($state){
    $options = array('region' => 'US-' . $state, 'resolution' => 'metros', 'keepAspectRatio' => True,
		     'displayMode' => 'region','legend' => 'none');
    $options['colorAxis'] = array('minValue' => 0,'color' => array('blue','gray','green') );
    $ret = array('type' => 'stateAll','options' => $options,
		 'areaName' => 'City','weightName' => 'Popularity');
    $q = Doctrine_Query::create()
      ->select($this->sqlSelect())
      ->from('Metrocode m')
      ->where('m.state = ?',$state);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $recs = array();
    foreach($rows as $i => $row){
      $recs[$i] = array($row['m_metrocode'] );
    }
    array_unshift($recs, array('Area') );
    $ret['records'] = $recs;
    return $ret;
  }
 
}