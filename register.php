<?php

require('connection.php');
session_start();

require './phpspredsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Generate Excel Template
if (isset($_GET['open_template_register'])) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $headers = ['PRN_No', 'UserName', 'FullName', 'Email', 'Password'];
    foreach ($headers as $col => $header) {
        $sheet->setCellValue(chr(65 + $col) . '1', $header);
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: inline; filename="User_Registration_Template.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

$msg = [];

if (isset($_POST['register_submit'])) {
    if (!isset($_SESSION['form_submitted'])) {
        $_SESSION['form_submitted'] = true;

        if (isset($_FILES['register_file']['tmp_name']) && !empty($_FILES['register_file']['tmp_name'])) {
            $file = $_FILES['register_file']['tmp_name'];

            if (!file_exists($file)) {
                $msg[] = "Uploaded file not found.";
            } else {
                try {
                    $spreadsheet = IOFactory::load($file);
                } catch (Exception $e) {
                    die("Error loading file: " . $e->getMessage());
                }

                $sheet = $spreadsheet->getActiveSheet();
                $rows = $sheet->toArray(null, true, true, true);
                
                if (!$connect->ping()) {
                    die("Database connection lost.");
                }
                
                if (empty($rows) || count($rows) <= 1) {
                    $msg[] = "The uploaded file is empty or does not contain data.";
                } else {
                    $stmt = $connect->prepare("INSERT INTO register (PRN_No, UserName, FullName, Email, Password, Date) VALUES (?, ?, ?, ?, ?, CURDATE())");

                    if (!$stmt) {
                        die("Prepare failed: " . $connect->error);
                    }

                    $stmt->bind_param("sssss", $prn_no, $username, $fullname, $email, $password);

                    $header = true;
                    foreach ($rows as $index => $row) {
                        if ($header) {
                            $header = false;
                            continue;
                        }

                        $prn_no = trim($row['A']);
                        $username = trim($row['B']);
                        $fullname = trim($row['C']);
                        $email = trim($row['D']);
                        $password = trim($row['E']);

                        if (empty($prn_no) || empty($username) || empty($fullname) || empty($email) || empty($password)) {
                            $msg[] = "Row " . ($index + 1) . ": Missing required fields.";
                            continue;
                        }

                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $msg[] = "Row " . ($index + 1) . ": Invalid email format ({$email}).";
                            continue;
                        }

                        if (!$stmt->execute()) {
                            $msg[] = "Row " . ($index + 1) . ": Database error - " . $stmt->error;
                        } else {
                            $msg[] = "Row " . ($index + 1) . " inserted successfully.";
                            $msg[] = "Query executed: " . $stmt->sqlstate;
                        }
                        
                    }

                    $stmt->close();
                }
            }
        } else {
            $msg[] = "No file uploaded or file is empty.";
        }
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $connect->query("DELETE FROM register WHERE PRN_No='$id'");
    header('Location: register.php');
}

$result = $connect->query("SELECT * FROM register");

$connect->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/Admin.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <script src="https://kit.fontawesome.com/e8e9c02a1b.js" crossorigin="anonymous"></script>
    <link rel="icon" href="./Images/collageLOGO.png">
    <title>Internship Management</title>
</head>

<body>
    <?php include('Admin.php'); ?>

    <section class="Admin_data">
        <label class="file_heading">User Registration</label>
        <div class="actions" style="display: block;">
            <h4>Upload Status </h4>
            <?php if (!empty($msg)) {
                foreach ($msg as $message) {
                    echo "<p style='color: green;'>" . htmlspecialchars($message) . "</p>";
                }
            } ?>
        </div>
        <div class="actions template_box">
            <h3>Download Excel Template:</h3>
            <a href="?open_template_register=true" target="_blank">Download Template</a>

            <h3>Upload User Data</h3>
            <form method="POST" enctype="multipart/form-data" onsubmit="preventResubmit()">
                <input type="file" name="register_file" required>
                <button type="submit" name="register_submit">Upload</button>
            </form>
        </div>

        <table class="table" style="display:table;">
            <thead class="thead">
                <tr>
                    <th>PRN_No</th>
                    <th>UserName</th>
                    <th>FullName</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['PRN_No']}</td>
                        <td>{$row['UserName']}</td>
                        <td>{$row['FullName']}</td>
                        <td>{$row['Email']}</td>
                        <td>{$row['Date']}</td>
                        <td>
                            <a href='update.php?id={$row['PRN_No']}' class='action_buttons'>Update</a> |
                            <a href='?delete={$row['PRN_No']}' onclick='return confirm('Are you sure?');' class='action_buttons'>Delete</a>
                        </td>
                    </tr>";
                } ?>
            </tbody>
        </table>
    </section>

    <script>
        function preventResubmit() {
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        }
    </script>
</body>

</html>
