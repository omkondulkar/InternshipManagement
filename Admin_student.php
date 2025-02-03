<?php
require('connection.php');
require('./phpspredsheet/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();

// Get selected columns, search, and order inputs
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

// Fixing selected column logic
$selectedColumns = !empty($columns) ? implode(', ', $columns) : '*';
if(!in_array('Student_Id',$columns)){
    array_unshift($columns,'Student_Id');
}

$selectClause = !empty($selectColumns) ? implode(', ', $selectColumns) : '*';

// SQL Query Preparation
$sql = "SELECT $selectedColumns FROM `student_details`";

// Adding search filter
if (!empty($searchQuery) && !empty($searchField) && array_key_exists($searchField, $availableColumns)) {
    $searchQuery = mysqli_real_escape_string($connect, $searchQuery);
    $sql .= " WHERE $searchField LIKE '%$searchQuery%'";
}

// Adding order by clause
if (!empty($orderBy) && array_key_exists($orderBy, $availableColumns)) {
    $sql .= " ORDER BY $orderBy";
}

// Execute query
$result = mysqli_query($connect, $sql);

?>

<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Management</title>
</head>
<body>
    <?php include('Admin.php'); ?>

    <section class="Admin_data">
        <div class="container">
            <form method="POST">
                <div class="perfom_box">
                    <div class="panel">
                        <div>
                            <h3>Available Columns</h3>
                            <ul id="availableColumns">
                                <?php foreach ($availableColumns as $key => $value): ?>
                                    <?php if (!in_array($key, $columns)): ?>
                                        <li class="column_list">
                                            <?php echo $value; ?>
                                            <button type="button" class="button" onclick="addToSelected('<?php echo $key; ?>', '<?php echo $value; ?>')">>></button>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul> 
                        </div>
                        <div class="selected-box">
                            <h3>Selected Columns</h3>
                            <ul id="selectedFields" class="column_list">
                                <?php foreach ($columns as $column): ?>
                                    <li>
                                        <input type="hidden" name="columns[]" value="<?php echo $column; ?>">
                                        <?php echo $availableColumns[$column]; ?>
                                        <button type="button" class="button" onclick="removeFromSelected(this, '<?php echo $column; ?>', '<?php echo $availableColumns[$column]; ?>')">Undo</button>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="action_box">
                        <div class="search-box">
                            <h3>Search</h3>
                            <select name="search_field">
                                <option value="">Select Field</option>
                                <?php foreach ($availableColumns as $key => $value): ?>
                                    <option value="<?php echo $key; ?>" <?php echo $searchField == $key ? 'selected' : ''; ?>><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <br>
                            <input type="text" name="search_query" placeholder="Enter the column name" value="<?php echo htmlspecialchars($searchQuery); ?>">
                        </div>
                        <div class="order-box">
                            <h3>Order By</h3>
                            <select name="order_by">
                                <option value="">Select Column</option>
                                <?php foreach ($availableColumns as $key => $value): ?>
                                    <option value="<?php echo $key; ?>" <?php echo $orderBy == $key ? 'selected' : ''; ?>><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit">Apply Filters</button>
            </form>
            
            <div class="table_box">
                <form action="./export/export_to_excel.php" method="POST" class="excel_form">
                        <input type="hidden" name="columns" value="<?php echo htmlspecialchars(json_encode($columns)); ?>">
                        <input type="hidden" name="search_query" value="<?php echo htmlspecialchars($searchQuery); ?>">
                        <button type="submit" class="exportbtn" name="intership_excel" >Export to Excel</button> 
             </form>

                <table class="table">
                    <thead>
                        <tr>
                            <?php foreach (explode(',', $selectedColumns) as $column): ?>
                                <th><?php echo htmlspecialchars(trim($column)); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <?php foreach (explode(',', $selectedColumns) as $column): ?>
                                        <td><?php echo htmlspecialchars($row[trim($column)] ?? ''); ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?php echo count(explode(',', $selectedColumns)); ?>">No results found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script>
        function addToSelected(key, value) {
            // Add the selected column to the "Selected Columns" list
            const selectedList = document.getElementById('selectedFields');
            const availableList = document.getElementById('availableColumns');
            const listItem = document.createElement('li');

            listItem.innerHTML = `
                <input type="hidden" name="columns[]" value="${key}">
                ${value}
                <button onclick="removeFromSelected(this, '${key}', '${value}')">Undo</button>
            `;
            selectedList.appendChild(listItem);

            // Remove the column from "Available Columns"
            const availableItems = availableList.querySelectorAll('li');
            availableItems.forEach(item => {
                if (item.textContent.includes(value)) {
                    item.remove();
                }
            });
        }
        function removeFromSelected(button, key, value) {
       // Remove the selected column from the "Selected Columns" list
            const selectedList = button.closest('li');
            selectedList.remove();

            // Add the column back to "Available Columns"
            const availableList = document.getElementById('availableColumns');
            const listItem = document.createElement('li');

            listItem.innerHTML = `
                ${value}
                <button onclick="addToSelected('${key}', '${value}')">>></button>
            `;
            availableList.appendChild(listItem);
        }
            function toggleSort(column) {
        const form = document.querySelector('form');
        const currentOrderBy = document.querySelector('input[name="order_by"]');
        const currentOrderDirection = document.querySelector('input[name="order_direction"]');

        if (currentOrderBy && currentOrderBy.value === column) {
            currentOrderDirection.value = currentOrderDirection.value === 'ASC' ? 'DESC' : 'ASC';
        } else {
            if (!currentOrderBy) {
                const orderByInput = document.createElement('input');
                orderByInput.type = 'hidden';
                orderByInput.name = 'order_by';
                orderByInput.value = column;
                form.appendChild(orderByInput);
            } else {
                currentOrderBy.value = column;
            }

            if (!currentOrderDirection) {
                const directionInput = document.createElement('input');
                directionInput.type = 'hidden';
                directionInput.name = 'order_direction';
                directionInput.value = 'ASC';
                form.appendChild(directionInput);
            } else {
                currentOrderDirection.value = 'ASC';
            }
        }

        form.submit();
    }

    </script>

</body>
</html>
