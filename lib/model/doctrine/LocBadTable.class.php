<?php


class LocBadTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('LocBad');
    }

    public function findBad($loc){
      $q = Doctrine_Query::create()
	->select('id,input,date_create')
	->from('LocBad l')
	->where('l.input = ?', $loc)
	->limit(1);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $rec = array();
      $rec['id'] = NULL;
      if(count($rows) > 0){
	$row = $rows[0];
	$rec['id'] = intval($row['l_id']);
	$rec['input'] = $row['l_input'];
	$rec['date_create'] = $row['l_date_create'];
      }
      return $rec;
    }

    public function saveBad($loc){
      $bad = new LocBad();
      $bad->setInput($loc);
      $bad->setDateCreate(new Doctrine_Expression('NOW()'));
      $bad->save();
    }
}