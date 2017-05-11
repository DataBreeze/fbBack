<?php

class fbBlogFile extends fbFile{
  public $parentTableName = 'Blog';
  public $parentKey = 0;
  public $recordLimit = 10;
  public $fileTableName = 'FileForBlog';
  public $dateCol = 'date_create';
  public $type = 'Blog';
  public $fileType = 4;
}
