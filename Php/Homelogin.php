
<?php
if (isset($_POST['Login'])) {
    // Get input values
    $Email = $_POST['email'];
    $Password = $_POST['password'];

    // Query to check in 'register' table (for regular users)
    $user_query = "SELECT * FROM register WHERE (UserName='$Email' OR Email='$Email')";
    $user_result = mysqli_query($connect, $user_query);

    // Query to check in 'admin' table
    $admin_query = "SELECT * FROM admin WHERE (Username='$Email' OR Email='$Email')";
    $admin_result = mysqli_query($connect, $admin_query);

    // Check in 'register' table
    if (mysqli_num_rows($user_result)) {
        $user_data = mysqli_fetch_assoc($user_result);

        if ($user_data['Password'] === $Password) {
            // Set session variables for user
            $_SESSION['Fname'] = $user_data['FullName'];
            $_SESSION['id'] = $user_data['PRN_No'];
            $_SESSION['Umail'] = $user_data['Email'];
            $_SESSION['Uname'] = $user_data['UserName'];

            // Redirect to internship home page
            echo "<script>
                alert('User Login Successful.');
                location.replace('Intership_Home.php');
            </script>";
        } else {
            echo "<script>alert('Wrong Password for User.');</script>";
        }
    }
    // Check in 'admin' table
    elseif (mysqli_num_rows($admin_result)) {
        $admin_data = mysqli_fetch_assoc($admin_result);

        if ($admin_data['Password'] === $Password) {
            // Set session variables for admin
            $_SESSION['AdminName'] = $admin_data['UserName'];
            $_SESSION['AdminEmail'] = $admin_data['Email'];

            // Redirect to admin page
            echo "<script>
                alert('Admin Login Successful.');
                location.replace('Admin_student.php');
            </script>";
        } else {
            echo "<script>alert('Wrong Password for Admin.');</script>";
        }
    } else {
        echo "<script>alert('Invalid Email or Password.');</script>";
    }
}

?>