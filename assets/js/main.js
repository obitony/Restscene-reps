
// Register A user script
function regMem(){
  var email = document.getElementById('email').value;
  var pwd   = document.getElementById('pwd').value;
  
    $(".result").html('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i> ');
    //let formData = $(this).serialize();
    //console.log(formData);
    $.ajax({
      url:"http://localhost/restscene/api/signup",
      type: "GET",
      dataType: 'jsonp',
      timeout: 50000,
      data: {email:email,pwd:pwd},
      //data: formData,
      success:function(data){         
            $(".result").html('<div class ="alert alert-success">'+data.message+'</div>'); 
            var url2 = data.url;		
            var delay = 5000; 
            setTimeout(function(){ location.href= url2; }, delay);   
          },
          error: function(request, status, err) {
           if (status == "timeout") {                
            $(".result").html('<div class ="alert alert-danger">Your connection is slow!</div>');                  
               } else {
               // another error occured 
          $(".result").html('<div class ="alert alert-danger">Error connecting Server!</div>');                  
                       }
          }
    });
};



// Login Script
function login(){
  var email = document.getElementById('email').value;
  var pwd   = document.getElementById('pwd').value;
  $(".result").html('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i> ');
    $.ajax({
      url:"http://localhost/restscene/api/login",
      type: "GET",
      dataType: 'jsonp',
      timeout: 50000,
      data: {email:email,pwd:pwd},
      success:function(data){  
            console.log(data.message);       
            $(".result").html('<div class ="alert alert-success">'+data.message+'</div>'); 
            var url2 = data.url;		
            var delay = 5000; 
            setTimeout(function(){ location.href= url2; }, delay);   
          },
          error: function(request, status, err) {
           if (status == "timeout") {                
            $(".result").html('<div class ="alert alert-danger">Your connection is slow!</div>');                  
               } else {
               // another error occured 
          $(".result").html('<div class ="alert alert-danger">Error connecting Server!</div>');                  
            }
          }
    });
};


$(document).ready(function(){
 // Forgot Password Script
 $("#recoverPasswordForm").submit(function(evt){
  evt.preventDefault();
  $(".result").html('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i> ');
  let formData = $(this).serialize();
  $.ajax({
    url:"http://localhost/restscene_webapp/controller/actions.php",
    method:"post",
    data: formData,
    success: (res,status)=>{
      if(res==1){
          $(".result").html('<div class ="alert alert-info"> <i class ="fa fa-info"></i> &nbsp;We have sent a new password to your email. Please Ensure to check your spam folder as well</div>');
      }else{
        $(".result").html('<div class ="alert alert-danger">'+res+'</div>');
      }
    }
  });
});


});