<?php require_once('../../Connections/connSM.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
        $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

	$data  = array();
	$email =  $_GET['email'];
  $pwd   =  $_GET['pwd'];

  //echo $email;
  
  $sqlFind = "SELECT email FROM members WHERE email =  '".$email."' ";
  $result = $connSM->query($sqlFind);

if ($result->num_rows > 0) {
	$data['symbol'] = 0;
	$data['url'] = 'signup.html';
	$data['message'] = "User with same Email ID already exists!";
	
	//returns data as JSON format
     $json = json_encode($data);     
     $functionName = $_GET['callback'];
     echo "$functionName($json);";
     		   
	   } else {  
		 
		   
	try {		
    
    $pwd = md5($pwd);
    // prepare sql and bind parameters
    $stmt = $conn->prepare("INSERT INTO members (email, pwd, date_created) VALUES (:email, :pwd, NOW())");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':pwd', $pwd);
    
   
    // insert a row
    $stmt->execute();
	
	$subject = 'Restscene Registration';
	$to = $email. ', ';
	
	$msg = "<div align='center'>
		<h3>Restscene Registration</h3>
		<h5>Restscene Account Notice!</h5><br/></div><div>
		Congratulations! Your Restscene account has successfully been created!<br/>
		Click on \"<b><u><a href=\"https://restscene.com\">Continue</a></u></b>\" to login.
		</div>";
				
	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	
	// More headers
	$headers .= 'From: <noreply@trestscene.com>' . "\r\n";
	$headers .= 'Bcc: ejiro4@hotmail.com' . "\r\n";
	
	 // Mail it
	//mail($to, $subject, $msg, $headers);
      
  $data['symbol'] = 1;
	$data['url'] = 'login.html';
	$data['message'] = 'Congratulations! Your Restscene account has been created.';
	
	//returns data as JSON format
     $json = json_encode($data);     
     $functionName = $_GET['callback'];
     echo "$functionName($json);";
    }
catch(PDOException $e)
    {
       
  $data['symbol'] = 0;
	$data['url'] = 'signup.html';
	$data['message'] = "Error: " . $e->getMessage();
	
	//returns data as JSON format
     $json = json_encode($data);     
     $functionName = $_GET['callback'];
     echo "$functionName($json);";
    }
	$conn = null;   
      

	   }


?>
