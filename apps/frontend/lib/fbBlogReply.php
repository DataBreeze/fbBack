<?php
class fbBlogReply extends fbReply{
  public $parentTableName = 'Blog';
  public $parentKey = 0;
  public $replyTableName = 'BlogReply';
  public $notifyMailTemplate = 'replyNotifyMail';
  public $mailHost = 'report.fishblab.com';
  public $dateCol = 'date_blog';
  public $type = 'Report';

}
