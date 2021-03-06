<?php 

	session_start();
	require_once "../../model/settings.php";
	// if(isset($_POST["userId"])){

		$sql = "SELECT id FROM `users` WHERE `username` = ? ";
		$params = array($_SESSION["user"]);
		$currentUserId = $db->row($sql, $params);

		$sql = "SELECT * FROM `follow` WHERE `user_id` = ? AND `follow_user_id` = ?";
		$params = array($currentUserId->id, $_POST['userId']);
		$row = $db->row($sql, $params);
		if ($row){
			$db->delete("follow",array(
									"follow_user_id"	=> $_POST["userId"],
									"user_id"			=> $currentUserId->id
								)
							);
			$data = array("status" => 200, "followed" => false);			
		}else{
			$db->insert("follow", array(
									"follow_user_id"	=> $_POST["userId"],
									"user_id"			=> $currentUserId->id,
									"date_followed"		=> date('Y-m-d H:i:s')
								)
							);
			$data = array("status" => 200, "followed" => true);			
		}

        // followers update
		$sql = "SELECT COUNT(follow_user_id) as total FROM follow WHERE follow_user_id = ?";
		$params = array($_POST["userId"]);
		$row = $db->row($sql, $params);

		$db->update("users", array("followers" => $row->total), array('id' => $_POST["userId"]));
		
        $data["totalFollowers"] = $row->total;

			$sql = "SELECT COUNT(user_id) as totalF FROM follow WHERE user_id = ?";
			$params = array($currentUserId->id);
			$row = $db->row($sql, $params);
			$db->update("users", array("following" => $row->totalF), array("id" => $currentUserId->id));
			// print_r($row);
			echo json_encode($data);
	// }

?>