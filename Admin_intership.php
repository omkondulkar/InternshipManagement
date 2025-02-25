<?php
require('connection.php');
require('./phpspredsheet/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();

$columns = isset($_POST['columns']) ? $_POST['columns'] : [];
$searchQuery = isset($_POST['search_query']) ? $_POST['search_query'] : '';
$searchField = isset($_POST['search_field']) ? $_POST['search_field'] : '';
$orderBy = isset($_POST['order_by']) ? $_POST['order_by'] : '';
$orderDirection = isset($_POST['order_direction']) && $_POST['order_direction'] === 'DESC' ? 'DESC' : 'ASC';

// Sanitize inputs to prevent SQL injection
$searchQuery = mysqli_real_escape_string($connect, $searchQuery);
$searchField = mysqli_real_escape_string($connect, $searchField);
$orderBy = mysqli_real_escape_string($connect, $orderBy);

// Available columns
$availableColumns = [
    'Inter_id' => 'Intern ID',
    'Name' => 'Intern Name',
    'Company' => 'Company',
    //  'CompanyCity' => 'Company City',
    // 'CompanyAddress' => 'Company Address',
    'Project_Name' => 'Project Name',
    'HR_Name' => 'HR Name',
    'HR_Email' => 'HR Email',
    'Category' => 'Category',
    'Group_Name' => 'Group Name',
    'Duration' => 'Duration',
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
    $selectColumns[] = "c.Company_City AS CompanyCity";
    $selectColumns[] = "c.Company_Address AS CompanyAddress";
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
    $fromClause .= " LEFT JOIN student_details AS sd ON id.Name = sd.Student_Id";
}
if (in_array('Company', $columns)) {
    $fromClause .= " LEFT JOIN company AS c ON id.Company = c.Comp_ID";
}

// Build the WHERE clause
$whereClause = "";
if (!empty($searchField) && array_key_exists($searchField, $availableColumns)) {
    if ($searchField === 'Company') {
        $whereClause = "WHERE c.Company_Name LIKE '%$searchQuery%' 
                        OR c.Company_City LIKE '%$searchQuery%' 
                        OR c.Company_Address LIKE '%$searchQuery%'";
    } elseif ($searchField === 'Name') {
        $whereClause = "WHERE CONCAT(sd.FirstName, ' ', IFNULL(sd.MiddleName, ''), ' ', sd.LastName) LIKE '%$searchQuery%'";
    } else {
        $whereClause = "WHERE id.$searchField LIKE '%$searchQuery%'";
    }
} elseif (!empty($searchQuery)) {
    $whereParts = [];
    foreach ($columns as $column) {
        if ($column == 'Name') {
            $whereParts[] = "CONCAT(sd.FirstName, ' ', IFNULL(sd.MiddleName, ''), ' ', sd.LastName) LIKE '%$searchQuery%'";
           
        } elseif ($column == 'Company') {
            $whereParts[] = "c.Company_Name LIKE '%$searchQuery%'";
            $whereParts[] = "c.Company_Address LIKE '%$searchQuery%'";
            $whereParts[] = "c.Company_City LIKE '%$searchQuery%'";
        } else {
            $whereParts[] = "id.$column LIKE '%$searchQuery%'";
        }
    }
    $whereClause = "WHERE " . implode(" OR ", $whereParts);
}

// Build the final query
$sql = "SELECT $selectClause $fromClause $whereClause";
if (!empty($orderBy) && array_key_exists($orderBy, $availableColumns)) {
    $sql .= " ORDER BY $orderBy $orderDirection";
}

// Execute the query
$result = mysqli_query($connect, $sql);
?>

<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Management</title>
   
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
</head>
<body>
    <?php
        include('Admin.php'); 
    ?>
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
                            <li >
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
            <form action="./export/intership_excel.php" method="POST" class="excel_form">
                        <input type="hidden" name="columns" value="<?php echo htmlspecialchars(json_encode($columns)); ?>">
                        <input type="hidden" name="search_query" value="<?php echo htmlspecialchars($searchQuery); ?>">
                        <button type="submit" name="intership_excel" >   
                        <i class="fa-solid fa-download" name="intership_excel" type="submit"></i>
                        </button>
                    </form>
            <table class="table">
                <thead>
                    <tr>
                        <?php foreach ($selectColumns as $selectColumn): ?>
                            <?php
                            preg_match('/(?:AS\s+)?(\w+)$/i', $selectColumn, $matches);
                            $header = $matches[1] ?? $selectColumn;
                            ?>
                            <th>
                                <?= htmlspecialchars($header) ?>
                                <!-- <button type="button" onclick="toggleSort('<?= $header ?>')">⇵</button> -->
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <?php foreach ($selectColumns as $selectColumn): ?>
                                    <?php
                                    preg_match('/(?:AS\s+)?(\w+)$/i', $selectColumn, $matches);
                                    $column = $matches[1] ?? $selectColumn;
                                    ?>
                                    <td><?= htmlspecialchars($row[$column] ?? '') ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= count($selectColumns) ?>">No results found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    </section>
</body>
</html>
