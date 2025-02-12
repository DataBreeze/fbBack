<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('CatchByState', 'doctrine');

/**
 * BaseCatchByState
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $state
 * @property string $state_full
 * @property integer $count_fish
 * @property decimal $avg_length
 * @property decimal $avg_weight
 * @property Doctrine_Collection $State
 * 
 * @method string              getState()      Returns the current record's "state" value
 * @method string              getStateFull()  Returns the current record's "state_full" value
 * @method integer             getCountFish()  Returns the current record's "count_fish" value
 * @method decimal             getAvgLength()  Returns the current record's "avg_length" value
 * @method decimal             getAvgWeight()  Returns the current record's "avg_weight" value
 * @method Doctrine_Collection getState()      Returns the current record's "State" collection
 * @method CatchByState        setState()      Sets the current record's "state" value
 * @method CatchByState        setStateFull()  Sets the current record's "state_full" value
 * @method CatchByState        setCountFish()  Sets the current record's "count_fish" value
 * @method CatchByState        setAvgLength()  Sets the current record's "avg_length" value
 * @method CatchByState        setAvgWeight()  Sets the current record's "avg_weight" value
 * @method CatchByState        setState()      Sets the current record's "State" collection
 * 
 * @package    fb
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCatchByState extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('catch_by_state');
        $this->hasColumn('state', 'string', 2, array(
             'type' => 'string',
             'fixed' => 1,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             'length' => 2,
             ));
        $this->hasColumn('state_full', 'string', 100, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 100,
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
        $this->hasColumn('avg_length', 'decimal', 10, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 10,
             'scale' => '1',
             ));
        $this->hasColumn('avg_weight', 'decimal', 10, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 10,
             'scale' => '1',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('State', array(
             'local' => 'state',
             'foreign' => 'state'));
    }
}