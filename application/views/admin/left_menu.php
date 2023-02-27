 <div class="row">
 	<div class="col-md-12">
 		<?php 
 		echo $this->session->flashdata('message');  
 		?>
 	</div>
 </div>

  <div class="container-fluid px-0 pt-2">
 	<div class="row gx-3">
 		<div class="col-md-2">
 			<div class="custom_column_reviewer">
 				
 					
 						<div class="d-grid gap-2">
 						<a href="<?php echo base_url('/admin/dashboard') ?>" class="btn
 						<?php
 						if($active_menu == "Dashboard")
 						{
 							echo "btn-dark";
 						}else{
 							echo "btn-light";
 						}
 						 ?>
 						btn-sm left_admin_menu">Dashboard</a>

 						<a href="<?php echo base_url('/admin/import') ?>" class="btn
 						<?php
 						if($active_menu == "Import Data")
 						{
 							echo "btn-dark";
 						}else{
 							echo "btn-light";
 						}
 						 ?>
 						btn-sm left_admin_menu">Import Data</a>


 						<a href="<?php echo base_url('/admin/applications') ?>" class="btn
 						<?php
 						if($active_menu == "Applications List")
 						{
 							echo "btn-dark";
 						}else{
 							echo "btn-light";
 						}
 						 ?>
 						btn-sm left_admin_menu">Applications List</a>


 						<a href="<?php echo base_url('/admin/users') ?>" class="btn
 						<?php
 						if($active_menu == "Users List")
 						{
 							echo "btn-dark";
 						}else{
 							echo "btn-light";
 						}
 						 ?>
 						btn-sm left_admin_menu">Users List</a>

 						<a href="<?php echo base_url('/admin/sections') ?>" class="btn
 						<?php
 						if($active_menu == "Sections List")
 						{
 							echo "btn-dark";
 						}else{
 							echo "btn-light";
 						}
 						 ?>
 						btn-sm left_admin_menu">Sections List</a>

 						<a href="<?php echo base_url('/admin/questions') ?>" class="btn
 						<?php
 						if($active_menu == "Questions List")
 						{
 							echo "btn-dark";
 						}else{
 							echo "btn-light";
 						}
 						 ?>
 						btn-sm left_admin_menu">Questions List</a>

 						

 						<a href="<?php echo base_url('/admin/logs/0/0/0/0') ?>" class="btn
 						<?php
 						if($active_menu == "Logs")
 						{
 							echo "btn-dark";
 						}else{
 							echo "btn-light";
 						}
 						 ?>
 						btn-sm left_admin_menu">Application Logs</a>

						<a href="<?php echo base_url('/admin/limits') ?>" class="btn
 						<?php
 						if($active_menu == "Limits")
 						{
 							echo "btn-dark";
 						}else{
 							echo "btn-light";
 						}
 						 ?>
 						btn-sm left_admin_menu">Manage Limits</a>


 						<a href="<?php echo base_url('/admin/crons') ?>" class="btn
 						<?php
 						if($active_menu == "Crons")
 						{
 							echo "btn-dark";
 						}else{
 							echo "btn-light";
 						}
 						 ?>
 						btn-sm left_admin_menu">Cron Details</a>






 						




 						</div>
 				
 			
 			</div>
 		</div>

