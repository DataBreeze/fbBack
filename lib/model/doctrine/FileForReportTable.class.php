<?php

/**
 * FileForReportTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class FileForReportTable extends Doctrine_Table{
  public static function getInstance(){
    return Doctrine_Core::getTable('FileForReport');
  }
  public function addFile($p){
    $file = new FileForReport();
    $file->setPid($p['pid']);
    $file->setFileId($p['id']);
    $file->setStatus(1);
    $file->save();
    return $file;
  }

}