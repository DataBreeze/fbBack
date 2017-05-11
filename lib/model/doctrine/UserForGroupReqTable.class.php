<?php
class UserForGroupReqTable extends Doctrine_Table{

  public static function getInstance(){
    return Doctrine_Core::getTable('UserForGroupReq');
  }

  public function add($p){
    $req = $this->get($p['group_id'],$p['user_id']);
    if($req){
      return False;
    }else{
      $req = new UserForGroupReq();
      $req->setUserId($p['user_id']);
      $req->setGroupId($p['group_id']);
      $req->setNote($p['note']);
      $req->save();
      return True;
    }
  }
  
  public function get($group_id,$user_id){
    $q = Doctrine_Query::create()
      ->select('group_id,user_id,note,ts')
      ->from('UserForGroupReq ufg')
      ->where('ufg.user_id = ?', $user_id)
      ->andWhere('ufg.group_id = ?', $group_id);
    $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
    if($rows and (count($rows) > 0) ){
      return $rows[0];
    }
    return False;
  }

  public function delete($group_id,$user_id){
    if($user_id and $group_id){
      return Doctrine_Query::create()
	->delete()
	->from('UserForGroupReq ufg')
	->where('ufg.user_id = ?', $user_id)
	->andWhere('ufg.group_id = ?', $group_id)
	->execute();
      return True;
    }
    return False;
  }


}