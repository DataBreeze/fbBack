<?php

class fbUserFile extends fbFile{
  public $parentTableName = false;
  public $parentKey = 0;
  public $recordLimit = 1;
  public $fileTableName = 'User';
  public $dateCol = 'date_create';
  public $type = 'User';
  public $fileType = 20;
  
  public function validateParent(){
    return True;
  }
  
  public function validateCreate(){
    if($this->validateUser()){
      return True;
    }
    return False;
  }
  
  public function createParent(){
    $p = array( 'photo_id' => $this->param['id'],'user_id' => $this->user['id'] );
    if(Doctrine_Core::getTable('User')->updateUserPhoto($p)){
      return True;
    }
    return False;
  }
  
  public function getPhoto($file_id){
    return $this->getPhoto2($file_id);
  }
  
  }