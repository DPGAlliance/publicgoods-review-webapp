 <div class="row">
 </div>
 <div class="container-fluid px-0 pt-2">
 	<div class="row gx-3">
 		<div class="col-md-12">
 			<div class="custom_column_reviewer table-responsive">
 				<?php echo "<h2>$solution_name</h2>"; ?>

 										<?php 
 										$current_s_name = "";
 										$old_s_name = "";
 										$show_clarify_question = 1;
 										foreach ($all_application_response as $key => $single_response) {

 											if($current_s_name != $single_response['section_name'])
 											{
 												echo "<div class='section_data'>";
 											echo "<div class='public_section_heading'>";
 											echo $single_response['section_name'];
 											echo "</div>";


 											echo "<div class='public_section_response_data'>";
 											}
 											
 											echo "<font color='black' style='font-weight: bold;'>";
 											echo $single_response['q_name'];
 											
 											echo "</font>";
 											//echo "<br>";
 											//echo "<font color='gray' style='italic'>";
 											//echo $single_response['q_description'];
 											//echo "</font>";
 											echo "<br>";
 											echo $single_response['answer'];
 											echo "<br>";
 											echo "<br>";
 											if($single_response['clarify_question'] != "")
 											{
 												
 												if($show_clarify_question % 2 == 0)
 												{
 													echo "<font color='black' style='font-weight: bold;'>";
		 											//echo "CQ: ";
		 											echo $single_response['clarify_question'];
		 											echo "</font>";
		 											echo "<br>";
		 											//echo "CA: ";
		 											echo $single_response['clarify_response'];
		 											echo "<br>";
		 											echo "<br>";
 												}
 												$show_clarify_question = $show_clarify_question+1;
 											
 											}
 											


 											

 											if($old_s_name == $single_response['section_name'])
 											{
												echo "</div>";
 												echo "</div>";
 												echo "<br>";
 											}
 											
 											if($current_s_name != $single_response['section_name'])
 											{
 												$old_s_name = $current_s_name;
 											}
 											$current_s_name = $single_response['section_name'];
 										}


 										?>
 			<h2>Application Details</h2>
 			<table class="table solution_table">
 					<?php 
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


 					echo "<td>Date Reviewed</td>";
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

 			<h2>Application Log Details</h2>
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
 								</table>
 				
 			</div>
 		</div>
 	</div>
 </div>
