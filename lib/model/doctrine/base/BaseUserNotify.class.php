<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('UserNotify', 'doctrine-discuss');

/**
 * BaseUserNotify
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $mtype
 * @property string $caption
 * @property string $content
 * @property timestamp $date_create
 * @property timestamp $ts
 * @property integer $user_id
 * @property integer $from_user_id
 * @property integer $status
 * @property string $url
 * @property User $User
 * 
 * @method integer    getId()           Returns the current record's "id" value
 * @method integer    getMtype()        Returns the current record's "mtype" value
 * @method string     getCaption()      Returns the current record's "caption" value
 * @method string     getContent()      Returns the current record's "content" value
 * @method timestamp  getDateCreate()   Returns the current record's "date_create" value
 * @method timestamp  getTs()           Returns the current record's "ts" value
 * @method integer    getUserId()       Returns the current record's "user_id" value
 * @method integer    getFromUserId()   Returns the current record's "from_user_id" value
 * @method integer    getStatus()       Returns the current record's "status" value
 * @method string     getUrl()          Returns the current record's "url" value
 * @method User       getUser()         Returns the current record's "User" value
 * @method UserNotify setId()           Sets the current record's "id" value
 * @method UserNotify setMtype()        Sets the current record's "mtype" value
 * @method UserNotify setCaption()      Sets the current record's "caption" value
 * @method UserNotify setContent()      Sets the current record's "content" value
 * @method UserNotify setDateCreate()   Sets the current record's "date_create" value
 * @method UserNotify setTs()           Sets the current record's "ts" value
 * @method UserNotify setUserId()       Sets the current record's "user_id" value
 * @method UserNotify setFromUserId()   Sets the current record's "from_user_id" value
 * @method UserNotify setStatus()       Sets the current record's "status" value
 * @method UserNotify setUrl()          Sets the current record's "url" value
 * @method UserNotify setUser()         Sets the current record's "User" value
 * 
 * @package    fb
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseUserNotify extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('user_notify');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('mtype', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '1',
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('caption', 'string', 200, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 200,
             ));
        $this->hasColumn('content', 'string', 500, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 500,
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
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('from_user_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('status', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '1',
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('url', 'string', 500, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 500,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User', array(
             'local' => 'user_id',
             'foreign' => 'id'));
    }
}