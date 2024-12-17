<?php

class Database {

    public $pdo;



    public function __construct($config) {

       // connect db with pdo
        // dsn = data source name
        $dsn = "mysql:" . http_build_query($config,"", ";");
        // PHP data objekts
        $this->pdo = new PDO($dsn);
        $this->pdo->setAttribute(19, 2);

    }

    public function query($sql){

        // sagatavot statement
        $statement = $this->pdo->prepare($sql);
        // do statement
        $statement->execute(); 
        return  $statement;

    } }
