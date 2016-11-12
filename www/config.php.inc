<?php
	$dbhost = 'localhost';
	$dbuser = 'ussruser';
	$dbpass = 'ussrpassword';
	$dbname = 'automation-ussr';
	$conn = new PDO(
		'mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8',
		$dbuser, $dbpass
	);
