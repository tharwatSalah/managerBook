<?php
    require_once "forgotPassword.php" ;
?>


<!-- Forgot Password Form -->
<div class="example">
    <h3>Forgot Password</h3>
    <form method="post" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] );?>" >
        <input type="string" name="username" placeholder="User Name"> <?=@$ob2?><br>
        <input type="submit" name="forgotPassword" value="Submit">
    </form>
</div>
<br>