<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Fake Sign Up Page</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link href="css/styles.css" rel="stylesheet" type="text/css" />
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Seymour+One&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap" rel="stylesheet">
    </head>
    
    <body>
        <h1>Sign Up!</h1>
         
         <form id="signupForm" action="welcome.php">
             <br>
             First Name:  <input type="text" name="fName"><br>
             Last Name:   <input type="text" name="lName"><br>
             Gender:     <input type="radio" name="gender" value="m"> Male
                         <input type="radio" name="gender" value="f"> Female
                         <input type="radio" name="gender" value="o"> Other<br>
                        
            Zip Code: <input type="text" id="zip" name="zip"><br>
            <span id="zipError"></span><br>
            City: <span id="city"></span><br>
            Latitude: <span id="latitude"></span><br>
            Longitude: <span id="longitude"></span><br><br>
            
            State:
            <select id="state" name="state">
            </select><br>
            
            Select a County: <select id="county"></select><br>
            
            <span id="req">*</span> Desired Username: <input type="text" id="username" name="username"><br>
            <span id="usernameError"></span><br>
            
            <span id="req">*</span> Password: <input type="password" id="password" name="password"><br>
            <span id="passwordError"></span><br>
            <span id="req">*</span> Password Again: <input type="password" id="passwordAgain"><br>
            <span id="passwordAgainError"></span><br>
            
            <span id="inputButton">- - - - <input type="submit" value="Sign up!"> - - - -</span><br><br>
        
        </form>
        
        <footer>
            <hr>CST336 Internet Programming. Copyright Sean Wilson &copy;2020<br>
            <b>Disclaimer: This page is only for educational purposes. This is not an actual sign up page for any real service. I will not do anything with your data, but it is probably best to not post your actual password here.</b>
            <a href="http://www.csumb.edu"><img src="img/csumb.png" alt ="The CSUMB Logo" width="200"></a>
        </footer>
        
        <script>
        /*global $*/
        
            var usernameAvailable = false;
        
            $("#zip").on("change", async function(){
                //alert($("#zip").val());
                let zipCode = $("#zip").val();
                let url =  `https://itcdland.csumb.edu/~milara/ajax/cityInfoByZip?zip=${zipCode}`;
                let response = await fetch(url);
                let data = await response.json();
                //console.log(data);
                if (data == false)
                {
                    $("#zipError").html("This is not a real Zip code!");
                    $("#city").html("");
                    $("#latitude").html("");
                    $("#longitude").html("");
                }else{
                    $("#city").html(data.city);
                    $("#latitude").html(data.latitude);
                    $("#longitude").html(data.longitude);
                    $("#zipError").html("");
                }
                
            });//zip
            
            $("#state").on("change", async function(){
                let state = $("#state").val();
                let url =  `https://itcdland.csumb.edu/~milara/ajax/countyList.php?state=${state}`;
                let response = await fetch(url);
                let data = await response.json();
                //console.log(data);
                $("#county").html("<option> Select one </option>")
                for (let i=0; i< data.length; i++){
                    $("#county").append(`<option> ${data[i].county} </option>`);
                }
            });//state
            
            $("#username").on("change", async function(){
                let username = $("#username").val();
                let url =  `https://cst336.herokuapp.com/projects/api/usernamesAPI.php?username=${username}`;
                let response = await fetch(url);
                let data = await response.json();
                
                if (data.available) {
                    $("#usernameError").html("Username available!");
                    $("#usernameError").css("color", "green");
                    usernameAvailable = true;
                }else{
                    $("#usernameError").html("Username not available!");
                    $("#usernameError").css("color", "red");
                    usernameAvailable = false;
                }
            });//username
            
            $("#signupForm").on("submit", function(e) {
               
               if (!isFormValid()){
                   e.preventDefault();
               }
            });//signupform
            
            function isFormValid(){
                var isValid= true;
                if (!usernameAvailable){
                    isValid=false;
                }
                
                
                if ($("#username").val().length == 0){
                    isValid=false;
                    $("#usernameError").html("Username is required.");
                    $("#usernameError").css("color", "red");
                }
                
                if ($("#password").val() != $("#passwordAgain").val()){
                    $("#passwordAgainError").html("Password Mismatch!");
                    isValid = false;
                }else{
                    $("#passwordAgainError").html("");
                }
                
                if ($("#password").val().length < 6){
                    $("#passwordError").html("Your password must be at least 6 characters!");
                    isValid = false;
                }else{
                    $("#passwordError").html("");
                }
                
                return isValid;
            }//isFormValid
            
            $(document).ready(async function(){
                
                //populate the list of states using a web API
                let url =  `https://cst336.herokuapp.com/projects/api/state_abbrAPI.php`;
                let response = await fetch(url);
                let data = await response.json();
                //console.log(data);
                $("#state").html("<option> Select one </option>")
                for (let i=0; i< data.length; i++){
                    $("#state").append(`<option value="${data[i].usps}"> ${data[i].state} </option>`);
                }
                
            });//document.ready
        </script>
    </body>
</html>