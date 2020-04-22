<?php 

namespace SysTem;


/**
 * Class SysDB is the connection to the system's database.
 * This class will include all functions that has to do with database and SQL.
 */
class SysDB{

    private $conn;

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
     * A get row method that can return three different types of result, depending on $data
     * 
     * @param   string $table_name
     * @param   string $where
     * @param   array $data
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

        if($query = $this->conn->query($sql)){

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
                    break;
    
            }

        }
        else{
            $result = false;
        }

        return $result;

    }

    /**
     * A method that will grab an entire colum from the specified table and column name.
     * 
     * @param   string $table_name
     * @param   string $col_name
     * 
     * @return  bool $result
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

    /**
     * A method that should be flexible enough that it will work with any valid SQL call.
     * 
     * @param   string $sql
     * 
     * @return  array $result
     */
    function get_results($sql){

        $query = $this->conn->query($sql);

        try{

            if($this->conn->error){

                throw new \Exception($this->conn->error);
    
            }
            else{

                $result = array();

                while($obj = $query->fetch_object()){

                    $sql_obj = $obj;

                    array_push($result, $sql_obj);

                }

                return $result;

            }

        }
        
        catch(\Exception $e){

            echo("CAUGHT ERROR: NOGET SOM HELST.");

            //var_dump($e);

        }

    }

    /**
     * A method for inserting data into a table.
     * 
     * @param   string $table_name
     * 
     * @param   array $data
     * 
     * @return  array $result
     * 
     * $data is expected to be a numbered array with associative arrays within.
     * 
     * $a_key_array and $a_value_array contain all the keys and values in the associative arrays respectively as they get array_pushed in the foreach loops.
     */
    function insert_first($table_name, $data){

        foreach($data as $key => $value){

            $a_key_array = array();

            $a_value_array = array();

            foreach($value as $a_key => $a_value){

                array_push($a_key_array, $a_key); 

                array_push($a_value_array, $a_value);

            }

            $sql = "INSERT INTO $table_name (" . implode(', ', $a_key_array) . ") VALUES ('" . implode("', '", $a_value_array) . "')";

            $query = $this->conn->query($sql);

            if($query){

                $result = true;
    
            }
            else{
    
                $result = false;
    
            }
        
        }

        return $result;

    }

    /**
     * A method for inserting data into a table.
     * 
     * @param   string $table_name
     * 
     * @param   array $data
     * 
     * $data is expected to be a numbered array with associative arrays within.
     * 
     * $a_key_array and $a_value_array contain all the keys and values in the associative arrays respectively as they get array_pushed in the foreach loops.
     */
    function insert($table_name, $data){

        foreach($data as $key => $value){

            $a_key_array = array();

            $a_value_array = array();

            $types = "";

            foreach($value as $a_key => $a_value){

                array_push($a_key_array, $a_key); 

                array_push($a_value_array, $a_value);

            }

            foreach($a_value_array as $type_key => $type_val){

                switch(gettype($type_val)){

                    case "integer":
                        $types = $types . "i";
                        break;

                    case "string":
                        $types = $types . "s";
                        break;

                    case "double":
                        $types = $types . "d";
                        break;

                }

            }

            $a_key_array_string = implode(", ", $a_key_array);

            //Counts how many indexes the array has and makes a string with that amount of '?'
            $a_value_count = str_repeat("?, ", count($a_value_array) - 1) . "?";

            //Uses the value counters to insert the proper amount of '?' in the sql string.
            $sql = "INSERT INTO $table_name ($a_key_array_string) VALUES ($a_value_count)";

            $stmt = $this->conn->prepare($sql);
            
            $stmt->bind_param($types, ...$a_value_array);

            $stmt->execute();

            $stmt->close();
            
        }

    }

    /**
     * A method for updating based on input.
     * 
     * @param   string $table_name
     * @param   array $data
     * @param   array $where
     * 
     */
    function update($table_name, $data, $where){

        $columns = array();

        foreach($data as $col => $val){

            $values = $col . " = " . "'" . $val . "'";

            array_push($columns, $values);

        }

        $updates = implode(", ", $columns);

        $sql = "UPDATE $table_name SET $updates WHERE ";

        $and_count = count($where);

        //At this point I do a count to check how many ANDs should be implemented.
        if($and_count - 1 / 1 > 0){

            $and_counter = $and_count -1 / 1;

            foreach($where as $and_array_key => $and_array_val){

                $sql = $sql . $and_array_key . " = " . "'" . $and_array_val . "'";

                //Here's where the ANDs get implemented if the counter is higher than 0.
                if($and_counter > 0){

                    $sql = $sql . " AND ";

                    $and_counter = $and_counter -1;

                }

            }

        }
        else{
            
            foreach($where as $and_array_key => $and_array_val){

                $sql = $sql . $and_array_key . " = " . "'" . $and_array_val . "'";

            }
            
        }
        
        $query = $this->conn->query($sql);

    }

}


?>