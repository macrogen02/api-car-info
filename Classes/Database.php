<?php

class Database {
	//Database connection
	private $db_host = 'ec2-34-230-115-172.compute-1.amazonaws.com';
    private $db_name = 'dav2p2geob8l1j';
    private $db_username = 'ehcnaytlesqfjd';
    private $db_password = '66fb1046913245277f8e203fbd6b1f9739bcc6f9b18d92bfab461be52874c2ad';
    
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
