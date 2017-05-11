<?php


class CatchByStateTable extends Doctrine_Table
{
  
  public static function getInstance()  {
    return Doctrine_Core::getTable('CatchByState');
  }
  
  public function allStates($limit = 50){
    $q = Doctrine_Query::create()
      ->select('c.state,c.state_full,c.count_fish,c.avg_weight,c.avg_length')
      ->from('CatchByState c')
      ->orderBy('count_fish DESC')
      ->limit($limit);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $ret = array();
    foreach($rows as $i => $row){
      $ret[$i]['state'] = $row['c_state'];
      $ret[$i]['state_full'] = $row['c_state_full'];
      $ret[$i]['count'] = intval($row['c_count_fish']);
      $ret[$i]['avg_weight'] = intval($row['c_avg_weight']);
      $ret[$i]['avg_length'] = intval($row['c_avg_length']);
      $ret[$i]['class'] = 'tc5';
    }
    return $ret;
  }    
  
  public function state($state){
    $q = Doctrine_Query::create()
      ->select('c.state,c.state_full,c.count_fish,c.avg_weight,c.avg_length')
      ->from('CatchByState c')
      ->where('c.state = ?',$state);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $row = $rows[0];
    $ret = array();
    $ret['state'] = $row['c_state'];
    $ret['state_full'] = $row['c_state_full'];
    $ret['count'] = intval($row['c_count_fish']);
    $ret['avg_weight'] = intval($row['c_avg_weight']);
    $ret['avg_length'] = intval($row['c_avg_length']);
    $ret['class'] = 'tc5';
    return $ret;
  }

  public function stateSelect(){
    $options = array('region' => 'US', 'resolution' => 'provinces', 'keepAspectRatio' => True,
		     'displayMode' => 'region','legend' => 'none','datalessRegionColor' => 'blue',
		     'magnifyingGlass' => array('enable' => True, 'zoomFactor' => 5) );
    $options['colorAxis'] = array('color' => array('blue','gray') );
    $ret = array('type' => 'stateAll','options' => $options,
		 'areaName' => 'State','weightName' => 'Popularity');
    $q = Doctrine_Query::create()
      ->select('s.state,s.state_full,c.count_fish,c.avg_weight,c.avg_length')
      ->from('State s')
      ->leftJoin('s.CatchByState c')
      ->whereNotIn('s.state',array('AS','GU','VI','PR'))
      ->groupBy('s.state')
      ->orderBy('c.count_fish DESC');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $recs = array();
    foreach($rows as $i => $row){
      #$recs[$i] = array($row['s_state_full'], intval( ($i % 3)) );
      $recs[$i] = array($row['s_state_full']);
    }
    ##array_unshift($recs, array('State','Popularity') );
    array_unshift($recs, array('State') );
    $ret['records'] = $recs;
    return $ret;
  }
  
}