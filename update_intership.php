<?php
require("connection.php");
session_start();

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

if (!$student) {
    echo "No student found with the provided email.";
    exit();
}

$studentId = $student['Student_Id'];

// Fetch internship details and company details using JOIN
$query = "
    SELECT 
        I.*, 
        S.FirstName, S.MiddleName, S.LastName, S.Roll_no, S.Email, S.Ph_no,
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
$row = $result->fetch_assoc();

if (!$row) {
    die("No internship details found for the logged-in student.");
}

// Handle form submission for updating data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectName = $_POST['Project_Name'];
    $hrName = $_POST['HR_Name'];
    $category = $_POST['Category'];
    $duration = $_POST['Duration'];
    // $memberName = $_POST['Member_name'];
    // $memberRoll = $_POST['Member_Roll'];
    $grpname = $_POST['Group_Name'];
    $companyName = $_POST['Company_Name'];
    $companyAddress = $_POST['Company_address'];

    // Handle file upload for the offer letter
    if (!empty($_FILES['Offer_Letter']['name'])) {
        $offerLetter = $_FILES['Offer_Letter']['name'];
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($offerLetter);
        $fileType = pathinfo($targetFile, PATHINFO_EXTENSION);

        // Validate file type
        $allowedTypes = ['pdf', 'doc', 'docx'];
        if (!in_array(strtolower($fileType), $allowedTypes)) {
            die("Invalid file format. Only PDF, DOC, and DOCX are allowed.");
        }

        if (!move_uploaded_file($_FILES['Offer_Letter']['tmp_name'], $targetFile)) {
            die("Failed to upload offer letter.");
        }
    } else {
        $offerLetter = $row['Offer_Letter']; // Keep the existing file if no new file is uploaded
    }

    // Update the internship details
    $updateInternshipQuery = "UPDATE `intership_details` SET 
                                `Project_Name` = ?, 
                                `HR_Name` = ?, 
                                `Category` = ?, 
                                `Duration` = ?, 
                                `Group_Name` = ?, 
                                -- `Member_Roll` = ?, 
                                `Offer_Letter` = ?
                              WHERE `Inter_id` = ? AND `Name` = ?";
    $stmt = $connect->prepare($updateInternshipQuery);
    $stmt->bind_param(
        "ssssssis",
        $projectName,
        $hrName,
        $category,
        $duration,
        $grpname    ,
        $offerLetter,
        $row['Inter_id'],
        $studentId
    );
    $internshipResult = $stmt->execute();

    // Update the company details
    $updateCompanyQuery = "UPDATE `company` SET 
                            `Company_Name` = ?, 
                            `Company_address` = ?
                           WHERE `Comp_ID` = ?";
    $stmt = $connect->prepare($updateCompanyQuery);
    $stmt->bind_param(
        "ssi",
        $companyName,
        $companyAddress,
        $row['Company']
    );
    $companyResult = $stmt->execute();

    if ($internshipResult && $companyResult) {
        echo "<script>alert('Internship details updated successfully.'); window.location.href='UserProfile.php';</script>";
    } else {
        echo "<script>alert('Failed to update internship or company details.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/intership_details.css">
    <link rel="icon" href="./Images/collageLOGO.png">
    <title>Internship Management</title></head>
<body>
    <?php include('Navbar.php'); ?>

    <div class="container">
        <header>Internship Details</header>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form first">
                <div class="details personal">
                   
                    <div class="fields">
                        <div class="input-field">
                            <label>Student Name</label>
                            <input disabled type="text" value="<?php echo $row['FirstName'] . ' ' . $row['MiddleName'] . ' ' . $row['LastName']; ?>">
                        </div>
                        <div class="input-field">
                            <label>Email</label>
                            <input disabled type="text" value="<?php echo $row['Email']; ?>">
                        </div>
                        <div class="input-field">
                            <label>Roll No</label>
                            <input disabled type="text" value="<?php echo $row['Roll_no']; ?>">
                        </div>
                        <div class="input-field">
                            <label>Project Name</label>
                            <input type="text" name="Project_Name" value="<?php echo $row['Project_Name']; ?>" >
                        </div>
                        <div class="input-field">
                            <label>HR Name</label>
                            <input type="text" name="HR_Name" value="<?php echo $row['HR_Name']; ?>" >
                        </div>
                        <div class="input-field">
                            <label>Category</label>
                            <input type="text" name="Category" value="<?php echo $row['Category']; ?>" >
                        </div>
                        <div class="input-field">
                            <label>Duration</label>
                            <input type="date" name="Duration" value="<?php echo $row['Duration']; ?>" >
                        </div>
                        <div class="input-field">
                            <label>Group Name</label>
                            <input type="text" name="Group_Name" value="<?php echo $row['Group_Name']; ?>" >
                        </div>
                       
                        <div class="input-field">
                            <label>Company Name</label>
                            <input type="text" name="Company_Name" value="<?php echo $row['Company_Name']; ?>" >
                        </div>
                        <div class="input-field">
                            <label>Company Address</label>
                            <input type="text" name="Company_address" value="<?php echo $row['Company_address']; ?>" >
                        </div>
                        <div class="input-field">
                            <label>Offer Letter</label>
                            <input type="file" name="Offer_Letter">
                            <small>Current: <?php echo $row['Offer_Letter']; ?></small>
                        </div>

                        
                        <div class="buttons">
                        <button type="submit" class="nextBtn" id="backbtn">
                           
                            <span class="btnText">Update</span>
                            <i class="fa-solid fa-circle-right"></i>
                        </button>

                        
                    </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
