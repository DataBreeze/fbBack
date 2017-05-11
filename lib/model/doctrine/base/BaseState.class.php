<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('State', 'doctrine');

/**
 * BaseState
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $fips
 * @property string $state
 * @property string $state_full
 * @property CatchByState $CatchByState
 * @property Doctrine_Collection $Site
 * 
 * @method integer             getFips()         Returns the current record's "fips" value
 * @method string              getState()        Returns the current record's "state" value
 * @method string              getStateFull()    Returns the current record's "state_full" value
 * @method CatchByState        getCatchByState() Returns the current record's "CatchByState" value
 * @method Doctrine_Collection getSite()         Returns the current record's "Site" collection
 * @method State               setFips()         Sets the current record's "fips" value
 * @method State               setState()        Sets the current record's "state" value
 * @method State               setStateFull()    Sets the current record's "state_full" value
 * @method State               setCatchByState() Sets the current record's "CatchByState" value
 * @method State               setSite()         Sets the current record's "Site" collection
 * 
 * @package    fb
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseState extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('state');
        $this->hasColumn('fips', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('state', 'string', 2, array(
             'type' => 'string',
             'fixed' => 1,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 2,
             ));
        $this->hasColumn('state_full', 'string', 50, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 50,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('CatchByState', array(
             'local' => 'state',
             'foreign' => 'state'));

        $this->hasMany('Site', array(
             'local' => 'state',
             'foreign' => 'state'));
    }
}