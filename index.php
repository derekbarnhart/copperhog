<?php
//Look for a source query parameter to track where this scan came from

$source = $_REQUEST['source'];
if(!isset($source))
{
	$source="unknown";
}

//TODO need to fix this to be dynamic

$raffle_id = 1;
$concert_title = 'K. Flay & Wishbone on Sept. 28 at the Wild Buffalo. 9pm';


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>
        </title>
        <link rel="stylesheet" href="my.css" />

        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0-alpha.1/jquery.mobile-1.2.0-alpha.1.min.css" />
        	<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
        	<script src="http://code.jquery.com/mobile/1.2.0-alpha.1/jquery.mobile-1.2.0-alpha.1.min.js"></script>



        <script src="my.js">
        </script>
        <script>      	
        	function sendRequest(email,raffle_id)
        	{	   

				//$.mobile.showPageLoadingMsg("d",'Submitting',false);

	        	$.mobile.loading( 'show', {
                	text: 'Submitting',
                	textVisible: true,
                	theme: 'd',
                	html: ""
                });


	        	//$('#loading').dialog();
	             //	$('#loading').dialog('open');


	        	$.ajax({
	        		url: "http://copperhog.nfshost.com/controller.php?email="+email+"&raffle_id="+raffle_id+"&source=<?php echo $source; ?>&rand="+Math.round(Math.random()*100000000),
	        		dataType: 'json',
	        		success: function(data, textStatus, jqXHR)
	        		{
	        			switch(data.response_code)
	        			{
		        			case 1:// Primary Win
		        			$.mobile.changePage($("#won1"));	
		        			break;
		        			
		        			case 2:// Secondary Win
		        			$.mobile.changePage($("#won2"));
		        			break;
		        			
		        			case -1:// Already Scanned
		        			alert("Woops.. Looks like you already played! Check back next month for another chance to win!");
		        			break;
		        
		        			case 0:// Lose
		        			$.mobile.changePage($("#lost"));
		        			break;
		        			
		        			case 201:
		        			
		        			alert("Yikes! Double check your email. It gave our servers an issue");
		        			
		        			break;
		        			
		        			case 202:
		        			
		        			break;
	        			}
	        			
	        			console.log(data);
	        			
	        		}
	        		
	        	}).done(function()
				{
				$.mobile.hidePageLoadingMsg();
				
				});
	        }
        </script>
    </head>
    <body>
    	<div id="fb-root"></div>
<script>
var signedIn = false;
var enterRequested = false;
var email = "";


  window.fbAsyncInit = function() {
    FB.init({
      appId      : '271291536305750', // App ID
      status     : false, // check login status
      cookie     : true, // enable cookies to allow the server to access the session
      xfbml      : true,  // parse XFBML
      oauth      : true
    });

    FB.Event.subscribe('auth.authResponseChange', 
	function(response)
 		{
 			console.log('Welcome!  Fetching your information.... ');
		     FB.api('/me', function(response) {     	
		     	 console.log(response); 	 
		     	// $('#first_name').attr("value",response.first_name);
		     	// $('#last_name').attr("value",response.last_name);
		     	 $('#email').attr("value",response.email); 
		     });
 			
 		});
          
  };
  


  function handleStatusChange(response) {
     document.body.className = response.authResponse ? 'connected' : 'not_connected';
	
	 
     if (response.authResponse) {
     	
     	if(response.status == "connected")
     	{
     		signedIn == true;
     		
     	}
       console.log(response);
     }
   }

  // Load the SDK Asynchronously
  (function(d){
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
   }(document));


function enterFacebook()
{
	//FB.login(function(response) { }, {scope:'email'});  
	FB.login(function(response) 
		{
			if (response.authResponse) 
			{
				console.log('Welcome!  Fetching your information.... '); 
				FB.api('/me', function(response) {
		     	 console.log(response);
		     	 //$('#first_name').attr("value",response.first_name);
		     	 //$('#last_name').attr("value",response.last_name);
		     	 $('#email').attr("value",response.email); 
				 enterManual();
		     });
			} else 
			{
				console.log('User cancelled login or did not fully authorize.');
			}
		},{scope:'email'});
}

function enterManual()
{
	//Validate Email
	email = testEmail($('#email').val());
	
	if(email == -1)
	{	
		alert("Please enter an email to enter.");
		return ;
	}else
	{
		console.log(email);	
	    sendRequest(email,<?php echo $raffle_id; ?>);
	}	
}


function testEmail(testEmail)
	{
		var validEmail = false;
		
		emailText = testEmail;
		emailText = emailText.trim();
		//Need an @ symbol
		var numAtSigns = emailText.split("@").length-1;
		
		var numSpaces = emailText.split(" ").length-1;
		//Period can't begin the email
		var atLoc = emailText. indexOf('@');
		
		//Period can't begin the email
		var firstPeriodLoc = emailText. indexOf('.');
		
		//Need a period and it cant be at the end
		var lastPeriodLoc = emailText.lastIndexOf('.');
		
		console.log('@: '+atLoc);
		console.log('firstPeriod: '+firstPeriodLoc);
		console.log('@: '+atLoc);
		if(numSpaces==0 && numAtSigns==1 && 
			emailText.length-2>lastPeriodLoc && 
			firstPeriodLoc !=0 && 
			emailText.charAt(atLoc-1)!= '.' && 
			emailText.charAt(atLoc+1)!= '.' &&
			atLoc != emailText.length-1 &&  
			lastPeriodLoc>atLoc)
		{//Valid
			return emailText;
		} else
		{//Not Valid
			return -1;
		}
		
		
		
		}


</script>
        <!-- Home -->
        <div data-role="page" id="landing" data-theme="a">
            <div data-role="content" style="padding: 15px">
                <div style="width: 267px; height: 269px; margin-left:auto; margin-right:auto;">
                    <img src="img/the_hog_small.png" alt="image" />
                </div>
                <br/>
                <h2 style="text-align:center;color:#FFFF85;">
                     <?php echo $concert_title;?>
                </h2>
                   <div style="width: 128px; height: 128px; margin-left:auto; margin-right:auto;">
               	 <img src="img/icon_tickets.png" alt="image" />
                </div>
   
                <div data-role="fieldcontain" style="text-align:center;">
    				<h3 style="text-align:center;">Enter to win with your email</h3>
    				<input type="email" name="email" id="email" value=""  data-theme="d"/>
				 <br/>
				 <input type="submit" onClick="enterManual();" data-icon="check" data-iconpos="left" value="Enter with Email" data-theme="e"/>
                 <br/>
				<input type="submit" onClick="enterFacebook();"data-icon="check" data-iconpos="left" value="Enter with Facebook" data-theme="b"/>
                  </div>

				    <hr/>
                <h5 style="text-align:center; margin:0px;">Powered by <img align="middle" style="margin-left:3px"src="img/kodely_logo_app.png" alt="image" /></h5>
            
            </div>
        </div>
        
          <div data-role="page" id="won1" data-theme="a">
            <div data-role="content" style="padding: 15px">
                
                    <h1 style="text-align:center;">YOU WON!!</h1>
               
               <div style="width: 128px; height: 128px; margin-left:auto; margin-right:auto;">
               	 <img src="img/icon_tickets.png" alt="image" />
                </div>
                
                <h2 style="text-align:center;">
                     2 tickets to super awesome fest
                </h2>
                   
               <br/>
                <h3 style="text-align:center;">
                     Check your email for details on how to claim your prize!
                </h3>
     <hr/>
                <h5 style="text-align:center; margin:0px;">Powered by <img align="middle" style="margin-left:3px"src="img/kodely_logo_app.png" alt="image" /></h5>
            
              
            </div>
        </div>
             <div data-role="page" id="won2" data-theme="a">
            <div data-role="content" style="padding: 15px">
                
                    <h2 style="text-align:center;">YOU WON!!</h2>
                
                <div style="width: 256px; height: 256px; margin-left:auto; margin-right:auto;">
               	 <img src="img/beer-icon.png" alt="image" />
                </div>
                <h2 style="text-align:center;">
                     One <strong>FREE</strong> pint at the Copper Hog!!
                </h2>
                   
               <br/>
                <h3 style="text-align:center;">Check your email for details on how to redeem your beer!</h3>
                <hr/>
                <h5 style="text-align:center; margin:0px;">Powered by <img align="middle" style="margin-left:3px"src="img/kodely_logo_app.png" alt="image" /></h5>
            
            </div>
        </div>
             <div data-role="page" id="lost" data-theme="a">
            <div data-role="content" style="padding: 15px">
                
                    <h2 style="text-align:center;">Just Missed It!</h2>
                       <div style="width: 256px; height: 256px; margin-left:auto; margin-right:auto;">
               	 <img src="img/negative_256.png" alt="image" />
                </div>
               
                <br/>
                <h2 style="text-align:center;">
                Boooo! You didn't win this time but make sure to try again next month!
                </h2>
                   
                <hr/>
                <h5 style="text-align:center; margin:0px;">Powered by <img align="middle" style="margin-left:3px"src="img/kodely_logo_app.png" alt="image" /></h5>
            </div>
        </div>

        <div data-role="page" id="loading" data-theme="a">
                    <div data-role="content" style="padding: 15px">
                            <h1 style="text-align:center;">Submitting...</h1>
                    </div>
        </div>
      <script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-34026440-1']);
		  _gaq.push(['_trackPageview']);

		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>
		
    </body>
</html>