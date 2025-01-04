<?php
	// Start the session.
	session_start();
	if(!isset($_SESSION['user'])) header('location: login.php');


	// Get all products.
	$show_table = 'products';
	$products = include('database/show.php');
	$products = json_encode($products);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Order Product - Inventory Management System</title>
	<?php include('partials/app-header-scripts.php'); ?>
</head>

<body>
	<div id="dashboardMainContainer">
		<?php include('partials/app-sidebar.php') ?>
		<div class="dasboard_content_container" id="dasboard_content_container">
			<?php include('partials/app-topnav.php') ?>
			<div class="dashboard_content">				
				<?php if(in_array('po_create', $user['permissions'])) { ?>
				<div class="dashboard_content_main">		
					<div class="row">
						<div class="column column-12">
							<h1 class="section_header"><i class="fa fa-plus"></i> Order Product</h1>
							<div>
								<form action="database/save-order.php" method="POST">										
									<div class="alignRight">
										<button type="button" class="orderBtn orderProductBtn" id="orderProductBtn">Add Another Product</button>
									</div>

									<div id="orderProductLists">
										<p id="noData" style="color: #9f9f9f;"> No products selected.</p>
									</div>	

									<div class="alignRight marginTop20">
										<button type="submit" class="orderBtn submitOrderProductBtn">Submit Order</button>
									</div>
								</form>
							</div>							
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
				<?php } else { ?>
					<div id="errorMessage"> Access denied.</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php include('partials/app-scripts.php'); ?>

<script>
	var products  = <?= $products ?>;
	var counter = 0;

	function script(){
		var vm = this;

		let productOptions = '\
			<div>\
				<label for="product_name">PRODUCT NAME</label>\
				<select name="products[]" class="productNameSelect" id="product_name">\
					<option value="">Select Product</option>\
					INSERTPRODUCTHERE\
				</select>\
				<div>\
					<label for="requested_by">Requested By: </label>\
					<input type="text" name="requested_bys[]" id="requested_by" placeholder="Enter requested by..." />\
				</div>\
				<button class="appbtn removeOrderBtn">Remove</button>\
		    </div>';


		this.initialize = function(){
			this.registerEvents();
			this.renderProductOptions();
		},

		this.renderProductOptions = function(){
			let optionHtml = '';
			products.forEach((product) => {
				optionHtml += '<option value="'+ product.id +'">'+ product.product_name +'</option>';
			})

			productOptions = productOptions.replace('INSERTPRODUCTHERE', optionHtml);
		},

		this.registerEvents = function(){

			document.addEventListener('click', function(e){
				targetElement = e.target; // Target element
				classList = targetElement.classList;




				// Add new product order event
				if(targetElement.id === 'orderProductBtn'){
					document.getElementById('noData').style.display = 'none';
					let orderProductListsContainer = document.getElementById('orderProductLists');

					orderProductLists.innerHTML += '\
						<div class="orderProductRow">\
							'+ productOptions +'\
							<div class="suppliersRows" id="supplierRows_'+ counter +'" data-counter="'+ counter +'"></div>\
						</div>';

					counter++;
				}

				// If remove button is clicked
				if(targetElement.classList.contains('removeOrderBtn')){
					let orderRow = targetElement
						.closest('div.orderProductRow');

					// Remove element.
					orderRow.remove();
					console.log(orderRow);
				}
			});

			document.addEventListener('change', function(e){
				targetElement = e.target; // Target element
				classList = targetElement.classList;


				// Add suppliers row on product option change 
				if(classList.contains('productNameSelect')){
					let pid = targetElement.value;

					let counterId = targetElement
						.closest('div.orderProductRow')
						.querySelector('.suppliersRows')
						.dataset.counter;
					

					$.get('database/get-product-suppliers.php', {id: pid}, function(suppliers){
						vm.renderSupplierRows(suppliers, counterId);
					}, 'json');
				}
			});
		},

		this.renderSupplierRows = function(suppliers, counterId){
			let supplierRows = '';
				
			suppliers.forEach((supplier) => {
				supplierRows += '\
					<div class="row">\
						<div style="width: 50%;">\
							<p class="supplierName">'+ supplier.supplier_name +'</p>\
					    </div>\
						<div style="width: 50%;">\
							<label for="quantity">Quantity: </label>\
							<input type="number" class="appFormInput orderProductQty" id="quantity" placeholder="Enter quantity..." name="quantity['+ counterId +']['+ supplier.id +']" />\
					    </div>\
					</div>';
			});

			// Append to container
			let supplierRowContainer = document.getElementById('supplierRows_' + counterId);
			supplierRowContainer.innerHTML = supplierRows;
		}
	}

	(new script()).initialize();
</script>
</body>
</html>