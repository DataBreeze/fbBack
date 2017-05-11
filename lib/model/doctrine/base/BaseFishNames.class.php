<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('FishNames', 'doctrine');

/**
 * BaseFishNames
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property integer $fish_id
 * @property FishSpecies $FishSpecies
 * @property CatchByFish $CatchByFish
 * 
 * @method integer     getId()          Returns the current record's "id" value
 * @method string      getName()        Returns the current record's "name" value
 * @method integer     getFishId()      Returns the current record's "fish_id" value
 * @method FishSpecies getFishSpecies() Returns the current record's "FishSpecies" value
 * @method CatchByFish getCatchByFish() Returns the current record's "CatchByFish" value
 * @method FishNames   setId()          Sets the current record's "id" value
 * @method FishNames   setName()        Sets the current record's "name" value
 * @method FishNames   setFishId()      Sets the current record's "fish_id" value
 * @method FishNames   setFishSpecies() Sets the current record's "FishSpecies" value
 * @method FishNames   setCatchByFish() Sets the current record's "CatchByFish" value
 * 
 * @package    fb
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseFishNames extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('fish_names');
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
        $this->hasColumn('fish_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 4,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('FishSpecies', array(
             'local' => 'fish_id',
             'foreign' => 'id'));

        $this->hasOne('CatchByFish', array(
             'local' => 'fish_id',
             'foreign' => 'fish_id'));
    }
}