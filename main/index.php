<?php
include 'dbcreation.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/ElderCareALLIANCE/main/style.css">
	
	<title>Landing Page</title>
     
</head>
<body>
    

    <header>
        <h2 class="logo">
                <img src="logo.png" alt="Logo">
        </h2> 
        <div class="address">Sta. Ana -  San Joaquin Bahay Ampunan Foundation Inc. 
            <div class="sub">Barangay Altura Bata, Tanauan, 4232</div> 
        </div>

        <nav class="navigation">
            <a href="#">HOME</a> 
            <a href="#">CONTACT US</a> 
            <a href="#">ABOUT US</a> 
            <a href="/ElderCareALLIANCE/main/signup.php">SIGN UP</a>
            <button class="btnLogin-popup"  onclick="gotologin()">LOGIN</button>
        </nav> 
    </header>
    <div class ="banner">
        
            
        
        <div class="qoute1">
            <h1>Make a difference in the lives of Sta.<br> Ana - San Joaquin Bahay Ampunan<br>Foundation Inc. for generations to come.</h1>
            <p>Your donation will be life changing.</p>
        </div>
        <div class="signup">
            <button class="btnDonate"  onclick="gotologin()">DONATE HERE</button>
            <script>
                function gotologin() {
                    window.location.href = "/ElderCareALLIANCE/main/login.php";
                }
            </script>
            <h1>Dont have an account?</h1>
            <button class="btnsignup"  onclick="gotosign()">Sign up to donate</button>
            <script>
                function gotosign() {
                    window.location.href = "/ElderCareALLIANCE/main/signup.php";
                }
                
                document.body.style.zoom = "100%";
            </script>
        </div>
    </div>  
    
</body> 
</html>