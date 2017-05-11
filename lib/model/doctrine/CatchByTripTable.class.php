<?php


class CatchByTripTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CatchByTrip');
    }
}