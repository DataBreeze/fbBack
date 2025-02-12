<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('FishDetail', 'doctrine');

/**
 * BaseFishDetail
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $species_id
 * @property string $common_name
 * @property string $scientific_name
 * @property string $lifestage
 * @property integer $abs_min_depth
 * @property integer $pref_min_depth
 * @property integer $abs_max_depth
 * @property integer $pref_max_depth
 * @property float $abs_min_lat
 * @property float $pref_min_lat
 * @property float $abs_max_lat
 * @property float $pref_max_lat
 * @property string $image_credit
 * @property string $species_category
 * @property integer $id
 * 
 * @method integer    getSpeciesId()        Returns the current record's "species_id" value
 * @method string     getCommonName()       Returns the current record's "common_name" value
 * @method string     getScientificName()   Returns the current record's "scientific_name" value
 * @method string     getLifestage()        Returns the current record's "lifestage" value
 * @method integer    getAbsMinDepth()      Returns the current record's "abs_min_depth" value
 * @method integer    getPrefMinDepth()     Returns the current record's "pref_min_depth" value
 * @method integer    getAbsMaxDepth()      Returns the current record's "abs_max_depth" value
 * @method integer    getPrefMaxDepth()     Returns the current record's "pref_max_depth" value
 * @method float      getAbsMinLat()        Returns the current record's "abs_min_lat" value
 * @method float      getPrefMinLat()       Returns the current record's "pref_min_lat" value
 * @method float      getAbsMaxLat()        Returns the current record's "abs_max_lat" value
 * @method float      getPrefMaxLat()       Returns the current record's "pref_max_lat" value
 * @method string     getImageCredit()      Returns the current record's "image_credit" value
 * @method string     getSpeciesCategory()  Returns the current record's "species_category" value
 * @method integer    getId()               Returns the current record's "id" value
 * @method FishDetail setSpeciesId()        Sets the current record's "species_id" value
 * @method FishDetail setCommonName()       Sets the current record's "common_name" value
 * @method FishDetail setScientificName()   Sets the current record's "scientific_name" value
 * @method FishDetail setLifestage()        Sets the current record's "lifestage" value
 * @method FishDetail setAbsMinDepth()      Sets the current record's "abs_min_depth" value
 * @method FishDetail setPrefMinDepth()     Sets the current record's "pref_min_depth" value
 * @method FishDetail setAbsMaxDepth()      Sets the current record's "abs_max_depth" value
 * @method FishDetail setPrefMaxDepth()     Sets the current record's "pref_max_depth" value
 * @method FishDetail setAbsMinLat()        Sets the current record's "abs_min_lat" value
 * @method FishDetail setPrefMinLat()       Sets the current record's "pref_min_lat" value
 * @method FishDetail setAbsMaxLat()        Sets the current record's "abs_max_lat" value
 * @method FishDetail setPrefMaxLat()       Sets the current record's "pref_max_lat" value
 * @method FishDetail setImageCredit()      Sets the current record's "image_credit" value
 * @method FishDetail setSpeciesCategory()  Sets the current record's "species_category" value
 * @method FishDetail setId()               Sets the current record's "id" value
 * 
 * @package    fb
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseFishDetail extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('fish_detail');
        $this->hasColumn('species_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('common_name', 'string', 200, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 200,
             ));
        $this->hasColumn('scientific_name', 'string', 200, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 200,
             ));
        $this->hasColumn('lifestage', 'string', 50, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 50,
             ));
        $this->hasColumn('abs_min_depth', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('pref_min_depth', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('abs_max_depth', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('pref_max_depth', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('abs_min_lat', 'float', null, array(
             'type' => 'float',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('pref_min_lat', 'float', null, array(
             'type' => 'float',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('abs_max_lat', 'float', null, array(
             'type' => 'float',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('pref_max_lat', 'float', null, array(
             'type' => 'float',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => '',
             ));
        $this->hasColumn('image_credit', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 255,
             ));
        $this->hasColumn('species_category', 'string', 200, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 200,
             ));
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}