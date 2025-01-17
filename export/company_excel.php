<?php
require('../connection.php');
require('../phpspredsheet/vendor/autoload.php'); // Include PhpSpreadsheet via Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Get the search term from the form submission
$searchTerm = isset($_POST['company_search']) ? $_POST['company_search'] : '';

// Fetch data based on the search term
$sql = "SELECT `Comp_ID`, `Company_Name`, `Company_address`, `Date` FROM `company` WHERE `Comp_ID` LIKE ? OR `Company_Name` LIKE ? OR `Comp_ID` LIKE ? OR `Date` LIKE ? ORDER BY `Company_Name`, `Comp_ID`;";
$searchTermWildcard = "%".$searchTerm."%";

$stmt = $connect->prepare($sql);
$stmt->bind_param("ss", $searchTermWildcard, $searchTermWildcard);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_all(MYSQLI_ASSOC);

    // Create a new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set the header row
    $headers = array_keys($data[0]);
    $columnIndex = 1; // Excel columns start from 1 (A)

    // Adding headers
    foreach ($headers as $header) {
        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
        $sheet->setCellValue($columnLetter . '1', $header);
        $columnIndex++;
    }

    // Adding data rows
    $rowIndex = 2; // Data rows start from 2
    foreach ($data as $row) {
        $columnIndex = 1;
        foreach ($row as $cell) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
            $sheet->setCellValue($columnLetter . $rowIndex, $cell);
            $columnIndex++;
        }
        $rowIndex++;
    }

    // Generate Excel file
    $writer = new Xlsx($spreadsheet);

    // Output the file to the browser
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="companyWise_search_results.xlsx"');
    $writer->save('php://output');
    exit;
} else {
    echo "No results found to export.";
}

$stmt->close();
$connect->close();
?>
