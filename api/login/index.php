<?php require_once('../../Connections/connSM.php'); ?>
<?php
	$data  = array();
	$email =  $_GET['email'];
    $pwd   =  $_GET['pwd'];
    $pwd = md5($pwd);

    //echo $pwd;

    $sqlFind = "SELECT * FROM members WHERE email = '".$email."' AND pwd = '".$pwd."' ";
    $result  = $connSM->query($sqlFind);

if ($result->num_rows > 0) {     
        $data['symbol']  = 1;
        $data['url']     = 'login.html';
        $data['message'] = 'Logged In Successfully!.';       
     		   
	 } else {  	 
        $data['symbol']   = 0;
        $data['url']      = 'signup.html';
        $data['message']  = "No Such User Exists!\nTry creating an account first";
      
    } 
    //returns data as JSON format
    $json = json_encode($data);     
    $functionName = $_GET['callback'];
    echo "$functionName($json);";   
?>
