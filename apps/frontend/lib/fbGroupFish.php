<?php

class fbGroupFish extends fbFish{
  public $parentTableName = 'UserGroup';
  public $parentKeyName = 'group_id';
  public $parentKey = 0;
  public $fishTableName = 'FishForGroup';
  public $dateCol = 'date_create';
  public $recordLimit = 20;
  public $type = 'Group';
}
