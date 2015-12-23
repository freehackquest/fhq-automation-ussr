<?php
	
	if(!isset($_GET['username'])){
		die("Missing requared parameter username");
	}
	
	$username = $_GET['username'];
	
	if(strlen($username) < 4 || strlen($username) > 10){
		die("Username must be more than 3 simbols and less than 11 simbols");
	}

	if (!preg_match("/^[A-Za-z0-9\-_]*$/", $username))
		die("Username must be has only English characters and numbers ");

	include_once('config.php');

    $stmt = $conn->prepare('SELECT COUNT(*) as cnt FROM users WHERE name = ?');
    $stmt->execute(array($username));
    $count = 0;
    if ($data = $stmt->fetch())
		$count = $data['cnt'];

    if ($count != 0)
		die("This username already registred");


	function generateRandomString($length = 6) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	$token = generateRandomString(10);
	$stmt = $conn->prepare('SELECT COUNT(*) as cnt FROM users WHERE token = ?');
    $stmt->execute(array($token));
    
	$count = 0;
    if ($data = $stmt->fetch())
		$count = $data['cnt'];

    if ($count != 0)
		die("Sorry something wrong try again");

	$stmt = $conn->prepare('INSERT INTO users(name, token, registred, score, attack, shtraf, tries) VALUES(?,?,NOW(),0,0,0,0)');
    $stmt->execute(array($username, $token));
	echo "Your token: ".$token." (Please don't fogot it)";
	
	
 
// $conn
