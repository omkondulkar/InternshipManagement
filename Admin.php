<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="./Images/collageLOGO.png">
  <title>Internship Management</title>  <link rel="stylesheet" href="./Admin.css">
  <script src="https://kit.fontawesome.com/e8e9c02a1b.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

  <style>

/* Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  body {  
    font-family: Arial, sans-serif;
    display: flex;
    min-height: 100vh;
    background-color: #e9f4fdf7;
  }
  
  .dashboard-container {
    display: flex;
    width: 100%;  
  }
  
  .sidebar {
    width: 250px;
    max-height: 100vh;
    height: 100%;
    background-color: #e9f4fdf7;
    color: #000000;
    display: flex;
    flex-direction: column;
    padding: 20px;


  }
  
  .sidebar .logo {
    position: absolute;
    font-size: 1.6rem;
    font-weight: bold;
    margin-bottom: 30px;
    top: 1rem;
    left: 2rem;
  }
  
  .sidebar nav ul {
    list-style: none;
    margin-top: 6rem;
    
  }
  
  .sidebar nav ul li a {
    text-decoration: none;
    color: #000000;
    padding: 10px;
    display: block;
    margin-bottom: 10px;
    border-radius: 5px;
    transition: background 0.3s;
  }
  
  .sidebar nav ul li a i {
    margin-right: 8px;
    font-size: 17x;
  }
  .sidebar nav ul li a:hover {
    background-color: #cfd0ff;
    border-left: 10px solid #8c8ef4 ;
  }
  
  .add-product-btn {
    background-color: #e3f0f9f7;
    color: white;
    padding: 10px;
    margin-top: auto;
    text-align: center;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  
  .main-content {
    flex: 1;
    background-color: #e9f4fdf7;
    padding: 20px;
  }
  
  header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }
  
  .stats {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
  }
  
  .stat-card {
    flex: 1;
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }
  
  .orders table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    border-radius: 5px;
    overflow: hidden;
  }
  
  .orders table th, .orders table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }
  
  .sales-analytics {
    display: flex;
    gap: 20px;
  }
  
  .analytics-card {
    flex: 1;
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }
  



/* Additional styles for notifications and profile */

.topbar {
    padding: 8px;
    /* background-color:#91a7ff; */
    position: absolute;
    left: 17.8rem;
    top: 0;
    /* border: 2px solid red; */
    max-width: 82.3%;
    width: 100%;
    height: 4rem;
  }
  
  .topbar-right {
    display: flex;
    align-items: center;
    gap: 20px;
  }
  
  .notification {
    position: relative;
    cursor: pointer;
    /* border: 2px solid red; */
    padding: 8px;
    border-radius: 5rem;
  }
  .notification:hover{
    background-color:rgb(171, 172, 249);
  }
  
  .notification i {
    font-size: 30px;
    font-weight: 400;
}
  
  .notification .bell-icon {
    width: 20px;
    height: 20px;
    font-size: 2rem;
    color: #fff;
    background: url('bell-icon.png') no-repeat center center / cover;
  }
  
  .notification .badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: red;
    color: white;
    font-size: 12px;
    padding: 3px 6px;
    border-radius: 50%;
  }

  
  .profile {
    display: flex;
    align-items: center;
    gap: 10px;
  }
  
  .profile-pic {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
    cursor: pointer;
  }
  
  .dropdown {
    position: relative;
  }
  
  .dropdown-btn {
    border: none;
    background: none;
    font-size: 16px;
    cursor: pointer;
  }
  
  .dropdown-content {
    position: absolute;
    top: 40px;
    right: 0;
    background-color: white;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    display: none;
    flex-direction: column;
    z-index: 10;
  }
  
  .dropdown-content a {
    padding: 10px 15px;
    text-decoration: none;
    color: black;
    border-bottom: 1px solid #ddd;
    transition: background 0.3s;
  }
  
  .dropdown-content a:hover {
    background-color: #f4f4f9;
  }
  
  .dropdown-content .logout-btn {
    color: red;
  }
  
  .profile:hover .dropdown-content {
    display: flex;
  }
   .topbar-right img{
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 3rem;

  }
  .add-product-btn {
  background-color:rgb(155, 62, 70);
  color: white;
  
  margin-top: auto;
  text-align: center;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
.add-product-btn a{
  font-size: 1.1rem;
    color: #fff;
    text-decoration: none;
    /* border: 2px solid #fff; */
    width: 2rem;
    padding: 0.5rem 5rem;
}

  </style>
</head>
<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar"> 
      <div class="logo">Admin</div>
      <nav>
        <ul>
          <li>
            <a href="./Admin_student.php">
              <div class="pages">
                <i class="fa-solid fa-user-tie"></i>
                StudentDetails
              </div>
              
            </a>
          </li>
          <li>
            <a href="./Admin_intership.php">
              <i class="fa-solid fa-briefcase"></i>
              IntershipDetails
           </a>
        </li>
          <li>
            <a href="./Admin_Company.php">
              <i class="fa-solid fa-building"></i>
              CompanyDetails
            </a>
          </li>
          <li>
            <a href="./Admin_marks.php">
              <i class="fa-duotone fa-solid fa-marker"></i>
              MarksAssign
            </a>
          </li>

          <li>
            <a href="./FileUpload.php">
            <i class="fa-solid fa-file-excel"></i>
              FileUpload
            </a>
          </li>

         
          
        </ul>
      </nav>
      <button class="add-product-btn"><a href="./logout.php" >Logout</a></button>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <header class="topbar">
        <h1>Dashboard</h1>
        <div class="topbar-right">
          <!-- Notification Icons -->
          <div class="notification">
            <a href="./AdminNotification.php" style="color:#000000;">

              <i class="fa-solid fa-bell"></i>
              <span class="badge">1</span>

            </a>
          </div>
              <img src="./Images/collageLOGO.png">
          
          
        </div>
      </header>

    </main>
  </div>

  <script src="script.js"></script>
  <script>
    let sub_Menu =document.getElementById("sub_Menu");

    function toggleMenu(){
        sub_Menu.classList.toggle("open_menu")
    }
</script>
</body>
</html>
