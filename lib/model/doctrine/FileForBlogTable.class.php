<?php

  /** FileForBlogTable **/
class FileForBlogTable extends Doctrine_Table{

    public static function getInstance(){
        return Doctrine_Core::getTable('FileForBlog');
    }
    public function addFile($p){
      $file = new FileForBlog();
      $file->setPid($p['pid']);
      $file->setFileId($p['id']);
      $file->setStatus(1);
      $file->save();
      return $file;
    }

}