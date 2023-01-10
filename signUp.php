<?php
    require_once "forgotPassword.php" ;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Sign Up</title>
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
        <!-- Sign Up Form --> 
        <div class="example">
            <h3>Imgs test</h3>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <!-- User Info --> 
                <input type='text' name='firstname' placeholder='First Name' size=35><br>
                <input type='text' name='lastname' placeholder='Last Name' size=35><br>
                <input type='text' name='phone' placeholder="Phone Number" size=35><br>
                <input type='text' name='country' placeholder='Country' size=35><br>
                <input type='text' name='address' placeholder='Address' size=35><br>
                <input type='text' name='central' placeholder='Central' size=35><br>
                <input type='text' name='governorate' placeholder='Governorate' size=35><br>
                Birth Date : <input type='text' name='day' placeholder='Day' size=3> <input type='text' name='month' placeholder='Month' size=3> <input type='text' name='year' placeholder='Year' size=5><br>
                <input type='text' name='nationalID' placeholder='National ID' size=35><br>
                <input type='text' name='jobTitle' placeholder='Job Title' size=35><br>
                Gender :<br> <input type='radio' name="gender" value="Male"> Male<br> <input type="radio" name="gender" value="Female"> Female<br> <input type="radio" name="gender" value="Other"> Other<br>
                Status :<br> <input type='radio' name="status" value="Single"> Single<br> <input type="radio" name="status" value="Married"> Married<br> <input type="radio" name="status" value="divorced"> Divorced<br>

                <!-- Entity Info --> 
                <input type='text' name='entityType' placeholder='Entity Type' size=35><br>
                <input type='text' name='entityName' placeholder='Entity Name' size=35><br>
                <input type='text' name='entityPhone' placeholder='Entity Phone' size=35><br>
                <input type='text' name='entityAddress' placeholder='Entity Address' size=35><br>
                <input type='text' name='entityCentral' placeholder='Entity Central' size=35><br>
                <input type='text' name='entityGovernorate' placeholder='Entity Governorate' size=35><br>
                Stablishment Date : <input type='text' name='stablishment_day' placeholder='Day' size=3> <input type='text' name='stablishment_month' placeholder='Month' size=3> <input type='text' name='stablishment_year' placeholder='Year' size=5><br>
                <input type='text' name='commercialRegistration_NO' placeholder='Commercial Registration NO.' size=35><br>
                <input type='text' name='taxCard_NO' placeholder='Tax Card NO.' size=35><br>
                <textarea name="bio" placeholder="bio"></textarea><br>

                <!-- Account Info --> 
                <input type='text' name='username' placeholder='User Name' size=35 required><br>
                <input type='password' name='password' placeholder='Password' size=35 required><br>
                <input type='text' name='website' placeholder='Website ( Ex: https://www.example.com )' size=35><br>
                <input type='text' name='email' placeholder='Email' size=35><br>
                Profile Img :<input type="file" name="profilePhoto"><br>
                Profile Background :<input type='file' name="profileBackground"><br>

                <!-- Security Questions --> 
                <input type="text" name="q1" placeholder="What is your First Job ?" size=50 ><br>
                <input type="text" name="q2" placeholder="What is first Company you worked at ?" size=50 ><br>
                <input type="text" name="q3" placeholder="What is your best Friend Name ?" size=50 ><br>
                <input type="text" name="q4" placeholder="What is the last five digits in your national ID(from right) ?" size=50 ><br>
                <input type="text" name="q5" placeholder="What is the name of your primary school ?" size=50 ><br>

                <input type="submit" name="signUp" value="Sign Up"><br>
                <?=@$ob4?>
            </form>
        </div>
        


        <!-- 
            [
                ID(required) , firstname(S) , lastname(S) , phone(S) , country(S) , address(S) , central(S) , governorate(S) , birthDate(S) , nationalID(S) , jobTitle(S) , gender(S) , status(S) , 
                entityType(S) , entityName(S) , entityPhone(S) , entityAddress(S) , entityCentral(S) , entityGovernorate(S) , stablishmentDate(S) , commercialRegistration_NO(S) , taxCard_NO(S) , bio(S) , 
                userName(S) , password(S) , website , email , securityQuestions(required) , registrationDate(required) , profilePhoto , profileBackground
            ]
        -->
    </body>
</html>