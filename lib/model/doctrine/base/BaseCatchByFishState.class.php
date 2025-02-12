<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('CatchByFishState', 'doctrine');

/**
 * BaseCatchByFishState
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $fish_id
 * @property string $state
 * @property string $name
 * @property string $name_sci
 * @property integer $rate
 * @property integer $count_fish
 * @property decimal $avg_length
 * @property decimal $avg_weight
 * 
 * @method integer          getId()         Returns the current record's "id" value
 * @method integer          getFishId()     Returns the current record's "fish_id" value
 * @method string           getState()      Returns the current record's "state" value
 * @method string           getName()       Returns the current record's "name" value
 * @method string           getNameSci()    Returns the current record's "name_sci" value
 * @method integer          getRate()       Returns the current record's "rate" value
 * @method integer          getCountFish()  Returns the current record's "count_fish" value
 * @method decimal          getAvgLength()  Returns the current record's "avg_length" value
 * @method decimal          getAvgWeight()  Returns the current record's "avg_weight" value
 * @method CatchByFishState setId()         Sets the current record's "id" value
 * @method CatchByFishState setFishId()     Sets the current record's "fish_id" value
 * @method CatchByFishState setState()      Sets the current record's "state" value
 * @method CatchByFishState setName()       Sets the current record's "name" value
 * @method CatchByFishState setNameSci()    Sets the current record's "name_sci" value
 * @method CatchByFishState setRate()       Sets the current record's "rate" value
 * @method CatchByFishState setCountFish()  Sets the current record's "count_fish" value
 * @method CatchByFishState setAvgLength()  Sets the current record's "avg_length" value
 * @method CatchByFishState setAvgWeight()  Sets the current record's "avg_weight" value
 * 
 * @package    fb
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseCatchByFishState extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('catch_by_fish_state');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('fish_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
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
        $this->hasColumn('rate', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
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
        
    }
}