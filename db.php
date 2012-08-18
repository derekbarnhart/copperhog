<?php

class DB
{

	private $db;

	function __construct()
	{
		 $this->db = new PDO("mysql:host=kodely.db;dbname=copperhog","derekbarnhar","db618273");	
	}
		
	function createScan($email,$raffleId,$ip,$source)
	{
		$query = "INSERT INTO scan SET email = :email, raffle_id = :raffleId, ip = :ip, source = :source";
		$stmt = $this->db->prepare($query);
		 return $stmt->execute(array(':email'=> $email,':raffleId' => $raffleId, ':ip'=> $ip,':source' => $source));
	}
	
	function getRaffle($id)
	{
			$query = "SELECT * FROM `raffle` WHERE `id` = ".$id;	
			$result = $this->db->query($query);
			return $result;
	}
	
	
	function scanExist($email,$raffleId)
	{
		$query = "SELECT * FROM `scan` WHERE `email` = '".$email."' AND `raffle_id` = '".$raffleId."'";	
			
			$result = $this->db->query($query);
			
			$count = $result->rowCount();
		
			
			if($count==0)
			{
				return false;
			}else
			{
				return true;
			}		
	}
	
	function countScans($raffleId)
	{
			$query = "SELECT * FROM `scan` WHERE `raffle_id` = '".$raffleId."'";	
			$result = $this->db->query($query);		
			$count = $result->rowCount();
			return $count;
	}
	
	function remainingPrizes($raffleId)
	{		
		$query = "SELECT * FROM `prizes` WHERE `raffle_id` = '".$raffleId."' AND `email` = 'open'" ;	
			$result = $this->db->query($query);		
			return $result;
	}
	
	function setWinner($prize_id,$email)
	{
		$query = "UPDATE `prizes` SET `email` = :email WHERE `id` = ".$prize_id;
		$stmt = $this->db->prepare($query);
		return $stmt->execute(array(':email'=> $email));
	}
		
	
		
}



?>