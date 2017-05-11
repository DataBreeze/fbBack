<?php

class fbPhotoFish extends fbFish{
  public $parentTableName = 'File';
  public $parentKeyName = 'file_id';
  public $parentKey = 0;
  public $fishTableName = 'FishForFile';
  public $dateCol = 'date_create';
  public $recordLimit = 20;
  public $type = 'Photo';
}
