<?php
require('./connection.php');
$input = isset($_POST['company_search']) ? $_POST['company_search'] : '';

session_start();

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
</head>

<body>
    <?php
        include('Admin.php') ; 
    ?>
    <Section class="Admin_data">
            
            
             <div class="action_box">
             <h3>Search</h3>
                        <div class="search-box">
                           
                            <form action="" method="POST" class="search_forms">
                                <input type="text" class="search"  id="search" placeholder="Search Courses" name="company_search" value="<?php echo htmlspecialchars($input); ?>">
                                <button type="submit">Search</button>
                            </form>
                          
                            
                        </div> 
             </div>                            

            <div class="table_box">
                 <?php
                // if ($input) {
                    $sql = "SELECT * FROM `company`WHERE `Company_Name` LIKE '%$input%' OR `Company_City` LIKE '%$input%' OR `Company_Address` LIKE '%$input%' OR`Comp_ID` LIKE '%$input%' OR `Date` LIKE '%$input%'";
                    
                    // SELECT `Comp_ID`, `Company_Name`, `Company_City`, `Company_Address`, `Date`


                    $company=mysqli_query($connect,$sql);
                    $Company_wise=mysqli_num_rows($company)>0;


                    if ($Company_wise) {
                ?>
               <table class="table" style=" display:table;">
                    <thead class="thead">
                        <tr>
                            <th class="t_course">Comp_Id</th>
                            <th class="t_course">CompanyName</th>
                            <th class="t_course">Company City </th>
                            <th class="t_course">Company Address </th>
                            <th class="t_course">Date </th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                                <?php while($data=mysqli_fetch_assoc($company)) { ?>
                          <tr>
                                <td class="user1"> <?php echo $data['Comp_ID'] ?></td>
                                        <td> <?php echo $data['Company_Name'] ?></td>
                                <td> <?php echo $data['Company_City'] ?></td>
                                <td> <?php echo $data['Company_Address'] ?></td>
                                <td> <?php echo $data['Date'] ?></td>
                          </tr> 
                          <?php } ?>  
                         
                          </tbody>
                    </table>
                    <?php
                    } else {
                        echo "No results found.";
                    }
                
                ?>  
            </div>

    </Section>
       
        







        

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

    </script>
</body>

</html>
