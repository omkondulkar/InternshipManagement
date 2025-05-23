<?php
require('../connection.php');
require('../phpspredsheet/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Retrieve columns and search query from POST
$columns = isset($_POST['columns']) ? json_decode($_POST['columns'], true) : [];
$searchQuery = isset($_POST['search_query']) ? $_POST['search_query'] : '';

// Default to including Inter_id if not provided
if (!in_array('Inter_id', $columns)) {
    array_unshift($columns, 'Inter_id');
}

// Available columns with proper aliases
$availableColumns = [
    'Inter_id' => 'Intern ID',
    'Name' => 'Intern Name',
    'Company' => 'Company',
    'CompanyCity' => 'Company City',
    'CompanyAddress' => 'Company Address',
    'Project_Name' => 'Project Name',
    'HR_Name' => 'HR Name',
    'HR_Email' => 'HR Email',
    'Category' => 'Category',
    'Group_Name' => 'Group Name',
    'Duration' => 'Duration',
    'Offer_Letter' => 'Offer Letter',
    'date' => 'Date',
];

// Build SELECT clause
$selectColumns = ["id.Inter_id"];
if (in_array('Name', $columns)) {
    $selectColumns[] = "CONCAT(sd.FirstName, ' ', IFNULL(sd.MiddleName, ''), ' ', sd.LastName) AS InternName";
}
if (in_array('Company', $columns)) {
    $selectColumns[] = "c.Company_Name AS CompanyName";
    $selectColumns[] = "c.Company_City AS CompanyCity";
    $selectColumns[] = "c.Company_Address AS CompanyAddress";
}
foreach ($columns as $column) {
    if (!in_array($column, ['Name', 'Company', 'Inter_id'])) {
        $selectColumns[] = "id.$column";
    }
}

$selectClause = implode(', ', $selectColumns);

// Build FROM and JOIN clauses
$fromClause = "FROM intership_details AS id";
if (in_array('Name', $columns)) {
    $fromClause .= " INNER JOIN student_details AS sd ON id.Name = sd.Student_Id";
}
if (in_array('Company', $columns)) {
    $fromClause .= " INNER JOIN company AS c ON id.Company = c.Comp_ID";
}

// Build WHERE clause
$whereClause = "";
if (!empty($searchQuery)) {
    $whereParts = [];
    if (in_array('Name', $columns)) {
        $whereParts[] = "sd.FirstName LIKE '%$searchQuery%'";
        $whereParts[] = "sd.MiddleName LIKE '%$searchQuery%'";
        $whereParts[] = "sd.LastName LIKE '%$searchQuery%'";
    }
    if (in_array('Company', $columns)) {
        $whereParts[] = "c.Company_Name LIKE '%$searchQuery%'";
        $whereParts[] = "c.Company_City LIKE '%$searchQuery%'";
        $whereParts[] = "c.Company_Address LIKE '%$searchQuery%'";
    }
    foreach ($columns as $column) {
        if (!in_array($column, ['Name', 'Company'])) {
            $whereParts[] = "id.$column LIKE '%$searchQuery%'";
        }
    }
    $whereClause = " WHERE " . implode(" OR ", $whereParts);
}

// Final SQL query
$sql = "SELECT $selectClause $fromClause $whereClause";
$result = mysqli_query($connect, $sql);

// Create a new spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Add headers
$headerRow = [];
foreach ($columns as $column)
if ($column === 'Company') {
    $headerRow[] = $availableColumns[$column];
    $headerRow[] = $availableColumns['CompanyCity'];
    $headerRow[] = $availableColumns['CompanyAddress'];
} else {
    $headerRow[] = $availableColumns[$column];
}
$sheet->fromArray($headerRow, null, 'A1');

// Add data
if ($result && mysqli_num_rows($result) > 0) {
    $rowNumber = 2; // Start adding data from row 2
    while ($row = mysqli_fetch_assoc($result)) {
        $dataRow = [];
        foreach ($columns as $column) {
            if ($column === 'Name') {
                $dataRow[] = $row['InternName'] ?? '';
            } elseif ($column === 'Company') {
                $dataRow[] = $row['CompanyName'] ?? '';
                $dataRow[] = $row['CompanyCity'] ?? '';
                $dataRow[] = $row['CompanyAddress'] ?? '';
            } else {
                $dataRow[] = $row[$column] ?? '';
            }
        }
        $sheet->fromArray($dataRow, null, 'A' . $rowNumber++);
    }
}

// Clear output buffer to avoid corruption
if (ob_get_length()) {
    ob_clean();
}

// Set headers for download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="internship_data.xlsx"');

// Write the file to output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
