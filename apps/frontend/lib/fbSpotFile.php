<?php

class fbSpotFile extends fbFile{
  public $parentTableName = 'Spot';
  public $parentKey = 0;
  public $recordLimit = 10;
  public $fileTableName = 'FileForSpot';
  public $dateCol = 'date_create';
  public $type = 'Spot';
  public $fileType = 6;
}
