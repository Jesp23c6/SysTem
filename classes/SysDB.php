<?php 

class SysTem{

    public $conn;

    const $DB_NAME;

    const $DB_USER;

    const $DB_PASSWORD;

    const $DB_HOST;

    public function __construct(){

        $this->conn = new mysqli ("$DB_HOST", "$DB_USER", "$DB_PASSWORD", "$DB_NAME");

        if ($this->conn->connect_error){
            die('Error connecting to the database: ' . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf-8");

    }

}


?>