<?php
	// Start the session.
	session_start();
	if(!isset($_SESSION['user'])) header('location: login.php');
	$_SESSION['table'] = 'users';
	$user = $_SESSION['user'];


	$show_table = 'users';
	$users = include('database/show.php');

	$user_permission = $user['permissions'];
?>
<!DOCTYPE html>
<html>
<head>
	<title>View Users - Inventory Management System</title>
	<?php include('partials/app-header-scripts.php'); ?>
</head>
<body>
	<div id="dashboardMainContainer">
		<?php include('partials/app-sidebar.php') ?>
		<div class="dasboard_content_container" id="dasboard_content_container">
			<?php include('partials/app-topnav.php') ?>
			<div class="dashboard_content">
				<?php if(in_array('user_view', $user['permissions'])) { ?>
				<div class="dashboard_content_main">		
					<div class="row">
						<div class="column column-12">
							<h1 class="section_header"><i class="fa fa-list"></i> List of Users</h1>
							<div class="section_content">
								<div class="users">
									<table>
										<thead>
											<tr>												
												<th>#</th>					
												<th>First Name</th>
												<th>Last Name</th>
												<th>Email</th>
												<th>Created At</th>
												<th>Updated At</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($users as $index => $user){ ?>
												<tr>
													<td><?= $index + 1 ?></td>
													<td class="firstName"><?= $user['first_name'] ?></td>
													<td class="lastName"><?= $user['last_name'] ?></td>
													<td class="email"><?= $user['email'] ?></td>
													<td><?= date('M d,Y @ h:i:s A', strtotime($user['created_at'])) ?></td>
													<td><?= date('M d,Y @ h:i:s A', strtotime($user['updated_at'])) ?></td>
													<td>
														<a href="" 
															class="<?= in_array('user_edit', $user_permission) ? 'updateUser' : 'accessDeniedErr' ?>" 
															data-userid="<?= $user['id'] ?>"> <i class="fa fa-pencil"></i> Edit</a>
														<a href="" 
															class="<?= in_array('user_delete', $user_permission) ? 'deleteUser' : 'accessDeniedErr' ?>" 
															data-userid="<?= $user['id'] ?>" data-fname="<?= $user['first_name'] ?>" data-lname="<?= $user['last_name'] ?>" > <i class="fa fa-trash"></i> Delete</a>
														<input type="hidden" id="cur_permission_<?= $user['id'] ?>" value="<?= $user['permissions'] ?>">
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
									<p class="userCount"><?= count($users) ?> Users </p>
								</div>
							</div>
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
	function script(){
		this.permissions = [];

		this.permissionEl = '\
			<div id="permissions">\
			 	<h4>Permissions</h4>\
			 	<hr>\
			 	<div id="permissionsContainer">\
			 		<div class="permission">\
			 			<div class="row">\
			 				<div class="col-md-3">\
			 					<p class="moduleName">Dashboard</p>\
			 				</div>\
			 				<div class="col-md-2">\
			 					<p class="moduleFunc" data-value="dashboard_view">View</p>\
			 				</div>\
			 			</div>\
			 		</div>\
			 		<div class="permission">\
			 			<div class="row">\
			 				<div class="col-md-3">\
			 					<p class="moduleName">Reports</p>\
			 				</div>\
			 				<div class="col-md-2">\
			 					<p class="moduleFunc" data-value="report_view">View</p>\
			 				</div>\
			 			</div>\
			 		</div>\
			 		<div class="permission">\
			 			<div class="row">\
			 				<div class="col-md-3">\
			 					<p class="moduleName">Purchase Order</p>\
			 				</div>\
			 				<div class="col-md-2">\
			 					<p class="moduleFunc" data-value="po_view">View</p>\
			 				</div>\
			 				<div class="col-md-2">\
			 					<p class="moduleFunc" data-value="po_create">Create</p>\
			 				</div>\
			 				<div class="col-md-2">\
			 					<p class="moduleFunc" data-value="po_edit">Edit</p>\
			 				</div>\
			 			</div>\
			 		</div>\
			 		<div class="permission">\
			 			<div class="row">\
			 				<div class="col-md-3">\
			 					<p class="moduleName">Product</p>\
			 				</div>\
			 				<div class="col-md-2">\
			 					<p class="moduleFunc" data-value="product_view">View</p>\
			 				</div>\
			 				<div class="col-md-2">\
			 					<p class="moduleFunc" data-value="product_create">Create</p>\
			 				</div>\
			 				<div class="col-md-2">\
			 					<p class="moduleFunc" data-value="product_edit">Edit</p>\
			 				</div>\
			 				<div class="col-md-2">\
			 					<p class="moduleFunc" data-value="product_delete">Delete</p>\
			 				</div>\
			 			</div>\
			 		</div>\
			 		<div class="permission">\
			 			<div class="row">\
			 				<div class="col-md-3">\
			 					<p class="moduleName" >Supplier</p>\
			 				</div>\
			 				<div class="col-md-2">\
			 					<p class="moduleFunc" data-value="supplier_view">View</p>\
			 				</div>\
			 				<div class="col-md-2">\
			 					<p class="moduleFunc" data-value="supplier_create">Create</p>\
			 				</div>\
			 				<div class="col-md-2">\
			 					<p class="moduleFunc" data-value="supplier_edit">Edit</p>\
			 				</div>\
			 				<div class="col-md-2">\
			 					<p class="moduleFunc" data-value="supplier_delete">Delete</p>\
			 				</div>\
			 			</div>\
			 		</div>\
			 		<div class="permission">\
			 			<div class="row">\
			 				<div class="col-md-3">\
			 					<p class="moduleName">User</p>\
			 				</div>\
			 				<div class="col-md-2">\
			 					<p class="moduleFunc" data-value="user_view">View</p>\
			 				</div>\
			 				<div class="col-md-2">\
			 					<p class="moduleFunc" data-value="user_create">Create</p>\
			 				</div>\
			 				<div class="col-md-2">\
			 					<p class="moduleFunc" data-value="user_edit">Edit</p>\
			 				</div>\
			 				<div class="col-md-2">\
			 					<p class="moduleFunc" data-value="user_delete">Delete</p>\
			 				</div>\
			 			</div>\
			 		</div>\
			 		<div class="permission">\
			 			<div class="row">\
			 				<div class="col-md-3">\
			 					<p class="moduleName">Point of Sale</p>\
			 				</div>\
			 				<div class="col-md-2">\
			 					<p class="moduleFunc" data-value="pos">Grant</p>\
			 				</div>\
			 			</div>\
			 		</div>\
			 	</div>\
			</div>';

		this.initialize = function(){
			this.registerEvents();
		},

		this.registerEvents = function(){
			document.addEventListener('click', function(e){
				targetElement = e.target;
				classList = targetElement.classList;


				// Permissions
     			let target = e.target;

     			// Check if class name - moduleFunc - is clicked
     			if(target.classList.contains('moduleFunc')){
     				// Get value
     				let permissionName = target.dataset.value;

     				// Set the active class
     				if(target.classList.contains('permissionActive')){
     					target.classList.remove('permissionActive');

     					// Remove from array
     					script.permissions = script.permissions.filter((name) => {
     						return name !== permissionName;
     					});
     				} else {
     					target.classList.add('permissionActive');
     					script.permissions.push(permissionName);
     				}

     				// Update the hidden element
     				document.getElementById('permission_el')
     					.value = script.permissions.join(',');
     			}


				if(classList.contains('deleteUser')){
					e.preventDefault();
					userId = targetElement.dataset.userid;
					fname = targetElement.dataset.fname;
					lname = targetElement.dataset.lname;
					fullName = fname + ' ' + lname;

					BootstrapDialog.confirm({
						title: 'Delete User',
						type: BootstrapDialog.TYPE_DANGER,
						message: 'Are you sure to delete <strong>'+ fullName +'</strong> ?',
						callback: function(isDelete){
							if(isDelete){								
								$.ajax({
									method: 'POST',
									data: {									
										id: userId,
										table: 'users'
									},
									url: 'database/delete.php',
									dataType: 'json',
									success: function(data){
										message = data.success ? 
												fullName + ' successfully deleted!' : 'Error processing your request!';

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

				if(classList.contains('updateUser')){
					e.preventDefault(); // Prevent loading.;

					// Get data.
					firstName = targetElement.closest('tr').querySelector('td.firstName').innerHTML;
					lastName = targetElement.closest('tr').querySelector('td.lastName').innerHTML;
					email = targetElement.closest('tr').querySelector('td.email').innerHTML;
					userId = targetElement.dataset.userid;
					let permissions = document.getElementById('cur_permission_' + userId).value;

					BootstrapDialog.confirm({
						title: 'Update ' + firstName + ' ' + lastName,
						message: '<form>\
						  <div class="form-group">\
						    <label for="firstName">First Name:</label>\
						    <input type="text" class="form-control" id="firstName" value="'+ firstName +'">\
						  </div>\
						  <div class="form-group">\
						    <label for="lastName">Last Name:</label>\
						    <input type="text" class="form-control" id="lastName" value="'+ lastName +'">\
						  </div>\
						  <div class="form-group">\
						    <label for="email">Email address:</label>\
						    <input type="email" class="form-control" id="emailUpdate" value="'+ email +'">\
						  </div>' + script.permissionEl + '\
						  <input type="hidden" id="permission_el" name="permissions" value="'+ permissions +'" > \
						</form>',
						callback: function(isUpdate){
							if(isUpdate){ // If user click 'Ok' button.
								$.ajax({
									method: 'POST',
									data: {
										userId: userId,
										f_name: document.getElementById('firstName').value,
										l_name: document.getElementById('lastName').value,
										email: document.getElementById('emailUpdate').value,
										permissions: document.getElementById('permission_el').value
									},
									url: 'database/update-user.php',
									dataType: 'json',
									success: function(data){
										if(data.success){
											BootstrapDialog.alert({
												type: BootstrapDialog.TYPE_SUCCESS,
												message: data.message,
												callback: function(){
													location.reload();
												}
											});
										} else 
											BootstrapDialog.alert({
												type: BootstrapDialog.TYPE_DANGER,
												message: data.message,
											});
									}
								});
							}
						},
						onshown: function(){
							script.permissions = [];
							
							// Add permission active class
							let permissionsArr = permissions.split(',');
							// Loop and then select element and add class

							permissionsArr.forEach((permission) => {
								if(permission !== ''){									
									let targetEl = document.querySelector("[data-value='"+ permission +"'");
									if(targetEl != null){									
										targetEl.classList.add('permissionActive');
										script.permissions.push(permission);
									}
								}
							});

						}
					});
				}
			});
		}
	}	

	var script = new script;
	script.initialize();
</script>
</body>
</html>