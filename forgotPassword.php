<?php
    require_once "core.php" ;
    $test = new main() ;
    $user2forget = new forgetPassword() ;

    // Log in
    if( isset($_POST['logIn']) ){
        $username = $_POST['username'] ;
        $password = $_POST['password'] ;
        $result = $test -> logIn( $username , $password ) ;
        if( is_string($result) ){
            ob_start() ;
            echo $result."<br>" ;
            $ob = ob_get_clean() ;
        }
    }

    # Forget Password Handling
    if( isset($_POST["forgotPassword"]) ){
        $username = $_POST['username'] ;
        if( $questions = $user2forget -> checkUser($username) ){
            if( is_string($questions) ){
                ob_start() ;
                echo $questions ;
                $ob2 = ob_get_clean() ;
            }elseif( is_array($questions) ){
                securityRespondForm( $username , $questions ) ;
            }
        }
    }

    
    # Security Questions Form
    function securityRespondForm( $username , $questions , $output=NULL ){ 
        $ob3 = $output ;
        if( !is_array($questions) ){
            die( "Something is wrong!" ) ;
        }
        ?>
            <br>
            <div class='example'>
            <h4>Security Questions</h4>
            <form method="post" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] );?>">
            <input type="hidden" name="username" value="<?=$username?>"><br>
            <?php

            for( $i = 0 ; $i < count($questions) ; $i++ ){
                $question = $questions[$i] ;
                $counter = $i+1 ;
                echo "<span>$question</span> <input type='string' name='q$counter'><br>" ;
            }
            echo '<input type="submit" name="securityRespond" value="Send"></form>' ;
            echo @$ob3 ;
            echo "</div>" ;
    }
     
    
    # Security Question Validations
    if( isset($_POST["securityRespond"]) ){
        $username = $_POST['username'] ;
        $q1 = $_POST['q1'] ;
        $q2 = $_POST['q2'] ;
        $q3 = $_POST['q3'] ;
        $q4 = $_POST['q4'] ;
        $q5 = $_POST['q5'] ;

        $result = $user2forget -> questionsValidation( $username , $q1 , $q2 , $q3 , $q4 , $q5 ) ;
        if( is_string($result) || $result == FALSE ){
            $questions = $user2forget -> checkUser($username) ;
            ob_start() ;
            echo $result ;
            $ob3 = ob_get_clean() ;
            securityRespondForm( $username , $questions , $ob3 ) ;
        }elseif( is_bool($result) ){
            if( $result === TRUE ){
                session_start() ;
                $_SESSION['username'] = $username ;
                header( "location:resetPassword.php" ) ;
                exit() ;
            }
        }else{
            header( "HTTP/1.0 404 ERROR!" ) ;
            exit() ;
        }
    }
    
    
    # Reset Password ( resetPassword function )
    if( isset($_POST['resetPassword']) ){
        $username = $_POST['username'] ;
        $pas1 = $_POST['password'] ;
        $pas2 = $_POST['reEnterPassword'] ;
        if( $reset = $user2forget -> resetPassword( $username , $pas1 , $pas2 ) ){
            if( is_string($reset) ){
                ob_start() ;
                echo $reset ;
                $resetOb = ob_get_clean() ;
            }
        }
    }


    # Create A New User
    if( isset($_POST['signUp']) ){
        // persional Validation
        $firstname = $_POST['firstname'] ;
        $lastname = $_POST['lastname'] ;
        $phone = $_POST['phone'] ;
        $country = $_POST['country'] ;
        $address = $_POST['address'] ;
        $central = $_POST['central'] ;
        $governorate = $_POST['governorate'] ;

        $day = $_POST['day'] ;
        $month = $_POST['month'] ;
        $year = $_POST['year'] ;
        
        $birthDate = "$year/$month/$day" ;
        $nationalID = $_POST['nationalID'] ;
        $jobTitle = $_POST['jobTitle'] ;
        $gender = $_POST['gender'] ;
        $status = $_POST['status'] ;

        // Entity validation
        $entityType = $_POST['entityType'] ;
        $entityName = $_POST['entityName'] ;
        $entityPhone = $_POST['entityPhone'] ;
        $entityAddress = $_POST['entityAddress'] ;
        $entityCentral = $_POST['entityCentral'] ;
        $entityGovernorate = $_POST['entityGovernorate'] ;

        $stablishment_day = $_POST['stablishment_day'] ;
        $stablishment_month = $_POST['stablishment_month'] ;
        $stablishment_year = $_POST['stablishment_year'] ;

        $stablishmentDate = "$stablishment_year/$stablishment_month/$stablishment_day" ;
        $commercialRegistration_NO = $_POST['commercialRegistration_NO'] ;
        $taxCard_NO = $_POST['taxCard_NO'] ;
        $bio = $_POST['bio'] ;

        // Account Validation
        $username = $_POST['username'] ;
        $password = $_POST['password'] ;
        $website = $_POST['website'] ;
        $email = $_POST['email'] ;
        $profilePhoto = $_FILES['profilePhoto'] ;
        $profileBackground = $_FILES['profileBackground'] ;

        // Security Questions Validation
        $q1 = $_POST['q1'] ;
        $q2 = $_POST['q2'] ;
        $q3 = $_POST['q3'] ;
        $q4 = $_POST['q4'] ;
        $q5 = $_POST['q5'] ;


        $newUser = $test -> createUser( $firstname , $lastname , $phone , $country , $address , $central , $governorate , $birthDate , $nationalID , $jobTitle , $gender , $status , $entityType , $entityName , $entityPhone , $entityAddress , $entityCentral , $entityGovernorate , $stablishmentDate , $commercialRegistration_NO , $taxCard_NO , $bio , $website , $email , $username , $password , $profilePhoto , $profileBackground , $q1 , $q2 , $q3 , $q4 , $q5 ) ;
        if( is_string($newUser) ){
            ob_start() ;
            echo $newUser ;
            $ob4 = ob_get_clean() ;
        }
    }

?>