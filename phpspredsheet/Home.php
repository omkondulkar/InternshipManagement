<?php
    require('connection.php');
    session_start();
  
  // login check data from register tables  
  include("./Php/Homelogin.php");
    
//  End of login student 
  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
   <script src="https://kit.fontawesome.com/e8e9c02a1b.js" crossorigin="anonymous"></script>
   
    <title>Document</title>
    <style>
        *{
  margin: 0px;
  padding: 0px;
  box-sizing: border-box;
}

body{
  font-family: Arial, Helvetica, sans-serif;
  /* background: linear-gradient(rgba(0,0,0,0.2),rgba(0,0,0,0.2)),url(./Images/search_img.jpg);
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover; */
  background-color: #ffff;
}

.container
{
  display: grid;
  grid-template-columns: 1fr 2fr;
  background-color: red;
  background: linear-gradient(to bottom, rgb(6, 108, 100),  rgb(14, 48, 122));
  width: 800px;
  height: 400px;
  margin: 10% auto;;
  border-radius: 5px;
}

.content-holder
{
  text-align: center;
  color: white;
  font-size: 14px;
  font-weight: lighter;
  letter-spacing: 2px;
  margin-top: 15%;
  padding: 50px;
}

.content-holder h2
{
  font-size: 34px;
  margin: 20px auto;
}

.content-holder p
{
  margin: 30px auto;
}

.content-holder button
{
  border:none;
  font-size: 15px;
  padding: 10px;
  border-radius: 6px;
  background-color: white;
  width: 150px;
  margin: 20px auto;
}


.box-2{
  background-color: white;

  margin: 5px;
}

.login-form-container
{
  text-align: center;
  margin-top: 5%;

}

.login-form-container h1
{
  color: black;
  font-size: 24px;
  padding: 20px;
}
.input-field i{
  margin-right: 5px;
}

.input-field input
{
  box-sizing: border-box;
  font-size: 14px;
  padding: 10px;
  border-radius: 7px;
  width: 250px;
  outline: none;
}
.input-field input:focus{
  border: 2px solid black;
  outline: auto;
}


.login-button{
  box-sizing: border-box;
  color: white;
  font-size: 17px;
  padding: 13px;
  border-radius: 7px;
  border: none;
  width: 250px;
  outline: none;
  background-color: rgb(56, 102, 189);
}



.button-2
{
  display: none;
}

.sign_up{
  margin-top: 10px;
  font-size: 1.2rem;
}



.signup-form-container
{
  position: relative;
  top: 50%;
  left: 50%;
  transform: translate(-50%,-60%);
  text-align: center;
  display: none;
}


.signup-form-container h1
{
  color: black;
  font-size: 24px;
  padding: 20px;
}

.signup-button{
  box-sizing: border-box;
  color: white;
  font-size: 17px;
  padding: 13px;
  border-radius: 7px;
  border: none;
  width: 250px;
  outline: none;
  background-color: rgb(56, 189, 149);  
}

.errmsg
{
  border-radius: 5px;
    border: 1px solid red;
    background:#ffe3e3 ;
    text-align: left;
    color: red;
    padding: 5px 5px;
    text-align: center;
    width: 50%;
    margin-left: 9.3rem;
    font-size: 15px;
    margin-bottom: 10px;
}

/*########### Responsive ############# */
/* Responsive for below 768px */
@media screen and (max-width: 767px) {
  .container {
    grid-template-columns: 1fr;
    width: 100%;
    height: auto;
    margin: 5% auto;
  }

  .box-1, .box-2 {
    width: 100%;
    margin: 0;
    padding: 10px;
  }

  .content-holder {
    padding: 20px;
    margin-top: 10% ;
  }

  .content-holder h2 {
    font-size: 24px;
  }

  .content-holder button {
    width: 50%;
    margin: 10px 7rem;
    padding: 8px;
    font-size: 14px;
  }

  .login-form-container, .signup-form-container {
    margin-top: 0;
  }

  .login-form-container h1, .signup-form-container h1 {
    font-size: 20px;
    margin: 1rem;
    padding: 10px;
  }

  .input-field input {
    width: 85%;
    font-size: 12px;
  }

  .login-button, .signup-button {
    width: 100%;
    padding: 10px;
  }

  .errmsg {
    width: 90%;
    margin-left: 5%;
    font-size: 14px;
  }

  .sign_up {
    font-size: 1rem;
  }
}

/* Responsive for 768px to 991px */
@media screen and (min-width: 768px) and (max-width: 991px) {
  .container {
    grid-template-columns: 1fr;
    width: 90%;
    height: auto;
    margin: 5% auto;
  }

  .box-1, .box-2 {
    width: 100%;
    margin: 0;
    padding: 10px;
  }

  .content-holder {
    padding: 30px;
    margin-top: 10%;
  }

  .content-holder h2 {
    font-size: 28px;
  }

  .content-holder button {
    width: 100%;
    margin: 10px 0;
    padding: 10px;
    font-size: 16px;
  }

  .login-form-container, .signup-form-container {
    margin-top: 0;
  }

  .login-form-container h1, .signup-form-container h1 {
    font-size: 22px;
    padding: 15px;
  }

  .input-field input {
    width: 90%;
    font-size: 14px;
  }

  .login-button, .signup-button {
    width: 90%;
    padding: 12px;
  }

  .errmsg {
    width: 80%;
    margin-left: 10%;
    font-size: 14px;
  }

  .sign_up {
    font-size: 1.1rem;
  }
}

    </style>

</head>
<body>
    <div class="container">
        <!--Data or Content-->
        <div class="box-1">
            <div class="content-holder">
                <h2>Welcome</h2>
               
                <button class="button-1" onclick="signup()" id="Admin">Admin</button>
                <button class="button-2" onclick="login()">Login</button>
            </div>
        </div>
  
        
        <!--Forms-->
        <div class="box-2">
        <div class="login-form-container">
    <form action="" method="POST">
        <h1>Login Form</h1>
        <?php if (isset($error)) { foreach ($error as $error) { echo '<p class="errmsg" id="err_msg"><i class="fa-solid fa-triangle-exclamation"></i>'.$error.'</p>'; } } ?>
        <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="Email" name="umail" required>
        </div>
        <br><br>
        <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" id="passhide" class="input-field" name="password" placeholder="Password" minlength="4" maxlength="8" required>
            <i class="fas fa-eye-slash" id="close_eye"></i>
        </div>
        <br><br>
        <input type="submit" class="login-button" value="Login" name="Login">
        <div class="sign_up">
            Not a member? <a href="Signin.php">Signup now</a><br>
            <a href="./ForgotPassword.php" onclick="forgotPassword()" style="font-size:1rem; top:5rem;">Forgot Password?</a>
        </div>
    </form>
</div>

  
  <!--Create Container for Signup form-->
        <div class="signup-form-container" >

        <form action="" method="POST">
            <h1>Admin Login</h1>
            <div class="input-field">
            <i class="fas fa-user"></i>
                <input type="text" class="input-field" placeholder="Email or Phone" name="admin_email" required value="<?php if(isset($error)) ?>"><!--{echo $Email;} -->
              </div>
              <br><br>

            <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" id="passwordhide" name="admin_password" placeholder="Password" minlength="4" maxlength="8" required value="<?php if(isset($error)) ?>" required>  <!--{echo $Password;}-->

                <i class="fas fa-eye-slash" id="close2_eye"></i>
              </div>
            <br><br>
            <input type="submit" class="signup-button" value="Login" name="Admin">
         </form>
        </div>
  
  
  
        </div>


        <script>
     function signup()
{
    document.querySelector(".login-form-container").style.cssText = "display: none;";
    document.querySelector(".signup-form-container").style.cssText = "display: block;";
    document.querySelector(".container").style.cssText = "background: linear-gradient(to bottom, rgb(56, 189, 149),  rgb(28, 139, 106));";
    document.querySelector(".button-1").style.cssText = "display: none";
    document.querySelector(".button-2").style.cssText = "display: block";

};

function login()
{
    document.querySelector(".signup-form-container").style.cssText = "display: none;";
    document.querySelector(".login-form-container").style.cssText = "display: block;";
    document.querySelector(".container").style.cssText = "background: linear-gradient(to bottom, rgb(6, 108, 224),  rgb(14, 48, 122));";
    document.querySelector(".button-2").style.cssText = "display: none";
    document.querySelector(".button-1").style.cssText = "display: block";

}
    //  Password Show   
    let passhide=document.getElementById("passhide");
    let passwordhide=document.getElementById("passwordhide");
    let close_eye=document.getElementById("close_eye");
    let close2_eye=document.getElementById("close2_eye");

    close_eye.onclick=function(){
        if(passhide.type=="password")
        {
            passhide.type="text";
            this.classList.add("fa-eye");
            this.classList.remove("fa-eye-slash");

        }
        else
        {
            passhide.type="password";
            this.classList.add("fa-eye-slash");
            this.classList.remove("fa-eye");
        }
    }
    close2_eye.onclick=function(){
        if(passwordhide.type=="password")
        {
          passwordhide.type="text";
            this.classList.add("fa-eye");
            this.classList.remove("fa-eye-slash");

        }
        else
        {
          passwordhide.type="password";
            this.classList.add("fa-eye-slash");
            this.classList.remove("fa-eye");
        }
    }
 </script>
</body>
</html>