<?php
require('connection.php');
session_start();

$input = isset($_POST['marks_search']) ? $_POST['marks_search'] : '';


if (isset($_POST['markassign'])) {
    $internal = $_POST['Internal'];
    $project = $_POST['Project'];
    $industry = $_POST['Industry'];
    $total = $_POST['Total'];
    $studentId = $_POST['student_id'];
    $date = date('Y-m-d');

    if (!empty($studentId) && is_numeric($internal) && is_numeric($project) && is_numeric($industry) && is_numeric($total)) {
        $query = "INSERT INTO `internal_marks`(`MarkId`, `MarkUser`, `InternalMarks`, `ProjectMarks`, `Industry_mark`, `Total`, `Date`) 
                  VALUES ('', '$studentId', '$internal', '$project', '$industry', '$total', '$date')";

        $marks_result = mysqli_query($connect, $query);

        if ($marks_result) {
            $done = "Marks assigned successfully!";
        } else {
            $error[] = "Failed: Database Error - " . mysqli_error($connect);
        }
    } else {
        $error[] = "Please fill all fields correctly.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Admin.css">
    <!-- <link rel="stylesheet" href="./css/Admin.css"> -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <script src="https://kit.fontawesome.com/e8e9c02a1b.js" crossorigin="anonymous"></script>
    <link rel="icon" href="./Images/collageLOGO.png">
    <title>Internship Management</title>
    <script>
        // JavaScript to calculate total marks dynamically
        document.addEventListener("DOMContentLoaded", () => {
            const internal = document.getElementById("internal");
            const project = document.getElementById("project");
            const industry = document.getElementById("industry");
            const total = document.getElementById("total");

            const updateTotal = () => {
                const internalMarks = parseInt(internal.value) || 0;
                const projectMarks = parseInt(project.value) || 0;
                const industryMarks = parseInt(industry.value) || 0;
                total.value = internalMarks + projectMarks + industryMarks;
            };

            [internal, project, industry].forEach((input) =>
                input.addEventListener("input", updateTotal)
            );
        });
    </script>
</head>

<body>
    <?php
        include('Admin.php') ; 
    ?>
    <Section class="Admin_data">
           
             <div class="actions">
                <?php if (!empty($done)) echo "<p style='color: green;'>$done</p>"; ?>
                <?php if (!empty($error)) echo "<p style='color: red;'>" . implode('<br>', $error) . "</p>"; ?>
             </div>                               

            <div class="table_box">
            <form action="" method="POST">
               <table class="table">
                    <thead class="thead">
                         <tr>
                                <th >Student Name</th>
                                <th >Roll No</th>   
                                <th >Internal Marks</th>
                                <th >Project Marks</th>
                                <th >Industry Marks</th>
                                <th >Total Marks</th>
                                <th >Action</th>
                          </tr>
                    </thead>
                    <tbody class="tbody">
                         <tr>
                                <td class="user">
                                    <select name="student_id" required class="drop">
                                        <option value="">Select Student</option>
                                        <?php 
                                            $students = mysqli_query($connect, "SELECT Student_Id, FirstName, LastName FROM student_details");
                                            while ($row = mysqli_fetch_assoc($students)) {
                                                $studentId = $row['Student_Id'];
                                                $fullName = $row['FirstName'] . " " . $row['LastName'];
                                                echo "<option value='$studentId'>$fullName</option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td class="user">
                                    <select name="roll" required class="drop">
                                        <option value="">Select Roll No</option>
                                        <?php 
                                        $rolls = mysqli_query($connect, "SELECT Roll_no FROM student_details");
                                        while ($row = mysqli_fetch_assoc($rolls)) {
                                            $rollNo = $row['Roll_no'];
                                            echo "<option value='$rollNo'>$rollNo</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td class="user">
                                    <input type="number" class="marksIn" name="Internal" id="internal" placeholder="Internal Marks" required>
                                </td>
                                <td class="user">
                                    <input type="number" class="marksIn" name="Project" id="project" placeholder="Project Marks" required>
                                </td>
                                <td class="user">
                                    <input type="number" class="marksIn" name="Industry" id="industry" placeholder="Industry Marks" required>
                                </td>
                                <td class="user">
                                    <input type="number" class="marksIn" name="Total" id="total" placeholder="Total Marks" readonly>
                                </td>
                                <td class="user">
                                    <button type="submit" class="marksIn"  name="markassign">Assign</button>
                                </td>
                            </tr>
                    </tbody>
                </table>
            </form>
            </div>

<!-- _________________ display table _____________  -->

             <div class="actions">
                    <form action="" method="POST" class="search_forms">
                            <label>Search:</label>
                             <input type="text" class="search" id="search" placeholder="Search MarkId, Roll No or Date" name="marks_search" value="<?php echo isset($input) ? htmlspecialchars($input) : ''; ?>" >
                            <button type="submit">Search</button>
                    </form> 
             </div>                               

            <div class="table_box">
                 <?php
                $input = isset($_POST['marks_search']) ? $_POST['marks_search'] : '';

                $sql = "SELECT im.MarkId, sd.Roll_No, im.InternalMarks, im.ProjectMarks, im.Industry_mark, im.Total, im.Date 
                        FROM internal_marks AS im
                        INNER JOIN student_details AS sd ON im.MarkUser = sd.Student_Id
                        WHERE im.MarkId LIKE '%$input%' OR sd.Roll_No LIKE '%$input%' OR im.Date LIKE '%$input%'";
        
                $marks = mysqli_query($connect, $sql);
                $marks_found = mysqli_num_rows($marks) > 0;
                if ($marks_found) {
                ?>
               <table class="table" style=" display:table;">
                    <thead class="thead">
                        <tr>
                                <th>MarkId</th>
                                <th>Roll_No</th>
                                <th>InternalMarks</th>
                                <th>ProjectMarks</th>
                                <th>Industry_mark</th>
                                <th>Total</th>
                                <th>Date</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                    <?php while ($rows = mysqli_fetch_assoc($marks)) { ?>
                        <tr>
                            <td><?php echo $rows['MarkId'] ?></td>
                            <td><?php echo $rows['Roll_No'] ?></td>
                            <td><?php echo $rows['InternalMarks'] ?></td>
                            <td><?php echo $rows['ProjectMarks'] ?></td>
                            <td><?php echo $rows['Industry_mark'] ?></td>
                            <td><?php echo $rows['Total'] ?></td>
                            <td><?php echo $rows['Date'] ?></td>
                        </tr>
                           <?php } ?>
                         
                          </tbody>
                    </table>
                    <?php } else { ?>
                        <p>No results found.</p>
                    <?php } ?>
                            </div>



    </Section>
                <?php
            mysqli_close($connect); // Close the connection at the end of the script
            ?>
                    







        

    <script>
        const body = document.querySelector("body"),
            sidebar = body.querySelector("nav"),
            sidebarToggle = body.querySelector(".sidebar-toggle");

        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        });

        // Table hovers effects 
        document.addEventListener('DOMContentLoaded', () => {
        const rows = document.querySelectorAll('.table tbody tr');
            rows.forEach(row => {
                row.addEventListener('click', () => {
                    rows.forEach(r => r.classList.remove('highlight'));
                    row.classList.add('highlight');
                });
            });
        });

        

            // total 
            const inter = document.getElementById("internal");
            const pro = document.getElementById("project");
            const ind = document.getElementById("industry");
            const total = document.getElementById("total");

            const form = document.querySelector(" form tbody");
            console.log(form);


    </script>
</body>

</html>
