<?php
 
 
 class Emailer
 {
  
	 function __construct() 
	 {
		// Constructor code
	 }
 	
	function send($email,$name,$type)
	{
	$to = $email;
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: Kodely <developer@kodely.com>' . "\r\n";
	
		if($type == "win1")
		{
			$subject = "You Won the Main Prize!";	
			
			$email_template = file_get_contents("email/welcome_email.html");
			
			$body = str_replace("{REDEMPTION}",$name, $email_template);
		}
		
		if($type == "win2")
		{
			$subject = "You Won a Prize!";	
			
			$email_template = file_get_contents("email/welcome_email.html");
			
			$body = str_replace("{REDEMPTION}",$name, $email_template);
			
		}
	
	 if (mail($to, $subject, $body,$headers)) 
	 {
		//Succeeded
	   return true;
	  } 
	  else 
	  {
		//Failed
		return false;
	  }
	
	}
	
  }
?>