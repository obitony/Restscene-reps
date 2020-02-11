<?php
header("Access-Control-Allow-Origin: *");
error_reporting(1);
ini_set( "display_errors", 1);
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true" 
$hostserver = "localhost";
$pagetitle_1 = "Restscene";

$currentyear = date('Y');
$month = date('m');
$currentday = date('d');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restscene";
$port = 3306; 


//mysqli connection
$connSM = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($connSM->connect_error) {
    die("Connection failed: " . $connSM->connect_error);
}

//pdo connection
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>