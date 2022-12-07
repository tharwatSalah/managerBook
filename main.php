<?php
    require_once "core.php" ;
    $test = new main() ;

    # Forgot Password Function
    function forgotPassword( $username ){
        $test = new main() ; 
        try{
            $username = filterInput( $username ) ;
            if( $result = $test -> forgotPassword( $username ) ){
                if( $security = $result["securityQuestions"] ){
                    $questions = [] ;
                    for( $i = 0 ; $i < count($security) ; $i++ ){
                        $current = $security[$i] ;
                        $question = $current['question'] ;
                        $counter = $i+1 ;

                        $sd = [ 'counter' => $counter , 'question' => $question ] ;
                        array_push( $questions , $sd ) ;
                    }
                    $data = [ 'username' => $username , 'questions' => $questions ] ; // Return User Name and Security Questions
                    return $data ;
                }
            }
            exit() ;
        }catch( Exception $e ){
            echo $e -> getMessage()."<br>" ;
            exit() ;
        }
    } 

    # Security Question Validation Function
    # Validating Security Answers of a User
    function questionsValidation( $username , $q1 , $q2 , $q3 , $q4 , $q5 ){
        try{
            $test = new main() ;

            $username = filterInput( $username ) ;
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

            if( !$result = $test -> forgotPassword( $username ) ){
                throw new exception( "Failed to gazarring the data!" ) ;
            }
            $security = $result["securityQuestions"] ;
            $realAnswers = array_column( $security , "answer" ) ;
            $answers2check = [ $q1 , $q2 , $q3 , $q4 , $q5 ] ;

            for( $i = 0 ; $i < count($answers2check) ; $i++ ){
                if( empty($answers2check[$i]) ){
                    throw new exception( "All Fields must filled!" ) ;
                }
            }
            
            if( $result = array_diff($realAnswers,$answers2check) ){
                throw new exception( "Not correct! Please try again." ) ;
            }else{
                if( $result = $test -> forgotPassword($username) ){
                    if( !$userInfo = $result["userInfo"] ){
                        throw new exception( "Failed to collect User's Info!" ) ;
                    }else{
                        // Final Result (just in case) if a developer needed
                        $userID = $userInfo['userID'] ;
                        $username = $userInfo['username'] ;
                    }
                }else{
                    throw new exception( "An error occurred!" ) ;
                }
                resetPasswordForm( $username ) ;
            }
            exit() ;
        }catch( Exception $e ){
            echo $e -> getMessage() ;
            exit ;
        }
    }

    # Reset The Password
    # Changing the Password of a User if all answers are Correct
    function resetPassword( $username , $pas1 , $pas2 ){
        try{
            $test = new main() ;

            $username = filterInput( $username ) ;
            $pas1 = filterInput( $pas1 ) ;
            $pas2 = filterInput( $pas2 ) ;

            if( empty($pas1) || empty($pas2) ){
                throw new exception( "Password is required!" , 2 ) ;
            }elseif( $pas1 != $pas2 ){
                throw new exception( "Incorrect! Please try again!" , 2 ) ;
            }elseif( $pas1 == $pas2 ){
                $password = $pas1 ;
                if( strlen($password) > 12 || strlen($password) < 8 ){
                    throw new exception( "Password must be less than 12 and greater than 8 letters and digits and symbols!" , 2 ) ;
                }

                if( $username && $password ){
                    $mysqli = new mysqli( host , username , password , dbname ) or die( "Connection failed!" ) ;
                    $sql = "UPDATE users SET password = '$password' WHERE userName = '$username';" ;
                    if( $mysqli -> real_query($sql) ){
                        refresh() ;
                        echo "Password Changed successfully.<br>" ;
                        $mysqli -> close() ;
                        $test -> logIn( $username , $password ) ;
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
            $code = $e -> getCode() ;
            switch( $code ){
                case 2 : resetPasswordForm( $username ) ; break ;
                // Using switch case if there more than one code for any other operation
            }
            echo $e -> getMessage() ;
            exit() ;
        }
    }
?>