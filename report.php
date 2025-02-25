<?php
require('connection.php');

// Fetch all students from student_details table
$sql = "SELECT Student_Id, FirstName, MiddleName, LastName FROM student_details";
$result = $connect->query($sql);
$students = $result->fetch_all(MYSQLI_ASSOC);

$connect->close();
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    
    <title>Student Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; display: flex; justify-content: center; background-color: #f4f4f4; }
        .Report { background: #fff; width: 66%; padding: 20px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); margin-top: 20px; margin-left:15rem; }
        .upper { display: flex; align-items: center; padding-bottom: 20px; }
        .img img { width: 100px; height: 100px; margin-right: 20px; }
        .clgName { text-align: center; flex: 1; }
        .clgName h3 { margin: 5px 0; font-size: 20px; }
        .clgName p { margin: 3px 0; font-size: 14px; }
        .box { margin-left: 5rem; margin-right: 5rem; }
        .greet { text-align: left; margin-top: 20px; font-size: 14px; }
        .main_content h4 { text-align: center; text-decoration: underline; margin-top: 20px; }
        .main_content h5 { text-align: left; font-size: 1rem; margin-top: 15rem; }
        .main_content h5 span { margin-left: 5rem; }
        .main_content p { text-align: justify; line-height: 1.6; margin: 10px 0; }
        .lowest { font-size: 12px; text-align: justify; margin-top: 20px; font-style: italic; }
        hr { margin-top: 20px; }

            .selection{
               
                display: flex;
                width: 90%;
              
                justify-content: space-between;
                margin-left: 3rem;
                
                }
        form{
            background-color: var(--background);
            width: 50%;
            display: flex;
            justify-content: space-between;
            padding: 1rem;
            box-shadow: 0 0 1.5px 0.5px #000;
        }
        .selection form #student{
            width: 15rem;
            height: 2rem;
            font-size: 1rem;
            margin-top: 0;
        }
        .selection  i{
            font-size: 2.5rem;
            /* color: darkslategrey; */
        }
    </style>
        <script src="https://kit.fontawesome.com/e8e9c02a1b.js" crossorigin="anonymous"></script>

</head>
<body>
    <?php
        include('Admin.php'); 
    ?> 
  <section class="Admin_data">
    <div class="selection">

        <form method='GET'>
            <label for='student'>Select Student:</label>
            <select name='student_id' id='student'>
                <?php foreach ($students as $student): ?>
                    <option value='<?php echo $student['Student_Id']; ?>'>
                        <?php echo $student['FirstName'] . ' ' . $student['MiddleName'] . ' ' . $student['LastName']; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <button type='submit'>Generate</button>
            </form>
            <!-- <button onclick='downloadPDF()'> <i class="fa-sharp fa-regular fa-download"></i></button> -->
            <i class="fa-solid fa-download" onclick='downloadPDF()'></i>
        </div>
    <?php
    if (isset($_GET['student_id'])) {
        require('connection.php');

        $student_id = $_GET['student_id'];
        $sql = "SELECT FirstName, MiddleName, LastName, `Acad-Year` FROM student_details WHERE Student_Id = $student_id";
        $result = $connect->query($sql);
        $student = $result->fetch_assoc();

        $fullName = $student['FirstName'] . ' ' . $student['MiddleName'] . ' ' . $student['LastName'];
        $academicYear = ($student['Acad-Year'] - 1) . ' - ' . $student['Acad-Year'];

        $connect->close();
    ?>

    <div class='Report' id='report'>
        <div class='upper'>
            <div class='img'><img src='./Images/collageLOGO.png'></div>
            <div class='clgName'>
                <p>VIDHARBHA YOUTH WELFARE SOCIETY'S</p>
                <h3>PROF. RAM MEGHE INSTITIUTE OF TECHNOLOGY & RESEARCH</h3>
                <p><b>[An Autonomous Institute]</b></p>
                <p>`A+` Grade Institute Accredited by National Board of Accreditation.<br>Recognized by: All India Council for Technical Education, New Delhi.<br>Affiliated to Sant Gadge Baba Amravati University, Amravati.</p>
            </div>
        </div>
        <div class='box'>
            <div class='greet'>
                <p>Ref:PRMIT&RMCA/Intership/<?php echo $academicYear; ?>/32<br>Date: <?php echo date('d F, Y'); ?></p>
            </div>
            <div class='main_content'>
                <h4>TO WHOMSOEVER IT MAY CONCERN</h4>
                <p>This is to certify that Mr. / Ms. <b><?php echo $fullName; ?></b> is a bonafide student of P.G. Department of Computer Applications of this institute for the academic year <?php echo $academicYear; ?>. He/She is studying in the Final Year of Master in Computer Applications (MCA).</p>
                <p>Prof.Ram Meghe Institute of Technology & Research, established in 1983 approved by AICTE, New Delhi and affiliated to the S.G.B. Amravati University, is a Professional Education Institute offering MCA Course. MCA course was started in 2009, with zeal to server good quality education.   </p>
                <p>As a part of the MCA Course, the Fourth Semester students are required to work full time on an <b>Industry Project and Internship</b> for a semester. We would be grateful if you could accommodate students of MCA final year in your esteemed organization, for in-house exposure and give them a chance to prove themselves.</p>
                <p>The curriculum of MCA program at our institute encompasses the latest technologies & our students have been trained to be productive and contribute in industry environment.</p>
                <p>We are sure that you shall help these students in their academic pursuits. It will be our pleasure and honor if you could associate with us in furthering our academic endeavors.</p>
                <p>Your consent to that effect for <b>'Industry Project and Internship'</b> in your organization will be highly appreciated.</p>
                <p>With Warm Regards,</p>
                <h5><span>Head</span><br>P.G. Dept. of Computer Applications</h5>
            </div>
            <div class='lowest'>
                Note: Internal Marks for the Fourth Semester Industrial Project should be submitted via email & post by the First week of June 2025.(in a format that will be provided) to ajprimprikar@mitra.ac.in, hod_mca@mita.ac.in. The format for submission of marks will be mailed in the second week of May-2025.
            </div>
        </div>
        <hr>
    </div>
   
  </section>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js'></script>
    <script>
        async function downloadPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const element = document.getElementById('report');
            const canvas = await html2canvas(element);
            const img = canvas.toDataURL('image/png');
            doc.addImage(img, 'PNG', 10, 10, 190, 240);
            doc.save('Student_Report.pdf');
        }
    </script>

    <?php } ?>
</body>
</html>
