<?php
    require_once "core.php" ;
    trait mainTrait{
        # Search Handling
        public function searchHandling( $data , $search ){
            try{
                $keys = [] ;
                $finalResult = [] ;
                
                $string_count = str_word_count( "$search" , 1 ) ; //convert the search string into an array ( to loop throw it )
                // loop throw pattern
                for( $p = 0 ; $p < count($string_count) ; $p++ ){
                    $pattern = $string_count[$p] ;
                    // loop throw data
                    for( $i = 0 ; $i < count($data) ; $i++ ){
                        $current = $data[$i] ;
                        if( preg_grep("/$pattern.|$pattern/i",$current) ){
                            //check keys if the current index is already fetched
                            if( count($keys) ){
                                $index = implode( "" , $keys ) ;
                                if( stristr($index,"$i") ){
                                    continue ;
                                }else{
                                    array_push( $finalResult , $current ) ;
                                    array_push( $keys , $i ) ;
                                }
                            }else{
                                if( $i == 0 ){
                                    array_push( $finalResult , $current ) ;
                                    array_push( $keys , "0$i" ) ;
                                }else{
                                    array_push( $finalResult , $current ) ;
                                    array_push( $keys , $i ) ;
                                }
                            }
                        }else{
                            throw new exception( "No Results found!" ) ;
                        }
                    }
                }
                return $finalResult ;
            }catch( exception $e ){
                echo $e -> getMessage() ;
            }
            
        }
        # Comparing Dates
        //=================================================================================================================================//
        function compareDates( $target , $from=null , $to=null ){
            try{
                $target_date = str_ireplace( "/" , " " , $target ) ;
                $target_date = sscanf( $target_date , "%s %s %s" ) ;
                list( $target_year , $target_month , $target_day ) = $target_date ;

                // Case "From" and "To" ( are real )
                if( $from && $to ){
                    $from_date = str_ireplace( "/" , " " , $from ) ;
                    $from_date = sscanf( $from_date , "%s %s %s" ) ;
                    list( $from_year , $from_month , $from_day ) = $from_date ;
                    // setting a default values
                    if( !$from_day ){ $from_day = 1 ; }
                    if( !$from_month ){ $from_month = 1 ; }

                    $to_date = str_ireplace( "/" , " " , $to ) ;
                    $to_date = sscanf( $to_date , "%s %s %s" ) ;
                    list( $to_year , $to_month , $to_day ) = $to_date ;
                    // setting a default values
                    if( !$to_day ){ $to_day = 1 ; }
                    if( !$to_month ){ $to_month = 1 ; }

                    // Case ( From-Date > To-Date )
                    if( $from_year > $to_year ){
                        throw new Exception( "Invalid year format!" ) ;
                    }

                    // Case ( From-Date < To-Date )
                    if( $from_year < $to_year ){
                        if( $target_year > $from_year && $target_year < $to_year ){
                            return TRUE ;
                        }elseif( $target_year == $from_year ){
                            if( $target_month > $from_month ){ 
                                return TRUE ; 
                            }elseif( $target_month == $from_month ){ 
                                if( $target_day >= $from_day ){ return TRUE ; }
                            }
                        }elseif( $target_year == $to_year ){
                            if( $target_month < $to_month ){
                                return TRUE ;
                            }elseif( $target_month == $to_month ){
                                if( $target_day <= $to_day ){ return TRUE ; }
                            }
                        }
                    
                    // Case ( From-Date == To-Date )
                    }elseif( $from_year == $to_year ){
                        $master_year = $from_year ;
                        if( $target_year == $master_year ){
                            if( $from_month > $to_month ){ throw new exception( "Invalid Month format!" ) ; }
                            if( $from_month < $to_month ){ 
                                if( $target_month > $from_month || $target_month < $from_month ){ echo "this is matches.<br>" ;
                                    return TRUE ;
                                }elseif( $target_month == $from_month ){ 
                                    if( $target_day >= $from_day ){ return TRUE ; }
                                }elseif( $target_month == $to_month ){ 
                                    if( $target_day <= $to_day ){ return TRUE ; }
                                }
                            }elseif( $from_month == $to_month ){ 
                                $master_month = $from_month ;
                                if( $target_month == $master_month ){
                                    if( $from_day > $to_day ){ throw new exception( "Invalid Day format!" ) ; }
                                    if( $from_day < $to_day ){
                                        if( $target_day >= $from_day && $target_day <= $to_day ){ return TRUE ; }
                                    }elseif( $from_day == $to_day ){
                                        $master_day = $from_day ;
                                        if( $target_day == $master_day ){ return TRUE ; }
                                    }
                                }
                            }
                        }
                    }
                
                // Case only "From" ( is real )
                }elseif( $from ){
                    $from_date = str_ireplace( "/" , " " , $from ) ;
                    $from_date = sscanf( $from_date , "%s %s %s" ) ;
                    list( $from_year , $from_month , $from_day ) = $from_date ;

                    if( $target_year > $from_year ){
                        return TRUE ;
                    }elseif( $target_year == $from_year ){
                        if( $target_month > $from_month ){
                            return TRUE ;
                        }elseif( $target_month == $from_month ){
                            if( $target_day >= $from_day ){ return TRUE ; }
                        }
                    }

                // Case only "To" ( is real )
                }elseif( $to ){
                    $to_date = str_ireplace( "/" , " " , $to ) ;
                    $to_date = sscanf( $to_date , "%s %s %s" ) ;
                    list( $to_year , $to_month , $to_day ) = $to_date ;

                    if( $target_year < $to_year ){
                        return TRUE ;
                    }elseif( $target_year == $to_year ){
                        if( $target_month < $to_month ){
                            return TRUE ;
                        }elseif( $target_month == $to_month ){
                            if( $target_day <= $to_day ){ return TRUE ; }
                        }
                    }
                }
            }catch( Exception $e ){
                return $e -> getMessage() ;
            }
            
        }
        //=================================================================================================================================//
    }
    class operations extends main{
        use mainTrait ;
        private $users ;

        public $userData ;
        public $companies ;
        public $projects ;
        public $errands ;
        public $suppliers ;
        public $workers ;
        public $purchases ;
        public $bills ;

        # Log IN
        public function __construct( $username , $password ){
            try{
                // get all uers
                $mysqli = new mysqli( host , username , password , dbname ) or die( "Failed to connect to the database!" ) ;
                $sql = "SELECT * FROM Users" ;
                if($mysqli -> real_query($sql) ){
                    $result = $mysqli -> store_result() ;
                    $result = $result -> fetch_all(MYSQLI_ASSOC) ;
                    $result = prepareResult( $result ) ;
                    $this -> users = $result ;
                }else{
                    throw new exception( "Sorry an error occurred!" ) ;
                }
                $mysqli -> close() ;

                // Start searching for a user
                if( empty($username) || empty($password) ){
                    throw new exception( "User name AND Password are Required!" ) ;
                }else{
                    $username = filterInput($username) ;
                    $username = stripslashes( $username ) ;

                    $password = filterInput($password) ;
                    $password = stripslashes( $password ) ;
                    // Fetch all users to check 
                    $allUsers = $this -> users ;
                    if( !$allUsers ){
                        echo "No Users found from Login!<br>" ;
                    }

                    for( $i = 0 ; $i < count($allUsers) ; $i++ ){
                        if( !$currentUser = $allUsers[$i] ){
                            // set a method to feedback error result
                            throw new exception( "An error occurred!" ) ;
                        }else{
                            // Encode Username
                            $user2find = $currentUser['userName'] ;
                            $user2find = myEncoding( $user2find ) ;
                            // Encode Password
                            $password2find = $currentUser['password'] ;
                            $password2find = myEncoding( $password2find ) ;
                        }
                        if( $username == $user2find && $password == $password2find ){
                            $this -> userData = $currentUser ;
                            
                            $id = $currentUser['ID'] ;
                            $userName = $currentUser['userName'] ;
                            if( $id && $userName ){
                                $sql = "SELECT * FROM compnies WHERE userID = $id;" ;
                                $sql .= "SELECT * FROM projects WHERE userID = $id;" ;
                                $sql .= "SELECT * FROM errands WHERE userID = $id;" ;
                                $sql .= "SELECT * FROM suppliers WHERE userID = $id;" ;
                                $sql .= "SELECT * FROM workers WHERE userID = $id;" ;
                                $sql .= "SELECT * FROM purchases WHERE userID = $id;" ;
                                $sql .= "SELECT * FROM bills WHERE userID = $id;" ;

                                $mysqli = new mysqli( host , username , password , dbname ) or die( "Failed to connect to database!" ) ;
                                if( mysqli_multi_query($mysqli,$sql) ){
                                    do{   
                                        $result = mysqli_store_result($mysqli) ;
                                        if( !$field = mysqli_fetch_fields($result) ){
                                            $error = $mysqli -> error_list ;
                                            $err = $error[0]['error'] ;
                                            throw new exception( "$err" ) ;
                                        }
                                        $table = $field[0] -> table ;
                                        $table = strtolower($table) ;
                                        # $data = @mysqli_fetch_all( $result , MYSQLI_ASSOC ) or die( "Cannot fetch the data!" ) ;
                                        $data = @mysqli_fetch_all( $result , MYSQLI_ASSOC ) ;
                                        $data = prepareResult( $data ) ;
                                        switch($table){
                                            case 'compnies' : $this -> companies = $data ; break ;
                                            case 'projects' : $this -> projects = $data ; break ;
                                            case 'errands' : $this -> errands = $data ; break ;
                                            case 'suppliers' : $this -> suppliers = $data ; break ;
                                            case 'workers' : $this -> workers = $data ; break ;
                                            case 'purchases' : $this -> purchases = $data ; break ;
                                            case 'bills' : $this -> bills = $data ; break ;
                                        }
                                        $result -> free_result() ;
                                    }while( mysqli_next_result($mysqli) ) ;
                                    $mysqli -> close() ;
                                    break ;
                                }else{
                                    $error = $mysqli -> error_list ;
                                    $err = $error[0]['error'] ;
                                    throw new exception( "$err" ) ;
                                    $mysqli -> close() ;
                                }
                            }else{
                                throw new exception( "Sorry. Something is wrong!" ) ;
                            }
                        }
                        if( $i == count( $allUsers )-1 ){
                            throw new exception( "User name OR Password is incorrect!<br>" ) ;
                        }
                    }
                }
            }catch( Exception $e ){
                $result = $e -> getMessage() ;
                return $result ;
                exit() ;
            }
        }

        # Result Handling 
        public function resultHandling( $table , $search=NULL , $value=NULL , $from=NULL , $to=NULL ){
            try{
                $table = filterInput( $table ) ;
                $search = filterInput( $search ) ;
                $value = filterInput( $value ) ;
                $from = filterInput( $from ) ;
                $to = filterInput( $to ) ;
                
                // preparring Companies table
                if( $table == "companies" ){
                    $companies = $this -> companies ;
                    if( $search ){
                        $result = $this -> searchHandling( $companies , $search ) ;
                        return $result ;
                    }else{
                        $companies = array_reverse( $companies ) ;
                        return $companies ;
                    }
                }

                // preparring Projects table
                if( $table == "projects" ){
                    $projects = $this -> projects ;
                    if( $search ){ # Search
                        $result = $this -> searchHandling( $projects , $search ) ;
                        return $result ;
                    }elseif( $value ){
                        if( $value == "allProjects" ){ # All Projects
                            $projects = array_reverse( $projects ) ;
                            return $projects ;
                        }elseif( $value == "currentProjects" ){ # Current Projects
                            $finalResult = [] ;
                            for( $i = 0 ; $i < count($projects) ; $i++ ){
                                $currentProject = $projects[$i] ;
                                $projectStatus = $currentProject['status'] ;
                                if( $projectStatus == "delivered" || $projectStatus == "canceled" ){
                                    continue ;
                                }else{
                                    array_push( $finalResult , $currentProject ) ;
                                }
                            }
                            $finalResult = array_reverse( $finalResult ) ;
                            return $finalResult ;
                        }elseif( $value == "delivered" ){ # Delivered Projects
                            $finalResult = [] ;
                            for( $i = 0 ; $i < count($projects) ; $i++ ){
                                $currentProject = $projects[$i] ;
                                $projectStatus = $currentProject['status'] ;
                                if( $projectStatus == "delivered" ){
                                    array_push( $finalResult , $currentProject ) ;
                                }
                            }
                            $finalResult = array_reverse( $finalResult ) ;
                            return $finalResult ;
                        }elseif( $value == "canceled" ){ # Canceled Projects
                            $finalResult = [] ;
                            for( $i = 0 ; $i < count($projects) ; $i++ ){
                                $currentProject = $projects[$i] ;
                                $projectStatus = $currentProject["status"] ;
                                if( $projectStatus == "canceled" ){
                                    array_push( $finalResult , $currentProject ) ;
                                }
                            }
                            $finalResult = array_reverse( $finalResult ) ;
                            return $finalResult ;
                        }
                    }else{
                        $projects = array_reverse( $projects ) ;
                        return $projects ;
                    }
                }

                // preparring Errands table
                if( $table == "errands" ){
                    $errands = $this -> errands ;
                    if( $search ){ # Search
                        $result = $this -> searchHandling( $errands , $search ) ;
                        return $result ;
                    }elseif( $value ){
                        if( $value == "allErrands" ){ # All Errands
                            $errands = array_reverse( $errands ) ;
                            return $errands ;
                        }elseif( $value == "defferedErrands" ){ # Deffered Errands
                            $finalResult = [] ;
                            for( $i = 0 ; $i < count($errands) ; $i++ ){
                                $currentErrand = $errands[$i] ;
                                $errandStatus = $currentErrand["status"] ;
                                if( $errandStatus == "finished" || $errandStatus == "canceled" ){
                                    continue ;
                                }else{
                                    array_push( $finalResult , $currentErrand ) ;
                                }
                            }
                            $finalResult = array_reverse( $finalResult ) ;
                            return $finalResult ;
                        }
                    }elseif( $value == "canceledErrands" ){ # Canceled Errands
                        $finalResult = [] ;
                        for( $i = 0 ; $i < count($errands) ; $i++ ){
                            $currentErrand = $errands[$i] ;
                            $errandStatus = $currentErrand['status'] ;
                            if( $errandStatus == "canceled" ){
                                array_push( $finalResult , $currentErrand ) ;
                            }
                        }
                        $finalResult = array_reverse( $finalResult ) ;
                        return $finalResult ;
                    }elseif( $value == "finishedErrands" ){
                        $finalResult = [] ;
                        for( $i = 0 ; $i < count($errands) ; $i++ ){
                            $currentErrand = $errands[$i] ;
                            $errandStatus = $currentErrand['status'] ;
                            if( $errandStatus == "finished" ){
                                array_push( $finalResult , $currentErrand ) ;
                            }
                        }
                        $finalResult = array_reverse( $finalResult ) ;
                        return $finalResult ;
                    }
                }

                // preparring Suppliers table
                if( $table == "suppliers" ){
                    $suppliers = $this -> suppliers ;
                    if( $search ){
                        $result = $this -> searchHandling( $suppliers , $search ) ;
                        return $result ;
                    }else{
                        $suppliers = array_reverse( $suppliers ) ;
                        return $suppliers ;
                    }
                }

                // preparring Workers table
                if( $table == "workers" ){
                    $workers = $this -> workers ;
                    if( $search ){
                        $result = $this -> searchHandling( $workers , $search ) ;
                        return $result ;
                    }else{
                        $workers = array_reverse( $workers ) ;
                        return $workers ;
                    }
                }

                // preparring Purchases table
                if( $table == "purchases" ){
                    $purchases = $this -> purchases ;
                    if( $search ){
                        $result = $this -> searchHandling( $purchases , $search ) ;
                        return $result ;
                    }else{
                        $purchases = array_reverse( $purchases ) ;
                        return $purchases ;
                    }
                }

                // preparring Bills table
                if( $table == "bills" ){
                    $bills = $this -> bills ;
                    // Search 
                    if( $search ){ 
                        $result = $this -> searchHandling( $bills , $search ) ;
                        return $result ;
                    // Search by bill-type ( eg. select bottons )
                    }elseif( $value ){
                        if( $value == "allBills" ){
                            $bills = array_reverse( $bills ) ;
                            return $bills ;
                        }else{
                            $finalResult = [] ;
                            for( $i = 0 ; $i < count($bills) ; $i++ ){
                                $currentBill = $bills[$i] ;
                                $billType = $currentBill['billType'] ;
                                $billDate = $currentBill['date'] ;
                                if( $billType == $value){
                                    if( $from && $to ){
                                        if( $this -> compareDates($billDate,$from,$to) ){ array_push( $finalResult , $currentBill ) ; }
                                    }elseif( $from ){
                                        if( $this -> compareDates($billDate,$from) ){ array_push( $finalResult , $currentBill ) ; }
                                    }elseif( $to ){
                                        if( $this -> compareDates($billDate,null,$to) ){ array_push( $finalResult , $currentBill ) ; }
                                    }else{
                                        array_push( $finalResult , $currentBill ) ;
                                    }
                                }
                            }
                            return $finalResult ;
                        }
                    // Filter Bills by dates ( "From" => start at && "To" => end at )
                    }elseif( $from && $to ){
                        $finalResult = [] ;
                        for( $i = 0 ; $i < count($bills) ; $i++ ){
                            $currentBill = $bills[$i] ;
                            $billDate = $currentBill['date'] ;
                            if( $this -> compareDates($billDate,$from,$to) ){
                                array_push( $finalResult , $currentBill ) ;
                            }
                        }
                        return $finalResult ;
                    // Filter Bills starting "From"
                    }elseif( $from ){
                        $finalResult = [] ;
                        for( $i = 0 ; $i < count($bills) ; $i++ ){
                            $currentBill = $bills[$i] ;
                            $billDate = $currentBill['date'] ;
                            if( $this -> compareDates($billDate,$from) ){
                                array_push( $finalResult , $currentBill ) ;
                            }
                        }
                        return $finalResult ;
                    // Filter Bills ending at "To"
                    }elseif( $to ){
                        $finalResult = [] ;
                        for( $i = 0 ; $i < count($bills) ; $i++ ){
                            $currentBill = $bills[$i] ;
                            $billDate = $currentBill['date'] ;
                            if( $this -> compareDates($billDate,null,$to) ){
                                array_push( $finalResult , $currentBill ) ;
                            }
                        }
                        return $finalResult ;
                    }
                }
        
            }catch( Exception $e ){
                echo $e -> getMessage() ;
            }
            
        }
        
    }
?>