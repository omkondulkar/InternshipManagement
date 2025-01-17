<?php
require("connection.php");
session_start();

// if (!isset($_GET['id'])) {
//     echo "Invalid request!";
//     exit();
// }

// $studentId = $_GET['id'];

// // Delete the student data
// $sql = "DELETE FROM `student_details` WHERE `Student_Id` = ?";
// $stmt = $connect->prepare($sql);
// $stmt->bind_param("i", $studentId);

// if ($stmt->execute()) {
//     // Redirect to the main page or any confirmation page after deletion
//     echo "Student record deleted successfully!";
//     header("Location: success.php");
//     exit();
// } else {
//     echo "Failed to delete student record!";
// }

// require("connection.php");
// session_start();

if (!isset($_GET['id'])) {
    echo "Invalid request!";
    exit();
}

$studentId = $_GET['id'];

// Delete internship details first
$sqlDeleteInternships = "DELETE FROM `intership_details` WHERE `Name` = ?";
$stmt = $connect->prepare($sqlDeleteInternships);
$stmt->bind_param("i", $studentId);
$stmt->execute();

// Delete the student
$sqlDeleteStudent = "DELETE FROM `student_details` WHERE `Student_Id` = ?";
$stmt = $connect->prepare($sqlDeleteStudent);
$stmt->bind_param("i", $studentId);

if ($stmt->execute()) {
    echo "Student record and associated internship details deleted successfully!";
    // header("Location: success.php");
    exit();
} else {
    echo "Failed to delete student record!";
}

?>
