<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('UserGroup', 'doctrine-discuss');

/**
 * BaseUserGroup
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property string $about
 * @property timestamp $date_create
 * @property timestamp $ts
 * @property string $location
 * @property decimal $lat
 * @property decimal $lon
 * @property integer $gtype
 * @property string $website
 * @property integer $photo_id
 * @property integer $sec
 * @property string $caption
 * @property string $keywords
 * @property string $fish
 * @property integer $fbdelete
 * @property integer $user_id
 * @property User $User
 * @property Doctrine_Collection $UserForGroup
 * @property Doctrine_Collection $UserForGroupReq
 * @property Doctrine_Collection $UserForGroupBlock
 * @property UserGroupType $UserGroupType
 * @property FishForGroup $FishForGroup
 * @property Doctrine_Collection $Disc
 * @property Doctrine_Collection $Report
 * @property Doctrine_Collection $Blog
 * @property Doctrine_Collection $Spot
 * @property Doctrine_Collection $File
 * 
 * @method integer             getId()                Returns the current record's "id" value
 * @method string              getName()              Returns the current record's "name" value
 * @method string              getAbout()             Returns the current record's "about" value
 * @method timestamp           getDateCreate()        Returns the current record's "date_create" value
 * @method timestamp           getTs()                Returns the current record's "ts" value
 * @method string              getLocation()          Returns the current record's "location" value
 * @method decimal             getLat()               Returns the current record's "lat" value
 * @method decimal             getLon()               Returns the current record's "lon" value
 * @method integer             getGtype()             Returns the current record's "gtype" value
 * @method string              getWebsite()           Returns the current record's "website" value
 * @method integer             getPhotoId()           Returns the current record's "photo_id" value
 * @method integer             getSec()               Returns the current record's "sec" value
 * @method string              getCaption()           Returns the current record's "caption" value
 * @method string              getKeywords()          Returns the current record's "keywords" value
 * @method string              getFish()              Returns the current record's "fish" value
 * @method integer             getFbdelete()          Returns the current record's "fbdelete" value
 * @method integer             getUserId()            Returns the current record's "user_id" value
 * @method User                getUser()              Returns the current record's "User" value
 * @method Doctrine_Collection getUserForGroup()      Returns the current record's "UserForGroup" collection
 * @method Doctrine_Collection getUserForGroupReq()   Returns the current record's "UserForGroupReq" collection
 * @method Doctrine_Collection getUserForGroupBlock() Returns the current record's "UserForGroupBlock" collection
 * @method UserGroupType       getUserGroupType()     Returns the current record's "UserGroupType" value
 * @method FishForGroup        getFishForGroup()      Returns the current record's "FishForGroup" value
 * @method Doctrine_Collection getDisc()              Returns the current record's "Disc" collection
 * @method Doctrine_Collection getReport()            Returns the current record's "Report" collection
 * @method Doctrine_Collection getBlog()              Returns the current record's "Blog" collection
 * @method Doctrine_Collection getSpot()              Returns the current record's "Spot" collection
 * @method Doctrine_Collection getFile()              Returns the current record's "File" collection
 * @method UserGroup           setId()                Sets the current record's "id" value
 * @method UserGroup           setName()              Sets the current record's "name" value
 * @method UserGroup           setAbout()             Sets the current record's "about" value
 * @method UserGroup           setDateCreate()        Sets the current record's "date_create" value
 * @method UserGroup           setTs()                Sets the current record's "ts" value
 * @method UserGroup           setLocation()          Sets the current record's "location" value
 * @method UserGroup           setLat()               Sets the current record's "lat" value
 * @method UserGroup           setLon()               Sets the current record's "lon" value
 * @method UserGroup           setGtype()             Sets the current record's "gtype" value
 * @method UserGroup           setWebsite()           Sets the current record's "website" value
 * @method UserGroup           setPhotoId()           Sets the current record's "photo_id" value
 * @method UserGroup           setSec()               Sets the current record's "sec" value
 * @method UserGroup           setCaption()           Sets the current record's "caption" value
 * @method UserGroup           setKeywords()          Sets the current record's "keywords" value
 * @method UserGroup           setFish()              Sets the current record's "fish" value
 * @method UserGroup           setFbdelete()          Sets the current record's "fbdelete" value
 * @method UserGroup           setUserId()            Sets the current record's "user_id" value
 * @method UserGroup           setUser()              Sets the current record's "User" value
 * @method UserGroup           setUserForGroup()      Sets the current record's "UserForGroup" collection
 * @method UserGroup           setUserForGroupReq()   Sets the current record's "UserForGroupReq" collection
 * @method UserGroup           setUserForGroupBlock() Sets the current record's "UserForGroupBlock" collection
 * @method UserGroup           setUserGroupType()     Sets the current record's "UserGroupType" value
 * @method UserGroup           setFishForGroup()      Sets the current record's "FishForGroup" value
 * @method UserGroup           setDisc()              Sets the current record's "Disc" collection
 * @method UserGroup           setReport()            Sets the current record's "Report" collection
 * @method UserGroup           setBlog()              Sets the current record's "Blog" collection
 * @method UserGroup           setSpot()              Sets the current record's "Spot" collection
 * @method UserGroup           setFile()              Sets the current record's "File" collection
 * 
 * @package    fb
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseUserGroup extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('user_group');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 255,
             ));
        $this->hasColumn('about', 'string', 2000, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 2000,
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
        $this->hasColumn('location', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 255,
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
        $this->hasColumn('gtype', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('website', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 255,
             ));
        $this->hasColumn('photo_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
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
        $this->hasColumn('caption', 'string', 500, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 500,
             ));
        $this->hasColumn('keywords', 'string', 500, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 500,
             ));
        $this->hasColumn('fish', 'string', 500, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 500,
             ));
        $this->hasColumn('fbdelete', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '0',
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
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User', array(
             'local' => 'user_id',
             'foreign' => 'id'));

        $this->hasMany('UserForGroup', array(
             'local' => 'id',
             'foreign' => 'group_id'));

        $this->hasMany('UserForGroupReq', array(
             'local' => 'id',
             'foreign' => 'group_id'));

        $this->hasMany('UserForGroupBlock', array(
             'local' => 'id',
             'foreign' => 'group_id'));

        $this->hasOne('UserGroupType', array(
             'local' => 'gtype',
             'foreign' => 'id'));

        $this->hasOne('FishForGroup', array(
             'local' => 'id',
             'foreign' => 'pid'));

        $this->hasMany('Disc', array(
             'local' => 'id',
             'foreign' => 'group_id'));

        $this->hasMany('Report', array(
             'local' => 'id',
             'foreign' => 'group_id'));

        $this->hasMany('Blog', array(
             'local' => 'id',
             'foreign' => 'group_id'));

        $this->hasMany('Spot', array(
             'local' => 'id',
             'foreign' => 'group_id'));

        $this->hasMany('File', array(
             'local' => 'id',
             'foreign' => 'group_id'));
    }
}