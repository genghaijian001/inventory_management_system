<?php
	session_start();
	try {			
		// Get data
		$requested_by = isset($_POST['requested_by']) ? $_POST['requested_by'] : NULL;
		$product = isset($_POST['product']) ? $_POST['product'] : NULL;
		$qty = isset($_POST['qty']) ? $_POST['qty'] : NULL;
		$date = isset($_POST['date']) ? $_POST['date'] : NULL;
		$cur_stock = $_POST['cur_stock'];

		$data = [
			'requested_by' => $requested_by,
			'product_id' => $product,
			'quantity' => $qty,
			'date' => $date,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
			'status' => 'On Process'
		];


		$sql = "INSERT INTO requests			
					(requested_by, product_id, quantity, date, created_at, updated_at, status) 
				VALUES 
					(:requested_by, :product_id, :quantity, :date, :created_at, :updated_at, :status)";

		include('connection.php');
		$stmt = $conn->prepare($sql);
		$stmt->execute($data);

		// Update product qty
		$new_stock = (int) $cur_stock - (int) $qty;
		$sql = "UPDATE products SET stock=? WHERE id=?";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$new_stock, $product]);


		$response = [
			'success' => true,
			'message' => 'Request successfully added to the system.'
		];
	} catch (PDOException $e) {
		$response = [
			'success' => false,
			'message' => $e->getMessage()
		];
	}

	$_SESSION['response'] = $response;
	header('location: ../schedule-add.php');

