<?php
	$data = $_POST;
	$id  = (int) $data['id'];
	$table = $data['table'];


	// Adding the record.
	try {
		include('connection.php');

		// Delete junction table 

		if($table === 'suppliers'){
			$command = "DELETE FROM productsuppliers WHERE supplier={$id}";
			$conn->exec($command);
		}

		if($table === 'products'){
			// Product Supplier
			$command = "DELETE FROM productsuppliers WHERE product={$id}";
			$conn->exec($command);

			// Get order product ids 
			$stmt = $conn->prepare("SELECT id FROM order_product WHERE product={$id}");
			$stmt->execute();
			$rows = $stmt->fetchAll();
			$ids  = array_column($rows, 'id');



			if($ids){
				$ids = implode(",", $ids);
				// Order Product History
				$command = "DELETE FROM order_product_history WHERE order_product_id IN ({$ids})";
				$conn->exec($command);

				// Order Product
				$command = "DELETE FROM order_product WHERE product={$id}";
				$conn->exec($command);
			}


				// Request
				$command = "DELETE FROM requests WHERE product_id={$id}";
				$conn->exec($command);
		}

		// Delete main table.
		$command = "DELETE FROM $table WHERE id={$id}";
		$conn->exec($command);

		echo json_encode([
 			'success' => true,
		]);
	} catch (PDOException $e) {
		echo json_encode([
 			'success' => false,
		]);
	}


