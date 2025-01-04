<?php
	// Start the session.
	session_start();
	if(!isset($_SESSION['user'])) header('location: login.php');

	$_SESSION['table'] = 'products';
	$_SESSION['redirect_to'] = 'product-add.php';

	$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Schedule - Inventory Management System</title>
	<?php include('partials/app-header-scripts.php'); ?>
</head>

<body>
	<div id="dashboardMainContainer">
		<?php include('partials/app-sidebar.php') ?>
		<div class="dasboard_content_container" id="dasboard_content_container">
			<?php include('partials/app-topnav.php') ?>
			<div class="dashboard_content">
				<div class="dashboard_content_main">		
					<div class="row" id="schedulePageContainer">
						<div class="column column-12">
							<h1 class="section_header"><i class="fa fa-plus"></i> Add Schedule</h1>
							<div>						
								<form action="database/save-request.php" method="POST" class="appForm" >
									<div class="appFormInputContainer">
										<label for="requested_by">Requested By</label>
										<input type="text" class="appFormInput" id="requested_by" placeholder="Enter requested by..." name="requested_by" />	
									</div>
									<div class="appFormInputContainer">
										<label for="description">Products</label>
										<select name="product" id="product">
											<option value="">Select Product</option>
											<?php
												$show_table = 'products';
												$products = include('database/show.php');
												$product_stocks = [];

												foreach($products as $product){
													if( (int) $product['stock'] != 0){
														echo "<option value='".  $product['id']  . "'> ".$product['product_name'] . " (stock: ". $product['stock'] .")</option>";
														$product_stocks[$product['id']] = (int) $product['stock'];
													}
												}
											?>
										</select>
									</div>
									<div class="appFormInputContainer">
										<label for="qty">Quantity</label>
										<input type="number" min="0" class="appFormInput" id="qty" placeholder="Enter quantity.." name="qty" />	
									</div>
									<div class="appFormInputContainer">
										<label for="date">Date</label>
										<input type="date" id="date" name="date" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>"  />
									</div>
									<input type="hidden" name="cur_stock" id="cur_stock" value="0">

									<button type="submit" class="appBtn"><i class="fa fa-plus"></i> Add Schedule</button>
								</form>	

								<?php 
									if(isset($_SESSION['response'])){
										$response_message = $_SESSION['response']['message'];
										$is_success = $_SESSION['response']['success'];
								?>
									<div class="responseMessage">
										<p class="responseMessage <?= $is_success ? 'responseMessage__success' : 'responseMessage__error' ?>" >
											<?= $response_message ?>
										</p>
									</div>
								<?php unset($_SESSION['response']); }  ?>
							</div>	
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>
	<?php include('partials/app-scripts.php'); ?>

<script type="text/javascript">
	let productStocks = <?= json_encode($product_stocks) ?>;


	document.addEventListener('change', function(e){
		let targetEl = e.target;

		if(targetEl.id === 'product'){
			let qtyEl = document.getElementById('qty');
			if(targetEl.value){
				qtyEl.setAttribute('max', productStocks[targetEl.value]);
				document.getElementById('cur_stock').value = productStocks[targetEl.value];
			} else {
				qtyEl.removeAttribute('max');
				qtyEl.value = 0;
			}
		}

	});
</script>
</body>
</html>