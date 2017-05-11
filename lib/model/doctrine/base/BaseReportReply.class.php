<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('ReportReply', 'doctrine-discuss');

/**
 * BaseReportReply
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $pid
 * @property timestamp $date_create
 * @property timestamp $ts
 * @property string $content
 * @property integer $user_id
 * @property Doctrine_Collection $Report
 * @property User $User
 * 
 * @method integer             getId()          Returns the current record's "id" value
 * @method integer             getPid()         Returns the current record's "pid" value
 * @method timestamp           getDateCreate()  Returns the current record's "date_create" value
 * @method timestamp           getTs()          Returns the current record's "ts" value
 * @method string              getContent()     Returns the current record's "content" value
 * @method integer             getUserId()      Returns the current record's "user_id" value
 * @method Doctrine_Collection getReport()      Returns the current record's "Report" collection
 * @method User                getUser()        Returns the current record's "User" value
 * @method ReportReply         setId()          Sets the current record's "id" value
 * @method ReportReply         setPid()         Sets the current record's "pid" value
 * @method ReportReply         setDateCreate()  Sets the current record's "date_create" value
 * @method ReportReply         setTs()          Sets the current record's "ts" value
 * @method ReportReply         setContent()     Sets the current record's "content" value
 * @method ReportReply         setUserId()      Sets the current record's "user_id" value
 * @method ReportReply         setReport()      Sets the current record's "Report" collection
 * @method ReportReply         setUser()        Sets the current record's "User" value
 * 
 * @package    fb
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseReportReply extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('report_reply');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('pid', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('date_create', 'timestamp', 25, array(
             'type' => 'timestamp',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 25,
             ));
        $this->hasColumn('ts', 'timestamp', 25, array(
             'type' => 'timestamp',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 25,
             ));
        $this->hasColumn('content', 'string', 2000, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 2000,
             ));
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Report', array(
             'local' => 'pid',
             'foreign' => 'id'));

        $this->hasOne('User', array(
             'local' => 'user_id',
             'foreign' => 'id'));
    }
}