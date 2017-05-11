<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('UserForFriendBlock', 'doctrine-discuss');

/**
 * BaseUserForFriendBlock
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $user_id
 * @property integer $friend_id
 * @property timestamp $ts
 * @property Doctrine_Collection $User
 * 
 * @method integer             getUserId()    Returns the current record's "user_id" value
 * @method integer             getFriendId()  Returns the current record's "friend_id" value
 * @method timestamp           getTs()        Returns the current record's "ts" value
 * @method Doctrine_Collection getUser()      Returns the current record's "User" collection
 * @method UserForFriendBlock  setUserId()    Sets the current record's "user_id" value
 * @method UserForFriendBlock  setFriendId()  Sets the current record's "friend_id" value
 * @method UserForFriendBlock  setTs()        Sets the current record's "ts" value
 * @method UserForFriendBlock  setUser()      Sets the current record's "User" collection
 * 
 * @package    fb
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseUserForFriendBlock extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('user_for_friend_block');
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             'length' => 4,
             ));
        $this->hasColumn('friend_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
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
        $this->hasMany('User', array(
             'local' => 'friend_id',
             'foreign' => 'id'));
    }
}