<?php 
    const host = "localhost" ;
    const username = "tharwat" ;
    const password = "myPassword" ;
    const dbName = "managerBook" ;

    class resetDatabase{
        // Delete current database
        public function delete_database(){
            $sql = "DROP DATABASE ".dbName ;
            $mysqli = @mysqli_connect( host , username , password ) ;
            if( mysqli_connect_errno() ){
                die( "Failed to connect to database!" ) ;
            }
            if( $mysqli -> query($sql) ){
                $status = TRUE ;
                return $status ;
            }else{
                $status = FALSE ;
                $err = $mysqli -> error ;

                $result = [ $status , $err ] ;
                return $result ;
            }
            $mysqli -> close() ;
        }
        // Create a new database
        public function create_database(){
            $sql = "create database ".dbName ;
            $mysqli = @mysqli_connect( host , username , password ) ;
            if( mysqli_connect_errno() ){
                die( "Failed to connect to database!" ) ;
            }
            if( $mysqli -> query($sql) ){
                $status = TRUE ;
                return $status ;
            }else{
                $err = $mysqli -> error ;
                $status = FALSE ;
                
                $result = [ $err , $status ] ;
                return $result ;
            }
            $mysqli -> close() ;
        }
        // Create Database tables
        public function create_tables(){
            $users ="
            Create Table Users(
                ID int NOT NULL AUTO_INCREMENT ,
                firstname varchar(20) NOT NULL ,
                lastname varchar(20) NOT NULL ,
                phone varchar(255) ,
                country varchar(50) ,
                address varchar(255) ,
                central varchar(100) ,
                governorate varchar(100) ,
                birthData DATE ,
                nationalID varchar(255) ,
                jobTitle varchar(255) ,
                gender varchar(10) ,
                status varchar(10) ,

                entityType varchar(100) ,
                entityName varchar(255) ,
                entityPhone varchar(255) ,
                entityAddress varchar(255) ,
                entityCentral varchar(100) ,
                entityGovernorate varchar(100) ,
                stablishmentDate DATE ,
                commercialRegistration_NO int ,
                taxCard_NO int ,
                bio varchar(500) ,

                website varchar(255) ,
                email varchar(255) ,
                userName varchar(255) ,
                password varchar(255) ,
                securityQuestions varchar(255) ,

                registrationDate DATE ,
                profilePhoto varchar(255) ,
                profileBackground varchar(255) ,

                UNIQUE(ID)
            );
            " ;
            $compnies="
            Create Table Compnies(
                ID int NOT NULL AUTO_INCREMENT ,
                userID int NOT NULL ,
                name varchar(255) ,
                phone varchar(255) ,
                email varchar(255) ,
                website varchar(255) ,
                address varchar(255) ,
                central varchar(255) ,
                governorate varchar(255) ,
                commercialRegistration_NO int ,
                taxCard_NO int ,
                notes varchar(1000) ,
                evaluation varchar(100) ,

                UNIQUE(ID)
            );
            " ;
            $projects="
            Create Table Projects(
                ID int NOT NULL AUTO_INCREMENT ,
                companyID int NOT NULL ,
                userID int NOT NULL ,
                name varchar(255) ,
                address varchar(255) ,
                central varchar(255) ,
                governorate varchar(255) ,
                specifications varchar(255) ,
                status varchar(100) ,
                deliveryDate DATE ,
                agreed int ,
                payments varchar(255) ,
                additionAndSubtraction varchar(255) ,
                photos varchar(255) ,
                design varchar(255) ,
                notes varchar(1000) ,
                evaluation varchar(255) ,

                UNIQUE(ID)
            );
            " ;
            $errands="
            Create Table Errands(
                ID int NOT NULL AUTO_INCREMENT ,
                userID int NOT NULL ,
                workersCount int ,
                workers varchar(255) ,
                specifications varchar(255) ,
                agreed FLOAT(7,3) ,
                paymentMethod varchar(20) ,
                payments varchar(255) ,
                receiver varchar(255) ,
                startDate DATE ,
                startTime varchar(100) ,
                endDate DATE ,
                endTime varchar(100) ,
                notes varchar(1000) ,
                evaluation varchar(255) ,

                UNIQUE(ID)
            );
            " ;
            $suppliers="
            Create Table Suppliers(
                ID int NOT NULL AUTO_INCREMENT ,
                userID int NOT NULL ,
                name varchar(255) ,
                nickName varchar(255) ,
                phone varchar(255) ,
                email varchar(255) ,
                website varchar(255) ,
                address varchar(255) ,
                central varchar(255) ,
                governorate varchar(255) ,
                commercialRegistration_NO int ,
                taxCard_NO int ,
                entityType varchar(255) ,
                notes varchar(1000) ,
                evaluation varchar(255) ,

                UNIQUE(ID)
            );
            " ;
            $workers="
            Create Table Workers(
                ID int NOT NULL AUTO_INCREMENT ,
                userID int NOT NULL ,
                name varchar(255) ,
                nickName varchar(255) ,
                phone varchar(255) ,
                address varchar(255) ,
                central varchar(255) ,
                governorate varchar(255) ,
                nationalID int ,
                birthDate DATE ,
                status varchar(100) , 
                jobTitle varchar(255) ,
                rank varchar(100) ,
                salary int ,
                workingSystem varchar(100) ,
                dailyLogs varchar(255) ,
                photo varchar(255) ,
                notes varchar(1000) ,
                evaluation varchar(255) ,

                UNIQUE(ID)
            );
            " ;
            $purchases="
            Create Table Purchases(
                ID int NOT NULL AUTO_INCREMENT ,
                supplierID int NOT NULL ,
                userID int NOT NULL ,
                purchaseData varchar(255) ,
                price FLOAT(7,3) ,
                paymentMethod varchar(20) ,
                payments varchar(255) ,
                date DATE ,
                time varchar(255) ,
                notes varchar(1000) ,
                evaluation varchar(100) ,

                UNIQUE(ID)
            );
            " ;
            $bills="
            Create Table Bills(
                ID int NOT NULL AUTO_INCREMENT ,
                userID int NOT NULL ,
                billType varchar(100) ,
                name varchar(255) ,
                price FLOAT(7,3) ,
                paymentMethod varchar(20) ,
                payments varchar(255) ,
                date DATE ,
                time varchar(255) ,
                notes varchar(1000) ,

                UNIQUE(ID)
            );
            " ;
            $databaseTables = [ "users" => $users , "companies" => $compnies , "projects" => $projects , "errands" => $errands , "suppliers" => $suppliers , "workers" => $workers , "purchases" => $purchases , "bills" => $bills ] ;
            $mysqli = @mysqli_connect( host , username , password , dbName ) or die( "Database connection failed!" ) ;
            foreach( $databaseTables as $key => $value ){
                if( @mysqli_query($mysqli,$value) ){
                    echo "<b>$key</b> Table created successfully.<br>" ;
                }else{
                    echo "Failed to create <b>$key</b> Table ( [$mysqli->error] )<br>" ;
                }
            }
            $mysqli -> close() ;
        }
        // Database Backup Handling
        public function myBackup(){
            $sql = "BACKUP DATABASE ".dbName."TO DISK = 'C:\xampp\htdocs\server\projects/backups.bak';" ;
            if( !$mysqli = @mysqli_connect( host , username , password , dbName ) ){
                die( "Failed to connect to the database!" ) ;
            }
            if( $mysqli -> query($sql) ){
                echo "Database Backup Created successfully." ;
            }else{
                $err = $mysqli -> error_list ;
                $err = $err[0]["error"] ;
                echo "Failed! <b>$err</b>" ;
            }
            $mysqli -> close() ;
        }
    }

    // Error Handling Function
    function myError( $errno , $error , $file , $line ){
        return $error ;
    }
    set_error_handler( "myError" ) ;

    $test = new resetDatabase() ;
    try{
        // Delete old database
        if( $test -> delete_database() ){
            echo "Database deleted successfully.<br>" ;
            if( $test -> create_database() ){
                echo "Database created successfully.<br>" ;
                echo "Creatting Database Tables...<br>" ;
                echo "<br>" ;
                echo $test -> create_tables() ;
                echo "Done." ;
            }else{
                $err = error_get_last() ;
                throw new exception( "Failed to create database! - $err" ) ;
            }
        }else{
            throw new exception( "Failed to delete Database!" ) ;
        }
    }catch( Exception $e ){
        echo $e -> getMessage() ;
        exit() ;
    }
?>