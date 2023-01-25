<?php
    session_start() ;
    if( !$_SESSION ){
        header( "location:../ui.php" ) ;
        exit() ;
    }else{
        // Check User-Page
        if( $_COOKIE ){
            if( @$userPage = $_COOKIE['targetPage'] ){
                $target = basename( "$userPage" , ".php" ) ;
                header( "location:$userPage" ) ;
                exit() ;
            }else{
                $userPage = "homepage.php" ;
                header( "location:$userPage" ) ;
                exit() ;
            }
            
        }else{
            $userPage = "homepage.php" ;
            header( "location:$userPage" ) ;
            exit() ;
        }

        exit() ;
    }

?>