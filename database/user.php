<?php
require_once("connection.php");

class User extends Db{
    private $fname ="";
    private $lname ="";
    private $email ="";
    private $pass = "";

   public function __construct()
   {
    $con      = self::connect();
    @$action  = trim($_POST['action']);
    session_start();
    switch($action){
        case 'register':
            self::register($con);
        break;

        case 'login':
           self::login($con);
        break;

        case 'recover_password':
            self::recoverPassword($con);
        break;

        case 'ask_question':
            self::askQuestion($con,$_SESSION['AMA_USER_ID']);
        break;

        case 'show_questions':
            self::showQuestions($con);
        break;

        case 'delete_questions':
            self::deleteMyQuestion($con,$_SESSION['AMA_USER_ID'],$_POST['id']);
        break;

        case 'reply_question':
            self::replyQuestion($con,$_SESSION['AMA_USER_ID'],$_POST['id']);
        break;

        case 'list_of_replies':
            self::listOfRepliesToAQuestion($con,$_POST['id']);
        break;

        case 'list_my_questions':
            self::listMyQuestions($con,$_SESSION['AMA_USER_ID']);
        break;
 
        default:
           //echo "No Action was called" listMyQuestions
        break;
    }

   }
   public function connect(){
    return new Mysqli("localhost","root","","reps");
   }


 private function getQuestion(){
    return $this->question;
 }

 private function setQuestion($question,$con){
    $question = $con->real_escape_string(trim($_POST[$question]));
    $this->question =$question;
 }

   private function getfname(){
       return $this->fname;
   }

   private function getlname(){
    return $this->lname;
   }
   private function getEmail(){
       return $this->email;
   }
   private function getPassword(){
       return $this->pass;
   }

   private function setfname($fname,$con){
        $fname = $con->real_escape_string(trim($_POST[$fname]));
        $this->fname =$fname;
   }

   private function setlname($lname,$con){
    $lname = $con->real_escape_string(trim($_POST[$lname]));
    $this->lname =$lname;
}

   private function setEmail($email,$con){
    $email = $con->real_escape_string(trim($_POST[$email]));
    $this->email =$email;
   }

   private function setPassword($pass,$con){
    $pass = $con->real_escape_string(trim($_POST[$pass]));
    $this->pass = $pass;
   }


   private function setRegFields($fname,$lname,$email,$pass,$con){
        $this->setfname($fname,$con);
        $this->setlname($lname,$con);
        $this->setEmail($email,$con);
        $this->setPassword($pass,$con);
   }

   private function register($con){
       // set he fields and get them ready with values
       $this->setRegFields("fname","sname","email","password",$con);
       //$fname  = $this->getfname();
       //$lname  = $this->getlname();
       $pass   = $this->getPassword();
       $email  = $this->getEmail();
       $values = array($pass,$email);
    // Check to ensure no field is empty
    if(!in_array('',$values)){ 
        if(self::checkUserExist($con,$email,"users","email")==false){	
            $pass_hash = password_hash($pass,PASSWORD_DEFAULT);
            $status    = "unverified";      // default status is unverified from database
            $date      = date_create("now")->format("d-m-Y H:i:s");
            $sql       = "INSERT INTO `members`(
                                                `username`,
                                                `fname`,
                                                `sname`,
                                                `email`,
                                                `phone`,
                                                `pwd`,
                                                `country`,
                                                `status1_email`,
                                                `status2_phone`,
                                                `date_created`) 
                                                 VALUES(?,?,?,?,?,?,?,?,?,?,?)";
            $statement = $con->prepare($sql);
            $statement->bind_param("sssssssssss","",$email,"","","","",$pass_hash,"","","",$date);
            if($statement->execute()){
                echo true;
            }else{
                echo "Error Registering ".$statement->error;
            }
            $statement->close();
      }else{
          echo 'User Already Exist You can try logging in here   <a href ="./login.php" class ="btn agile_btn">Login</a>';
      }

    }else{
        echo 'All fields are required Please';
    }
    $con->close();
   }



   private function login($con){ 
        $this->setEmail("email",$con);
        $this->setPassword("password",$con);

        // Get the login info
        $pass  = $this->getPassword();
        $email = $this->getEmail();


        if(self::checkUserExist($con,$email,"users","email")){	
        $sql  		= "SELECT * FROM `users` WHERE `email`=?";
        $stmt 		= $con->prepare($sql);
        $stmt->bind_param("s",$email);  

       if($stmt->execute()){
                $result	= $stmt->get_result();
                $row       = $result->fetch_assoc();
            if($row['status']=='verified'){
                if(password_verify($pass,$row['paswd'])){
                    $user 	      = $_SESSION["AMA_USER_LIVE"]	= $row['fname'];
                    $uid          = $_SESSION["AMA_USER_ID"]    = $row['uid'];
                    if(isset($user) && isset($uid)){
                        echo true;
                    }else{
                        echo "Sorry No Session Was set at this time";
                    }
                }else{
                echo "LOGIN FAILED  ".$stmt->error;
            }
            }else{
                echo 'Your Email Addresss have not been verified. Please check your email to verify before login';
            }
       }
    }else{
        echo "The User with this emil does not exist";
    }
   }


    private function updateMe(){ }

    private function updatePassword(){}


    private function askQuestion($con,$uid){
        $this->setQuestion("discussion_start",$con);
        $question  = $this->getQuestion();
        // `u_id`,`question`,`date_asked`,`reply_msg`,`date_of_reply`,`reply_id`
        $date_asked      = date_create("now")->format("d-m-Y H:i:s");
        $sql             = "INSERT INTO `questions`(`u_id`,`question`,`date_asked`) VALUES(?,?,?)";
        $statement = $con->prepare($sql); 
        $statement->bind_param("iss",$uid,$question,$date_asked);
        if($statement->execute()){
            echo true;
        }else{
            echo  "Error Posting question".$statement->error;
        }


    }


// List out 20 the questions asked
private function showQuestions($con){
    $sql = "SELECT * FROM `questions` ORDER BY `id` DESC LIMIT 20";
    $query = $con->query($sql);
    if($query){  //
        while($res =$query->fetch_assoc()){
             echo '<div class="card-body p-0">
                    <div class="blog-comments__item d-flex p-3">
                    <div class="blog-comments__avatar mr-3"><img src="images/avatars/1.jpg" alt="User avatar" /> </div>
                    <div class="blog-comments__content">
                    <div class="blog-comments__meta text-muted">
                    <a class="text-secondary" href="#">'.self::getUserWhoAskedQuestion($con,$res['u_id']).'</a>
                    <span class="text-muted">– '.self::timeago($res["date_asked"]).'</span>
                    </div>
                    <p class="m-0 my-1 mb-2 text-muted">'.$res['question'].'</p>
                    <div class="blog-comments__actions">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-white question_id_view"   id ="'.$res['id'].'"><span class="text-default"> <i class="fa fa-eye"></i></span></button>
                        <button type="button" class="btn btn-white question_id_like"   id ="'.$res['id'].'"><span class="text-success"> <i class="material-icons">check</i></span>Like</button>
                        <button type="button" class="btn btn-white question_id_delete" id ="'.$res['id'].'"><span class="text-danger"> <i class="fa fa-trash"></i></span></button>
                        <button type="button" class="btn btn-white question_id_reply"  id ="'.$res['id'].'"><span class="text-light"> <i class="material-icons"></i></span>Reply &nbsp; <span class ="badge badge-danger">'.self::totalReplyPerQuestion($con,$res['id']).'</span> </button>
                    </div>
                    </div>
                </div>
                </div>
                <!-- Reply Message -->
                <div class="row reply_row" id ="reply_row'.$res['id'].'" style ="display:none;">
                <div class="col-lg col-md-6 col-sm-6 view-report mx-4 my-1">
                   <form  method ="post" id="question_replyForm'.$res['id'].'">
                        <input     name ="action" type ="hidden"  value ="reply_question"/>
                        <textarea  name ="discussion_reply'.$res['id'].'"   class ="form-control mb-1" id ="discussion_reply'.$res['id'].'" placeholder ="Your reply"></textarea>
                        <button    type ="button" class="btn btn-danger discussion_reply_sbBtn" id ="'.$res['id'].'">Submit</button>
                   </form>
                </div>
              </div>
              <!-- Reply Message /-->
            </div>';
        }     

    }else{
        echo 'No Questions Yet';
    }
}

// This function gets the list of replie to a particluar question
private function listOfRepliesToAQuestion($con,$q_id){
    $sql = "SELECT * FROM `reply` WHERE `q_id`='$q_id' ORDER BY `id` DESC LIMIT 20";
    $query = $con->query($sql);
    if($query){  //
        while($res =$query->fetch_assoc()){
             echo '<div class="card-body p-0">
                    <div class="blog-comments__item d-flex p-3">
                    <div class="blog-comments__avatar mr-3"><img src="images/avatars/4.png" alt="User Replyavatar" /> </div>
                    <div class="blog-comments__content">
                    <div class="blog-comments__meta text-muted">
                    <a class="text-secondary" href="#">'.self::getUserWhoAskedQuestion($con,$res['reply_id']).'</a>
                    <span class="text-muted">– '.self::timeago($res["date_of_reply"]).'</span>
                    </div>
                    <p class="m-0 my-1 mb-2 text-muted">'.$res['reply_msg'].'</p>
                    <div class="blog-comments__actions">
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-white question_id_view"   id ="'.$res['id'].'"><span class="text-default"> <i class="fa fa-eye"></i></span></button>
                        <button type="button" class="btn btn-white question_id_like"   id ="'.$res['id'].'"><span class="text-success"> <i class="material-icons">check</i></span>Like</button>
                        <button type="button" class="btn btn-white question_id_delete" id ="'.$res['id'].'"><span class="text-danger"> <i class="fa fa-trash"></i></span></button>
                        <button type="button" class="btn btn-white question_id_reply"  id ="'.$res['id'].'"><span class="text-light"> <i class="material-icons"></i></span>Reply &nbsp; <span class ="badge badge-danger">'.self::totalReplyPerQuestion($con,$res['id']).'</span> </button>
                    </div>
                    </div>
                </div>
                </div>
                <!-- Reply Message -->
                <div class="row reply_row" id ="reply_row'.$res['id'].'" style ="display:none;">
                <div class="col-lg col-md-6 col-sm-6 view-report mx-4 my-1">
                   <form  method ="post" id="question_replyForm'.$res['id'].'">
                        <input     name ="action" type ="hidden"  value ="reply_question"/>
                        <textarea  name ="discussion_reply'.$res['id'].'"   class ="form-control mb-1" id ="discussion_reply'.$res['id'].'" placeholder ="Your reply"></textarea>
                        <button    type ="button" class="btn btn-danger discussion_reply_sbBtn" id ="'.$res['id'].'">Submit</button>
                   </form>
                </div>
              </div>
              <!-- Reply Message /-->
            </div>';
        }     

    }else{
        echo 'No Reply Yet for this question';
    }
}

// Delete my question
public function deleteMyQuestion($con,$uid,$q_id){
	$sql   = "DELETE FROM `questions` WHERE `id`='$q_id' AND `u_id` ='$uid'";
	$query = $con->query($sql);
	if($query){
        if($con->affected_rows <1){
            echo '<div class ="alert alert-danger">You Do not have permission to delete this Question </div>';
        }else{
            echo true;
        }
	}
	$con->close();
}


private function replyQuestion($con,$reply_id,$q_id){
        $reply_msg         = trim($_POST['reply']);
        $date_replied      = date_create("now")->format("d-m-Y H:i:s");
        $sql               = "INSERT INTO `reply`(`q_id`, `reply_id`, `reply_msg`, `date_of_reply`) VALUES(?,?,?,?)";
        $statement         = $con->prepare($sql);  
        $statement->bind_param("iiss",$q_id,$reply_id,$reply_msg,$date_replied);
        if($statement->execute()){
            echo true;
        }else{
            echo "Error Replying to Question ". $statement->error;
        }
       // $statement->close();
        $con->close();
}


private function totalReplyPerQuestion($con,$q_id){
      $sql  = "SELECT COUNT(*) AS totalCount FROM `reply`  WHERE `q_id` ='$q_id'";
      $query = $con->query($sql);
      if($query){
            $row = $query->fetch_assoc();
            return number_format($row['totalCount'],0,"",",");
      }
}


// Calculate time difference
 private function timeago($date) {
	   $timestamp = strtotime($date);	
	   
	   $strTime = array("second", "minute", "hour", "day", "month", "year");
	   $length = array("60","60","24","30","12","10");

	   $currentTime = time();
	   if($currentTime >= $timestamp) {
			$diff     = time()- $timestamp;
			for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
			$diff = $diff / $length[$i];
			}

			$diff = round($diff);
			return $diff . " " . $strTime[$i] . "(s) ago ";
	   }
	}

// Get and return the lname from Users Table given the UserID
    private function getUserWhoAskedQuestion($con,$user_id){
        $sql = "SELECT * FROM `users` WHERE `uid` =?";
        $statement = $con->prepare($sql);
        $statement->bind_param("i",$user_id);  
        if($statement->execute()){
            $result = $statement->get_result();
            $row    = $result->fetch_assoc();
            $name   = $row['fname'];
            return $name;
        }

    }

// Get and return the list of my questions
private function listMyQuestions($con,$user_id){
    $sql = "SELECT * FROM `questions` WHERE `u_id`=?";
    $statement = $con->prepare($sql);
    $statement->bind_param("i",$user_id);  
    if($statement->execute()){
        $result = $statement->get_result();
        while($row    = $result->fetch_assoc()){
                echo '<li class="list-group-item d-flex px-3">
                        <span class="text-semibold text-fiord-blue">'.$row['question'].'</span>
                        <span class="ml-auto text-right text-semibold text-reagent-gray">'.self::getMyQuestionsTotalReplies($con,$row['id']).'</span>
                    </li>';
        }
    }

}
// Get the Total Number of Replies to my Questions
private function getMyQuestionsTotalReplies($con,$q_id){
    $sql  = "SELECT COUNT(*) AS totalCount FROM `reply`  WHERE `q_id` ='$q_id'";
    $query = $con->query($sql);
    if($query){
          $row = $query->fetch_assoc();
          return number_format($row['totalCount'],0,"",",");
    }
}

        
// This is the function that attempts to send mail/ please use this PHPMailer 5
public function sendMail($to,$from,$from_name,$subject,$msg){
    // this function should return true or false
    $mail    = smtpmailer($to, $from,$from_name, $subject,$msg);
    if($mail==true){
        return true;
    }else{
        return false;
    }

}

   public function recoverPassword($con){
	     if(isset($_POST)){		
            $this->setEmail("email",$con);
            $email = $this->getEmail();	
				if(self::checkUserExist($con,$email,"users","email")){			  
					$new_password  	    = substr(str_shuffle(rand(time(),time()+7)."Aab12345CcDdeFoNdaTi@zZKkL"),1,8);
					$hash_pass 			= password_hash($new_password,PASSWORD_BCRYPT); // hash the password
					$updateQuery 		= "UPDATE `users` SET `paswd`=? WHERE `email`=?";
					$stmt_update 		= $con->prepare($updateQuery);
					$stmt_update->bind_param("ss",$hash_pass,$email);
					if($stmt_update->execute()){
                        $msg 	    ='<h2 style ="background-color:#a41034; color:#fff;font-weight:600;">PASSWORD RECOVERY</h2>
                                      <p> A request to reset your password has been initiated </p>
                                      <h3>Your New Passowrd is :</h3>
                                      <p><h2>'.$new_password.'</h2></p>
                                      <p>NB: If you did not initiate this action, kindly  click here <a href ="#" >Block</a></p>';
						$headers 	="";
						if(@self::sendMail($email,"AGILE MASTERS PASWORD RECOVERY","",$msg,$headers)==true){
								echo "We have sent you a new password to your email. Use it to login.";
						}else{
							echo 'Error Snding Mail Please Try again Ensuring you have a network connection '.$new_password;
						}
					$stmt_update->close();
					}


				}else{
					echo "This admin does not exist. Please use the correct Email address!!!";
				}
			$con->close();
	
	}
}

    // this function checks if a user already exist  based on email and phone number
public function checkUserExist($con,$email,$table,$email_field){
        if(!empty($email)){
        $sql   ="SELECT * FROM `".$table."` WHERE `".$email_field."`=?";
        $stmt  = $con->prepare($sql);
        $stmt->bind_param("s",$email);
        $exec  =$stmt->execute();
        if($exec){
            $result   = $stmt->get_result();
            $num_rows = $result->num_rows;
            if($num_rows>0){
                return true;
            }else{
                return false;
            }
            $stmt->close();
            }
        }
    }


// Logout From all sessions set
  
 public function logOut(...$sessionId){
    if($sessionId){
        unset($sessionId);
        if(session_destroy()){
            echo true;
        }
        
    }
  }



 }
//sleep(3);
 new User();