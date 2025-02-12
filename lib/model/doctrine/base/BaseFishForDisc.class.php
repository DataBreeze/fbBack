<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('FishForDisc', 'doctrine-discuss');

/**
 * BaseFishForDisc
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $pid
 * @property integer $fish_id
 * @property Doctrine_Collection $Disc
 * @property Doctrine_Collection $Fish
 * 
 * @method integer             getPid()     Returns the current record's "pid" value
 * @method integer             getFishId()  Returns the current record's "fish_id" value
 * @method Doctrine_Collection getDisc()    Returns the current record's "Disc" collection
 * @method Doctrine_Collection getFish()    Returns the current record's "Fish" collection
 * @method FishForDisc         setPid()     Sets the current record's "pid" value
 * @method FishForDisc         setFishId()  Sets the current record's "fish_id" value
 * @method FishForDisc         setDisc()    Sets the current record's "Disc" collection
 * @method FishForDisc         setFish()    Sets the current record's "Fish" collection
 * 
 * @package    fb
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseFishForDisc extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('fish_for_disc');
        $this->hasColumn('pid', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('fish_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             'length' => 4,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Disc', array(
             'local' => 'pid',
             'foreign' => 'id'));

        $this->hasMany('Fish', array(
             'local' => 'fish_id',
             'foreign' => 'id'));
    }
}