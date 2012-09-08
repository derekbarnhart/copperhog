<?php

require_once ('db.php');
require_once ('email.php');

define("ERROR_EMAIL", 201);
define("ERROR_RAFFLE", 202);

define("SCANNED", -1);
define("LOSE", 0);
define("WIN_1", 1);
define("WIN_2", 2);

$primary_win_interval = 50;
$secondary_win_interval = 25;

$secondary_win_code = "fat_fingers_55";

$raffle_closed = false;

//Framwork for the JSON response
$response["response_code"] = "1";
//The following will contain the data that the page needs to render
//$response["data"] = ;

$emailer = new Emailer();

$db = new DB();

// Collect Email 
$email = $_REQUEST['email'];

if(!isset($email))
{// Ensure that something is placed into the $ip variable	
	
	$response["response_code"] = ERROR_EMAIL;
	$response["response_msg"]="No email present";
	sendResponse($response);
	die();
}

// Collect the Raffle ID
$raffle_id = $_REQUEST['raffle_id'];
if(!isset($raffle_id))
{// Ensure that something is placed into the $ip variable	
	
	$response["response_code"] = ERROR_RAFFLE;
	$response["response_msg"]="No raffle id present";
	sendResponse($response);
	die();
}

//Collect Request IP Address
$ip = $_SERVER['REMOTE_ADDR'];
if(!isset($ip))
{// Ensure that something is placed into the $ip variable
	
	$ip= "0.0.0.0";
}


$source = $_REQUEST['source'];
if(!isset($source))
{// Ensure that something is placed into the $source variable	
	$source = 'unknown';
}

//Get information about this raffle
$raffle_result =  $db->getRaffle(1)->fetchAll();

if(!$raffle_result)
{
	$response["response_code"] = ERROR_RAFFLE;
	$response["response_msg"]="Could not find raffle in database";
	sendResponse($response);
	die();
}
$raffle_info = $raffle_result[0];

//Check to see if the raffle is still going

if(!$raffle_info['state'] == '1')
{
	//Raffle is off
	$raffle_closed = true;
}

//Check to see if the current date is passed the last date for this raffle
$end_date = DateTime::createFromFormat('Y-m-d',$raffle_info['end_date']);
date_default_timezone_set('UTC');
$current_date = new DateTime('now', new DateTimeZone('UTC'));
$days = intval($end_date->format('Ymd'))-intval($current_date->format('Ymd'));
 
if($days<=0)
{

	$raffle_closed = true;
}


//Check to see if this individual has scanned before
$result = $db->scanExist($email,$raffle_id);

if($result==true)
{
	//This person has already scanned this code
	$response["response_code"] = SCANNED;
	$response["response_msg"]="Already Scanned";
	sendResponse($response);
	die();	
}
else
{
	//This is a new scan for this code/person 		
	$db->createScan($email,$raffle_id,$ip,$source);
	//echo "New scan created". $result;
	$raffle_scans = $db->countScans($raffle_id);
	//echo "Total Raffle Scans:".$raffle_scans;
		
	//Check to see if they won the primary prize
	if(($raffle_scans % $primary_win_interval) == 0)
	{
		//Potential winner since it is on the win interval
		//echo "Potential Winner";
		
		//Lets make sure we have prizes left to give to them
		//Get a list of remaining prizes
		$prizes = $db->remainingPrizes($raffle_id);
		
		if(isset($prizes))
		{
			$prizeCount = $prizes->rowCount();
			//echo "Prizes remaining ".$prizeCount;
		
			if($prizeCount>0 && !$raffle_closed)
			{// There are still prizes to give away	
				$prize_list = $prizes->fetchAll();
				$prize_id = $prize_list[0]['id']; // Get the next prize in the list to be assigned to the winner
				
				//echo "Assign this winner to prize_id: ". $prize_id;
				$db->setWinner($prize_id,$email);
				
				//Send an email to the winner		
				$emailer->send($email,$prize_list[0]['redemption_code'],"win1");
				$response["response_code"] = WIN_1;
				$response["response_msg"]="Primary win";
				sendResponse($response);
				die();
					
			}else
				{	
					//Make them a secondary winner
					$emailer->send($email,$secondary_win_code,"win2");
					$response["response_code"] = WIN_2;
					$response["response_msg"]="Secondary win";
					sendResponse($response);
					die();		
					
				}
		
		}else
			{	//We had some error and we couldnt find any prizes
				//Make them lose to be safe
					$response["response_code"] = LOSE;
					$response["response_msg"]=" Lose";
					sendResponse($response);
					die();
			}
	}else
		{//They did not win the primary prize check to see if they won a secondary prize
			
			if(($raffle_scans % $secondary_win_interval) == 0)
			{
				//Make them a secondary winner
					$emailer->send($email,$secondary_win_code,"win2");
					$response["response_code"] = WIN_2;
					$response["response_msg"]="Secondary win";
					sendResponse($response);
					die();		
			}else
				{
					//They just didnt win this time
					$response["response_code"] = LOSE;
					$response["response_msg"]=" Lose";
					sendResponse($response);
					die();		
				}				
		}
}


//check to see if the user has checked in before


// Utility functions

//Create record

//Count records


//Get unclaimed prizes

function processSecondary()
{
	
	
}


function sendResponse($response)
{
	header('Content-type: application/json');
	ob_start('ob_gzhandler');
	echo json_encode($response);
}

?>
