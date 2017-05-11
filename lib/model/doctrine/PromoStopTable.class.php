<?php

/**
 * PromoStopTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PromoStopTable extends Doctrine_Table{

  public static function getInstance(){
    return Doctrine_Core::getTable('PromoStop');
  }
  
  public function addStop($email){
    $stop = new PromoStop;
    $stop->setEmail($email);
    $stop->setDateCreate(new Doctrine_Expression('NOW()'));
    $stop->save();
    return True;
  }
  
  public function getStop($email){
    if($email){
      $q = Doctrine_Query::create()
	->select('email,DATE_FORMAT(date_create,\'%c/%e/%y\') AS date_create')
	->from('PromoStop s')
	->where('s.email = ?', $email);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      if($rows and $rows[0]){
	$rec = array();
	$rec['email'] = $rows[0]['s_email'];
	$rec['date_create'] = $rows[0]['s_date_create'];
	return $rec;
      }
    }
    return False;
  }

  public function getAllCount(){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS stop_count')
      ->from('PromoStop s');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['s_stop_count']);
  }

  public function getAll($limit = 0){
    $count = $this->getAllCount();
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => intval($offset),'record_limit' => $limit);
    $q = Doctrine_Query::create()
      ->select('email,DATE_FORMAT(date_create,\'%c/%e/%y\') AS date_create,detail')
      ->from('PromoStop s')
      ->limit($limit);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    if($rows){
      foreach($rows as $i => $row){
	$recs[$i]['email'] = $rows[0]['s_email'];
	$recs[$i]['date_create'] = $rows[0]['s_date_create'];
	$recs[$i]['detail'] = $rows[0]['s_detail'];
      }
      $ret['records'] = $recs;
      $ret['count'] = count($recs);
    }
    return $ret;
  }

}