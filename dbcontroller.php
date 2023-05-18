<?php

class DBController {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "test";
    private $conn;
    
    function __construct() {
        $this->conn = $this->connectDB();
    }
    
    function connectDB() {
        $conn = mysqli_connect($this->host, $this->user, $this->password, $this->database);
        
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            exit();
        }
        
        return $conn;
    }
    
    public function runQuery($query)
    {
        $result = mysqli_query($this->conn, $query);
        
        if ($result === false) {
            // Handle the query error
            echo "Query error: " . mysqli_error($this->conn);
            return false;
        }
    
        if ($result instanceof mysqli_result) {
            $rows = array();
    
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
    
            mysqli_free_result($result);
    
            return $rows;
        } else {
            return $result;
        }
    }
    
    
    function numRows($query) {
        $result = mysqli_query($this->conn, $query);
        
        if ($result === false) {
            // Handle the query error
            echo "Query error: " . mysqli_error($this->conn);
            return false;
        }
        
        $rowcount = mysqli_num_rows($result);
        mysqli_free_result($result);
        
        return $rowcount;   
    }
}
?>