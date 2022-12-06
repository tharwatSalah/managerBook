<?php
    require_once "core.php" ;
    #require_once "main.php" ;
    
    function resetPasswordForm( $username ){
        $username = filterInput( $username ) ;
        echo "<h3>Reset Password</h3>" ;
        echo "<p>Type your New Password</p>" ;
        /*echo "<form method='post' action='<?php echo $action;?>'" ;*/
        echo "
            <form method='post' action='forgotPassword.php'>
            <input type='hidden' name='username' value='$username'>
            <input type='password' name='password' placeholder='New Password'><br>
            <input type='password' name='reEnterPassword' placeholder='Re-Enter Password'><br>
            <input type='submit' name='resetPassword' value='Submit'>
            </form>
        " ;
    }

    #$test = new main() ;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Log in</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <style>
            * { font-family:Arial, Helvetica, sans-serif; }
            body { background-color:beige; }
            .example {
                padding: 20px;
                background-color: rgb(201, 201, 201);
                max-width : 85%;
            }
            .example p{ padding: 7px; background-color:rgb(247, 247, 235); border-left: 5px solid green ; max-width:100%;}
            span {
                color: red;
                background-color: rgb(230, 230, 230);
                padding: 0 3px;
            }
            .note {
                padding: 20px;
                background-color: rgb(245, 237, 126);
                margin-top: 10px;
                margin-bottom: 10px;
            }
            .warning {
                background-color: tomato; 
                padding: 25px;
            }
            button{
                display: inline-block;
                text-align: center;
                text-decoration: none;
                width: 17%; 
                padding: 14px 32px;
                margin: 5px;
                border: none;
                background-color: green;
                font-size: 16px;
                cursor: pointer;
                transition: .5s;
            }
            div.column {
                float: left;
                padding: 15px;
                width: 47%;
                background-color:rgb(193, 205, 209) ;
            }
            /* Clear floats after the columns */
            div.row:after {
                content: "";
                display: table;
                clear: both;
            }
            /* Responsive Layout -Makes the three columns stack on Top Of Each Other
            Instead Of Next To Each Other */
            @media screen and (max-width: 600px){
            div.column { width: 100%; }
            }

            comment{ color: darkgreen; }
            button:hover{ background-color: rgb(5, 235, 5);}
            table{border: 3px solid rgb(193, 205, 209);border-collapse:collapse; width: 90%; background-color: rgb(235, 233, 233); /*margin:auto;*/ }
            td{border:1px solid grey; padding: 10px;}
            th { border:1px solid grey; padding: 10px; }
            tr:hover{ background-color:rgb(180, 180, 180); }
            ul{ list-style-type:circle; }
            .note ul, .note ol {background-color: inherit; }
            ul li, ol li {padding: 5px; }
            em { color: blue; }
            #db { color : rgb(0, 170, 192); }
            #blue { color : blue; }
            img { margin: auto;}
        </style>
    </head>
    <body>
        <!-- Log in Form -->
        <div class="example">
            <h3>Forgot Password</h3>
            <form method="post" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] );?>" >
                <input type="string" name="username" placeholder="User Name"><br>
                <input type="submit" name="forgotPassword" value="Submit">
            </form>
        </div>
        <?php

            # Forget Password Handling
            if( isset($_POST["forgotPassword"]) ){
                $username = $_POST['username'] ;
                
                try{
                    $username = filterInput( $username ) ;
                    if( $result = $test -> forgotPassword( $username ) ){
                        $security = $result["securityQuestions"] ;
        
                        echo "<br><div class='example'> <h4>Security Questions</h4>" ;
                        /*echo '<form method="post" action="<?php echo htmlspecialchars( $_SERVER[PHP_SELF] );?>" >' ;*/
                        echo '<form method="post" action="forgotPassword.php" >' ;
                        echo "<input type='hidden' name='username' value='$username'><br>" ;
                        for( $i = 0 ; $i < count($security) ; $i++ ){
                            $current = $security[$i] ;
        
                            $id = $current['id'] ;
                            $question = $current['question'] ;
                            $answer = $current['answer'] ;
                            $counter = $i+1 ;
        
                            echo "$question : $answer<br>" ;
        
                            echo "<p>$question</p> <input type='string' name='q$counter'><br>" ;
                        }
                        echo '<input type="submit" name="securityRespond" value="Send"></form>' ;
                        echo "</div>" ;
                    }
                    exit() ;
                }catch( Exception $e ){
                    echo $e -> getMessage() ;
                    exit() ;
                }
            }

            # Security Question Validations
            if( isset($_POST["securityRespond"]) ){
                try{
                    $username = $_POST['username'] ;
                    $q1 = $_POST['q1'] ;
                    $q2 = $_POST['q2'] ;
                    $q3 = $_POST['q3'] ;
                    $q4 = $_POST['q4'] ;
                    $q5 = $_POST['q5'] ;

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
                        die( "Not correct! Please try again." ) ;
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
                        /*
                        $action = htmlspecialchars( $_SERVER['PHP_SELF']); ;
                        ob_start() ;
                        echo "<h3>Reset Password</h3>" ;
                        echo "<p>Type your New Password</p>" ;
                        */
                        /*echo "<form method='post' action='<?php echo $action;?>'" ;*/
                        /*
                        echo "
                            <form method='post' action='forgotPassword.php'>
                            <input type='hidden' name='username' value='$username'>
                            <input type='password' name='password' placeholder='New Password'><br>
                            <input type='password' name='reEnterPassword' placeholder='Re-Enter Password'><br>
                            <input type='submit' name='resetPassword' value='Submit'>
                            </form>
                        " ;
                        $resetForm = ob_get_contents() ;
                        $buffer = [ 'resetPasswordForm' => $resetForm ] ;
                        myOutput( $buffer ) ;
                        */
                    }
                    exit() ;
                }catch( Exception $e ){
                    echo $e -> getMessage() ;
                    exit ;
                }
                
            }

            # Reset Password
            if( isset($_POST['resetPassword']) ){
                try{
                    $username = $_POST['username'] ;
                    $pas1 = $_POST['password'] ;
                    $pas2 = $_POST['reEnterPassword'] ;
                    
                    $username = filterInput( $username ) ;
                    $pas1 = filterInput( $pas1 ) ;
                    $pas2 = filterInput( $pas2 ) ;

                    if( $pas1 == $pas2 ){
                        $password = $pas1 ;
                        
                        /*
                        switch( $password ){
                            case empty($password) : throw new exception( "Password is required!" ) ; break ;
                            case strlen($password) < 8 : throw new exception( "Maximum limit is 12 letters and numbers!" , 2 ) ; break ;
                            case strlen($password) > 12 : throw new exception( "Maximum limit is 12 letters and numbers!" , 2 ) ; break ;
                        }
                        */
                        
                        if( strlen($password) > 12 || strlen($password) < 8 ){
                            throw new exception( "Password must be less than 12 and greater than 8 letters and digits and symbols!" , 2 ) ;
                        }

                        if( $username && $password ){
                            $mysqli = new mysqli( host , username , password , dbname ) or die( "Connection failed!" ) ;
                            $sql = "UPDATE users SET password = '$password' WHERE userName = '$username'" ;
                            if( $mysqli -> real_query($sql) ){
                                echo "Password Changed successfully.<br>" ;
                                $mysqli -> close() ;
                                #$test -> logIn( $username , $password ) ;
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
            
    </body>
</html>