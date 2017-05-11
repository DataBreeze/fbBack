<?php
class UserForGroupBlockTable extends Doctrine_Table{

  public static function getInstance(){
    return Doctrine_Core::getTable('UserForGroupBlock');
  }
  
  public function add($group_id,$user_id,$note){
    if($user_id and $group_id){
      $block = $this->get($group_id,$user_id);
      if($block){
	return False;
      }else{
	$block = new UserForGroupBlock();
	$block->setGroupId($group_id);
	$block->setUserId($user_id);
	if($note){
	  $block->setNote($note);
	}
	$block->save();
	return True;
      }
    }
    return False;
  }
  
  public function get($group_id,$user_id){
    if($user_id and $group_id){
      $q = Doctrine_Query::create()
	->select('group_id,user_id,note,ts')
	->from('UserForGroupBlock ufg')
	->where('ufg.user_id = ?', $user_id)
	->andWhere('ufg.group_id = ?', $group_id);
      $rows = $q->execute(array(),Doctrine_Core::HYDRATE_SCALAR);
      if($rows and (count($rows) > 0) ){
	return $rows[0];
      }
    }
    return False;
  }

  public function delete($group_id,$user_id){
    if($user_id and $group_id){
      Doctrine_Query::create()
	->delete()
	->from('UserForGroupBlock ufg')
	->where('ufg.user_id = ?', $user_id)
	->andWhere('ufg.group_id = ?', $group_id)
	->execute();
    }
    return False;
  }


}