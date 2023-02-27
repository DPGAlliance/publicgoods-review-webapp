<h3>All Applications List</h3>
		
				<table class="table" id="list_applications">
					<thead>
						<tr>
							<th>Application ID</th>
            <th>Name</th>
            <th>Tags</th>
            <th>Review Status</th>
            <th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php

						$status_array = array('0' => 'Deactive',
							'1' => 'Active');				
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
              $reviewer_status_array = array_column($all_app_status_for_admin, 'reviewer');
            $reviewer_status_key_array = array_column($all_app_status_for_admin, 'id');
            $key_id = array_search($single_app['status'],$reviewer_status_key_array,true);
            $current_application_status = $reviewer_status_array[$key_id];
            echo $reviewer_status_array[$key_id];
              echo "</td>";

              echo "<td>";
              echo "<a class='btn btn-info btn-sm' href='" .base_url("admin/application/$application_id"). "' role='button'>See Details</a>";
              echo "</td>";

              echo "</tr>";
            }


						 ?>
					</tbody>
				</table>

	</div>
</div><!-- col-10 end -->
</div><!-- row end -->
</div><!-- container-fluid end -->



