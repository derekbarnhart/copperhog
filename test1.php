<?php

require 'db.php';
$db = new DB();


$raffle_info = $db->getRaffle(1)->fetchAll();
var_dump($raffle_info[0]);

echo $raffle_info[0]['state'];
if($raffle_info[0]['state'] == "1")
{
	echo 'Raffle is on';

}else
{
	echo 'Raffle is off';
}

echo "<br/><br/><br/>";

//echo var_dump(explode("-",$raffle_info[0]['end_date']));

$end_date = DateTime::createFromFormat('Y-m-d',$raffle_info[0]['end_date']);
date_default_timezone_set('UTC');
$current_date = new DateTime('now', new DateTimeZone('UTC'));
$days = intval($end_date->format('Ymd'))-intval($current_date->format('Ymd'));
 
 echo $days;
 //$date_diff = $end_date->diff($current_date,false );
// echo var_dump($date_diff);
//echo $date_diff->d;
 
?>