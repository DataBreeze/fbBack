<?php
class fbReportReply extends fbReply{
  public $parentTableName = 'Report';
  public $parentKey = 0;
  public $replyTableName = 'ReportReply';
  public $notifyMailTemplate = 'replyNotifyMail';
  public $mailHost = 'catch.fishblab.com';
  public $dateCol = 'date_catch';
  public $type = 'Catch';

}
