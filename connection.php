<?php
// $dbHost='localhost';
// $dbName='internship';
// $dbUserName='root';
// $dbPassword='';

// $connect=mysqli_connect($dbHost,$dbUserName,$dbPassword,$dbName);


// if(mysqli_connect_error())
// {
//     echo"<script> Connection Error ;</script>";
//     exit();
// }
// // else{
// //     echo "Connection succesful";
// // }
?>
<?php
$dbHost='localhost';
$dbName='intern';
$dbUserName='root';
$dbPassword='';

$connect=mysqli_connect($dbHost,$dbUserName,$dbPassword,$dbName);


if(mysqli_connect_error())
{
    echo"<script> Connection Error ;</script>";
    exit();
}
?>