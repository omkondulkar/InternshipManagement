<?php
    require('connection.php');
    session_start();
  
  // login check data from register tables  
  include("./Php/Homelogin.php");
    
//  End of login student 
  ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="./fonts/icomoon/style.css">

    <link rel="stylesheet" href="./css/owl.carousel.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    
    <!-- Style -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="./Images/collageLOGO.png">
    <title>Internship Management</title>
  </head>
  <body>
  

  <div class="d-lg-flex half">
    <div class="bg order-1 order-md-2" style="background-image: url('./Images/clgcampus.jpg');"></div>
    <div class="contents order-2 order-md-1">

      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-7">
            <img src="./Images/collageLOGO.png" alt="Logo" class="clglogo">
            <p class="mb-4">Vidarbha Youth Welfare Society's</p>
            <h3> Prof. Ram Meghe Institute of Technology and <br> Research Badnera - Amravati</h3>
            <form action="#" method="post">
            <?php if (isset($error))
             { 
              foreach ($error as $error) 
                { echo '<p class="errmsg" id="err_msg"><i     class="fa-solid fa-triangle-exclamation"></i>'.$error.'</p>'; } 
              }
            ?>
              <div class="form-group first">
                <label for="username">Username</label>
                <input type="text" class="form-control"  name="email" placeholder="your-email@gmail.com" id="username">
              </div>
              <div class="form-group last mb-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Your Password" id="password">
              </div>
              
              <div class="d-flex mb-5 align-items-center">
                <!-- <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
                  <input type="checkbox" checked="checked"/>
                  <div class="control__indicator"></div>
                </label> -->
                <span class="ml-auto"><a href="./ForgotPassword.php" class="forgot-pass">Forgot Password</a></span> 
                <span class="ml-auto"><a href="./Signin.php" class="forgot-pass">Register</a></span> 
              </div>

              <input type="submit" value="Log In" name="Login" class="btn btn-block btn-primary">

            </form>
          </div>
        </div>
      </div>
    </div>

    
  </div>
    
    

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>