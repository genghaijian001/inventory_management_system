<?php
	try {
		$req_id = $_POST['id'];
		$date = $_POST['newDate'];

		$sql = "UPDATE requests SET date=? WHERE id=?";
		include('connection.php');
		$stmt = $conn->prepare($sql);
		$stmt->execute([$date, $req_id]);

		echo json_encode(['success' => true]);
	} catch (PDOException $e) {
		echo json_encode([ 'success' => false ]);
	}