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
 				<h2>Application Details</h2>
 				<table class="table solution_table table-sm">
 					<?php 
 					echo "<tr>";
 					echo "<td>Solution Name</td>";
 					echo "<td class='bold_column'>$solution_name</td>";
 					echo "</tr>";
 					echo "<tr>";
 					echo "<td>Application ID</td>";
 					echo "<td class='bold_column'>$application_id</td>";
 					echo "</tr>";
 					echo "<tr>";

 					echo "<td>Parent Application ID</td>";
 					echo "<td class='bold_column'>";
 					if($ind_application_data[0]['parent_id'])
 					{
 						echo $ind_application_data[0]['parent_id'];
 					}else{
 						echo "NA";
 					}
 					echo "</td>";

 					echo "</tr>";

 					echo "<tr>";

 					echo "<td>Status</td>";
 					echo "<td class='bold_column'>$current_application_status</td>";
 					echo "</tr>";
 					echo "<tr>";

 					echo "<td>Current L1 Reviewer</td>";
 					echo "<td class='bold_column'>";
 					if($ind_application_data[0]['current_l1'])
 					{
 						echo $ind_application_data[0]['l1_fname'];
 						echo " ";
 						echo $ind_application_data[0]['l1_lname'];
 					}else{
 						echo "NA";
 					}
 					echo "</td>";
 					echo "</tr>";
 					echo "<tr>";

 					echo "<td>Current L2 Reviewer</td>";
 					echo "<td class='bold_column'>";
 					if($ind_application_data[0]['current_l2'])
 					{
 						echo $ind_application_data[0]['l2_fname'];
 						echo " ";
 						echo $ind_application_data[0]['l2_lname'];
 					}else{
 						echo "NA";
 					}
 					echo "</td>";
 					echo "</tr>";
 					echo "<tr>";
 					echo "<td>Public URL</td>";
 					echo "<td class='bold_column'>";
 					echo anchor("a/" . $application_id . "", base_url("a/$application_id"), array('title' => base_url("a/$application_id"), 'class' => "",'target' => "_blank"));
 					echo "</td>";

 					echo "</tr>";
 					echo "<tr>";

 					echo "<td>Priority</td>";
 					echo "<td class='bold_column'>";
 					$tags_array = explode(",",$ind_application_data[0]['tags']);
 					if(in_array(1, $tags_array))
 					{
 						echo "Yes";
 					}else{
 						echo "No";
 					}
 					echo "</td>";
 					echo "</tr>";
 					echo "<tr>";

 					echo "<td>Clarifications Requested</td>";
 					echo "<td class='bold_column'>";
 					if($ind_application_data[0]['clarifications_days'])
 					{
 						echo "Yes";
 					}else{
 						echo "No";
 					}
 					echo "</td>";
 					echo "</tr>";
 					echo "<tr>";
 					

 					echo "<td>Date Created</td>";
 					echo "<td class='bold_column'>";
 					echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['added_on']));
 					echo " UTC";
 					echo "</td>";

 					echo "</tr>";

 					echo "<tr>";
 					echo "<td>Date Submitted</td>";
 					echo "<td class='bold_column'>";
 					if($ind_application_data[0]['submitted_on'])
 					{
 						echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['submitted_on']));
 						echo " UTC";
 					}else{
 						echo "NA";
 					}
 					echo "</td>";
 					echo "</tr>";
 					echo "<tr>";



 					echo "<td>Date L1 Review Started</td>";
 					echo "<td class='bold_column'>";
 					if($ind_application_data[0]['l1_assign_on'])
 					{
 						echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['l1_assign_on']));
 						echo " UTC";
 					}else{
 						echo "NA";
 					}
 					echo "</td>";
 					echo "</tr>";
 					echo "<tr>";

 					echo "<td>Date L1 Review Complete</td>";
 					echo "<td class='bold_column'>";
 					if($ind_application_data[0]['l1review_complete_on'])
 					{
 						echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['l1review_complete_on']));
 						echo " UTC";
 					}else{
 						echo "NA";
 					}
 					echo "</td>";
 					echo "</tr>";

 					echo "<tr>";

 					echo "<td>Date L2 Review Started</td>";
 					echo "<td class='bold_column'>";
 					if($ind_application_data[0]['l2_assign_on'])
 					{
 						echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['l2_assign_on']));
 						echo " UTC";
 					}else{
 						echo "NA";
 					}
 					echo "</td>";

 					echo "</tr>";
 					echo "<tr>";

 					echo "<td>Date L2 Review Completed</td>";
 					echo "<td class='bold_column'>";
 					if($ind_application_data[0]['review_complete_on'])
 					{
 						echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['review_complete_on']));
 						echo " UTC";
 					}else{
 						echo "NA";
 					}
 					echo "</td>";

 					echo "</tr>";
 					echo "<tr>";


 					echo "<td>Date of Expiry</td>";
 					echo "<td class='bold_column'>";
 					if($ind_application_data[0]['expire_on'])
 					{
 						echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['expire_on']));
 						echo " UTC";
 					}else{
 						echo "NA";
 					}
 					echo "</td>";

 					echo "</tr>";

 					?>
 					
 				</table>
 				
 				
 				<div class="accordion" id="accordion_application">
 					<div class="accordion-item">
 						<h2 class="accordion-header" id="flush-headingOne">
 							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
 								<Strong>Application Response & Reviewer Actions</Strong>
 							</button>
 						</h2>
 						<div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordion_application">
 							<div class="accordion-body">
 								<table class="table table-sm table-bordered">
 									<thead>
 										<tr>
 											<th>Section Name</th>
 											
 											<th>Filling Status</th>
 											<th>Notes</th>
 											<th>L1 Decision</th>
 											<th>L2 Decision</th>
 											<th>Applicant Response</th>
 										</tr>
 									</thead>
 									<tbody>
 										<?php 
 										$current_s_name = "";
 										$old_s_name = "";
 										$show_clarify_question = 1;
 										foreach ($all_application_response as $key => $single_response) {

 											if($current_s_name != $single_response['section_name'])
 											{
 												echo "<tr>";
 											echo "<td>";
 											echo $single_response['section_name'];
 											echo "</td>";

 											

 											echo "<td>";
 											if($single_response['filling_status'] == 1)
 											{
 												echo "Filled";
 											}else{
 												echo "Not Filled";
 											}
 											echo "</td>";

 											echo "<td>";
 											echo $single_response['notes'];
 											echo "</td>";

 											echo "<td>";
 											if($single_response['r1_status'] == 0)
 											{
 												echo "NA";
 											}else if ($single_response['r1_status'] == 1){
 												echo "Pass";
 											}else{
 												echo "Fail";
 											}
 											echo "</td>";

 											echo "<td>";
 											if($single_response['r2_status'] == 0)
 											{
 												echo "NA";
 											}else if ($single_response['r2_status'] == 1){
 												echo "Pass";
 											}else if ($single_response['r2_status'] == 2){
 												echo "Fail";
 											}else if ($single_response['r2_status'] == 3){
 												echo "Waiting for Clarifications";
 											}else if ($single_response['r2_status'] == 4){
 												echo "Under Consultation";
 											}
 											echo "</td>";


 											echo "<td>";
 											}

 											
 											echo "<font color='blue'>";
 											echo $single_response['q_name'];
 											
 											echo "</font>";
 											echo "<br>";
 											echo "<font color='gray' style='italic'>";
 											echo $single_response['q_description'];
 											echo "</font>";
 											echo "<br>";
 											echo $single_response['answer'];
 											echo "<br>";
 											echo "<br>";

 											if($single_response['clarify_question'] != "")
 											{
 												
 												if($show_clarify_question % 2 == 0)
 												{
 													echo "<font color='purple' style='italic'>";
		 											//echo "CQ: ";
		 											echo $single_response['clarify_question'];
		 											echo "</font>";
		 											echo "<br>";
		 											//echo "CA: ";
		 											echo $single_response['clarify_response'];
		 											echo "<br>";
 												}
 												$show_clarify_question = $show_clarify_question+1;
 											
 											}


 											

 											if($old_s_name == $single_response['section_name'])
 											{
												echo "</td>";
 												echo "</tr>";
 											}
 											
 											if($current_s_name != $single_response['section_name'])
 											{
 												$old_s_name = $current_s_name;
 											}
 											$current_s_name = $single_response['section_name'];
 										}


 										?>
 									</tbody>
 								</table>
 							</div>
 						</div>
 					</div>
 					<div class="accordion-item">
 						<h2 class="accordion-header" id="flush-headingTwo">
 							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
 								<strong>Application Log Details</strong>
 							</button>
 						</h2>
 						<div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordion_application">
 							<div class="accordion-body">
 								<table class="table table-sm table-bordered">
 									<thead>
 										<tr>
						        			<th>Timestamp</th>
						        			<th>Activity</th>
						        		</tr>
 									</thead>
 									<tbody>
 										<?php 
										foreach ($application_logs as $key => $log) {
											echo "<tr>";
											
											echo "<td>";
											echo date("d-m-Y h:i A", strtotime($log['perform_on']));
										                  echo " UTC";

											 echo "</td>";
											 echo "<td>"; echo $log['comment']; echo "</td>";
											echo "</tr>";
										}


        		?>
 									</tbody>
 								</table> <!-- log table end -->
 							</div> <!-- accordion-body end -->
 						</div><!-- accordion flush-collapseTwo end -->
 					</div><!-- accordion-item end -->
 				</div><!-- accordion_application end -->
 				
 			</div>
 		</div>
 	</div>
 </div>
