<?php
      session_start();
      require('connection.php');

      if(isset($_POST['sendmsg']))
      {
            $name=$_POST['yourname'];
            $contactemail=$_POST['contactemail'];
            $subject=$_POST['subject'];
            $massage=$_POST['massage'];
            $date=date('Y-m-d');

            $contactQuery="INSERT INTO `contact`(`Name`, `Email`, `Subject`, `message`, `date`) VALUES (' $name','$contactemail','$subject','$massage','$date')";
            $Queryrun=mysqli_query($connect, $contactQuery);

            $msgsend=1;
      }
        
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./Images/collageLOGO.png">
    <title>Internship Management</title>   
     <!-- Costum Css Link -->
    <link rel="stylesheet" href="./css/Contact.css" type="text/css">
   
    <!-- Font Awesomr CDN link -->
    <script src="https://kit.fontawesome.com/e8e9c02a1b.js" crossorigin="anonymous"></script>
    <link rel="icon" href="../Images/Favicon (2).png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Festive&family=Merriweather:ital,wght@0,300;0,900;1,300;1,900&family=Poppins:wght@400;500;600&family=Roboto+Slab:wght@700&family=Roboto:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    
</head>
<body>

<!-- Navbar Starts -->
    <?php
        include("Navbar.php");
    ?>
<!-- Navbar Ends -->


<!-- Contact Us section Starts -->
<div class="categories_head_container">
    <div class="head_left">
        <img src="./Images/contact_img-new.png" alt="">
    </div>

    <div class="head_details">
        <h1>Contact Us</h1>
        <p>We're here to help!</p>
    </div>

    <div class="head_left">
        <img src="../Images/contact2.png" alt="">
    </div>
</div>

<div class="contact_form_container">

        <div class="contact_details">
            <div class="contact1 "> 
              <i class="fas fa-phone icon1"></i>
                <h2 class="detail-h1">Customer Support</h2>
                <p class="detail_p">+(423) 733-8222 </p>
                <p class="detail_p">+(423) 733-8222 </p>

            </div>

            <div class="contact1">
                <i class="fas fa-envelope icon2"></i>
                <h2 class="detail-h1">Email Us</h2>
                <p class="detail_p">PRMIT&R@gmail.com </p>
                <p class="detail_p">PRMIT&R@gmail.com </p>
            </div>


            <div class="contact1">
                
                <i class="fas fa-map-marked-alt icon3"></i>
                <h2 class="detail-h1">Main Office Address</h2>
                <p class="detail_p">Prof.Ram Meghe Institute Of Technology and Reshearch ,Badnera</p>
            </div>
        </div>
 
        <div class="form-right">

                 <?php
                    if(isset($msgsend))
                    { ?>
                    <div class="success_msg_container">
                        <div class="successmsg">
                            <div class="ani_icon">
                                <lottie-player src="https://assets1.lottiefiles.com/packages/lf20_vzhtcqsd.json"  background="transparent"  speed="1.5"  style="width: 150px; height: 150px;" loop autoplay></lottie-player>
                            </div>
                        
                            <br>
                            <h2>Message Send Sucessfully</h2> 
                            <p>Thank you for Cantacting Us</p>
                            <a href="Contact.php" class="back">Back</a>
                            <a href="Intership_Home.php" class="continue_btn">Continue to Intern</a>  
                        </div>
                    </div>
                    
                <?php } else { ?>   
            <div class="form_head">
                <div class="form_details">
                    <h1>Get <span style="color:#f60;">Intouch</span> </h1>
                    <p>To request a quote or want to meet up for coffee, contact us directly or fill out the form and we will get back to you promptly</p>
                </div>
                         
            <div class="contact_form">            
                <form action="" method="POST">
                    <div class="details">
                        <input type="text" placeholder="Your Name" class="name" name="yourname" required autocomplete="off">
                        <input type="text" placeholder="Your Email" class="name" name="contactemail" required autocomplete="off">
                    </div>
                    <input type="text" placeholder="Subject" class="subject" name="subject" required autocomplete="off">
                    <br>
                    <textarea id="" cols="30" rows="10" class="textarea" placeholder="Write Your Massage" name="massage" required></textarea>

                    <div class="checkbox_feild">
                        <input type="checkbox" class="checkbox" required>
                        <p>I agree to the <b>Terms & Conditions</b> </p>
                    </div>
                   
                    <div class="btn">
                      <button name="sendmsg">Send Your message</button>
                    </div>
                </form>

                <?php } ?>
            </div>
              
            </div>

           
        </div>

    
</div>

<!-- Map Section Starts -->
        <div class="map">

       
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3728.431251561657!2d77.74960037525182!3d20.854661080751395!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bd6a54e170995cb%3A0xfed5bd6314ba3973!2sProf.Ram%20Meghe%20Institute%20of%20Technology%20%26%20Research!5e0!3m2!1sen!2sin!4v1718610019437!5m2!1sen!2sin" style="border:0;" allowfullscreen="" width="90%" height="450px" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

<!-- Map Section Ends -->


<!-- Footer Section Starts -->

<?php
    include("footer.php");
?>

<!-- Footer Section Ends -->
