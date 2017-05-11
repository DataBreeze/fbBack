<?php

  /* FileForDiscTable */
class FileForDiscTable extends Doctrine_Table{
  public static function getInstance(){
    return Doctrine_Core::getTable('FileForDisc');
  }
  
  public function addFile($p){
    $file = new FileForDisc();
    $file->setPid($p['pid']);
    $file->setFileId($p['id']);
    $file->setStatus(1);
    $file->save();
    return $file;
  }

}