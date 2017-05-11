<?php

class FishForBlogTable extends Doctrine_Table{
  
  public static function getInstance() {
    return Doctrine_Core::getTable('FishForBlog');
  }
  
  public function addFish($p){
    $fish = new FishForBlog();
    $fish->setPid($p['pid']);
    $fish->setFishId($p['fish_id']);
    $fish->save();
    return $fish;
  }

}