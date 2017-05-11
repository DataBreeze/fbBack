<?php
  /**
   * PromoTable
   * 
   * This class has been auto-generated by the Doctrine ORM Framework
   */

class PromoTable extends Doctrine_Table{
  
  public static function getInstance(){
    return Doctrine_Core::getTable('Promo');
  }
  
  public function newPromo($p){
    $promo = new Promo;
    $promo->setCaption($p['caption']);
    $promo->setEmailFrom($p['email_from']);
    $promo->setNameFrom($p['name_from']);
    $promo->setTitleFrom($p['title_from']);
    $promo->setSubject($p['subject']);
    $promo->setUserId($p['user_id']);
    $promo->setDateCreate(new Doctrine_Expression('NOW()'));
    $promo->setLat(floatval($p['lat']));
    $promo->setLon(floatval($p['lon']));
    $promo->save();
    $promo_id = $promo->getId();
    return $this->getRec($promo_id);
  }
  
  public function edit($p){
    if($promo = $this->find($p['id']) ){
      $promo->setCaption($p['caption']);
      $promo->setEmailFrom($p['email_from']);
      $promo->setNameFrom($p['name_from']);
      $promo->setTitleFrom($p['title_from']);
      $promo->setSubject($p['subject']);
      $promo->save();    
      $promo_id = $promo->getId();
      return $this->getRec($promo_id);
    }
    return false;
  }

  public function editGeo($p){
    if($promo = $this->find($p['id']) ){
      $promo->setLat(floatval($p['lat']));
      $promo->setLon(floatval($p['lon']));
      $promo->save();    
      $promo_id = $promo->getId();
      return $this->getRec($promo_id);
    }
    return false;
  }  

  private function sqlSelect(){
    return 'p.id,p.user_id,p.caption,p.email_from,p.name_from,p.title_from,p.subject,p.lat,p.lon,DATE_FORMAT(p.date_create,\'%c/%e/%y\') AS date_create,u.username';
  }
  
  private function sqlRow($row){
    $rec = array();
    $rec['id'] = intval($row['p_id']);
    $rec['user_id'] = intval($row['p_user_id']);
    $rec['caption'] = $row['p_caption'];
    $rec['email_from'] = $row['p_email_from'];
    $rec['name_from'] = $row['p_name_from'];
    $rec['title_from'] = $row['p_title_from'];
    $rec['subject'] = $row['p_subject'];
    $rec['date_create'] = $row['p_date_create'];
    $rec['lat'] = $row['p_lat'];
    $rec['lon'] = $row['p_lon'];
    $rec['username'] = $row['u_username'];
    return $rec;
  }
    
  public function getRec($promo_id){
    $q = Doctrine_Query::create()
      ->select($this->sqlSelect())
      ->from('Promo p')
      ->innerJoin('p.User u')
      ->where('p.id = ?', $promo_id);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    $row = $rows[0];
    if($row){
      $rec = $this->sqlRow($row);
      return $rec;
    }
    return False;
  }

  public function delete($id){
    if($id){
      $q = Doctrine_Query::create()
	->delete()
	->from('Promo p')
	->where('p.id = ?', $id)->execute();
      return True;
    }
    return False;
  }
  
  public function getBBCount(){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS promo_count')
      ->from('Promo p')
      ->innerJoin('p.User u');
    fbLib::addBoundsSQL($q,'p');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['p_promo_count']);
  }

  public function getBB($p){
    $p['limit'] = ($p['limit'] ? $p['limit'] : 100);
    $p['offset'] = ($p['offset'] ? $p['offset'] : 0);
    $count = $this->getBBCount();
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => intval($p['offset']),'record_limit' => $p['limit']);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Promo p')
	->innerJoin('p.User u')
	->limit($p['limit']);
      fbLib::addBoundsSQL($q,'p');
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      if($rows){
	foreach($rows as $i => $row){
	  $recs[$i] = $this->sqlRow($row);
	}
      }
      $ret['records'] = $recs;
      $ret['count'] = count($recs);
    }
    return $ret;
  }

  public function getAllCount(){
    $q = Doctrine_Query::create()
      ->select('COUNT(*) AS promo_count')
      ->from('Promo p')
      ->innerJoin('p.User u');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    return intval($rows[0]['p_promo_count']);
  }

  public function getAll($p){
    $p['limit'] = ($p['limit'] ? $p['limit'] : 100);
    $p['offset'] = ($p['offset'] ? $p['offset'] : 0);
    $count = $this->getAllCount();
    $recs = array();
    $ret = array('count_total' => $count, 'count' => 0, 'records' => $recs,
		 'record_offset' => intval($p['offset']),'record_limit' => $p['limit']);
    if($count){
      $q = Doctrine_Query::create()
	->select($this->sqlSelect())
	->from('Promo p')
	->innerJoin('p.User u')
	->limit($p['limit']);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      if($rows){
	foreach($rows as $i => $row){
	  $recs[$i] = $this->sqlRow($row);
	}
      }
      $ret['records'] = $recs;
      $ret['count'] = count($recs);
    }
    return $ret;
  }

  
}