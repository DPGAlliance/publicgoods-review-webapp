 <div class="row">
 	<div class="col-md-12">
 		<?php 
 		echo $this->session->flashdata('message');  
 		?>
 	</div>
 </div>
 <div class="container-fluid px-0 pt-2">
 	<div class="row gx-3">
 		<div class="col-md-3">
 			<div class="custom_column_reviewer">
 				<div class="d-flex justify-content-between">
 					<div>
 						<?php
                        $input_id = 0;
 						$under_consultation_count = 0;
 						$total_passed = 0;
 						$total_failed = 0;
 						$total_clarifying = 0;
 						$total_pending = 0;
 						$total_ipr_found = 0;
 						$total_ipr_action = 0;
 						$total_ipr_pass = 0;
 						$total_input_asked = 0;
 						$total_input_submit = 0;
 						$input_disabled = 0;
 						$reviewer_status_array = array_column($all_app_status_for_reviewer, 'reviewer');
 						$reviewer_status_key_array = array_column($all_app_status_for_reviewer, 'id');
 						$key_id = array_search($ind_application_data[0]['status'],$reviewer_status_key_array,true);
 						$current_application_status = $reviewer_status_array[$key_id];
 						echo "<span>$reviewer_status_array[$key_id]</span>";
 						?>
 						
 						
 					</div>
 					<div class="pt-0">
 						<?php
 						if($application_status == 5 && $user_role == 3)
 						{
 							echo '<i class="bi bi-arrow-repeat"  style="font-size: 1.5rem; color: rgba(33, 33, 128, 1);" data-bs-toggle="modal" data-bs-target="#change_app_status_review"></i>';

 						}else if($application_status != 5 && $user_role == 3){
 							echo '<i class="bi bi-arrow-repeat"  style="font-size: 1.5rem; color: rgba(33, 33, 128, 1);" data-bs-toggle="modal" data-bs-target="#change_app_status_consultation"></i>';
 						}else{
 							
 						}


 						?>
 						


 					</div>
 				</div>
 				


 				<p style="font-size: 20px;"><?php echo $solution_name; ?><strong><br>
 					<?php echo $application_id; ?></strong>
 				</p>
 				<?php
 				$all_section_ids_array = array();
 				$filled_sections_count = 0;
 				foreach ($all_section_data_in_application as $key => $single_section) {
 					$is_this_filled = "";
 					
 					if($single_section['ipr'] == 1)
 					{
 						$total_ipr_found = $total_ipr_found + 1;
 					}
 					echo "<div class='left_side_menu_div'>";
 					array_push($all_section_ids_array, $single_section['id']);

 					if($user_role == 2)
 					{
 						$section_review_status = $single_section['r1_status'];
 					} else if($user_role == 3)
 					{
 						$section_review_status = $single_section['r2_status'];
 					}else if($user_role == 5){
 						// $section_review_status = 0;

 						if($single_section['consultant'] == "")
 						{
 							$section_review_status = 0;
                            
 						}else{
 							foreach ($consultant_response as $key => $single_data) {
 								
 								$single_data_response = $single_data['response'];

 								// if($single_section['id'])
 								


 								if($single_section['id'] == $single_data['section_id'] && $single_data['consultant_id'] == $user_id)
 								{
 									$total_input_asked = $total_input_asked+1;
 									if($single_data_response != "")
 									{
 										$section_review_status = 5;
 										$total_input_submit = $total_input_submit+1;
 										
 									}else{
 										$section_review_status = 4;	
 										
 									}
 								}






 								
 							}
 						}


 					}
 					
 					if($section_review_status == 4 && $application_status == 5){
 						echo '<i class="bi bi-circle-fill" style="font-size: 1.2rem; color: #C000AD;"></i> ';
 						$is_this_filled = "section_filled";
 						$filled_sections_count = $filled_sections_count + 1;
 						$under_consultation_count = $under_consultation_count+1;
 					}else if($section_review_status == 5 && $application_status == 5){
 						echo '<i class="bi bi-check-circle" style="font-size: 1.2rem; color: #C000AD;"></i> ';
 						$is_this_filled = "section_filled";
 						$filled_sections_count = $filled_sections_count + 1;
 					}else if ($section_review_status != 4 && $application_status == 5) {
 						echo '<i class="bi bi-circle" style="font-size: 1.2rem; color: rgba(0, 0, 0, 0.5);"></i> ';
 					}
 					if ($single_section['section_id'] == $current_section_id) {
 						echo "<strong class='left_menu_selected_item'><font color='#212180'>" . $single_section['name'] . "</font></strong>";
 					} else {
          // echo $single_section['name'];
 						$ipr_class = "";
 						if($total_ipr_pass != $total_ipr_found && $single_section['ipr'] == 0)
 						{
 							// $ipr_class = "ipr_disabled";
 						}
 						echo anchor("process/inputs/" . $single_section['id'] . "", $single_section['name'], array('title' => $single_section['name'], 'class' => "section_menu_links $is_this_filled $ipr_class"));
 						
 					}
 					echo "<br>";
 					echo "</div>";
 				}
 				echo "<br>";
 				if($application_status == 5 && $total_input_asked == $total_input_submit)
 				{
 					
 					// echo '<div class="d-grid gap-2"><button type="button" class="btn btn-primary mb-5" data-bs-toggle="modal" data-bs-target="#submit_confirmation">Submit Inputs</button></div>';
 				}else{
 					// echo '<div class="d-grid gap-2"><button type="button" class="btn btn-primary mb-5" data-bs-toggle="modal" data-bs-target="#submit_confirmation" disabled>Submit Inputs</button></div>';
 				}
 				
 				?>
 			</div>
 			
 			<div class="app_meta_data_column mt-3 mb-3">
 				
 				<a href="<?php echo base_url("expert"); ?>"><i class="bi bi-arrow-left" style="color: red;"></i> <font color="red">Exit</font></a>

 			</div>
 		</div>
 		<div class="col-md-6">
 			<div class="custom_column_reviewer">
 				<div id="edit_section" style="display:none">
 					<h3>
 						<?php
 						echo $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['heading'];
 						?>
 					</h3>
 					<p>
 						<?php echo $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['details']; ?>
 					</p>
 					<hr class="bg-secondary border-1 border-top border-secondary">

 					<p>
 						<?php
 						$all_question_ids_array = array();
 						$all_multiple_select_qids_array = array();
 						$all_required_qids_array = array();
 						echo form_open('process/form/update', 'id="dpga_form"');
 						$temp_source = 0;
 						$temp_destination = 0;
 						foreach ($all_section_questions as $key => $single_question) {
 							if($single_question['destination'])
 							{
 								$temp_source = $single_question['id'];
 								$temp_destination = $single_question['destination'];
 							}

        //get prefilled value if exists
 							$this_question_answer = null;
 							$already_answered_qids_array = array_column($all_section_answers, 'question_id');
 							$qid_key = array_search($single_question['id'], $already_answered_qids_array);
 							if (is_numeric($qid_key)) {
 								$this_question_answer = $all_section_answers[$qid_key]['answer'];
 							}
        //check is this question is required
 							if ($single_question['required'] == '*') {
 								array_push($all_required_qids_array, $single_question['id']);
 							}
        //handle input text
 							if ($single_question['type'] == 'text') {
 								echo "<div class='mb-5'>
 								<label for='" . $single_question['id'] . "' class='form-label'>" . $single_question['name'] . "" . $single_question['required'] . "</label>
 								<p class='q_description'>" . $single_question['description'] . "</p>
 								<input type='text' class='form-control";
 								if (in_array($current_section_id, $this->session->userdata('section_revision')) && $single_question['required'] == '*' && !isset($this_question_answer)) {
 									echo " is-invalid";
 								}
 								echo "' id='" . $single_question['id'] . "' name='" . $single_question['id'] . "'";
 								if (isset($this_question_answer)) {
 									echo "value='" . $this_question_answer . "'";
 								}
 								echo  "placeholder='" . $single_question['placeholder'] . "'>
 								</div>";
 								array_push($all_question_ids_array, $single_question['id']);
 							}
        //handle textbox
 							if ($single_question['type'] == 'textbox') {
 								echo "<div class='mb-5'>
 								<label for='" . $single_question['id'] . "' class='form-label'>" . $single_question['name'] . "" . $single_question['required'] . "</label>
 								<p class='q_description'>" . $single_question['description'] . "</p>
 								<textarea class='form-control";
 								if (in_array($current_section_id, $this->session->userdata('section_revision')) && $single_question['required'] == '*' && !isset($this_question_answer)) {
 									echo " is-invalid";
 								}
 								echo "' id='" . $single_question['id'] . "' name='" . $single_question['id'] . "' rows='" . $single_question['lineheight'] . "' placeholder='" . $single_question['placeholder'] . "'";
 								if (!isset($this_question_answer)) {
 									echo $single_question['disabled'];
 								}

 								echo ">";
 								if (isset($this_question_answer)) {
 									echo $this_question_answer;
 								}
 								echo "</textarea>
 								</div>";
 								array_push($all_question_ids_array, $single_question['id']);
 							}
        //handle signle select
 							if ($single_question['type'] == 'single_select') {
 								echo "<div class='mb-5'>
 								<label for='" . $single_question['id'] . "' class='form-label'>" . $single_question['name'] . "" . $single_question['required'] . "</label>";
 								echo "<p class='q_description'>" . $single_question['description'] . "</p>";
 								echo "<select class='form-select";
 								if (in_array($current_section_id, $this->session->userdata('section_revision')) && $single_question['required'] == '*' && !isset($this_question_answer)) {
 									echo " is-invalid";
 								}
 								echo "' aria-label='" . $single_question['id'] . "' id='" . $single_question['id'] . "' name='" . $single_question['id'] . "' onchange='manage_disable_actions(" . $single_question['id'] . ", " . $single_question['destination'] . ")'>";
 								$available_options_string = $single_question['options'];
 								$available_options_array = explode(",", $available_options_string);
 								echo "<option value=''>" . $single_question['placeholder'] . "</option>";

 								foreach ($available_options_array as $key => $select_option) {
 									echo "<option value='" . $select_option . "'";
 									if (isset($this_question_answer)) {
 										if ($this_question_answer == $select_option) {
 											echo "selected";
 										}
 									}
 									echo ">" . $select_option . "</option>";
 								}
 								echo "</select>";
 								echo "</div>";
 								array_push($all_question_ids_array, $single_question['id']);
 							}

        //handle multiple select
 							if ($single_question['type'] == 'multiple_select') {
 								echo "<div class='mb-5'>
 								<label for='" . $single_question['id'] . "' class='form-label'>" . $single_question['name'] . "" . $single_question['required'] . "</label>";
 								echo "<p class='q_description'>" . $single_question['description'] . "</p>";
 								echo "<select class='form-select";
 								if (in_array($current_section_id, $this->session->userdata('section_revision')) && $single_question['required'] == '*' && !isset($this_question_answer)) {
 									echo " is-invalid";
 								}
 								echo "' id='multiple-select-" .$single_question['id']. "' name='" .$single_question['id']. "[]' data-placeholder='" .$single_question['placeholder']. "' multiple";
 								if($single_question['id'] == 3)
 								{
              // echo 'onchange = manage_disable_actions()';
 								}

 								echo ">";

 								$available_options_string = $single_question['options'];
 								$available_options_array = explode(",", $available_options_string);
 								if($this_question_answer == '')
 								{
 									$this_question_answer_array = array();
 								}else{
 									$this_question_answer_array = explode(",", $this_question_answer);
 								}
 								foreach ($available_options_array as $key => $select_option) {
 									echo "<option value='" . $select_option . "'";
 									if (isset($this_question_answer)) {
 										if (in_array($select_option, $this_question_answer_array)) {
 											echo " selected";
 										}
 									}
 									echo ">" . $select_option . "</option>";
 								}
 								echo "</select>";

 								echo "</div>";
 								array_push($all_question_ids_array, $single_question['id']);
 								array_push($all_multiple_select_qids_array, $single_question['id']);
 							}
 						}
 						$all_question_ids_string = implode(",", $all_question_ids_array);
 						$all_section_ids_string = implode(",", $all_section_ids_array);
 						$all_required_qids_string = implode(",", $all_required_qids_array);
 						$all_required_qids_string = implode(",", $all_required_qids_array);
 						$all_multiple_select_qids_string = implode(",", $all_multiple_select_qids_array);

 						echo "<input type='hidden' name='all_question_ids_string' value='" . $all_question_ids_string . "'>";
 						echo "<input type='hidden' name='all_section_ids_string' value='" . $all_section_ids_string . "'>";
 						echo "<input type='hidden' name='current_section_id' value='" . $current_section_id . "'>";
 						echo "<input type='hidden' name='all_required_qids_string' value='" . $all_required_qids_string . "'>";
 						echo "<input type='hidden' name='all_multiple_select_qids_string' value='" . $all_multiple_select_qids_string . "'>";
 						echo '<div class="d-grid gap-2">
 						<button class="btn btn-primary" type="submit">Update</button>
 						</div>';
 						echo form_close();
 						?>
 					</p>
 				</div> <!-- edit_section end -->
 				<div id="view_section">
 					<strong><?php
 					echo $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['heading'];
 					if($user_role == 2)
 					{
 						$current_section_review_status = $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['r1_status'];
 					}else if($user_role == 3){
 						$current_section_review_status = $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['r2_status'];

 						$l1_review_status = $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['r1_status'];
 					}else if($user_role == 5){
 						$current_section_review_status = $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['r2_status'];

 						$l1_review_status = $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['r1_status'];
 					}
 					
 					?></strong>
 					<p>
 						<?php
 						foreach ($all_section_questions as $key => $single_question) {


        //get prefilled value if exists
 							$this_question_answer = null;
 							$already_answered_qids_array = array_column($all_section_answers, 'question_id');
 							$qid_key = array_search($single_question['id'], $already_answered_qids_array);
 							if (is_numeric($qid_key)) {
 								$this_question_answer = $all_section_answers[$qid_key]['answer'];
 							}
        //check is this question is required
 							if ($single_question['required'] == '*') {
 								array_push($all_required_qids_array, $single_question['id']);
 							}
 							echo "<div class='reviewer_q_section'>";
 							echo "<div class='reviewer_q_details'>";
 							echo $single_question['name'];
 							echo "</div>";
 							echo "<div class='reviewer_q_answer'>";
 							echo $this_question_answer;
 							echo "</div>";
 							echo "</div>";
 						}


 						?>
 					</p>
 				</div> <!-- view_section end -->
 				
 			</div><!-- custom_column_reviewer end -->
 			<?php
 			if($all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['clarify_question']){
 				echo '<div id="clarification_section" class="custom_column_reviewer mt-3">';
 				echo '<strong>CLARIFICATION</strong>';
 				echo "<div class='reviewer_q_section'>";
 				echo "<div class='reviewer_q_details'>";
 				echo $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['clarify_question'];
 				echo "</div>";
 				echo "<div class='reviewer_q_answer'>";
 				echo $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['clarify_response'];
 				echo "</div>";
 				echo "</div>";
 				echo '</div><!-- clarification_section end -->';
 			}

 			?>
 		</div>

 		<div class="col-md-3">
 			<div class="custom_column_reviewer" 
 			<?php
 			if($current_section_review_status == 5 && $application_status == 5)
 			{
 				echo 'style="display: none;"';
 			}
 			 ?>>
 				<div class="d-flex justify-content-between">
 					<div>
 						<label for="options-outlined" class="form-label">
 							YOUR INPUT</label>
 						</div>
 						<div>
 							
 						</div>
 					</div>
 					<div>
 						<?php
 							
 							//echo $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['consultant_question'];

 							 ?>
 					</div>
 					<?php
 					echo form_open('process/inputs/decision', 'id="dpga_decision_form"');
 					?>
 					<?php
 					
 						
 							?>
 						
 					<div class="row">

 						<div class="col-md-12 pt-2">
 							<textarea class="form-control" id="expert_input" name="expert_input" rows="8" oninput="return enable_save_button();" placeholder="Add your input" <?php if(count($consultant_response_section_wise) == 0){echo "disabled";} ?>><?php
                            foreach ($consultant_response_section_wise as $key => $single_response) {
                                
                                if($single_response['consultant_id'] == $user_id)
                                {
                                    echo $single_response['response'];
                                    $input_id = $single_response['id'];
                                }
                            }
                             ?></textarea>
                             <input type="hidden" name="input_id" value="<?php echo $input_id; ?>">
                             <div class="d-grid gap-2 mt-3">
 							<button class="btn btn-primary" id="save_decision" name="save_decision" type="submit" disabled>
                                Send Input</button>
                            </div>
 						</div>
 					</div>
 					
 			
 			<?php 
 			if($current_section_review_status != 4)
 			{
 				?>
 				<div class="row">
 					<div class="col-md-12 pt-4">
 						<div class="d-grid gap-2">
 							<input type="hidden" name="current_section_id" id="csi" value="<?php echo $current_section_id; ?>">
 							<?php echo "<input type='hidden' name='all_section_ids_string' value='" . $all_section_ids_string . "'>"; ?>
 							
 							</div>
 						</div>
 					</div>
 				<?php } ?>
 				<?php echo form_close(); ?>
 			</div>

 			<?php
 			if($all_section_data_in_application[$current_section_id - 1]['consultant'])
 				{ ?>

 				
 						</div>
 						
 					</div>


 				<?php }

 				?>

 				
 			</div>
 		</div>
 	</div>




 	<?php 
 	if($user_role == 3)
 	{

 		?>
 		<!-- Under Consultation Modal -->
 		<div class="modal fade" id="change_app_status_consultation" tabindex="-1" aria-labelledby="change_app_status_label" aria-hidden="true">
 			<div class="modal-dialog">
 				<div class="modal-content">
 					<div class="modal-header">
 						<h5 class="modal-title" id="change_app_status_label">Move application to Under Consultation?</h5>
 						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
 					</div>
 					<div class="modal-body">
 						<ul>
 							<li>You will not be able to make new decisions or change old decisions.</li>
 							<li>Decisions and requests for clarifications will be preserved and available when you move application back to Under Review.</li>
 							<li>The timer will be paused until you move application back to Under Review.</li>
 						</ul>
 					</div>
 					<div class="modal-footer">
 						<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
 						<?php echo form_open('process/update/consultation', 'id="dpga_update_consultation"'); ?>
 						<input type="hidden" name="change_status" value="change_status">
 						<button type="submit" class="btn btn-primary">Move</button>
 						<?php 
 						echo form_close();
 						?>
 					</div>
 				</div>
 			</div>
 		</div><!--End Under Consultation Modal -->

 		<?php
 	}
 	?>


 	<?php 
 	if($application_status == 5)
 	{

 		?>
 		<!-- Under Consultation Modal -->
 		<div class="modal fade" id="change_app_status_review" tabindex="-1" aria-labelledby="change_app_status_review_label" aria-hidden="true">
 			<div class="modal-dialog">
 				<div class="modal-content">
 					<div class="modal-header">
 						<h5 class="modal-title" id="change_app_status_review_label">Move application to Under Review?</h5>
 						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
 					</div>
 					<div class="modal-body">
 						<ul>
 							<li>All indicators must be unmarked to move the application to Under Review.
 							</li>
 							<li>The timer will restart when you move the application to Under Review</li>
 						</ul>
 					</div>
 					<div class="modal-footer">
 						<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
 						<?php echo form_open('process/update/underreview', 'id="dpga_update_underreview"'); ?>
 						<input type="hidden" name="change_status" value="change_status">
 						<button type="submit" class="btn btn-primary" <?php if($under_consultation_count > 0){echo 'disabled';} ?> >Move</button>
 						<?php 
 						echo form_close();
 						?>
 					</div>
 				</div>
 			</div>
 		</div><!--End Under Consultation Modal -->

 		<?php
 	}
 	?>



 	<div class="modal fade" id="submit_confirmation" tabindex="-1" aria-labelledby="submit_confirmation_label" aria-hidden="true">
 		<div class="modal-dialog">
 			<div class="modal-content">
 				<div class="modal-header">
 					<h5 class="modal-title" id="submit_confirmation_label">Please check and confirm.</h5>
 					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
 				</div>
 				<div class="modal-body">
 					<?php echo form_open('process/submit/inputs', 'id="dpga_submit_inputs"'); ?>
 					<div class="modal_popup_para">SUMMARY</div>
 					<div>
 						<?php echo $total_input_asked; ?> Input asked &nbsp;&nbsp;&nbsp; 
 						<?php echo $total_input_submit; ?> Input submit &nbsp;&nbsp;&nbsp; 
 						<?php 
 						if($user_role == 3)
 						{
 							echo "$total_clarifying Clarifying";
 							echo '&nbsp;&nbsp;&nbsp;';
 						} 
 						?>
 					</div>
 					</div>
 				<div class="modal-footer">
 					<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
 					<?php 
 				// echo "total_ipr_found = $total_ipr_found<br>";
 				// echo "total_ipr_action = $total_ipr_action<br>";
 				// echo "total_ipr_pass = $total_ipr_pass<br>";
 				// echo "total_pending = $total_pending<br>";


 					?>
 					<input type="hidden" name="change_status" value="change_status">
 					<button type="submit" class="btn btn-primary" <?php
				//if all 4 action done
				//if yes, then all 4 pass
				//then rest condition 





				
				?> >Submit Inputs</button>
				<?php 
				echo form_close();
				?>
			</div>
		</div>
	</div>
</div><!--End submit_confirmation Modal -->


<!-- To warn user on page switch without save -->
<script type="text/javascript">
  // Store form state at page load
  var initial_form_state = $('#dpga_form').serialize();
  // Store form state after form submit
  $('#dpga_form').submit(function() {
  	initial_form_state = $('#dpga_form').serialize();
  });
  // Check form changes before leaving the page and warn user if needed
  $(window).bind('beforeunload', function(e) {
  	var form_state = $('#dpga_form').serialize();
  	if (initial_form_state != form_state) {
  		var message = "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
      e.returnValue = message; // Cross-browser compatibility (src: MDN)
      return message;
  }
});
</script>

<?php 
if ($filled_sections_count == count($all_section_ids_array)) {
	?>
	<!-- To handle final_submit -->
	<script type="text/javascript">
		var terms_1 = document.getElementById('terms_1');
		terms_1.addEventListener('click', checked, false);
		var terms_2 = document.getElementById('terms_2');

		terms_2.addEventListener('click', checked, false);

		function checked() {

			if (terms_1.checked && terms_2.checked) {
				document.getElementById('final_submit').removeAttribute('disabled');
				document.getElementById('final_submit').removeAttribute('class');
				document.getElementById('final_submit').setAttribute('class', 'btn btn-primary');
			} else {
				document.getElementById('final_submit').removeAttribute('class');
				document.getElementById('final_submit').setAttribute('class', 'btn btn-outline-secondary disabled');
				document.getElementById('final_submit').setAttribute('disabled', 'disabled');
			}

		}
	</script>
<?php } ?>

<!-- Initialize the plugin: -->

<script type="text/javascript">
	<?php 
	foreach ($all_multiple_select_qids_array as $key => $multiple_question_id) {

		echo "$( '#multiple-select-";
		echo $multiple_question_id;
		echo "' ).select2( {";
		echo 'theme: "bootstrap-5",';
		echo "width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
		placeholder: $( this ).data( 'placeholder' ),
		closeOnSelect: false,";
		echo '} );';
	}
	?>

	<?php 
	if($application_status == 5)
	{
		echo "$( '#consultant";
		echo "' ).select2( {";
		echo 'theme: "bootstrap-5",';
		echo "width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
		placeholder: $( this ).data( 'placeholder' ),
		closeOnSelect: false,";
		echo '} );';

		
		echo "$( '#new_consultant_list";
		echo "' ).select2( {";
		echo 'theme: "bootstrap-5",';
		echo "width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
		placeholder: $( this ).data( 'placeholder' ),
		closeOnSelect: false,";
		echo '} );';
	}
	?>
</script>

<script type="text/javascript">
	<?php 
	if($current_section_id == SECTION_ID)
	{
		?>
		const multiselect_field_id = "#multiple-select-<?php echo MULTI_SELECT_FIELD_ID; ?>";
		$(document.body).on("change",multiselect_field_id,function(){
			var selected_values_array = $(multiselect_field_id).val();
			const solution_code_repo = <?php echo SOLUTION_CODE_REPO_FIELD_ID; ?>;
			const software_field_selected = selected_values_array.includes('Open Software');
			if(software_field_selected){
				document.getElementById(solution_code_repo).setAttribute('required', 'required');
				var current_value = document.getElementById(solution_code_repo).value;
				if(current_value == '')
				{
					document.getElementById(solution_code_repo).setAttribute('class', 'form-control is-invalid');
				}else{
					document.getElementById(solution_code_repo).removeAttribute('class');
					document.getElementById(solution_code_repo).setAttribute('class', 'form-control');
				}

			}else {
				document.getElementById(solution_code_repo).removeAttribute('required');
			}
		});
	<?php } ?>  
</script>


<script type="text/javascript">
	function manage_disable_actions(source_id, destination_id)
	{
		if(source_id == 15 || source_id == 18 || source_id == 28)
		{
			var current_value = document.getElementById(source_id).value;
			if(current_value == 'Yes')
			{
				document.getElementById(destination_id).removeAttribute('disabled');
				document.getElementById(destination_id).setAttribute('required', 'required');
				var destination_value = document.getElementById(destination_id).value;
				if(destination_value == '')
				{
					document.getElementById(destination_id).setAttribute('class', 'form-control is-invalid');
				}else{
					document.getElementById(destination_id).removeAttribute('class');
					document.getElementById(destination_id).setAttribute('class', 'form-control');
				}
			}else{
				document.getElementById(destination_id).removeAttribute('class');
				document.getElementById(destination_id).setAttribute('class', 'form-control');
				document.getElementById(destination_id).removeAttribute('required');
				document.getElementById(destination_id).setAttribute('disabled', 'disabled');
			}
		}

		if(source_id == 22)
		{
			var current_value = document.getElementById(source_id).value;
			var privacy_field_id = <?php echo PRIVACY_FIELD_ID; ?>;
			if(current_value != 'PII data is NOT collected NOT stored and NOT distributed.')
			{
				document.getElementById(destination_id).removeAttribute('disabled');
				document.getElementById(privacy_field_id).removeAttribute('disabled');
				document.getElementById(destination_id).setAttribute('required', 'required');
				document.getElementById(privacy_field_id).setAttribute('required', 'required');
			}else{
				document.getElementById(destination_id).setAttribute('disabled', 'disabled');
				document.getElementById(privacy_field_id).setAttribute('disabled', 'disabled');
				document.getElementById(destination_id).removeAttribute('required');
				document.getElementById(privacy_field_id).removeAttribute('required');
			}
		}

		if(source_id == 25)
		{
			var current_value = document.getElementById(source_id).value;
			var solution_content_id = <?php echo SOLUTION_CONTENT_ID; ?>;
			if(current_value != 'Content is NOT collected NOT stored and NOT distributed.')
			{
				document.getElementById(destination_id).removeAttribute('disabled');
				document.getElementById(solution_content_id).removeAttribute('disabled');
				document.getElementById(destination_id).setAttribute('required', 'required');
				document.getElementById(solution_content_id).setAttribute('required', 'required');
			}else{
				document.getElementById(destination_id).setAttribute('disabled', 'disabled');
				document.getElementById(solution_content_id).setAttribute('disabled', 'disabled');
				document.getElementById(destination_id).removeAttribute('required');
				document.getElementById(solution_content_id).removeAttribute('required');
			}
		}
	}
</script>


<script type="text/javascript">
	<?php 
	if($temp_source > 0)
	{
		?>
		$( document ).ready(function() {
			var source_id = <?php echo $temp_source; ?>;
			if (document.getElementById(source_id))
			{
				var destination_id = <?php echo $temp_destination; ?>;
				var current_value = document.getElementById(source_id).value;
				if(current_value == 'Yes')
				{
					document.getElementById(destination_id).removeAttribute('disabled');
					document.getElementById(destination_id).setAttribute('required', 'required');
					var destination_value = document.getElementById(destination_id).value;
					if(destination_value == '')
					{
						document.getElementById(destination_id).setAttribute('class', 'form-control is-invalid');
					}else{
						document.getElementById(destination_id).removeAttribute('class');
						document.getElementById(destination_id).setAttribute('class', 'form-control');
					}
				}
			}
		});
	<?php } ?>
</script>

<script type="text/javascript">
	$( document ).ready(function() {
		var source_id = <?php echo $temp_source; ?>; 
		var destination_id = <?php echo $temp_destination; ?>;
		if (document.getElementById(source_id))
		{
			var current_value = document.getElementById(source_id).value;
			if(source_id == 22)
			{
				var privacy_field_id = <?php echo PRIVACY_FIELD_ID; ?>;
				if(current_value != 'PII data is NOT collected NOT stored and NOT distributed.')
				{
					if(current_value != '')
					{
						document.getElementById(destination_id).removeAttribute('disabled');
						document.getElementById(privacy_field_id).removeAttribute('disabled');
						document.getElementById(destination_id).setAttribute('required', 'required');
						document.getElementById(privacy_field_id).setAttribute('required', 'required');
					}
				}
			}else if(source_id == 25){
				var solution_content_id = <?php echo SOLUTION_CONTENT_ID; ?>;
				if(current_value != 'Content is NOT collected NOT stored and NOT distributed.')
				{
					if(current_value != '')
					{
						document.getElementById(destination_id).removeAttribute('disabled');
						document.getElementById(solution_content_id).removeAttribute('disabled');
						document.getElementById(destination_id).setAttribute('required', 'required');
						document.getElementById(solution_content_id).setAttribute('required', 'required');
					}
				}
			}
		}
	});
</script>

<script type="text/javascript">
	

	
	function enable_edit_experts()
	{
		$("#enable_edit_experts").hide();
		$("#enable_save_experts").show();
		$("#add_edit_experts").show();
		$("#cancel_save_experts").show();

	}

	function cancel_save_experts()
	{
		$("#cancel_save_experts").hide();
		$("#enable_save_experts").hide();
		$("#add_edit_experts").hide();
		$("#enable_edit_experts").show();
		
		
	}

	
	function enable_save_button()
	{
		$('#expert_input').attr('required', 'required');
		$("#save_decision").removeAttr("disabled");	
	}



	
</script>

<script type="text/javascript">
	$('input:radio[name="review_decision"]').change(function(){
		if($(this).is(":checked")){
			$("#save_decision").removeAttr('disabled');
		}
		var current_selection_value = $('input[name="review_decision"]:checked').val();
		if(current_selection_value == 3){
			$("#clarify_question_div").show();
			$('#clarify_question').attr('required', 'required');
		}else{
			$('#clarify_question').removeAttr('required');
			$('#clarify_question').val("");
			$("#clarify_question_div").hide();
		}
	});


	<?php
	if($application_status == 5)
	{
		echo '$("#consultant").on("change", function () {
			$("#save_decision").removeAttr("disabled");
		});';
	} ?>
</script>