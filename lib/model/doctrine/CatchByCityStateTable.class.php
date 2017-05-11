<?php

/**
 * CatchByCityStateTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class CatchByCityStateTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object CatchByCityStateTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CatchByCityState');
    }

    public function stateCitySelect($state){
      $options = array('region' => 'US-' . $state, 'resolution' => 'metros', 'keepAspectRatio' => True,
		       'displayMode' => 'region','legend' => 'none');
    $options['colorAxis'] = array('minValue' => 0,'color' => array('blue','gray','green') );
    $ret = array('type' => 'stateAll','options' => $options,
		 'areaName' => 'City','weightName' => 'Popularity');
    $q = Doctrine_Query::create()
      ->select('c.state,c.city,c.count_fish,c.avg_weight,c.avg_length')
      ->from('CatchByCity c')
      ->where('c.state = ?',$state)
      ->groupBy('c.city')
      ->orderBy('c.count_fish DESC');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $recs = array();
    foreach($rows as $i => $row){
      $recs[$i] = array($row['c_city'], intval($row['c_count_fish']+1) );
    }
    array_unshift($recs, array('City','Popularity') );
    $ret['records'] = $recs;
    return $ret;
  }

    public function state($state,$limit = 50){
      $q = Doctrine_Query::create()
	->select('c.state,c.city,c.count_fish,c.avg_weight,c.avg_length')
	->from('CatchByCity c')
	->where('c.state = ?',$state)
	->orderBy('count_fish DESC')
	->limit($limit);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $ret = array();
      foreach($rows as $i => $row){
	$ret[$i]['state'] = $row['c_state'];
	$ret[$i]['city'] = $row['c_city'];
	$ret[$i]['count'] = intval($row['c_count_fish']);
	$ret[$i]['avg_weight'] = intval($row['c_avg_weight']);
	$ret[$i]['avg_length'] = intval($row['c_avg_length']);
	$ret[$i]['class'] = 'tc5';
      }
      return $ret;
    }    

    public function catchByCity($state,$city){
      $q = Doctrine_Query::create()
	->select('c.state,c.city,c.count_fish,c.avg_weight,c.avg_length')
	->from('CatchByCity c')
	->where('c.state = ?',$state)
	->andWhere('c.city = ?',$city);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $row = $rows[0];
      $ret = array();
      $ret['state'] = $row['c_state'];
      $ret['city'] = $row['c_city'];
      $ret['count'] = intval($row['c_count_fish']);
      $ret['avg_weight'] = intval($row['c_avg_weight']);
      $ret['avg_length'] = intval($row['c_avg_length']);
      $ret['class'] = 'tc5';
      return $ret;
    }    
    
}