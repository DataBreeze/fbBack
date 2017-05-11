<?php

class fbPhotoFile extends fbFile{
  public $parentTableName = false;
  public $parentKey = 0;
  public $recordLimit = 10;
  public $fileTableName = False;
  public $dateCol = 'date_create';
  public $type = 'Photo';
  public $fileType = 1;

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
    return True;
  }

  public function deleteParentFile($p){
    return True;
  }

  public function getPhoto($file_id){
    return $this->getPhoto2($file_id);
  }


}
