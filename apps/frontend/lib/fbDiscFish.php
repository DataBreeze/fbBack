<?php

class fbDiscFish extends fbFish{
  public $parentTableName = 'Disc';
  public $parentKeyName = 'disc_id';
  public $parentKey = 0;
  public $fishTableName = 'FishForDisc';
  public $dateCol = 'date_create';
  public $recordLimit = 20;
  public $type = 'Disc';
}
