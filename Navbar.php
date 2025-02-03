<?php
require("connection.php");
// session_start();
error_reporting(0);

    
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Main.css">
    <script src="https://kit.fontawesome.com/e8e9c02a1b.js" crossorigin="anonymous"></script>

    <title>Document</title>

<style>
body::-webkit-scrollbar {
    width: 15px;
}
body::-webkit-scrollbar-thumb {
    background-color:#a5d8ff;
    
}

.Navbar{
    display: flex;
    justify-content: space-between;
    box-shadow: 0px 5px 10px 0px rgb(160, 154, 154);
    background-color: #fff;
    opacity: 0.85;
    z-index: 999;
    position: relative;
    max-width: 1520px;
    width: 100%;
    margin-top: 5px;
    margin-bottom: 2rem;

}
.company .clgglogos{
    width: 3rem;
    height: 3rem;
    margin: 0.5rem;

}
.menu-box{
    display: flex;
    justify-content: space-around;
}
.Firstname{
    font-size: 15px;
    font-weight: 500;
    /* border: 2px solid red; */
    /* padding: 1rem; */
    margin-left: 5px;
    margin-top: 1rem;
}
.menu-items{
    /* border: 2px solid red;   */
    margin-right: 5rem;
}
.items{
    display: flex;
    justify-content: space-between;
    margin-top: 1rem;
    list-style: none;

}
.items .item{
    margin-left: 3rem;
}

.items .item a {
    text-decoration: none;
    padding: 2px;
    font-size: 1.2rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #000;
    font-weight: 300;
    transition: .1s ease-in;
}
.items .item a:hover{
    color:rgb(0, 72, 255);
    border-bottom: 2px solid  rgb(0,72,255);
}

.profile_section{
    /* border: 2px solid red; */
    width: 18rem;
    height: 4rem;

}
.cart
{
    font-size: 22px;
    margin-right: 5rem;
    padding: 8px 8px;
    background-color: #f7f7f7;
    border-radius: 5px;
    color: black;
    /* border: 2px solid black; */
    transition: all .3s ease ;
    cursor: pointer; 
    padding-top: 10px; 
}
.cart:hover
{
    color: #fff;
    background-color: #00B0FF;
    
   
}

.user_profile 
{
    top: 6px;
    height: 3rem;
    position: absolute;
    width: 0.5px;
    font-size: 28px;
    margin-left: 13rem;
    padding: 2px 22px;
    background-color: #f60;
    border-radius: 5px;
    color: #fff;
    border: 2px solid #f60;
    transition: all .3s ease;
    cursor: pointer;

}
.user_profile:hover{
    border: 2px solid #f60; 
    color: #f60;
    background-color: #fff;
}   
.user_profile i{
    position: relative;
    right: 9px;
    top: -1px;
    font-size: 1.5rem;
    /* border: 2px solid black; */
    width: 2.9rem;
    height: 2.7rem;
    left: -20px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: space-around;
}
.user_profile{
    opacity: 255;
}
.submenu_wrap
{
    position: absolute;
    top: 100%;  
    right: 2%;
    width: 250px;
    max-height:0px;
    opacity: 255;
    overflow: hidden;
    transition:  max-height .6s;
}
.submenu_wrap.open_menu
{
    max-height:350px;
    opacity: 255;
}
.submenu
{
    background-color: #fff;
    padding: 20px 20px 2px 20px;
    margin: 8px 5px 5px 5px;

    border-radius: 10px;
}
.user_info
{
    display: flex;
    align-items: center;
    justify-content: center;
    
}
.Email
{
    font-size: 1rem;
    font-weight: 500;
    color: #000;
    margin-bottom: 10px;
}
.submenu hr
{
    border: 0;
    height: 1px;
    width: 100%;
    background:#6440FB;
    margin: 3px 0px;
}
.submenu_link
{
    display: flex;
    align-items: center;
    text-decoration: none;
    /* color: #21225f; */
    margin: 15px 0px;

}
.submenu_link i
{
    background-color: #F5F7FE;
    padding: 10px;
    margin-left: 1rem;
    font-size: 1rem;
    border-radius: 50%;
    color: #21225f;
}
.submenu_link p
{
   color: #000;
   font-weight: 500;
   font-size: 1.1rem;
   width: 100%;
   transition: all ease .1s;
}
.submenu_link span
{
   color: #000;
   font-weight: 600;
   font-size: 12px;
   transition: all .1s;
}
.submenu_link:hover span
{
    transform: translateX(5px);
}
.submenu_link:hover p
{
   font-size: 17px;
}

/* For Search Icon in search field */
.search_logo
{
    color: #140342;    
}
.searchbtn
{
    border: none;
    background: none;
    cursor: pointer;
}
.admin{
    /* background-color: #9775fa;h1
    border-color: #9775fa; */
    color:#000;
}
.forms{
    width: 12rem;
    position: absolute;
    left: 7rem;
    list-style: none;
    padding-top: 1rem;
    padding-left: 0;
    background-color: #fff;
    margin-top: 1rem;
    opacity: 0;
    visibility: hidden;
    transition: all .5s ease ;
    border-radius: 3px;
    box-shadow: 0 0 5px 0.1px #000;


}
.item:hover .forms{
    opacity: 255;
    visibility: visible;
    transform: translateY(3px);
}
.form{
    height: 2rem;
    font-size: 1rem;
    padding-top: 7px;
    padding-bottom: 7px;
    /* border: 2px solid red; */
    margin-bottom: 10px;
    padding-left: 1rem;
}
.form:hover{
    background-color: #e0e0e0;
}
.items .item .forms .form a{
    font-size: 13px;
}
</style>
</head>
<body>
    


<div class="Navbar">
          <div class="menu-box">
               
               <div class="company">
                   <img src="./Images/collageLOGO.jpeg" alt="Logo" class="clgglogos">
               </div>
               <div class="menu-items">
                    <ul class="items">
                        <li class="item"><a href="./Intership_Home.php">Home</a></li>
                        <li class="item"><a href="./student_details.php">Student Details</a></li>
                        <li class="item"><a href="./intership_details.php">  Internship Details</a></li>
                         <!-- <li class="item"><a >Forms</a>
                             <ul class="forms">
                                <li class="form"><a href="./student_details.php"> Student Details </a>  </li>
                                <li class="form"><a href="./intership_details.php">  Internship Details</a>  </li>
                            </ul>
                        </li> -->

                        
                    </ul>
               </div>
          </div>


          <!-- <div class="users"><a href="./user_profile.php">
          <i class="fa-solid fa-user-tie profile admin"> </i> </a>

          </div> -->
          <div class="profile_section">
                  <div class="buttons flex">
                        <!-- <a class="btn2" href="StudyLab_login.php" > <div >Login</div></a> -->
                        <?php
                            echo'<p class="Firstname"> Hello, '.$_SESSION['Uname'];'</p>';
                        ?>   
                    </div>
                    <div class="user_profile">
                    
                        <i class="far fa-user profile" onclick="toggleMenu()"></i>

                            <div class="submenu_wrap" id="sub_Menu">
                                <div class="submenu">
                                    <div class="user_info">
                                        <p> <?php
                                                 echo'<p class="Email">  '.$_SESSION['Umail'];'</p>';
                                            ?>  </p>
                                    </div>
                                    
                                    <hr>

                                    <a href="UserProfile.php" class="submenu_link">
                                        <i class="fas fa-user-alt"></i>
                                        <p>Edit profile</p>
                                        <span> > </span>
                                    </a>

                                    <a href="./notification.php" class="submenu_link">
                                    <i class="fa-duotone fa-regular fa-solid fa-bell"></i>
                                        <p>Notification</p>
                                        <span> > </span>
                                    </a>

                                    <a href="Contact.php" class="submenu_link">
                                        <i class="fas fa-phone"></i>
                                        <p>Help & Support</p>
                                        <span> > </span>
                                    </a>

                                    <a href="Logout.php" class="submenu_link">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <p>Logout</p>
                                        <span> > </span>
                                    </a>

                                </div>

                            </div>
                    </div>
              </div>

     </div>




     <script>
    let sub_Menu =document.getElementById("sub_Menu");

    function toggleMenu(){
        sub_Menu.classList.toggle("open_menu")
    }
</script>

</body>
</html>