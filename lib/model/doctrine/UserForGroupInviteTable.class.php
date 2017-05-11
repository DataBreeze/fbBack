<?php
class UserForGroupInviteTable extends Doctrine_Table{

  public static function getInstance(){
    return Doctrine_Core::getTable('UserForGroupInvite');
  }

  public function add($p){
    $inv = $this->get($p['group_id'],$p['user_id']);
    if($inv){
      return False;
    }else{
      $inv = new UserForGroupInvite();
      $inv->setUserId($p['user_id']);
      $inv->setGroupId($p['group_id']);
      $inv->setNote($p['note']);
      $inv->save();
      return True;
    }
  }

  public function get($group_id,$user_id){
    $q = Doctrine_Query::create()
      ->select('group_id,user_id,note,ts')
      ->from('UserForGroupInvite ufg')
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
	->from('UserForGroupInvite ufg')
	->where('ufg.user_id = ?', $user_id)
	->andWhere('ufg.group_id = ?', $group_id)
	->execute();
    }
  }

}