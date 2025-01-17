<?php
    require('connection.php');
    session_start();

    if(isset($_POST['submit_email'])) {
        $email = $_POST['email'];
        $query = "SELECT * FROM register WHERE Email='$email'";
        $result = mysqli_query($connect, $query);
        
        if(mysqli_num_rows($result) > 0) {
            $_SESSION['reset_email'] = $email;
            header('Location: ForgotPassword.php?step=2');
            exit();
        } else {
            $error[] = 'Email not found.';
        }
    }

    if(isset($_POST['reset_password'])) {
        $email = $_SESSION['reset_email'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if($new_password === $confirm_password) {
            // Directly use the new password without hashing
            $query = "UPDATE register SET Password='$new_password' WHERE Email='$email'";
            if(mysqli_query($connect, $query)) {
                $success[] = 'Password updated successfully.';
                unset($_SESSION['reset_email']);
                header('Location:Home.php');
                exit();
            } else {
                $error[] = 'Failed to update password.';
            }
        } else {
            $error[] = 'Passwords do not match.';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./Images/collageLOGO.png">
    <title>Internship Management</title>    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0,0,0,0.2),rgba(0,0,0,0.2)), url('./Images/search_img.jpg');
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 400px;
        }
        h1 {
            margin-bottom: 20px;
        }
        .input-field {
            margin-bottom: 15px;
        }
        .input-field input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            width: 100%;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if(!isset($_GET['step']) || $_GET['step'] == 1): ?>
            <h1>Forgot Password</h1>
            <form action="" method="POST">
                <?php if (isset($error)) { foreach ($error as $e) { echo '<p class="error">'.$e.'</p>'; } } ?>
                <div class="input-field">
                    <input type="email" name="email" placeholder="Enter your registered email" required>
                </div>
                <button type="submit" name="submit_email" class="btn">Submit</button>
            </form>
        <?php elseif($_GET['step'] == 2): ?>
            <h1>Reset Password</h1>
            <form action="" method="POST">
                <?php if (isset($error)) { foreach ($error as $e) { echo '<p class="error">'.$e.'</p>'; } } ?>
                <?php if (isset($success)) { foreach ($success as $s) { echo '<p class="success">'.$s.'</p>'; } } ?>
                <div class="input-field">
                    <input type="password" name="new_password" placeholder="New Password" required>
                </div>
                <div class="input-field">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <button type="submit" name="reset_password" class="btn">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
