<?php
    require_once "forgotPassword.php" ;
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
            <h3>Log in form</h3>
            <form method="post" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ); ?>" >
                <input type="string" name="username" placeholder="User Name"><br>
                <input type="password" name="password" placeholder="Password"><br>
                <input type="submit" name="logIn" value="Log in">
            </form><?=@$ob?>
            <a href="forgotPassword.php">Forgot Password</a>
        </div>
        <br>
        <!-- Forgot Password Form -->
        <div class="example">
            <h3>Forgot Password</h3>
            <form method="post" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] );?>" >
                <input type="string" name="username" placeholder="User Name"><br>
                <?=@$ob2?>
                <input type="submit" name="forgotPassword" value="Submit">
            </form>
        </div>
        <br>
        <?php
        # Reset Password Form
        function resetPasswordForm( $username ){
        $username = filterInput( $username ) ; ?>
        <h3>Reset Password</h3>
        <p>Type your New Password</p>
        <form method='post' action='<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] );?>'>
            <input type='hidden' name='username' value='<?=$username?>'>
            <input type='password' name='password' placeholder='New Password'><br>
            <input type='password' name='reEnterPassword' placeholder='Re-Enter Password'><br>
            <input type='submit' name='resetPassword' value='Change Password'>
            </form>
        <?php
    }
    ?>

    </body>
</html>