<?php
require('./connection.php');
// Add Notification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_notification'])) {
    $message = mysqli_real_escape_string($connect, $_POST['message']);
    $timeline = mysqli_real_escape_string($connect, $_POST['timeline']);

    if (!empty($message) && !empty($timeline)) {
        $sql = "INSERT INTO notifications (message, timeline) VALUES ('$message', '$timeline')";
        mysqli_query($connect, $sql);
    }
}

// Delete Notification
if (isset($_GET['delete_notification'])) {
    $notificationId = intval($_GET['delete_notification']);
    $sql = "DELETE FROM notifications WHERE id = $notificationId";
    mysqli_query($connect, $sql);
}

// Fetch Notifications
$notificationsSql = "SELECT * FROM notifications ORDER BY created_at DESC";
$notificationsResult = mysqli_query($connect, $notificationsSql);

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
                <h2>Manage Notifications</h2>
            <!-- Add Notification Form -->
            <form action="" method="POST" class="msg_form" >
                <textarea name="message" class="msg_write"  placeholder="Write your notification here..." required></textarea>
               <div class="time_line">

                   <label for="timeline">Timeline:</label>
                   <input type="date" name="timeline" class="time" required><br>

               </div>  

                <button type="submit" name="add_notification" class="applybtn" style="margin:2rem ;">Send</button>
            </form>
         </div>
            <!-- Notifications Table -->
            <div class="notifications_table">
                <table class="table" style="margin-left: 5rem;">
                    <thead>
                        <tr>
                            <th>Message</th>
                            <th>Timeline</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($notification = mysqli_fetch_assoc($notificationsResult)): ?>
                            <?php
                                $isExpired = (new DateTime($notification['timeline']) < new DateTime());
                                $rowClass = $isExpired ? 'expired' : 'active';
                            ?>
                            <tr class="<?php echo $rowClass; ?>">
                                <td><?php echo htmlspecialchars($notification['message']); ?></td>
                                <td><?php echo htmlspecialchars($notification['timeline']); ?></td>
                                <td><?php echo $isExpired ? 'Expired' : 'Active'; ?></td>
                                <td>
                                    <a href="?delete_notification=<?php echo $notification['id']; ?>" class="delete-btn">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
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
