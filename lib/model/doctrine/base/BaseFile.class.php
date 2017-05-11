<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('File', 'doctrine-discuss');

/**
 * BaseFile
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $caption
 * @property string $keyword
 * @property integer $fsize
 * @property timestamp $date_create
 * @property timestamp $ts
 * @property integer $status
 * @property integer $user_id
 * @property string $detail
 * @property decimal $lat
 * @property decimal $lon
 * @property integer $ftype
 * @property integer $sec
 * @property integer $group_id
 * @property FileForSpot $FileForSpot
 * @property FileForBlog $FileForBlog
 * @property FileForReport $FileForReport
 * @property FileForDisc $FileForDisc
 * @property User $User
 * @property UserGroup $UserGroup
 * @property FileReply $FileReply
 * @property FishForFile $FishForFile
 * 
 * @method integer       getId()            Returns the current record's "id" value
 * @method string        getCaption()       Returns the current record's "caption" value
 * @method string        getKeyword()       Returns the current record's "keyword" value
 * @method integer       getFsize()         Returns the current record's "fsize" value
 * @method timestamp     getDateCreate()    Returns the current record's "date_create" value
 * @method timestamp     getTs()            Returns the current record's "ts" value
 * @method integer       getStatus()        Returns the current record's "status" value
 * @method integer       getUserId()        Returns the current record's "user_id" value
 * @method string        getDetail()        Returns the current record's "detail" value
 * @method decimal       getLat()           Returns the current record's "lat" value
 * @method decimal       getLon()           Returns the current record's "lon" value
 * @method integer       getFtype()         Returns the current record's "ftype" value
 * @method integer       getSec()           Returns the current record's "sec" value
 * @method integer       getGroupId()       Returns the current record's "group_id" value
 * @method FileForSpot   getFileForSpot()   Returns the current record's "FileForSpot" value
 * @method FileForBlog   getFileForBlog()   Returns the current record's "FileForBlog" value
 * @method FileForReport getFileForReport() Returns the current record's "FileForReport" value
 * @method FileForDisc   getFileForDisc()   Returns the current record's "FileForDisc" value
 * @method User          getUser()          Returns the current record's "User" value
 * @method UserGroup     getUserGroup()     Returns the current record's "UserGroup" value
 * @method FileReply     getFileReply()     Returns the current record's "FileReply" value
 * @method FishForFile   getFishForFile()   Returns the current record's "FishForFile" value
 * @method File          setId()            Sets the current record's "id" value
 * @method File          setCaption()       Sets the current record's "caption" value
 * @method File          setKeyword()       Sets the current record's "keyword" value
 * @method File          setFsize()         Sets the current record's "fsize" value
 * @method File          setDateCreate()    Sets the current record's "date_create" value
 * @method File          setTs()            Sets the current record's "ts" value
 * @method File          setStatus()        Sets the current record's "status" value
 * @method File          setUserId()        Sets the current record's "user_id" value
 * @method File          setDetail()        Sets the current record's "detail" value
 * @method File          setLat()           Sets the current record's "lat" value
 * @method File          setLon()           Sets the current record's "lon" value
 * @method File          setFtype()         Sets the current record's "ftype" value
 * @method File          setSec()           Sets the current record's "sec" value
 * @method File          setGroupId()       Sets the current record's "group_id" value
 * @method File          setFileForSpot()   Sets the current record's "FileForSpot" value
 * @method File          setFileForBlog()   Sets the current record's "FileForBlog" value
 * @method File          setFileForReport() Sets the current record's "FileForReport" value
 * @method File          setFileForDisc()   Sets the current record's "FileForDisc" value
 * @method File          setUser()          Sets the current record's "User" value
 * @method File          setUserGroup()     Sets the current record's "UserGroup" value
 * @method File          setFileReply()     Sets the current record's "FileReply" value
 * @method File          setFishForFile()   Sets the current record's "FishForFile" value
 * 
 * @package    fb
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseFile extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('file');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('caption', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 255,
             ));
        $this->hasColumn('keyword', 'string', 500, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 500,
             ));
        $this->hasColumn('fsize', 'integer', 4, array(
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
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('detail', 'string', 1000, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 1000,
             ));
        $this->hasColumn('lat', 'decimal', 18, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 18,
             'scale' => '12',
             ));
        $this->hasColumn('lon', 'decimal', 18, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 18,
             'scale' => '12',
             ));
        $this->hasColumn('ftype', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '0',
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('sec', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '1',
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('group_id', 'integer', 4, array(
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
        $this->hasOne('FileForSpot', array(
             'local' => 'id',
             'foreign' => 'file_id'));

        $this->hasOne('FileForBlog', array(
             'local' => 'id',
             'foreign' => 'file_id'));

        $this->hasOne('FileForReport', array(
             'local' => 'id',
             'foreign' => 'file_id'));

        $this->hasOne('FileForDisc', array(
             'local' => 'id',
             'foreign' => 'file_id'));

        $this->hasOne('User', array(
             'local' => 'user_id',
             'foreign' => 'id'));

        $this->hasOne('UserGroup', array(
             'local' => 'group_id',
             'foreign' => 'id'));

        $this->hasOne('FileReply', array(
             'local' => 'id',
             'foreign' => 'pid'));

        $this->hasOne('FishForFile', array(
             'local' => 'id',
             'foreign' => 'pid'));
    }
}