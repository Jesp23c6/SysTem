<?php 

class SysTem{

    public $conn;

    /**
     * 
     */
    public function __construct(){

        $this->conn = new mysqli (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($this->conn->connect_error){
            die('Error connecting to the database: ' . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf-8");

    }

    /**
     * 
     */
    function get_row($table_name, $where, $data){

        if(empty($data)){
            $data = "OBJECT";
        }

        $sql = "SELECT * FROM $table_name WHERE id = '$where'";

        $result = $this->conn->query($sql);

        if($data == "OBJECT"){

            $array = array();

            while($row = $result->fetch_assoc()){

                $test = $row['id'];

            }

        }

        return $test;
        //return $array;

    }


}


?>