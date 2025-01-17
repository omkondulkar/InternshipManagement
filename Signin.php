<?php
    require('connection.php');
    session_start();
  // Inserting data into register tables  

if(isset($_POST['Submit']))
  {
    extract($_POST);
    // Both passwords should be matched
    if($password!= $confirmpassword) {
        $error[]=' Oops !Password Do not matched';
    }
    // Passwords Should not be empty.
     if($password=='' || $confirmpassword =='')
    {
        $error[]=' Password can not be Empty';
    }



    $FName = $_POST['fullname'];
    $clgId = $_POST['Id'];
    $Username = $_POST['username'];
    $Email = $_POST['email'];
    $Pwd = $_POST['password'];

    $sql = "select * FROM `register` WHERE (UserName = '$_POST[username]' OR Email = '$_POST[email]');";
    $result = mysqli_query($connect,$sql);
    if(mysqli_num_rows($result)>0)
    {
      $row = mysqli_fetch_assoc($result);

      if($Username == $row['UserName'])
      {
        $error[] = 'UserName is Already Exist';
      }
      if($Email == $row['Email'])
      {
        $error[] = 'Email is already exits';
      }
    }

    if(!isset($error))
    {
       $date = date('Y-m-d');
       $option = array("cost"=>4);

       $res = mysqli_query($connect,"INSERT INTO `register`(`Signup_Id`,`PRN_No `, `FullName`, `Email`, `Password`, `DATE`, `UserName`) VALUES ('','$clgId','$FName','$Email','$Pwd','$date',' $Username')");

       if($res)
       {
        $done = 1;
        ?>
            <script>
              alert("Register Successful");
            location.replace('Home.php');
            </script>
    <?PHP
       }
       else{
        $error[] = 'Failed: Something Went Wrong';
       }

    }

  }

?>
    
<!DOCTYPE html>
<!-- Created By CodingLab - www.codinglabweb.com -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <link rel="icon" href="./Images/collageLOGO.png">
    <title>Internship Management</title>
        <!-- <link rel="stylesheet" href="sigin.css"> -->
    <link rel="stylesheet" href="./css/sigin.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
   <script src="https://kit.fontawesome.com/e8e9c02a1b.js" crossorigin="anonymous"></script>
   </head>
<body>
  <div class="main_div">
    <div class="title">Signin </div>

   <form action="" method="POST">
     <?php
            if(isset($error))
            {
                foreach($error as $error)
                {
                    echo'<p class="errmsg" id="err_msg"><i class="fa-solid fa-triangle-exclamation"></i>'.$error.' </p>'; 
                }
            }
          ?>
          <!-- <span id="nameError" class="validation"></span> -->
          <!-- <span id="emailError" class="validation"></span>
          <span id="passError" class="validation"></span> -->

      <div class="social_icons">
        <a href="#"><i class="fab fa-facebook-f"></i> <span>Facebook</span></a>
        <a href="#"><i class="fa-brands fa-google"></i><span>Google</span></a>
      </div>
      
        <div class="input_box">
          <input type="number" placeholder=" Enter your Collage Id"  name="Id" autocomplete="off ">
          <div class="icon"><i class="fas fa-user"></i></div>
        </div>

        <div class="input_box">
          <input type="text" placeholder=" Enter your Fullname"  name="fullname"   autocomplete="off ">
          <div class="icon"><i class="fas fa-user"></i></div>

        
        </div>

        <div class="input_box">
          <input type="text" placeholder=" Enter your Username"  name="username"  autocomplete="off "required>
          <div class="icon"><i class="fas fa-user"></i></div>
        </div>
      
        <div class="input_box">
          <input type="email" placeholder=" Enter your Email"  name="email" id="email"  autocomplete="off" required >
          <div class="icon"><i class="fas fa-user"></i></div>

          
        </div>
      
      
        <div class="input_box">
          <input type="password"  id="passhide" placeholder=" Enter your Passsword"  name="password"     id="password" minlength="4" maxlength="8"  required>
            <div class="icon"> 
              <i class="fas fa-lock"></i>   
            </div>
          
            <i class="fas fa-eye-slash" id="close_eye "></i>
            
          </div>

          

        <div class="input_box">
          <input type="password"  placeholder=" Enter your ConformPasssword"  name="confirmpassword"  minlength="4" maxlength="8"  required>
          <div class="icon"> <i class="fas fa-lock"></i></div>
         

        </div>
      
        
        <div class="option_div">
          <div class="check_box">
            <input type="checkbox" required>
            <span>Agree Terms & Conditions </span>
          </div>
          
        </div>
        <div class="input_box button">
          <input type="submit" value="Signin" name="Submit" >
        </div>

        <div class="sign_up">
        Already have an account?  <a href="Home.php">Login now</a>
        </div>
        
        

    </form>

  </div>
  <script>
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


    // ________FORM VALIDATION ________________ 

    





// ____________ END _____________ 
   
</script>
</body>
</html>