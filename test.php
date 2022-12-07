<?php
    /*
        $file = fopen( "security.txt" , 'w' ) or die( "Failed to open the file!" ) ;
        while( !feof($file) ){
            $line = fgets( $file ) ;
            $data = sscanf( $line , "%s %s %s" ) ;
            list( $id , $question , $answer ) = $data ;
            if( !empty($id) ){
                echo "ID : $id , and Question : $question , and Answer : $answer <br>" ;
            }
        }
        fclose($file) ;

        $file = fopen( "security.txt" , "c" ) or die( "Failed to open the file!" ) ;
        while( !feof($file) ){
            $line = fgets( $file ) ;
            $data = sscanf( $line , "%s %s %s" ) ;
            list( $id , $question , $answer ) = $data ;
            if( !empty($id) ){
                if( $id == 12344 ){
                    $idlen = strlen( $id ) ;
                    $qlen = strlen( $question ) ;
                    $alen = strlen( $answer ) ;
                    $totalLen = $idlen + $qlen + $alen ;

                    $pos = ftell($file) ;
                    $lineCount = strlen($line) ;
                    $buffer = $pos - $lineCount ;
                    fseek( $file , $buffer , SEEK_SET ) ;
                    
                    $id = $id ;
                    $question = "This is a test of changing a content of a file." ;
                    $answer = "Do not think that iam a just regular." ;
                    // Preparring the data to be replaced
                    $question = str_ireplace( " " , "_" , $question ) ;
                    $answer = str_ireplace( " " , "_" , $answer ) ; 
                    fwrite( $file , "$id $question $answer\n" , $totalLen ) ;
                }
            }
        }
        fclose( $file ) ;
    */


    /*
    while( !feof($file) ){
        $line = fgets($file) ;
        $data = sscanf( $line , "%s %s %s" ) ;
        list( $id , $question , $answer ) = $data ;
        $question = str_replace( "_" , " " , $question ) ;
        $answer = str_replace( "_" , " " , $answer ) ;
        if( !empty($id) ){
            echo "ID : $id , and Question : $question , and Answer : $answer <br>" ;
        }
        #echo "ID : $id , and Question : $question , and Answer : $answer <br>" ;
    }
    */


    
    session_start() ;
    require_once "main.php" ;

    if( $_SESSION ){
        echo "Hello ".$_SESSION['username']."<br>" ;
    }else{
        echo "No session recoreded!<br>" ;
    }
    echo "<a href='?q=destroy'>Log out</a><br>" ;
    if( isset( $_GET['q'] ) ){
        session_unset() ;
        session_destroy() ;
        refresh() ;
    }
    echo "<a href=ui.php>Go Back</a>" ;
    
?>