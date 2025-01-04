<?php
	// Start the session.
	session_start();
	if(!isset($_SESSION['user'])) header('location: login.php');

	// Get all products.
	include('database/connection.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>View Scheduled Requests - Inventory Management System</title>
	<?php include('partials/app-header-scripts.php'); ?>
</head>
<body>
	<div id="dashboardMainContainer">
		<?php include('partials/app-sidebar.php') ?>
		<div class="dasboard_content_container" id="dasboard_content_container">
			<?php include('partials/app-topnav.php') ?>
			<div class="dashboard_content">
				<div class="dashboard_content_main">		
					<div class="row">
						<div class="column column-12">
							<h1 class="section_header"><i class="fa fa-list"></i> List of Scheduled Requests</h1>
							<div class="section_content">
								<div class="users">
									<?php
										$statement = $conn->prepare("SELECT requests.id, products.product_name, requests.status, requests.requested_by, requests.product_id, requests.quantity, requests.date 
														FROM requests
														LEFT JOIN products 
														ON requests.product_id = products.id");
										$statement->execute();
										$rows = $statement->fetchAll(PDO::FETCH_ASSOC);

										$has_data = count($rows) ? true : false;
										if(!$has_data) echo "No data";
										else { 
									?>
									<table>
										<thead>
											<tr>												
												<th>#</th>					
												<th>Requested By</th>
												<th>Product</th>
												<th>Quantity</th>
												<th>Due Date</th>
												<th>Action</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
											<?php
												 foreach($rows as $index => $row){ ?>
													<tr>
														<td><?= $index + 1 ?></td>
														<td><?= $row['requested_by'] ?></td>
														<td><?= $row['product_name'] ?></td>
														<td><?= number_format($row['quantity']) ?></td>
														<td class="bgRed"><?= date('F d, Y', strtotime($row['date'])) ?></td>
														<td>
															<a href="" class="extendRequest" data-rid="<?= $row['id'] ?>" data-value="<?= $row['date'] ?>"> <i class="fa fa-calendar"></i> Extend</a>
														</td>
														<td>
															<?php 
																if($row['status'] == 'Returned' && !empty($row['status']) ) {
																	echo 'Returned';
																} else {
															?>
															<select class="statusSelect" data-rid="<?= $row['id'] ?>" data-pid="<?= $row['product_id'] ?>" data-qty="<?= $row['quantity'] ?>">
																<option value="On Process" <?= $row['status'] == 'On Process' ? 'selected' : '' ?>>On Process</option>
																<option value="On Hand" <?= $row['status'] == 'On Hand' ? 'selected' : '' ?>>On Hand</option>
																<option value="Returned" <?= $row['status'] == 'Returned' ? 'selected' : '' ?>>Returned</option>																
															</select>
														<?php } ?>
														</td>
													</tr>
												<?php } ?>
										</tbody>
									</table>
									<p class="userCount"><?= count($rows) ?> Scheduled Requests </p>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>


<?php 
	include('partials/app-scripts.php'); 

	$show_table = 'suppliers';
	$suppliers = include('database/show.php');

	$suppliers_arr = [];

	foreach($suppliers as $supplier){
		$suppliers_arr[$supplier['id']] = $supplier['supplier_name'];
	}

	$suppliers_arr = json_encode($suppliers_arr);
?>
<script>
	var suppliersList = <?= $suppliers_arr ?>;


	function script(){
		var vm = this;

		this.registerEvents = function(){
			document.addEventListener('click', function(e){
				targetElement = e.target; // Target element
				classList = targetElement.classList;


				if(classList.contains('extendRequest')){
					e.preventDefault(); // this prevents the default mechanism.
					rId = targetElement.dataset.rid;
					value = targetElement.dataset.value;

					BootstrapDialog.confirm({
						title: 'Extend Due Date',
						message: 'Set new date: <hr/> \
								<input type="date" id="extendDate" name="extendDate" value="'+ value +'" min="<?= date('Y-m-d') ?>"  />',
						callback: function(isExtend){
							if(isExtend){								
								$.ajax({
									method: 'POST',
									data: {
										id: rId,
										newDate: document.getElementById('extendDate').value
									},
									url: 'database/extend-request.php',
									dataType: 'json',
									success: function(data){
										message = data.success ? 'Request successfully updated!' : 'Error processing your request!';

										BootstrapDialog.alert({
											type: data.success ? BootstrapDialog.TYPE_SUCCESS : BootstrapDialog.TYPE_DANGER,
											message: message,
											callback: function(){
												if(data.success) location.reload();
											}
										});
									}
								});
							}
						}
					});
				}

			});


			document.addEventListener('change', function(e){
				targetElement = e.target; // Target element
				classList = targetElement.classList;


				if(classList.contains('statusSelect')){
					rId = targetElement.dataset.rid;
					pid = targetElement.dataset.pid;
					qty = targetElement.dataset.qty;
					console.log('rId:', rId, 'pid:', pid, 'qty:', qty);


					$.ajax({
						method: 'POST',
						data: { id: rId, status: targetElement.value, pid: pid , qty: qty },
						url: 'database/update-request-status.php',
						dataType: 'json',
						success: function(data){
							message = data.success ? 'Request status successfully updated!' : 'Error processing your request!';

							BootstrapDialog.alert({
								type: data.success ? BootstrapDialog.TYPE_SUCCESS : BootstrapDialog.TYPE_DANGER,
								message: message,
								callback: function(){
									if(data.success) location.reload();
								}
							});
						}
					});
				}

			});
		},


		this.initialize = function(){
			this.registerEvents();
		}
	}
	var script = new script;
	script.initialize();
</script>
</body>
</html>