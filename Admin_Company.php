<?php
require('./connection.php');
$input = isset($_POST['company_search']) ? $_POST['company_search'] : '';
// error_reporting(0);
session_start();

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM company WHERE Comp_ID = ?";
    $stmt = $connect->prepare($delete_sql);
    $stmt->bind_param('i', $delete_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = 'Record deleted successfully';
    } else {
        $_SESSION['message'] = 'Failed to delete record';
    }
    header('Location: Admin_company.php');
    exit;
}

if (isset($_POST['update_company'])) {
    $comp_id = $_POST['Comp_ID'];
    $company_name = $_POST['Company_Name'];
    $company_city = $_POST['Company_City'];
    $company_address = $_POST['Company_Address'];

    $update_sql = "UPDATE company SET Company_Name = ?, Company_City = ?, Company_Address = ? WHERE Comp_ID = ?";
    $stmt = $connect->prepare($update_sql);
    $stmt->bind_param('sssi', $company_name, $company_city, $company_address, $comp_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = 'Record updated successfully';
    } else {
        $_SESSION['message'] = 'Failed to update record';
    }
    header('Location: Admin_company.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Admin.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <script src="https://kit.fontawesome.com/e8e9c02a1b.js" crossorigin="anonymous"></script>
    <link rel="icon" href="./Images/collageLOGO.png">
    <title>Internship Management</title>
    <!-- update file styleing  -->
    <style>
        .popup-form {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px 0px #000;
            z-index: 1000;
            display: none;
            width: 450px;
            height: 300px;
        }

        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 999;
            display: none;
        }

        .popup-form.active, .popup-overlay.active {
            display: block;
        }
        .input_field{
            /* border: 2px solid red; */
           padding: 5px;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }
        .input_field input{
            width: 60%;
            height: 2rem;
            margin-left: 1rem;
            font-size: 1rem;
            padding: 5px;
            font-weight: 550;
        }
        .message {
            background: #4CAF50;
            color: #fff;
            padding: 10px;
            text-align: center;
            margin-bottom: 10px;
        }
        td a{
            display: inline-block;
            text-decoration: none;
            text-align: center;
            padding: 5px;
        }
    </style>
</head>

<body>
    <?php include('Admin.php'); ?>

    <?php if (isset($_SESSION['message'])) { ?>
    <script>    alert("<?php echo $_SESSION['message']; unset($_SESSION['message']); ?>") </script> 
    <?php } ?>

    <Section class="Admin_data">
        <div class="action_box">
            <h3>Search</h3>
            <div class="search-box">
                <form action="" method="POST" class="search_forms">
                    <input type="text" class="search" id="search" placeholder="Search Courses" name="company_search" value="<?php echo htmlspecialchars($input); ?>">
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>

        <div class="table_box">
            <?php
            $sql = "SELECT * FROM `company` WHERE `Company_Name` LIKE '%$input%' OR `Company_City` LIKE '%$input%' OR `Company_Address` LIKE '%$input%' OR `Comp_ID` LIKE '%$input%' OR `Date` LIKE '%$input%'";
            $company = mysqli_query($connect, $sql);
            $Company_wise = mysqli_num_rows($company) > 0;

            if ($Company_wise) {
            ?>
                <table class="table" style="display:table;">
                    <thead class="thead">
                        <tr>
                            <th>Comp_Id</th>
                            <th>CompanyName</th>
                            <th>Company City</th>
                            <th>Company Address</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($data = mysqli_fetch_assoc($company)) { ?>
                            <tr>
                                <td><?php echo $data['Comp_ID']; ?></td>
                                <td><?php echo $data['Company_Name']; ?></td>
                                <td><?php echo $data['Company_City']; ?></td>
                                <td><?php echo $data['Company_Address']; ?></td>
                                <td><?php echo $data['Date']; ?></td>
                                <td >
                                    <button class="action_buttons" onclick="openUpdateForm('<?php echo $data['Comp_ID']; ?>', '<?php echo $data['Company_Name']; ?>', '<?php echo $data['Company_City']; ?>', '<?php echo $data['Company_Address']; ?>')">Update</button>

                                    <a href="" onclick="return confirm('Are you sure you want to delete this record?')" disable class="action_buttons" >Delete</a>

                                    <!-- href="?delete_id=<?php //  echo $data['Comp_ID'];  ?>  " -->
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { echo "No results found."; } ?>
        </div>
    </Section>

    <div class="popup-overlay" id="popupOverlay"></div>
    <div class="popup-form" id="popupForm">
        <form method="POST">
            <input type="hidden" name="Comp_ID" id="updateCompID">
            <br>
            <div class="input_field">
                <label>Company Name</label>
                <input type="text" name="Company_Name" id="updateCompanyName" required>
            </div>
            <div class="input_field">
                <label>Company City</label>
                <input type="text" name="Company_City" id="updateCompanyCity" required>
            </div>
            <div class="input_field">
                <label>Company Address</label>
                <input type="text" name="Company_Address" id="updateCompanyAddress" required>
            </div>
            <div class="update_btn" style="margin-top: 3rem; margin-left:3rem ">
                <button type="submit" name="update_company">Update</button>
                <button type="button" onclick="closeUpdateForm()" class="action_buttons">Cancel</button>
            </div>
        </form>
    </div>

    <script>
        function openUpdateForm(id, name, city, address) {
            document.getElementById('updateCompID').value = id;
            document.getElementById('updateCompanyName').value = name;
            document.getElementById('updateCompanyCity').value = city;
            document.getElementById('updateCompanyAddress').value = address;
            document.getElementById('popupOverlay').classList.add('active');
            document.getElementById('popupForm').classList.add('active');
        }

        function closeUpdateForm() {
            document.getElementById('popupOverlay').classList.remove('active');
            document.getElementById('popupForm').classList.remove('active');
        }
    </script>
</body>
</html>
