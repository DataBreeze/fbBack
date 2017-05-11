<?php
class fbDiscReply extends fbReply{  
  public $parentTableName = 'Disc';
  public $parentKeyo = 0;
  public $replyTableName = 'DiscReply';
  public $mailHost = 'discuss.fishblab.com';
  public $dateCol = 'date_create';
  public $type = 'Discussion';
}
