<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Fish', 'doctrine-discuss');

/**
 * BaseFish
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $sp_code
 * @property string $tsn
 * @property string $name
 * @property string $name_sci
 * @property integer $id
 * @property string $wiki_title
 * @property integer $noaa_id
 * @property integer $wiki_auto_load
 * @property string $detail
 * @property integer $user_id
 * @property timestamp $date_create
 * @property integer $status
 * @property string $alias
 * @property decimal $lat
 * @property decimal $lon
 * @property decimal $avg_weight
 * @property decimal $avg_length
 * @property integer $count_fish
 * @property FishAlias $FishAlias
 * @property Report $Report
 * @property User $User
 * @property FishForDisc $FishForDisc
 * @property FishForReport $FishForReport
 * @property FishForBlog $FishForBlog
 * @property FishForSpot $FishForSpot
 * @property FishForFile $FishForFile
 * @property FishForUser $FishForUser
 * @property FishForGroup $FishForGroup
 * 
 * @method string        getSpCode()         Returns the current record's "sp_code" value
 * @method string        getTsn()            Returns the current record's "tsn" value
 * @method string        getName()           Returns the current record's "name" value
 * @method string        getNameSci()        Returns the current record's "name_sci" value
 * @method integer       getId()             Returns the current record's "id" value
 * @method string        getWikiTitle()      Returns the current record's "wiki_title" value
 * @method integer       getNoaaId()         Returns the current record's "noaa_id" value
 * @method integer       getWikiAutoLoad()   Returns the current record's "wiki_auto_load" value
 * @method string        getDetail()         Returns the current record's "detail" value
 * @method integer       getUserId()         Returns the current record's "user_id" value
 * @method timestamp     getDateCreate()     Returns the current record's "date_create" value
 * @method integer       getStatus()         Returns the current record's "status" value
 * @method string        getAlias()          Returns the current record's "alias" value
 * @method decimal       getLat()            Returns the current record's "lat" value
 * @method decimal       getLon()            Returns the current record's "lon" value
 * @method decimal       getAvgWeight()      Returns the current record's "avg_weight" value
 * @method decimal       getAvgLength()      Returns the current record's "avg_length" value
 * @method integer       getCountFish()      Returns the current record's "count_fish" value
 * @method FishAlias     getFishAlias()      Returns the current record's "FishAlias" value
 * @method Report        getReport()         Returns the current record's "Report" value
 * @method User          getUser()           Returns the current record's "User" value
 * @method FishForDisc   getFishForDisc()    Returns the current record's "FishForDisc" value
 * @method FishForReport getFishForReport()  Returns the current record's "FishForReport" value
 * @method FishForBlog   getFishForBlog()    Returns the current record's "FishForBlog" value
 * @method FishForSpot   getFishForSpot()    Returns the current record's "FishForSpot" value
 * @method FishForFile   getFishForFile()    Returns the current record's "FishForFile" value
 * @method FishForUser   getFishForUser()    Returns the current record's "FishForUser" value
 * @method FishForGroup  getFishForGroup()   Returns the current record's "FishForGroup" value
 * @method Fish          setSpCode()         Sets the current record's "sp_code" value
 * @method Fish          setTsn()            Sets the current record's "tsn" value
 * @method Fish          setName()           Sets the current record's "name" value
 * @method Fish          setNameSci()        Sets the current record's "name_sci" value
 * @method Fish          setId()             Sets the current record's "id" value
 * @method Fish          setWikiTitle()      Sets the current record's "wiki_title" value
 * @method Fish          setNoaaId()         Sets the current record's "noaa_id" value
 * @method Fish          setWikiAutoLoad()   Sets the current record's "wiki_auto_load" value
 * @method Fish          setDetail()         Sets the current record's "detail" value
 * @method Fish          setUserId()         Sets the current record's "user_id" value
 * @method Fish          setDateCreate()     Sets the current record's "date_create" value
 * @method Fish          setStatus()         Sets the current record's "status" value
 * @method Fish          setAlias()          Sets the current record's "alias" value
 * @method Fish          setLat()            Sets the current record's "lat" value
 * @method Fish          setLon()            Sets the current record's "lon" value
 * @method Fish          setAvgWeight()      Sets the current record's "avg_weight" value
 * @method Fish          setAvgLength()      Sets the current record's "avg_length" value
 * @method Fish          setCountFish()      Sets the current record's "count_fish" value
 * @method Fish          setFishAlias()      Sets the current record's "FishAlias" value
 * @method Fish          setReport()         Sets the current record's "Report" value
 * @method Fish          setUser()           Sets the current record's "User" value
 * @method Fish          setFishForDisc()    Sets the current record's "FishForDisc" value
 * @method Fish          setFishForReport()  Sets the current record's "FishForReport" value
 * @method Fish          setFishForBlog()    Sets the current record's "FishForBlog" value
 * @method Fish          setFishForSpot()    Sets the current record's "FishForSpot" value
 * @method Fish          setFishForFile()    Sets the current record's "FishForFile" value
 * @method Fish          setFishForUser()    Sets the current record's "FishForUser" value
 * @method Fish          setFishForGroup()   Sets the current record's "FishForGroup" value
 * 
 * @package    fb
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseFish extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('fish');
        $this->hasColumn('sp_code', 'string', 10, array(
             'type' => 'string',
             'fixed' => 1,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 10,
             ));
        $this->hasColumn('tsn', 'string', 20, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 20,
             ));
        $this->hasColumn('name', 'string', 50, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 50,
             ));
        $this->hasColumn('name_sci', 'string', 50, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 50,
             ));
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('wiki_title', 'string', 500, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 500,
             ));
        $this->hasColumn('noaa_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '0',
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('wiki_auto_load', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '0',
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('detail', 'string', 10000, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 10000,
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
        $this->hasColumn('date_create', 'timestamp', 25, array(
             'type' => 'timestamp',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 25,
             ));
        $this->hasColumn('status', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('alias', 'string', 1000, array(
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
        $this->hasColumn('avg_weight', 'decimal', 18, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 18,
             'scale' => '2',
             ));
        $this->hasColumn('avg_length', 'decimal', 18, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 18,
             'scale' => '2',
             ));
        $this->hasColumn('count_fish', 'integer', 4, array(
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
        $this->hasOne('FishAlias', array(
             'local' => 'id',
             'foreign' => 'fish_id'));

        $this->hasOne('Report', array(
             'local' => 'id',
             'foreign' => 'fish_id'));

        $this->hasOne('User', array(
             'local' => 'user_id',
             'foreign' => 'id'));

        $this->hasOne('FishForDisc', array(
             'local' => 'id',
             'foreign' => 'fish_id'));

        $this->hasOne('FishForReport', array(
             'local' => 'id',
             'foreign' => 'fish_id'));

        $this->hasOne('FishForBlog', array(
             'local' => 'id',
             'foreign' => 'fish_id'));

        $this->hasOne('FishForSpot', array(
             'local' => 'id',
             'foreign' => 'fish_id'));

        $this->hasOne('FishForFile', array(
             'local' => 'id',
             'foreign' => 'fish_id'));

        $this->hasOne('FishForUser', array(
             'local' => 'id',
             'foreign' => 'fish_id'));

        $this->hasOne('FishForGroup', array(
             'local' => 'id',
             'foreign' => 'fish_id'));
    }
}