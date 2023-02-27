<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reviewer extends CI_Controller {

	function __construct()
	{
        // Call the Model constructor
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$user_role = $this->session->userdata('user_role');
		//This controller only for Reviewers
		if($user_role == 2 OR $user_role == 3)
		{
			
		} else{
			redirect('/login', 'refresh');
			exit();
		}
		
		
		$this->load->helper('form');
		$this->load->helper('cookie');
		$this->load->library('encryption');
		$this->load->model('users_model');
		$this->load->model('application_model');
		$this->load->model('section_model');
		$this->load->database();
	}

	public function home()
	{
		$data['page_title'] = 'Reviewer Home - DPGA';
		$data['page_heading'] = 'home';
		$data['user_details'] = $this->users_model->get_applicant_details($this->session->userdata('user_id'));
		$data['user_role'] = $user_role = $this->session->userdata('user_role');

		$data['queue_applications_list'] = $this->application_model->get_queue_applications($user_role);
		$user_id = $this->session->userdata('user_id');
		$data['under_review_application'] = $this->application_model->get_under_review_application($user_role, $user_id);
		$data['under_consultation_application'] = $this->application_model->get_under_consultation_application($user_role, $user_id);

		$data['experts_response'] = $this->application_model->experts_response($user_role, $user_id);
		$data['clarifications_application'] = $this->application_model->clarifications_application($user_role, $user_id);
		$data['dpga_limits'] = $this->application_model->get_dpga_limits();
		$data['all_tags_master_array'] = $this->application_model->all_tags_list();
		
		//provide a home to user
		$this->load->view('reviewer/header', $data);
		$this->load->view('reviewer/home', $data);
		$this->load->view('reviewer/footer', $data);
	}

	public function pull_application($application_id)
	{
		$user_role = $this->session->userdata('user_role');
		$user_id = $this->session->userdata('user_id');
		if($user_role == 2)
		{
			$status = 1;
		} else if($user_role == 3)
		{
			$status = 3;
		}
		//first we check application status
		if($this->verify_application_status($application_id, $status))
		{
			if(!$this->is_already_assigned($application_id, $user_role))
			{
				if(!$this->is_reviewer_already_busy($user_id, $user_role))
				{

					//all set, now we assign and update status
					$this->application_model->assign_application($application_id,$user_role,$user_id);
					//save logs
					$log_role = $this->session->userdata('user_role_details');
					$log_name = $this->session->userdata('user_fullname');
					$solution_name = $this->application_model->get_solution_name($application_id);

					$log_string = "$log_name ($log_role) pulled $solution_name ($application_id) under review";
					$insert_data = array('application_id' => $application_id,
					'comment' => $log_string,
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
					$this->application_model->insert_application_log($insert_data);
					$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
					<strong>Success !!</strong> Application assigned to you.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
					redirect('/reviewer', 'refresh');
					exit();
				}else{
					$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Error !!</strong>Reviewer busy in another application
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
				redirect('/reviewer', 'refresh');
				exit();
				}
			}else{
				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Error !!</strong> Application already assigned to other reviewer.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
				redirect('/reviewer', 'refresh');
				exit();
			}

		}else{
			$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Error !!</strong> Application status is not in required state.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
			redirect('/reviewer', 'refresh');
			exit();
		}
	}

	public function verify_application_status($application_id, $status)
	{
		return $this->application_model->verify_application_status($application_id, $status);
	}

	public function is_already_assigned($application_id, $user_role)
	{
		return $this->application_model->is_already_assigned($application_id, $user_role);
	}

	public function is_reviewer_already_busy($user_id, $user_role)
	{
		return $this->application_model->is_reviewer_already_busy($user_id, $user_role);
	}

	public function is_assigned_to_me($application_id, $user_role, $user_id)
	{
		return $this->application_model->is_assigned_to_me($application_id, $user_role, $user_id);
	}

	public function is_possible_to_review($application_id)
	{
		return $this->application_model->is_possible_to_review($application_id);
	}

	public function start_review($application_id)
	{
		$user_role = $this->session->userdata('user_role');
		$user_id = $this->session->userdata('user_id');
		if($this->is_assigned_to_me($application_id, $user_role, $user_id))
		{
			if($this->is_possible_to_review($application_id))
			{
				//all good, now set session
				$this->session->set_userdata('current_review_id', $application_id);
				$this->session->set_userdata('current_section_id', SECTION_ID);
				$this->session->set_userdata('section_revision', array());
				redirect('/process/review', 'refresh');
				exit();

			}else{
				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Application Status Issue !!</strong> Application status is not in required state.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
			redirect('/reviewer', 'refresh');
			exit();
			}
			
		}else{
			$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Permission Issue !!</strong> Application is not assigned to reviewer.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
			redirect('/reviewer', 'refresh');
			exit();
		}

	}

	public function start_consultation($application_id)
	{
		$user_role = $this->session->userdata('user_role');
		$user_id = $this->session->userdata('user_id');
		if($this->is_assigned_to_me($application_id, $user_role, $user_id))
		{
			if($this->application_model->is_application_under_consultation($application_id))
			{
				//all good, now set session
				$this->session->set_userdata('current_review_id', $application_id);
				$this->session->set_userdata('current_section_id', SECTION_ID);
				$this->session->set_userdata('section_revision', array());
				redirect('/process/review', 'refresh');
				exit();
			}else{
				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Application Status Issue !!</strong> Application status is not in required state.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
			redirect('/reviewer', 'refresh');
			exit();
			}
			
		}else{
			$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Permission Issue !!</strong> Application is not assigned to reviewer.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
			redirect('/reviewer', 'refresh');
			exit();
		}
	}

	public function process_review()
	{
		$current_review_id = $this->session->userdata('current_review_id');
		$user_id = $this->session->userdata('user_id');
		$data['user_role'] = $user_role = $this->session->userdata('user_role');
		$data['application_id'] = $application_id = $this->session->userdata('current_review_id');
		$data['current_section_id'] = $current_section_id = $this->session->userdata('current_section_id');
		if($user_role == 2)
		{
			$data['reviewer_type'] = 'L1 Reviewer';
		} else if($user_role == 3) {
			$data['reviewer_type'] = 'L2 Reviewer';
		}
		if($current_review_id > 0)
		{
			$data['page_title'] = 'Application Review - DPGA';
			$data['page_heading'] = 'home';
			$data['user_details'] = $this->users_model->get_applicant_details($user_id);
			$data['solution_name'] = $this->application_model->get_solution_name($application_id);
			$data['all_section_data_in_application'] = $all_section_data_in_application = $this->section_model->all_section_data_via_application_id($application_id);
			
			$data['ind_application_data'] = $ind_application_data = $this->application_model->ind_application_data($application_id);
			//print_r($ind_application_data);

			$data['application_status'] = $ind_application_data[0]['status'];
			$data['show_timer'] = $this->show_timer($application_id, $user_role);
			$data['dpga_limits'] = $this->application_model->get_dpga_limits();
			$data['application_logs'] = $this->application_model->get_application_logs($application_id);
			$data['all_app_status_for_reviewer'] = $this->application_model->all_app_status_for_reviewer($user_role);

			//get question details
			$data['all_section_questions'] = $this->application_model->get_all_section_questions($current_section_id);
			//get all answers
			$data['all_section_answers'] = $this->application_model->get_all_section_answers($current_section_id, $application_id);
			$data['consultant_list'] = $this->users_model->consultant_list();

			$data['consultant_response'] = $this->application_model->get_all_consultant_response($current_section_id, $application_id);

			$this->load->view('reviewer/header', $data);
			$this->load->view('reviewer/review_application', $data);
			$this->load->view('reviewer/footer', $data);

		}else{
			$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Error !!</strong> Please start again to review application.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
			redirect('/reviewer', 'refresh');
		}
	}

	public function change_section($section_id)
	{
		$this->session->set_userdata('current_section_id', $section_id);
		redirect('/process/review', 'refresh');
		
	}

	public function show_timer($application_id, $user_role)
	{
		return $this->application_model->show_timer($application_id, $user_role);
	}

	public function save_updated_form()
	{
		if ($this->input->post()) {

			// $multid = $this->input->post('2');
			// print_r($multid);
			// echo is_array($this->input->post('2'));
			// exit();
			$application_id = $this->session->userdata('current_review_id');
			$current_section_id = $this->input->post('current_section_id');
			$user_id = $this->session->userdata('user_id');
			$data = array('filling_status' => 0, );
			$this->section_model->update_section_data($current_section_id, $application_id, $data);
			$all_question_ids_string = $this->input->post('all_question_ids_string');
			$all_section_ids_array = explode(",", $this->input->post('all_section_ids_string'));
			if($this->input->post('all_required_qids_string') == ''){
				$all_required_qids_array = array();
			}else{
				$all_required_qids_array = explode(",", $this->input->post('all_required_qids_string'));
			}

			if($this->input->post('all_multiple_select_qids_string') == ''){
				$all_multiple_select_qids_array = array();
			}else{
				$all_multiple_select_qids_array = explode(",", $this->input->post('all_multiple_select_qids_string'));
			}
			
		   //first we delete existing answers
			$this->application_model->remove_old_answers($application_id, $current_section_id);
			$all_question_ids_array = explode (",", $all_question_ids_string);
			$bulk_insert_data = array();
			$attempted_qids_which_required = array();
			foreach ($all_question_ids_array as $key => $question_id) {
				if($this->input->post($question_id) != '')
				{
					
		   			//here we check qid is multi select or not
					if (in_array($question_id, $all_multiple_select_qids_array)) {

						if(is_array($this->input->post($question_id)))
						{
							$single_array = array(
								'application_id'=> $application_id,
								'section_id'=> $current_section_id,
								'question_id'=> $question_id,
								'answer'=> implode(",", $this->input->post($question_id)),
								'response_time'=> date('Y-m-d H:i:s')
							);
							array_push($bulk_insert_data,$single_array);
						}
						
					}
					else{

						$single_array = array(
							'application_id'=> $application_id,
							'section_id'=> $current_section_id,
							'question_id'=> $question_id,
							'answer'=> $this->input->post($question_id),
							'response_time'=> date('Y-m-d H:i:s')
						);
						array_push($bulk_insert_data,$single_array);
					}
					
					if (in_array($question_id, $all_required_qids_array)) {
						array_push($attempted_qids_which_required, $question_id);
					}
					
				}
			}
			//now save all answers to db
			if(count($bulk_insert_data) > 0)
			{
				$this->application_model->insert_answers_in_bulk($bulk_insert_data);
			}

			//update logs
			$log_role = $this->session->userdata('user_role_details');
			$log_name = $this->session->userdata('user_fullname');
			$solution_name = $this->application_model->get_solution_name($application_id);
			$section_name = $this->application_model->get_section_name($current_section_id);
			$log_string = "$log_name ($log_role) edited $section_name for $solution_name ($application_id)";
			$insert_data = array('application_id' => $application_id,
					'comment' => $log_string,
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
					$this->application_model->insert_application_log($insert_data);
			
			//now we check is this section filled all required questions
			if(count($all_required_qids_array) == count($attempted_qids_which_required))
			{
				//update db- all required questions answered
				$data = array('filling_status' => 1, );
				$this->section_model->update_section_data($current_section_id, $application_id, $data);
			}
			//now update the current section and redirect
			$total_section_count = count($all_section_ids_array)-1;
			$key = array_search ($current_section_id, $all_section_ids_array);
			if($key < $total_section_count){
				$next_section_id_key = $key+1;
			}else{
				$next_section_id_key = $key;
			}
			$next_section_id = $all_section_ids_array[$next_section_id_key];
			//$this->session->set_userdata('current_section_id', $next_section_id);
			//handle section_revision
			// $temp_section_revision = $this->session->userdata('section_revision');
			// array_push($temp_section_revision, $current_section_id);
			// $temp_section_revision = array_unique($temp_section_revision);
			// $this->session->set_userdata('section_revision', $temp_section_revision);
			redirect('/process/review/', 'refresh');
		}else{
			redirect('/reviewer', 'refresh');
		}
	}

	public function manage_child_application($application_id)
	{
		$parent_application_id = $this->application_model->get_parent_id($application_id);
		if (is_numeric($parent_application_id))
		{
			if($parent_application_id > 0){
				$app_status = $this->application_model->get_applicant_status($parent_application_id);
				if($app_status == 8)
				{
					//we need to expire this application because refresher approved
					$update_data = array('status' => 9);
		  			$this->application_model->update_app_data($update_data, $parent_application_id);
		  			//update logs
				$solution_name = $this->application_model->get_solution_name($parent_application_id);
				$log_string = "System moved $solution_name ($application_id) to expired because parent applcation is now DPG.";
		  		//update application logs
				$insert_data = array('application_id' => $parent_application_id,
					'comment' => $log_string,
					'perform_on' => date('Y-m-d H:i:s') );
				$this->application_model->insert_application_log($insert_data);
				return true;

				}
			}
		}
	}


	public function remove_tag_in_application($application_id, $tag_id)
	{
		$tags_string = $this->application_model->get_tags_string($application_id);
		if($tags_string == "")
		{
			$tags_array = array();
		}else{
			$tags_array = explode (",", $tags_string);
		}
		
		if (in_array($tag_id, $tags_array))
		  {
		  	//now remove from array
		  	$tags_array_new = array_diff($tags_array, array($tag_id));
		  	$new_tags_string = implode(',', $tags_array_new);
		  	$update_data = array('tags' => $new_tags_string);
		  	$this->application_model->update_app_data($update_data, $application_id);
		  	//update logs
		  	$user_id = $this->session->userdata('user_id');
		  	$tag_name = $this->application_model->get_tag_name($tag_id);
		  	$log_role = $this->session->userdata('user_role_details');
			$log_name = $this->session->userdata('user_fullname');
			$solution_name = $this->application_model->get_solution_name($application_id);
			$log_string = "System remove tag $tag_name from $solution_name ($application_id)";
		  //update application logs
				$insert_data = array('application_id' => $application_id,
					'comment' => $log_string,
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
				$this->application_model->insert_application_log($insert_data);
				return true;
		  }
		else
		  {
		return true;
		exit();
		  } 
	}

	public function save_notes()
	{
		if ($this->input->post()) {
			$section_notes = $this->input->post('section_notes');
			$section_id = $this->input->post('current_section_id');
			$application_id = $this->session->userdata('current_review_id');
			$data = array('notes' => $section_notes);
			$user_id = $this->session->userdata('user_id');
			$this->section_model->update_section_data($section_id, $application_id, $data);
			//update application logs
			$log_role = $this->session->userdata('user_role_details');
			$log_name = $this->session->userdata('user_fullname');
			$solution_name = $this->application_model->get_solution_name($application_id);
			$section_name = $this->application_model->get_section_name($section_id);

			$log_string = "$log_name ($log_role) edited a note on $section_name for $solution_name ($application_id)";
			//save logs
					$insert_data = array('application_id' => $application_id,
					'comment' => $log_string,
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
					$this->application_model->insert_application_log($insert_data);
		}
	}

	public function process_decision()
	{
		if ($this->input->post()) {
			//0- default, need to take action
			//1- pass
			//2- fail
			//3 - clarify
			//5 - insight consultation
			$decision = $this->input->post('review_decision');
			$user_id = $this->session->userdata('user_id');
			$user_role = $this->session->userdata('user_role');
			$section_id = $this->input->post('current_section_id');
			$application_id = $this->session->userdata('current_review_id');
			$clarify_question = $this->input->post('clarify_question');
			
			$consultation_reason = "";
			$consultant = "";
			$consultation_insight = "";

			if($decision == 5)
			{
				$consultation_insight = $this->input->post('consultation_insight');
				if($this->application_model->is_application_under_consultation($application_id))
				{
					$this->update_decision($decision, $clarify_question, $user_role, $section_id, $application_id, $consultant, $consultation_reason, $consultation_insight);
					redirect('/process/review', 'refresh');

				}else{
					redirect('/reviewer', 'refresh');
				}

				exit();
			}
			
			//if r1 try to clarify
			if($user_role == 2 && $decision == 3)
			{
				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Error !!</strong> You are not allowed to ask clarification.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
				redirect('/process/review', 'refresh');
				exit();
			}
			if($this->application_model->is_application_under_consultation($application_id))
			{
				$consultant = implode(",", $this->input->post('consultant'));
				$consultation_reason = $this->input->post('consultation_reason');
				$consultant_array = $this->input->post('consultant');
				foreach ($consultant_array as $key => $cdata) {
					$c_insert_data = array('consultant_id' => $cdata,
					'application_id' => $application_id,
					'section_id' => $section_id,
					'question' => $consultation_reason,
					'added_on' => date('Y-m-d H:i:s'));
					$this->application_model->replace_consultant_questions($cdata, $c_insert_data);
					}
				//send email alert
				$reviewer_name = $this->session->userdata('user_fullname');
				$this->email_alert_for_expert($consultant_array, $application_id, $section_id, $reviewer_name);
			}
			if(!$decision)
			{
				$decision = 0;
			}
			$this->update_decision($decision, $clarify_question, $user_role, $section_id, $application_id, $consultant, $consultation_reason, $consultation_insight);
			//now manage next section for view
			$all_section_ids_array = explode(",", $this->input->post('all_section_ids_string'));
			$total_section_count = count($all_section_ids_array)-1;
			$key = array_search ($section_id, $all_section_ids_array);
			if($key < $total_section_count){
				$next_section_id_key = $key+1;
			}else{
				$next_section_id_key = $key;
			}
			$next_section_id = $all_section_ids_array[$next_section_id_key];
			//$this->session->set_userdata('current_section_id', $next_section_id);
			redirect('/process/review/', 'refresh');

			
		}else{
			redirect('/reviewer', 'refresh');
		}
	}

	public function update_decision($decision, $clarify_question, $user_role, $section_id, $application_id, $consultant, $consultation_reason, $consultation_insight)
	{
		//0- default, need to take action
		//1- pass
		//2- fail
		//3 - clarify
		//5 - insight consultation
		$user_id = $this->session->userdata('user_id');
		//is clarifications submit on application by applicant
		$clarifications_submit_status = $this->is_clarifications_submit($application_id);
		$log_role = $this->session->userdata('user_role_details');
		$log_name = $this->session->userdata('user_fullname');
		$solution_name = $this->application_model->get_solution_name($application_id);
		$section_name = $this->application_model->get_section_name($section_id);

		if($user_role == 2)
		{
			$status_name = "r1_status";
			$update_time = "r1_time";
		} else if($user_role == 3){
			$status_name = "r2_status";
			$update_time = "r2_time";
		}

		if($decision == 3)
		{
			$data = array($status_name => $decision,
				'clarify_question' => $clarify_question,
				$update_time => date('Y-m-d H:i:s'));
			// $log_message = 'Clarification question added to section';
			$log_message = "$log_name ($log_role) require clarification on $section_name for $solution_name ($application_id)";
		}else if($decision == 0) {
			$data = array('clarify_question' => NULL,
			$status_name => $decision,
			$update_time => NULL );
			// $log_message = 'Decision reset by reviewer';
			$log_message = "$log_name ($log_role) reset their decision on $section_name for $solution_name ($application_id)";
		} else if($decision == 1) {
			if($clarifications_submit_status)
			{
				$data = array($status_name => $decision,
				$update_time => date('Y-m-d H:i:s'));
			}else{
				$data = array($status_name => $decision,
				'clarify_question' => NULL,
				$update_time => date('Y-m-d H:i:s'));
			}
			
			// $log_message = 'Pass - Decision taken by reviewer for section';
			$log_message = "$log_name ($log_role) passed $section_name for $solution_name ($application_id)";
		} else if($decision == 2) {
			if($clarifications_submit_status)
			{
				$data = array($status_name => $decision,
				$update_time => date('Y-m-d H:i:s'));
			}else{
				$data = array($status_name => $decision,
				'clarify_question' => NULL,
				$update_time => date('Y-m-d H:i:s'));
			}
			
			// $log_message = 'Fail - Decision taken by reviewer for section';
			$log_message = "$log_name ($log_role) failed $section_name for $solution_name ($application_id)";
		}else if($decision == 4) {
			$data = array($status_name => $decision,
				'consultant'=> $consultant,
				'consultant_question' => $consultation_reason,
			$update_time => date('Y-m-d H:i:s'));
			// $log_message = 'Under Consultation - Decision taken by reviewer for section';
			$log_message = "$log_name ($log_role) requested consultation on $section_name for $solution_name ($application_id)";
		}else if($decision == 5) {
			$data = array($status_name => 5, 'consultation_insight' => $consultation_insight);
			// $log_message = 'consultation insight inserted by reviewer';
			$log_message = "$log_name ($log_role) finished consultation on $section_name for $solution_name ($application_id)";
		}
		$this->section_model->update_section_data($section_id, $application_id, $data);
		$insert_data = array('application_id' => $application_id,
					'section_id' => $section_id,
					'comment' => $log_message,
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
		$this->application_model->insert_application_log($insert_data);
	}

	public function is_clarifications_submit($application_id)
	{
		return $this->application_model->is_clarifications_submit($application_id);
	}

	public function change_to_consultation()
	{
		if ($this->input->post()) {
			$user_id = $this->session->userdata('user_id');
			$user_role = $this->session->userdata('user_role');
			if($user_role == 2)
			{
				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Error !!</strong> You are not allowed to change application status to consultation.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
				redirect('/process/review', 'refresh');
				exit();
			}
			$application_id = $this->session->userdata('current_review_id');
			$this->application_model->change_to_consultation($user_id, $application_id);
			//update application logs
			$log_role = $this->session->userdata('user_role_details');
			$log_name = $this->session->userdata('user_fullname');
			$solution_name = $this->application_model->get_solution_name($application_id);

			$log_string = "$log_name ($log_role) moved $solution_name ($application_id) to under consultation";
			//update logs
			$insert_data = array('application_id' => $application_id,
					'comment' => $log_string,
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
					$this->application_model->insert_application_log($insert_data);
					$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
					<strong>Success !!</strong> Application status changed to under consultation.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
					redirect('/process/review', 'refresh');
					exit();

		}else{
			redirect('/reviewer', 'refresh');
		}
	}

	public function change_to_underreview()
	{
		if ($this->input->post()) {
			$user_id = $this->session->userdata('user_id');
			$user_role = $this->session->userdata('user_role');
			if($user_role == 2)
			{
				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Error !!</strong> You are not allowed to change application status to under review.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
				redirect('/process/review', 'refresh');
				exit();
			}
			$application_id = $this->session->userdata('current_review_id');
			if($this->can_change_status_to_under_review($application_id))
			{
				$this->application_model->change_to_underreview($user_id, $application_id);
				$log_role = $this->session->userdata('user_role_details');
				$log_name = $this->session->userdata('user_fullname');
				$solution_name = $this->application_model->get_solution_name($application_id);

				$log_string = "$log_name ($log_role) moved $solution_name ($application_id) to under review";
				//update logs
				$insert_data = array('application_id' => $application_id,
					'comment' => $log_string,
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
					$this->application_model->insert_application_log($insert_data);
					$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
					<strong>Success !!</strong> Application status changed to under review.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
					redirect('/process/review', 'refresh');
					exit();
			}
			
			

			


		}else{
			redirect('/reviewer', 'refresh');
		}
	}

	public function can_change_status_to_under_review($application_id)
	{
		if($this->application_model->count_under_consultation($application_id) == 0)
		{
			return true;
		}else{
			return false;
		}
	}

	public function handle_submit_review()
	{
		if ($this->input->post())
		{
			$user_id = $this->session->userdata('user_id');
			$user_role = $this->session->userdata('user_role');
			$application_id = $this->session->userdata('current_review_id');
			$time_limit_days = $this->input->post('time_limit_days');
			/*
			//first handle INELIGIBLE
			if($this->is_application_ineligible_via_ipr($application_id, $user_role))
			{
				//now change application to ineligible status code- 7
				$update_data = array('status' => 7,
				'nominee' => 0,
				'review_complete_on' => date('Y-m-d H:i:s'));
				$this->application_model->update_app_data($update_data, $application_id);
				//update logs
				$insert_data = array('application_id' => $application_id,
					'comment' => 'Application status changed to ineligible',
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
				$this->application_model->insert_application_log($insert_data);
				$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
					<strong>Success !!</strong> Your review submitted. Thanks
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
				$this->session->set_userdata('current_review_id', 0);
				redirect('/reviewer', 'refresh');
				exit();
			}
			*/
			

			//now we check action taken on each section
			if($this->is_action_taken_on_each_section($application_id, $user_role))
			{
				//now we check is any section failed
				if($this->is_any_section_failed($application_id, $user_role))
				{
					//one or more section failed, 
					//echo "now change application to ineligible status code-- 7";
					if($this->is_application_nominee($application_id))
					{
						//handle log when notion updated
						
					}




					$update_data = array('status' => 7,
					'nominee' => 0,
					'review_complete_on' => date('Y-m-d H:i:s') );
					$this->application_model->update_app_data($update_data, $application_id);
					//update logs
					$log_role = $this->session->userdata('user_role_details');
					$log_name = $this->session->userdata('user_fullname');
					$solution_name = $this->application_model->get_solution_name($application_id);

					$log_string = "$log_name ($log_role) submitted their review of $solution_name ($application_id) and found it to be ineligible";
					$insert_data = array('application_id' => $application_id,
					'comment' => $log_string,
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
					$this->application_model->insert_application_log($insert_data);
					//remove late tag if any exists
					$temp_tag_id = 4;
					$this->remove_tag_in_application($application_id, $temp_tag_id);

					//handle graph1 entry
					$this->application_model->update_graph1_data("decision_completed");

					//send email alert
					$this->email_alert_for_ineligible_dpg($application_id);




					$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
					<strong>Success !!</strong> Your review submitted. Thanks
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
					$this->session->set_userdata('current_review_id', 0);
					redirect('/reviewer', 'refresh');
					exit();
				}else{
					//now we submit the application
					if($this->submit_application($application_id, $user_role, $time_limit_days))
					{
						$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
					<strong>Success !!</strong> Your review submitted. Thanks
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
						$this->session->set_userdata('current_review_id', 0);
						redirect('/reviewer', 'refresh');
					exit();
					}else{
						$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
					<strong>Error !!</strong>Unable to submit, Something went wrong.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
						$this->session->set_userdata('current_review_id', 0);
						redirect('/reviewer', 'refresh');
					exit();
					}	
				}
			}else{
				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Error !!</strong> Unable to submit review, Decision must be taken on each section.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
				redirect('/process/review', 'refresh');
				exit();
			}


		}else{
			redirect('/reviewer', 'refresh');
		}
	}

	public function submit_application($application_id, $user_role, $time_limit_days)
	{
		$user_id = $this->session->userdata('user_id');
		//update application logs
		$log_role = $this->session->userdata('user_role_details');
		$log_name = $this->session->userdata('user_fullname');
		$solution_name = $this->application_model->get_solution_name($application_id);

		if($user_role == 2)
		{
			$status = 3;
			$update_data = array('status' => $status,
			'l1review_complete_on' => date('Y-m-d H:i:s'));
			// $log_message = 'Application status changed to Waiting for L2';
			$log_message = "$log_name ($log_role) submitted their review of $solution_name ($application_id)";
			//remove late tag if any exists
			$tag_id = 4;
			$this->remove_tag_in_application($application_id, $tag_id);

		}else if($user_role == 3){
			if($this->section_model->count_clarifications($application_id, $user_role) > 0)
			{
				$status = 6;
				$update_data = array('status' => $status,
				'to_clarifications'=> date('Y-m-d H:i:s'),
				'nominee' => 1,
				'clarifications_days'=> $time_limit_days);
				// $log_message = 'Application status changed to Waiting for Clarifications';
				$log_message = "$log_name ($log_role) requested clarifications for $solution_name ($application_id)";
				$new_log = "System marked $solution_name ($application_id) as a nominee";
				$insert_data = array('application_id' => $application_id,
					'comment' => $new_log,
					'perform_on' => date('Y-m-d H:i:s') );
				$this->application_model->insert_application_log($insert_data);

				//handle email alert
				$this->email_alert_for_clarifications_requested($application_id, $time_limit_days);

			}else{
				$status = 8;
				$dpga_limits = $this->application_model->get_dpga_limits();
				$app_expire_days = $dpga_limits[0]['app_expire_days'];
				$filter_string = "+ $app_expire_days day";

				// $expiry_date = date('Y-m-d H:i:s', strtotime(' + 1 years'));
				$expiry_date = date('Y-m-d H:i:s', strtotime($filter_string));

				$update_data = array('status' => $status,
				'review_complete_on' => date('Y-m-d H:i:s'),
				'nominee' => 0,
				'expire_on' => $expiry_date );
				// $log_message = 'Application status changed to DPG';
				$log_message = "$log_name ($log_role) submitted their review of $solution_name ($user_id) and found it to be a DPG";
				$new_log = "System unmarked $solution_name ($application_id) as a nominee";
				$insert_data = array('application_id' => $application_id,
					'comment' => $new_log,
					'perform_on' => date('Y-m-d H:i:s') );
				$this->application_model->insert_application_log($insert_data);
				//remove late tag if any exists
				$temp_tag_id = 4;
				$this->remove_tag_in_application($application_id, $temp_tag_id);
				//handle child case
				$this->manage_child_application($application_id);
				//handle graph1 entry
				$this->application_model->update_graph1_data("decision_completed");
				//send email alert
				$this->email_alert_for_become_dpg($application_id);

			}
		}
		//also check Under Consultation or not
		if(!$this->application_model->is_application_under_consultation($application_id))
		{
			//update application status
			$this->application_model->update_app_data($update_data, $application_id);
			//update logs
			$insert_data = array('application_id' => $application_id,
					'comment' => $log_message,
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
			$this->application_model->insert_application_log($insert_data);
			return true;
		}else{
			return false;
		}
	}

	public function is_any_section_failed($application_id, $user_role)
	{
		if($this->section_model->count_section_failed($application_id, $user_role) > 0 && $user_role == 3)
		{
			return true;
		}else{
			return false;
		}
	}

	public function is_action_taken_on_each_section($application_id, $user_role)
	{
		if($this->section_model->count_no_action_on_sections($application_id, $user_role) > 0){
			return false;
		}else{
			return true;
		}
	}

	public function is_application_ineligible_via_ipr($application_id, $user_role)
	{
		$user_id = $this->session->userdata('user_id');
		if($user_role == 2)
		{
			$role_key = 'r1_status';
		} else if($user_role == 3)
		{
			$role_key = 'r2_status';
		}
		$ipr_details = $this->section_model->get_ipr_details($application_id);
		$mismatch_count = 0;
		$nominee_count = 0;
		foreach ($ipr_details as $key => $single_section) {
			if($single_section['ipr'] == 1)
			{
				if($single_section[$role_key] == 2 OR $single_section[$role_key] == 0)
				{
					$mismatch_count = $mismatch_count+1;
				}
				if($single_section[$role_key] != 1)
				{
					$nominee_count = $nominee_count+1;
				}		
			}
		}

		if($user_role == 3 && $nominee_count == 0)
			{
				$update_data = array('nominee' => 1);
					$this->application_model->update_app_data($update_data, $application_id);
					//update logs
					$insert_data = array('application_id' => $application_id,
					'comment' => 'Application nominee flag enabled',
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
					$this->application_model->insert_application_log($insert_data);
		}
		if($mismatch_count > 0 && $user_role == 3)
		{
			return true;
		}else{
			return false;
		}
	}

	public function directory()
	{
		$data['page_title'] = 'Directory - DPGA';
		$data['page_heading'] = 'Directory';
		$user_id = $this->session->userdata('user_id');
		$user_role = $this->session->userdata('user_role');
		$data['show_data_table'] = TRUE;
		$data['user_details'] = $this->users_model->get_applicant_details($user_id);
		$data['list_directory'] = $this->application_model->list_directory();

		$data['all_tags_master_array'] = $this->application_model->all_tags_list();
		$data['all_app_status_for_reviewer'] = $this->application_model->all_app_status_for_reviewer($user_role);

		$this->load->view('reviewer/header', $data);
		$this->load->view('reviewer/list_directory', $data);
		$this->load->view('reviewer/footer', $data);
	}


	public function application_details($application_id)
	{
		$data['page_title'] = 'Application Details - DPGA';
		$data['page_heading'] = 'Application Details';
		$user_id = $this->session->userdata('user_id');
		$user_role = $this->session->userdata('user_role');
		$data['user_details'] = $this->users_model->get_applicant_details($user_id);
		$data['solution_name'] = $this->application_model->get_solution_name($application_id);
		$data['application_id'] = $application_id;
		$data['ind_application_data'] = $ind_application_data = $this->application_model->ind_application_data($application_id);
		$all_app_status_for_reviewer = $this->application_model->all_app_status_for_reviewer($user_role);
		$reviewer_status_array = array_column($all_app_status_for_reviewer, 'reviewer');
 		$reviewer_status_key_array = array_column($all_app_status_for_reviewer, 'id');
 		$key_id = array_search($ind_application_data[0]['status'],$reviewer_status_key_array,true);
 		$data['all_application_response'] = $this->application_model->all_application_response($application_id);
 		$data['application_logs'] = $this->application_model->get_application_logs($application_id);
 		$data['current_application_status'] = $reviewer_status_array[$key_id];
		$this->load->view('reviewer/header', $data);
		$this->load->view('reviewer/ind_application_details', $data);
		$this->load->view('reviewer/footer', $data);
	}

	public function update_experts_list()
	{
		if ($this->input->post()) {
			
			$application_id = $this->session->userdata('current_review_id');
			$section_id = $this->session->userdata('current_section_id');
			$user_id = $this->session->userdata('user_id');
			if($this->application_model->is_application_under_consultation($application_id))
			{
				//$consultant = implode(",", $this->input->post('consultant'));
				//$consultation_reason = $this->input->post('consultation_reason');
				$consultant_array = $this->input->post('new_consultant_list');
				foreach ($consultant_array as $key => $cdata) {
					$c_insert_data = array('consultant_id' => $cdata,
					'application_id' => $application_id,
					'section_id' => $section_id,
					'question' => '',
					'added_on' => date('Y-m-d H:i:s'));
					$this->application_model->replace_consultant_questions($cdata, $c_insert_data);
				}
				$consultant_string = implode(",", $consultant_array);
				$data = array('consultant' => $consultant_string);
				$this->section_model->update_section_data($section_id, $application_id, $data);

				//update logs
			$log_role = $this->session->userdata('user_role_details');
			$log_name = $this->session->userdata('user_fullname');
			$solution_name = $this->application_model->get_solution_name($application_id);
			$section_name = $this->application_model->get_section_name($current_section_id);
			$log_string = "$log_name ($log_role) edited the list of consulting experts on $section_name for $solution_name ($application_id)";
			$insert_data = array('application_id' => $application_id,
					'comment' => $log_string,
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
					$this->application_model->insert_application_log($insert_data);

				//send email alert
				$reviewer_name = $this->session->userdata('user_fullname');
				$this->email_alert_for_expert($consultant_array, $application_id, $section_id, $reviewer_name);

			}
		}
	}

	public function finish_consultation($section_id)
	{
		$application_id = $this->session->userdata('current_review_id');
		$user_id = $this->session->userdata('user_id');
		$user_role = $this->session->userdata('user_role');
		if($this->is_assigned_to_me($application_id, $user_role, $user_id))
		{
			if($this->application_model->is_application_under_consultation($application_id))
			{
				//all good update section l2 review status
				$data = array('r2_status' => 5);
				$this->section_model->update_section_data($section_id, $application_id, $data);
				//update logs
				$insert_data = array('application_id' => $application_id,
					'comment' => 'Consultant request resolved',
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
					$this->application_model->insert_application_log($insert_data);
				redirect('/process/review', 'refresh');
				exit();

			}else{
				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Application Status Issue !!</strong> Application status is not in required state.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
			redirect('/reviewer', 'refresh');
			exit();
			}

		}else{
			$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Permission Issue !!</strong> Application is not assigned to reviewer.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
			redirect('/reviewer', 'refresh');
			exit();
		}
	}

	//this is for testing purpose, we need to test several conditions in the web. So here we create an instant application
	public function generate_cron_application($applicant_id = 0)
	{
		//first get any active applicant id if not provided by url
		
		if($applicant_id == 0)
		{
			$applicant_id = $this->users_model->get_single_active_applicant_id();
		}
		//get active sections
		//$this->get_random_name(5);
		$application_id = $this->application_model->create_application_applicant($applicant_id);
		$insert_data = array('application_id' => $application_id,
					'comment' => 'Application created by the system',
					'perform_on' => date('Y-m-d H:i:s') );
		$this->application_model->insert_application_log($insert_data);
		/*
		$project_name_id = PROJECT_NAME_ID;
		$section_id = SECTION_ID;
		$insert_data = array('application_id' => $application_id,
				'section_id' => $section_id,
				'question_id' => $project_name_id,
				'answer' => $this->get_random_name(2),
				'response_time'=> date('Y-m-d H:i:s'));
		$this->application_model->insert_single_answer($insert_data);
		*/
		$this->load->model('section_model');
		$list_all_active_sections_array = $this->section_model->list_all_active_sections();
		$bulk_insert_data = array();
		foreach ($list_all_active_sections_array as $key => $single_section) {
				$single_array = array(
					'application_id'=> $application_id,
					'section_id'=> $single_section['id'],
					'filling_status'=> 1
				);
				array_push($bulk_insert_data,$single_array);
		}
		if(count($bulk_insert_data) > 0)
		{
				$this->section_model->insert_bulk_section_data($bulk_insert_data);
		}
		
		//now insert answers
		// $application_id = 10;
		//get all application questions
		$bulk_response_array = array();
		$all_qids = $this->application_model->get_qid_with_section_id($application_id);
		
		foreach ($all_qids as $key => $single_question) {
			$qid = $single_question['qid'];
			$answer = "";
			if($qid == 3)
			{
				$answer = "Open Software";
			} else if($qid == 9)
			{
				$answer = "SDG1: End Poverty in all its forms everywhere";
			} else if($qid == 11)
			{
				$answer = "MIT";
			} else if($qid == 15)
			{
				$answer = "Yes";
			} else if($qid == 18)
			{
				$answer = "Yes";
			} else if($qid == 22)
			{
				$answer = "PII data is collected and stored but NOT distributed.";
			} else if($qid == 25)
			{
				$answer = "Content is collected and stored but NOT distributed.";
			} else if($qid == 28)
			{
				$answer = "Yes";
			} else if($qid == 7)
			{
				$answer = "South Africa,India";
			} else if($qid == 8)
			{
				$answer = "Tonga,India";
			} else{
				$answer = $this->get_random_name(4);
			}
			$section_id = $single_question['section_id'];
			$single_response_array = array("application_id" => $application_id,
				"section_id" => $section_id,
				"question_id" => $qid,
				"answer" => $answer,
				"response_time" => date('Y-m-d H:i:s'));

			array_push($bulk_response_array, $single_response_array);
		}

		$this->application_model->insert_answers_in_bulk($bulk_response_array);
		//now enter log
		//update application logs
		$insert_data = array('application_id' => $application_id,
					'comment' => 'Application auto fill by system',
					'perform_on' => date('Y-m-d H:i:s') );
		$this->application_model->insert_application_log($insert_data);

		//now submit application
		$update_data = array('status' => 1, 'submitted_on' => date('Y-m-d H:i:s'));
		//now all things in correct, we update the application status
		$this->application_model->update_application_data($update_data, $application_id, $applicant_id);

		$insert_data = array('application_id' => $application_id,
					'comment' => 'Application submitted by the system',
					'perform_on' => date('Y-m-d H:i:s') );
		$this->application_model->insert_application_log($insert_data);

		//handle graph1 entry
		$this->application_model->update_graph1_data("application_received");

		echo "All good, Application created and submitted";

	}

	public function get_random_name($size=10)
	{
		$some_words = array("xiaomi", "lenovo", "leadership", "manthan", "some", "categorical", "effect", "expected", "shift", "the", "outcome");
		$newword = "";
		for ($i=0; $i < $size; $i++) { 
			//$randowm_word = "govind";
			$randowm_key = array_rand($some_words);
			$randowm_word = $some_words[$randowm_key];
			$newword = "$newword $randowm_word";
		}

		return $newword;
	}


	function is_application_nominee($application_id)
	{
		return $this->application_model->is_application_nominee($application_id);
	}


	function email_alert_for_become_dpg($application_id)
	{
		$is_email_service_enable = IS_EMAIL_SERVICE_ENABLE;
		

		if($is_email_service_enable)
		{
			$data['solution_name'] = $solution_name = $this->application_model->get_solution_name($application_id);
			$applicant_id = $this->application_model->get_applicant_id($application_id);
			$data['application_id'] = $application_id;
				$this->load->model('users_model');
				//SMTP Email Start
				$this->load->config('email');
				$this->load->library('email');
				$from = $this->config->item('smtp_user');
				$display_name = $this->config->item('smtp_display_name');
				$to = $this->users_model->get_applicant_email($applicant_id);
				$data['applicant_name'] = $this->users_model->get_user_fullname($applicant_id);
				$subject = "Your DPG application for $solution_name ($application_id) has been approved as a DPG!";
				$message = $this->load->view('emails/e8_l2_review_decision_dpg', $data, true);
				
				$this->email->set_newline("\r\n");
				$this->email->from($from,$display_name);
				$this->email->to($to);
				$this->email->cc('ricardo@digitalpublicgoods.net','jameson@digitalpublicgoods.net','sarah@digitalpublicgoods.net');
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();
				//SMTP Email End

		}
	}

	function email_alert_for_ineligible_dpg($application_id)
	{
		$is_email_service_enable = IS_EMAIL_SERVICE_ENABLE;
		

		if($is_email_service_enable)
		{
			$data['solution_name'] = $solution_name = $this->application_model->get_solution_name($application_id);
			$applicant_id = $this->application_model->get_applicant_id($application_id);
			$data['application_id'] = $application_id;
				$this->load->model('users_model');
				//SMTP Email Start
				$this->load->config('email');
				$this->load->library('email');
				$from = $this->config->item('smtp_user');
				$display_name = $this->config->item('smtp_display_name');
				$to = $this->users_model->get_applicant_email($applicant_id);
				$data['applicant_name'] = $this->users_model->get_user_fullname($applicant_id);
				$subject = "Your DPG application for $solution_name ($application_id) could not be approved as a DPG";
				$message = $this->load->view('emails/e9_l2_review_decision_ineligible', $data, true);
				
				$this->email->set_newline("\r\n");
				$this->email->from($from,$display_name);
				$this->email->to($to);
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();
				//SMTP Email End

		}
	}

	function email_alert_for_clarifications_requested($application_id, $time_limit_days)
	{
		$is_email_service_enable = IS_EMAIL_SERVICE_ENABLE;
		

		if($is_email_service_enable)
		{
			$data['solution_name'] = $solution_name = $this->application_model->get_solution_name($application_id);
			$applicant_id = $this->application_model->get_applicant_id($application_id);
			$data['application_id'] = $application_id;
				$this->load->model('users_model');
				//SMTP Email Start
				$this->load->config('email');
				$this->load->library('email');
				$from = $this->config->item('smtp_user');
				$display_name = $this->config->item('smtp_display_name');
				$to = $this->users_model->get_applicant_email($applicant_id);
				$data['applicant_name'] = $this->users_model->get_user_fullname($applicant_id);
				$data['login_link'] = base_url("login");
				$data['time_limit_days'] = $time_limit_days;
				$subject = "Important: Your DPG application for $solution_name ($application_id) requires clarification.";
				$message = $this->load->view('emails/e6_clarifications_requested', $data, true);
				
				$this->email->set_newline("\r\n");
				$this->email->from($from,$display_name);
				$this->email->to($to);
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();
				//SMTP Email End

		}
	}


	function email_alert_for_expert($consultant_array, $application_id, $section_id, $reviewer_name)
	{
		$is_email_service_enable = IS_EMAIL_SERVICE_ENABLE;
		

		if($is_email_service_enable)
		{
			$data['solution_name'] = $solution_name = $this->application_model->get_solution_name($application_id);
			$data['section_name'] = $section_name = $this->application_model->get_section_name($section_id);
			$data['reviewer_name'] = $reviewer_name;
			$data['application_id'] = $application_id;

			foreach ($consultant_array as $key => $cdata) {
					$consultant_id = $cdata;

					$data['expert_name'] = $this->users_model->get_user_fullname($consultant_id);

					//SMTP Email Start
				$this->load->config('email');
				$this->load->library('email');
				$from = $this->config->item('smtp_user');
				$display_name = $this->config->item('smtp_display_name');
				$to = $this->users_model->get_applicant_email($consultant_id);
				$subject = "Your input is requested on $section_name of $solution_name ($application_id).";
				$data['login_link'] = base_url("login");
				
				$message = $this->load->view('emails/e14_input_requested_from_an_expert', $data, true);
				
				$this->email->set_newline("\r\n");
				$this->email->from($from,$display_name);
				$this->email->to($to);
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();
				//SMTP Email End

			}
		}

	}

	public function logout()
	{
		$this->session->sess_destroy();
		delete_cookie('applicant_id');
		delete_cookie('application_id');
		//now redirect user to form
		redirect('/login', 'refresh');
	}
}
