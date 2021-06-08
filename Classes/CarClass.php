<?php

include 'Classes/Database.php';

class CarClass extends Database {
    //DATABASE INITIALIZATION
    private function connDB()
    {
        $db_connection = new Database();
        $connDB = $db_connection->dbConnection();
        return $connDB;
    }
    //SELECT ALL data SQL QUERY
    public function read(){
        $conn = $this->connDB();
        $sql = "SELECT * FROM car_info ORDER BY car_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $cars = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $cars[] = [
                'car_id' => $row['car_id'],
                'car_brand' => $row['car_brand'],
                'car_plate' => $row['car_plate'],
                'car_current_location' => $row['car_current_location'],
                'car_owner' => $row['car_owner'],
                'is_lost' => $row['is_lost'],
                'created_by' => $row['created_by'],
                'created_date' => $row['created_date'],
                'modify_by' => $row['modify_by'],
                'modify_date' => $row['modify_date']
            ];
        }
        return $cars;
    }
    //GET CAR by ID
    public function getbyID($car_id){
        $conn = $this->connDB();
        $car_id = $_GET['car_id'];
        $sql = "SELECT * FROM car_info WHERE car_id='$car_id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        if(is_numeric($car_id) && $stmt->rowCount() > 0){
            $cars = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $cars[] = [
                    'car_id' => $row['car_id'],
                    'car_brand' => $row['car_brand'],
                    'car_plate' => $row['car_plate'],
                    'car_current_location' => $row['car_current_location'],
                    'car_owner' => $row['car_owner'],
                    'is_lost' => $row['is_lost'],
                    'created_by' => $row['created_by'],
                    'created_date' => $row['created_date'],
                    'modify_by' => $row['modify_by'],
                    'modify_date' => $row['modify_date']
                ];
            }
            return $cars;
        }
        else{
            //IF THERE IS NO CAR DATA IN OUR DATABASE
            echo json_encode(['message'=>'No data found']);
        }
    }
    //CREATE new data
    public function create(){
        $conn = $this->connDB();
        $data = json_decode(file_get_contents("php://input"));
        //CHECK if car_id already exists in database
        $car_id = $data->car_id;
        $check_data = "SELECT * FROM car_info WHERE car_id=:car_id";
        $check_data_stmt = $conn->prepare($check_data);
        $check_data_stmt->bindValue(':car_id', $car_id,PDO::PARAM_INT);
        $check_data_stmt->execute();
        //CREATE MESSAGE ARRAY AND SET EMPTY
        $msg['message'] = '';
        //CHECK if car_id already exists in database
        if($check_data_stmt->rowCount() > 0){
            $msg['message'] = 'Car_id already exists in database';
        }
        else{
                // CHECK IF RECEIVED DATA FROM THE REQUEST
                if(isset($data->car_id) && isset($data->car_brand) && isset($data->car_plate) && isset($data->car_owner) && isset($data->is_lost) && isset($data->created_by) && isset($data->created_date)){
                // CHECK DATA VALUE IS EMPTY OR NOT
                if(!empty($data->car_id)){
                $insert_query = "INSERT INTO car_info(car_id,car_brand,car_plate,car_current_location,car_owner,is_lost,created_by,created_date,modify_by,modify_date) 
                VALUES(:car_id,:car_brand,:car_plate,:car_current_location,:car_owner,:is_lost,:created_by,:created_date,:modify_by,:modify_date)";
                $insert_stmt = $conn->prepare($insert_query);
                // DATA BINDING
                $insert_stmt->bindValue(':car_id', htmlspecialchars(strip_tags($data->car_id)),PDO::PARAM_INT);
                $insert_stmt->bindValue(':car_brand', htmlspecialchars(strip_tags($data->car_brand)),PDO::PARAM_STR);
                $insert_stmt->bindValue(':car_plate', htmlspecialchars(strip_tags($data->car_plate)),PDO::PARAM_STR);
                $insert_stmt->bindValue(':car_current_location', NULL,PDO::PARAM_NULL);
                $insert_stmt->bindValue(':car_owner', htmlspecialchars(strip_tags($data->car_owner)),PDO::PARAM_STR);
                $insert_stmt->bindValue(':is_lost', $data->is_lost,PDO::PARAM_INT);
                $insert_stmt->bindValue(':created_by', htmlspecialchars(strip_tags($data->created_by)),PDO::PARAM_INT);
                $insert_stmt->bindValue(':created_date', htmlspecialchars(strip_tags($data->created_date)),PDO::PARAM_STR);
                $insert_stmt->bindValue(':modify_by', NULL,PDO::PARAM_NULL);
                $insert_stmt->bindValue(':modify_date', NULL,PDO::PARAM_NULL);
                if($insert_stmt->execute()){
                $msg['message'] = 'Data Inserted Successfully';
                }else{
                $msg['message'] = 'Data not Inserted';
                }
                }else{
                $msg['message'] = 'Oops! empty field detected. Please fill all the fields';
                }
                }
            else{
                $msg['message'] = 'Please fill all the required fields';
            }
        }
        //ECHO DATA IN JSON FORMAT
        echo  json_encode($msg);
    }
    //DELETE data
    public function delete($car_id){
        $conn = $this->connDB();
        // GET DATA FORM REQUEST
        $data = json_decode(file_get_contents("php://input"));
        $msg['message'] = '';
        //CHECKING, IF ID AVAILABLE ON $data
        $car_id = $data->car_id;
        //GET DATA BY ID FROM DATABASE
        // YOU CAN REMOVE THIS QUERY AND PERFORM ONLY DELETE QUERY
        $validate_data = "SELECT * FROM car_info WHERE car_id=:car_id";
        $validate_data_stmt = $conn->prepare($validate_data);
        $validate_data_stmt->bindValue(':car_id',$car_id,PDO::PARAM_INT);
        $validate_data_stmt->execute();
        //CHECK WHETHER THERE IS ANY DATA IN OUR DATABASE
        if($validate_data_stmt->rowCount() > 0){
            //DELETE DATA BY ID FROM DATABASE
            $delete_data = "DELETE FROM car_info WHERE car_id=:car_id";
            $delete_data_stmt = $conn->prepare($delete_data);
            $delete_data_stmt->bindValue(':car_id',$car_id,PDO::PARAM_INT);
            if($delete_data_stmt->execute()){
                $msg['message'] = 'Data Deleted Successfully';
            }else{
                $msg['message'] = 'Data Not Deleted';
            }
            }else{
                $msg['message'] = 'Invalid ID';
            }
        // ECHO MESSAGE IN JSON FORMAT
        echo  json_encode($msg);
    }
    //UPDATE data
    public function update($car_id){
        $conn = $this->connDB();
        // GET DATA FORM REQUEST
        $data = json_decode(file_get_contents("php://input"));
        $msg['message'] = '';
        $car_id = $data->car_id;
        //GET DATA BY ID FROM DATABASE
        $get_data = "SELECT * FROM car_info WHERE car_id=:car_id";
        $get_stmt = $conn->prepare($get_data);
        $get_stmt->bindValue(':car_id', $car_id,PDO::PARAM_INT);
        $get_stmt->execute();
        //CHECKING, IF ID AVAILABLE ON $data
        if(isset($car_id)){
            //CHECK WHETHER THERE IS ANY DATA IN OUR DATABASE
            if($get_stmt->rowCount() > 0){
            // FETCH POST FROM DATABASE 
            $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
            // CHECK, IF NEW UPDATE REQUEST DATA IS AVAILABLE THEN SET IT OTHERWISE SET OLD DATA
            $car_brand = isset($data->car_brand) ? $data->car_brand : $row['car_brand'];
            $car_plate = isset($data->car_plate) ? $data->car_plate : $row['car_plate'];
            $car_current_location = isset($data->car_current_location) ? $data->car_current_location : $row['car_current_location'];
            $car_owner = isset($data->car_owner) ? $data->car_owner : $row['car_owner'];
            $is_lost = isset($data->is_lost) ? $data->is_lost : $row['is_lost'];
            $created_by = isset($data->created_by) ? $data->created_by : $row['created_by'];
            $created_date = isset($data->created_date) ? $data->created_date : $row['created_date'];
            $modify_by = 2;
            $modify_date = new DateTime();
            $update_query = "UPDATE car_info SET car_brand = :car_brand, car_plate = :car_plate, car_current_location = :car_current_location,
            car_owner = :car_owner, is_lost = :is_lost, created_by = :created_by, created_date = :created_date,
            modify_by = :modify_by, modify_date = :modify_date
            WHERE car_id = :car_id";
            $update_stmt = $conn->prepare($update_query);
            // DATA BINDING AND REMOVE SPECIAL CHARS AND REMOVE TAGS
            $update_stmt->bindValue(':car_brand', htmlspecialchars(strip_tags($car_brand)),PDO::PARAM_STR);
            $update_stmt->bindValue(':car_plate', htmlspecialchars(strip_tags($car_plate)),PDO::PARAM_STR);
            $update_stmt->bindValue(':car_current_location', htmlspecialchars(strip_tags($car_current_location)),PDO::PARAM_STR);
            $update_stmt->bindValue(':car_owner', htmlspecialchars(strip_tags($car_owner)),PDO::PARAM_STR);
            $update_stmt->bindValue(':is_lost', $is_lost,PDO::PARAM_INT);
            $update_stmt->bindValue(':created_by', $created_by,PDO::PARAM_INT);
            $update_stmt->bindValue(':created_date', $created_date,PDO::PARAM_STR);
            $update_stmt->bindValue(':modify_by', $modify_by,PDO::PARAM_INT);
            $update_stmt->bindValue(':modify_date', $modify_date->format('Y-m-d H:i:s'),PDO::PARAM_STR);
            $update_stmt->bindValue(':car_id', $car_id,PDO::PARAM_INT);
            if($update_stmt->execute()){
                $msg['message'] = 'Data updated successfully';
            }else{
                $msg['message'] = 'Data not updated';
            }
            }
            else{
            $msg['message'] = 'Invalid ID';
            }  
            echo  json_encode($msg);
        }
    }
}