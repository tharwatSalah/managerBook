<?php
    require_once "../core.php" ;
    require_once "../main.php" ;
    session_start() ;
    $myTest = new main() ;

    if( $_SESSION ){
        
        $username = $_SESSION['username'] ;
        $password = $_SESSION['password'] ;

        echo "Hello ".$_SESSION['username']."<br><hr>" ;
        echo "Username : $username <br>" ;
        echo "Password : $password <br>" ;
        
        echo "<hr>" ;
        $operations = new operations( $username , $password ) ;
    }else{
        echo "No session recoreded!<br>" ;
    }
    echo "<a href='?q=destroy'>Log out</a><br>" ;
    if( isset( $_GET['q'] ) ){
        session_unset() ;
        session_destroy() ;
        header( "location:".$_SERVER['PHP_SELF'] ) ;
        exit() ;
    }
    echo "<a href=ui.php>Go Back</a>" ;
    
    echo "<br><hr><br>" ;

    $mysearch = $operations -> resultHandling( "projects" , "new" ) ;
    if( empty($mysearch) ){
        echo "No Results Found! ( iam else )<br>" ;
    }elseif( is_array($mysearch) ){
        for( $i = 0 ; $i < count($mysearch) ; $i++ ){
            $current = $mysearch[$i] ;
            foreach( $current as $key => $value ){
                echo "<b>$key</b> => $value " ;
            }
            echo "<br>" ;
        }
    }elseif( is_string($mysearch) ){
        echo $mysearch."<br>" ;
    }



    echo "<br><hr><br><hr>" ;
    /*
    $keys = [] ;
    $finalResult = [] ;
    $data = [
        [ 'firstname' => 'tharwat' , 'lname' => 'salah' , 'lastname' => 'sayed' ] ,
        [ 'firstname' => 'ahmed' , 'lname' => 'salah' , 'lastname' => 'sayed' ] ,
        [ 'firstname' => 'khlid' , 'lname' => 'salah' , 'lastname' => 'sayed' ] ,
        [ 'firstname' => 'mohammed' , 'lname' => 'salah' , 'lastname' => 'sayed' ] 
    ] ;

    $search = "tharwat salah sayed" ;
    $string_count = str_word_count( $search , 1 ) ; //convert the search string into an array ( to loop throw it )

    // loop throw pattern
    for( $p = 0 ; $p < count($string_count) ; $p++ ){
        $pattern = $string_count[$p] ;
        // loop throw data
        for( $i = 0 ; $i < count($data) ; $i++ ){
            $current = $data[$i] ;
            if( preg_grep("/$pattern.|$pattern/i",$current) ){
                //check keys if the current index is already fetched
                if( count($keys) ){
                    $index = implode( "" , $keys ) ;
                    if( stristr($index,"$i") ){
                        continue ;
                    }else{
                        array_push( $finalResult , $current ) ;
                        array_push( $keys , $i ) ;
                    }
                }else{
                    if( $i == 0 ){
                        array_push( $finalResult , $current ) ;
                        array_push( $keys , "0$i" ) ;
                    }else{
                        array_push( $finalResult , $current ) ;
                        array_push( $keys , $i ) ;
                    }
                }
            }
        }
    }


    echo "<hr>" ;

    for( $i = 0 ; $i < count($finalResult) ; $i++ ){
        $current = $finalResult[$i] ;
        echo "$i : " ;
        foreach( $current as $key => $value ){
            echo "<b>$key</b> = $value " ;
        }
        echo "<br>" ;
    }
    echo "<hr>" ;
    print_r( $keys ) ;
    */
    function mytest( $bills , $value=NULL , $from=NULL , $to=NULL ){
            // Search by bill-type ( eg. select bottons )
            if( $value ){
                // filter bill-type from a Date into a Date
                if( $from && $to ){ 
                    $finalResult = [] ;

                    $from_date = str_ireplace( "/" , " " , $from ) ;
                    $from_date = sscanf( $from_date , "%s %s %s" ) ;
                    list( $from_year , $from_month , $from_day ) = $from_date ;

                    $to_date = str_ireplace( "/" , " " , $to ) ;
                    $to_date = sscanf( $to_date , "%s %s %s" ) ;
                    list( $to_year , $to_month , $to_day ) = $to_date ;

                    for( $i = 0 ; $i < count($bills) ; $i++ ){
                        $currentBill = $bills[$i] ;
                        $billType = $currentBill['billType'] ;
                        if( $billType == $value ){
                            // check the date
                            $bill_date = $currentBill['date'] ;
                            $bill_date = str_ireplace( "/" , " " , $bill_date ) ;
                            $bill_date = sscanf( $bill_date , "%s %s %s" ) ;
                            list( $bill_year , $bill_month , $bill_day ) = $bill_date ;

                            // If From is bigger than To
                            if( $from_year > $to_year ){
                                throw new exception( "Invalid Year format!" ) ;
                                
                            // If both ( From_year and To_year ) are equal to each other
                            }elseif( $from_year == $to_year ){
                                if( $from_month > $to_month ){
                                    throw new exception( "Invalid Month format!" ) ;
                                }elseif( $from_month == $to_month ){ 
                                    if( $from_day > $to_day ){
                                        throw new exception( "Invalid Day format!" ) ;
                                    }elseif( $from_day == $to_day ){ # if from_day = to_day
                                        $target_year = $from_year ;
                                        $target_month = $from_month ;
                                        $target_day = $from_day ;
                                        if( $bill_year == $target_year && $bill_month == $target_month && $bill_day == $target_day ){
                                            array_push( $finalResult , $currentBill ) ;
                                        }
                                    }elseif( $from_day < $to_day ){
                                        if( $bill_day >= $from_day && $bill_day <= $to_day ){
                                            array_push( $finalResult , $currentBill ) ;
                                        }
                                    }
                                    
                                }elseif( $from_month < $to_month ){ 
                                    if( $bill_month == $from_month ){
                                        // check days ( from_day )
                                        if( $bill_day >= $from_day ){
                                            array_push( $finalResult , $currentBill ) ;
                                        }
                                    }elseif( $bill_month > $from_month && $bill_month < $to_month ){
                                        array_push( $finalResult , $currentBill ) ;
                                    }elseif( $bill_month == $to_month ){
                                        // check days ( to_day )
                                        if( $bill_day <= $to_day ){
                                            array_push( $finalResult , $currentBill ) ;
                                        }
                                    }
                                }
                            
                            // If From_year is less than To_year
                            }elseif( $from_year < $to_year ){
                                // if bill_year = from_year
                                if( $bill_year == $from_year ){
                                    if( $bill_month == $from_month ){ // if bill_month = from_month
                                        // check days
                                        if( $bill_day >= $from_day ){ 
                                            array_push( $finalResult , $currentBill ) ;
                                        }
                                    }elseif( $bill_month > $from_month ){ // if bill_month > from_month
                                        array_push( $finalResult , $currentBill ) ;
                                    }
                                // if (bill_year > from_year) and (bill_year < to_year)
                                }elseif( $bill_year > $from_year && $bill_year < $to_year ){
                                    array_push( $finalResult , $currentBill ) ;
                                // if ( bill_year = to_year )
                                }elseif( $bill_year == $to_year ){
                                    if( $bill_month < $to_month ){
                                        array_push( $finalResult , $currentBill ) ;
                                    }elseif( $bill_month == $to_month ){ // check days
                                        if( $bill_day <= $to_day ){
                                            array_push( $finalResult , $currentBill ) ;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    return $finalResult ;

                // Filter bill-type starting from a Date
                }elseif( $from ){
                    $finalResult = [] ;

                    $from_date = str_ireplace( "/" , " " , $from ) ;
                    $from_date = sscanf( $from_date , "%s %s %s" ) ;
                    list( $from_year , $from_month , $from_day ) = $from_date ;

                    for( $i = 0 ; $i < count($bills) ; $i++ ){
                        $currentBill = $bills[$i] ;
                        $billType = $currentBill['billType'] ;
                        if( $billType == $value ){
                            // check Bill's date
                            $bill_date = $currentBill['date'] ;
                            $bill_date = str_ireplace( "/" , " " , $bill_date ) ;
                            $bill_date = sscanf( $bill_date , "%s %s %s" ) ;
                            list( $bill_year , $bill_month , $bill_day ) = $bill_date ;

                            if( $bill_year > $from_year ){
                                array_push( $finalResult , $currentBill ) ;
                            }elseif( $bill_year == $from_year ){
                                // check months
                                if( $bill_month > $from_month ){
                                    array_push( $finalResult , $currentBill ) ;
                                }elseif( $bill_month == $from_month ){
                                    // check day
                                    if( $bill_day >= $from_day ){
                                        array_push( $finalResult , $currentBill ) ;
                                    }
                                }
                            }
                        }
                    }
                    return $finalResult ;

                // Filter bill-type untill a Date
                }elseif( $to ){
                    $finalResult = [] ;

                    $to_date = str_ireplace( "/" , " " , $to ) ;
                    $to_date = sscanf( $to_date , "%s %s %s" ) ;
                    list( $to_year , $to_month , $to_day ) = $to_date ;

                    for( $i = 0 ; $i < count($bills) ; $i++ ){
                        $currentBill = $bills[$i] ;
                        $billType = $currentBill['billType'] ;
                        if( $billType == $value ){
                            // check Bill's date
                            $bill_date = $currentBill['date'] ;
                            $bill_date = str_ireplace( "/" , " " , $bill_date ) ;
                            $bill_date = sscanf( $bill_date , "%s %s %s" ) ;
                            list( $bill_year , $bill_month , $bill_day ) = $bill_date ;

                            if( $bill_year < $to_year ){
                                array_push( $finalResult , $currentBill ) ;
                            }elseif( $bill_year == $to_year ){
                                // check months
                                if( $bill_month < $to_month ){
                                    array_push( $finalResult , $currentBill ) ;
                                }elseif( $bill_month == $to_month ){
                                    // check days
                                    if( $bill_day <= $to_day ){
                                        array_push( $finalResult , $currentBill ) ;
                                    }
                                }
                            }
                        }
                    }
                    return $finalResult ;
                // Filter bills by Type
                }else{
                    $finalResult = [] ;
                    for( $i = 0 ; $i < count($bills) ; $i++ ){
                        $currentBill = $bills[$i] ;
                        $billType = $currentBill['billType'] ;
                        if( $billType == $value ){
                            array_push( $finalResult , $currentBill ) ;
                        }
                    }
                    return $finalResult ;
                }
            // filter bill-type from a Date into a Date
            }elseif( $from && $to ){ 
                $finalResult = [] ;

                $from_date = str_ireplace( "/" , " " , $from ) ;
                $from_date = sscanf( $from_date , "%s %s %s" ) ;
                list( $from_year , $from_month , $from_day ) = $from_date ;
                if( !$from_day ){ $from_day = 1 ; }
                if( !$from_month ){ $from_month = 1 ; }

                $to_date = str_ireplace( "/" , " " , $to ) ;
                $to_date = sscanf( $to_date , "%s %s %s" ) ;
                list( $to_year , $to_month , $to_day ) = $to_date ;
                if( !$to_day ){ $to_day = 30 ; }
                if( !$to_month ){ $to_month = 12 ; }

                for( $i = 0 ; $i < count($bills) ; $i++ ){
                    $currentBill = $bills[$i] ;
                    // check the date
                    $bill_date = $currentBill['date'] ;
                    $bill_date = str_ireplace( "/" , " " , $bill_date ) ;
                    $bill_date = sscanf( $bill_date , "%s %s %s" ) ;
                    list( $bill_year , $bill_month , $bill_day ) = $bill_date ;

                    // If From is bigger than To
                    if( $from_year > $to_year ){
                        throw new exception( "Invalid Year format!" ) ;
                        
                    // If both ( From_year and To_year ) are equal to each other
                    }elseif( $from_year == $to_year ){ # if from_year = to_year
                        // check months
                        if( $from_month > $to_month ){
                            throw new exception( "Invalid Month format!" ) ;
                        // if (from_month) = (to_month)
                        }elseif( $from_month == $to_month ){ 
                            if( $from_day > $to_day ){
                                throw new exception( "Invalid Day format!" ) ;
                            }elseif( $from_day == $to_day ){ # if from_day = to_day
                                $target_year = $from_year ;
                                $target_month = $from_month ;
                                $target_day = $from_day ;
                                if( $bill_year == $target_year && $bill_month == $target_month && $bill_day == $target_day ){
                                    array_push( $finalResult , $currentBill ) ;
                                }
                            }elseif( $from_day < $to_day ){
                                if( $bill_day > $from_day && $bill_day <= $to_day ){
                                    array_push( $finalResult , $currentBill ) ;
                                }
                            }
                        // if (from_month) < (to_month)
                        }elseif( $from_month < $to_month ){ 
                            if( $bill_month == $from_month ){
                                // check days ( from_day )
                                if( $bill_day >= $from_day ){
                                    array_push( $finalResult , $currentBill ) ;
                                }
                            }elseif( $bill_month > $from_month && $bill_month < $to_month ){
                                array_push( $finalResult , $currentBill ) ;
                            }elseif( $bill_month == $to_month ){
                                // check days ( to_day )
                                if( $bill_day <= $to_day ){
                                    array_push( $finalResult , $currentBill ) ;
                                }
                            }
                        }
                    
                    // If From_year is less than To_year
                    }elseif( $from_year < $to_year ){
                        // if bill_year = from_year
                        if( $bill_year == $from_year ){
                            if( $bill_month == $from_month ){ // if bill_month = from_month
                                // check days
                                if( $bill_day >= $from_day ){ 
                                    array_push( $finalResult , $currentBill ) ;
                                }
                            }elseif( $bill_month > $from_month ){ // if bill_month > from_month
                                array_push( $finalResult , $currentBill ) ;
                            }
                        // if (bill_year > from_year) and (bill_year < to_year)
                        }elseif( $bill_year > $from_year && $bill_year < $to_year ){
                            array_push( $finalResult , $currentBill ) ;
                        // if ( bill_year = to_year )
                        }elseif( $bill_year == $to_year ){
                            if( $bill_month < $to_month ){
                                array_push( $finalResult , $currentBill ) ;
                            }elseif( $bill_month == $to_month ){ // check days
                                if( $bill_day <= $to_day ){
                                    array_push( $finalResult , $currentBill ) ;
                                }
                            }
                        }
                    }
                }
                return $finalResult ;

            // Filter bills starting from a Date
            }elseif( $from ){
                $finalResult = [] ;

                $from_date = str_ireplace( "/" , " " , $from ) ;
                $from_date = sscanf( $from_date , "%s %s %s" ) ;
                list( $from_year , $from_month , $from_day ) = $from_date ;
                if( !$from_day ){ $from_day = 1 ; }
                if( !$from_month ){ $from_month = 1 ; }

                for( $i = 0 ; $i < count($bills) ; $i++ ){
                    $currentBill = $bills[$i] ;
                    // check Bill's date
                    $bill_date = $currentBill['date'] ;
                    $bill_date = str_ireplace( "/" , " " , $bill_date ) ;
                    $bill_date = sscanf( $bill_date , "%s %s %s" ) ;
                    list( $bill_year , $bill_month , $bill_day ) = $bill_date ;

                    if( $bill_year > $from_year ){
                        array_push( $finalResult , $currentBill ) ;
                    }elseif( $bill_year == $from_year ){
                        // check months
                        if( $bill_month > $from_month ){
                            array_push( $finalResult , $currentBill ) ;
                        }elseif( $bill_month == $from_month ){
                            // check day
                            if( $bill_day >= $from_day ){
                                array_push( $finalResult , $currentBill ) ;
                            }
                        }
                    }
                }
                return $finalResult ;

            // Filter bills untill a Date
            }elseif( $to ){
                $finalResult = [] ;

                $to_date = str_ireplace( "/" , " " , $to ) ;
                $to_date = sscanf( $to_date , "%s %s %s" ) ;
                list( $to_year , $to_month , $to_day ) = $to_date ;
                if( !$to_day ){ $to_day = 30 ; } 
                if( !$to_month ){ $to_month = 12 ; }

                for( $i = 0 ; $i < count($bills) ; $i++ ){
                    $currentBill = $bills[$i] ;
                    // check Bill's date
                    $bill_date = $currentBill['date'] ;
                    $bill_date = str_ireplace( "/" , " " , $bill_date ) ;
                    $bill_date = sscanf( $bill_date , "%s %s %s" ) ;
                    list( $bill_year , $bill_month , $bill_day ) = $bill_date ;
                    
                    if( $bill_year < $to_year ){
                        array_push( $finalResult , $currentBill ) ;
                    }elseif( $bill_year == $to_year ){
                        // check months
                        if( $bill_month < $to_month ){
                            array_push( $finalResult , $currentBill ) ;
                        }elseif( $bill_month == $to_month ){
                            // check days
                            if( $bill_day <= $to_day ){
                                array_push( $finalResult , $currentBill ) ;
                            }
                        }
                    }
                }
                return $finalResult ;
            }
        
    }
    /*
    //=================================================================================================================================//
    function myCheckDate( $target , $from=null , $to=null ){
        try{
            $target_date = str_ireplace( "/" , " " , $target ) ;
            $target_date = sscanf( $target_date , "%s %s %s" ) ;
            list( $target_year , $target_month , $target_day ) = $target_date ;

            // Case "From" and "To" ( are real )
            if( $from && $to ){
                $from_date = str_ireplace( "/" , " " , $from ) ;
                $from_date = sscanf( $from_date , "%s %s %s" ) ;
                list( $from_year , $from_month , $from_day ) = $from_date ;

                $to_date = str_ireplace( "/" , " " , $to ) ;
                $to_date = sscanf( $to_date , "%s %s %s" ) ;
                list( $to_year , $to_month , $to_day ) = $to_date ;

                // Case ( From-Date > To-Date )
                if( $from_year > $to_year ){
                    throw new Exception( "Invalid year format!" ) ;
                }

                // Case ( From-Date < To-Date )
                if( $from_year < $to_year ){
                    if( $target_year > $from_year && $target_year < $to_year ){
                        return TRUE ;
                    }elseif( $target_year == $from_year ){
                        if( $target_month > $from_month ){ 
                            return TRUE ; 
                        }elseif( $target_month == $from_month ){ 
                            if( $target_day >= $from_day ){ return TRUE ; }
                        }
                    }elseif( $target_year == $to_year ){
                        if( $target_month < $to_month ){
                            return TRUE ;
                        }elseif( $target_month == $to_month ){
                            if( $target_day <= $to_day ){ return TRUE ; }
                        }
                    }
                
                // Case ( From-Date == To-Date )
                }elseif( $from_year == $to_year ){
                    $master_year = $from_year ;
                    if( $target_year == $master_year ){
                        if( $from_month > $to_month ){ throw new exception( "Invalid Month format!" ) ; }
                        if( $from_month < $to_month ){ 
                            if( $target_month > $from_month || $target_month < $from_month ){ echo "this is matches.<br>" ;
                                return TRUE ;
                            }elseif( $target_month == $from_month ){ 
                                if( $target_day >= $from_day ){ return TRUE ; }
                            }elseif( $target_month == $to_month ){ 
                                if( $target_day <= $to_day ){ return TRUE ; }
                            }
                        }elseif( $from_month == $to_month ){ 
                            $master_month = $from_month ;
                            if( $target_month == $master_month ){
                                if( $from_day > $to_day ){ throw new exception( "Invalid Day format!" ) ; }
                                if( $from_day < $to_day ){
                                    if( $target_day >= $from_day && $target_day <= $to_day ){ return TRUE ; }
                                }elseif( $from_day == $to_day ){
                                    $master_day = $from_day ;
                                    if( $target_day == $master_day ){ return TRUE ; }
                                }
                            }
                        }
                    }
                }
            
            // Case only "From" ( is real )
            }elseif( $from ){
                $from_date = str_ireplace( "/" , " " , $from ) ;
                $from_date = sscanf( $from_date , "%s %s %s" ) ;
                list( $from_year , $from_month , $from_day ) = $from_date ;

                if( $target_year > $from_year ){
                    return TRUE ;
                }elseif( $target_year == $from_year ){
                    if( $target_month > $from_month ){
                        return TRUE ;
                    }elseif( $target_month == $from_month ){
                        if( $target_day >= $from_day ){ return TRUE ; }
                    }
                }

            // Case only "To" ( is real )
            }elseif( $to ){
                $to_date = str_ireplace( "/" , " " , $to ) ;
                $to_date = sscanf( $to_date , "%s %s %s" ) ;
                list( $to_year , $to_month , $to_day ) = $to_date ;

                if( $target_year < $to_year ){
                    return TRUE ;
                }elseif( $target_year == $to_year ){
                    if( $target_month < $to_month ){
                        return TRUE ;
                    }elseif( $target_month == $to_month ){
                        if( $target_day <= $to_day ){ return TRUE ; }
                    }
                }
            }
        }catch( Exception $e ){
            return $e -> getMessage() ;
        }
        
    }
    //=================================================================================================================================//


    $bills = [
        [ "ID"=>1 , "userID"=>1 ,"billType"=>"electricity" , "name"=>"" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2021/1/1" , "time"=>"" , "notes"=>"" ] ,
        [ "ID"=>2 , "userID"=>1 ,"billType"=>"water" , "name"=>"" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2021/3/17" , "time"=>"" , "notes"=>"" ] ,
        [ "ID"=>3 , "userID"=>1 ,"billType"=>"internet" , "name"=>"" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2021/4/23" , "time"=>"" , "notes"=>"" ] ,
        [ "ID"=>4 , "userID"=>1 ,"billType"=>"maintenance" , "name"=>"" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2021/6/22" , "time"=>"" , "notes"=>"" ] ,
        [ "ID"=>5 , "userID"=>1 ,"billType"=>"food" , "name"=>"lunch" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2021/8/17" , "time"=>"" , "notes"=>"" ] ,
        [ "ID"=>6 , "userID"=>1 ,"billType"=>"other" , "name"=>"tasaly" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2021/9/20" , "time"=>"" , "notes"=>"" ] ,
        [ "ID"=>7 , "userID"=>1 ,"billType"=>"food" , "name"=>"lunch" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2021/10/21" , "time"=>"" , "notes"=>"" ] ,
        [ "ID"=>8 , "userID"=>1 ,"billType"=>"food" , "name"=>"lunch" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2021/12/17" , "time"=>"" , "notes"=>"" ] ,

        [ "ID"=>9 , "userID"=>1 ,"billType"=>"food" , "name"=>"lunch" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2022/1/1" , "time"=>"" , "notes"=>"" ] ,
        [ "ID"=>10 , "userID"=>1 ,"billType"=>"electricity" , "name"=>"lunch" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2022/1/5" , "time"=>"" , "notes"=>"" ] ,
        [ "ID"=>1 , "userID"=>1 ,"billType"=>"electricity" , "name"=>"" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2022/3/1" , "time"=>"" , "notes"=>"" ] ,
        [ "ID"=>2 , "userID"=>1 ,"billType"=>"water" , "name"=>"" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2022/9/17" , "time"=>"" , "notes"=>"" ] ,
        [ "ID"=>3 , "userID"=>1 ,"billType"=>"internet" , "name"=>"" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2022/10/17" , "time"=>"" , "notes"=>"" ] ,
        [ "ID"=>4 , "userID"=>1 ,"billType"=>"maintenance" , "name"=>"" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2022/12/22" , "time"=>"" , "notes"=>"" ] ,

        [ "ID"=>5 , "userID"=>1 ,"billType"=>"food" , "name"=>"lunch" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2023/3/17" , "time"=>"" , "notes"=>"" ] ,
        [ "ID"=>6 , "userID"=>1 ,"billType"=>"other" , "name"=>"tasaly" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2023/5/20" , "time"=>"" , "notes"=>"" ] ,
        [ "ID"=>7 , "userID"=>1 ,"billType"=>"food" , "name"=>"lunch" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2023/5/21" , "time"=>"" , "notes"=>"" ] ,
        [ "ID"=>8 , "userID"=>1 ,"billType"=>"food" , "name"=>"lunch" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2023/9/17" , "time"=>"" , "notes"=>"" ] ,
        [ "ID"=>9 , "userID"=>1 ,"billType"=>"food" , "name"=>"lunch" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2023/7/1" , "time"=>"" , "notes"=>"" ] ,
        [ "ID"=>10 , "userID"=>1 ,"billType"=>"electricity" , "name"=>"lunch" , "price"=>"150,00" , "paymentMethod"=>"" , "payments"=>"" , "date"=>"2023/8/5" , "time"=>"" , "notes"=>"" ]
    ] ;
    $mytype = 'electricity' ;
    $mydate_from = "2021/3/17" ;
    $mydate_to = "2023/3/17" ;

    // $result = mytest( $bills , null , $mydate_from , $mydate_to ) ;
    echo "<br><hr><br>" ;

    $finalResult = [] ;
    for( $i = 0 ; $i < count($bills) ; $i++ ){
        $currentBill = $bills[$i] ;
        $billDate = $currentBill['date'] ;

        if( myCheckDate($billDate , $mydate_from , $mydate_to) ){
            array_push( $finalResult , $currentBill ) ;
        }
    }
    echo "<b>Date-From</b> : $mydate_from <br> <b>Date-To</b> : $mydate_to <br> <b>Value-Type</b> : $mytype <br>" ;
    echo "<table>" ;
    echo "<tr> <th>ID</th> <th>User_ID</th> <th>Bill_Type</th> <th>Name</th> <th>Price</th> <th>Payment_Method</th> <th>Payments</th> <th>Date</th> <th>Time</th> <th>Notes</th> </tr>" ;
    for( $i = 0 ; $i < count($finalResult) ; $i++ ){
        $current = $finalResult[$i] ;

        $id = $current['ID'] ;
        $userID = $current['userID'] ;
        $billType = $current['billType'] ;
        $name = $current['name'] ;
        $price = $current['price'] ;
        $paymentMethod = $current['paymentMethod'] ;
        $payments = $current['payments'] ;
        $date = $current['date'] ;
        $time = $current['time'] ;
        $notes = $current['notes'] ;

        echo "<tr> <td>$id</td> <td>$userID</td> <td>$billType</td> <td>$name</td> <td>$price</td> <td>$paymentMethod</td> <td>$payments</td> <td>$date</td> <td>$time</td> <td>$notes</td> </tr>" ;
    }
    echo "</table>" ;
    */
?>