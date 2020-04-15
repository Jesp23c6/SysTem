<?php 

namespace SysTem;


/**
 * Class SysDB is the connection to the system's database.
 * This class will include all functions that has to do with database and SQL.
 */
class SysDB{

    public $conn;

    /**
     * Here I'm making a constructor that makes sure to either connect or show error message.
     */
    public function __construct(){

        $this->conn = new \mysqli (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($this->conn->connect_error){
            die('Error connecting to the database: ' . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf-8");

    }

    /**
     * A get row function that can return three different types of result, depending on $data
     * 
     * if $data is "OBJECT" it will return the result in an object.
     * if $data is "ARRAY_A" it will return the result as an associative array.
     * if $data is "ARRAY_N" it will return the result as a numbered array.
     */
    function get_row($table_name, $where, $data){

        if(empty($data)){
            $data = "OBJECT";
        }

        $sql = "SELECT * FROM $table_name WHERE id = '$where'";

        $query = $this->conn->query($sql);

        if($data == "OBJECT"){

            $result = new \stdClass();

            while($obj = $query->fetch_object()){

                $result = $obj;

            }

        }

        else if($data == "ARRAY_A" || "ARRAY_N")
            while($row = $query->fetch_assoc()){

                if($data == "ARRAY_A"){

                    $result = array();

                    $result = array('ID' => $row['id'], 'Year' => $row['year'], 'Manufacturer' => $row['make'], 'Model' => $row['model']);

                }

                if($data == "ARRAY_N"){

                    $result = array();

                    $result = array($row['id'], $row['year'], $row['make'], $row['model']);

                }

        }

        return $result;

    }


}


?>