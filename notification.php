<?php
require('./connection.php');
session_start();
// Fetch Active Notifications
$activeNotificationsSql = "SELECT * FROM notifications ORDER BY created_at DESC";
$activeNotificationsResult = mysqli_query($connect, $activeNotificationsSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Admin.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .notification_box {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
        }

        .notification_box h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
            padding-bottom: 1rem;
            border-bottom: 3px solid orange;
        }

        .notification_box ul {
            list-style: none;
            padding: 0;
        }

        .notification_box li {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            color: #fff;
            font-size: 18px;
            animation: move 5s linear infinite;
        }

        .notification_box li.active {
            background-color: #28a745; /* Green for active notifications */
        }

        .notification_box li.expired {
            background-color: #dc3545; /* Red for expired notifications */
        }

        /* @keyframes move {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-100%);
            }
        } */
    </style>
        <link rel="icon" href="./Images/collageLOGO.png">
        <title>Internship Management</title></head>
<body>
    <?php
        include("./Navbar.php");    
    ?>
    <div class="notification_box">
        <section class="Student_notifications">
            <h2>Notifications</h2>
        <?php
        if(isset($activeNotificationsResult)){
        ?>

            <ul>
                <?php while ($notification = mysqli_fetch_assoc($activeNotificationsResult)): ?>
                    <?php
                        // Determine the class based on the timeline
                        $class = strtotime($notification['timeline']) >= time() ? 'active' : 'expired';
                    ?>
                    <li class="<?php echo $class; ?>">
                    <marquee direction="left">
                        <?php 
                            echo htmlspecialchars($notification['message']); 
                        ?> 
                        (Valid until: <?php echo htmlspecialchars($notification['timeline']); ?>)
                    </marquee>
                    </li>
                <?php endwhile; ?>
            </ul>

            <?php
        }else {
           ?>
            <ul>
                <p>No Any Notice </p>
            </ul>
           
           <?php

        }
            ?>
        </section>
    </div>
</body>
</html>
