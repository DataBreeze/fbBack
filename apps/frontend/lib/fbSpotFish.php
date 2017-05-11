<?php

class fbSpotFish extends fbFish{
  public $parentTableName = 'Spot';
  public $parentKeyName = 'spot_id';
  public $parentKey = 0;
  public $fishTableName = 'FishForSpot';
  public $dateCol = 'date_create';
  public $recordLimit = 20;
  public $type = 'Spot';
}
