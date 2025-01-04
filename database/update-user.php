<?php
	$data = $_POST;
	$user_id  = (int) $data['userId'];
	$first_name  = $data['f_name'];
	$last_name  = $data['l_name'];
	$email = $data['email'];
	$permissions = isset($data['permissions']) ? $data['permissions'] : '';

	if($permissions == ''){
		echo json_encode([
 			'success' => false,
 			'message' => 'Please make sure permissions are set'
		]);
		return;
	}

	// Adding the record.
	try {			
		$sql = "UPDATE users SET email=?, first_name=?, last_name=?, updated_at=?, permissions=? WHERE id=?";
		include('connection.php');
		$conn->prepare($sql)->execute([$email, $first_name, $last_name, date('Y-m-d h:i:s'), $permissions, $user_id]);
		echo json_encode([
 			'success' => true,
 			'message' => $first_name . ' ' . $last_name . ' successfully updated.'
		]);
	} catch (PDOException $e) {
		echo json_encode([
 			'success' => false,
 			'message' => 'Error processing your request!'
		]);
	}


