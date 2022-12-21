<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General_public extends CI_Controller {

	function __construct()
	{
        // Call the Model constructor
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('users_model');
		$this->load->model('application_model');
		$this->load->model('section_model');
		$this->load->database();
	}

	public function app_public_view($application_id)
	{
		if($this->is_application_exists($application_id))
		{
			
			
			$data['solution_name'] = $solution_name = $this->application_model->get_solution_name($application_id);
			$data['application_id'] = $application_id;
			$data['ind_application_data'] = $ind_application_data = $this->application_model->ind_application_data($application_id);
			$all_app_status_for_applicant = $this->application_model->all_app_status_for_applicant();
			$applicant_status_array = array_column($all_app_status_for_applicant, 'applicant');
	 		$applicant_status_key_array = array_column($all_app_status_for_applicant, 'id');
	 		$key_id = array_search($ind_application_data[0]['status'],$applicant_status_key_array,true);
	 		$data['all_application_response'] = $this->application_model->all_application_response($application_id);
	 		$data['application_logs'] = $this->application_model->get_application_logs($application_id);
	 		$data['current_application_status'] = $applicant_status_array[$key_id];



			$data['page_title'] = "$solution_name - DPGA Details";
		
			//provide a home to user
			$this->load->view('public/header', $data);
			$this->load->view('public/app_details', $data);
			$this->load->view('public/footer', $data);
		}else{
			echo "Application not found, Please recheck public url link.";
		}


		
	}

	public function legal_page_view()
	{
		$data['page_title'] = "Legal - DPGA";
		
			//provide a home to user
			$this->load->view('public/header', $data);
			$this->load->view('public/legal', $data);
			$this->load->view('public/footer', $data);
	}

	public function is_application_exists($application_id)
	{
		return $this->application_model->is_application_exists($application_id);
	}

	
	
}
