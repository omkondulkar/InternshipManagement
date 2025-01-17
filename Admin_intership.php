<?php
require('connection.php');
require('./phpspredsheet/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();

$columns = isset($_POST['columns']) ? $_POST['columns'] : [];
$searchQuery = isset($_POST['search_query']) ? $_POST['search_query'] : '';   //Ternary Operator

// Available columns (with special handling for Name and Company)
$availableColumns = [
    'Inter_id' => 'Intern ID',
    'Name' => 'Intern Name',
    'Company' => 'Company',
    'Project_Name' => 'Project Name',
    'HR_Name' => 'HR Name',
    'Category' => 'Category',
    'Duration' => 'Duration',
    'Member_name' => 'Member Name',
    'Member_Roll' => 'Member Roll',
    'Offer_Letter' => 'Offer Letter',
    'date' => 'Date'
];

// Ensure Inter_id is always included and appears first
if (!in_array('Inter_id', $columns)) {
    array_unshift($columns, 'Inter_id');
}

// Build the SELECT part of the query
$selectColumns = ["id.Inter_id"];
if (in_array('Name', $columns)) {
    $selectColumns[] = "CONCAT(sd.FirstName, ' ', IFNULL(sd.MiddleName, ''), ' ', sd.LastName) AS InternName";
}
if (in_array('Company', $columns)) {
    $selectColumns[] = "c.Company_Name AS CompanyName";
    $selectColumns[] = "c.Company_address AS CompanyAddress";
}
foreach ($columns as $column) {
    if ($column != 'Name' && $column != 'Company' && $column != 'Inter_id') {
        $selectColumns[] = "id.$column";
    }
}

$selectClause = !empty($selectColumns) ? implode(', ', $selectColumns) : '*';

// Build the FROM and JOIN parts of the query
$fromClause = "FROM intership_details AS id";
if (in_array('Name', $columns)) {
    $fromClause .= " INNER JOIN student_details AS sd ON id.Name = sd.Student_Id";
}
if (in_array('Company', $columns)) {
    $fromClause .= " INNER JOIN company AS c ON id.Company = c.Comp_ID";
}

// Build the WHERE clause
$whereClause = "";
if (!empty($searchQuery)) {
    $whereParts = [];
    foreach ($columns as $column) {
        if ($column == 'Name') {
            $whereParts[] = "CONCAT(sd.FirstName, ' ', IFNULL(sd.MiddleName, ''), ' ', sd.LastName) LIKE '%$searchQuery%'";
        } elseif ($column == 'Company') {
            $whereParts[] = "c.Company_Name LIKE '%$searchQuery%' OR c.Company_address LIKE '%$searchQuery%'";
        } else {
            $whereParts[] = "id.$column LIKE '%$searchQuery%'";
        }
    }
    $whereClause = "WHERE " . implode(" OR ", $whereParts);
}

$sql = "SELECT $selectClause $fromClause $whereClause";

$result = mysqli_query($connect, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./Images/collageLOGO.png">
    <title>Internship Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include('Admin.php'); ?>
<section class="Admin_data">
    <div class="column_select">
        <form action="" method="POST" class="column_form">
            <label for="columns">Select Columns:</label><br>
            <div class="select_item">
                <?php foreach ($availableColumns as $key => $value): ?>
                    <input type="checkbox" name="columns[]" value="<?= $key ?>" <?= in_array($key, $columns) ? 'checked' : '' ?>>
                    <?= $value ?>
                <?php endforeach; ?>
            </div>
            <br>
            <button type="submit" class="applybtn">Done</button>
        </form>
    </div>

    <div class="actions">
        <form action="" method="POST" class="search_forms">
            <label for="search_query">Search:</label>
            <input type="text" name="search_query" class="search" value="<?= htmlspecialchars($searchQuery) ?>">
            <?php foreach ($columns as $column): ?>
                <input type="hidden" name="columns[]" value="<?= htmlspecialchars($column) ?>">
            <?php endforeach; ?>
            <button type="submit">Search</button>
        </form>

        <form action="./export/intership_excel.php" method="POST" class="excel_form">
            <input type="hidden" name="columns" value="<?= htmlspecialchars(json_encode($columns)) ?>">
            <input type="hidden" name="search_query" value="<?= htmlspecialchars($searchQuery) ?>">
            <button type="submit">Export to Excel</button>
        </form>
    </div>

    <div class="table_box">
        <table class="table">
            <thead>
                <tr>
                    <?php foreach ($selectColumns as $selectColumn): ?>
                        <?php
                        preg_match('/(?:AS\s+)?(\w+)$/', $selectColumn, $matches);
                        $header = $matches[1] ?? $selectColumn;
                        ?>
                        <th><?= htmlspecialchars($header) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <?php foreach ($selectColumns as $selectColumn): ?>
                                <?php
                                preg_match('/(?:AS\s+)?(\w+)$/', $selectColumn, $matches);
                                $column = $matches[1] ?? $selectColumn;
                                ?>
                                <td><?= htmlspecialchars($row[$column]) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= count($columns) ?>">No results found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
</body>
</html>
