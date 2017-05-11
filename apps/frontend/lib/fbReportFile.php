<?php

class fbReportFile extends fbFile{
  public $parentTableName = 'Report';
  public $parentKey = 0;
  public $recordLimit = 10;
  public $fileTableName = 'FileForReport';
  public $dateCol = 'date_create';
  public $type = 'Catch';
  public $fileType = 5;
}
