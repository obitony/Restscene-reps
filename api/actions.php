<?php
require_once("user.php");       
if(isset($_POST['module'])){
  $module   = trim($_POST['module']);
  //use the modele to differentiate between ,Modules modules
   switch($module){
        case 'user':
            new User();
        break;    

        default:
            // Nothing yet
        break;
   }
}



?>