<?php
    require_once "ob.php" ;
    $thisPage = "projects.php" ;
    setcookie( "targetPage" , $thisPage ) ;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Projects</title>
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

            a:hover{ color : red ; }
            comment{ color: darkgreen; }
            button:hover{ background-color: rgb(5, 235, 5);}
            table{border: 3px solid rgb(193, 205, 209);border-collapse:collapse; max-width:90%; background-color: rgb(235, 233, 233); /*margin:auto;*/ }
            td{border:1px solid grey; padding: 10px; size:5px ;}
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
        <!-- Top Navegation Bar -->
        <div class="example">
            <div>
                <ul>
                    <li> <a href="?projectsValue=allProjects"> All </a> </li>
                    <li> <a href="?projectsValue=currentProjects"> Current Projects </a> </li>
                    <li> <a href="?projectsValue=deliveredProjects"> Delivered Projects </a> </li>
                    <li> <a href="?projectsValue=canceledProjects"> Canceled Projects </a> </li>
                </ul>
            </div>
            <?= @$projectsStatusResults ?>
        </div>
        <br>

        <!-- Modify A Project --> 
        <div class="example">
            <div>
                <h4>Modify a Project</h4>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ; ?>" enctype="multipart/form-data">
                    <input type="hidden" name="modify_pro_id" value="<?= @$aProjectToModifyID ?>" > <br>
                    <input type="txt" name="modify_pro_name" placeholder="Project Name"> <br>
                    <input type="txt" name="modify_pro_address" placeholder="Address"> <br>
                    <input type="txt" name="modify_pro_central" placeholder="Central"> <br>
                    <input type="txt" name="modify_pro_governorate" placeholder="Governorate"> <br>
                    <label>Delivery Date</label>
                    <input type="txt" name="modify_pro_year" placeholder="Year" size="5"> 
                    <input type="txt" name="modify_pro_month" placeholder="Month" size="3"> 
                    <input type="txt" name="modify_pro_day" placeholder="Day" size="3"> <br>

                    <label>Photos : </label> <input type="file" name="modify_pro_photos"> <br>
                    <label>Design : </label> <input type="file" name="modify_pro_design"> <br>
                    <textarea name="modify_pro_notes" placeholder="Notes"></textarea> <br>
                    <input type="txt" name="modify_pro_evaluation" placeholder="Evaluation"> <br>
                    <input type="submit" name="modify_project" value="Modify Project">
                </form>
                <?= @$modifyProjectResult ?>
            </div>
        </div>
        <br>

        <!-- All Projects -->
        <div>
            <div class="example">
                <h3>All Projects</h3>
                <?= @$allProjectsResults ?>
            </div>
        </div>
        <br>

        <!-- Create a Project --> 
        <div class="example">
            <h3>Create a Project</h3>
            <div>
                <form method="post" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ) ;?>" enctype="multipart/form-data" >
                    <select name="pro_companyName" placeholder="Company Name">
                        <?=$companies_to_select?>
                    </select>
                    <br>
                    <input type="txt" name="pro_name" placeholder="Project Name"> <br>
                    <input type="txt" name="pro_address" placeholder="Address"> <br>
                    <input type="txt" name="pro_central" placeholder="Central"> <br>
                    <input type="txt" name="pro_governorate" placeholder="Governorate"> <br>
                    <textarea name="pro_specifications" placeholder="Specifications"></textarea> <br>
                    <lable>Status : </lable>
                    <select name="pro_status">
                        <option value="New" selected>New</option>
                        <option value="Delivered">Delivered</option>
                        <option value="Canceled">Canceled</option>
                    </select><br>
                    <lable>Delivery Date : </lable>
                    <input type="txt" name="pro_deliveryYear" placeholder="Year" size="5">
                    <input type="txt" name="pro_deliveryMonth" placeholder="Month" size="3">
                    <input type="txt" name="pro_deliveryDay" placeholder="Day" size="3"> <br>
                    <label>Agreed : </label>
                    <input type="txt" name="pro_agreed" placeholder="Agreed"> <br>

                    <fieldset>
                        <legend>Payment</legend>
                        <lable>Date : </lable>
                        <input type="txt" name="pro_paymentYear" placeholder="Year" size="5">
                        <input type="txt" name="pro_paymentMonth" placeholder="Month" size="3">
                        <input type="txt" name="pro_paymentDay" placeholder="Day" size="3">
                        <br>
                        
                        <input type="txt" name="pro_paymentAmount" placeholder="Amount"><br>
                        <lable>Payment Type : </lable> 
                        <select name="pro_paymentType">
                            <option value="Cache">Cache</option>
                            <option value="Bank account">Bank Account</option>
                            <option value="Other">Other</option>
                        </select>
                        <br>
                        <textarea name="pro_paymentNotes" placeholder="Notes"></textarea><br>
                    </fieldset>
                    <label>Image : </label> <input type="file" name="pro_photo"><br>
                    <lable>Design : </lable> <input type="file" name="pro_design"><br>
                    <textarea name="pro_notes" placeholder="Notes"></textarea><br>
                    <input type="txt" name="pro_evaluation" placeholder="Evaluation"><br>
                    <input type="submit" name="create_a_project" value="Create Project">
                </form>
                <?=@$createProject?> 
            </div>
        </div>
        <br>

        <!-- Projects search -->
        <div>
            <h3>Projects Search</h3>
            <div class="example">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" >
                    <input type="txt" name="projects_search" placeholder="Search"> <br>
                    <input type="submit" name="search_in_projects" value="Search"> <br>
                </form>
                <?= @$projectsSearchResult ?>
            </div>
        </div>
        <br>

        <!-- Change Project Status --> 
        <div class="example">
            <h3>Change Project Status</h3>
            <div>
                <form method="post" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ) ; ?>">
                    <input type="hidden" name="projectID_to_changeStatus" value="<?= @$projectIDToChangeStatus ?>"> <br>
                    <lable>Status : </lable>
                    <select name="project_status_to_be_changed">
                        <option value="New" selected>New</option>
                        <option value="Delivered">Delivered</option>
                        <option value="Canceled">Canceled</option>
                    </select><br>
                    <input type="submit" name="projectToChangeStatus" value="Change Status">
                </form>
                <?= @$aProjectToChangeStatus ?>
            </div>
        </div>
        <br>

        <!-- Modify a Project specifications --> 
        <div class="example">
            <h3>Modify Specifications File</h3>
            <div>
                <form method="post" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ) ; ?>" >
                    <input type="hidden" name="specificationsToFetch" value="<?= @$targetSpecificationsFile ?>" > <br>
                    <textarea name="newProjectSpecifications" placeholder="Specifications..."></textarea> <br>
<<<<<<< HEAD
                    <input type="submit" name="modify_ProjectSpecifications" value="Change">
=======
                    <input type="submit" name="modifyProjectSpecifications" value="Change">
>>>>>>> 9b66a40846db7a190dd7236db37ad8241499e53d
                </form>
                <?= @$modifyProjectSpecificationsFileResult ?>
            </div>
        </div>
        <br>

        <!-- View a Project's Specifications --> 
        <div class="example">
            <h3>Specifications</h3>
            <div>
                <?= @$viewProjectSpecificationsResult ?>
            </div>
        </div>
        <br>

        <!-- Receive a Payment --> 
        <div class="example">
            <h3>Receive a Payment</h3>
            <div>
                <form method="post" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ) ;?>" >
                    <input type="hidden" name="projects_payments_file" value="<?=@$paymentsToSelect?>" > <br>
                    <label>Date :</label>
                    <input type="txt" name="projects_payments_paymentYear" placeholder="Year" size="5">
                    <input type="txt" name="projects_payments_paymentMonth" placeholder="Month" size="3">
                    <input type="txt" name="projects_payments_paymentDay" placeholder="Day" size="3">
                    <br>
                    <input type="txt" name="projects_payments_paymentAmount" placeholder="Amount"> <br>
                    <lable>Payment Type</lable>  <br>
                    <select name="projects_paymentType">
                        <option value="Cache">Cache</option>
                        <option value="Bank account">Bank Account</option>
                        <option value="Other">Other</option>
                    </select>
                    <br>
                    <textarea name="projects_payments_paymentNotes" placeholder="Notes..." ></textarea><br>
                    <input type="submit" name="receive_a_new_payment" value="Receive">
                </form>
                <?= @$receiveANewPaymentResult ?>
            </div>
        </div>
        <br>

        <!-- View all Payments for a project -->
        <div class="example">
            <h3>View all payments for a Project</h3>
            <div>
                <?= @$viewAllPaymentsResults ?> 
            </div>
        </div>
        <br>
        
        <!-- Setting an addition and Subtraction requist --> 
        <div class="example">
            <h3>Set Addition and Subtraction Requist</h3>
            <div>
                <form method="post" action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ) ;?>">
                    <input type="hidden" name="addAndSubFile" value="<?=@$additionAndSubtractionFile?>"> <br>
                    <input type="txt" name="addAndSubAmount" placeholder="Amount"> <br>

                    <lable>Operation Type :</lable>
                    <select name="addAndSubOperationsType" >
                        <option value="Addition">Addition</option>
                        <option value="Subtraction">Subtraction</option>
                    </select> <br>
                    
                    <label>Date : </label>
                    <input type="txt" name="addAndSubYear" placeholder="Year" size="5" >
                    <input type="txt" name="addAndSubMonth" placeholder="Month" size="3" >
                    <input type="txt" name="addAndSubDay" placeholder="Day" size="3">
                    <br>
                    <textarea name="addAndSubNotes" placeholder="Notes"></textarea><br>
                    <input type="submit" name="setAddAndSub" value="Submit">
                </form>
                <?= @$settingAdditionAndSubtractionResults ?>
            </div>
        </div>
        <br>

        <!-- View Additions and Subtractions --> 
        <div class="example">
            <h3>View Additions and Subtractions </h3>
            <div>
                <?= @$viewAddAndSubResults ?>
            </div>
        </div>

    </body>
</html>
