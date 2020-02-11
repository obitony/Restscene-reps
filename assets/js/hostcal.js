  $(document).ready(()=>{
	  let percentageOff  = 0.0125;
	  $(".amount").keyup(()=>{
		  amount = $(".amount").val();
		  let hostprofit = $("#hostprofit");
		  let relAmount = parseInt(amount);
		  if(relAmount>0){
			  if(relAmount<=600 && relAmount>=150){
				   let calc_profit  = 0.15*relAmount;
					hostprofit.removeClass("alert-danger");
					hostprofit.addClass("alert alert-success mt-4");
					hostprofit.html("Your expected profit is: USD "+calc_profit).slideDown(1000);
			   }else{
					hostprofit.addClass("alert alert-danger mt-4");
					hostprofit.html("Amount allowed is between USD 150 - USD 600").slideDown(1000);
			   }
			  }else{
				  hostprofit.slideUp(1000);
			  }
	  });
	  
  });