<?php
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

	include_once('../../../config.php');
	$result = array('rating' => array());
	$stmt = $conn->prepare('SELECT name, score, attack, shtraf, tries FROM users ORDER BY score DESC');
    $stmt->execute();
    while($data = $stmt->fetch()){
		$result['rating'][] = array(
			'name' => $data['name'],
			'score' => $data['score'],
			'attack' => $data['attack'],
			'shtraf' => $data['shtraf'],
			'tries' => $data['tries']
		);
	}
    echo json_encode($result);
