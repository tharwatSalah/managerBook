<?php
    require_once "ob.php" ;
     
    $thisPage = "companies.php" ;
    setcookie( "targetPage" , $thisPage ) ;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Companies</title>
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
        <!-- Modify Company Form --> 
        <div class="example">
            <div>
                <h3>Modify Company</h3>
                <form method="post" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] );?>">
                    <input type="hidden" name="company_id" value="<?=$company_id_to_modify?>"> <br>
                    <input type="text" name="company_name" placeholder="Company Name"> <br>
                    <input type="text" name="company_phone" placeholder="Phone Number"> <br>
                    <input type="text" name="company_address" placeholder="Address"> <br>
                    <input type="text" name="company_central" placeholder="Central"> <br>
                    <input type="text" name="company_governorate" placeholder="Governorate"> <br>
                    <input type="email" name="company_email" placeholder="E-mail"> <br>
                    <input type="url" name="company_website" placeholder="Website"> <br>
                    <input type="text" name="company_commercialRegistration_NO" placeholder="Commercial Registration NO"> <br>
                    <input type="text" name="company_taxCard_NO" placeholder="Tax Card NO"> <br>
                    <textarea name="company_notes" placeholder="Notes"></textarea> <br>
                    <input type="text" name="company_evaluation" placeholder="Evaluation"> <br>
                    <input type="submit" name="modify_a_company" value="Modify Company"> <?=@$modifyCompanyResult?>
                </form>
            </div>
        </div>
        <br>

        <!-- Dissplay all Companies Result --> 
        <div class="example">
            <h3>All Companies</h3>
            <div>
                <?= @$allCompaniesResult ?>
            </div>
        </div>
        <br>

        <!-- Dissplay all Projects for a company -->
        <div class="example">
            <div>
                <?= @$companysProjectsResult ?>
            </div>
        </div>
        <br>

        <!-- Create Company Form --> 
        <div class="example">
          <div>
                <h3>Create a Company</h3>
                <form method="post" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] );?>">
                    <input type="text" name="company_name" placeholder="Company Name"> <br>
                    <input type="text" name="company_phone" placeholder="Phone Number"> <br>
                    <input type="text" name="company_address" placeholder="Address"> <br>
                    <input type="text" name="company_central" placeholder="Central"> <br>
                    <input type="text" name="company_governorate" placeholder="Governorate"> <br>
                    <input type="email" name="company_email" placeholder="E-mail"> <br>
                    <input type="url" name="company_website" placeholder="Website"> <br>
                    <input type="text" name="company_commercialRegistration_NO" placeholder="Commercial Registration NO"> <br>
                    <input type="text" name="company_taxCard_NO" placeholder="Tax Card NO"> <br>
                    <textarea name="company_notes" placeholder="Notes"></textarea> <br>
                    <input type="text" name="company_evaluation" placeholder="Evaluation"> <br>
                    <input type="submit" name="create_a_company" value="Create Company"> <?=@$createCompanyResult?>
                </form>
            </div>  
        </div>
        <br>
        
        <!-- Company search --> 
        <div class="example">
            <div>
                <h3>Search in Companies</h3>
                <form method="post" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] );?>">
                    <input type="text" name="company_search" placeholder="Search"> <br>
                    <input type="submit" name="search_in_companies" value="Search">
                </form>
                <?=@$searchCompany?>
            </div>
        </div>
        
    </body>
</html>