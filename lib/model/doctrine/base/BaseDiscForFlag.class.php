<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('DiscForFlag', 'doctrine-discuss');

/**
 * BaseDiscForFlag
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $disc_id
 * @property integer $user_id
 * @property integer $flag
 * @property timestamp $ts
 * 
 * @method integer     getDiscId()  Returns the current record's "disc_id" value
 * @method integer     getUserId()  Returns the current record's "user_id" value
 * @method integer     getFlag()    Returns the current record's "flag" value
 * @method timestamp   getTs()      Returns the current record's "ts" value
 * @method DiscForFlag setDiscId()  Sets the current record's "disc_id" value
 * @method DiscForFlag setUserId()  Sets the current record's "user_id" value
 * @method DiscForFlag setFlag()    Sets the current record's "flag" value
 * @method DiscForFlag setTs()      Sets the current record's "ts" value
 * 
 * @package    fb
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseDiscForFlag extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('disc_for_flag');
        $this->hasColumn('disc_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('flag', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '5',
             'notnull' => true,
             'autoincrement' => false,
             'length' => 4,
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
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}