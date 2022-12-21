 <div class="row">
 	<div class="col-md-12">
 		<?php 
 		echo $this->session->flashdata('message');  
 		?>
 	</div>
 </div>
 <div class="container-fluid px-0 pt-2">
 	<div class="row gx-3">
 		<div class="col-md-12">
 			<div class="custom_column_reviewer table-responsive">
 				<h2>Directory</h2>
 				<table id="list_directory" class="table table-striped" style="width:100%">
 					<thead>
 						<th>Application ID</th>
 						<th>Name</th>
 						<th>Tags</th>
 						<th>Review Status</th>
 						<th>Action</th>
 					</thead>
 					<tbody>
 						<?php 
 						foreach ($list_directory as $key => $single_app) {
 							echo "<tr>";
 							echo "<td>";
 							echo $application_id = $single_app['id'];
 							echo "</td>";

 							echo "<td>";
 							echo $single_app['solution_name'];
 							echo "</td>";

 							echo "<td>";
 							$tags_string = $single_app['tags'];
			                if($tags_string)
			                {
			                  $tags_array = explode(',', $tags_string);
			                  foreach ($tags_array as $key => $tag_id) {
			                    echo '<span class="custom_badge badge rounded-pill ';
			                    echo $all_tags_master_array[array_search($tag_id, array_column($all_tags_master_array, 'id'))]['mode'];
			                     echo '">';
			                     echo $all_tags_master_array[array_search($tag_id, array_column($all_tags_master_array, 'id'))]['name'];
			                     echo '</span>';
			                  }
			                }
 							echo "</td>";

 							echo "<td>";
 							$reviewer_status_array = array_column($all_app_status_for_reviewer, 'reviewer');
 						$reviewer_status_key_array = array_column($all_app_status_for_reviewer, 'id');
 						$key_id = array_search($single_app['status'],$reviewer_status_key_array,true);
 						$current_application_status = $reviewer_status_array[$key_id];
 						echo $reviewer_status_array[$key_id];
 							echo "</td>";

 							echo "<td>";
 							echo "<a class='btn btn-info btn-sm' href='" .base_url("reviewer/directory/$application_id"). "' role='button' target='_blank'>View</a>";
 							echo "</td>";

 							echo "</tr>";
 						}
 			
 				?>
 					</tbody>
 				</table>
 				
 			</div>
 		</div>
 	</div>
 </div>
 		