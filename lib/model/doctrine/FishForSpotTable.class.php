<?php

class FishForSpotTable extends Doctrine_Table{
  public static function getInstance(){
    return Doctrine_Core::getTable('FishForSpot');
  }
  
  public function addFish($p){
    $fish = new FishForSpot();
    $fish->setPid($p['pid']);
    $fish->setFishId($p['fish_id']);
    $fish->save();
    return $fish;
  }

  public function editFish($p){
    if($fish = $this->find($p['fish_id']) ){
      $fish = True;
      return $fish;
    }
    return False;
  }
  
}