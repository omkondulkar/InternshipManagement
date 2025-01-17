<?php
require("./connection.php");
session_start();

if (!isset($_GET['id'])) {
    echo "Invalid request!";
    exit();
}

$studentId = $_GET['id'];

// Fetch the student data
$sql = "SELECT `Student_Id`, `ID`, `FirstName`, `MiddleName`, `LastName`, `Email`, `Ph_no`, `Address`, `Roll_No`, `DOB`, `Gender`, `Acad-Year`, `Date` FROM `student_details` WHERE `Student_Id` = ?";
$stmt = $connect->prepare($sql);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "No student data found!";
    exit();
}

$student = $result->fetch_assoc();

// Handle form submission for update
if (isset($_POST['Update'])) {
    $First = $_POST['first'];
    $Middle = $_POST['middle'];
    $Last = $_POST['last'];
    $clgid = $_POST['id'];
    $Email = $_POST['email'];
    $Gender = $_POST['gender'];
    $DOB = $_POST['dob'];
    $Admission = $_POST['Acdamic_year'];
    $Ph_no = $_POST['mob'];
    $Address = $_POST['adress'];
    $Roll = $_POST['roll'];

    $update_sql = "UPDATE `student_details` SET `ID`=?, `FirstName`=?, `MiddleName`=?, `LastName`=?, `Email`=?, `Ph_no`=?, `Address`=?, `DOB`=?, `Acad-Year`=?, `Gender`=?, `Roll_No`=? WHERE `Student_Id`=?";
    $update_stmt = $connect->prepare($update_sql);
    $update_stmt->bind_param("sssssssssssi", $clgid, $First, $Middle, $Last, $Email, $Ph_no, $Address, $DOB, $Admission, $Gender, $Roll, $studentId);

    if ($update_stmt->execute()) {
        echo "Update successful!";
        header("Location: user_profile.php");
        exit();
    } else {
        echo "Failed to update student data!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./Images/collageLOGO.png">
    <title>Internship Management</title>    <link rel="stylesheet" href="./css/student_details.css">
    <!-- <link rel="stylesheet" href="Main.css"> -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <script src="https://kit.fontawesome.com/e8e9c02a1b.js" crossorigin="anonymous"></script>
</head>
<body>
    <!-- Include the navigation bar -->
    <?php include('./Navbar.php') ?>
    <div class="container">
        <header>Update Student Information</header>
        <form action="" method="POST">
            <div class="form first">
                <div class="details personal">
                    <span class="title">Personal Details</span>
                    <div class="fields">
                        <div class="input-field">
                            <label>First Name</label>
                            <input type="text" name="first" value="<?php echo $student['FirstName']; ?>" required>
                        </div>
                        <div class="input-field">
                            <label>Middle Name</label>
                            <input type="text" name="middle" value="<?php echo $student['MiddleName']; ?>" required>
                        </div>
                        <div class="input-field">
                            <label>Last Name</label>
                            <input type="text" name="last" value="<?php echo $student['LastName']; ?>" required>
                        </div>
                        <div class="input-field">
                            <label>Student Id</label>
                            <input type="text" name="id" value="<?php echo $student['ID']; ?>" required>
                        </div>
                        <div class="input-field">
                            <label>Roll No</label>
                            <input type="text" name="roll" value="<?php echo $student['Roll_No']; ?>" minlength="4" maxlength="4" required>
                        </div>
                        <div class="input-field">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" value="<?php echo $student['DOB']; ?>" required>
                        </div>
                        <div class="input-field">
                            <label>Email</label>
                            <input type="email" name="email" value="<?php echo $student['Email']; ?>" required>
                        </div>
                        <div class="input-field">
                            <label>Mobile Number</label>
                            <input type="tel" name="mob" value="<?php echo $student['Ph_no']; ?>" required>
                        </div>
                        <div class="input-field">
                            <label>Gender</label>
                            <select name="gender" required>
                                <option value="Male" <?php if($student['Gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                <option value="Female" <?php if($student['Gender'] == 'Female') echo 'selected'; ?>>Female</option>
                                <option value="Others" <?php if($student['Gender'] == 'Others') echo 'selected'; ?>>Others</option>
                            </select>
                        </div>
                        <div class="input-field">
                            <label>Academic Year</label>
                            <input type="number" name="Acdamic_year" value="<?php echo $student['Acad-Year']; ?>" min="2000" max="2100" step="1" required>
                        </div>
                        <div class="input-field">
                            <label>Permanent Address</label>
                            <input type="text" name="adress" value="<?php echo $student['Address']; ?>" required>
                        </div>
                    </div>
                    <button type="submit" class="nextBtn" name="Update">
                        <span class="btnText">Update</span>
                        <i class="fa-solid fa-circle-right"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
