<?php


class LocGoodTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('LocGood');
    }

    public function findGood($loc){
      $q = Doctrine_Query::create()
	->select('id,input,loc,city,state,zip,county,country,lat,lon,sw_lat,sw_lon,ne_lat,ne_lon,date_create')
	->from('LocGood l')
	->where('l.input = ?', $loc)
	->limit(10);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      $rec = array( 'id' => NULL);
      if(count($rows) > 0){
	$row = $rows[0];
	$rec['id'] = intval($row['l_id']);
	$rec['input'] = $row['l_input'];
	$rec['loc'] = $row['l_loc'];
	$rec['city'] = $row['l_city'];
	$rec['state'] = $row['l_state'];
	$rec['zip'] = $row['l_zip'];
	$rec['county'] = $row['l_county'];
	$rec['loc'] = $row['l_loc'];
	$rec['lat'] = $row['l_lat'];
	$rec['lon'] = $row['l_lon'];
	$rec['sw_lat'] = $row['l_sw_lat'];
	$rec['sw_lon'] = $row['l_sw_lon'];
	$rec['ne_lat'] = $row['l_ne_lat'];
	$rec['ne_lon'] = $row['l_ne_lon'];
	$rec['date_create'] = $row['l_date_create'];
      }
      return $rec;
    }

    public function saveGood($geo){
      $good = new LocGood();
      $good->setInput($geo['input']);
      $good->setLoc($geo['loc']);
      if(isset($geo['city'])){ $good->setCity($geo['city']); }
      $good->setState($geo['state']);
      $good->setStateFull($geo['state_full']);
      if(isset($geo['zip'])){ $good->setZip($geo['zip']); }
      $good->setCounty($geo['county']);
      $good->setCountry($geo['country']);
      $good->setCountryFull($geo['country_full']);
      $good->setLat($geo['lat']);
      $good->setLon($geo['lon']);
      $good->setSwLat($geo['sw_lat']);
      $good->setSwLon($geo['sw_lon']);
      $good->setNeLat($geo['ne_lat']);
      $good->setNeLon($geo['ne_lon']);
      $good->setResultCount($geo['count']);
      $good->setDateCreate(new Doctrine_Expression('NOW()'));
      $good->save();
    }

}
