<?php
session_start();

$post_data = $_POST;
$products = $post_data['products'];
$qty = array_values($post_data['quantity']);
$requested_by = $post_data['requested_bys'];

$post_data_arr = [];


foreach ($products as $key => $pid) {
	if(isset($qty[$key])){
		$post_data_arr[$pid]['sup_qty'] = $qty[$key];
		$post_data_arr[$pid]['req_by'] = isset($requested_by[$key]) ? $requested_by[$key] : 'NOT SET';
	}
}

// Include connection
include('connection.php');


// Store data.
$batch = time();


$success = false;
try {
	foreach($post_data_arr as $pid => $post_data){
		$supplier_qty = $post_data['sup_qty'];
		$requested_by = $post_data['req_by'];
		foreach($supplier_qty as $sid => $qty){
			// Insert to database.

			$values = [
				'supplier' => $sid,
				'product' => $pid,
				'quantity_ordered' => $qty,
				'status' => 'pending',
				'batch' => $batch,
				'created_by' =>  $_SESSION['user']['id'],
				'requested_by' => $requested_by,
				'updated_at' => date('Y-m-d H:i:s'),
				'created_at' => date('Y-m-d H:i:s')
			];

			$sql = "INSERT INTO order_product			
						(supplier, product, quantity_ordered, status, batch, created_by, requested_by, updated_at, created_at) 
					VALUES 
						(:supplier, :product, :quantity_ordered, :status, :batch, :created_by, :requested_by, :updated_at, :created_at)";
			$stmt = $conn->prepare($sql);
			$stmt->execute($values);
		}
	}
	$success = true;
	$message = 'Order successfully created!';
} catch (\Exception $e) {
	$message = $e->getMessage();
}

$_SESSION['response']  = [
	'message' => $message,
	'success' => $success
];

header('location: ../product-order.php');
