<?php

class FishForUserTable extends Doctrine_Table{

    public static function getInstance(){
      return Doctrine_Core::getTable('FishForUser');
    }

    public function addFish($p){
      $fish = new FishForUser();
      $fish->setPid($p['pid']);
      $fish->setFishId($p['fish_id']);
      $fish->save();
      return $fish;
    }

}