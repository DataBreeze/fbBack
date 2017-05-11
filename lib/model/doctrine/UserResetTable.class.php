<?php

/** UserResetTable **/
class UserResetTable extends Doctrine_Table {
  public static function getInstance() {
    return Doctrine_Core::getTable('UserReset');
  }
  
  public function createReset($user_id){
    $reset = new UserReset;
    $reset->setDateCreate(new Doctrine_Expression('NOW()'));
    $reset_code = fbLib::randomHash(70);
    $reset->setResetCode($reset_code);
    $reset->setUserId($user_id);
    $reset->setStatus(1);
    $reset->save();
    $rid = $reset->getId();
    $new_reset = $this->getResetById($rid);
    return $new_reset;    
  }

  public function updateResetStatus($reset_id,$new_status){
    if($reset = $this->find($reset_id)){
      $reset->setStatus($new_status);
      $reset->save();
    }
    $new_reset = $this->getResetById($reset_id);
    return $new_reset;
  }

  public function getResetByCode($reset_code){
    $q = Doctrine_Query::create()
      ->select('r.id,r.reset_code,r.date_create,r.user_id,r.status')
      ->from('UserReset r')
      ->where('r.reset_code = ?', $reset_code);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    if(count($rows) > 0 ){
      $rec = array();
      $rec['id'] = $rows[0]['r_id'];
      $rec['reset_code'] = $rows[0]['r_reset_code'];
      $rec['dc'] = $rows[0]['r_date_create'];
      $rec['user_id'] = $rows[0]['r_user_id'];
      $rec['status'] = $rows[0]['r_status'];
      return $rec;
    }else{
      return False;
    }
  }

  public function getResetById($reset_id){
    $q = Doctrine_Query::create()
      ->select('r.id,r.reset_code,r.date_create,r.user_id,r.status')
      ->from('UserReset r')
      ->where('r.id = ?', $reset_id);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    if(count($rows) > 0 ){
      $rec = array();
      $rec['id'] = $rows[0]['r_id'];
      $rec['reset_code'] = $rows[0]['r_reset_code'];
      $rec['dc'] = $rows[0]['r_date_create'];
      $rec['user_id'] = $rows[0]['r_user_id'];
      $rec['status'] = $rows[0]['r_status'];
      return $rec;
    }else{
      return False;
    }
  }

  ## get only resets 24 hours or newer
  public function getCurReset($reset_id,$reset_code){
    $q = Doctrine_Query::create()
      ->select('r.id,r.reset_code,r.date_create,r.user_id,r.status')
      ->from('UserReset r')
      ->where('r.id = ?', $reset_id)
      ->andWhere('r.reset_code = ?', $reset_code)
      ->andWhere('r.date_create > DATE_ADD(NOW(),INTERVAL -24 HOUR)');
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    if(count($rows) > 0 ){
      $rec = array();
      $rec['id'] = $rows[0]['r_id'];
      $rec['reset_code'] = $rows[0]['r_reset_code'];
      $rec['dc'] = $rows[0]['r_date_create'];
      $rec['user_id'] = $rows[0]['r_user_id'];
      $rec['status'] = $rows[0]['r_status'];
      return $rec;
    }else{
      return False;
    }
  }

}