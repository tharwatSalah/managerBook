<?php
    require_once "forgotPassword.php" ;
    echo $username."<br> Iam UserName.<br>" ;
?>


<!-- Security Questions Form -->
<div class='example'>
<h4>Security Questions</h4>
<form method="post" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] );?>">
<input type='hidden' name='username' value='<?=$username?>'><br>
<?=@$q1?> <input type='string' name='q1'><br>
<?=@$q2?> <input type='string' name='q2'><br>
<?=@$q3?> <input type='string' name='q3'><br>
<?=@$q4?> <input type='string' name='q4'><br>
<?=@$q5?> <input type='string' name='q5'><br>
<input type="submit" name="securityRespond" value="Send"></form>