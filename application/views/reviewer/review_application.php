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
 						$under_consultation_count = 0;
 						$total_passed = 0;
 						$total_failed = 0;
 						$total_clarifying = 0;
 						$total_pending = 0;
 						$total_ipr_found = 0;
 						$total_ipr_action = 0;
 						$total_ipr_pass = 0;
 						$clarifications_submit = $ind_application_data[0]['clarifications_submit'];
 						$reviewer_status_array = array_column($all_app_status_for_reviewer, 'reviewer');
 						$reviewer_status_key_array = array_column($all_app_status_for_reviewer, 'id');
 						$key_id = array_search($ind_application_data[0]['status'],$reviewer_status_key_array,true);
 						$current_application_status = $reviewer_status_array[$key_id];
 						echo "<span>$reviewer_status_array[$key_id]</span>";
 						?>
 						
 						<?php
 						if($show_timer)
 						{
 							
 							if($user_role == 2)
 							{
 								$limit_in_hours = $dpga_limits[0]['l1review'];
 								$assign_on = $ind_application_data[0]['l1_assign_on'];
                $consultation_duration = 0;
 							}else if ($user_role == 3)
 							{
 								$limit_in_hours = $dpga_limits[0]['l2review'];
 								$assign_on = $ind_application_data[0]['l2_assign_on'];
                $consultation_duration = $ind_application_data[0]['consultation_duration'];
 							}

 							$allow_till = strtotime("+" .$limit_in_hours. " hours", strtotime($assign_on));
              
              $allow_till = $allow_till + $consultation_duration;
 							$time_remaining = $allow_till - strtotime("now");
 							$allow_till_datetime = new DateTime(date("Y-m-d h:i A", $allow_till));
 							
 							if($time_remaining > 0)
 							{
 								$time_badge_class = "bg-secondary";
 							}else{
			                    $time_badge_class = "bg-danger";
			                  }


 							echo "<span class='badge $time_badge_class'>";
 							
 							
 							$diff = $allow_till_datetime->diff(new DateTime()); 
 							echo $diff->h.' h ';
 							echo $diff->i.' m'; 


 							echo '</span>';
 						}

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
 					}
 					
 					if ($section_review_status == 0 && $application_status != 5) {
 						echo '<i class="bi bi-circle" style="font-size: 1.2rem; color: rgba(0, 0, 0, 0.5);"></i> ';
 						$total_pending = $total_pending+1;
 					} else if($section_review_status == 1 && $application_status != 5) {
 						echo '<i class="bi bi-circle-fill" style="font-size: 1.2rem; color: #00A023;"></i> ';
 						$is_this_filled = "section_filled";
 						$filled_sections_count = $filled_sections_count + 1;
 						$total_passed = $total_passed+1;
 						if($single_section['ipr'] == 1)
 						{
 							$total_ipr_action = $total_ipr_action+1;
 							$total_ipr_pass = $total_ipr_pass+1;
 						}
 						
 					} else if($section_review_status == 2 && $application_status != 5){
 						echo '<i class="bi bi-circle-fill" style="font-size: 1.2rem; color: #DC2626;"></i> ';
 						$is_this_filled = "section_filled";
 						$filled_sections_count = $filled_sections_count + 1;
 						$total_failed = $total_failed+1;
 						if($single_section['ipr'] == 1)
 						{
 							$total_ipr_action = $total_ipr_action+1;
 						}
 					} else if($section_review_status == 3 && $application_status != 5){
 						echo '<i class="bi bi-circle-fill" style="font-size: 1.2rem; color: #FFC700;"></i> ';
 						$is_this_filled = "section_filled";
 						$filled_sections_count = $filled_sections_count + 1;
 						$total_clarifying = $total_clarifying+1;
 						if($single_section['ipr'] == 1)
 						{
 							$total_ipr_action = $total_ipr_action+1;
 						}
 					}else if($section_review_status == 4 && $application_status != 5){
 						echo '<i class="bi bi-circle-fill" style="font-size: 1.2rem; color: #C000AD;"></i> ';
 						$is_this_filled = "section_filled";
 						$filled_sections_count = $filled_sections_count + 1;
 					}else if($section_review_status == 5 && $application_status != 5){
 						echo '<i class="bi bi-circle" style="font-size: 1.2rem; color: rgba(0, 0, 0, 0.5);"></i> ';
 						$is_this_filled = "section_filled";
 						$filled_sections_count = $filled_sections_count + 1;
 						$total_pending = $total_pending+1;
 					}
 					else if($section_review_status == 4 && $application_status == 5){
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
 						echo anchor("process/" . $single_section['id'] . "", $single_section['name'], array('title' => $single_section['name'], 'class' => "section_menu_links $is_this_filled $ipr_class"));
 						
 					}
 					echo "<br>";
 					echo "</div>";
 				}
 				echo "<br>";
 				if($application_status == 5)
 				{
 					//echo '<div class="d-grid gap-2"><button type="button" class="btn btn-primary mb-5" data-bs-toggle="modal" data-bs-target="#submit_confirmation" disabled>Submit Review</button></div>';
 				}else{
 					echo '<div class="d-grid gap-2"><button type="button" class="btn btn-primary mb-5" data-bs-toggle="modal" data-bs-target="#submit_confirmation">Submit Review</button></div>';
 				}
 				
 				?>
 			</div>
 			<div class="app_meta_data_column mt-3 mb-3">
 				<div class="modal_popup_para">MORE DETAILS</div>
 				<p>
 					<a href="#" data-bs-toggle="modal" data-bs-target="#app_meta_data_modal">Application Metadata</a><br>
 					<a href="#" data-bs-toggle="modal" data-bs-target="#audit_log_modal">Application Audit Log</a>
 				</p>
 			</div>
 			<div class="app_meta_data_column mt-3 mb-3">
 				
 				<a href="<?php echo base_url("reviewer"); ?>"><i class="bi bi-arrow-left" style="color: red;"></i> <font color="red">Exit</font></a>

 			</div>
 		</div>
 		<div class="col-md-6">
 			<div class="custom_column_reviewer">
 				<div id="edit_section" style="display:none">
 					<p class="text-end">
 						<a href="#" onclick="return show_view_section();">Cancel</a>
 					</p>
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
 					<p class="text-end"><a href="#" onclick="return show_edit_section();">Edit</a></p>
 					<strong><?php
 					echo $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['heading'];
 					if($user_role == 2)
 					{
 						$current_section_review_status = $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['r1_status'];
 					}else if($user_role == 3){
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
 							<?php 
 							if($application_status != 5){
 								echo 'REVIEW DECISION';
 							}else if($application_status == 5 && $current_section_review_status == 4){
 								echo 'FINISH CONSULTATION';
 							}else if($application_status == 5){
 								echo 'REQUEST CONSULTATION';
 							}else{
 								echo 'REVIEW DECISION';
 							}
 							?></label>
 							<?php

 							if($user_role == 3 && $application_status != 5)
 							{
 								echo "<div class='mb-3'>L1 Decision :";
 								if($l1_review_status == 1)
 								{
 									echo " Pass";
 								}else if($l1_review_status == 2)
 								{
 									echo " Fail";
 								}else{
 									echo " No Action Found";
 								}
 								echo "</div>";
 							}

 							 ?>
 							
 							
 						</div>
 						<div>
 							<a href="#" id="reset_review_decision_button" onclick="return reset_review_decision();"><?php
 							if($application_status != 5){
 								echo 'Reset';
 							}else{
 								// echo 'Unmark';
 							}
 							?></a>
 						</div>
 					</div>
 					<?php
 					echo form_open('process/review/decision', 'id="dpga_decision_form"');
 					?>
 					<?php
 					if($application_status != 5)
 					{
 						?>
 						<div class="row">
 							<div class="col-md-6">
 								<input type="radio" class="btn-check" name="review_decision" id="pass" value="1" autocomplete="off" 
 								<?php 
 								if($current_section_review_status == 1)
 								{
 									echo "checked";
 								}
 								?>
 								>
 								<label class="btn btn-outline-success result_indicator" for="pass" style="width: 100%">Pass</label>
 							</div>
 							<div class="col-md-6">
 								<input type="radio" class="btn-check" name="review_decision" id="fail" value="2" autocomplete="off" 
 								<?php if($current_section_review_status == 2)
 								{
 									echo "checked";
 								}
 								?>
 								>
 								<label class="btn btn-outline-danger result_indicator" for="fail" style="width: 100%">Fail</label>
 							</div>
 						</div>
 						<?php
 						if($user_role == 3 && $ind_application_data[0]['clarifications_submit'] == 0)
 						{
 							?>
 							<div class="row">
 								<div class="col-md-12 pt-2">
 									<input type="radio" class="btn-check" name="review_decision" id="clarify" value="3" autocomplete="off" 
 									<?php if($current_section_review_status == 3)
 									{
 										echo "checked";
 									}
 									?>
 									>
 									<label class="btn btn-outline-warning result_indicator_clarify" for="clarify" style="width: 100%">Clarify</label>
 								</div>
 								<div class="col-md-12 pt-3" id="clarify_question_div"
 								<?php
 								if($current_section_review_status != 3)
 									{
 										echo 'style="display: none;"';
 									}
 								 ?>
 								 >
 									<label for="clarify_question" class="form-label">CLARIFICATION REQUEST*</label>
 									<textarea class="form-control" oninput="return enable_save_button();" id="clarify_question" name="clarify_question" rows="4" ><?php
 									if($all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['clarify_question'])
 										{ echo $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['clarify_question'];
 									} ?></textarea>
 								</div>
 							</div>
 						<?php } ?>
 						<?php 
 					}else{
 						if($current_section_review_status != 4)
 						{
 							?>
 						<!--
 						<div class="row">
 							<div class="col-md-12 pt-2">
 								<input type="radio" class="btn-check" name="review_decision" id="consultation" value="4" autocomplete="off" 
 								<?php if($current_section_review_status == 4)
 								{
 									echo "checked";
 								}
 								?>
 								>
 								<label class="btn result_indicator_consultation" for="consultation" style="width: 100%"><?php if($current_section_review_status == 4)
 								{
 									echo "Marked for Consultation";
 								} else {
 									echo "Mark for Consultation";
 								}
 								?></label>
 							</div>
 						</div>
 					-->
 					<div class="row">
 						<div class="col-md-12 pt-2">
 							<textarea class="form-control mb-3" id="consultation_reason" name="consultation_reason" rows="4" placeholder="Enter reason for consultation" required></textarea>
 							<select class="form-select" name="consultant[]" id="consultant" data-placeholder='Select expert(s)' multiple required>
 								<?php
 								foreach ($consultant_list as $key => $consultant) {
 									echo '<option value="';
 									echo $consultant['id'];
 									echo '">';
 									echo $consultant['fname'];
 									echo " ";
 									echo $consultant['lname'];
 									echo '</option>';
 								}
 								?>
 							</select>
 						</div>
 					</div>
 					<input type="hidden" name="review_decision" value="4">
 				<?php }else{
 				//FINISH CONSULTATION BUTTON
 					echo '<textarea class="form-control mb-3" id="consultation_insight" name="consultation_insight" rows="4" placeholder="Enter insight(s) from this consultation" required></textarea>';
 					echo '<input type="hidden" name="review_decision" value="5">';
 					echo '<input type="hidden" name="clarify_question" value="">';
 					echo "<input type='hidden' name='current_section_id' value='$current_section_id'>";
 					echo '<div class="d-grid gap-2 mt-3">';
 					//echo '<a class="btn " href="';
 					//echo base_url("process/finish/$current_section_id");
 					//echo '" role="button">Finish Consultation</a>';
 					echo '<button class="btn finish_consultation" id="save_decision" name="save_decision" type="submit">
 								Finish Consultation</button>';
 					echo '</div>';
 				}
 			}
 			?>
 			<?php 
 			if($current_section_review_status != 4)
 			{
 				?>
 				<div class="row">
 					<div class="col-md-12 pt-4">
 						<div class="d-grid gap-2">
 							<input type="hidden" name="current_section_id" id="csi" value="<?php echo $current_section_id; ?>">
 							<?php echo "<input type='hidden' name='all_section_ids_string' value='" . $all_section_ids_string . "'>"; ?>
 							<button class="btn btn-primary" id="save_decision" name="save_decision" type="submit" disabled>
 								<?php 
 								if($application_status == 5 & $section_review_status == 4)
 								{
 									echo 'Finish';
 								} else if($application_status == 5 & $section_review_status != 4)
 								{
 									echo 'Send Request';
 								}else {
 									echo 'Save';
 								}
 								?></button>
 							</div>
 						</div>
 					</div>
 				<?php } ?>
 				<?php echo form_close(); ?>
 			</div>

 			<?php 


 			if($all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['consultation_insight']){
 				?>

 				<div class="custom_column_reviewer mt-3">
 					
 					
 					<div class="d-flex justify-content-between">
 						<div>
 							<label for="section_notes" class="form-label">CONSULTATION INSIGHTS</label>
 						</div>
 						
 					</div>
 					<div class="mb-3">
 						<p>
 							<?php echo $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['consultation_insight']; ?>
 						</p>
 					</div>
 				</div>


 				<?php
 			}
 			?>

 			<?php
 			if($all_section_data_in_application[$current_section_id - 1]['consultant'])
 				{ ?>

 					<div class="custom_column_reviewer mt-3">
 						<div class="alert alert-success alert-dismissible fade show" id="notes_saved_alert" role="alert" style="display: none;">
 							Notes saved.
 							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 						</div>
 						<p class="text-center" id="processing_notes" style="display: none;">
 							<img src="<?php echo base_url("assets/images/loading_image.gif"); ?>" height="20%" width="20%">
 						</p>
 						<div class="d-flex justify-content-between">
 							<div>
 								<label for="section_notes" class="form-label">INPUTS</label>
 							</div>
 							<div>
 								<?php 

 								if($current_section_review_status != 5)
 								{
 									echo '<a href="#" id="enable_edit_experts" onclick="return enable_edit_experts();">Add Experts</a>
 								<a href="#" id="cancel_save_experts" onclick="return cancel_save_experts();" style="display:none">Cancel</a>';
 								}
 								?>
 								
 							</div>
 						</div>
 						<div class="mb-3">
 							<p class="text-center" id="processing_experts" style="display: none;">
 								<img src="<?php echo base_url("assets/images/loading_image.gif"); ?>" height="20%" width="20%">
 							</p>
 							<div class="alert alert-success alert-dismissible fade show" id="experts_saved_alert" role="alert" style="display: none;">
 								Experts Saved.
 								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 							</div>

 							<?php
 							$consultant_string = $all_section_data_in_application[$current_section_id - 1]['consultant'];
 							$consultant_array = explode(",", $consultant_string);
 							echo '<div id="add_edit_experts" style="display:none">';
 							echo '<select class="form-select" name="new_consultant_list[]" id="new_consultant_list" data-placeholder="Select expert(s)" multiple>';
 							foreach ($consultant_list as $key => $consultant) {
 								echo '<option value="';
 								echo $consultant['id'];
 								echo '"';
 								if (in_array($consultant['id'], $consultant_array))
 								{
 									echo "selected";
 								}
 								echo '>';
 								echo $consultant['fname'];
 								echo " ";
 								echo $consultant['lname'];
 								echo '</option>';
 							}
 							echo '</select>';
 							echo '<div class="d-grid gap-2 mt-3">';
 							echo '<button type="button" id="update_experts" onclick="return update_experts();" class="btn btn-primary">Update Experts</button>';
 							echo '</div>';
 							echo '</div>';
 							echo "<br>";
 							foreach ($consultant_response as $key => $ind_response) {

 								if (in_array($ind_response['consultant_id'], $consultant_array))
 								{
 									echo "<p>";
 									echo "<strong>";
 									echo $ind_response['fname'];
 									echo " ";
 									echo $ind_response['lname'];
 									echo ": </strong>";
 									echo $ind_response['response'];
 									echo "</p>";
 								}
 							}
 							?>
 						</div>
 					</div>


 				<?php }

 				?>

 				<div class="custom_column_reviewer mt-3">
 					<div class="alert alert-success alert-dismissible fade show" id="notes_saved_alert" role="alert" style="display: none;">
 						Notes saved.
 						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 					</div>
 					<p class="text-center" id="processing_notes" style="display: none;">
 						<img src="<?php echo base_url("assets/images/loading_image.gif"); ?>" height="20%" width="20%">
 					</p>
 					<div class="d-flex justify-content-between">
 						<div>
 							<label for="section_notes" class="form-label">NOTES</label>
 						</div>
 						<div>
 							<a href="#" id="enable_edit_notes" onclick="return enable_edit_notes();">Edit</a>
 							<a href="#" id="enable_save_notes" onclick="return enable_save_notes();" style="display:none">Save</a>
 						</div>
 					</div>
 					<div class="mb-3">

 						<textarea class="form-control" id="section_notes" name="section_notes" rows="4" disabled><?php echo $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['notes']; ?></textarea>
 						<input type="hidden" name="current_section_id" id="current_section_id" value="<?php echo $current_section_id; ?>">
 					</div>
 				</div>
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
 							<li>You will not be able to make review decisions while the application is Under Consultation.</li>
 							<li>Previous decisions, if any, on which you will request inputs on will get reset.</li>
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

 	<!-- audit log modal -->
 	<div class="modal fade" id="audit_log_modal" tabindex="-1" aria-labelledby="audit_log_modal_label" aria-hidden="true">
 		<div class="modal-dialog modal-dialog-scrollable modal-lg">
 			<div class="modal-content">
 				<div class="modal-header">
 					<h5 class="modal-title" id="audit_log_modal_label">Audit Logs</h5>
 					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
 				</div>
 				<div class="modal-body">
 					<table class="table table-bordered border-secondary solution_table">
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
 				<div class="modal-footer">
 					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
 				</div>
 			</div>
 		</div>
 	</div>
 	<!-- End audit log modal -->

 	<!-- app_meta_data_modal -->

 	<div class="modal fade" id="app_meta_data_modal" tabindex="-1" aria-labelledby="app_meta_data_modal_label" aria-hidden="true">
 		<div class="modal-dialog modal-dialog-scrollable modal-lg">
 			<div class="modal-content">
 				<div class="modal-header">
 					<h5 class="modal-title" id="app_meta_data_modal_label">Application Meta Data</h5>
 					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
 				</div>
 				<div class="modal-body">
 					<?php
 					echo '<table class="table table-sm">';
 					echo '<tr>';
 					echo '<td>';
 					echo "Name :";
 					echo '</td>';

 					echo '<td class="bold_column">';
 					echo $solution_name;
 					echo '</td>';
 					echo '</tr>';


 					echo '<tr>';
 					echo '<td>';
 					echo "Application ID :";
 					echo '</td>';
 					echo '<td class="bold_column">';
 					echo $application_id;
 					echo '</td>';
 					echo '</tr>';


 					echo '<tr>';
 					echo '<td>';
 					echo "Parent Application ID :";
 					echo '</td>';
 					echo '<td class="bold_column">';
 					if($ind_application_data[0]['parent_id'])
 					{
 						echo $ind_application_data[0]['parent_id'];
 					}else{
 						echo "NA";
 					}
 					echo '</td>';
 					echo '</tr>';


 					echo '<tr>';
 					echo '<td>';
 					echo "Status :";
 					echo '</td>';
 					echo '<td class="bold_column">';
 					if($application_status == 2 OR $application_status == 4)
 					{
 						if($user_role == 2)
				          {
				            echo "Under L1 Review";
				          } else if ($user_role == 3)
				          {
				            echo "Under L2 Review";
				          }else{
				          	echo "Unknown Status";
				          }
 					}else{
 						echo $current_application_status;
 					}
 					
 					echo '</td>';
 					echo '</tr>';


 					echo '<tr>';
 					echo '<td>';
 					echo "Current L1 Reviewer :";
 					echo '</td>';
 					echo '<td class="bold_column">';
 					if($ind_application_data[0]['current_l1'])
 					{
 						echo $ind_application_data[0]['l1_fname'];
 						echo " ";
 						echo $ind_application_data[0]['l1_lname'];
 					}else{
 						echo "NA";
 					}
 					echo '</td>';
 					echo '</tr>';


 					echo '<tr>';
 					echo '<td>';
 					echo "Current L2 Reviewer :";
 					echo '</td>';
 					echo '<td class="bold_column">';
 					if($ind_application_data[0]['current_l2'])
 					{
 						echo $ind_application_data[0]['l2_fname'];
 						echo " ";
 						echo $ind_application_data[0]['l2_lname'];
 					}else{
 						echo "NA";
 					}
 					echo '</td>';
 					echo '</tr>';


 					echo '<tr>';
 					echo '<td>';
 					echo "Public URL :";
 					echo '</td>';
 					echo '<td class="bold_column">';
 					echo anchor("a/" . $application_id . "", base_url("a/$application_id"), array('title' => base_url("a/$application_id"), 'class' => "",'target' => "_blank"));
 					echo '</td>';
 					echo '</tr>';



 					echo '<tr>';
 					echo '<td>';
 					echo "Priority :";
 					echo '</td>';
 					echo '<td class="bold_column">';
 					$tags_array = explode(",",$ind_application_data[0]['tags']);
 					if(in_array(1, $tags_array))
 					{
 						echo "Yes";
 					}else{
 						echo "No";
 					}
 					echo '</td>';
 					echo '</tr>';



 					echo '<tr>';
 					echo '<td>';
 					echo "Date Created :";
 					echo '</td>';
 					echo '<td class="bold_column">';
 					echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['added_on']));
 					echo " UTC";
 					echo '</td>';
 					echo '</tr>';


 					echo '<tr>';
 					echo '<td>';
 					echo "Date Submitted :";
 					echo '</td>';
 					echo '<td class="bold_column">';
 					if($ind_application_data[0]['submitted_on'])
 					{
 						echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['submitted_on']));
 						echo " UTC";
 					}else{
 						echo "NA";
 					}
 					echo '</td>';
 					echo '</tr>';



 					echo '<tr>';
 					echo '<td>';
 					echo "Date L1 Review Started :";
 					echo '</td>';
 					echo '<td class="bold_column">';
 					if($ind_application_data[0]['l1_assign_on'])
 					{
 						echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['l1_assign_on']));
 						echo " UTC";
 					}else{
 						echo "NA";
 					}
 					echo '</td>';
 					echo '</tr>';

 					echo '<tr>';
 					echo '<td>';
 					echo "Date L1 Review Complete :";
 					echo '</td>';
 					echo '<td class="bold_column">';
 					if($ind_application_data[0]['l1review_complete_on'])
 					{
 						echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['l1review_complete_on']));
 						echo " UTC";
 					}else{
 						echo "NA";
 					}
 					echo '</td>';
 					echo '</tr>';


 					echo '<tr>';
 					echo '<td>';
 					echo "Date L2 Review Started :";
 					echo '</td>';
 					echo '<td class="bold_column">';
 					if($ind_application_data[0]['l2_assign_on'])
 					{
 						echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['l2_assign_on']));
 						echo " UTC";
 					}else{
 						echo "NA";
 					}
 					echo '</td>';
 					echo '</tr>';



 					echo '<tr>';
 					echo '<td>';
 					echo "Date L2 Review Completed :";
 					echo '</td>';
 					echo '<td class="bold_column">';
 					if($ind_application_data[0]['review_complete_on'])
 					{
 						echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['review_complete_on']));
 						echo " UTC";
 					}else{
 						echo "NA";
 					}
 					echo '</td>';
 					echo '</tr>';


 					echo '<tr>';
 					echo '<td>';
 					echo "Date of Expiry :";
 					echo '</td>';
 					echo '<td class="bold_column">';
 					if($ind_application_data[0]['expire_on'])
 					{
 						echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['expire_on']));
 						echo " UTC";
 					}else{
 						echo "NA";
 					}
 					echo '</td>';
 					echo '</tr>';

 					echo '</table>';
 					

 					?>
 				</div>
 				<div class="modal-footer">
 					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
 				</div>
 			</div>
 		</div>
 	</div>
 	<!-- End app_meta_data_modal -->

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
 							<li>You must click “Finish Consultation” on all sections you requested consultation on before you can move application back to Under Review.
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
 					<?php echo form_open('process/submit/review', 'id="dpga_submit_review"'); ?>
 					<div class="modal_popup_para">SUMMARY</div>
 					<div>
 						<?php echo $total_passed; ?> Passed &nbsp;&nbsp;&nbsp; 
 						<?php echo $total_failed; ?> Failed &nbsp;&nbsp;&nbsp; 
 						<?php 
 						if($user_role == 3)
 						{
 							echo "$total_clarifying Clarifying";
 							echo '&nbsp;&nbsp;&nbsp;';
 						} 
 						if($total_pending > 0 && $user_role == 3)
 						{
 							echo "<font color='red'>";
 							echo "$total_pending Pending";
 							echo "</font>";
 						}else if($user_role == 3){
 							echo "$total_pending Pending";
 						}?>
 					</div>
 					<?php if($user_role ==3){

 						?>
 						<div class="modal_popup_para pt-2">RESULT</div>
 						<div class="pt-2">
 							<?php
 							if($total_failed > 0)
 							{
 								echo '<div class="d-grid gap-2">
 								<button type="button" class="btn btn-danger mb-2">INELIGIBLE</button>
 								</div>';
 							}else if($total_clarifying > 0 && $clarifications_submit == 0)
 							{
 								echo '<div class="d-grid gap-2">
 								<button type="button" class="btn btn-warning mb-2">NEED CLARIFICATIONS</button>
 								</div>';
 								echo '<div class="modal_popup_para pt-2">CLARIFICATION TIME LIMIT (IN DAYS)</div>
 								<div class="row pt-2">
 								<div class="col">
 								<input type="radio" class="btn-check" name="time_limit_days" id="15" value="15" autocomplete="off" checked>
 								<label class="btn btn-outline-primary" for="15" style="width: 100%">15</label>
 								</div>
 								<div class="col">
 								<input type="radio" class="btn-check" name="time_limit_days" id="30" value="30" autocomplete="off">
 								<label class="btn btn-outline-primary" for="30" style="width: 100%">30</label>
 								</div>
 								<div class="col">
 								<input type="radio" class="btn-check" name="time_limit_days" id="45" value="45" autocomplete="off">
 								<label class="btn btn-outline-primary" for="45" style="width: 100%">45</label>
 								</div>
 								<div class="col">
 								<input type="radio" class="btn-check" name="time_limit_days" id="60" value="60" autocomplete="off">
 								<label class="btn btn-outline-primary" for="60" style="width: 100%">60</label>
 								</div>
 								<div class="col">
 								<input type="radio" class="btn-check" name="time_limit_days" id="75" value="75" autocomplete="off">
 								<label class="btn btn-outline-primary" for="75" style="width: 100%">75</label>
 								</div>

 								<div class="col">
 								<input type="radio" class="btn-check" name="time_limit_days" id="90" value="90" autocomplete="off">
 								<label class="btn btn-outline-primary" for="90" style="width: 100%">90</label>
 								</div>
 								</div>';
 							}else if ($total_pending > 0)
 							{
 								echo '<div class="d-grid gap-2">
 								<button type="button" class="btn btn-danger mb-2">INELIGIBLE</button>
 								</div>';
 							}else if ($total_clarifying > 0 && $clarifications_submit == 1)
 							{
 								echo '<div class="d-grid gap-2">
 								<button type="button" class="btn btn-danger mb-2">INELIGIBLE</button>
 								</div>';
 							}else{
 								if($user_role ==2)
 								{
 									echo '<div class="d-grid gap-2">
 									<button type="button" class="btn btn-success mb-2">Move to L2 Review</button>
 									</div>';
 								}else{
 									echo '<div class="d-grid gap-2">
 									<button type="button" class="btn btn-success mb-2">DPG</button>
 									</div>';
 								}

 							}

 							?>



 						</div>


 					</div>
 					<?php 
 				}
 				?>
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

 					if($user_role == 2)
 					{

 						if($total_ipr_action == $total_ipr_found)
 						{
 							if($total_ipr_pass != $total_ipr_found)
 							{
 								if($total_pending > 0 && $total_ipr_pass == $total_ipr_found)
 								{
 									echo 'disabled';
 								}
 							}else{

 								if($total_pending > 0 && $total_ipr_pass == $total_ipr_found)
 								{
 									echo 'disabled';
 								}
 							}

 						} else {
 							echo 'disabled';
 						}


 					}


 					if($user_role == 3)
 					{
 						if($total_ipr_action == $total_ipr_found)
 						{
 							if($total_ipr_pass != $total_ipr_found)
 							{
 								if($under_consultation_count > 0 OR $total_pending > 0)
 								{
 									echo 'disabled';
 								}
 							}

 						} else {
 							echo 'disabled';
 						}

 						if($total_clarifying > 0 && $clarifications_submit == 1)
 						{
 							echo 'disabled';
 						}
 					}












/*
				if($total_ipr_action == $total_ipr_found)
				{
					if($total_ipr_action == $total_ipr_pass)
					{
						
					}else{
						echo 'disabled';
					}
				}else{
					echo 'disabled';
				}

*/



				
				?> >Submit Review</button>
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
	function show_edit_section()
	{
		$("#edit_section").show();
		$("#view_section").hide();
	}
	function show_view_section()
	{
		$("#view_section").show();
		$("#edit_section").hide();
	}

	function enable_edit_notes()
	{
		$("#enable_save_notes").show();
		$("#enable_edit_notes").hide();
		$("#section_notes").removeAttr('disabled');
	}

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

	function reset_review_decision()
	{
		
		<?php
		if($application_status == 5)
		{
			echo '$("#consultation").prop("checked", false);';
		}else{
			echo '$("#pass").prop("checked", false);';
			echo '$("#fail").prop("checked", false);';
			if($user_role == 3)
			{
				echo '$("#clarify").prop("checked", false);';
			}
			echo '$("#clarify_question").removeAttr("required");';
			echo '$("#clarify_question").val("");';
			echo '$("#clarify_question_div").hide();';
		}
		?>
		$("#save_decision").removeAttr("disabled");	 
	}

	function enable_save_button()
	{
		$('#clarify_question').attr('required', 'required');
		$("#save_decision").removeAttr("disabled");	
	}

	function enable_save_notes()
	{
		
		$("#processing_notes").show();
		$.ajax({
			url: "<?php echo base_url("index.php/reviewer/notes/update"); ?>",
			type: "post",
			data: {'section_notes': document.getElementById("section_notes").value,
			'current_section_id': document.getElementById("current_section_id").value
		},
		success: function (response) {
			$("#section_notes").prop("disabled", true);
			$("#enable_edit_notes").show();
			$("#enable_save_notes").hide();
			$("#processing_notes").hide();
			// $("#notes_saved_alert").show();
		},
		error: function(jqXHR, textStatus, errorThrown) {
			alert("something went wrong. Notes not saved. Please retry");
			console.log(textStatus, errorThrown);
		}
	});
	}

	function update_experts(){

		$("#processing_experts").show();
		
		if($('#new_consultant_list').val().length == 0)
		{
			alert("Please select atleast one expert from list");
			$("#processing_experts").hide();
			return;
		}
		console.log("govind");
		$.ajax({
			url: "<?php echo base_url("index.php/reviewer/experts/update"); ?>",
			type: "post",
			data: {'new_consultant_list': $('#new_consultant_list').val()
		},
		success: function (response) {
			// $("#enable_edit_notes").show();
			$("#enable_edit_experts").show();
			$("#add_edit_experts").hide();
			$("#processing_experts").hide();
			$("#experts_saved_alert").show();
			alert("Experts list updated");
			location.reload(true)
		},
		error: function(jqXHR, textStatus, errorThrown) {
			$("#processing_experts").hide();
			alert("something went wrong. Experts not updated. Please retry");
			console.log(textStatus, errorThrown);
		}
	});
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