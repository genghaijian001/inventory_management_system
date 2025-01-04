<?php
	try {
		$req_id = $_POST['id'];
		$status = $_POST['status'];
		$pid = $_POST['pid'];
		$stock_requested = $_POST['qty'];
		
		include('connection.php');

		// Update qty 
		if($status === 'Returned'){
			$stmt = $conn->prepare("SELECT products.stock FROM products WHERE id = $pid");
			$stmt->execute();
			$product = $stmt->fetch();
			$cur_stock = (int) $product['stock'];
			$updated_stock = $cur_stock + (int) $stock_requested;

			$sql = "UPDATE products SET stock=? WHERE id=?";
			$stmt = $conn->prepare($sql);
			$stmt->execute([$updated_stock, $pid]);
		}

		// Save status 
		$sql = "UPDATE requests SET status=? WHERE id=?";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$status, $req_id]);

		echo json_encode(['success' => true]);
	} catch (PDOException $e) {
		echo json_encode([ 'success' => false ]);
	}