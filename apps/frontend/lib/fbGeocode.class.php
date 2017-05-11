<?php

class fbGeocode{

  public function geocodeLoc($loc){
    $bad = Doctrine_Core::getTable('LocBad')->findBad($loc);
    if($bad['id']){
      $bad['status'] = 'fail';
      $bad['input'] = $loc;
      $this->status = 'CACHE BAD';
      $this->out = 'Bad Cache';
      $geo = $bad;
    }else{
      $good = Doctrine_Core::getTable('LocGood')->findGood($loc);
      if($good['id']){
	$this->status = 'CACHE GOOD';
	$this->out = $good['loc'];
	$good['status'] = 'ok';
	$good['input'] = $loc;
	$geo = $good;
      }else{
	$this->status = 'GEOCODE';
	$geo = $this->geocode($loc);
	if($geo['status'] == 'ok'){
	  $this->status .= ' GOOD';
	  $good = Doctrine_Core::getTable('LocGood')->saveGood($geo);	  
	}else{
	  $this->status .= ' BAD';
	  $bad = Doctrine_Core::getTable('LocBad')->saveBad($loc);  
	}
      }
    }
    $geo['zoom'] = 10;
    return $geo;
  }
  
  public function geocode($loc){
    $url = 'http://maps.google.com/maps/api/geocode/json?address=' . urlencode($loc) . '&sensor=false';
    $data = file_get_contents($url);
    $obj = json_decode($data,true);
    $ret = array();
#    $ret['source'] = $data;
    if($obj['status'] == 'OK'){
      $ret['status'] = 'ok';
      $results = $obj['results'];
      $ret['count'] = count($results);
      $ret['input'] = $loc;
      $res = $results[0];
      $ret['loc'] = $res['formatted_address'];
      $parts = $res['address_components'];
      foreach ($parts as $part){
	$types = $part['types'];
	$str_types = implode('-',$types);
	if($str_types == 'street_number'){
	  $ret['address'] = $part['long_name'] . (isset($ret['address']) ?  ' ' . $ret['address'] : '');
	}elseif($str_types == 'route'){
	  $ret['address'] = (isset($ret['address']) ?  $ret['address'] .' ' : '') . $part['long_name'];
	}elseif($str_types == 'locality-political'){
	  $ret['city'] = $part['long_name'];
	}elseif($str_types == 'administrative_area_level_2-political'){
	  $ret['county'] = $part['long_name'];
	}elseif($str_types == 'administrative_area_level_3-political'){
	  if(!$ret['city']){
	    $ret['city'] = $part['long_name'];
	  }
	}elseif($str_types == 'administrative_area_level_1-political'){
	  $ret['state'] = $part['short_name'];
	  $ret['state_full'] = $part['long_name'];
	}elseif($str_types == 'country-political'){
	  $ret['country'] = $part['short_name'];
	  $ret['country_full'] = $part['long_name'];
	}elseif($str_types == 'postal_code'){
	  $ret['zip'] = $part['long_name'];
	}
      }
      $geo = $res['geometry'];
      $ret['lat'] = $geo['location']['lat'];
      $ret['lon'] = $geo['location']['lng'];
      $ret['sw_lat'] = $geo['viewport']['southwest']['lat'];
      $ret['sw_lon'] = $geo['viewport']['southwest']['lng'];
      $ret['ne_lat'] = $geo['viewport']['northeast']['lat'];
      $ret['ne_lon'] = $geo['viewport']['northeast']['lng'];
    }else{
      $ret['status'] = 'fail';
    }
    return $ret;
  }

}
