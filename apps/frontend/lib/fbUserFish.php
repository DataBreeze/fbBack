<?php

class fbUserFish extends fbFish{
  public $parentTableName = 'User';
  public $parentKeyName = 'user_id';
  public $parentKey = 0;
  public $fishTableName = 'FishForUser';
  public $dateCol = 'date_create';
  public $recordLimit = 20;
  public $type = 'User';
}
