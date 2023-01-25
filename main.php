<?php
    require_once "core.php" ;
    trait mainTrait{
        # Search Handling
        public function searchHandling( $data , $search ){
            try{
                /*
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
                */
                if( preg_grep( "/$search.|$search/i" , $data ) ){
                    return TRUE ;
                }else{
                    return FALSE ;
                }
            }catch( exception $e ){
                return $e -> getMessage() ;
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
                        $finalResult = [] ;
                        for( $i = 0 ; $i < count($companies) ; $i++ ){
                            $current = $companies[$i] ;
                            if( $this -> searchHandling($current,$search) ){
                                array_push( $finalResult , $current ) ;
                            }elseif( $i == count($companies)-1 ){
                                if( !count($finalResult) ){
                                    throw new exception( "No Result Found!" ) ;
                                }
                            }
                        }
                        return $finalResult ;
                    }else{
                        $companies = array_reverse( $companies ) ;
                        return $companies ;
                    }
                }

                // preparring Projects table
                if( $table == "projects" ){
                    $projects = $this -> projects ;
                    if( $search ){ # Search
                        $finalResult = [] ;
                        for( $i = 0 ; $i < count($projects) ; $i++ ){
                            $current = $projects[$i] ;
                            if( $this -> searchHandling($current,$search) ){
                                array_push( $finalResult , $current ) ;
                            }elseif( $i == count($projects)-1 ){
                                if( !count($finalResult) ){
                                    throw new exception( "No Results Found!" ) ;
                                }
                            }
                        }
                        return $finalResult ;
                        #$result = $this -> searchHandling( $projects , $search ) ;
                        #return $result ;
                    }elseif( $value ){
                        if( $value == "allProjects" ){ # All Projects
                            $projects = array_reverse( $projects ) ;
                            return $projects ;
                        }elseif( $value == "currentProjects" ){ # Current Projects
                            $finalResult = [] ;
                            for( $i = 0 ; $i < count($projects) ; $i++ ){
                                $currentProject = $projects[$i] ;
                                $projectStatus = $currentProject['status'] ;
                                $projectStatus = strtolower( $projectStatus ) ;
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
                                $projectStatus = strtolower( $projectStatus ) ;
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
                                $projectStatus = strtolower( $projectStatus ) ;
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
                return $e -> getMessage() ;
            }
        }

        //==============================================================================================================================================================================================//

        # Action Methods
        # Action Methods is the responsible methods of actions like "Create & Modify" ( companies , projects , errands , suppliers , workers , purchases , bills ) 

        //==============================================================================================================================================================================================//


        # Companies Section
        #------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------#

        // Create Company
        public function createCompany( $companyName , $phone , $address , $central=NULL , $governorate=NULL , $email=NULL , $website=NULL , $commercialRegistration_NO=NULL , $taxCard_NO=NULL , $notes=NULL , $evaluation=NULL ){
            try{
                $userData = $this -> userData ;
                $companies = $this -> companies ;
                if( count($companies) ){
                    for( $i = 0 ; $i < count($companies) ; $i++ ){
                        $current = $companies[$i] ;
                        $company2check = $current['name'] ;
                        if( $companyName == $company2check ){
                            throw new exception( "Company '$companyName' already exists!" ) ;
                            break ;
                        }
                    }
                }
                

                $userID = $userData['ID'] ;
                $companyName = filterInput( $companyName ) ;
                $phone = filterInput( $phone ) ;
                $address = filterInput( $address ) ;
                if( !$companyName || !$phone || !$address ){
                    throw new exception( "Company Name and Phone and Address are required!" ) ;
                }
                $central = filterInput( $central ) ;
                $governorate = filterInput( $governorate ) ;

                $email = filter_var( $email , FILTER_SANITIZE_EMAIL ) ;
                $email = filter_var( $email , FILTER_VALIDATE_EMAIL ) ;

                // filter website
                if( empty( $website ) ){
                    $website = '' ;
                }elseif( $website ){
                    if( filterWebsite( $website ) ){
                        $website = filterWebsite( $website ) ;
                    }else{
                        throw new exception( "Invalid URL!" ) ;
                    }   
                }

                $commercialRegistration_NO = filterInput( $commercialRegistration_NO ) ;
                $taxCard_NO = filterInput( $taxCard_NO ) ;
                $notes = filterInput( $notes ) ;
                $evaluation = filterInput( $evaluation ) ;

                $str = "INSERT INTO compnies( userID , name , phone , address , central , governorate , email , website , commercialRegistration_NO , taxCard_NO , notes , evaluation ) 
                VALUES( $userID , '$companyName' , '$phone' , '$address' , '$central' , '$governorate' , '$email' , '$website' , '$commercialRegistration_NO' , '$taxCard_NO' , '$notes' , '$evaluation' )" ;

                $mysqli = new mysqli( host , username , password , dbname ) or die("Failed to connect to Database!" ) ; 
                if( $mysqli -> query($str) ){
                    $mysqli -> close() ;
                    $companyName = $phone = $address = $central = $governorate = $email = $website = $commercialRegistration_NO = $taxCard_NO = $notes = $evaluation = "" ;
                    return TRUE ;
                }else{
                    $mysqli -> close() ;
                    throw new exception( "Failed to create a new company!" ) ;
                }
            }catch( Exception $e ){
                return $e -> getMessage() ;
            }
        }

        // Modify Company
        public function modifyCompany( $companyID , $companyName=NULL , $phone=NULL , $address=NULL , $central=NULL , $governorate=NULL , $email=NULL , $website=NULL , $commercialRegistration_NO=NULL , $taxCard_NO=NULL , $notes=NULL , $evaluation=NULL){
            try{
                $companies = $this -> companies ;
                $userData = $this -> userData ;
                $userID = $userData['ID'] ;

                $targetCompany = [] ;
                $companyID = filterInput( $companyID ) ;
                if( !$companyID ){ throw new exception( "Company-ID is Required!" ) ; }
                for( $i = 0 ; $i < count($companies) ; $i++ ){
                    $current = $companies[$i] ;
                    $coID = $current['ID'] ; # company ID
                    $coID = myEncoding( $coID ) ;
                    $coU_ID = $current['userID'] ; # user ID
                    if( $coID == $companyID && $coU_ID == $userID ){
                        array_push( $targetCompany , $current ) ;
                        break ;
                    }elseif( $i == count($companies)-1 ){
                        throw new exception( "No such a company in your data!" ) ;
                    }
                }
                $company2modify = $targetCompany[0] ;
                $companyID = $company2modify['ID'] ;
                // store old values
                $o_companyName = $company2modify['name'] ;
                $o_phone = $company2modify['phone'] ;
                $o_address = $company2modify['address'] ;
                $o_central = $company2modify['central'] ;
                $o_governorate = $company2modify['governorate'] ;
                $o_email = $company2modify['email'] ;
                $o_website = $company2modify['website'] ;
                $o_commercialRegistration_NO = $company2modify['commercialRegistration_NO'] ;
                $o_taxCard_NO = $company2modify['taxCard_NO'] ;
                $o_notes = $company2modify['notes'] ;
                $o_evaluation = $company2modify['evaluation'] ;
                // validate new values
                $companyName = filterInput( $companyName ) ;
                $phone = filterInput( $phone ) ;
                $address = filterInput( $address ) ;
                $central = filterInput( $central ) ;
                $governorate = filterInput( $governorate ) ;
                $email = filter_var( $email , FILTER_SANITIZE_EMAIL ) ;
                $email = filter_var( $email , FILTER_VALIDATE_EMAIL ) ;
                // filter website
                if( empty( $website ) ){
                    $website = '' ;
                }elseif( $website ){
                    if( filterWebsite( $website ) ){
                        $website = filterWebsite( $website ) ;
                    }else{
                        throw new exception( "Invalid URL!" ) ;
                    }   
                }
                $commercialRegistration_NO = filterInput( $commercialRegistration_NO ) ;
                $taxCard_NO = filterInput( $taxCard_NO ) ;
                $notes = filterInput( $notes ) ;
                $evaluation = filterInput( $evaluation ) ;
                // modifing data
                if( !$companyName ){ $companyName = $o_companyName ; }
                if( !$phone ){ $phone = $o_phone ; }
                if( !$address ){ $address = $o_address ; }
                if( !$central ){ $central = $o_central ; }
                if( !$governorate ){ $governorate = $o_governorate ; }
                if( !$email ){ $email = $o_email ; }
                if( !$website ){ $website = $o_website ; }
                if( !$commercialRegistration_NO ){ $commercialRegistration_NO = $o_commercialRegistration_NO ; }
                if( !$taxCard_NO ){ $taxCard_NO = $o_taxCard_NO ; }
                if( !$notes ){ $notes = $o_notes ; }
                if( !$evaluation ){ $evaluation = $o_evaluation ; }
                // update the database
                $sql = "UPDATE compnies SET name='$companyName',phone='$phone',address='$address',central='$central',governorate='$governorate',email='$email',website='$website',commercialRegistration_NO='$commercialRegistration_NO',taxCard_NO='$taxCard_NO',notes='$notes',evaluation='$evaluation' WHERE ID = '$companyID' " ;
                $mysqli = new mysqli( host , username , password , dbname ) or die( "Database connection Failed!" ) ;
                if( !$mysqli -> query($sql) ){
                    $mysqli -> close() ;
                    throw new exception( "Failed to modify '$companyName' company data!" ) ;
                }else{
                    $mysqli -> close() ;
                    return TRUE ;
                }
                
            }catch( Exception $e ){
                return $e -> getMessage() ;
            }
            
        }


        //_________________________________________________________________________________________________________________________________________________________________________________________________//
        # End Of Companies

        # Start with Projects Section
        //_________________________________________________________________________________________________________________________________________________________________________________________________//


        # Projects Section
        #------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------#

        // Create A Project
        public function createProject( $companyName , $name , $address=null , $central=null , $governorate=null , $specifications=null , $status=null , $deliveryDate=null , $agreed=null , $paymentDate=null , $amount=null , $paymentType=NULL , $paymentNotes=null , $photo=FALSE , $design=null , $notes=null , $evaluation=null ){
            try{
                $userData = $this -> userData ;
                $companyName = filterInput( $companyName ) ;
                $companyName = strtolower( $companyName ) ;
                $companies = $this -> companies ;
                $companyID = "" ;
                for( $i = 0 ; $i < count($companies) ; $i++ ){
                    $current = $companies[$i] ;
                    $cN = $current['name'] ;
                    $cN = strtolower( $cN ) ;
                    if( $cN == $companyName ){
                        $companyID = $current['ID'] ;
                        break ;
                    }elseif( $i == count($companies)-1 ){
                        throw new exception( "Company '$companyName' Not Found!" ) ;
                    }
                }
                // search for If a project exists
                $projects = $this -> projects ;
                $name = filterInput( $name ) ;
                $name = strtolower( $name ) ;
                for( $i = 0 ; $i < count($projects) ; $i++ ){
                    $current = $projects[$i] ;
                    $pName = $current['name'] ;
                    $pName = strtolower( $pName ) ;
                    if( $pName == $name ){
                        throw new exception( "Project '$name' already exists!" ) ;
                        break ;
                    }
                }
                
                $userID = $userData['ID'] ;
                
                $address = filterInput( $address ) ;
                $central = filterInput( $central ) ;
                $governorate = filterInput( $governorate ) ;
                $specifications = filterInput( $specifications ) ;
                $status = filterInput( $status ) ;

                $deliveryDate = filterInput( $deliveryDate ) ;
                if( $deliveryDate ){
                    $deliveryDate = str_replace( "/" , " " , $deliveryDate ) ;
                    $deliveryDate = sscanf( $deliveryDate , "%s %s %s" ) ;
                    list( $year , $month , $day ) = $deliveryDate  ;
                    if( !checkdate( $month , $day , $year ) ){
                        throw new exception( "Invalid Date!" ) ;
                    }
                    $deliveryDate = "$year/$month/$day" ;
                }

                $agreed = filterInput( $agreed ) ;

                // payments
                $paymentDate = filterInput( $paymentDate ) ;
                $paymentType = filterInput( $paymentType ) ;
                $amount = filterInput( $amount ) ;
                $paymentNotes = filterInput( $paymentNotes ) ;
                
                $photo = filterImage( $photo ) ;
                if( !is_file($photo) ){
                    $photo = "" ;
                }
                
                $design = filterImage( $design ) ;
                if( !is_file($design) ){
                    $design = "" ;
                }

                $notes = filterInput( $notes ) ;
                $evaluation = filterInput( $evaluation ) ;

                $specificationsFile = "src/".uniqid( "speci" ) ; // create a specification file
                $paymentsFile = "src/".uniqid( "pay" ) ; // create a payments file
                $additionAndSubtractionFile = "src/".uniqid( "aAs" ) ; // create an additionAndSubtraction file

                // validate Specifications
                if( $specifications ){
                    /*
                        # specifications file is a file to store the project's details in format ( id , quantity , demand , description ) each line of the file is considered as a "request"
                        # Examples( demand=[windows,doors,side_walk,etc...] , quantity=[3,15,etc..] , description=[hight:1.5m,width:3m,color:blak,etc....] ) 

                        I will delay that because of personal reasons that related to working life , beside i will need some of front-end knowledge
                        So i'll enough with storing the data into the file directly for now.

                        * Every thing is descriped in "databaseExplenation.txt" file
                    */
                    $file = fopen( "$specificationsFile" , "w" ) ;
                    fwrite( $file , $specifications ) ;
                    fclose( $file ) ;
                    #clearstatcache() ;
                }

                // validate payments
                if( $amount ){
                    if( $paymentDate ){
                        $paymentDate = str_replace( "/" , " " , $paymentDate ) ;
                        $paymentDate = sscanf( $paymentDate , "%s %s %s" ) ;
                        list( $year , $month , $day ) = $paymentDate ;
                        if( !checkdate( $month , $day , $year ) ){
                            throw new exception( "Invalid Payment Date!" ) ;
                        }else{
                            $paymentDate = "$year/$month/$day" ;
                        }
                        
                    }
                    $paymentID = uniqid( "pays" ) ;
                    # if( strlen($paymentID) < 35 ){ $paymentID = str_pad( $paymentID , 35 , "_" , STR_PAD_RIGHT ) ; }
                    # if( strlen($paymentDate) < 10 ){ $paymentDate = str_pad( $paymentDate , 10 , "_" , STR_PAD_RIGHT ) ; }
                    # if( strlen($amount) < 6 ){ $amount = str_pad( $amount , 6 , "_" , STR_PAD_RIGHT ) ; }
                    # if( strlen($paymentType) < 10 ){ $amount = str_pad( $paymentType , 10 , "_" , STR_PAD_RIGHT ) ; }
                    # if( strlen($paymentNotes) < 250 ){ $paymentNotes = str_pad($paymentNotes , 250 , "_" , STR_PAD_RIGHT) ; }
                    $line = "$paymentID $paymentDate $amount $paymentType $paymentNotes \n" ;
                    if( $file = @fopen( "$paymentsFile" , "w" ) ){
                        fwrite( $file , $line ) ;
                        fclose( $file ) ;
                    }else{
                        throw new exception( "Invalid Payment" ) ;
                    }
                    
                }


                $sql = "INSERT INTO projects( companyID , userID , name , address , central , governorate , specifications , status , deliveryDate , agreed , payments , additionAndSubtraction , photos , design , notes , evaluation ) 
                VALUES( $companyID , $userID , '$name' , '$address' , '$central' , '$governorate' , '$specificationsFile' , '$status' , '$deliveryDate' , '$agreed' , '$paymentsFile' , '$additionAndSubtractionFile' , '$photo' , '$design' , '$notes' , '$evaluation' )" ;
                
                $mysqli = new mysqli( host , username , password , dbname ) or die( "Database Connection Failed!" ) ;
                if( $mysqli -> query($sql) ){
                    $mysqli -> close() ;
                    $companyID = $userID = $name = $address = $central = $governorate = $specificationsFile = $status = $deliveryDate = $agreed = $paymentsFile = $additionAndSubtractionFile = $photo = $design = $notes = $evaluation = "" ;
                    return TRUE ;
                }else{
                    $mysqli -> close() ;
                    throw new exception( "Failed to Create a Project!" ) ;
                }

            }catch( Exception $e ){
                return $e -> getMessage() ;
            }
        }


        // Modify a Project ( 'only modify' : Boxes that are not related to financial matters , OR project status )
        public function modifyProject( $projectID , $name=null , $address=null , $central=null , $governorate=null , $deliveryDate=null , $status=null , $photos=null , $design=null , $notes=null , $evaluation=null ){
            try{
                $o_name = "" ;
                $o_address = "" ;
                $o_central = "" ;
                $o_governorate = "" ;
                $o_deliveryDate = "" ;
                $o_status = "" ;
                $o_photos = "" ;
                $o_design = "" ;
                $o_notes = "" ;
                $o_evaluation = "" ;

                $projectID = filterInput( $projectID ) ;
                if( !$projectID ){
                    throw new exception( "Please select a project!" ) ;
                }
                $projects = $this -> projects ;
                for( $i = 0 ; $i < count($projects) ; $i++ ){
                    $current = $projects[$i] ;
                    $proID = $current['ID'] ;
                    if( $proID == $projectID ){
                        $o_name = $current['name'] ;
                        $o_address = $current['address'] ;
                        $o_central = $current['central'] ;
                        $o_governorate = $current['governorate'] ;
                        $o_deliveryDate = $current['deliveryDate'] ;
                        $o_status = $current['status'] ;
                        $o_photos = $current['photos'] ;
                        $o_design = $current['design'] ;
                        $o_notes = $current['notes'] ;
                        $o_evaluation = $current['evaluation'] ;
                        break ;
                    }elseif( $i == count($projects)-1 ){
                        throw new exception( "Project Not Found!" ) ;
                    }
                }

                $name = filterInput( $name ) ;
                $address = filterInput( $address ) ;
                $central = filterInput( $central ) ;
                $governorate = filterInput( $governorate ) ;
                $deliveryDate = filterInput( $deliveryDate ) ;
                $status = filterInput( $status ) ;
                $photos = filterImage( $photos ) ;
                $design = filterImage( $design ) ;
                $notes = filterInput( $notes ) ;
                $evaluation = filterInput( $evaluation ) ;

                if( !$name ){ $name = $o_name ; }
                if( !$address ){ $address = $o_address ; }
                if( !$central ){ $central = $o_central ; }
                if( !$governorate ){ $governorate = $o_governorate ; }
                if( $deliveryDate ){
                    print_r( $deliveryDate ) ;
                    $deliveryDate = str_replace("/" , " " , $deliveryDate ) ;
                    $deliveryDate = sscanf( $deliveryDate , "%s %s %s" ) ;
                    list( $year , $month , $day ) = $deliveryDate ;
                    if( !@checkdate( $month , $day , $year ) ){
                        throw new exception( "Invalid Date Format!" ) ;
                    }else{
                        $deliveryDate = "$year/$month/$day" ;
                    }
                }else{
                    $deliveryDate = $o_deliveryDate ;
                }
                if( !$status ){ $status = $o_status ; }
                if( !$photos ){ $photos = $o_photos ; }
                if( !$design ){ $design = $o_design ; }
                if( !$notes ){ $notes = $o_notes ; }
                if( !$evaluation ){ $evaluation = $o_evaluation ; }

                $sql = "UPDATE projects SET name = '$name' , address = '$address' , central = '$central' , governorate = '$governorate' , deliveryDate = '$deliveryDate' , status = '$status' , photos = '$photos' , design = '$design' , notes = '$notes' , evaluation = '$evaluation' WHERE ID = '$projectID' " ;
                $mysqli = new mysqli( host , username , password , dbname ) or die( "Database connection failed!" ) ;
                if( $mysqli -> query($sql) ){
                    $mysqli -> close() ;
                    $projectID = $name = $address = $central = $governorate = $deliveryDate = $photos = $design = $notes = $evaluation = "" ;
                    $h = $_SERVER['PHP_SELF'] ;
                    header( "refresh:3 ; url=$h" ) ;
                    return TRUE ;
                }else{
                    $err = $mysqli -> error ;
                    $mysqli -> close() ;
                    throw new exception( "Update Failed! . $err" ) ;
                }

            }catch( exception $e ){
                return $e -> getMessage() ;
            }
        }

        // Receive a New Payment
        public function receiveNewPayment( $id , $date , $amount , $paymentType , $notes=null ){
            try{
                if( !( $id || $date || $amount || $paymentType ) ){
                    throw new exception( "Please fill all Data!" ) ;
                }
                if( !$id ){ throw new exception( "Please sellect a project!" ) ; }
                $id = filterInput( $id ) ;
                $date = filterInput( $date ) ;
                $amount = filterInput( $amount ) ;
                $paymentType = filterInput( $paymentType ) ;
                $notes = filterInput( $notes ) ;

                $projects = $this -> projects ;
                $paymentFile = "" ;
                for( $i = 0 ; $i < count($projects) ; $i++ ){
                    $current = $projects[$i] ;
                    $projectID = $current['ID'] ;
                    if( $projectID == $id ){
                        $paymentFile = $current['payments'] ;
                        break ;
                    }elseif( $i == count($projects)-1 ){
                        throw new exception( "Payment Registration Failed!" ) ;
                    }
                }
                $paymentID = uniqid( "pays" ) ;
                $Pdate = str_replace( "/" , " " , $date ) ;
                $Pdate = sscanf( $Pdate , "%s %s %s" ) ;
                list( $year , $month , $day ) = $Pdate ;
                if( !checkdate( $month , $day , $year ) ){
                    throw new exception( "Invalid Date Format!" ) ;
                }
                if( $notes ){
                    $notes = nl2br( $notes , FALSE ) ;
                    $notes = str_replace( " " , "_" , $notes ) ;
                }
                $amount = @intval( $amount ) ;
                if( @$amount < 0 ){
                    throw new exception( "Negative Values are Not Allowed!" ) ;
                }
                
                $paymentID = str_replace( " " , "_" , $paymentID ) ;
                $date = str_replace( " " , "_" , $date ) ;
                $amount = str_replace( " " , "_" , $amount ) ;
                $paymentType = str_replace( " " , "_" , $paymentType ) ;
                $notes = bin2hex( $notes ) ;

                $line = "$paymentID $date $amount $paymentType $notes \n" ;
                if( $file = @fopen( "$paymentFile" , "a" ) ){
                    fwrite( $file , $line ) ;
                    fclose( $file ) ;
                    return TRUE ;
                }else{
                    throw new exception( "Failed to open Payments file!" ) ;
                }
                
            }catch( Exception $e ){
                return $e -> getMessage() ;
            }
            
        }

        // Preview all Payments
        public function previewAllPayments( $id ){
            try{
                $id = filterInput( $id ) ;
                $projects = $this -> projects ;
                $paymentFile = "" ;
                if( count($projects) ){
                    for( $i = 0 ; $i < count($projects) ; $i++ ){
                        $current = $projects[$i] ;
                        $projectID = $current['ID'] ;
                        if( $projectID == $id ){
                            $paymentFile = $current['payments'] ;
                            break ;
                        }elseif( $i == count($projects)-1 ){
                            throw new exception( "Cannot find Payments!" ) ;
                        }
                    }
                    if( $file = @fopen("$paymentFile" , "r" ) ){
                        $finalResult = [] ;
                        while( $line = fgets( $file ) ){
                            $line = sscanf( $line , "%s %s %s %s %s" ) ;
                            list( $paymentID , $paymentDate , $paymentAmount , $paymentType , $paymentNotes ) = $line ;
                            $paymentID = str_replace( "_" , " " , $paymentID ) ;
                            $paymentDate = str_replace( "_" , " " , $paymentDate ) ;
                            $paymentAmount = str_replace( "_" , " " , $paymentAmount ) ;
                            $paymentType = str_replace( "_" , " " , $paymentType ) ;

                            $paymentNotes = hex2bin( $paymentNotes ) ;
                            $paymentNotes = str_replace( "_" , " " , $paymentNotes ) ;
                            $paymentNotes = stripslashes( $paymentNotes ) ;

                            if( $paymentID ){
                                $x = [ "date" => $paymentDate , "amount" => $paymentAmount , "paymentType" => $paymentType , "notes" => $paymentNotes ] ;
                                array_push( $finalResult , $x ) ;
                            }
                        }
                        fclose( $file ) ;
                        return $finalResult ;
                    }else{
                        throw new exception( "Failed to Read Payments!" ) ;
                    }
                }else{
                    return FALSE ;
                }
                
            }catch( Exception $e ){
                return $e -> getMessage() ;
            } 
        }

        // Modify Specifications File
        public function modifyProjectSpecifications( $id , $content ){
            try{
                $id = filterInput( $id ) ;
                $content = filterInput( $content ) ;
                if( !$id ){ throw new exception( "Please select a Project!" ) ; }
                
                $projects = $this -> projects ;
                if( !count($projects) ){
                    return FALSE ;
                }
                $specifications = "" ;
                for( $i = 0 ; $i < count($projects) ; $i++ ){
                    $current = $projects[$i] ;
                    $projectID = $current['ID'] ;
                    if( $projectID == $id ){
                        $specifications = $current['specifications'] ;
                        break ;
                    }elseif( $i == count($projects)-1 ){
                        throw new exception( "Specifications File is not exists!" ) ;
                    }
                }
                if( $file = fopen($specifications , "w") ){
                    fwrite( $file , $content ) ;
                    fclose( $file ) ;
                    return TRUE ;
                }else{
                    throw new exception( "Failed to modify!" ) ;
                }
            }catch( Exception $e ){
                return $e -> getMessage() ;
            }
        }

        // Preview Specifications File
        public function viewProjectsSpecifications( $id ){
            try{
                $id = filterInput( $id ) ;
                if( !$id ){ throw new exception( "Please select a Project!" ) ; }
                
                $projects = $this -> projects ;
                if( !count($projects) ){ return FALSE ; }
                $specificationsFile = "" ;
                for( $i = 0 ; $i < count($projects) ; $i++ ){
                    $current = $projects[$i] ;
                    $projectID = $current['ID'] ;
                    if( $projectID == $id ){
                        $specificationsFile = $current['specifications'] ;
                        break ;
                    }elseif( $i == count($projects)-1 ){
                        throw new exception( "Cannot find specification file!" ) ;
                    }
                }
                if( is_file($specificationsFile) ){
                    return $specificationsFile ;
                }else{
                    return FALSE ;
                }

            }catch( Exception $e ){
                return $e -> getMessage() ;
            }
        }

        // Setting Additions/Subtractions
        public function setProjectAdditions( $id , $amount , $operationType , $date , $notes=null ){
            try{
                $id = filterInput( $id ) ;
                $amount = filterInput( $amount ) ;
                $operationType = filterInput( $operationType ) ;
                $date = filterInput( $date ) ;
                $notes = filterInput( $notes ) ;

                $amount = intval( $amount ) ;
                if( $amount < 0 ){
                    throw new exception( "Negative Values are Not Allowed!" ) ;
                }
                
                if( !$date ){
                    throw new exception( "Date is Required!" ) ;
                }else{
                    $Pdate = str_replace( "/" , " " , $date ) ;
                    $Pdate = sscanf( $Pdate , "%s %s %s" ) ;
                    list( $year , $month , $day ) = $Pdate ;
                    if( !@checkdate( $month , $day , $year ) ){
                        throw new exception( "Invalid Date Format!" ) ;
                    }
                }

                $projects = $this -> projects ;
                $targetFile = "" ;
                if( !count($projects) ){ return FALSE ; }
                for( $i = 0 ; $i < count($projects) ; $i++ ){
                    $current = $projects[$i] ;
                    $projectID = $current['ID'] ;
                    if( $projectID == $id ){
                        $targetFile = $current['additionAndSubtraction'] ;
                        break ;
                    }elseif( $i == count($projects)-1 ){
                        throw new exception( "No Results Found!" ) ;
                    }
                }
                $operationID = uniqid() ;

                $operationID = str_replace( " " , "_" , $operationID ) ;
                $amount = str_replace( " " , "_" , $amount ) ;
                $operationType = str_replace( " " , "_" , $operationType ) ;
                $date = str_replace( " " , "_" , $date ) ;
                if( $notes ){
                    $notes = nl2br( $notes , FALSE ) ;
                    $notes = str_replace( " " , "_" , $notes ) ;
                    $notes = bin2hex( $notes ) ;
                }

                $line = "$operationID $amount $operationType $date $notes \n" ;
                if( $file = fopen( "$targetFile" , "a" ) ){
                    if( fwrite( $file , $line ) ){
                        fclose( $file ) ;
                        return TRUE ;
                    }
                }else{
                    return FALSE ;
                }

            }catch( Exception $e ){
                return $e -> getMessage() ;
            }
            
        }

        // Getting addition/subtraction
        public function getProjectAdditions( $id ){
            try{
                $id = filterInput( $id ) ;
                if( !$id ){ throw new exception( "Please select a project!" ) ; }

                $projects = $this -> projects ;
                if( !count($projects) ){ return FALSE ; }
                $targetFile = "" ;
                for( $i = 0 ; $i < count($projects) ; $i++ ){
                    $current = $projects[$i] ;
                    $projectID = $current['ID'] ;
                    if( $projectID == $id ){
                        $targetFile = $current['additionAndSubtraction'] ;
                        break ;
                    }elseif( $i == count($projects)-1 ){
                        throw new exception( "No Results Found!" ) ;
                    }
                }
                if( $file = fopen( "$targetFile" , "r" ) ){
                    $finalResult = [] ;
                    while( $line = fgets( $file ) ){
                        $line = sscanf( $line , "%s %s %s %s %s" ) ;
                        list( $operationID , $amount , $operationType , $date , $notes ) = $line ;
                        if( $operationID ){
                            $operationID = str_replace( "_" , " " , $operationID ) ;
                            $amount = str_replace( "_" , " " , $amount ) ;
                            $operationType = str_replace( "_" , " " , $operationType ) ;
                            $date = str_replace( "_" , " " , $date ) ;
                            if( $notes = hex2bin($notes) ){
                                $notes = str_replace( "_" , " " , $notes ) ;
                                $notes = stripslashes( $notes ) ;
                            }else{
                                $notes = str_replace( "_" , " " , $notes ) ;
                                $notes = stripslashes( $notes ) ;
                            }
                            $x = [ "amount" => $amount , "operationType" => $operationType , "date" => $date , "notes" => $notes ] ;
                            array_push( $finalResult , $x ) ;
                        }
                    }
                    fclose( $file ) ;
                    if( count($finalResult) ){
                        return $finalResult ;
                    }else{
                        return FALSE ;
                    }
                    
                }else{
                    return FALSE ;
                }
            }catch( Exception $e ){
                return $e -> getMessage() ;
            }
            
        }


    }

    
?>