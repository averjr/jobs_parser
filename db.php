<?php

class Database
{
    private $_db;
    static $_instance;

    private function __construct()
    {
        $this->_db = new PDO('mysql:host=localhost;dbname=jobs_parser',
            'login',
            'pass',
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function query($query)
    {
        $args = func_get_args();
        array_shift($args); //first element is not an argument but the query itself, should removed

        $reponse = $this->_db->prepare($query);
        $reponse->execute($args);
        return $reponse;
    }

    public function multi_insert($query, $params)
    {
        //get query like "insert into mytable (name, age) values (:name, :age)"
        $response = $this->_db->prepare($query);

        // start transaction
        $this->_db->beginTransaction();
        foreach ($params as &$row) {
            $response->execute($row);
        }
        // end transaction
        return $this->_db->commit();
    }

    public function getLastId()
    {
        return $this->_db->lastInsertId();
    }

}