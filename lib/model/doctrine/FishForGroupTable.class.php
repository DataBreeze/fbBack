<?php

class FishForGroupTable extends Doctrine_Table{

  public static function getInstance(){
    return Doctrine_Core::getTable('FishForGroup');
  }
  
  public function addFish($p){
    $fish = new FishForGroup();
    $fish->setPid($p['pid']);
    $fish->setFishId($p['fish_id']);
    $fish->save();
    return $fish;
  }

}