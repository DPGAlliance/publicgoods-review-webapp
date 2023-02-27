

<div class="col-md-10">
	<div class="custom_column_reviewer">
					<div>
 						
 						<a href="<?php echo base_url('/admin/sections') ?>" class="btn
 						<?php
 						if($active_menu == "Sections List")
 						{
 							echo "btn-primary";
 						}else{
 							echo "btn-light";
 						}
 						 ?>
 						btn-sm left_admin_menu">All Sections List</a>

 						<button type="button" class="btn btn-light btn-sm left_admin_menu" data-bs-toggle="modal" data-bs-target="#addNewSectionModal">
						Add New Section
						</button>

 					</div> <!-- page menu end -->
 					<hr>