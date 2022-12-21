

<div class="col-md-10">
	<div class="custom_column_reviewer">
					<div>
 						
 						<a href="<?php echo base_url('/admin/users') ?>" class="btn
 						<?php
 						if($active_menu == "Users List")
 						{
 							echo "btn-primary";
 						}else{
 							echo "btn-light";
 						}
 						 ?>
 						btn-sm left_admin_menu">All Users List</a>

 						<button type="button" class="btn btn-light btn-sm left_admin_menu" data-bs-toggle="modal" data-bs-target="#addNewUserModal">
						Add New User
						</button>

 					</div> <!-- page menu end -->
 					<hr>