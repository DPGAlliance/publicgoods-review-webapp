<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expert extends CI_Controller {

	function __construct()
	{
        // Call the Model constructor
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$user_role = $this->session->userdata('user_role');
		//This controller only for Reviewers
		if($user_role == 5)
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
		$data['page_title'] = 'Expert Home - DPGA';
		$data['page_heading'] = 'home';
		$data['user_details'] = $this->users_model->get_applicant_details($this->session->userdata('user_id'));
		$data['user_role'] = $user_role = $this->session->userdata('user_role');
		
		
		$user_id = $this->session->userdata('user_id');
		
		$data['under_consultation_application'] = $uca = $this->application_model->get_under_consultation_application_for_expert($user_role, $user_id);

		$app_ids_array = array();
		foreach ($uca as $key => $ind_app) {
			array_push($app_ids_array, $ind_app['id']);
		}

		$data['experts_response'] = $this->application_model->experts_response_for_dashboard($user_role, $user_id, $app_ids_array);
		
		$data['dpga_limits'] = $this->application_model->get_dpga_limits();
		$data['all_tags_master_array'] = $this->application_model->all_tags_list();
		
		//provide a home to user
		$this->load->view('expert/header', $data);
		$this->load->view('expert/home', $data);
		$this->load->view('expert/footer', $data);
	}


	public function start_inputs($application_id)
	{
		$user_role = $this->session->userdata('user_role');
		$user_id = $this->session->userdata('user_id');
		if($this->is_assigned_to_expert($application_id, $user_role, $user_id))
		{
			if($this->application_model->is_application_under_consultation($application_id))
			{
				//all good, now set session
				$this->session->set_userdata('current_review_id', $application_id);
				$this->session->set_userdata('current_section_id', SECTION_ID);
				$this->session->set_userdata('section_revision', array());
				redirect('/process/inputs', 'refresh');
				exit();

			}else{
				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Application Status Issue !!</strong> Application status is not in required state.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
			redirect('/expert', 'refresh');
			exit();
			}
			
		}else{
			$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Permission Issue !!</strong> Application is not assigned to expert.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
			redirect('/expert', 'refresh');
			exit();
		}
	}


	function is_assigned_to_expert($application_id, $user_role, $user_id)
	{
		return $this->application_model->is_assigned_to_expert($application_id, $user_role, $user_id);
	}

	public function process_inputs()
	{
		$current_review_id = $this->session->userdata('current_review_id');
		$data['user_id'] = $user_id = $this->session->userdata('user_id');
		$data['user_role'] = $user_role = $this->session->userdata('user_role');
		$data['application_id'] = $application_id = $this->session->userdata('current_review_id');
		$data['current_section_id'] = $current_section_id = $this->session->userdata('current_section_id');
		
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
			
			$data['dpga_limits'] = $this->application_model->get_dpga_limits();
			$data['all_app_status_for_reviewer'] = $this->application_model->all_app_status_for_reviewer($user_role);

			//get question details
			$data['all_section_questions'] = $this->application_model->get_all_section_questions($current_section_id);
			//get all answers
			$data['all_section_answers'] = $this->application_model->get_all_section_answers($current_section_id, $application_id);
			$data['consultant_list'] = $this->users_model->consultant_list();

			$data['consultant_response'] = $consultant_response = $this->application_model->get_all_consultant_response_new($current_section_id, $application_id);

			$data['consultant_response_section_wise'] = $this->application_model->get_all_consultant_response($current_section_id, $application_id);
			//echo "<pre>";
			//print_r($consultant_response);
			//echo "<pre>";

			$this->load->view('expert/header', $data);
			$this->load->view('expert/review_application', $data);
			$this->load->view('expert/footer', $data);

		}else{
			$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Error !!</strong> Please start again to give inputs in application.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
			redirect('/expert', 'refresh');
		}
	}


	public function change_section($section_id)
	{
		$this->session->set_userdata('current_section_id', $section_id);
		redirect('/process/inputs', 'refresh');
		
	}


	public function process_inputs_decision()
	{
		if ($this->input->post())
		{
			$expert_input = $this->input->post('expert_input');
			$input_id = $this->input->post('input_id');
			$consultant_id = $this->session->userdata('user_id');
			$application_id = $this->session->userdata('current_review_id');
			$update_data = array('response' => $expert_input,
			'response_on' => date('Y-m-d H:i:s'));
			$this->application_model->consultant_inputs($update_data, $application_id,$consultant_id,$input_id);
			$log_role = $this->session->userdata('user_role_details');
			$log_name = $this->session->userdata('user_fullname');
			$solution_name = $this->application_model->get_solution_name($application_id);
			$section_name = $this->application_model->get_section_name($this->session->userdata('current_section_id'));
			// $log_message = "Input submit by expert";
			$log_message = "$log_name ($log_role) submitted their inputs on $section_name for $solution_name ($application_id) as “input”";
			$insert_data = array('application_id' => $application_id,
					'comment' => $log_message,
					'perform_by' => $consultant_id,
					'section_id' => $this->session->userdata('current_section_id'),
					'perform_on' => date('Y-m-d H:i:s') );
			$this->application_model->insert_application_log($insert_data);
			redirect('/process/inputs', 'refresh');

		}else{
			redirect('/expert', 'refresh');
		}
	}


	public function submit_inputs()
	{
		if ($this->input->post())
		{
			$consultant_id = $this->session->userdata('user_id');
			$application_id = $this->session->userdata('current_review_id');
			$log_message = "All Inputs submit by expert";
			$insert_data = array('application_id' => $application_id,
					'comment' => $log_message,
					'perform_by' => $consultant_id,
					'perform_on' => date('Y-m-d H:i:s') );
			$this->application_model->insert_application_log($insert_data);
			$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
				Inputs submitted successfully.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
			redirect('/expert', 'refresh');
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