<?php
require('./connection.php');
require('./phpspredsheet/vendor/autoload.php'); // Include PhpSpreadsheet via Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
session_start();

$input = isset($_POST['company_search']) ? $_POST['company_search'] : '';
$columns = isset($_POST['columns']) ? $_POST['columns'] : [];
$searchField = isset($_POST['search_field']) ? $_POST['search_field'] : '';
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

// Retain selected columns in session to prevent resetting on refresh
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['columns'])) {
    $_SESSION['selected_columns'] = $columns;
} elseif (isset($_SESSION['selected_columns'])) {
    $columns = $_SESSION['selected_columns'];
}

// Prepare SQL query for searching
$selectedColumns = !empty($columns) ? implode(',', $columns) : '*';
$sql = "SELECT $selectedColumns FROM `student_details`";

if (!empty($searchQuery) && !empty($searchField) && array_key_exists($searchField, $availableColumns)) {
    $sql .= " WHERE $searchField LIKE '%" . mysqli_real_escape_string($connect, $searchQuery) . "%'";
}

$result = mysqli_query($connect, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="./css/Admin.css"> -->
    <link rel="stylesheet" href="./Admin.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <script src="https://kit.fontawesome.com/e8e9c02a1b.js" crossorigin="anonymous"></script>
    <link rel="icon" href="./Images/collageLOGO.png">
    <title>Internship Management</title></head>

<body>
    <?php
        include('Admin.php'); 
    ?>
    <section class="Admin_data">
        <div class="column_select">
            <form action="" method="POST" class="column_form">
                <label>Select Columns:</label><br>
                <div class="select_item">
                    <?php foreach ($availableColumns as $key => $value): ?>
                        <input type="checkbox" name="columns[]" value="<?php echo $key; ?>" <?php echo in_array($key, $columns) ? 'checked' : ''; ?> >
                        <?php echo $value; ?>
                    <?php endforeach; ?>
                </div>
                <br>
                <button type="submit" class="applybtn">Done</button>
            </form>
        </div>
        <div class="actions">
            <form action="" method="POST" class="search_forms">
                <label>Search By:</label>
                <select name="search_field" class="search_field">
                    <option value="">Select Field</option>
                    <?php foreach ($availableColumns as $key => $value): ?>
                        <option value="<?php echo $key; ?>" <?php echo $searchField == $key ? 'selected' : ''; ?>><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="search_query" class="search" value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit">Search</button>
            </form>

            <form action="./export/export_to_excel.php" method="POST" class="excel_form">
                <input type="hidden" name="columns" value="<?php echo htmlspecialchars(json_encode($columns)); ?>">
                <input type="hidden" name="search_field" value="<?php echo htmlspecialchars($searchField); ?>">
                <input type="hidden" name="search_query" value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit">Export to Excel</button>
            </form>
        </div>
        <div class="table_box">
            <table class="table">
                <thead class="thead">
                    <tr>
                        <?php if ($columns): ?>
                            <?php foreach ($columns as $column): ?>
                                <th><?php echo $availableColumns[$column]; ?></th>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <th colspan="<?php echo count($availableColumns); ?>">No Columns Selected</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="tbody">
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
