<?php
    /*
        This file is the responsible file of all logical operations , it consests of 3 classes

        1- Main class ( for logIn , and collectting data from the database , it also used as a bank of informations that are needed to be inherted from other classes )
        2- DataInsertion class ( for CREATE , MODIFY ) data in the database
        3- Home class ( it used to prepare the data before sendding into UI )
    */

    const host = "localhost" ;
    const username = "tharwat" ;
    const password = "myPassword" ;
    const dbname = "managerBook" ;

    // User Input Filteration
    function filterInput( $var ){
        $var = trim( $var ) ;
        $var = addslashes( $var ) ;
        $var = htmlspecialchars( $var ) ;
        $var = strip_tags( $var ) ;
        return $var ;
    }

    // Website Filteration
    function filterWebsite( $website=FALSE ){
        if( strlen($website) <= 1 ){
            return FALSE ;
        }else{
            $website = filterInput( $website ) ;
            $website = filter_var( $website , FILTER_SANITIZE_URL ) ;
            $website = filter_var( $website , FILTER_VALIDATE_URL , FILTER_FLAG_HOSTNAME ) ;
            $urls = [ "http://www." , "https://www." ] ; // URLs to be scanned
            for( $i = 0 ; $i < count($urls) ; $i++ ){
                if( stristr($website,$urls[$i]) ){
                    // $startpos = stripos( $website , $urls[$i] ) ;
                    $len = strlen( $urls[$i] ) ;
                    $host = substr( $website , $len ) ;
                    if( @dns_check_record($host) ){
                        return $website ;
                        break ;
                    }
                }
            }
        }  
    }

    // Images Filteration
    function filterImage( $img ){
        if( $img ){
            try{
                // target a path to save the img
                $targetDir = "imgs/" ;
                $targetFile = $targetDir.uniqid('img',TRUE).basename($img['name']) ; #$targetFile = $targetDir.basename( $img['name'] ) ;
                $targetPath = TRUE ;
                // check if image file is an actual image
                $check = @getimagesize( $img['tmp_name'] ) ;
                # $check = $img['type'] ;
                if( $check !== FALSE ){
                    $checkFile = TRUE ;
                }else{
                    $checkFile = FALSE ;
                    throw new exception( "Only images are allowed!" ) ;
                }
                // check if image file is exists
                if( file_exists( $targetFile) ){
                    $upload = FALSE ;
                    throw new exception( "File already exists!" ) ;
                }else{
                    $upload = TRUE ;
                }
                // check image size ( 1mb )
                if( $img['size'] > 1000000 ){
                    $fileSize = FALSE ;
                    throw new exception( "Image is too large (only 1MB size allwed)!" ) ;
                }else{
                    $fileSize = TRUE ;
                }
                // allow certain formats
                $imgExtension = pathinfo( $targetFile , PATHINFO_EXTENSION ) ;
                $imgExtension = strtolower( $imgExtension ) ;
                if( $imgExtension != "jpg" && $imgExtension != "png" && $imgExtension != "jpeg" && $imgExtension != "gif" ){
                    $fileFormat = FALSE ;
                    throw new exception( "only JPG , JPEG , PNG & GIF files are allowed!" ) ;
                }else{
                    $fileFormat = TRUE ;
                }
                // check if all CHECKs are TRUE
                if( $targetPath && $checkFile && $upload && $fileSize && $fileFormat ){
                    if( !move_uploaded_file( $img['tmp_name'] , $targetFile ) ){
                        throw new exception( "Failed to Upload the img!" ) ;
                    }else{
                        return $targetFile ;
                    }
                }else{
                    throw new exception( "Image processing failed!" ) ;
                }
            }catch( Exception $e ){
                return $e -> getMessage() ;
            }
        }
    }

    # Prepare Results 
    function prepareResult( $result ){
        $finalResult = [] ;
        for( $i = 0 ; $i < count($result) ; $i++ ){
            $myResult = fn( &$value , $key ) => $value = stripslashes( $value ) ;
            $current = $result[$i] ;
            array_walk( $current , $myResult ) ;
            array_push( $finalResult , $current ) ;
        }
        return $finalResult ;
    }
    
    # Encoding results
    function myEncoding( $value ){
        if( CRYPT_SHA512 ){
            $salt = '$6$rounds=5000$PzAwMosFrtTHnxQl' ;
            $value = crypt( $value , $salt ) ;
        }elseif( CRYPT_SHA256 ){
            $salt = '$5$rounds=5000$BrxQsTorTSmL' ;
            $value = crypt( $value , $salt ) ;
        }elseif( !CRYPT_BLOWFISH ){
            $salt = '$2a$09$AoxUigikuyQwAplMeTHEaq' ;
            $value = crypt( $value , $salt ) ;
        }elseif( CRYPT_MD5 ){
            $salt = '$1$TGaNmoeirLLp' ;
            $value = crypt( $value , $salt ) ;
        }
        return $value ;
    }

    trait newRegisteration{
        public function newUserGreeding( $firstname , $lastname ){
            $firstname = stripslashes( $firstname ) ;
            $lastname = stripslashes( $lastname ) ;
            echo "Welcome $firstname $lastname.<br> Now you have an acount in ManagerBook.<br> You can do your work tasks easly." ;
        }
    }
    class main{
        use newRegisteration ;
        private $users ;

        # Collect all users ( for login )
        function __construct(){
            try{
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
            }catch( Exception $e ){
                $err = $e -> getMessage() ;
                header( "HTTP/1.0 404 $err" ) ;
                exit() ;
            }
        }
        
        # Log IN
        public function logIn( $username , $password ){
            try{
                $username = filterInput($username) ;
                $username = stripslashes( $username ) ;

                $password = filterInput($password) ;
                $password = stripslashes( $password ) ;
                // Fetch all users to check 
                $allUsers = $this -> users ;

                if( empty($username) || empty($password) ){
                    throw new exception( "User name AND Password are Required!" ) ;
                }else{ 
                    if( !$allUsers ){
                        echo "No Users found from Login!<br>" ;
                    }

                    for( $i = 0 ; $i < count($allUsers) ; $i++ ){
                        if( !$currentUser = $allUsers[$i] ){
                            // set a method to feedback error result
                            throw new exception( "An error occurred!" ) ;
                        }else{
                            $user2find = $currentUser['userName'] ;
                            $password2find = $currentUser['password'] ;
                        }
                        if( $username == $user2find && $password == $password2find ){
                            $username = myEncoding( $username ) ;
                            $password = myEncoding( $password ) ;

                            session_start() ;
                            $_SESSION['username'] = $username ;
                            $_SESSION['password'] = $password ;
                            header( "location:ui/test.php" ) ;
                        }elseif( $i == count( $allUsers )-1 ){
                            throw new exception( "User name OR Password is incorrect!<br>" ) ;
                        }
                    }
                }
                exit() ;
            }catch( Exception $e ){
                $result = $e -> getMessage() ;
                return $result ;
                exit() ;
            }
        }
        
    
        # Forgot Password
        public function forgotPassword( $username ){
            try{
                $username = filterInput( $username ) ;
                $username = stripslashes( $username ) ;

                $allUsers = $this -> users ;
                for( $i = 0 ; $i < count($allUsers) ; $i++ ){
                    $currentUser = $allUsers[$i] ;
                    if( $username == $currentUser['userName'] ){
                        $id = $currentUser['ID'] ;
                        $userInfo = [ "userID" => $id ,  "username" => $username ] ;

                        $securityFile = $currentUser['securityQuestions'] ;
                        $securityQuestions = [] ;
                        
                        $file = @fopen( "$securityFile" , 'r' ) or die( "You did not set up the security file!" ) ;
                        while( !feof($file) ){
                            $line = fgets( $file ) ;
                            $securityData = sscanf( $line , "%s %s %s" ) ;
                            list( $id , $question , $answer ) = $securityData ;
                            if( !empty($id) ){
                                $question = str_replace( "_" , " " , $question ) ;
                                $question = ucfirst( $question ) ;

                                $answer = str_replace( "_" , " " , $answer ) ;
                                $answer = strtolower( $answer ) ;
                                $answer = trim( $answer ) ;

                                $securityInfo = [ 'id' => $id , 'question' => $question , 'answer' => $answer ] ;
                                array_push( $securityQuestions , $securityInfo ) ;
                            }
                        }
                        $result = [ "securityQuestions" => $securityQuestions , "userInfo" => $userInfo ] ;
                        return $result ;
                        break ;
                    }
                    if( $i == count($allUsers)-1 ){
                        throw new exception( "No Registration Found!" ) ;
                    }
                }
                exit() ;
            }catch( Exception $e ){
                return $e -> getMessage() ;
                exit() ;
            } 
        }

        # Create a User
        public function createUser(
            $firstname , $lastname , $phone , $country , $address , $central , $governorate , $birthDate , $nationalID , $jobTitle , $gender , $status , 
            $entityType , $entityName , $entityPhone , $entityAddress , $entityCentral , $entityGovernorate , $stablishmentDate , $commercialRegistration_NO , $taxCard_NO , $bio , 
            $website , $email , $username , $password , $profilePhoto , $profileBackground , 
            $q1 , $q2 , $q3 , $q4 , $q5 
        )
        {
            try{
                
                $firstname = filterInput( $firstname ) ;
                $lastname = filterInput( $lastname ) ;
                $phone = filterInput( $phone ) ;
                $country = filterInput( $country ) ;
                $address = filterInput( $address ) ;
                $central = filterInput( $central ) ;
                $governorate = filterInput( $governorate ) ;
                $birthDate = filterInput( $birthDate ) ;
                $nationalID = filterInput( $nationalID ) ;
                $jobTitle = filterInput( $jobTitle ) ;
                $gender = filterInput( $gender ) ;
                $status = filterInput( $status ) ;

                $entityType = filterInput( $entityType ) ;
                $entityName = filterInput( $entityName ) ;
                $entityPhone = filterInput( $entityPhone ) ;
                $entityAddress = filterInput( $entityAddress ) ;
                $entityCentral = filterInput( $entityCentral ) ;
                $entityGovernorate = filterInput( $entityGovernorate ) ;
                $stablishmentDate = filterInput( $stablishmentDate ) ;
                $commercialRegistration_NO = filterInput( $commercialRegistration_NO ) ;
                $taxCard_NO = filterInput( $taxCard_NO ) ;
                $bio = filterInput( $bio ) ;

                
                // filter a username
                if( empty($username) ){
                    throw new exception( "User Name is required!" ) ;
                }else{
                    $username = filterInput( $username ) ;
                    // Check if a Username already exists
                    $allUsers = $this -> users ;
                    for( $i = 0 ; $i < count($allUsers) ; $i++ ){
                        $currentUser = $allUsers[$i] ;
                        $user2find = filterInput($currentUser['userName']) ;
                        if( $user2find == $username ){
                            throw new exception( "User Name already exists!" ) ;
                            break ;
                        }
                    }
                }
                
                
                // filter a password
                if( empty($password) ){
                    throw new exception( "Password is required!" ) ;
                }elseif( strlen($password) < 8 || strlen($password) > 12 ){
                    throw new exception( "Password must be less than 12 and greater than 8 letters and digits and symbols!" ) ;
                }else{
                    $password = filterInput( $password ) ;
                }

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
                // filter profile image
                $profilePhoto = filterImage( $profilePhoto ) ;
                if( !is_file($profilePhoto) ){
                    $profilePhoto = "" ;
                }
                // filter background
                $profileBackground=filterImage( $profileBackground ) ;
                if( !is_file($profileBackground) ){
                    $profileBackground = "" ;
                }
                
                $email = filter_var( $email , FILTER_SANITIZE_EMAIL ) ;
                $email = filter_var( $email , FILTER_VALIDATE_EMAIL ) ;
                $securityQuestions = "src/".uniqid( "sec" ).".txt" ;
                
                $registrationDate = date( "Y-m-d" , time() ) ; 

                // Security Questions and Answers handling
                $q1 = filterInput( $q1 ) ;
                $q2 = filterInput( $q2 ) ;
                $q3 = filterInput( $q3 ) ;
                $q4 = filterInput( $q4 ) ;
                $q5 = filterInput( $q5 ) ;

                $q1 = strtolower( $q1 ) ;
                $q2 = strtolower( $q2 ) ;
                $q3 = strtolower( $q3 ) ;
                $q4 = strtolower( $q4 ) ;
                $q5 = strtolower( $q5 ) ;

                $q1 = str_replace( " " , "_" , $q1 ) ;
                $q2 = str_replace( " " , "_" , $q2 ) ;
                $q3 = str_replace( " " , "_" , $q3 ) ;
                $q4 = str_replace( " " , "_" , $q4 ) ;
                $q5 = str_replace( " " , "_" , $q5 ) ;

                function answerHandling( $value ){
                    if( !$value ){
                        return "" ;
                    }elseif( strlen($value) > 100 ){
                        throw new exception( "Max limit is 100 chars!" ) ;
                    }else{
                        $len = strlen( $value ) ;
                        $max = 100 ;
                        $length = $max - $len ;
                        return str_pad( $value , $length , "_" , STR_PAD_RIGHT ) ;
                    }
                }

                $q1 = answerHandling( $q1 ) ;
                $q2 = answerHandling( $q2 ) ;
                $q3 = answerHandling( $q3 ) ;
                $q4 = answerHandling( $q4 ) ;
                $q5 = answerHandling( $q5 ) ;

                $question1 = str_replace( " " , "_" , "What is your First Job ?" ) ;
                $question2 = str_replace( " " , "_" , "What is first Company you worked at ?" ) ;
                $question3 = str_replace( " " , "_" , "What is your best Friend Name ?" ) ;
                $question4 = str_replace( " " , "_" , "What is the last five digits in your national ID(from right) ?" ) ;
                $question5 = str_replace( " " , "_" , "What is the name of your primary school ?" ) ;

                $qid1 = uniqid( "que" , TRUE ) ;
                $qid2 = uniqid( "que" , TRUE ) ;
                $qid3 = uniqid( "que" , TRUE ) ;
                $qid4 = uniqid( "que" , TRUE ) ;
                $qid5 = uniqid( "que" , TRUE ) ;

                // SQL Statement
                $sql = "INSERT INTO users(
                    firstname , lastname , phone , country , address , central , governorate , birthData , nationalID , jobTitle , gender , status , 
                    entityType , entityName , entityPhone , entityAddress , entityCentral , entityGovernorate , stablishmentDate , commercialRegistration_NO , taxCard_NO , bio ,
                    userName , password , website , email , securityQuestions , registrationDate , profilePhoto , profileBackground
                )VALUES(
                    '$firstname' , '$lastname' , '$phone' , '$country' , '$address' , '$central' , '$governorate' , '$birthDate' , '$nationalID' , '$jobTitle' , '$gender' , '$status' ,
                    '$entityType' , '$entityName' , '$entityPhone' , '$entityAddress' , '$entityCentral' , '$entityGovernorate' , '$stablishmentDate' , '$commercialRegistration_NO' , '$taxCard_NO' , '$bio' ,
                    '$username' , '$password' , '$website' , '$email' , '$securityQuestions' , '$registrationDate' , '$profilePhoto' , '$profileBackground'
                );
                " ;
                
                $mysqli = new mysqli( host , username , password , dbname ) or die( "Failed to connect to Database!" ) ;
                if( $mysqli -> query($sql) ){
                    // Security file prepare
                    $file = fopen( "$securityQuestions" , "w" ) ;
                    fwrite( $file , "$qid1 $question1 $q1 \n" ) ;
                    fwrite( $file , "$qid2 $question2 $q2 \n" ) ;
                    fwrite( $file , "$qid3 $question3 $q3 \n" ) ;
                    fwrite( $file , "$qid4 $question4 $q4 \n" ) ;
                    fwrite( $file , "$qid5 $question5 $q5 \n" ) ;
                    fclose( $file ) ;
                    
                    // User Greeding
                    $this -> newUserGreeding( $firstname , $lastname ) ;
                    $mysqli -> close() ;

                    $username = myEncoding( $username ) ;
                    $password = myEncoding( $password ) ;

                    session_start() ;
                    $_SESSION['username'] = $username ;
                    $_SESSION['password'] = $password ;
                    header( "refresh:5 ; url=ui/test.php" ) ;
                    exit() ;
                }else{
                    $mysqli -> close() ;
                    throw new exception( "An Error occurred!" ) ;
                }
                exit() ;
            }catch( Exception $e ){
                return $e -> getMessage() ;
                exit() ;
            }
        }
    }


//================================================================================================================================================================================================================//

    # Forgot password class 
    # This class takes the Security questions and answers to manage the user account 
    # This class makes sure that a user is the owner of an acount

    class forgetPassword extends main{
        //public $userID ;
        public $username ;
        public $questions ;
        public $answers ;
        
        # Check if a user is already exists ( if true it returns the Security-Questions and Security-Answers )
        public function checkUser( $username ){
            try{
                //$username = filterInput( $username ) ;
                $result = $this -> forgotPassword( $username ) ;
                if( is_string($result) ){
                    throw new exception( $result ) ;
                }elseif( is_array($result) ){
                    if( $security = $result["securityQuestions"] ){
                        $questions = [] ;
                        $userAnswers = [] ;
                        for( $i = 0 ; $i < count($security) ; $i++ ){
                            $current = $security[$i] ;

                            $answer = $current['answer'] ;
                            $question = $current['question'] ;
                            array_push( $questions , $question ) ;
                            array_push( $userAnswers , $answer ) ;
                        }
                        if( $user = $result['userInfo'] ){
                            //$userID = $user['userID'] ;
                            $username = $user['username'] ;
                        }
                        //$this -> userID = $userID ;
                        $this -> username = $username ;
                        $this -> questions = $questions ;
                        $this -> answers = $userAnswers ;
                    }
                }
                return $this -> questions ;
                exit() ;
            }catch( Exception $e ){
                return $e -> getMessage() ;
                exit() ;
            }
        }
        
        # Security Question Validation Function
        # Validating Security Answers of a User
        public function questionsValidation( $username , $q1 , $q2 , $q3 , $q4 , $q5 ){
            $this -> checkUser( $username ) ;
            $answers = $this -> answers ;
            try{
                //$username = filterInput( $username ) ;
                $q1 = filterInput( $q1 ) ;
                $q2 = filterInput( $q2 ) ;
                $q3 = filterInput( $q3 ) ;
                $q4 = filterInput( $q4 ) ;
                $q5 = filterInput( $q5 ) ;

                $q1 = strtolower( $q1 ) ;
                $q2 = strtolower( $q2 ) ;
                $q3 = strtolower( $q3 ) ;
                $q4 = strtolower( $q4 ) ;
                $q5 = strtolower( $q5 ) ;
                
                $realAnswers = $this -> answers ;
                $answers2check = [ $q1 , $q2 , $q3 , $q4 , $q5 ] ;

                for( $i = 0 ; $i < count($answers2check) ; $i++ ){
                    if( empty($answers2check[$i]) ){
                        throw new exception( "All Fields must filled!" ) ;
                    }
                }
                
                if( $result = array_diff_assoc($realAnswers,$answers2check) ){
                    throw new exception( "Not correct! Please try again." ) ;
                }elseif( $username == $this -> username ){
                    $username = $this -> username ;
                    return TRUE ;
                }else{
                    return FALSE ;
                }
                exit() ;
            }catch( Exception $e ){
                return $e -> getMessage() ;
                exit ;
            }
        }


        # Reset The Password
        # Changing the Password of a User if all answers are Correct
        public function resetPassword( $username , $pas1 , $pas2 ){
            try{
                $username = filterInput( $username ) ;
                $pas1 = filterInput( $pas1 ) ;
                $pas2 = filterInput( $pas2 ) ;

                if( empty($pas1) || empty($pas2) ){
                    throw new exception( "Password is required!" ) ;
                }elseif( $pas1 != $pas2 ){
                    throw new exception( "Incorrect! Please try again!" ) ;
                }elseif( $pas1 == $pas2 ){
                    $password = $pas1 ;
                    if( strlen($password) > 12 || strlen($password) < 8 ){
                        throw new exception( "Password must be less than 12 and greater than 8 letters and digits and symbols!" ) ;
                    }
                    
                    if( $username && $password ){
                        $mysqli = new mysqli( host , username , password , dbname ) or die( "Connection failed!" ) ;
                        $sql = "UPDATE users SET password = '$password' WHERE userName = '$username';" ;
                        if( $mysqli -> real_query($sql) ){
                            echo "Password Changed successfully.<br>" ;
                            $mysqli -> close() ;
                            // Encode Username and Password
                            $username = myEncoding( $username ) ;
                            $password = myEncoding( $password ) ;

                            session_start() ;
                            $_SESSION['username'] = $username ;
                            $_SESSION['password'] = $password ;
                            header( "refresh:5 ; url=ui/test.php" ) ;
                            exit() ;
                        }else{
                            throw new exception( "Failed to change your Password!" ) ;
                        }
                    }else{
                        throw new exception( "Failed to open a session!" ) ;
                    }
                }else{
                    throw new exception( "Incorrect! Please try again!" ) ;
                }
                exit() ;
            }catch( Exception $e ){
                return $e -> getMessage() ;
                exit() ;
            }
        }

    }

?>