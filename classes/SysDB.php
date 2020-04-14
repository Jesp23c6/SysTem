<?php 

class SysTem{

    public $conn;

    public function __construct(){

        $this->conn = new mysqli ("$DB_HOST", "$DB_USER", "$DB_PASSWORD", "$DB_NAME");

        if ($this->conn->connect_error){
            die('Error connecting to the database: ' . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf-8");

    }

}


?>