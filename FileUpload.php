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

// Generate Excel Template
if (isset($_GET['open_template'])) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = ['PRN_No', 'FirstName', 'MiddleName', 'LastName', 'Email', 'Ph_no', 'Address', 'Roll_No', 'DOB', 'Gender', 'Acad-Year'];
    foreach ($headers as $col => $header) {
        $sheet->setCellValue(chr(65 + $col) . '1', $header);
    }
    $sheet->getStyle('I:I')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
    $sheet->getStyle('A:A')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: inline; filename="Student_Template.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

$msg = [];

if (isset($_POST['student_submit'])) {
    $file = $_FILES['file']['tmp_name'];

    try {
        $spreadsheet = IOFactory::load($file);
    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
        die("Error loading file: " . $e->getMessage());
    }

    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

// SELECT `Student_Id`, `PRN_No`, `FirstName`, `MiddleName`, `LastName`, `Email`, `Ph_no`, `Address`, `Roll_No`, `DOB`, `Gender`, `Acad-Year`, `Date` FROM `student_details`

    // Updated SQL query (Student_Id is AUTO_INCREMENT, so we don't insert it)
    $stmt = $connect->prepare("INSERT INTO student_details (PRN_No, FirstName, MiddleName, LastName, Email, Ph_no, Address, Roll_No, DOB, Gender, `Acad-Year`, Date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE())");


    if (!$stmt) {
        die("Prepare failed: " . $connect->error);
    }

    $stmt->bind_param("sssssssssss", $id, $firstName, $middleName, $lastName, $email, $ph_no, $address, $roll_no, $dob, $gender, $acad_year);

    $header = true;
    foreach ($rows as $index => $row) {
        if ($header) {
            $header = false;
            continue;
        }

        $id = isset($row[0]) ? trim($row[0]) : '';
        $firstName = isset($row[1]) ? trim($row[1]) : '';
        $middleName = isset($row[2]) ? trim($row[2]) : '';
        $lastName = isset($row[3]) ? trim($row[3]) : '';
        $email = isset($row[4]) ? trim($row[4]) : '';
        $ph_no = isset($row[5]) ? trim($row[5]) : '';
        $address = isset($row[6]) ? trim($row[6]) : '';
        $roll_no = isset($row[7]) ? trim($row[7]) : '';
        $dob = isset($row[8]) ? trim($row[8]) : '';
        $gender = isset($row[9]) ? trim($row[9]) : '';
        $acad_year = isset($row[10]) ? trim($row[10]) : '';

        // if (!is_numeric($id)) {
        //     $msg[] = "Row " . ($index + 1) . ": Invalid ID format ({$id}). Must be a number.";
        //     continue;
        // }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg[] = "Row " . ($index + 1) . ": Invalid email format ({$email}).";
            continue;
        }

        if (!preg_match('/^\d{10}$/', $ph_no)) {
            $msg[] = "Row " . ($index + 1) . ": Invalid phone number ({$ph_no}).";
            continue;
        }

        if (!empty($dob)) {
            $dateFormats = ['d-m-Y', 'd/m/Y', 'm/d/Y', 'Y-m-d', 'Y/m/d'];
            $date = false;
            foreach ($dateFormats as $format) {
                $date = \DateTime::createFromFormat($format, $dob);
                if ($date !== false) {
                    break;
                }
            }

            if ($date === false) {
                $msg[] = "Row " . ($index + 1) . ": Invalid DOB format ({$dob}). Use DD-MM-YYYY, DD/MM/YYYY, MM/DD/YYYY, YYYY-MM-DD or YYYY/MM/DD.";
                continue;
            }
            $dob = $date->format('Y-m-d');
        } else {
            $dob = null;
        }

        if (!$stmt->execute()) {
            $msg[] = "Row " . ($index + 1) . ": Database error - " . $stmt->error;
        } else {
            $msg[] = "Row " . ($index + 1) . " inserted successfully.";
        }
    }


    $stmt->close();
}
// ________________________________________Company Data Code start____________________________________ 

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Generate Excel Template (Comp_ID removed)
if (isset($_GET['open_template_company'])) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();   

    $headers = ['Company_Name','Company_City', 'Company_Address']; // Comp_ID removed from headers
    foreach ($headers as $col => $header) {
        $sheet->setCellValue(chr(65 + $col) . '1', $header);
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: inline; filename="Company_Template.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

$cmpmsg = [];

if (isset($_POST['company_submit'])) {
    $file = $_FILES['company_file']['tmp_name'];

    if (!file_exists($file)) {
        die("No file uploaded or file not found.");
    }

    try {
        $spreadsheet = IOFactory::load($file);
    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
        die("Error loading file: " . $e->getMessage());
    }

    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    if (empty($rows)) {
        die("No data found in the uploaded file.");
    }

    $stmt = $connect->prepare("INSERT INTO company (Company_Name, Company_City, Company_Address, Date) VALUES (?, ?, ?, CURDATE())");

    if (!$stmt) {
        die("Prepare failed: " . $connect->error);
    }

    $stmt->bind_param("sss", $company_name, $company_city, $company_address);

    $header = true;
    foreach ($rows as $index => $row) {
        if ($header) {
            $header = false;
            continue;
        }

        $company_name = isset($row[0]) ? trim($row[0]) : '';
        $company_city = isset($row[1]) ? trim($row[1]) : '';
        $company_address = isset($row[2]) ? trim($row[2]) : '';

        if (empty($company_name) || empty($company_city) || empty($company_address)) {
            $cmpmsg[] = "Row " . ($index + 1) . ": Skipped due to missing data.";
            continue;
        }

        if (!$stmt->execute()) {
            $cmpmsg[] = "Row " . ($index + 1) . ": Database error - " . $stmt->error;
        } else {
            $cmpmsg[] = "Row " . ($index + 1) . " inserted successfully.";
        }
    }

    $stmt->close();

    // // Display messages
    // foreach ($cmpmsg as $msg) {
    //     echo $msg . "<br>";
    // }
}



// ________________________________Comapny Code End __________________________________ 
$connect->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/Admin.css">
    <!-- <link rel="stylesheet" href="./Admin.css"> -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <script src="https://kit.fontawesome.com/e8e9c02a1b.js" crossorigin="anonymous"></script>
    <link rel="icon" href="./Images/collageLOGO.png">
    <title>Internship Management</title></head>

<body>
    <?php
        include('Admin.php'); 
    ?>

    <section class="Admin_data">

    <!-- ____________________ Student File Upload Code _________________  -->
            <label class="file_heading">Student Information</label>
            <div class="actions">
            <!-- if (!empty($msg)) {
                foreach ($msg as $message) {
                  echo "<p style='color: red;'>" .htmlspecialchars($message)."</p>";
                }
            } -->
                <?php 
                    if (!empty($msg)) {
                        foreach ($msg as $message) {
                          echo "<p style='color: red;'>" .htmlspecialchars($message)."</p>";
                        }
                    }


                // if (empty($msg)){ echo "<p style='color: green;'>" ."Data uploaded successfully!"."</p>"; 
                // }else {
                //     foreach ($msg as $message) {
                //        echo "<p style='color: red;'>" .htmlspecialchars($message)."</p>";
                //         // echo htmlspecialchars($message) . "<br>";
                //     }
                // }
                ?>
            </div>     

            <div class="actions template_box">
                 <h2>Download Excel Template:</h2>
                 <a href="?open_template=true" target="_blank" class="template">Open Template</a>

              <h2>Upload Student Data</h2>
                    <form method="POST" enctype="multipart/form-data" class="upload_form">
                        <input type="file" name="file" required>
                        <button type="submit" name="student_submit">Upload</button>
                  </form>
            </div> 
            <!-- ___________________End _____________  -->

           <hr class="line">
            <!-- _______________Company File Upload Code _________________  -->

            <label class="file_heading">Company  Information</label>
            <div class="actions">
                
                <?php 

                    if (!empty($cmpmsg)) {
                        foreach ($cmpmsg as $message) {
                            echo "<p style='color: red;'>" .htmlspecialchars($message)."</p>";
                        }
                    }

             
                ?>
             </div>     

            <div class="actions template_box">
                 <h2>Download Excel Template:</h2>
                 <a href="?open_template_company=true" target="_blank" class="template">Open Template</a>

              <h2>Upload Company Data</h2>
                    <form method="POST" enctype="multipart/form-data" class="upload_form">
                        <input type="file" name="company_file" required>
                         <button type="submit" name="company_submit">Upload </button>
                    </form>
            </div> 
        <!-- ________________________ company End ________________  -->

    </section>
    <script>
        const body = document.querySelector("body"),
            sidebar = body.querySelector("nav"),
            sidebarToggle = body.querySelector(".sidebar-toggle");

        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        });

        // Table hover effects 
        document.addEventListener('DOMContentLoaded', () => {
            const rows = document.querySelectorAll('.table tbody tr');
            rows.forEach(row => {
                row.addEventListener('click', () => {
                    rows.forEach(r => r.classList.remove('highlight'));
                    row.classList.add('highlight');
                });
            });
        });
    </script>
</body>

</html>
