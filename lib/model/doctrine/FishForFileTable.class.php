<?php

class FishForFileTable extends Doctrine_Table{

  public static function getInstance(){
    return Doctrine_Core::getTable('FishForFile');
  }

  public function addFish($p){
    $fish = new FishForFile();
    $fish->setPid($p['pid']);
    $fish->setFishId($p['fish_id']);
    $fish->save();
    return $fish;
  }

}