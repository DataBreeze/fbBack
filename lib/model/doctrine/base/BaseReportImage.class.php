<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('ReportImage', 'doctrine-discuss');

/**
 * BaseReportImage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $report_id
 * @property timestamp $date_create
 * @property timestamp $ts
 * @property string $description
 * @property string $caption
 * @property integer $sort_order
 * 
 * @method integer     getId()          Returns the current record's "id" value
 * @method integer     getReportId()    Returns the current record's "report_id" value
 * @method timestamp   getDateCreate()  Returns the current record's "date_create" value
 * @method timestamp   getTs()          Returns the current record's "ts" value
 * @method string      getDescription() Returns the current record's "description" value
 * @method string      getCaption()     Returns the current record's "caption" value
 * @method integer     getSortOrder()   Returns the current record's "sort_order" value
 * @method ReportImage setId()          Sets the current record's "id" value
 * @method ReportImage setReportId()    Sets the current record's "report_id" value
 * @method ReportImage setDateCreate()  Sets the current record's "date_create" value
 * @method ReportImage setTs()          Sets the current record's "ts" value
 * @method ReportImage setDescription() Sets the current record's "description" value
 * @method ReportImage setCaption()     Sets the current record's "caption" value
 * @method ReportImage setSortOrder()   Sets the current record's "sort_order" value
 * 
 * @package    fb
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseReportImage extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('report_image');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('report_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '0',
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
        $this->hasColumn('description', 'string', 255, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 255,
             ));
        $this->hasColumn('caption', 'string', 125, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 125,
             ));
        $this->hasColumn('sort_order', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'default' => '999',
             'notnull' => false,
             'autoincrement' => false,
             'length' => 4,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}