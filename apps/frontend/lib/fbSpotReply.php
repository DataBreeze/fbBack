<?php
class fbSpotReply extends fbReply{
  public $parentTableName = 'Spot';
  public $parentKey = 0;
  public $replyTableName = 'SpotReply';
  public $notifyMailTemplate = 'replyNotifyMail';
  public $mailHost = 'spot.fishblab.com';
  public $dateCol = 'date_create';
  public $type = 'Spot';

}
