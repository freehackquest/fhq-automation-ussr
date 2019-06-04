<?php
	header("Access-Control-Allow-Origin: *");

	if (!isset($_GET['flag']) || !isset($_GET['token']))
	{
		echo '
			<form>
				<table>
					<tr>
						<td>Flag:</td>
						<td><input type="text" name="flag" value=""/></td>
					</tr>
					<tr>
						<td>Token:</td>
						<td><input type="text" name="token" value=""/></td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" value="Send"/></td>
					</tr>
				</table>
			</form>
		';
		exit;
	}

	$flag_was_not_accepted = "[FLAG WAS NOT ACCEPTED]";
	
	if(!isset($_GET['token']) && !isset($_GET['flag'])) {
		// $conn->close();
		echo($flag_was_not_accepted);
		exit;
	}
	
	$token = $_GET['token'];
	$flag = strtoupper($_GET['flag']);

	if (!preg_match("/^[A-Za-z0-9]*$/", $token)){
		echo($flag_was_not_accepted."Invalid token");
		exit;
	}

	// 6a331fd2-133a-4713-9587-12652d34666d
	if (!preg_match("/^[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}$/", $flag)){
		echo($flag_was_not_accepted." (6 - flag has wrong format)");
		exit;
	}

	include_once('../../../config.php');

	// check token
	{
		$stmt = $conn->prepare('SELECT COUNT(*) as cnt FROM users WHERE token = ?');
		$stmt->execute(array($token));
		
		$count = 0;
		if ($data = $stmt->fetch())
			$count = $data['cnt'];

		if ($count == 0){
			echo($flag_was_not_accepted." (4 - Token did not found)");
			exit;
		}else if ($count > 1){
			echo($flag_was_not_accepted." (15 - System has errors, please say about to admin)");
			exit;
		}
	}
	
	// search user id
	$userid = 0;
	{
		$stmt = $conn->prepare('SELECT id FROM users WHERE token = ?');
		$stmt->execute(array($token));
		if ($data = $stmt->fetch())
			$userid = $data['id'];

		if ($userid == 0){
			echo($flag_was_not_accepted." (16 - User did not found by token)");
			exit;
		}
	}

	// insert to tries
	{
		$stmt = $conn->prepare('INSERT INTO tries(flag,dt,userid) VALUES(?,NOW(),?)');
		$stmt->execute(array($flag, $userid));
	}
	
	// update tries
	{
		$stmt = $conn->prepare('UPDATE users SET tries = (SELECT COUNT(*) FROM tries WHERE userid = ?) WHERE id = ?');
		$stmt->execute(array($userid, $userid));
	}

	// update shtraf for user
	{
		// SELECT flag, COUNT(*) FROM tries WHERE userid = 1 GROUP BY flag
		$stmt = $conn->prepare('UPDATE users SET shtraf = (select sum(t0.shtraf) from (SELECT COUNT(*)-1 as shtraf FROM tries WHERE userid = ? GROUP BY flag) as t0) WHERE id = ?');
		$stmt->execute(array($userid, $userid));
	}

	// update score for user
	{
		// SELECT flag, COUNT(*) FROM tries WHERE userid = 1 GROUP BY flag
		$stmt = $conn->prepare('UPDATE users SET score = attack - shtraf WHERE id = ?');
		$stmt->execute(array($userid));
	}

	// check for old flag
	{
		$stmt = $conn->prepare('select id, userid, dt_end - NOW() as ti FROM flags WHERE flag = ?');
		$stmt->execute(array($flag));
		if ($data = $stmt->fetch()){
			if($data['ti'] < 0){
				echo($flag_was_not_accepted." (8 - flag is old)");
				exit;
			}else{
				if ($data['userid'] == $userid) {
					echo($flag_was_not_accepted." (9 - flag already belongs to you)");
					exit;
				}
				
				if ($data['userid'] != $userid && $data['userid'] != 0) {
					echo($flag_was_not_accepted." (10 - flag belongs to another user)");
					exit;
				}

				$stmt_update = $conn->prepare('UPDATE flags SET userid = ? WHERE flag = ? AND dt_end > NOW() AND userid <> ? AND userid = 0');
				$stmt_update->execute(array($userid, $flag, $userid));
				echo "[FLAG ACCEPTED]";
			}
		}else{
			echo($flag_was_not_accepted." (7 - flag is not exists)");
			exit;
		}
	}

	// update user attack
	{
		$stmt = $conn->prepare('UPDATE users SET attack = (select COUNT(*) FROM flags WHERE userid = ?) WHERE id = ?');
		$stmt->execute(array($userid, $userid));
	}

	// update score for user
	{
		// SELECT flag, COUNT(*) FROM tries WHERE userid = 1 GROUP BY flag
		$stmt = $conn->prepare('UPDATE users SET score = attack - shtraf WHERE id = ?');
		$stmt->execute(array($userid));
	}
