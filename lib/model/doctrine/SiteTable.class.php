<?php


class SiteTable extends Doctrine_Table{
  
  public static function getInstance(){
        return Doctrine_Core::getTable('Site');
  }

  # given a lat/lon, compute bounds and return sites within
  public function getSites($lat=26.7488889,$lon=-82.2619444, $limit=20){
    $dist = 30;
    $geo = fbLib::boundsFromPoint($lat,$lon,$dist);
    return $this->fetchSites($geo,$limit);
  }

  ## get Sites By Bounds (southWest lat/lon and NorthEast lat/lon)
  ## passed in from client
  public function getSitesBB($geo,$limit=20){
    return $this->fetchSites($geo,$limit);
  }

  private function fetchSites($geo,$limit=20){
    if(is_null($limit)){ $limit = 20; }
    $q = Doctrine_Query::create()
      ->select('s.id,s.name,s.city,s.state,s.county,s.lat,s.lon')
      ->from('Site s')
      ->where('s.lat > ? AND s.lat < ?', array($geo['sw_lat'],$geo['ne_lat']))
      ->andWhere('s.lon > ? AND s.lon < ?', array($geo['sw_lon'],$geo['ne_lon']))
      ->groupBy('s.id')
      ->limit($limit);
    if($geo['lat'] and $geo['lon']){
      $q->addSelect('(3956 * 2 * ASIN(SQRT( POWER(SIN((' . $geo['lat'] . ' - s.lat)* pi()/180 / 2), 2) +COS(' . $geo['lat'] . ' * pi()/180) * COS(s.lat * pi()/180) *POWER(SIN((' . $geo['lon'] . ' -s.lon) * pi()/180 / 2), 2) ))) AS distance');
      $q->orderBy('distance');
    }
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $json = array();
    foreach($rows as $i => $row){
      $json[$i]['id'] = intval($row['s_id']);
      $json[$i]['name'] = $row['s_name'];
      $json[$i]['city'] = $row['s_city'];
      $json[$i]['state'] = $row['s_state'];
      $json[$i]['county'] = $row['s_county'];
      $json[$i]['lat'] = doubleval($row['s_lat']);
      $json[$i]['lon'] = doubleval($row['s_lon']);
      $json[$i]['distance'] = floatval(number_format($row['s_distance'],1));
    }
    return $json;
  }

  public function getSiteByID($site_id){
    $q = Doctrine_Query::create()
      ->select('s.id,s.name,s.city,s.state,s.county,s.lat,s.lon')
      ->from('Site s')
      ->where('s.id = ?',$site_id);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $json = array();
    foreach($rows as $i => $row){
      $json['id'] = intval($row['s_id']);
      $json['name'] = $row['s_name'];
      $json['city'] = $row['s_city'];
      $json['state'] = $row['s_state'];
      $json['county'] = $row['s_county'];
      $json['lat'] = doubleval($row['s_lat']);
      $json['lon'] = doubleval($row['s_lon']);
    }
    return $json;
  }

}