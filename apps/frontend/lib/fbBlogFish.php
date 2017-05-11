<?php

class fbBlogFish extends fbFish{
  public $parentTableName = 'Blog';
  public $parentKeyName = 'blog_id';
  public $parentKey = 0;
  public $fishTableName = 'FishForBlog';
  public $dateCol = 'date_create';
  public $recordLimit = 20;
  public $type = 'Blog';
}
