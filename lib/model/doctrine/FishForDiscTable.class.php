<?php

class FishForDiscTable extends Doctrine_Table{
  
  public static function getInstance(){
    return Doctrine_Core::getTable('FishForDisc');
  }
  
  public function addFish($p){
    $fish = new FishForDisc();
    $fish->setPid($p['pid']);
    $fish->setFishId($p['fish_id']);
    $fish->save();
    return $fish;
  }

}