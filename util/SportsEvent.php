<?php
require_once("Event.php");

class SportsEvent extends Event {

    const TABLE_NAME = "sport";
    const TABLE_PREFIX =  parent::TABLE_NAME . "_";

    // properties



    // todo : muh database ekle, yukarıya table name ekle.
    public function insertToDatabase() : bool
    {
        return false;
    }
}
