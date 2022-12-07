<?php
    require_once "main.php" ;

    // Log in
    if( isset($_POST['logIn']) ){
        $username = $_POST['username'] ;
        $password = $_POST['password'] ;
        if( $result = $test -> logIn( $username , $password ) ){
            if( $result ){
                ob_start() ;
                echo $result."<br>" ;
                $ob = ob_get_clean() ;
            }
        }
    }

    # Forget Password Handling
    if( isset($_POST["forgotPassword"]) ){
        $username = $_POST['username'] ;
        $result = forgotPassword( $username ) ;

        $username = $result['username'] ;
        $questions = $result['questions'] ;
        securityRespondForm( $username , $questions ) ;
    }

    # Security Questions Form
    function securityRespondForm( $username , $questions ){ ?>
            <br>
            <div class='example'>
            <h4>Security Questions</h4>
            <form method="post" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] );?>">
            <input type='hidden' name='username' value='<?=$username?>'><br>
            <?php
            for( $i = 0 ; $i < count($questions) ; $i++ ){
                $current = $questions[$i] ;
                $question = $current['question'] ;
                $counter = $current['counter'] ;
                echo "<span>$question</span> <input type='string' name='q$counter'><br>" ;
            }
            echo '<input type="submit" name="securityRespond" value="Send"></form>' ;
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

        questionsValidation( $username , $q1 , $q2 , $q3 , $q4 , $q5 ) ;
    }
    
    # Reset Password ( resetPassword function )
    if( isset($_POST['resetPassword']) ){
        $username = $_POST['username'] ;
        $pas1 = $_POST['password'] ;
        $pas2 = $_POST['reEnterPassword'] ;
        
        resetPassword( $username , $pas1 , $pas2 ) ;
    }
?>