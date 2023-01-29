<?php
    session_start() ;
    if( !$_SESSION ){
        header( "location:test.php" ) ;
        exit() ;
    }elseif( !$_SESSION['userID'] ){
        header( "location:test.php" ) ;
        exit() ;
    }
    require_once "../main.php" ;

    # Log-Out Func
    function logOut(){
        session_unset() ;
        session_destroy() ;
        header( "location:test.php" ) ;
        exit() ;
    }

    # Log-Out
    echo "<a href='?q=destroy'>Log out</a><br>" ;
    echo "<hr>" ;
    if( isset( $_GET['q'] ) ){
        logOut() ;
    }
    
    # Log-IN
    $userID = $_SESSION['userID'] ;
    $operations = new operations( $userID ) ;
    if( is_string($operations) ){
        die( $operations ) ;
    }

    // A not important query to show some Unnecessary information
    $companies = $operations -> companies ;
    if( @count($companies) ){
        
        for( $i = 0 ; $i < count($companies) ; $i++ ){
            $current = $companies[$i] ;
            #print_r( $current ) ;
            #echo "<br>" ;
        }
    }

    # Preparring Section "Companies"
    //======================================================================================================================================================================//

    // create company
    if( isset( $_POST['create_a_company'] ) ){
        $companyName = $_POST['company_name'] ;
        $phone = $_POST['company_phone'] ;
        $address = $_POST['company_address'] ;
        $central = $_POST['company_central'] ;
        $governorate = $_POST['company_governorate'] ;
        $email = $_POST['company_email'] ;
        $website = $_POST['company_website'] ;
        $commercialRegistration_NO = $_POST['company_commercialRegistration_NO'] ;
        $taxCard_NO = $_POST['company_taxCard_NO'] ;
        $notes = $_POST['company_notes'] ;
        $evaluation = $_POST['company_evaluation'] ;

        $result = $operations -> createCompany( $companyName , $phone , $address , $central , $governorate , $email , $website , $commercialRegistration_NO , $taxCard_NO , $notes , $evaluation ) ;
        if( is_string($result) ){
            ob_start() ;
            echo $result ;
            $createCompanyResult = ob_get_clean() ;
        }elseif( $result == TRUE ){
            ob_start() ;
            echo "'$companyName' company created successfully." ;
            $createCompanyResult = ob_get_clean() ;
        }
    }

    // modify a company
    if( isset($_GET['modify_company']) ){
        $id = $_GET['modify_company'] ;
        $company_id_to_modify = filterInput( $id ) ;
        $companies = $operations -> companies ;
        for( $i = 0 ; $i < @count($companies) ; $i++ ){
            $current = $companies[$i] ;
            $cID = $current['ID'] ;
            $cID = myEncoding( $cID ) ;
            if( $company_id_to_modify == $cID ){
                foreach( $current as $key => $value ){
                    echo "<b>$key</b> = $value <br>" ;
                }
            }
        }
        
    }
    //---------------------------------------------------------//
    if( isset( $_POST['modify_a_company'] ) ){
        $id = $_POST['company_id'] ;
        $companyName = $_POST['company_name'] ;
        $phone = $_POST['company_phone'] ;
        $address = $_POST['company_address'] ;
        $central = $_POST['company_central'] ;
        $governorate = $_POST['company_governorate'] ;
        $email = $_POST['company_email'] ;
        $website = $_POST['company_website'] ;
        $commercialRegistration_NO = $_POST['company_commercialRegistration_NO'] ;
        $taxCard_NO = $_POST['company_taxCard_NO'] ;
        $notes = $_POST['company_notes'] ;
        $evaluation = $_POST['company_evaluation'] ;
        $result = $operations -> modifyCompany( $id , $companyName , $phone , $address , $central , $governorate , $email , $website , $commercialRegistration_NO , $taxCard_NO , $notes , $evaluation ) ;
        if( is_string($result) ){
            ob_start() ;
            echo $result ;
            $modifyCompanyResult = ob_get_clean() ;
        }elseif( $result == TRUE ){
            ob_start() ;
            echo "'$companyName' company modified successfully." ;
            $modifyCompanyResult = ob_get_clean() ;
            $id=$companyName=$phone=$address=$central=$governorate=$email=$website=$commercialRegistration_NO=$taxCard_NO=$notes=$evaluation= "" ;
        }
    }

    // company search
    if( isset($_POST['search_in_companies']) ){
        $table = "companies" ;
        $search = $_POST['company_search'] ;
        $result = $operations -> resultHandling( $table , $search ) ;
        if( is_string($result) ){
            ob_start() ;
            echo $result ;
            $searchCompany = ob_get_clean() ;
        }elseif( is_array($result) ){
            # ini_set( "url_rewriter.tags" , "a=href,form= " ) ;
            ob_start() ;
            echo "<table>" ;
            echo "<tr> <th>Company Name</th> <th>Phone Number</th> <th>Address</th> <th>Central</th> <th>Governorate</th> <th>Email</th> <th>Website</th> <th>Commercial Registration-NO</th> <th>Tax Card-NO</th> <th>Notes</th> <th>Evaluation</th> <th>Eddit</th> <th>Company Projects</th> </tr>" ;
            for( $i = 0 ; $i < count($result) ; $i++ ){
                $current = $result[$i] ;
                $id = $current['ID'] ;
                $id = myEncoding( $id ) ;
                $name = $current['name'] ;
                $phone = $current['phone'] ;
                $address = $current['address'] ;
                $central = $current['central'] ;
                $governorate = $current['governorate'] ;
                $email = $current['email'] ;
                $website = $current['website'] ;
                $commercialRegistration_NO  = $current['commercialRegistration_NO'] ;
                $taxCard_NO = $current['taxCard_NO'] ;
                $notes = $current['notes'] ;
                $evaluation = $current['evaluation'] ;
                // Output rewriter to validate a certain company-ID
                # output_add_rewrite_var( "modify_company" , $id ) ;
                $eddit = "<a href='?modify_company=$id'> eddit </a>" ;
                $companysProjects = "<a href='?companysProjects=$id'> View </a>" ;
                # ob_flush() ;
                # output_reset_rewrite_vars() ;

                echo "<tr> <td>$name</td> <td>$phone</td> <td>$address</td> <td>$central</td> <td>$governorate</td> <td>$email</td> <td>$website</td> <td>$commercialRegistration_NO</td> <td>$taxCard_NO</td> <td>$notes</td> <td>$evaluation</td> <td>$eddit</td> <td>$companysProjects</td> </tr>" ;
            }
            echo "</table>" ;
            $searchCompany = ob_get_clean() ;
            
        }
    }

    // companies's projects
    if( isset( $_GET['companysProjects'] ) ){
        $companyID = $_GET['companysProjects'] ;
        $companyID = filterInput( $companyID ) ;

        // gitting a company Name
        $companies = $operations -> companies ;
        $companyNameToFind = "" ;
        if( count($companies) ){
            for( $i = 0 ; $i < count($companies) ; $i++ ){
                $current = $companies[$i] ;
                $coID = $current['ID'] ;
                $coID = myEncoding( $coID ) ;
                if( $coID == $companyID ){
                    $companyNameToFind = $current['name'] ;
                    break ;
                }elseif( $i == count($companies)-1 ){
                    if( empty($companyNameToFind) ){
                        ob_start() ;
                        echo "No Registrations Found!" ;
                        $companysProjectsResult = ob_get_clean() ;
                    }
                }
            }
        }

        // gitting a all projects for a company
        $projects = $operations -> projects ;
        $companysProjects = [] ;
        for( $i = 0 ; $i < count($projects) ; $i++ ){
            $current = $projects[$i] ;
            $cID = $current['companyID'] ;
            $cID = myEncoding( $cID ) ;
            if( $cID == $companyID ){
                array_push( $companysProjects , $current ) ;
            }elseif( $i == count($projects)-1 ){
                if( !count($companysProjects) ){
                    ob_start() ;
                    echo "No Results Found!" ;
                    $companysProjectsResult = ob_get_clean() ;
                }
            }
        }
        if( count($companysProjects) ){
            ob_start() ;
            echo "<table>" ;
            echo "<tr> <th>Project Name</th> <th>Company Name</th> <th>Address</th> <th>Central</th> <th>Governorate</th> <th>Delivery Date</th> <th>Notes</th> <th>Evaluation</th> </tr>" ;
            for( $i = 0 ; $i < count($companysProjects) ; $i++ ){
                $current = $companysProjects[$i] ;
                $name = $current['name'] ;
                $companyName = $companyNameToFind ;
                $address = $current['address'] ;
                $central = $current['central'] ;
                $governorate = $current['governorate'] ;
                $deliveryDate = $current['deliveryDate'] ;
                $notes = $current['notes'] ;
                $evaluation = $current['evaluation'] ;
                echo "<tr> <td>$name</td> <td>$companyName</td> <td>$address</td> <td>$central</td> <td>$governorate</td> <td>$deliveryDate</td> <td>$notes</td> <td>$evaluation</td> </tr>" ;
            }
            echo "</table>" ;
            $companysProjectsResult = ob_get_clean() ;
        }else{
            ob_start() ;
            echo "No Projects Found!" ;
            $companysProjectsResult = ob_get_clean() ;
        }

    }

    // all companies
    $companiesTable = "companies" ;
    $result = $operations -> resultHandling( $companiesTable ) ;
    if( is_string($result) ){
        ob_start() ;
        echo $result ;
        $searchCompany = ob_get_clean() ;
    }elseif( is_array($result) ){
        # ini_set( "url_rewriter.tags" , "a=href,form= " ) ;
        ob_start() ;
        echo "<table>" ;
        echo "<tr> <th>Company Name</th> <th>Phone Number</th> <th>Address</th> <th>Central</th> <th>Governorate</th> <th>Email</th> <th>Website</th> <th>Commercial Registration-NO</th> <th>Tax Card-NO</th> <th>Notes</th> <th>Evaluation</th> <th>Eddit</th> <th>Company Projects</th> </tr>" ;
        for( $i = 0 ; $i < count($result) ; $i++ ){
            $current = $result[$i] ;
            $id = $current['ID'] ;
            $id = myEncoding( $id ) ;
            $name = $current['name'] ;
            $phone = $current['phone'] ;
            $address = $current['address'] ;
            $central = $current['central'] ;
            $governorate = $current['governorate'] ;
            $email = $current['email'] ;
            $website = $current['website'] ;
            $commercialRegistration_NO  = $current['commercialRegistration_NO'] ;
            $taxCard_NO = $current['taxCard_NO'] ;
            $notes = $current['notes'] ;
            $evaluation = $current['evaluation'] ;
            // Output rewriter to validate a certain company-ID
            # output_add_rewrite_var( "modify_company" , $id ) ;
            $eddit = "<a href='?modify_company=$id'> eddit </a>" ;
            $companysProjects = "<a href='?companysProjects=$id'> View </a>" ;
            # ob_flush() ;
            # output_reset_rewrite_vars() ;

            echo "<tr> <td>$name</td> <td>$phone</td> <td>$address</td> <td>$central</td> <td>$governorate</td> <td>$email</td> <td>$website</td> <td>$commercialRegistration_NO</td> <td>$taxCard_NO</td> <td>$notes</td> <td>$evaluation</td> <td>$eddit</td> <td>$companysProjects</td> </tr>" ;
        }
        echo "</table>" ;
        $allCompaniesResult = ob_get_clean() ;
        
    }

    
    //======================================================================================================================================================================//
    # Ending Section "Companies"


    # Preparring Section "Projects"
    //======================================================================================================================================================================//

    //getting all companies ( for : create a project form )
    $companies = $operations -> companies ;
    ob_start() ;
    for( $i = 0 ; $i < count($companies) ; $i++ ){
        $current = $companies[$i] ;
        $project_name = $current['name'] ;
        echo "<option value='$project_name'>$project_name</option>" ;
    }
    $companies_to_select = ob_get_clean() ;

    // Create a Project
    if( isset($_POST['create_a_project']) ){
        // delivery date
        $deliveryYear = $_POST['pro_deliveryYear'] ;
        $deliveryMonth = $_POST['pro_deliveryMonth'] ;
        $deliveryDay = $_POST['pro_deliveryDay'] ;
        // payment date
        $paymentYear = $_POST['pro_paymentYear'] ;
        $paymentMonth = $_POST['pro_paymentMonth'] ;
        $paymentDay = $_POST['pro_paymentDay'] ;

        $companyName = $_POST['pro_companyName'] ;
        $name = $_POST['pro_name'] ;
        $address = $_POST['pro_address'] ;
        $central = $_POST['pro_central'] ;
        $governorate = $_POST['pro_governorate'] ;
        $specifications = $_POST['pro_specifications'] ;
        $status = $_POST['pro_status'] ;
        if( !($deliveryYear||$deliveryYear||$deliveryYear) ){
            $deliveryDate = "" ;
        }else{
            $deliveryDate = "$deliveryYear/$deliveryMonth/$deliveryDay" ;
        }
        
        $agreed = $_POST['pro_agreed'] ;
        if( !($paymentYear||$paymentMonth||$paymentDay) ){
            $paymentDate = "" ;
        }else{
            $paymentDate = "$paymentYear/$paymentMonth/$paymentDay" ;
        }
        
        $paymentType = $_POST['pro_paymentType'] ;
        $amount = $_POST['pro_paymentAmount'] ;
        $paymentNotes = $_POST['pro_paymentNotes'] ;
        $photo = $_FILES['pro_photo'] ;
        $design = $_FILES['pro_design'] ;
        $notes = $_POST['pro_notes'] ;
        $evaluation = $_POST['pro_evaluation'] ;

        $result = $operations -> createProject( $companyName , $name , $address , $central , $governorate , $specifications , $status , $deliveryDate , $agreed , $paymentDate , $amount , $paymentType , $paymentNotes , $photo , $design , $notes , $evaluation ) ;
        if( is_string( $result ) ){
            ob_start() ;
            echo $result ;
            $createProject = ob_get_clean() ;
        }elseif( $result === TRUE ){
            ob_start() ;
            echo "New Project Created Successfully." ;
            $createProject = ob_get_clean() ;
        }
    }

    // Modify Project
    if( isset( $_GET['a_project_to_modify'] ) ){
        $aProjectToModifyID = $_GET['a_project_to_modify'] ; # project ID to be modified
    }
    //-----------------------------------//
    if( isset( $_POST['modify_project'] ) ){
        $year = $_POST['modify_pro_year'] ;
        $month = $_POST['modify_pro_month'] ;
        $day = $_POST['modify_pro_day'] ;

        $id = $_POST['modify_pro_id'] ;
        $name = $_POST['modify_pro_name'] ;
        $address = $_POST['modify_pro_address'] ;
        $central = $_POST['modify_pro_central'] ;
        $governorate = $_POST['modify_pro_governorate'] ;
        $deliveryDate = "$year/$month/$day" ;

        if( !($year || $month || $day) ){ 
            $deliveryDate = NULL ; 
        }
        $photos = $_FILES['modify_pro_photos'] ;
        $design = $_FILES['modify_pro_design'] ;
        $notes = $_POST['modify_pro_notes'] ;
        $evaluation = $_POST['modify_pro_evaluation'] ;

        $result = $operations -> modifyProject( $id , $name , $address , $central , $governorate , $deliveryDate , NULL , $photos , $design , $notes , $evaluation  ) ;
        if( $result === TRUE ){
            ob_start() ;
            echo "Project Modified Successfully." ;
            $modifyProjectResult = ob_get_clean() ;
        }elseif( is_string($result) ){
            ob_start() ;
            echo "Failed to modify : $result" ;
            $modifyProjectResult = ob_get_clean() ;
        }
    }

    // All projects
    $allProjects = $operations -> projects ;
    $userAllCompanies = $operations -> companies ;
    if( @count($allProjects) ){
        ob_start() ;
        echo "<table>" ;
        echo "<tr> <th>Project Name</th> <th>Company Name</th> <th>Address</th> <th>Central</th> <th>Governorate</th> <th>Delivery Date</th> <th>Agreed</th> <th>Additions and Subtractions</th> <th>Payed</th> <th>Remaining</th> <th>Status</th> <th>Specifications</th> <th>Photos</th> <th>Design</th> <th>Notes</th> <th>Evaluation</th> </tr>" ;
        for( $i = 0 ; $i < count($allProjects) ; $i++ ){
            $current = $allProjects[$i] ;
            $companyID = $current['companyID'] ;
            // gitting company Name
            $companyName = "" ;
            for( $c = 0 ; $c < count($userAllCompanies) ; $c++ ){
                $currentCompany = $userAllCompanies[$c] ;
                $cID = $currentCompany['ID'] ;
                if( $cID == $companyID ){
                    $companyName = $currentCompany['name'] ;
                    break ;
                }elseif( $c == count($userAllCompanies)-1 ){
                    die( "Company Not Found!" ) ;
                }
            }
            $projectID = $current['ID'] ;
            $projectName = $current['name'] ;
            $projectAddress = $current['address'] ;
            $projectCentral = $current['central'] ;
            $projectGovernorate = $current['governorate'] ;
            $projectDeliveryDate = $current['deliveryDate'] ;
            $projectAgreed = $current['agreed'] ;
            $projectStatus = $current['status'] ;
            $projectPhotos = $current['photos'] ;
            $projectDesign = $current['design'] ;
            $projectNotes = $current['notes'] ;
            $projectEvaluation = $current['evaluation'] ;
            $modify_a_project = "<a href='?a_project_to_modify=$projectID'> Modify </a>" ; # modify a project
        
                    $projectPaymentFile = $current['payments'] ; # to ( receive a new payment ) , and ( preview all-payments ).
                    $additionAndSubtraction = $current['additionAndSubtraction'] ; # to preview ( addition/subtraction ) , AND setting ( addition/subtraction ) file.
                    $projectSpecifications = $current['specifications'] ; # to preview ( specifications ) , AND modify ( specifications ) file.
                    
                    # calculatting Additions and Subtractions
                    $additions = [] ;
                    $subtractions = [] ;
                    if( $file = @fopen( "$additionAndSubtraction" , "r" ) ){
                        while( $line = fgets( $file ) ){
                            $line = sscanf( $line , "%s %s %s %s %s" ) ;
                            list( $id , $amount , $operationType , $date , $notes ) = $line ;
                            $id = stripslashes( $id ) ;
                            $amount = stripslashes( $amount ) ;
                            $operationType = stripslashes( $operationType ) ;
                            
                            $id = str_replace( "_" , " " , $id ) ;
                            $amount = str_replace( "_" , " " , $amount ) ;
                            $operationType = str_replace( "_" , " " , $operationType ) ;
                            $operationType = strtolower( $operationType ) ;
                            if( $id ){
                                if( $operationType == "addition" ){
                                    array_push( $additions , $amount ) ;
                                }elseif( $operationType == "subtraction" ){
                                    array_push( $subtractions , $amount ) ;
                                }
                            }
                        }
                    }
                    // calculate additions
                    if( count($additions) ){
                        $additionsTotal = array_sum( $additions ) ;
                    }else{
                        $additionsTotal = 0 ;
                    }
                    // calculate subtractions
                    if( count($subtractions) ){
                        $subtractionsTotal = array_sum( $subtractions ) ;
                    }else{
                        $subtractionsTotal = 0 ;
                    }
                    $theTotal = @( $additionsTotal - $subtractionsTotal ) ;

                    $subRemaining = @( $projectAgreed + $theTotal ) ;
                    
                    # gitting the total of payd
                    $totalPayed = [] ;
                    if( $projectPaymentFile ){
                        $file = @fopen( "$projectPaymentFile" , "r" ) ;
                        if( $file ){
                            while( !feof($file) ){
                                $line = fgets( $file ) ;
                                $lineData = sscanf( $line , "%s %s %s %s" ) ;
                                list( $id , $date , $amount , $notes ) = $lineData ;
                                if( $id ){
                                    array_push( $totalPayed , $amount ) ;
                                }
                            }
                        }
                    }
                    if( count($totalPayed) ){
                        $payed = array_sum( $totalPayed ) ;
                    }else{
                        $payed = 0 ;
                    }
                    
                    $changeProjectStatus = "<a href='?changeStatus=$projectID'> change </a>" ; # change project status
                    $receiveACachPayment = "<a href='?receiveACachePayment=$projectID'> Receive </a>" ; # receive a payment
                    $viewAllPayments = "<a href='?viewAllPayments=$projectID' title='View all Payments'> $payed </a>" ; # preview all payments
                    $settingAnAdditionAndSubtraction = "<a href='?addAndSub=$projectID'> add </a>" ; # setting an addition and subtraction
                    $viewAdditionsAndSubtractions = "<a href='?viewAddAndSub=$projectID'> $theTotal </a>" ; # preview all additions and subtractions
                    $modifyProjectSpecifications = "<a href='?modifyProjectSpecifications=$projectID'> Modify Content </a>" ; # modify specifications
                    $viewProjectSpecifications = "<a href='?viewProjectSpecifications=$projectID'> Read Content</a>" ; # preview specifications

                    # gitting the remaining amount
                    $projectRemaining = @($subRemaining - $payed) ; 
                    if( is_float($projectRemaining) ){
                        $projectRemaining = number_format( $projectRemaining , 2 , "." , " " ) ;
                    }else{
                        $projectRemaining = number_format( $projectRemaining , 0 , NULL , " " ) ;
                    }
                    
                    echo "
                            <tr> 
                                <td>$projectName</td> <td>$companyName</td> <td>$projectAddress</td> <td>$projectCentral</td> <td>$projectGovernorate</td> <td>$projectDeliveryDate</td> <td>$projectAgreed</td> <td>$viewAdditionsAndSubtractions<br><hr>$settingAnAdditionAndSubtraction</td> <td>$viewAllPayments<br><hr>$receiveACachPayment</td> <td>$projectRemaining</td> <td>$projectStatus<br><hr>$changeProjectStatus</td> <td>$viewProjectSpecifications <br> $modifyProjectSpecifications </td> <td>$projectPhotos</td> <td>$projectDesign</td> <td>$projectNotes</td> <td>$projectEvaluation</td> 
                                <td>$modify_a_project</td>
                            </tr>
                        " ;
                }
                echo "</table>" ;
        $allProjectsResults = ob_get_clean() ;
    }else{
        ob_start() ;
        echo "No Result Found!" ;
        $allProjectsResults = ob_get_clean() ;
    }
    

    // Search projects
    if( isset( $_POST['search_in_projects'] ) ){
        $table = "projects" ;
        $search = $_POST['projects_search'] ;

        $userAllCompanies = $operations -> companies ;
        $result = $operations -> resultHandling( $table , $search ) ;
        if( is_string($result) ){
            ob_start() ;
            echo "$result" ;
            $projectsSearchResult = ob_get_clean() ;
        }elseif( is_array($result) ){
            if( count($result) ){
                ob_start() ;
                echo "<h3> All Results </h3>" ;
                echo "<table>" ;
                echo "<tr> <th>Project Name</th> <th>Company Name</th> <th>Address</th> <th>Central</th> <th>Governorate</th> <th>Delivery Date</th> <th>Agreed</th> <th>Additions and Subtractions</th> <th>Payed</th> <th>Remaining</th> <th>Status</th> <th>Specifications</th> <th>Photos</th> <th>Design</th> <th>Notes</th> <th>Evaluation</th> </tr>" ;
                for( $i = 0 ; $i < count($result) ; $i++ ){
                    $current = $result[$i] ;
                    $companyID = $current['companyID'] ;
                    // gitting company Name
                    $companyName = "" ;
                    for( $c = 0 ; $c < count($userAllCompanies) ; $c++ ){
                        $currentCompany = $userAllCompanies[$c] ;
                        $cID = $currentCompany['ID'] ;
                        if( $cID == $companyID ){
                            $companyName = $currentCompany['name'] ;
                            break ;
                        }elseif( $c == count($userAllCompanies)-1 ){
                            die( "Company Not Found!" ) ;
                        }
                    }
                    $projectID = $current['ID'] ;
                    $projectName = $current['name'] ;
                    $projectAddress = $current['address'] ;
                    $projectCentral = $current['central'] ;
                    $projectGovernorate = $current['governorate'] ;
                    $projectDeliveryDate = $current['deliveryDate'] ;
                    $projectAgreed = $current['agreed'] ;
                    $projectStatus = $current['status'] ;
                    $projectPhotos = $current['photos'] ;
                    $projectDesign = $current['design'] ;
                    $projectNotes = $current['notes'] ;
                    $projectEvaluation = $current['evaluation'] ;
                    $modify_a_project = "<a href='?a_project_to_modify=$projectID'> Modify </a>" ; # modify a project
        
                    $projectPaymentFile = $current['payments'] ; # to ( receive a new payment ) , and ( preview all-payments ).
                    $additionAndSubtraction = $current['additionAndSubtraction'] ; # to preview ( addition/subtraction ) , AND setting ( addition/subtraction ) file.
                    $projectSpecifications = $current['specifications'] ; # to preview ( specifications ) , AND modify ( specifications ) file.
                    
                    # calculatting Additions and Subtractions
                    $additions = [] ;
                    $subtractions = [] ;
                    if( $file = @fopen( "$additionAndSubtraction" , "r" ) ){
                        while( $line = fgets( $file ) ){
                            $line = sscanf( $line , "%s %s %s %s %s" ) ;
                            list( $id , $amount , $operationType , $date , $notes ) = $line ;
                            $id = stripslashes( $id ) ;
                            $amount = stripslashes( $amount ) ;
                            $operationType = stripslashes( $operationType ) ;
                            
                            $id = str_replace( "_" , " " , $id ) ;
                            $amount = str_replace( "_" , " " , $amount ) ;
                            $operationType = str_replace( "_" , " " , $operationType ) ;
                            $operationType = strtolower( $operationType ) ;
                            if( $id ){
                                if( $operationType == "addition" ){
                                    array_push( $additions , $amount ) ;
                                }elseif( $operationType == "subtraction" ){
                                    array_push( $subtractions , $amount ) ;
                                }
                            }
                        }
                    }
                    // calculate additions
                    if( count($additions) ){
                        $additionsTotal = array_sum( $additions ) ;
                    }else{
                        $additionsTotal = 0 ;
                    }
                    // calculate subtractions
                    if( count($subtractions) ){
                        $subtractionsTotal = array_sum( $subtractions ) ;
                    }else{
                        $subtractionsTotal = 0 ;
                    }
                    $theTotal = @( $additionsTotal - $subtractionsTotal ) ;

                    $subRemaining = @( $projectAgreed + $theTotal ) ;
                    
                    # gitting the total of payd
                    $totalPayed = [] ;
                    if( $projectPaymentFile ){
                        $file = @fopen( "$projectPaymentFile" , "r" ) ;
                        if( $file ){
                            while( !feof($file) ){
                                $line = fgets( $file ) ;
                                $lineData = sscanf( $line , "%s %s %s %s" ) ;
                                list( $id , $date , $amount , $notes ) = $lineData ;
                                if( $id ){
                                    array_push( $totalPayed , $amount ) ;
                                }
                            }
                        }
                    }
                    if( count($totalPayed) ){
                        $payed = array_sum( $totalPayed ) ;
                    }else{
                        $payed = 0 ;
                    }
                    
                    $changeProjectStatus = "<a href='?changeStatus=$projectID'> change </a>" ; # change project status
                    $receiveACachPayment = "<a href='?receiveACachePayment=$projectID'> Receive </a>" ; # receive a payment
                    $viewAllPayments = "<a href='?viewAllPayments=$projectID' title='View all Payments'> $payed </a>" ; # preview all payments
                    $settingAnAdditionAndSubtraction = "<a href='?addAndSub=$projectID'> add </a>" ; # setting an addition and subtraction
                    $viewAdditionsAndSubtractions = "<a href='?viewAddAndSub=$projectID'> $theTotal </a>" ; # preview all additions and subtractions
                    $modifyProjectSpecifications = "<a href='?modifyProjectSpecifications=$projectID'> Modify Content </a>" ; # modify specifications
                    $viewProjectSpecifications = "<a href='?viewProjectSpecifications=$projectID'> Read Content</a>" ; # preview specifications

                    # gitting the remaining amount
                    $projectRemaining = @($subRemaining - $payed) ; 
                    if( is_float($projectRemaining) ){
                        $projectRemaining = number_format( $projectRemaining , 2 , "." , " " ) ;
                    }else{
                        $projectRemaining = number_format( $projectRemaining , 0 , NULL , " " ) ;
                    }
                    
                    echo "
                            <tr> 
                                <td>$projectName</td> <td>$companyName</td> <td>$projectAddress</td> <td>$projectCentral</td> <td>$projectGovernorate</td> <td>$projectDeliveryDate</td> <td>$projectAgreed</td> <td>$viewAdditionsAndSubtractions<br><hr>$settingAnAdditionAndSubtraction</td> <td>$viewAllPayments<br><hr>$receiveACachPayment</td> <td>$projectRemaining</td> <td>$projectStatus<br><hr>$changeProjectStatus</td> <td>$viewProjectSpecifications <br> $modifyProjectSpecifications </td> <td>$projectPhotos</td> <td>$projectDesign</td> <td>$projectNotes</td> <td>$projectEvaluation</td> 
                                <td>$modify_a_project</td>
                            </tr>
                        " ;
                }
                echo "</table>" ;
                $projectsSearchResult = ob_get_clean() ;
            }
        }
        
    }

    // Dissplay Project's content accurding to 'status'
    if( isset( $_GET['projectsValue'] ) ){
        $table = "projects" ;
        $value = $_GET['projectsValue'] ;
        switch( $value ){
            case "allProjects" : $allProjects = $operations -> resultHandling( $table , null , "allProjects" ) ; break ;
            case "currentProjects" : $allProjects = $operations -> resultHandling( $table , null , "currentProjects" ) ; break ;
            case "deliveredProjects" : $allProjects = $operations -> resultHandling( $table , null , "delivered" ) ; break ;
            case "canceledProjects" : $allProjects = $operations -> resultHandling( $table , null , "canceled" ) ; break ;
        }

        $userAllCompanies = $operations -> companies ;
        if( @count($allProjects) ){
            ob_start() ;
            echo "<table>" ;
            echo "<tr> <th>Project Name</th> <th>Company Name</th> <th>Address</th> <th>Central</th> <th>Governorate</th> <th>Delivery Date</th> <th>Agreed</th> <th>Additions and Subtractions</th> <th>Payed</th> <th>Remaining</th> <th>Status</th> <th>Specifications</th> <th>Photos</th> <th>Design</th> <th>Notes</th> <th>Evaluation</th> </tr>" ;
            for( $i = 0 ; $i < count($allProjects) ; $i++ ){
                $current = $allProjects[$i] ;
                $companyID = $current['companyID'] ;
                // gitting company Name
                $companyName = "" ;
                for( $c = 0 ; $c < count($userAllCompanies) ; $c++ ){
                    $currentCompany = $userAllCompanies[$c] ;
                    $cID = $currentCompany['ID'] ;
                    if( $cID == $companyID ){
                        $companyName = $currentCompany['name'] ;
                        break ;
                    }elseif( $c == count($userAllCompanies)-1 ){
                        die( "Company Not Found!" ) ;
                    }
                }
                $projectID = $current['ID'] ;
                $projectName = $current['name'] ;
                $projectAddress = $current['address'] ;
                $projectCentral = $current['central'] ;
                $projectGovernorate = $current['governorate'] ;
                $projectDeliveryDate = $current['deliveryDate'] ;
                $projectAgreed = $current['agreed'] ;
                $projectStatus = $current['status'] ;
                $projectPhotos = $current['photos'] ;
                $projectDesign = $current['design'] ;
                $projectNotes = $current['notes'] ;
                $projectEvaluation = $current['evaluation'] ;
                $modify_a_project = "<a href='?a_project_to_modify=$projectID'> Modify </a>" ; # modify a project
            
                $projectPaymentFile = $current['payments'] ; # to ( receive a new payment ) , and ( preview all-payments ).
                $additionAndSubtraction = $current['additionAndSubtraction'] ; # to preview ( addition/subtraction ) , AND setting ( addition/subtraction ) file.
                $projectSpecifications = $current['specifications'] ; # to preview ( specifications ) , AND modify ( specifications ) file.
                
                # calculatting Additions and Subtractions
                $additions = [] ;
                $subtractions = [] ;
                if( $file = @fopen( "$additionAndSubtraction" , "r" ) ){
                    while( $line = fgets( $file ) ){
                        $line = sscanf( $line , "%s %s %s %s %s" ) ;
                        list( $id , $amount , $operationType , $date , $notes ) = $line ;
                        $id = stripslashes( $id ) ;
                        $amount = stripslashes( $amount ) ;
                        $operationType = stripslashes( $operationType ) ;
                        
                        $id = str_replace( "_" , " " , $id ) ;
                        $amount = str_replace( "_" , " " , $amount ) ;
                        $operationType = str_replace( "_" , " " , $operationType ) ;
                        $operationType = strtolower( $operationType ) ;
                        if( $id ){
                            if( $operationType == "addition" ){
                                array_push( $additions , $amount ) ;
                            }elseif( $operationType == "subtraction" ){
                                array_push( $subtractions , $amount ) ;
                            }
                        }
                    }
                }
                // calculate additions
                if( count($additions) ){
                    $additionsTotal = array_sum( $additions ) ;
                }else{
                    $additionsTotal = 0 ;
                }
                // calculate subtractions
                if( count($subtractions) ){
                    $subtractionsTotal = array_sum( $subtractions ) ;
                }else{
                    $subtractionsTotal = 0 ;
                }
                $theTotal = @( $additionsTotal - $subtractionsTotal ) ;

                $subRemaining = @( $projectAgreed + $theTotal ) ;
                
                # gitting the total of payd
                $totalPayed = [] ;
                if( $projectPaymentFile ){
                    $file = @fopen( "$projectPaymentFile" , "r" ) ;
                    if( $file ){
                        while( !feof($file) ){
                            $line = fgets( $file ) ;
                            $lineData = sscanf( $line , "%s %s %s %s" ) ;
                            list( $id , $date , $amount , $notes ) = $lineData ;
                            if( $id ){
                                array_push( $totalPayed , $amount ) ;
                            }
                        }
                    }
                }
                if( count($totalPayed) ){
                    $payed = array_sum( $totalPayed ) ;
                }else{
                    $payed = 0 ;
                }
                
                $changeProjectStatus = "<a href='?changeStatus=$projectID'> change </a>" ; # change project status
                $receiveACachPayment = "<a href='?receiveACachePayment=$projectID'> Receive </a>" ; # receive a payment
                $viewAllPayments = "<a href='?viewAllPayments=$projectID' title='View all Payments'> $payed </a>" ; # preview all payments
                $settingAnAdditionAndSubtraction = "<a href='?addAndSub=$projectID'> add </a>" ; # setting an addition and subtraction
                $viewAdditionsAndSubtractions = "<a href='?viewAddAndSub=$projectID'> $theTotal </a>" ; # preview all additions and subtractions
                $modifyProjectSpecifications = "<a href='?modifyProjectSpecifications=$projectID'> Modify Content </a>" ; # modify specifications
                $viewProjectSpecifications = "<a href='?viewProjectSpecifications=$projectID'> Read Content</a>" ; # preview specifications

                # gitting the remaining amount
                $projectRemaining = @($subRemaining - $payed) ; 
                if( is_float($projectRemaining) ){
                    $projectRemaining = number_format( $projectRemaining , 2 , "." , " " ) ;
                }else{
                    $projectRemaining = number_format( $projectRemaining , 0 , NULL , " " ) ;
                }
                
                echo "
                        <tr> 
                            <td>$projectName</td> <td>$companyName</td> <td>$projectAddress</td> <td>$projectCentral</td> <td>$projectGovernorate</td> <td>$projectDeliveryDate</td> <td>$projectAgreed</td> <td>$viewAdditionsAndSubtractions<br><hr>$settingAnAdditionAndSubtraction</td> <td>$viewAllPayments<br><hr>$receiveACachPayment</td> <td>$projectRemaining</td> <td>$projectStatus<br><hr>$changeProjectStatus</td> <td>$viewProjectSpecifications <br> $modifyProjectSpecifications </td> <td>$projectPhotos</td> <td>$projectDesign</td> <td>$projectNotes</td> <td>$projectEvaluation</td> 
                            <td>$modify_a_project</td>
                        </tr>
                    " ;
            }
            echo "</table>" ;
            $projectsStatusResults = ob_get_clean() ;
        }else{
            ob_start() ;
            echo "No Result Found!" ;
            $projectsStatusResults = ob_get_clean() ;
        }
    }


    // Change a Project status
    if( isset( $_GET['changeStatus'] ) ){
        $projectIDToChangeStatus = $_GET['changeStatus'] ;
    }
    //--------------------------------//
    if( isset( $_POST['projectToChangeStatus'] ) ){
        $projectID = $_POST["projectID_to_changeStatus"] ; # project ID for Change-Project-Status Form
        $projectStatus = $_POST['project_status_to_be_changed'] ;
        $result = $operations -> modifyProject( $projectID , NULL , NULL , NULL , NULL , NULL , $projectStatus ) ;
        if( $result === TRUE ){
            ob_start() ;
            echo "Project Status Changed Successfully." ;
            $aProjectToChangeStatus = ob_get_clean() ;
        }elseif( is_string($result) ){
            ob_start() ;
            echo "$result" ;
            $aProjectToChangeStatus = ob_get_clean() ;
        }
    }


    // Modify a Project specifications
    if( isset( $_GET['modifyProjectSpecifications'] ) ){
        $targetSpecificationsFile = $_GET['modifyProjectSpecifications'] ;
    }
    //----------------------------------//
    if( isset( $_POST['modify_ProjectSpecifications'] ) ){
        $projectID = $_POST['specificationsToFetch'] ;
        $content = $_POST['newProjectSpecifications'] ;
        $result = $operations -> modifyProjectSpecifications( $projectID , $content ) ;
        if( $result == TRUE ){
            ob_start() ;
            echo "Specifications File Modified Successfully!" ;
            $modifyProjectSpecificationsFileResult = ob_get_clean() ;
        }elseif( !$result ){
            ob_start() ;
            echo "Failed to Modify!" ;
            $modifyProjectSpecificationsFileResult = ob_get_clean() ;
        }elseif( is_string($result) ){
            ob_start() ;
            echo $result ;
            $modifyProjectSpecificationsFileResult = ob_get_clean() ; ;
        }
    }

    // Preview a Project specifications
    if( isset( $_GET['viewProjectSpecifications'] ) ){
        $projectID = $_GET['viewProjectSpecifications'] ;
        $result = $operations -> viewProjectsSpecifications( $projectID ) ;
        ob_start() ;
        if( is_file($result) ){
            if( $file = fopen( "$result" , "r" ) ){
                while( $line = fgets($file) ){
                    $line = stripslashes( $line ) ;
                    echo "$line <br>" ;
                }
                $viewProjectSpecificationsResult = ob_get_clean() ;
            }elseif( !$result ){
                echo "No Data Were Found!" ;
                $viewProjectSpecificationsResult = ob_get_clean() ;
            }elseif( is_string($result) ){
                echo $result ;
                $viewProjectSpecificationsResult = ob_get_clean() ;
            }
        }
    }


    // Receive a Payment
    if( isset( $_GET['receiveACachePayment'] ) ){
        $paymentsToSelect = $_GET['receiveACachePayment'] ;
    }
    //-------------------------------------//
    if( isset( $_POST['receive_a_new_payment'] ) ){
        $p_year = $_POST['projects_payments_paymentYear'] ;
        $p_month = $_POST['projects_payments_paymentMonth'] ;
        $p_day = $_POST['projects_payments_paymentDay'] ;

        $projectID = $_POST['projects_payments_file'] ;
        $paymentAmount = $_POST['projects_payments_paymentAmount'] ;
        $paymentType = $_POST['projects_paymentType'] ;
        $paymentNotes = $_POST['projects_payments_paymentNotes'] ;
        if( !($p_year && $p_month && $p_day) ){
            $newPaymentDate = "" ;
        }else{
            $newPaymentDate = "$p_year/$p_month/$p_day" ;
        }
        $result = $operations -> receiveNewPayment( $projectID , $newPaymentDate , $paymentAmount , $paymentType , $paymentNotes ) ;
        if( $result === TRUE ){
            ob_start() ;
            echo "New Payment Stored successfully." ;
            $receiveANewPaymentResult = ob_get_clean() ;
        }elseif( is_string( $result ) ){
            ob_start() ;
            echo "$result" ;
            $receiveANewPaymentResult = ob_get_clean() ;
        }
    }

    // Preview all payments
    if( isset( $_GET['viewAllPayments'] ) ){
        $projectID = $_GET['viewAllPayments'] ;
        $result = $operations -> previewAllPayments( $projectID ) ;
        if( is_string( $result ) ){
            ob_start() ;
            echo $result ;
            $viewAllPaymentsResults = ob_get_clean() ;
        }elseif( is_array($result) || !$result ){
            ob_start() ;
            if( !count($result) ){
                echo "No Payments Found." ;
                $viewAllPaymentsResults = ob_get_clean() ;
            }else{
                echo "<table>" ;
                echo "<tr> <th>Date</th> <th>Amount</th> <th>Payment Type</th> <th>Notes</th> </tr>" ;
                for( $i = 0 ; $i < count($result) ; $i++ ){
                    $current = $result[$i] ;
                    $date = $current['date'] ;
                    $amount = $current['amount'] ;
                    $paymentType = $current['paymentType'] ;
                    $notes = $current['notes'] ;
                    echo "<tr> <td>$date</td> <td>$amount</td> <td>$paymentType</td> <td>$notes</td> </tr>" ;
                }
                $viewAllPaymentsResults = ob_get_clean() ;
            }
        }
    }


    // Setting an addition_and_subtraction for a Project
    if( isset( $_GET['addAndSub'] ) ){
        $additionAndSubtractionFile = $_GET['addAndSub'] ;
    }
    //------------------------------------------------//
    if( isset( $_POST['setAddAndSub'] ) ){
        $year = $_POST['addAndSubYear'] ;
        $month = $_POST['addAndSubMonth'] ;
        $day = $_POST['addAndSubDay'] ;

        $projectID = $_POST['addAndSubFile'] ;
        $amount = $_POST['addAndSubAmount'] ;
        $operationType = $_POST['addAndSubOperationsType'] ;
        $date = "$year/$month/$day" ;
        $notes = $_POST['addAndSubNotes'] ;

        $result = $operations -> setProjectAdditions( $projectID , $amount , $operationType , $date , $notes ) ;
        ob_start() ;
        if( $result === TRUE ){
            echo "Done." ;
            $settingAdditionAndSubtractionResults = ob_get_clean() ;
        }elseif( is_string( $result ) ){
            echo "$result" ;
            $settingAdditionAndSubtractionResults = ob_get_clean() ;
        }else{
            echo "Failed!" ;
            $settingAdditionAndSubtractionResults = ob_get_clean() ;
        }
    }

    
    // Preview addition/subtraction of a Project
    if( isset( $_GET['viewAddAndSub'] ) ){
        $projectID = $_GET['viewAddAndSub'] ;
        $result = $operations -> getProjectAdditions( $projectID ) ;
        ob_start() ;
        if( is_array($result) ){
            echo "<table>" ;
            echo "<tr> <th>Amount</th> <th>Operation Type</th> <th>Date</th> <th>Notes</th> </tr>" ;
            for( $i = 0 ; $i < count($result) ; $i++ ){
                $current = $result[$i] ;
                $amount = $current['amount'] ;
                $operationType = $current['operationType'] ;
                $date = $current['date'] ;
                $notes = $current['notes'] ;
                echo "<tr> <td>$amount</td> <td>$operationType</td> <td>$date</td> <td>$notes</td> </tr>" ;
            }
            $viewAddAndSubResults = ob_get_clean() ;
        }elseif( is_string($result) ){
            echo $result ;
            $viewAddAndSubResults = ob_get_clean() ;
        }elseif( $result == FALSE ){
            echo "No Results Found!" ;
            $viewAddAndSubResults = ob_get_clean() ;
        }
    }

?>