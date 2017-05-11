<?php

class fbReportFish extends fbFish{
  public $parentTableName = 'Report';
  public $parentKeyName = 'report_id';
  public $parentKey = 0;
  public $fishTableName = 'FishForReport';
  public $dateCol = 'date_create';
  public $recordLimit = 20;
  public $type = 'Catch';
}
