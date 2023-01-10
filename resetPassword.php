<?php
    require_once "forgotPassword.php" ;
    session_start() ;
    if( $_SESSION ){
        $username = $_SESSION['username'] ;
    }
    # Reset Password Form
    function resetPasswordForm( $username ){
        //$username = filterInput( $username ) ;
    }
?>


<!-- Reset Password Form --> 
<h3>Reset Password</h3>
<p>Type your New Password</p>
<form method='post' action='<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] );?>'>
    <input type="hidden" name="username" value="<?=$username?>">
    <input type="password" name="password" placeholder="New Password"><br>
    <input type="password" name="reEnterPassword" placeholder="Re-Enter Password"><br>
    <?=@$resetOb?><br>
    <input type="submit" name="resetPassword" value="Change Password">
</form>