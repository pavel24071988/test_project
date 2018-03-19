<?php

class Db 
{
    protected $dbh;

    public function __construct() {
        $this->dbh = new PDO('mysql:host=localhost;dbname=test_project', 'root', 'root');
    }

    public function getConnection()
    {
        return $this->dbh;
    }
}

