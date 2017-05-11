<?php

/**
 * FileForSpotTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class FileForSpotTable extends Doctrine_Table{

  public static function getInstance(){
    return Doctrine_Core::getTable('FileForSpot');
  }
  
  public function addFile($p){
    $file = new FileForSpot();
    $file->setPid($p['pid']);
    $file->setFileId($p['id']);
    $file->setStatus(1);
    $file->save();
    return $file;
  }

}