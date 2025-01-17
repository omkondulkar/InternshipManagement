<?php
require('connection.php');
session_start();
// echo $_SESSION['id'] ;
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
       <script src="https://kit.fontawesome.com/e8e9c02a1b.js" crossorigin="anonymous"></script>

       <!-- <link rel="stylesheet" href="./css/Main.css">    -->
       <link rel="stylesheet" href="Main.css">
    <link rel="icon" href="./Images/collageLOGO.png">
     <title>Internship Management</title>    
</head>
<body>
   
     <!-- menu  -->
    <?php 
     
     include('Navbar.php') ;
     // echo "$_SESSION[id]" ;
     // echo "  $_SESSION[Fname]" ;
     ?>
     <!-- menu-end  -->
     <section class="main_page"> 
          <div class="main" id="Home">
               <!-- <div class="main-content"> -->
                    <!-- <h2>Providing Placements and Intership to students</h2>     
                    <button type="submit" class="btn"><a href="./student_details.php"> Click Here </a></button> -->
               <!-- </div> -->
          </div>

          <div class="About" id="About">
              
               <div class="about_content">
                <h1>About</h1>
                    <p>The Vidarbha Youth Welfare Society’s Prof. Ram Meghe Institute of Technology & Research, Badnera-Amravati (Formerly well known as College of Engineering Badnera), is leading technological institute from central India. Established in the year 1983, the institute has a prestigious standing amongst the topmost Technical Institutes of Maharashtra. The Institute is approved by AICTE, New Delhi, Accredited by National Assessment and Accreditation Council (NAAC), Bangalore with Grade ‘A+’ & some of it’s UG Programmes are Accredited thrice by the National Board of Accreditation (NBA), New Delhi. The Institute is recognized by Directorate of Technical Education (DTE Mumbai), Govt. of Maharashtra and affiliated to Sant Gadge Baba Amravati University, Amravati and is offering UG, PG and Ph.D courses in Mechanical Engineering, Computer Science and Engineering, Information Technology, Electronics and Telecommunication Engineering and Civil Engineering along with PG courses like MBA and MCA.</p>
               </div>
               <div class="about_img">
                    <img src="./Images/About_right.png">
               </div>
          </div>


     </section>
<!-- ------------------ footer ----------  -->
     <?php  include('footer.php') ?>

<!-- ------------------- footer end ------------  -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  // Add smooth scrolling to all links
  $("a").on('click', function(event) {

    // Make sure this.hash has a value before overriding default behavior
    if (this.hash !== "") {
      // Prevent default anchor click behavior
      event.preventDefault();

      // Store hash
      var hash = this.hash;

      // Using jQuery's animate() method to add smooth page scroll
      // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 800, function(){

        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = hash;
      });
    } // End if
  });
});


 //javascript for  hamberger menu
// const hamberger = document.querySelector(".menu-btn");

//hamberger.addEventListener("click",()=>{
  //  hamberger.classList.toggle("on");
//})




    const menuBtn = document.querySelector('.menu-btn');
    const menuItems = document.querySelector('.items');

    menuBtn.addEventListener('click', () => {
        menuBtn.classList.toggle('on');
        menuItems.classList.toggle('active');
    });



</script>
</body>
</html>