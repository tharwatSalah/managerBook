<?php
    /*
        This file is the responsible file of all logical operations , it consests of 3 classes

        1- Main class ( for logIn , and collectting data from the database , it also used as a bank of informations that are needed to be inherted from other classes )
        2- DataInsertion class ( for CREATE , MODIFY ) data in the database
        3- Home class ( it used to prepare the data before sendding into UI )
    */
    require_once "main.html" ;

    const host = "localhost" ;
    const username = "tharwat" ;
    const password = "myPassword" ;
    const dbname = "managerBook" ;

    function filterInput( $var ){
        $var = trim( $var ) ;
        $var = addslashes( $var ) ;
        $var = htmlspecialchars( $var ) ;
        $var = strip_tags( $var ) ;
        return $var ;
    }
    function refresh(){
        $currentPage = $_SERVER['PHP_SELF'] ;
        #return $currentPage ;
        header( "location:$currentPage" ) ;
    }

    class main{
        private $users ;
        #public $users ;
        public $userData ;
        public $companies ;
        public $projects ;
        public $errands ;
        public $suppliers ;
        public $workers ;
        public $purchases ;
        public $bills ;

        // Collect all users ( for login )
        function __construct(){
            try{
                $mysqli = new mysqli( host , username , password , dbname ) or die( "Failed to connect to the database!" ) ;
                $sql = "SELECT * FROM Users" ;
                if($mysqli -> real_query($sql) ){
                    $result = $mysqli -> store_result() ;
                    $result = $result -> fetch_all(MYSQLI_ASSOC) ;
                    $this -> users = $result ;
                }else{
                    throw new exception( "Sorry an error occurred!" ) ;
                }
                $mysqli -> close() ;
            }catch( Exception $e ){
                echo $e -> getMessage() ;
            }
        }

        # Log IN
        public function logIn( $username , $password ){
            try{
                $username = filterInput($username) ;
                $password = filterInput($password) ;
                // Fetch all users to check 
                if( !$allUsers = $this -> users ){
                    throw new exception( "Failed to collect Users!" ) ;
                    exit() ;
                }
                for( $i = 0 ; $i < count($allUsers) ; $i++ ){
                    if( !$currentUser = $allUsers[$i] ){
                        throw new exception( "An error occurred!" ) ;
                        // set a method to feedback error result
                        //exit() ;
                    }else{
                        $user2find = $currentUser['userName'] ;
                        $password2find = $currentUser['password'] ;
                    }
                    if( $username == $user2find && $password == $password2find ){
                        $this -> userData = $currentUser ;
                        $id = $currentUser['ID'] ;
                        $userName = $currentUser['userName'] ;
                        session_start() ;
                        if( $_SESSION['id'] = $id && $_SESSION['username'] = $userName ){
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
                                        //exit() ;
                                    }
                                    $table = $field[0] -> table ;
                                    $table = strtolower($table) ;
                                    $data = @mysqli_fetch_all( $result , MYSQLI_ASSOC ) or die( "Cannot fetch the data!" ) ;
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
                                if( header( "location:test.php" ) ){
                                    break ;
                                }
                            }else{
                                $error = $mysqli -> error_list ;
                                $err = $error[0]['error'] ;
                                throw new exception( "$err" ) ;
                                $mysqli -> close() ;
                                //exit() ;
                            }
                        }else{
                            throw new exception( "Sorry. Something is wrong!" ) ;
                        }

                    }else{
                        $feedback = "User name OR Password is incorrect!" ;
                        echo $feedback ;
                        exit() ;
                    }
                }
                //exit() ;
            }catch( Exception $e ){
                echo $e -> getMessage() ;
                exit() ;
            }
        }

    
        # Forgot Password
        public function forgotPassword( $username ){
            try{
                $username = filterInput( $username ) ;
                $allUsers = $this -> users ;
                for( $i = 0 ; $i < count($allUsers) ; $i++ ){
                    $currentUser = $allUsers[$i] ;
                    if( $username == $currentUser['userName'] ){
                        $id = $currentUser['ID'] ;
                        $userInfo = [ "userID" => $id ,  "username" => $username ] ;

                        $securityFile = $currentUser['securityQuestions'] ;
                        $securityQuestions = [] ;
                        
                        $file = fopen( "$securityFile" , 'r' ) or die( "An error Occurred!" ) ;
                        while( !feof($file) ){
                            $line = fgets( $file ) ;
                            $securityData = sscanf( $line , "%s %s %s" ) ;
                            list( $id , $question , $answer ) = $securityData ;
                            if( !empty($id) ){
                                $question = str_replace( "_" , " " , $question ) ;
                                $question = ucfirst( $question ) ;

                                $answer = str_replace( "_" , " " , $answer ) ;
                                $answer = strtolower( $answer ) ;

                                $securityInfo = [ 'id' => $id , 'question' => $question , 'answer' => $answer ] ;
                                array_push( $securityQuestions , $securityInfo ) ;
                            }
                        }
                        $result = [ "securityQuestions" => $securityQuestions , "userInfo" => $userInfo ] ;
                        return $result ;
                        break ;
                    }else{
                        throw new exception( "No Registration Found!" ) ;
                    }
                }
                exit() ;
            }catch( Exception $e ){
                echo $e -> getMessage() ;
                exit() ;
            } 
        }

    }

    $test = new main() ;
    // Log in
    if( isset($_POST['logIn']) ){
        $username = $_POST['username'] ;
        $password = $_POST['password'] ;
        $test -> logIn( $username , $password ) ;
    }
    
    /*
    // Create a new user
    $sql = "INSERT INTO USERS( ID , firstName , LastName , phone , country , address , central , governorate , birthData , securityQuestions , userName , password ) VALUES( 0 , 'tharwat' , 'salah' , '01004645524' , 'Egypt' , 'Ellith.st' , 'Elziton' , 'Cairo' , '1995/4/17' , 'security.txt' , 'tharwat' , 'tharwat' )" ;
    $mysqli = new mysqli( host , username , password , dbname ) or die( "Failed to connect to the database!" ) ;
    if( $mysqli -> query($sql) ){
        echo "New User created successfully." ;
    }else{
        echo "Failed to create a new user!" ;
    }
    $mysqli -> close() ;
    */

    #$id = 1 ;
    // Create a new Company
    # $sql = "INSERT INTO compnies( userID , name , phone , email , address , central , governorate ) VALUES( $id , 'tabark' , '0222794656' , 'tabark@example.com' , '22 Agha_khan' , 'shobra' , 'Cairo' )" ;
    
    // Create a new Project
    #$sql = "INSERT INTO projects( companyID , userID , name , address , central , governorate ) VALUES( 1 , $id , 'bdr' , 'area_301' , 'BDR' , 'New Cairo' )" ;

    // Create a new Errand
    #$sql = "INSERT INTO errands( userID , workersCount , agreed ) VALUES( $id , 2 , 200 )" ;

    // Create a new Supplier
    #$sql = "INSERT INTO suppliers( userID , name , nickName , phone , address , central , governorate ) VALUES( $id , 'mohammed' , 'sh3rawie' , '01004645524' , 'main_rouad' , 'shebien_alqanater' , 'Alqalyobia' )" ;

    // Create a new Worker
    #$sql = "INSERT INTO workers( userID , name , nickName , phone ) VALUES( $id , 'Ahmed' , 'Som3a' , '01004645524' )" ;

    // Create a new Purchases
    #$sql = "INSERT INTO purchases( supplierID , userID , price ) VALUES( 1 , $id , 20000 )" ;

    // Create a new Bill
    #$sql = "INSERT INTO bills( userID , billType , name , price ) VALUES( $id , 'food' , 'lunch' , 150 )" ;
    /*
    $mysqli = new mysqli( host , username , password , dbname ) or die( "failed to connect!" ) ;
    if( $mysqli -> query($sql) ){
        echo "New Bill registered successfully." ;
    }else{
        print_r( $mysqli -> error_list ) ;
        echo "<br>" ;
        echo "Failed to register a new Bill!" ;
    }
    $mysqli -> close() ;
    */

    /*
        $companies = $this -> companies ;
        $projects = $this -> projects ;
        $errands = $this -> errands ;
        $suppliers = $this -> suppliers ;
        $workers = $this -> workers ;
        $purchases = $this -> purchases ;
        $bills = $this -> bills ;
        $userData = $this -> userData ;
        $sysInfo = [ 'companies'=>$companies , 'projects'=>$projects , 'errands'=>$errands , 'suppliers'=>$suppliers , 'workers'=>$workers , 'purchases'=>$purchases , 'bills'=>$bills ] ;
        foreach( $sysInfo as $key => $value ){
            echo "<h3>$key Table</h3>" ;
            
            for( $i = 0 ; $i < count($value) ; $i++ ){
                echo "<table>" ;
                # table headers
                $keys = array_keys($value[$i]) ;
                echo "<tr>" ;
                for($x = 0 ; $x < count($keys) ; $x++ ){
                    echo "<th>".$keys[$x]."</th>" ;
                }
                echo "</tr>" ;
                # table data
                $row = $value[$i] ;
                echo "<tr>" ;
                foreach( $row as $values ){
                    echo "<td>$values</td>" ;
                }
                echo "</tr>" ;
            }
            echo "</table>" ;
        }
    */
?>