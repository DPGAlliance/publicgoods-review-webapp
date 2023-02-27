

<div class="col-md-10">
	<div class="custom_column_reviewer">
		<div class="d-flex justify-content-between">
			<div>
				<table class="table table-sm">
					<thead>
						<tr>
							<th>Application ID</th>
							<th>Name</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php

						$new_applications_array = array_column($new_applications, 'old_dpg_id');
						foreach ($old_dpg_array as $key => $single_old_app) {
							echo "<tr>";
							echo "<td>";
							echo $single_old_app['id'];
							$import_url = "admin/import/old_application/dpg/" .$single_old_app['id']. "";
							$delete_url = "admin/delete/old_application/dpg/" .$single_old_app['id']. "";
							echo "</td>";
							echo "<td>";
							echo $single_old_app['name'];
							echo "</td>";

							echo "<td>";
							$appid_key = array_search($single_old_app['id'], $new_applications_array);
        if (is_numeric($appid_key)) {
          echo '<span class="badge rounded-pill bg-success">Completed</span>';
        }else{
        	echo '<span class="badge rounded-pill bg-warning">Pending</span>';
        }
							echo "</td>";

							echo "<td>";
							$appid_key = array_search($single_old_app['id'], $new_applications_array);
        if (is_numeric($appid_key)) {
         echo "<a href='";
        	echo base_url($delete_url);
        	echo "' style='color: red;'>Delete</a>";
        }else{
        	echo "<a href='";
        	echo base_url($import_url);
        	echo "'>Import</a>";
        }
							echo "</td>";
							echo "</tr>";
						}


						 ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div><!-- col-10 end -->
</div><!-- row end -->
</div><!-- container-fluid end -->