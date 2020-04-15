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
     * @param   $table_name
     * @param   $where
     * @param   $data
     * 
     * if $data is "OBJECT" it will return the result in an object.
     * if $data is "ARRAY_A" it will return the result as an associative array.
     * if $data is "ARRAY_N" it will return the result as a numbered array.
     * 
     * @return  $result
     */
    function get_row($table_name, $where, $data){

        if(empty($data)){
            $data = "OBJECT";
        }

        $sql = "SELECT * FROM $table_name WHERE id = '$where'";

        $query = $this->conn->query($sql);

        switch($data){

            case "OBJECT":
                $result = new \stdClass();

                while($obj = $query->fetch_object()){

                $result = $obj;

                }
                break;
            case "ARRAY_A":
                while($row = $query->fetch_assoc()){

                    $result = $row;

                }
                break;
            case "ARRAY_N":
                while($row = $query->fetch_array()){

                    $result = $row;

                }

        }
        
        return $result;

    }

    /**
     * A method that will grab an entire colum from the specified table and column name.
     * 
     * @param   $table_name
     * @param   $col_name
     * 
     * @return  $result
     */
    function get_col($table_name, $col_name){

        $sql = "SELECT $col_name FROM $table_name";

        if($query = $this->conn->query($sql)){

            $result = array();

            while($row = $query->fetch_array()){

                array_push($result, $row[$col_name]);

            }

        }
        else{
            $result = false;
        }

        return $result;

    }


}


?>