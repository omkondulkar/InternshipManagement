<?php
require('connection.php');
session_start();

require './phpspredsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Generate Excel Template for Internship Details
if (isset($_GET['open_template'])) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = ['Student_Id', 'Comp_ID', 'Project_Name', 'HR_Name', 'Category', 'Duration', 'Member_name', 'Member_Roll', 'Offer_Letter'];
    foreach ($headers as $col => $header) {
        $sheet->setCellValue(chr(65 + $col) . '1', $header);
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: inline; filename="Internship_Template.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

$msg = [];

if (isset($_POST['internship_submit'])) {
    $file = $_FILES['file']['tmp_name'];

    // Load the spreadsheet
    try {
        $spreadsheet = IOFactory::load($file);
    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
        die("Error loading file: " . $e->getMessage());
    }

    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    // Prepared statement for inserting internship data
    $stmt = $connect->prepare("INSERT INTO internship_details (Name, Company, Project_Name, HR_Name, Category, Duration, Member_name, Member_Roll, Offer_Letter, date) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE())");

    if (!$stmt) {
        die("Prepare failed: " . $connect->error);
    }

    $stmt->bind_param("iissssss", $studentId, $compId, $projectName, $hrName, $category, $duration, $memberName, $memberRoll);

    $header = true;
    foreach ($rows as $index => $row) {
        if ($header) {
            $header = false;
            continue;
        }

        $studentId = isset($row[0]) ? trim($row[0]) : '';
        $compId = isset($row[1]) ? trim($row[1]) : '';
        $projectName = isset($row[2]) ? trim($row[2]) : '';
        $hrName = isset($row[3]) ? trim($row[3]) : '';
        $category = isset($row[4]) ? trim($row[4]) : '';
        $duration = isset($row[5]) ? trim($row[5]) : '';
        $memberName = isset($row[6]) ? trim($row[6]) : '';
        $memberRoll = isset($row[7]) ? trim($row[7]) : '';

        // Check if the Student ID exists in student_details
        $studentQuery = "SELECT Student_Id FROM student_details WHERE Student_Id = ?";
        $studentStmt = $connect->prepare($studentQuery);
        $studentStmt->bind_param("i", $studentId);
        $studentStmt->execute();
        $studentStmt->store_result();

        if ($studentStmt->num_rows == 0) {
            $msg[] = "Row " . ($index + 1) . ": Student ID {$studentId} does not exist in the database.";
            $studentStmt->close();
            continue;
        }

        // Check if the Company ID exists in company_details
        $companyQuery = "SELECT Comp_ID FROM company_details WHERE Comp_ID = ?";
        $companyStmt = $connect->prepare($companyQuery);
        $companyStmt->bind_param("i", $compId);
        $companyStmt->execute();
        $companyStmt->store_result();

        if ($companyStmt->num_rows == 0) {
            $msg[] = "Row " . ($index + 1) . ": Company ID {$compId} does not exist in the database.";
            $companyStmt->close();
            continue;
        }

        // Execute the insert if all checks pass
        if (!$stmt->execute()) {
            $msg[] = "Row " . ($index + 1) . ": Database error - " . $stmt->error;
        } else {
            $msg[] = "Row " . ($index + 1) . " inserted successfully.";
        }

        $companyStmt->close();
    }

    if (empty($msg)) {
        echo "<script>alert('Data uploaded successfully!');</script>";
    } else {
        foreach ($msg as $message) {
            echo htmlspecialchars($message) . "<br>";
        }
    }

    $stmt->close();
}

$connect->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="./Images/collageLOGO.png">
    <title>Internship Management</title></head>
<body>
    <h2>Open Excel Template</h2>
    <a href="?open_template=true" target="_blank">Download Internship Template</a>

    <h2>Upload Internship Details</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit" name="internship_submit">Upload</button>
    </form>
</body>
</html>
