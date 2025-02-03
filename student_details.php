<?php
    require("connection.php");
    session_start();

    // insert data into student_details table in databa se 
    if(isset($_POST['Next']))
    {
        $clgid =  $_POST['PRN'];
        $First  = $_POST['first'];
        $Middle  = $_POST['middle'];
        $Last = $_POST['last'];
        $Email  = $_POST['email'];
        $Gender  = $_POST['gender'];
        $DOB  = $_POST['dob'];
        $Admission  = $_POST['Acdamic_year'];
        $Ph_no  = $_POST['mob'];
        $Address = $_POST['adress'];
        $Roll = $_POST['roll'];

    $sql="select * from `student_details` where (Email='$_POST[email]');";
    $result=mysqli_query($connect,$sql);
    if(mysqli_num_rows($result)>0)
    {
        $row=mysqli_fetch_assoc($result);
        //Email already exist in database
        if($Email==$row['Email'])
        {
            $error[]='Email already Registered.';
        }
       
    }
    if(!isset($error))
    {
        $date = date('Y-m-d');
        $option = array("cost" >= 4);

            $result = mysqli_query($connect,"INSERT INTO `student_details`(`Student_Id`, `PRN_No`, `FirstName`, `MiddleName`, `LastName`, `Email`, `Ph_no`, `Address`, `Roll_No`, `DOB`, `Gender`, `Acad-Year`, `Date`) VALUES('','$clgid',' $First','$Middle','$Last','$Email','$Ph_no','$Address','$Roll','$DOB','$Gender','$Admission','$date')");

            if($result){
                $done = 1;

            ?>
            <script>location.replace('intership_details.php')</script>

            <?php
                $sql="select * from `student_details` where (Email='$_POST[email]');";
                $result=mysqli_query($connect,$sql);
                $save = mysqli_fetch_assoc($result);
               $_SESSION['First']=$save['FirstName'];
            }

            else{
                $error[] = 'Failed : Something Went Wrong';
            }
        }    
}

?>

<!DOCTYPE html>
<!-- Coding By CodingNepal - codingnepalweb.com -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!----======== CSS ======== -->
    <link rel="stylesheet" href="./css/student_details.css">
    <!-- <link rel="stylesheet" href="Main.css"/> -->
    

    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <script src="https://kit.fontawesome.com/e8e9c02a1b.js" crossorigin="anonymous"></script>
    <link rel="icon" href="./Images/collageLOGO.png">
    <title>Internship Management</title></head>
<body>  
    <!-- menu  -->
    <?php  include('Navbar.php') ?>
     <!-- menu-end  -->
    <div class="container">
        <header>Student Details</header>
       
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
            <div class="form first">
                <div class="details personal">

                    <div class="fields">
                        <div class="input-field">
                            <label>FirstName</label>
                            <input type="text" name="first" placeholder="Enter your FirstName" required value="<?php if(isset($error)){echo $First;} ?>">
                        </div>

                        <div class="input-field">
                            <label>MiddleName</label>
                            <input type="text" name="middle" placeholder="Enter your MiddleName" required value="<?php if(isset($error)){echo $Middle;} ?>">
                        </div>

                        <div class="input-field">
                            <label>LastName</label>
                            <input type="text" name="last" placeholder="Enter your LastName" required value="<?php if(isset($error)){echo $Last;} ?>">
                        </div>

                        <div class="input-field">
                            <label>Student Id</label>
                            <input type="text" name="PRN" placeholder="Enter your Student Id" required value="<?PHP if(isset($_SESSION['id'])){echo $_SESSION['id'];}?>">
                        </div>

                        

                        <div class="input-field">
                            <label>Roll No</label>
                            <input type="text" name="roll" placeholder="Enter your RollNo" minlength="4" maxlength="4" required value="<?php if(isset($error)){echo $Roll;} ?>">
                        </div>

                        <div class="input-field">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" placeholder="Enter birth date" required value="<?php if(isset($error)){echo $DOB;} ?>">
                        </div>

                        <div class="input-field">
                            <label>Email</label>
                            <input type="text" name="email" placeholder="Enter your email" required value="<?PHP if(isset($_SESSION['Umail'])){echo $_SESSION['Umail'];}?>">
                        </div>

                        <div class="input-field">
                            <label>Mobile Number</label>
                            <input type="tel" name="mob" placeholder="Enter mobile number" minlength="10" maxlength="10" required value="<?php if(isset($error)){echo $Ph_no;} ?>">
                        </div>

                       

                        <div class="input-field">
                            <label>Gender</label>
                            <select name="gender" required value="<?php if(isset($error)){echo $Gender;} ?>">
                                <option disabled selected>Select gender</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Others</option>
                            </select>
                        </div>
                        
                      
                        <div class="input-field">
                            <label>Academic Year</label>
                            <input type="number" name="Acdamic_year" id="acdamics" disabled required placeholder="2020">
                        </div>
                        
                        <div class="input-field ">
                            <label>Perment Address</label>
                            <input type="text"  name="adress"   placeholder="Enter your Address" required value="<?php if(isset($error)){echo $Address;} ?>">
                        </div>
                        
                    </div>

                    <button type="submit" class="nextBtn" name="Next">
                        <span class="btnText">Next</span>
                        <i class="fa-solid fa-circle-right"></i>
                    </button>
                </div> 
            </div>
        </form>
    </div>

    <script>
        var acdamic = document.getElementById('acdamics'); 
        var year = new Date();
        acdamic.value = year.getFullYear()
        


    </script>
    
</body>
</html>