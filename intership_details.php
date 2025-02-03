<?php
require("connection.php");
session_start();

// // Insert data into internship_details table in the database
// if (isset($_POST['Done'])) {
//     $studentId = $_POST['studentId']; // Student_Id from dropdown
//     $project = $_POST['project'];
//     $HR = $_POST['HR'];
//     $HREmail = $_POST['HREmail'];
//     $GrpName = $_POST['GrpName'];
//     $mobile = $_POST['mob'];
//     $category = $_POST['category'];
//     $duration = $_POST['duration'];
//     $offer = $_POST['offer'];

//     $companyId = isset($_POST['companyId']) ? $_POST['companyId'] : null; // Check if companyId exists
//     $companyName = $_POST['compName'];
//     $companyCity = $_POST['Company_city'];
//     $companyAddress = $_POST['Company_address'];

//     $date = date('Y-m-d');

//     //
//     // _____________If no company is selected, save the new company_________________
//     if (empty($companyId) && !empty($companyName)  && !empty($Company_City) && !empty($companyAddress)) {
//         $insertCompany = mysqli_query($connect, "INSERT INTO `company`(`Comp_ID`, `Company_Name`, `Company_City`, `Company_Address`, `Date`) VALUES ('','$companyName','$companyCity','$companyAddress','$date')");
//         if ($insertCompany) {
//             $companyId = mysqli_insert_id($connect); // Get the ID of the newly inserted company
//         }
//     }


//     // SELECT `Inter_id`, `Name`, `Company`, `Project_Name`, `HR_Name`, `Category`, `Group_Name`, `Duration`, `Offer_Letter`, `date` FROM `intership_details

//     $inter = mysqli_query($connect, "INSERT INTO `intership_details`(`Inter_id`, `Name`, `Company`, `Project_Name`, `HR_Name`, `HR_Email`, `Group_Name`, `Category`,`Duration`,`Offer_Letter`, `date`) VALUES ('','$studentId','$companyId','$project','$HR','$HREmail ','$GrpName','$category','$duration','$offer','$date')");

//     // INSERT INTO `intership_details`(`Inter_id`, `Name`, `Company`, `Project_Name`, `HR_Name`, `HR_Email`, `Group_Name`, `Category`, `Duration`, `Offer_Letter`, `date`) VALUES ('[value-1]','[value-2]','[value-3]','[value-4]','[value-5]','[value-6]','[value-7]','[value-8]','[value-9]','[value-10]','[value-11]')

//     if ($inter) {
//         $done = 1;
//     } else {
//         $error[] = 'Failed: Something went wrong';
//     }


    // Insert data into internship_details table in the database
if (isset($_POST['Done'])) {
    require("connection.php");
    
    $studentId = $_POST['studentId']; // Student_Id from dropdown
    $project = $_POST['project'];
    $HR = $_POST['HR'];
    $HREmail = $_POST['HREmail'];
    $GrpName = $_POST['GrpName'];
    $mobile = $_POST['mob'];
    $category = $_POST['category'];
    $duration = $_POST['duration'];
    $offer = $_POST['offer'];

    $companyId = isset($_POST['companyId']) && !empty($_POST['companyId']) ? $_POST['companyId'] : null;
    $companyName = trim($_POST['compName']);
    $companyCity = trim($_POST['Company_city']);
    $companyAddress = trim($_POST['Company_address']);
    
    $date = date('Y-m-d');

    // Insert new company if not selected
    if (empty($companyId) && !empty($companyName) && !empty($companyCity) && !empty($companyAddress)) {
        $insertCompany = mysqli_query($connect, "INSERT INTO `company`(`Company_Name`, `Company_City`, `Company_Address`, `Date`) VALUES ('$companyName','$companyCity','$companyAddress','$date')");
        
        if ($insertCompany) {
            $companyId = mysqli_insert_id($connect); // Get the new company ID
        } else {
            $error[] = 'Failed to add company details.';
        }
    }

    // Ensure companyId is not null
    if (!empty($companyId)) {
        $inter = mysqli_query($connect, "INSERT INTO `intership_details`(`Name`, `Company`, `Project_Name`, `HR_Name`, `HR_Email`, `Group_Name`, `Category`, `Duration`, `Offer_Letter`, `date`) 
                                         VALUES ('$studentId','$companyId','$project','$HR','$HREmail','$GrpName','$category','$duration','$offer','$date')");
        if ($inter) {
            $done = 1;
        } else {
            $error[] = 'Failed: Something went wrong';
        }
    } else {
        $error[] = 'Invalid company selection. Please select or add a company.';
    }
}

// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/intership_details.css">
    <!-- <link rel="stylesheet" href="intership_details.css"> -->
    <link rel="stylesheet" href="Main.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <script src="https://kit.fontawesome.com/e8e9c02a1b.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <link rel="icon" href="./Images/collageLOGO.png">
    <title>Internship Management</title></head>
<body>
    <?php include('Navbar.php'); ?>
    <?php 
            if(isset($done) )
            { ?>
            <div class="success_msg_container">
                <div class="successmsg" style="margin-top: -25rem;">
                    <div class="ani_icon">
                        <lottie-player src="https://assets1.lottiefiles.com/packages/lf20_vzhtcqsd.json"  background="transparent"  speed="1.5"  style="width: 150px; height: 150px;" loop autoplay></lottie-player>
                    </div>
                
                    <br>
                    <h2>Information Submited</h2> 
                    <p>Thanks for Submission</p>
                    <a href="./Intership_Home.php" class="continue_btn" style="text-decoration: none;">Back To Home</a>
                </div>
            </div>
            <?php } else { ?>
    <div class="container">
        <header>Internship Details</header>
         
        <form action="" method="POST" id="interForm">
            <?php
            if (isset($error)) {
                foreach ($error as $err) {
                    echo '<p class="errmsg" id="err_msg"><i class="fa-solid fa-triangle-exclamation"></i>' . $err . ' </p>';
                }
            }
            ?>

            <div class="form first">
                <div class="details personal">
                   

                    <div class="fields">
                        <div class="input-field">
                            <label>Student Name</label>
                            <select name="studentId" required>
                                <option disabled selected>Select Student</option>
                                <?php
                                $students = mysqli_query($connect, "SELECT Student_Id, FirstName, LastName, Roll_No FROM student_details");
                                while ($row = mysqli_fetch_assoc($students)) {
                                    echo "<option value='" . $row['Student_Id'] . "'>" . $row['FirstName'] . " " . $row['LastName'] . " (Roll No: " . $row['Roll_No'] . ")</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="input-field">
                            <label>Project Name</label>
                            <input type="text" name="project" placeholder="Enter your Project Name" required>
                        </div>

                        <div class="input-field">
                            <label>HR Name</label>
                            <input type="text" name="HR" placeholder="Enter HR names" required>
                        </div>
                        <div class="input-field">
                            <label>HR Email</label>
                            <input type="text" name="HREmail" placeholder="Enter HR names" required>
                        </div>

                        <div class="input-field">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="Enter your email" required value="<?PHP if(isset($_SESSION['Umail'])){echo $_SESSION['Umail'];}?>" disabled>
                        </div>

                        <div class="input-field">
                            <label>Mobile Number</label>
                            <input type="tel" name="mob" placeholder="Enter mobile number" minlength="10" maxlength="10" required>
                        </div>

                        <div class="input-field">
                            <label>Category</label>
                            <select name="category" required>
                                <option disabled selected>Select project Category</option>
                                <option value="Single">Single</option>
                                <option value="Group">Group</option>
                            </select>
                        </div>

                        <div class="input-field">
                            <label>Group Name</label>
                            <input type="text" name="GrpName" placeholder="Enter your Group Name" required>
                        </div>

                        <div class="input-field">
                            <label>Duration</label>
                            <input type="date" name="duration" required>
                        </div>

                       

                        <div class="input-field">
                            <label>Company</label>
                            <select name="companyId">
                                <option disabled selected>Select Company</option>
                                <?php      // SELECT `Comp_ID`, `Company_Name`, `Company_City`, `Company_Address`, `Date` FROM `company`
                                $companies = mysqli_query($connect, "SELECT `Comp_ID`, `Company_Name`, `Company_City`, `Company_Address` FROM company");
                                while ($row = mysqli_fetch_assoc($companies)) {
                                    echo "<option value='" . $row['Comp_Id'] . "'>" . $row['Company_Name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="input-field">
                            <label>(If your company name is not found, enter here)</label>
                            <label>Company Name</label>
                            <input type="text" name="compName" placeholder="Enter Company Name">
                        </div>
                        <div class="input-field">
                                <label>Company Address</label>
                            <input type="text" name="Company_address" placeholder="Enter Company Address">
                        </div>
                        <div class="input-field">
                                <label>Company City</label>
                            <input type="text" name="Company_city" placeholder="Enter Company Address">
                        </div>
                        <div class="input-field">
                                <label>Offer Letter</label>
                                <input type="file" name="offer" style="padding: 0.6rem; font-size: 1.1rem;" required>
                            </div>
                    </div>
                    <div class="buttons">
                        <button type="button" class="nextBtn" id="backbtn">
                            <i class="fa-solid fa-circle-left"></i>
                            <span class="btnText">Back</span>
                        </button>

                        <button type="submit" class="nextBtn" id="done" name="Done">
                            <span class="btnText">Done</span>
                            <i class="fa-solid fa-circle-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <?php } ?>
    </div>
   

    <script>
        document.getElementById('backbtn').addEventListener("click", () => {
            location.replace('student_details.php');
        });

        const form = document.getElementById('interForm');
        document.getElementById('done').addEventListener("submit",()=>{
            e.preventDefault();
            form.style.display = 'none';
            const newform = document.createElement("form");
            const success = `<div class="success_msg_container">
                            <div class="successmsg">
                                <div class="ani_icon">
                                    <lottie-player src="https://assets1.lottiefiles.com/packages/lf20_vzhtcqsd.json"  background="transparent"  speed="1.5"  style="width: 150px; height: 150px;" loop autoplay></lottie-player>
                                </div>
                            
                                <br>
                                <h2>Registration Sucessfull</h2> 
                                <p>Thanks for Registration</p>
                                <a href="StudyLab_login.php" class="continue_btn">Continue to Login</a>
                            </div>
                        </div>`

                newform.inerHTML = success;
        })
    </script>
</body>
</html>
