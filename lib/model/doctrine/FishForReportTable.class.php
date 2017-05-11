<?php

class FishForReportTable extends Doctrine_Table{
  public static function getInstance() {
    return Doctrine_Core::getTable('FishForReport');
  }
  
  public function addFish($p){
    $fish = new FishForReport();
    $fish->setPid($p['pid']);
    $fish->setFishId($p['fish_id']);
    $fish->save();
    return $fish;
  }
  
}