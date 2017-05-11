<?php
class fbPhotoReply extends fbReply{
  public $parentTableName = 'File';
  public $parentKey = 0;
  public $replyTableName = 'FileReply';
  public $notifyMailTemplate = 'replyNotifyMail';
  public $mailHost = 'photo.fishblab.com';
  public $dateCol = 'date_create';
  public $type = 'Photo';
}
