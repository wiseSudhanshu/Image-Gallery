<?php
require_once('./config.php');
error_reporting(E_ALL & ~E_WARNING);

/*
 * DB Class
 * This class is used for database related (connect, insert, update, and delete) operations
 * @author    Sudhanshu Rai
 */
class DB{
    public $db;

    public function __construct(){
        if(!isset($this->db)){
            // Connect to the database
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);
            if(!$conn){
                die("Failed to connect with MySQL: " . $conn->connect_error);
            }else{
                $result = $conn->query("SHOW DATABASES LIKE '".DB_NAME."'");
                if($result->num_rows == 0) {
                    if(isset($_SESSION['user_id'])) {
                        unset($_SESSION['user_id']);
                    }
                    session_destroy();
                    header('Location: index.php');
                    $sql = "CREATE DATABASE " . DB_NAME;
                    $create = $conn->query($sql);
                    if($create) {
                        $conn->query("USE " . DB_NAME);
                        $this->db = $conn;
                    } 
                    else 
                        die("Failed to create database!");
                } else {
                    $conn->query("USE " . DB_NAME);
                    $this->db = $conn;
                }
            }
        }
    }
    
    public function create_image_table($table) {
        $sql = "CREATE TABLE IF NOT EXISTS {$table} (
                    id INT(4) AUTO_INCREMENT PRIMARY KEY,
                    file_name VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
                    title VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                    modified_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                    added_by VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL
                );";
        
        $this->db->query($sql);
    }

    public function create_user_table($table) {
        $sql = "CREATE TABLE IF NOT EXISTS {$table} (
                    id INT(100) AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
                    email VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
                    password VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL
                );";

        $this->db->query($sql);
    }
    /*
     * Insert data into the database
     * @param string name of the table
     * @param array the data for inserting into the table
     */
    public function insert_data($table, $data){
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

    /*
     * Update data into the database
     * @param string name of the table
     * @param array the data for updating into the table
     * @param array where condition on updating data
     */
    public function update_data($table, $data){
        if(!empty($data) && is_array($data)){
            $colvalSet = '';
            $i = 0;
            if(!array_key_exists('modified_at',$data)){
                $data['modified_at'] = date("Y-m-d H:i:s");
            }
            foreach($data as $key=>$val){
                $pre = ($i > 0)?', ':'';
                $colvalSet .= $pre.$key."='".$this->db->real_escape_string($val)."'";
                $i++;
            }
            $query = "UPDATE ".$table." SET ".$colvalSet." WHERE id=".$data['id']."";
            $update = $this->db->query($query);
            return $update?$this->db->affected_rows:false;
        }else{
            return false;
        }
    }

    public function select_data($table, $conditions) {
        if($table == IMAGE_TABLE)
            $this->create_image_table($table);
        else
            $this->create_user_table($table);
        $whereSql = '';
        if(!empty($conditions)&& is_array($conditions)){
            $whereSql .= ' WHERE ';
            $i = 0;
            foreach($conditions as $key => $value){
                if($key == 'count') continue;
                $pre = ($i > 0)?' AND ':'';
                if(substr($value, 0, 4) == 'LIKE')
                    $whereSql .= $pre.$key." LIKE '".substr($value, 5)."'";
                else
                    $whereSql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }
        $query = "SELECT * FROM ".$table.$whereSql;
        if(array_key_exists("count", $conditions))
            $query = "SELECT COUNT(*) FROM ".$table.$whereSql;
        $select = $this->db->query($query);
        return $select;
    }

    /*
     * Delete data from the database
     * @param string name of the table
     * @param array where condition on deleting data
     */
    public function delete_data($table, $conditions){
        $whereSql = '';
        if(!empty($conditions)&& is_array($conditions)){
            $whereSql .= ' WHERE ';
            $i = 0;
            foreach($conditions as $key => $value){
                $pre = ($i > 0)?' AND ':'';
                $whereSql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }
        $query = "DELETE FROM ".$table.$whereSql;
        $delete = $this->db->query($query);
        return $delete?true:false;
    }

    /*
     * Add user into the database
     * @param string name of the table
     * @param array the data for inserting into the table
     */
    public function add_user($table, $data) {
        if(!empty($data) && is_array($data)){
            $columns = '';
            $values  = '';
            $i = 0;
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