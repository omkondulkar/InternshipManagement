<?php
require('./connection.php');
require('./phpspredsheet/vendor/autoload.php'); // Include PhpSpreadsheet via Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
session_start();

$columns = isset($_POST['columns']) ? $_POST['columns'] : [];
$searchField = isset($_POST['search_field']) ? $_POST['search_field'] : '';
$searchQuery = isset($_POST['search_query']) ? $_POST['search_query'] : '';
$orderBy = isset($_POST['order_by']) ? $_POST['order_by'] : '';

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

// SQL Query Preparation
$selectedColumns = !empty($columns) ? implode(',', $columns) : '*';
$sql = "SELECT $selectedColumns FROM `student_details`";

if (!empty($searchQuery) && !empty($searchField) && array_key_exists($searchField, $availableColumns)) {
    $sql .= " WHERE $searchField LIKE '%" . mysqli_real_escape_string($connect, $searchQuery) . "%'";
}

if (!empty($orderBy) && array_key_exists($orderBy, $availableColumns)) {
    $sql .= " ORDER BY $orderBy";
}

$result = mysqli_query($connect, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Admin.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>Internship Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
        }
        .panel, .content {
            padding: 20px;
        }
        .panel {
            width: 20%;
            background-color: #f4f4f4;
        }
        .content {
            width: 80%;
        }
        .panel ul {
            list-style: none;
            padding: 0;
        }
        .panel ul li {
            margin: 10px 0;
            cursor: pointer;
            padding: 10px;
            background-color: #e7e7e7;
            border-radius: 5px;
        }
        .panel ul li button {
            float: right;
        }
        .selected-box, .search-box, .order-box {
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="panel">
        <h3>Available Columns</h3>
        <ul id="availableColumns">
            <?php foreach ($availableColumns as $key => $value): ?>
                <li>
                    <?php echo $value; ?>
                    <button onclick="addToSelected('<?php echo $key; ?>', '<?php echo $value; ?>')">>></button>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="content">
        <form action="" method="POST">
            <!-- Selected Fields -->
            <div class="selected-box">
                <h3>Selected Fields</h3>
                <ul id="selectedFields">
                    <?php foreach ($columns as $column): ?>
                        <li>
                            <input type="hidden" name="columns[]" value="<?php echo $column; ?>">
                            <?php echo $availableColumns[$column]; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Search Box -->
            <div class="search-box">
                <h3>Search By</h3>
                <select name="search_field">
                    <option value="">Select Field</option>
                    <?php foreach ($availableColumns as $key => $value): ?>
                        <option value="<?php echo $key; ?>" <?php echo $searchField == $key ? 'selected' : ''; ?>>
                            <?php echo $value; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="search_query" value="<?php echo htmlspecialchars($searchQuery); ?>">
            </div>

            <!-- Order By Box -->
            <div class="order-box">
                <h3>Order By</h3>
                <select name="order_by">
                    <option value="">Select Column</option>
                    <?php foreach ($availableColumns as $key => $value): ?>
                        <option value="<?php echo $key; ?>" <?php echo $orderBy == $key ? 'selected' : ''; ?>>
                            <?php echo $value; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit">Apply Filters</button>
        </form>

        <!-- Results Table -->
        <div class="table_box">
            <table class="table">
                <thead>
                    <tr>
                        <?php if ($columns): ?>
                            <?php foreach ($columns as $column): ?>
                                <th><?php echo $availableColumns[$column]; ?></th>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <th>No Columns Selected</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <?php foreach ($columns as $column): ?>
                                    <td><?php echo htmlspecialchars($row[$column]); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="<?php echo count($columns); ?>">No results found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const selectedFields = document.getElementById('selectedFields');

        function addToSelected(key, value) {
            const li = document.createElement('li');
            li.innerHTML = `<input type="hidden" name="columns[]" value="${key}">${value}`;
            selectedFields.appendChild(li);
        }
    </script>
</body>
</html>
