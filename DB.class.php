<?php
/*
 * DB Class
 * This class is used for database related (connect, insert, update, and delete) operations
 * @author    Sudhanshu Rai
 */
class DB{
    private $dbHost     = "localhost";
    private $dbUsername = "root";
    private $dbPassword = "";
    private $dbName     = "gallery";
    public $db;

    public function __construct(){
        if(!isset($this->db)){
            // Connect to the database
            $conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
            if(!$conn){
                die("Failed to connect with MySQL: " . $conn->connect_error);
            }else{
                $this->db = $conn;
            }
        }
    }
    
    /*
     * Insert data into the database
     * @param string name of the table
     * @param array the data for inserting into the table
     */
    public function insert($table, $data){
        if(!empty($data) && is_array($data)){
            $columns = '';
            $values  = '';
            $i = 0;
            if(!array_key_exists('created_at',$data)){
                $data['created_at'] = date("Y-m-d H:i:s");
            }
            if(!array_key_exists('modified_at',$data)){
                $data['modified_at'] = date("Y-m-d H:i:s");
            }
            foreach($data as $key=>$val){
                $pre = ($i > 0)?', ':'';
                $columns .= $pre.$key;
                $values  .= $pre."'".$this->db->real_escape_string($val)."'";
                $i++;
            }
            $query = "INSERT INTO ".$table." (".$columns.") VALUES (".$values.")";
            $insert = $this->db->query($query);
            return $insert?$this->db->insert_id:false;
        }else{
            return false;
        }
    }
}