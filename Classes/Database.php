<?php

class Database {
	//Database connection
	private $db_host = 'localhost';
    private $db_name = 'api';
    private $db_username = 'postgres';
    private $db_password = 'masterkey02';
    
    public function dbConnection(){
        
        try{
            $conn = new PDO('pgsql:host='.$this->db_host.';dbname='.$this->db_name,$this->db_username,$this->db_password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }
        catch(PDOException $e){
            echo "Connection error ".$e->getMessage(); 
            exit;
        }
        
        
    }

}