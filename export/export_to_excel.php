<?php
require('../connection.php');
require('../phpspredsheet/vendor/autoload.php'); // Include PhpSpreadsheet via Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['columns'])) {
    // Get the columns and search query from the form
    $columns = isset($_POST['columns']) ? json_decode($_POST['columns'], true) : [];
    $searchQuery = isset($_POST['search_query']) ? $_POST['search_query'] : '';

    // Available columns in the `student_details` table
    $availableColumns = [
        'Student_Id' => 'Student ID',
        'PRN_No' => 'PRN Number',
        'Roll_no' => 'Roll Number',
        'FirstName' => 'First Name',
        'MiddleName' => 'Middle Name',
        'LastName' => 'Last Name',
        'Email' => 'Email',
        'Ph_no' => 'Phone Number',
        'Address' => 'Address',
        'DOB' => 'Date of Birth',
        'Gender' => 'Gender',
        'Date' => 'Date'
    ];

    // Prepare SQL query for searching
    $selectedColumns = !empty($columns) ? implode(',', $columns) : '*';
    $sql = "SELECT $selectedColumns FROM `student_details`";
    if (!empty($searchQuery)) {
        $sql .= " WHERE CONCAT_WS(' ', $selectedColumns) LIKE '%$searchQuery%'";
    }

    // Fetch the data based on the query
    $result = mysqli_query($connect, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the header row
        $columnIndex = 1; // Excel columns start from 1 (A)

        // Adding headers for only selected columns
        foreach ($columns as $column) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
            $sheet->setCellValue($columnLetter . '1', $availableColumns[$column]);
            $columnIndex++;
        }

        // Adding data rows
        $rowIndex = 2; // Data rows start from 2
        foreach ($data as $row) {
            $columnIndex = 1;
            foreach ($columns as $column) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
                $sheet->setCellValue($columnLetter . $rowIndex, $row[$column]);
                $columnIndex++;
            }
            $rowIndex++;
        }

        // Generate Excel file
        $writer = new Xlsx($spreadsheet);

        // Output the file to the browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="student_search_results.xlsx"');
        $writer->save('php://output');
        exit;
    } else {
        echo "No results found to export.";
    }
}
?>
