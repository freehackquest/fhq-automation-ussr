<?php
	$dbhost = 'localhost';
	$dbuser = 'automation-ussr';
	$dbpass = 'automation-ussr';
	$dbname = 'automation-ussr';
	$conn = new PDO(
		'mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8',
		$dbuser, $dbpass
	);
