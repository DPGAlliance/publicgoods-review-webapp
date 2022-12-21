<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	function __construct()
	{
        // Call the Model constructor
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$user_role = $this->session->userdata('user_role');
		//This controller only for Reviewers
		if($user_role == 1)
		{
			
		} else{
			redirect('/login', 'refresh');
			exit();
		}
		
		
		$this->load->helper('form');
		$this->load->model('users_model');
		$this->load->model('application_model');
		$this->load->model('section_model');
		$this->load->database();
	}


	public function home($filter_mode="yesterday")
	{
		$data['page_title'] = 'Admin Home - DPGA';
		$data['page_heading'] = 'home';
		$data['user_details'] = $this->users_model->get_applicant_details($this->session->userdata('user_id'));
		$data['user_role'] = $user_role = $this->session->userdata('user_role');
		$data['active_menu'] = "Dashboard";

		$data['show_graph'] = TRUE;

		$data['filter_mode'] = $filter_mode;
		if($filter_mode == "yesterday")
		{
			$filter_string = "- 1 day";
			$main_date = date('Y-m-d', strtotime($filter_string));
			$date1_start = date('Y-m-d 00:00:01',strtotime($main_date));
			$date2_end = date('Y-m-d 23:59:59',strtotime($main_date));
		}else if($filter_mode == "week"){
			$filter_string = "- 7 day";
			$date1_start = date('Y-m-d 00:00:01',strtotime($filter_string));
			$date2_end = date('Y-m-d 23:59:59',strtotime("now"));
		}else if($filter_mode == "month"){
			$filter_string = "- 30 day";
			$date1_start = date('Y-m-d 00:00:01',strtotime($filter_string));
			$date2_end = date('Y-m-d 23:59:59',strtotime("now"));
		}else{
			$filter_string = "- 5 years";
			$date1_start = date('Y-m-d 00:00:01',strtotime($filter_string));
			$date2_end = date('Y-m-d 23:59:59',strtotime("now"));
		}
		//now get data from db
		$status_array = array(0);
		$data['not_complete'] = $this->application_model->count_applications($status_array, $date1_start, $date2_end);
		$status_array = array(1);

		$data['applications_received'] = $this->application_model->count_applications($status_array, $date1_start, $date2_end);

		$status_array = array(7,8);
		$data['review_completed'] = $this->application_model->count_completed_applications($status_array, $date1_start, $date2_end);

		$data['dpgs_count'] = $this->application_model->count_applications_through_status(8);

		$data['nominees_count'] = $this->application_model->count_nominees_applications();

		$data['ineligible_count'] = $this->application_model->count_applications_through_status(7);

		//handle graph1 data
		$data['graph1_data'] = $this->application_model->get_graph1_data($date1_start, $date2_end);

		$data['status_wise_count'] = $this->application_model->status_wise_count();
		$data['all_app_status_for_admin'] = $this->application_model->all_app_status_for_reviewer($user_role);

		$days_difference = $this->application_model->days_difference();
		$final_graph_array = array();
foreach ($days_difference as $key => $single_day_data) {
	if($single_day_data['days_difference'])
	{
		$key_number = $single_day_data['days_difference'];
		$key_value = "$key_number days";
		if (array_key_exists($key_value,$final_graph_array))
		  {
		  	$final_graph_array[$key_value] = $final_graph_array[$key_value] + 1;
		  }
		else
		  {
		  	$final_graph_array[$key_value] = 1;
		  }
		
	}else{
		if (array_key_exists("0 days",$final_graph_array))
		  {
		  	$final_graph_array['0 days'] = $final_graph_array['0 days'] + 1;
		  }
		else
		  {
		  	$final_graph_array['0 days'] = 1;
		  }
	}
}

		$data['graph3_data_array'] = $final_graph_array;

		$status_array = array(1,2,3,4,5,6);
		$data['count_pending_applications'] = $this->application_model->count_pending_applications($status_array);
		$filter_string = "- 7 day";
		$date1_start = date('Y-m-d 00:00:01',strtotime($filter_string));
		$date2_end = date('Y-m-d 23:59:59',strtotime("now"));
		$status_array = array(7,8);
		$count_completed_applications = $this->application_model->count_completed_applications($status_array, $date1_start, $date2_end);
		$data['per_day_app_process'] = round($count_completed_applications/7, 1);


		
		$data['status_wise_count_with_late'] = $this->application_model->status_wise_count_with_late();




		//provide views
		$this->load->view('admin/header', $data);
		$this->load->view('admin/left_menu', $data);
		$this->load->view('admin/home', $data);
		$this->load->view('admin/footer', $data);
	}


	public function import_old_data()
	{
		$data['page_title'] = 'Import Data - Admin DPGA';
		$data['page_heading'] = 'home';
		$data['user_details'] = $this->users_model->get_applicant_details($this->session->userdata('user_id'));
		$data['user_role'] = $user_role = $this->session->userdata('user_role');
		$data['active_menu'] = "Import Data";
		$old_dpg_data = $this->get_old_dpg_data_from_apis();

		$data['new_applications'] = $this->application_model->get_new_applications();
		$data['old_dpg_array'] = $old_dpg_array = json_decode($old_dpg_data,true);
		//echo "<pre>";
		//print_r($old_dpg_array);
		//echo "<pre>";


		// provide views
		$this->load->view('admin/header', $data);
		$this->load->view('admin/left_menu', $data);
		$this->load->view('admin/old_dpg_list', $data);
		$this->load->view('admin/footer', $data);
	}

	public function get_old_dpg_data_from_apis()
	{
        $url = 'https://api.digitalpublicgoods.net/dpgs/';
             
        /* Init cURL resource */
        $ch = curl_init($url);
            
        /* set return type json */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
        /* execute request */
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
	}


	

	public function users_list()
	{
		$data['page_title'] = 'Users List - Admin DPGA';
		$data['page_heading'] = 'home';
		$data['user_details'] = $this->users_model->get_applicant_details($this->session->userdata('user_id'));
		$data['user_role'] = $user_role = $this->session->userdata('user_role');
		$data['active_menu'] = "Users List";
		$data['show_data_table'] = TRUE;
		$data['users_list'] = $this->users_model->get_all_users();

		// provide views
		$this->load->view('admin/header', $data);
		$this->load->view('admin/left_menu', $data);
		$this->load->view('admin/page_menus/users_pagemenu', $data);
		$this->load->view('admin/users_list', $data);
		$this->load->view('admin/footer', $data);
	}


	public function create_new_user()
	{
		if ($this->input->post()) {

			$user_email = $this->input->post('user_email');
			$user_password = md5($this->input->post('user_password'));
			$user_fullname = $this->input->post('user_fullname');
			$user_role = $this->input->post('user_role');
			$user_status = $this->input->post('user_status');
			$user_email_notify = $this->input->post('user_email_notify');

			if($user_role == 1)
				{
					$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>You can not add Admin Users
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
				redirect('/admin/users', 'refresh');
				exit();
				}

			//first we check is email already registered with us or not
			if($this->users_model->user_already_exists($user_email))
			{
				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Email already registered.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
				redirect('/admin/users', 'refresh');
				exit();
			}else{
				//now create the user
				$insert_data = array('email' => $user_email,
					'password' => $user_password,
					'role' => $user_role,
					'fname' => $user_fullname,
					'enroll_time' => date('Y-m-d H:i:s'),
					'status' => $user_status);
				$this->users_model->create_user($insert_data);

				//now send alert to user
				if($user_email_notify)
				{
					$this->send_account_details_on_email($user_email);
				}

				$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>User added successfully.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
				redirect('/admin/users', 'refresh');
				exit();

			}




			

		}
	}


	public function update_user()
	{
		if ($this->input->post()) {

			$user_id = $this->input->post('user_id');
			$user_fullname = $this->input->post('user_fullname');
			$user_role = $this->input->post('user_role');
			$user_status = $this->input->post('user_status');

			if($user_role == 1)
				{
					$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>You can not Modify Admin Users
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
				redirect('/admin/users', 'refresh');
				exit();
				}
			//now create the user
				$update_data = array('role' => $user_role,
					'fname' => $user_fullname,
					'status' => $user_status);
			$this->users_model->update_user_data($user_id, $update_data);
			$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>User modified successfully.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
				redirect('/admin/users', 'refresh');
				exit();
		}
	}

	public function sections_list()
	{
		$data['page_title'] = 'Sections List - Admin DPGA';
		$data['page_heading'] = 'home';
		$data['user_details'] = $this->users_model->get_applicant_details($this->session->userdata('user_id'));
		$data['user_role'] = $user_role = $this->session->userdata('user_role');
		$data['active_menu'] = "Sections List";
		$data['show_data_table'] = TRUE;
		$data['sections_list'] = $this->section_model->list_all_sections();


		// provide views
		$this->load->view('admin/header', $data);
		$this->load->view('admin/left_menu', $data);
		$this->load->view('admin/page_menus/sections_pagemenu', $data);
		$this->load->view('admin/sections_list', $data);
		$this->load->view('admin/footer', $data);
	}

	function update_section()
	{
		if ($this->input->post()) {

			$section_id = $this->input->post('section_id');
			$section_name = $this->input->post('section_name');
			$section_heading = $this->input->post('section_heading');
			$section_details = $this->input->post('section_details');
			$section_visible_order = $this->input->post('section_visible_order');
			$section_status = $this->input->post('section_status');

			if($this->section_model->is_section_exists($section_id))
			{
				$update_data = array('name' => $section_name,
					'heading' => $section_heading,
					'details' => $section_details,
					'visible_order' => $section_visible_order,
					'status' => $section_status);
				$this->section_model->update_main_section($section_id, $update_data);
				$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>Section details updated successfully.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
				redirect('/admin/sections', 'refresh');
				exit();
			}else{
				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Section ID not valid.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
				redirect('/admin/sections', 'refresh');
				exit();
			}

		}
	}


	public function create_new_section()
	{
		if ($this->input->post()) {

			$section_name = $this->input->post('section_name');
			$section_heading = $this->input->post('section_heading');
			$section_details = $this->input->post('section_details');
			$section_visible_order = $this->input->post('section_visible_order');
			$section_status = $this->input->post('section_status');
			$insert_data = array('name' => $section_name,
					'heading' => $section_heading,
					'details' => $section_details,
					'visible_order' => $section_visible_order,
					'status' => $section_status);
			$this->section_model->create_new_section($insert_data);
			$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>Section created successfully.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
				redirect('/admin/sections', 'refresh');
				exit();

		}
	}

	public function questions_list($section_id = 0)
	{
		$data['page_title'] = 'Questions List - Admin DPGA';
		$data['page_heading'] = 'home';
		$data['user_details'] = $this->users_model->get_applicant_details($this->session->userdata('user_id'));
		$data['user_role'] = $user_role = $this->session->userdata('user_role');
		if($section_id > 0)
		{
			// $data['active_menu'] = "";
			$data['active_menu'] = "Questions List";
			$data['sub_active_menu'] = "Filtered Data";
			$data['filter_text'] = $this->section_model->get_section_name($section_id);
		}else{
			$data['active_menu'] = "Questions List";
			$data['sub_active_menu'] = "All Questions List";
			$data['filter_text'] = "";
		}
		
		$data['show_data_table'] = TRUE;
		$data['questions_list'] = $this->application_model->list_all_questions_in_system($section_id);
		$data['list_all_sections'] = $this->section_model->list_all_sections();


		// provide views
		$this->load->view('admin/header', $data);
		$this->load->view('admin/left_menu', $data);
		$this->load->view('admin/page_menus/questions_pagemenu', $data);
		$this->load->view('admin/questions_list', $data);
		$this->load->view('admin/footer', $data);
	}


	public function update_question()
	{
		if ($this->input->post()) {
			$question_id = $this->input->post('question_id');
			$name = $this->input->post('name');
			$placeholder = $this->input->post('placeholder');

			$description = $this->input->post('description');
			$visible_order = $this->input->post('visible_order');
			$options = $this->input->post('options');

			$lineheight = $this->input->post('lineheight');
			$required = $this->input->post('required');
			$status = $this->input->post('status');
			$section_id = $this->input->post('section_id');


			$update_data = array('name' => $name,
				'placeholder' => $placeholder,
				'description' => $description,
				'visible_order' => $visible_order,
				'options' => $options,
				'lineheight' => $lineheight,
				'required' => $required,
				'status' => $status,
				'section_id' => $section_id,
			);
			$this->application_model->update_question_details($question_id, $update_data);

			$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>Question updated successfully.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
				redirect('/admin/questions', 'refresh');
				exit();
		}
	}

	function create_new_question($question_type)
	{
		if ($this->input->post()) {

			$insert_data = array('name' => $this->input->post('name'),
				'placeholder' => $this->input->post('placeholder'),
				'description' => $this->input->post('name'),
				'type' => $this->input->post('type'),
				'visible_order' => $this->input->post('visible_order'),
				'options' => $this->input->post('options'),
				'lineheight' => $this->input->post('lineheight'),
				'required' => $this->input->post('required'),
				'status' => $this->input->post('status'),
				'section_id' => $this->input->post('section_id'),
			);

			$this->application_model->create_new_question($insert_data);
			$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>Question added successfully.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
				redirect('/admin/questions', 'refresh');
				exit();
		}
	}




	public function send_account_details_on_email($email)
	{
		$user_ind_details = $this->users_model->get_user_details($email);
		$user_fullname = $user_ind_details[0]['fname'];
		$user_role = $user_ind_details[0]['role'];
		$user_role_in_text = "";
		if($user_role == 2)
		{
			$user_role_in_text = "L1 reviewer";
		}else if ($user_role == 3)
		{
			$user_role_in_text = "L2 reviewer";
		}else if ($user_role == 4)
		{
			$user_role_in_text = "Applicant";
		}else if ($user_role == 5)
		{
			$user_role_in_text = "Expert";
		}else{
			$user_role_in_text = "NA";
		}

		// now send email
		$is_email_service_enable = IS_EMAIL_SERVICE_ENABLE;
				if($is_email_service_enable)
				{
				//SMTP Email Start
				$this->load->config('email');
				$this->load->library('email');
				$from = $this->config->item('smtp_user');
				$display_name = $this->config->item('smtp_display_name');
				$to = $email;
				$subject = "Account Created on Portal";

				$main_url = base_url();
				$data['login_link'] ="" .$main_url. "login";
				$data['user_fullname'] = $user_fullname;
				$message = $this->load->view('emails/send_account_details_on_email', $data, true);
				$this->email->set_newline("\r\n");
				$this->email->from($from,$display_name);
				$this->email->to($email);
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();
			}
	}

	public function applications_list()
	{
		$data['page_title'] = 'Applications List - Admin DPGA';
		$data['page_heading'] = 'home';
		$data['user_details'] = $this->users_model->get_applicant_details($this->session->userdata('user_id'));
		$data['user_role'] = $user_role = $this->session->userdata('user_role');
		$data['active_menu'] = "Applications List";
		$data['filter_text'] = "";
		$data['sub_active_menu'] = "All Applications";
		
		$data['show_data_table'] = TRUE;
		$data['list_directory'] = $this->application_model->list_directory();

		$data['all_tags_master_array'] = $this->application_model->all_tags_list();
		$data['all_app_status_for_admin'] = $this->application_model->all_app_status_for_reviewer($user_role);


		// provide views
		$this->load->view('admin/header', $data);
		$this->load->view('admin/left_menu', $data);
		$this->load->view('admin/page_menus/applications_pagemenu', $data);
		$this->load->view('admin/applications_list', $data);
		$this->load->view('admin/footer', $data);
	}

	public function application_details($application_id)
	{
		$data['page_title'] = "Application Details - $application_id - Admin DPGA";
		$data['page_heading'] = 'home';
		$data['user_details'] = $this->users_model->get_applicant_details($this->session->userdata('user_id'));
		$data['user_role'] = $user_role = $this->session->userdata('user_role');
		$data['active_menu'] = "Applications List";
		$data['filter_text'] = "Application Details - $application_id";
		$data['sub_active_menu'] = "Filtered Data";
		
		$data['show_data_table'] = TRUE;

		$data['all_active_reviewers'] = $this->users_model->get_all_active_reviewers();

		$data['solution_name'] = $this->application_model->get_solution_name($application_id);
		$data['application_id'] = $application_id;
		$data['ind_application_data'] = $ind_application_data = $this->application_model->ind_application_data($application_id);
		$all_app_status_for_admin = $this->application_model->all_app_status_for_reviewer($user_role);
		$data['admin_status_array'] = $admin_status_array = array_column($all_app_status_for_admin, 'reviewer');
 		$admin_status_key_array = array_column($all_app_status_for_admin, 'id');
 		$key_id = array_search($ind_application_data[0]['status'],$admin_status_key_array,true);
 		$data['all_application_response'] = $this->application_model->all_application_response($application_id);
 		$data['application_logs'] = $this->application_model->get_application_logs($application_id);
 		$data['current_application_status'] = $admin_status_array[$key_id];
 		$data['all_tags_master_array'] = $this->application_model->all_tags_list();


		// provide views
		$this->load->view('admin/header', $data);
		$this->load->view('admin/left_menu', $data);
		$this->load->view('admin/page_menus/applications_pagemenu', $data);
		$this->load->view('admin/ind_applications_details', $data);
		$this->load->view('admin/footer', $data);
	}

	function update_application()
	{
		if ($this->input->post()) {

			$user_id = $this->session->userdata('user_id');
			$update_type = $this->input->post("update_type");
			$application_id = $this->input->post("application_id");
			$log_role = $this->session->userdata('user_role_details');
			$log_name = $this->session->userdata('user_fullname');
			$solution_name = $this->application_model->get_solution_name($application_id);
			if($update_type == "application_status")
			{
				$application_status = $this->input->post("application_status");
				if($application_status == 8)
				{
					$update_data = array('status' => $application_status,
						'nominee' => 0,
						'expire_on' => date('Y-m-d H:i:s', strtotime(' + 1 years')));
				}else{
					$update_data = array('status' => $application_status,
						'expire_on' => NULL);
				}
				


				$this->application_model->update_app_data($update_data, $application_id);
				//manage logs
				$log_string = "$log_name ($log_role) over ride decision for $solution_name ($application_id)";
				$insert_data = array('application_id' => $application_id,
					'comment' => $log_string,
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
				$this->application_model->insert_application_log($insert_data);
				$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
					<strong>Success !!</strong> Application status override successfully.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
					redirect("admin/application/$application_id", 'refresh');
					exit();
			}
			if($update_type == "update_l1")
			{
				$l1_reviewer = $this->input->post("l1_reviewer");
				$update_data = array('current_l1' => $l1_reviewer,
					'l1_assign_on' => date('Y-m-d H:i:s'),
					'l1_timer' => 1);
				$this->application_model->update_app_data($update_data, $application_id);
				//manage logs
				$log_string = "$log_name ($log_role) update L1 Reviewer for $solution_name ($application_id)";
				$insert_data = array('application_id' => $application_id,
					'comment' => $log_string,
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
				$this->application_model->insert_application_log($insert_data);
				$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
					<strong>Success !!</strong> L1 Reviewer update successfully.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
					redirect("admin/application/$application_id", 'refresh');
					exit();
			}
			if($update_type == "update_l2")
			{
				$l2_reviewer = $this->input->post("l2_reviewer");
				$update_data = array('current_l2' => $l2_reviewer,
					'l2_assign_on' => date('Y-m-d H:i:s'),
					'l2_timer' => 1);
				$this->application_model->update_app_data($update_data, $application_id);
				//manage logs
				$log_string = "$log_name ($log_role) update L2 Reviewer for $solution_name ($application_id)";
				$insert_data = array('application_id' => $application_id,
					'comment' => $log_string,
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
				$this->application_model->insert_application_log($insert_data);
				$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
					<strong>Success !!</strong> L2 Reviewer update successfully.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
					redirect("admin/application/$application_id", 'refresh');
					exit();
			}
		}
	}

	public function add_priority($application_id)
	{
		$this->update_tag_in_application($application_id, 1);
		$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
					Priority tag added successfully.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
					redirect("admin/application/$application_id", 'refresh');
	}

	public function remove_priority($application_id)
	{
		$this->remove_tag_in_application($application_id, 1);
		$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
					Priority tag removed successfully.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
		redirect("admin/application/$application_id", 'refresh');
	}

	public function update_tag_in_application($application_id, $tag_id)
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
		  	return true;
		  	exit();
		  }
		else
		  {
		  array_push($tags_array, $tag_id);
		  $new_tags_string = implode(',', $tags_array);
		  $update_data = array('tags' => $new_tags_string);
		  $this->application_model->update_app_data($update_data, $application_id);

		  //update logs
		  $user_id = $this->session->userdata('user_id');
		  $tag_name = $this->application_model->get_tag_name($tag_id);
		  $log_role = $this->session->userdata('user_role_details');
		$log_name = $this->session->userdata('user_fullname');
		$solution_name = $this->application_model->get_solution_name($application_id);
		$log_string = "$log_name ($log_role) add new tag $tag_name to $solution_name ($application_id)";
		  
		  $comment ="New tag ($tag_name) added to application";
		  //update application logs
				$insert_data = array('application_id' => $application_id,
					'comment' => $log_string,
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
				$this->application_model->insert_application_log($insert_data);
				return true;
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
			$log_string = "$log_name ($log_role) remove tag $tag_name from $solution_name ($application_id)";
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

	public function edit_response($application_id, $section_id, $question_id)
	{

		$data['page_title'] = "Update Application Response Details - $application_id - Admin DPGA";
		$data['page_heading'] = 'home';
		$data['user_details'] = $this->users_model->get_applicant_details($this->session->userdata('user_id'));
		$data['user_role'] = $user_role = $this->session->userdata('user_role');
		$data['active_menu'] = "Applications List";
		$data['filter_text'] = "Application Details - $application_id";
		$data['sub_active_menu'] = "Filtered Data";
		
		$data['solution_name'] = $this->application_model->get_solution_name($application_id);
		$data['section_name'] = $this->section_model->get_section_name($section_id);
		$data['application_id'] = $application_id;
		$data['section_id'] = $section_id;
		$data['question_id'] = $question_id;
		$data['ind_question_details'] = $this->application_model->ind_question_details($question_id);
		$data['get_response_details'] = $this->application_model->get_response_details($question_id, $section_id, $application_id);
		


		// provide views
		$this->load->view('admin/header', $data);
		$this->load->view('admin/left_menu', $data);
		$this->load->view('admin/page_menus/applications_pagemenu', $data);
		$this->load->view('admin/edit_response', $data);
		$this->load->view('admin/footer', $data);
	}

	public function update_response()
	{
		if ($this->input->post()) {

			$q_type = $this->input->post('q_type');
			$application_id = $this->input->post('application_id');
			$question_id = $this->input->post('question_id');
			$section_id = $this->input->post('section_id');
			$q_type = $this->input->post('q_type');
			$new_response = $this->input->post($question_id);
			$accepted_response = NULL;
			if($q_type == "multiple_select")
			{
				$accepted_response = implode(",", $new_response);
			} else {
				$accepted_response = $new_response;
			}
			

			//delete old response
			$this->application_model->delete_old_response($question_id, $application_id);
			//now insert new response
			$insert_data = array('application_id' => $application_id,
							'section_id' => $section_id,
							'question_id' => $question_id,
							'answer' => $accepted_response,
							'response_time' => date('Y-m-d H:i:s'));
			$this->application_model->insert_single_answer($insert_data);

			//update logs
			$user_id = $this->session->userdata('user_id');
			$log_role = $this->session->userdata('user_role_details');
			$log_name = $this->session->userdata('user_fullname');
			$solution_name = $this->application_model->get_solution_name($application_id);
			$section_name = $this->application_model->get_section_name($section_id);
			$log_string = "$log_name ($log_role) edited $section_name for $solution_name ($application_id)";
		  //update application logs
				$insert_data = array('application_id' => $application_id,
					'comment' => $log_string,
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
				$this->application_model->insert_application_log($insert_data);
			//now redirect to application page
			$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
					Response updated successfully.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
			redirect("admin/application/$application_id", 'refresh');

		}
	}

	public function update_tags_string()
	{
		if ($this->input->post()) {

			$application_id = $this->input->post('application_id');
			$tags_array = $this->input->post('tags');
			$tags_string = implode(",", $tags_array);
			$update_data = array('tags' => $tags_string);
			$this->application_model->update_app_data($update_data, $application_id);

			//update logs
			//manage logs
			$user_id = $this->session->userdata('user_id');
			$log_role = $this->session->userdata('user_role_details');
			$log_name = $this->session->userdata('user_fullname');
			$solution_name = $this->application_model->get_solution_name($application_id);
			$log_string = "$log_name ($log_role) update Tags for $solution_name ($application_id)";
			$insert_data = array('application_id' => $application_id,
					'comment' => $log_string,
					'perform_by' => $user_id,
					'perform_on' => date('Y-m-d H:i:s') );
			$this->application_model->insert_application_log($insert_data);
			$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>Tags update successfully.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
					redirect("admin/application/$application_id", 'refresh');
			exit();



		}
	}


	function logs_data($application_id = 0, $user_id = 0, $date1 = 0, $date2 = 0, $rowno=0)
	{
		$data['page_title'] = "Log Details - Admin DPGA";
		$data['page_heading'] = 'home';
		$data['user_details'] = $this->users_model->get_applicant_details($this->session->userdata('user_id'));
		$data['user_role'] = $user_role = $this->session->userdata('user_role');
		$data['active_menu'] = "Logs";
		$data['filter_text'] = "";
		$data['sub_active_menu'] = "";
		
		$data['list_directory'] = $this->application_model->list_directory();
		$data['list_users'] = $this->application_model->users_list_for_log_filter();
		$data['application_id'] = $application_id;
		$data['user_id'] = $user_id;
		$data['date1'] = $date1;
		$data['date2'] = $date2;

		//handle pagination
    	$this->load->library('pagination');
    	// Row per page
    	$rowperpage = $this->application_model->get_rows_limit();
    	// $rowperpage = 10;
    	
    	$data['table_row_count_start_value'] = ($rowperpage*($rowno))+1;
    	
    	// Row position
	    if($rowno != 0){
	    	$data['table_row_count_start_value'] = ($rowperpage*($rowno-1))+1;
	      $rowno = ($rowno-1) * $rowperpage;
	    }

	    $apply_filter = 1;
	    $data['logs_list'] = $this->application_model->get_logs_list($application_id, $user_id, $date1, $date2, $rowno, $rowperpage, $apply_filter);
	    $apply_filter = 0;
	    $logs_count = $this->application_model->get_logs_list($application_id, $user_id, $date1, $date2, $rowno, $rowperpage, $apply_filter);
	    // All records count
    	$data['allcount'] =$allcount = count($logs_count);
    	// Pagination Configuration
    	$page_link = base_url("admin/logs/$application_id/$user_id/$date1/$date2/");
    $config['base_url'] = $page_link;
    $config['use_page_numbers'] = TRUE;
    $config['total_rows'] = $allcount;
    $config['per_page'] = $rowperpage;
// Bootstrap 4, work very fine.
$config['full_tag_open'] = '<ul class="pagination">';
$config['full_tag_close'] = '</ul>';
$config['attributes'] = ['class' => 'page-link'];
$config['first_link'] = false;
$config['last_link'] = false;
$config['first_tag_open'] = '<li class="page-item">';
$config['first_tag_close'] = '</li>';
$config['prev_link'] = '&laquo';
$config['prev_tag_open'] = '<li class="page-item">';
$config['prev_tag_close'] = '</li>';
$config['next_link'] = '&raquo';
$config['next_tag_open'] = '<li class="page-item">';
$config['next_tag_close'] = '</li>';
$config['last_tag_open'] = '<li class="page-item">';
$config['last_tag_close'] = '</li>';
$config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
$config['cur_tag_close'] = '</a></li>';
$config['num_tag_open'] = '<li class="page-item">';
$config['num_tag_close'] = '</li>';


    // Initialize
    $this->pagination->initialize($config);
 	
    $data['pagination'] = $this->pagination->create_links();
    
    $data['row'] = $rowno;


		// provide views
		$this->load->view('admin/header', $data);
		$this->load->view('admin/left_menu', $data);
		$this->load->view('admin/page_menus/blank_pagemenu', $data);
		$this->load->view('admin/logs', $data);
		$this->load->view('admin/footer', $data);
	}


	function export_logs_data($application_id = 0, $user_id = 0, $date1 = 0, $date2 = 0, $rowno=0)
	{
		$list_directory = $this->application_model->list_directory();
		require 'vendor/autoload.php';
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', 'Log ID');
	$sheet->setCellValue('B1', 'Log Details');
	$sheet->setCellValue('C1', 'Application Name');
	$sheet->setCellValue('D1', 'Perform on');

	$apply_filter = 0;
	$rowno = 10;
	$rowperpage = 10;
	$logs_list = $this->application_model->get_logs_list($application_id, $user_id, $date1, $date2, $rowno, $rowperpage, $apply_filter);

	$sn = 0;
	foreach ($logs_list as $key => $single_log_data) {
			
			$new_key = $key+2;
			$column_name_ref = "A"."$new_key";
			$sheet->setCellValue($column_name_ref, $single_log_data['id']);
			$column_name_ref = "B"."$new_key";
			$sheet->setCellValue($column_name_ref, $single_log_data['comment']);
			$column_name_ref = "C"."$new_key";


			 $application_ids_array = array_column($list_directory, 'id');
        $application_id_key = array_search($single_log_data['application_id'], $application_ids_array);
        if (is_numeric($application_id_key)) {
          $sheet->setCellValue($column_name_ref, $list_directory[$application_id_key]['solution_name']);
        }


			
			$column_name_ref = "D"."$new_key";
			$sheet->setCellValue($column_name_ref, $single_log_data['perform_on']);
			
			$sn = $sn+1;
		}

	$sheet->getColumnDimension("A")->setAutoSize(true);
	$sheet->getColumnDimension("B")->setAutoSize(true);
	$sheet->getColumnDimension("C")->setAutoSize(true);
	$sheet->getColumnDimension("D")->setAutoSize(true);

	$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
	$filename = 'Log Details';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');

	}

	public function process_filter()
	{
		if ($this->input->post()) {

			$application_id = $this->input->post('application_id');
			$user_id = $this->input->post('user_id');
			
			
			$date1 = $this->input->post('date1');
			$date2 = $this->input->post('date2');
			if($date1 == "")
			{
				$date1 = 0;
			}
			
			if($date2 == "")
			{
				$date2 = 0;
			}

			$filter_string = "admin/logs/$application_id/$user_id/$date1/$date2/";
			redirect("$filter_string", 'refresh');
			exit();
		}else{
			redirect("admin/logs/0/0/0/0/", 'refresh');
			exit();
		}
	}


	public function manage_limits()
	{
		$data['page_title'] = "Manage Application Limits - Admin DPGA";
		$data['page_heading'] = 'home';
		$data['user_details'] = $this->users_model->get_applicant_details($this->session->userdata('user_id'));
		$data['user_role'] = $user_role = $this->session->userdata('user_role');
		$data['active_menu'] = "Limits";
		$data['filter_text'] = "";
		$data['sub_active_menu'] = "";

		$data['limits_data'] = $limits_data = $this->application_model->get_limits_data();

		
		// provide views
		$this->load->view('admin/header', $data);
		$this->load->view('admin/left_menu', $data);
		$this->load->view('admin/page_menus/blank_pagemenu', $data);
		$this->load->view('admin/manage_limits', $data);
		$this->load->view('admin/footer', $data);
	}

	public function process_limits()
	{
		if ($this->input->post()) {
			$update_data = array('l1review' => $this->input->post('l1review'),
				'l2review' => $this->input->post('l2review'),
				'to_refresher' => $this->input->post('to_refresher'),
				'app_expire_days' => $this->input->post('app_expire_days'),
				'github_token' => $this->input->post('github_token'),
				'github_owner_name' => $this->input->post('github_owner_name'),
				'github_repo_name' => $this->input->post('github_repo_name'),
				'github_main_branch_name' => $this->input->post('github_main_branch_name'),
				'log_count' => $this->input->post('log_count'));
			$this->application_model->update_limits($update_data);

			$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>Limits update successfully.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
					redirect("admin/limits", 'refresh');
			exit();



		}
	}

	public function cron_details()
	{
		$data['page_title'] = "Cron Details - Admin DPGA";
		$data['page_heading'] = 'home';
		$data['user_details'] = $this->users_model->get_applicant_details($this->session->userdata('user_id'));
		$data['user_role'] = $user_role = $this->session->userdata('user_role');
		$data['active_menu'] = "Crons";
		$data['filter_text'] = "";
		$data['sub_active_menu'] = "";
		
		// provide views
		$this->load->view('admin/header', $data);
		$this->load->view('admin/left_menu', $data);
		$this->load->view('admin/page_menus/blank_pagemenu', $data);
		$this->load->view('admin/cron_details', $data);
		$this->load->view('admin/footer', $data);
	}



	public function logout()
	{
		$this->session->sess_destroy();
		redirect('/login', 'refresh');
	}

}