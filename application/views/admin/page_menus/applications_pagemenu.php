

<div class="col-md-10">
	<div class="custom_column_reviewer">
					<div>
 						
 						<a href="<?php echo base_url('/admin/applications') ?>" class="btn
 						<?php
 						if($sub_active_menu == "All Applications")
 						{
 							echo "btn-primary";
 						}else{
 							echo "btn-light";
 						}
 						 ?>
 						btn-sm left_admin_menu">All Applications</a>

						<?php 
						if($sub_active_menu == "Filtered Data")
 						{
 							echo "<button type='button' class='btn btn-primary btn-sm left_admin_menu'>
						<i class='bi bi-funnel'></i> $filter_text </button>";
 						}

						?>

						

 					</div> <!-- page menu end -->
 					<hr>