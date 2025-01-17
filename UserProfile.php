<?php
    require("connection.php");
    session_start();

    // echo $_SESSION['id'] ;

  
 ?>
<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="./Images/collageLOGO.png">
        <title>Internship Management</title>
        <script src="https://kit.fontawesome.com/e8e9c02a1b.js" crossorigin="anonymous"></script>
   
        <!-- <link rel="stylesheet" href="user_profile.css"> -->
        <link rel="stylesheet" href="./css/user_profile.css">

        <link
            href="https://fonts.googleapis.com/css2?family=Festive&family=Merriweather:ital,wght@0,300;0,900;1,300;1,900&family=Poppins:wght@400;500;600&family=Roboto+Slab:wght@700&family=Roboto:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,500&display=swap"
            rel="stylesheet">
            <script src="https://kit.fontawesome.com/e8e9c02a1b.js" crossorigin="anonymous"></script>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@400;500;600;700&display=swap" rel="stylesheet">
    </head>

    <body>

    <!-- Navbar Starts -->
        <?php
            include("Navbar.php");
            

                $selectQuery="SELECT * FROM `register` WHERE `Email`='$_SESSION[Fname]'";	 
                $selectdata=mysqli_query($connect,$selectQuery);
                $data=mysqli_fetch_assoc($selectdata);
         ?>
    <section class="main_form common_c">
        <div class="UserProfile_section">
                <form action="" enctype="multipart/form-data" method="POST">
                        <div class="profile_bg">
                            <div class="userprofile">
                            <img src="../Images/User Profile.png" alt="" >
                            </div>  
                            <div class="profile_img">
                                <i class="far fa-images"></i>
                                <input type="file" id="file"  class="Choose File" accept="image/png, image/jpeg" name="image">
                                <label for="file" class="change" name="upload" >Change Profile</label>
                            </div>
                        
                            
                        </div>
                </form>
                <div class="user_details">
                        <div class="Username">
                            <?php
                                echo'<p class="Firstname"> Name :  '.$_SESSION['Fname'] ;'</p>';
                            ?>   
                        </div>
                        <!-- <div class="Username">
                            <p>Username: Saurabh1122</p>
                        </div> -->
                        <div class="Username">
                            <?php
                                echo'<p class="Firstname"> Email Address :  '.$_SESSION['Umail' ];
                                ;'</p>';
                            ?>  
                        </div>   
                </div>
            <form action="" method="POST">
                <div class="buttons">

                        <a href="logout.php"><button class="logout">Logout</button></a>
                        </a><button class="delete" name="delete">Delete Account</button>
                </div>

            </form>
        </div>
        <div class="right_form_pm">

            <div class="head_edit_r">
                <div><h2> User Account Details</h2></div>

                <!-- <div class="updatemsg">
                    <p>
                <?php
                    // if(isset($updatesuccsess))
                    // {
                        
                    //     echo'<p class="successmsg" id="err_msg"><i class="fas fa-check-circle"></i>'."Profile Updated Successfully".' </p>'; 
                
                    // } 
                ?> 
                </p>
                </div>          -->
            </div>

            <div class="user_info">
                    <p class="user_information">USER INFORMATION</p> 
            </div>
            <br>
            <br>
            <div class="form_p">
                <div class="info_block">

                    <div class="user_r">
                        <p class="full_name_head_r">Full Name</p>
                        <input class="inputfeild" type="text" 
                        value="<?PHP if(isset($_SESSION['Fname'])){echo $_SESSION['Fname'] ;}?>" disabled>

                    </div>

                    <div class="user_r">
                        <p class="full_name_head_r">Username</p>
                        <input class="inputfeild" type="text" 
                        value="<?PHP if(isset($_SESSION['Uname'])){echo $_SESSION['Uname'];}?>" disabled>
                    </div>

                    <div class="email_r">
                        <p class="full_name_head_r">Email id (Email can not be changed)</p>
                        <input disabled class="inputfeild inputfeild" type="text" value="<?PHP if(isset($_SESSION['Umail'])){echo $_SESSION['Umail'];}?>"> 
                    </div>
                    

                    <div class="email_r">
                        <p class="full_name_head_r"> Student Id</p>
                        <input disabled class="inputfeild " type="text  " value="<?php if(isset($_SESSION['id'])){echo $_SESSION['id'];} ?>"> 
                   </div>
                </div>    
             </div>


             <!-- _____________ Student Details ____________________  -->
                <div class="head_edit_r" style="margin-top:3rem;">
                        <div><h2> Student Details</h2></div>
                </div>
                <?php
                 // Check if the session is set and user is logged in
                    if (!isset($_SESSION['id'])) {
                        echo "Unauthorized access!";
                        exit();
                    }

                 // Fetch the student's email from the session to retrieve their details
                    $studentEmail = $_SESSION['Umail'];

                 // Fetch student data from the database
                    $sql = "SELECT * FROM `student_details` WHERE `Email` = ?";
                    $stmt = $connect->prepare($sql);
                    $stmt->bind_param("s", $studentEmail);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows == 0) {
                        echo "No student data found!";
                        exit();
                    }

                 // Fetch the student data
                    $student = $result->fetch_assoc();

                    ?>

                    

                    <div class="info_block">
                       
                            <div class="email_r">
                            <p class="full_name_head_r">First Name</p>
                            <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $student['FirstName']; ?>"> 
                            </div>

                            <div class="email_r">
                            <p class="full_name_head_r">Middle Name</p>
                            <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $student['MiddleName']; ?>"> 
                            </div>

                            <div class="email_r">
                            <p class="full_name_head_r">Last Name</p>
                            <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $student['LastName']; ?>"> 
                            </div>

                            <div class="email_r">
                            <p class="full_name_head_r">Student Id</p>
                            <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $student['PRN_No']; ?>"> 
                            </div>

                            <div class="email_r">
                            <p class="full_name_head_r"> Email</p>
                            <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $student['Email']; ?>"> 
                            </div>

                            <div class="email_r">
                            <p class="full_name_head_r"> Phone Number</p>
                            <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $student['Ph_no']; ?>"> 
                            </div>

                            <div class="email_r">
                            <p class="full_name_head_r"> Address </p>
                            <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $student['Address']; ?>"> 
                            </div>

                            <div class="email_r">
                            <p class="full_name_head_r"> Date of Birth </p>
                            <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $student['DOB']; ?>"> 
                            </div>

                            <div class="email_r">
                            <p class="full_name_head_r">Academic Year </p>
                            <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $student['Acad-Year']; ?>"> 
                            </div>

                            <div class="email_r">
                            <p class="full_name_head_r">Gender  </p>
                            <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $student['Gender']; ?>"> 
                            </div>

                            <div class="email_r">
                            <p class="full_name_head_r">Roll No </p>
                            <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $student['Roll_No']; ?>"> 
                            </div>
                  
                        <div class="email_r">
                            <p class="full_name_head_r">Actions </p>
                            <div class="action">
                                <a href="./update_student_details.php?id=<?php echo $student['Student_Id']; ?>">
                                    <button class="logout">    Update </button></a>
                                <a href="  ?id=<?php echo $student['Student_Id']; ?>" onclick="return confirm('Are you sure you want to delete this record?');" >
                                    <button class="delete" id="del"> Delete </button> </a>
                                    <!-- delete_student.php -->
                            </div>
                        </div>
                     </div>
          <!-- ___________________________ Student End _______________  -->
                    <div class="head_edit_r" style="margin-top:3rem;">
                            <div>
                                <h2> Internship Details</h2>
                            </div>
                    </div>
                    <?php

                            // Ensure the session is active and a user is logged in
                            if (!isset($_SESSION['id']) || !isset($_SESSION['Umail'])) {
                                echo "Unauthorized access!";
                                exit();
                            }

                            // Fetch the student's email from the session
                            $studentEmail = $_SESSION['Umail'];

                            // Fetch student data from the student_details table
                            $sql = "SELECT * FROM `student_details` WHERE `Email` = ?";
                            $stmt = $connect->prepare($sql);
                            $stmt->bind_param("s", $studentEmail);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $student = $result->fetch_assoc();

                            // Get the Student_Id for fetching internship details
                            if ($student) {
                                $studentId = $student['Student_Id'];

                                // Fetch internship details and company details using JOIN
                                $query = "
                                    SELECT 
                                        I.*, 
                                        S.FirstName, S.Email, S.Roll_no,
                                        C.Company_Name, C.Company_address 
                                    FROM 
                                        intership_details I
                                    INNER JOIN 
                                        student_details S ON I.Name = S.Student_Id
                                    INNER JOIN 
                                        company C ON I.Company = C.Comp_ID
                                    WHERE 
                                        I.Name = ?";
                                $stmt = $connect->prepare($query);
                                $stmt->bind_param("i", $studentId);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $internship = $result->fetch_assoc();

                                if ($internship) {
                                    ?>
                                <div class="info_block">
                                        
                                        <div class="email_r">
                                        <p class="full_name_head_r">Student Name</p>
                                        <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $internship['FirstName']; ?>"> 
                                        </div>

                                        <div class="email_r">
                                        <p class="full_name_head_r">Email</p>
                                        <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $internship['Email']; ?>"> 
                                        </div>

                                        <div class="email_r">
                                        <p class="full_name_head_r">Company</p>
                                        <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $internship['Company_Name']; ?>"> 
                                        </div>

                                        <div class="email_r">
                                        <p class="full_name_head_r">Company Address</p>
                                        <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $internship['Company_address']; ?>"> 
                                        </div>

                                        <div class="email_r">
                                        <p class="full_name_head_r">Project Name</p>
                                        <input disabled class="inputfeild inputfeild" type="text" value="<?php echo  $internship['Project_Name']; ?>"> 
                                        </div>

                                        <div class="email_r">
                                        <p class="full_name_head_r">HR Name</p>
                                        <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $internship['HR_Name']; ?>"> 
                                        </div>

                                        <div class="email_r">
                                        <p class="full_name_head_r">Roll Number </p>
                                        <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $internship['Roll_no']; ?>"> 
                                        </div>

                                        <div class="email_r">
                                        <p class="full_name_head_r">Category </p>
                                        <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $internship['Category']; ?>"> 
                                        </div>

                                        <div class="email_r">
                                        <p class="full_name_head_r"> Group Name</p>
                                        <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $internship['Group_Name']; ?>"> 
                                        </div>

                                        <div class="email_r">
                                        <p class="full_name_head_r">  Duration </p>
                                        <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $internship['Duration']; ?>"> 
                                        </div>

                                        <div class="email_r">
                                        <p class="full_name_head_r"> Offer_Letter </p>
                                        <input disabled class="inputfeild inputfeild" type="text" value="<?php echo $internship['Offer_Letter']; ?>" download> 


                                        <div class="action"> 
                                            <a href="delete_intership..php?id=<?php echo $internship['Offer_Letter'];?>" download>
                                                <button class="delete" id="del"> Veiw </button> 
                                            </a>
                                        </div>
                                        </div>

                                       
                                
                                    <div class="email_r">
                                        <p class="full_name_head_r">Actions </p>
                                        <div class="action">
                                            <a href="./update_intership.php?id=<?php echo $student['Student_Id']; ?>">
                                                <button class="logout">    Update </button>
                                            </a>
                                            <a href="delete_intership..php?id=<?php echo $student['Student_Id']; ?>" onclick="return confirm('Are you sure you want to delete this record?');" >
                                                <button class="delete" id="del"> Delete </button> 
                                            </a>
                                        </div>
                                    </div>
                                </div>
                         <?php
                            } else {
                                    echo "No internship details found for the student.";
                                }
                            } else {
                                echo "No student details found.";
                            }

                            $connect->close();
                         ?>

            
            </div>
      
              



    </section>