<?php

class fbDiscFile extends fbFile{
  public $parentTableName = 'Disc';
  public $parentKey = 0;
  public $recordLimit = 10;
  public $fileTableName = 'FileForDisc';
  public $dateCol = 'date_create';
  public $type = 'Disc';
  public $fileType = 7;
}
