<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fresh_application extends CI_Controller {

	function __construct()
	{
        // Call the Model constructor
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('cookie');
		$this->load->library('encryption');
		$this->load->model('application_model');
		$this->load->database();
	}

	public function application()
	{
		
		//check old application_id in cookies or in session
		if($this->is_application_id_in_cookies() OR $this->session->userdata('application_id') > 0)
		{
			if($this->is_application_id_in_cookies())
			{
				$application_id = $this->is_application_id_in_cookies();
			}else{
				$application_id = $this->session->userdata('application_id');
			}

			if($this->is_applicant_id_in_cookies())
			{
				$applicant_id = $this->is_applicant_id_in_cookies();
			}else{
				$applicant_id = $this->session->userdata('applicant_id');
			}

			$session_data = array(
				'application_id'  => $application_id,
				'applicant_id' => $applicant_id,
				'email_verified' => $this->is_applicant_email_verified($applicant_id),
				'application_type'     => 'new',
				'current_section_id' => 0,
				'section_revision' =>array(),
			);
			//store fresh application data in session
			$this->session->set_userdata($session_data);
			redirect('/form', 'refresh');
			exit();
		}

		if($this->session->userdata('applicant_id') > 0)
		{
			redirect('/home?force_model=1', 'refresh');
			exit();
		}



		//create a fresh application and pass the application id to session
		
		$application_id = $this->application_model->create_fresh_empty_application();
		$session_data = array(
			'application_id'  => $application_id,
			'applicant_id' => 0,
			'email_verified' => 0,
			'application_type'     => 'new',
			'current_section_id' => 0,
			'section_revision' =>array(),
		);
		//store fresh application data in session
		$this->session->set_userdata($session_data);
		//save this application_id to cookies
		$this->set_application_id_in_cookies($application_id);
		// print_r($this->session->userdata());
		// echo $this->session->userdata('application_id');
		//now insert all active sections in section status
		$this->load->model('section_model');
		$list_all_active_sections_array = $this->section_model->list_all_active_sections();
		$bulk_insert_data = array();
		foreach ($list_all_active_sections_array as $key => $single_section) {
			$single_array = array(
				'application_id'=> $application_id,
				'section_id'=> $single_section['id']
			);
			array_push($bulk_insert_data,$single_array);
		}
		if(count($bulk_insert_data) > 0)
		{
			$this->section_model->insert_bulk_section_data($bulk_insert_data);
		}
		//now redirect applicant to fill sections
		redirect('/form', 'refresh');
	}

	public function start()
	{
		//check applicant have existing session or not
		$application_id = $this->session->userdata('application_id');
		if(!$this->session->userdata('application_id'))
		{
			redirect('/home?force_model=1', 'refresh');
			exit();
		}
		// echo $this->session->userdata('application_id');
		$this->load->model('section_model');
		
		// $this->session->set_userdata('current_section_id') = 0;
		// $this->session->set_userdata('current_section_id', 0);
		$data['list_all_active_sections_array'] = $list_all_active_sections_array = $this->section_model->list_all_active_sections();
		//print_r($list_all_active_sections_array[0]);
		if (count($list_all_active_sections_array) > 0 && $this->session->userdata('current_section_id') == 0 ){
			//get starting section id and assign to session
			//$this->session->userdata('current_section_id') = $list_all_active_sections_array[0]['id'];
			$this->session->set_userdata('current_section_id', $list_all_active_sections_array[0]['id']);
		}
		$data['current_section_id'] = $current_section_id = $this->session->userdata('current_section_id');
		//get sections details for application
		$data['all_section_data_in_application'] = $this->section_model->all_section_data_via_application_id($this->session->userdata('application_id'));
		//get question details
		$data['all_section_questions'] = $this->application_model->get_all_section_questions($current_section_id);
		//get all answers
		$data['all_section_answers'] = $this->application_model->get_all_section_answers($current_section_id, $this->session->userdata('application_id'));
		$data['solution_name'] = $this->application_model->get_solution_name($this->session->userdata('application_id'));
		$data['application_status'] = $application_status = $this->application_model->get_application_status($application_id);
		if($application_status == 6)
		{
			$data['page_title'] = "Fill Clarification Details - DPGA";
		}else{
			$data['page_title'] = "Fill New Application - DPGA";
		}
		
		$data['page_heading'] = 'DPG Application Form';
		$this->load->view('applicant/header',$data);

		$viewmode = $this->session->userdata('viewmode');
		if($viewmode)
		{
			$this->load->view('applicant/form_viewmode',$data);
		}else{
			$this->load->view('applicant/form',$data);
		}
		
		//$this->load->view('applicant/form',$data);
		
		$this->load->view('applicant/footer',$data);
	}

	public function change_section($section_id)
	{
		$this->session->set_userdata('current_section_id', $section_id);
		redirect('/form', 'refresh');
	}

	

	public function email_alert_for_new_application_created($applicant_id, $application_id)
	{
		$is_email_service_enable = IS_EMAIL_SERVICE_ENABLE;
		$application_type = $this->application_model->get_application_type($application_id);
		if($is_email_service_enable)
		{

				$data['solution_name'] = $solution_name = $this->application_model->get_solution_name($application_id);
				$data['application_id'] = $application_id;
				$this->load->model('users_model');
				//SMTP Email Start
				$this->load->config('email');
				$this->load->library('email');
				$from = $this->config->item('smtp_user');
				$display_name = $this->config->item('smtp_display_name');
				$to = $this->users_model->get_applicant_email($applicant_id);
				
				$main_url = base_url();
				$data['app_public_link'] ="" .$main_url. "a/$application_id";
				$data['applicant_name'] = $this->users_model->get_user_fullname($applicant_id);
				if($application_type == "new")
				{
					$subject = "Your DPG application for $solution_name ($application_id) has been successfully submitted for review";
					$message = $this->load->view('emails/e4_new_application_submitted_successfully', $data, true);
				}else{
					$subject = "Your application to renew the DPG status of $solution_name ($application_id) has been successfully submitted for review";
					$message = $this->load->view('emails/e5_renewal_application_submitted_successfully', $data, true);
				}
				
				$this->email->set_newline("\r\n");
				$this->email->from($from,$display_name);
				$this->email->to($to);
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();
				//SMTP Email End
		}
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
		  $tag_name = $this->get_tag_name($tag_id);
		  $comment ="New tag ($tag_name) added to application";
		  //update application logs
				$insert_data = array('application_id' => $application_id,
					'comment' => $comment,
					'perform_by' => $this->session->userdata('applicant_id'),
					'perform_on' => date('Y-m-d H:i:s') );
				$this->application_model->insert_application_log($insert_data);
				return true;
		  } 
	}


	public function get_tag_name($tag_id)
	{
		return $this->application_model->get_tag_name($tag_id);
	}



	public function resume_application($application_id)
	{
		//now check this application belongs to this user or not
		$applicant_id = $this->session->userdata('applicant_id');
		
		if($this->application_model->is_application_owner($application_id, $applicant_id))
		{
			//check is incomplete or not
			if($this->application_model->is_application_not_submitted($application_id))
			{
				$this->session->set_userdata('application_id', $application_id);
				$this->session->set_userdata('application_type', 'new');
				$this->session->set_userdata('viewmode', FALSE);
				$this->session->set_userdata('current_section_id', 0);
				$this->session->set_userdata('section_revision', array());

			//delete old application_id from cookies
				delete_cookie('application_id');
			//insert new application_id in cookies
				$this->set_application_id_in_cookies($application_id);
				redirect('/form', 'refresh');
			}else{
				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
					<strong>Error !!</strong> You do not have permission to access that application.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
				redirect('/home', 'refresh');
			}

			
		}else{
			//permission issue
			$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Error !!</strong> You do not have permission to access that application.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
			redirect('/home', 'refresh');
		}
	}


	public function process_refresher($application_id)
	{
		//now check this application belongs to this user or not
		$applicant_id = $this->session->userdata('applicant_id');
		
		if($this->application_model->is_application_owner($application_id, $applicant_id))
		{
			//check is incomplete or not
			if($this->is_application_allow_for_refresher($application_id))
			{
			




			
			$applicant_id = $this->session->userdata('applicant_id');

			//get all old answers
			$old_responses = $this->application_model->get_old_responses($application_id);
			
			$new_qids = $this->application_model->get_new_qids();
			
			//create new application
			$new_application_id = $this->application_model->create_refresher_application_applicant($applicant_id,$application_id);
			
			//update application logs
				$insert_data = array('application_id' => $application_id,
					'comment' => 'New Refresher Application created',
					'perform_by' => $applicant_id,
					'perform_on' => date('Y-m-d H:i:s') );
				// $this->application_model->insert_application_log($insert_data);

				//move to expire old application
				/*
			$update_data = array('status' => 9);
			$this->application_model->update_application_data($update_data, $application_id, $applicant_id);

			//update application logs
				$insert_data = array('application_id' => $application_id,
					'comment' => 'Old application status changed to expired',
					'perform_by' => $applicant_id,
					'perform_on' => date('Y-m-d H:i:s') );
				$this->application_model->insert_application_log($insert_data);
			*/
			// save this application_id in session
			$this->session->set_userdata('application_id', $new_application_id);
			$this->session->set_userdata('application_type', 'refresher');
			$this->session->set_userdata('current_section_id', '0');
			$this->session->set_userdata('section_revision', array());
			
			//now insert section section entries
			$this->load->model('section_model');
			$list_all_active_sections_array = $this->section_model->list_all_active_sections();
			$bulk_insert_data = array();
			$bulk_response_data = array();
			foreach ($list_all_active_sections_array as $key => $single_section) {
				$single_array = array(
					'application_id'=> $new_application_id,
					'section_id'=> $single_section['id']
				);
				array_push($bulk_insert_data,$single_array);

				foreach ($new_qids as $qkey => $ind_question) {
					if($ind_question['section_id'] == $single_section['id'])
					{
						//get answer from old_responses
 						$old_responses_qid_key = array_search($ind_question['new_qid'], array_column($old_responses, 'question_id'));
 						if($old_responses_qid_key)
 						{
 							$old_qid_response = $old_responses[$old_responses_qid_key]['answer'];
 						}else{
 							$old_qid_response = "";
 						}

						$temp_array = array(
						'application_id'=> $new_application_id,
						'section_id'=> $single_section['id'],
						'question_id'=> $ind_question['new_qid'],
						'answer'=> $old_qid_response,
						);
						array_push($bulk_response_data,$temp_array);
					}
				}
			}



			if(count($bulk_insert_data) > 0)
			{
				$this->section_model->insert_bulk_section_data($bulk_insert_data);
			}

			//handle solution name
			$solution_name = $this->application_model->get_solution_name($application_id);
		
			$bulk_response_data[0]['answer'] = $solution_name;

			

			if(count($bulk_response_data) > 0)
			{
				$this->section_model->insert_bulk_response_data($bulk_response_data);
			}

			$log_role = $this->session->userdata('user_role_details');
			$log_name = $this->session->userdata('user_fullname');
			$solution_name = $this->application_model->get_solution_name($application_id);

			$log_string = "$log_name ($log_role) created a refresher application for $solution_name ($application_id)";
			


			//update application logs
				$insert_data = array('application_id' => $application_id,
					'comment' => $log_string,
					'perform_by' => $applicant_id,
					'perform_on' => date('Y-m-d H:i:s') );
				$this->application_model->insert_application_log($insert_data);









			//delete old application_id from cookies
				delete_cookie('application_id');
			//insert new application_id in cookies
				$this->set_application_id_in_cookies($new_application_id);
				redirect('/form', 'refresh');
			}else{
				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
					<strong>Error !!</strong> You do not have permission to access that application.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
				redirect('/home', 'refresh');
			}

			
		}else{
			//permission issue
			$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Error !!</strong> You do not have permission to access that application.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
			redirect('/home', 'refresh');
		}
	}


	function is_application_allow_for_refresher($application_id)
	{
		$dpga_limits = $this->application_model->get_dpga_limits();
		$days_number = $dpga_limits[0]['to_refresher'];
        $filter_string = "-$days_number day";
        $application_expire_on = $this->application_model->get_expiry_date($application_id);
        $allow_after =  strtotime($filter_string, strtotime($application_expire_on));
        $application_status = $this->application_model->get_application_status($application_id);
        if($application_status == 8 OR $application_status == 9)
        {
        	if($allow_after < time())
	        {
	          return true;
	        }else{
	        	return false;
	        }
        }else{
        	return false;
        }
        
	}


	function create_new_application()
	{
		if ($this->input->post()) {
			$project_name = $this->input->post('project_name');
			$applicant_id = $this->session->userdata('applicant_id');
			
			//create new application
			$application_id = $this->application_model->create_application_applicant($applicant_id);
			// save this application_id in session
			$this->session->set_userdata('application_id', $application_id);
			$this->session->set_userdata('application_type', 'new');
			$this->session->set_userdata('viewmode', FALSE);
			$this->session->set_userdata('current_section_id', '0');
			$this->session->set_userdata('section_revision', array());
			//now save project name under response db
			$project_name_id = PROJECT_NAME_ID;
			$section_id = SECTION_ID;
			$insert_data = array('application_id' => $application_id,
				'section_id' => $section_id,
				'question_id' => $project_name_id,
				'answer' => $project_name,
				'response_time'=> date('Y-m-d H:i:s'));
			$this->application_model->insert_single_answer($insert_data);
			//now insert section section entries
			$this->load->model('section_model');
			$list_all_active_sections_array = $this->section_model->list_all_active_sections();
			$bulk_insert_data = array();
			foreach ($list_all_active_sections_array as $key => $single_section) {
				$single_array = array(
					'application_id'=> $application_id,
					'section_id'=> $single_section['id']
				);
				array_push($bulk_insert_data,$single_array);
			}
			if(count($bulk_insert_data) > 0)
			{
				$this->section_model->insert_bulk_section_data($bulk_insert_data);
			}
			//now redirect to form fill screen
			redirect('/form', 'refresh');
			
		}
		else{
			redirect('/home', 'refresh');
		}
	}

	public function process_clarifications($application_id)
	{
		//now check this application belongs to this user or not
		$applicant_id = $this->session->userdata('applicant_id');
		
		if($this->application_model->is_application_owner($application_id, $applicant_id))
		{
			//check is need clarifications or not
			if($this->application_model->is_application_need_clarifications($application_id))
			{
				$this->session->set_userdata('application_id', $application_id);
				$this->session->set_userdata('application_type', 'new');
				$this->session->set_userdata('viewmode', FALSE);
				$this->session->set_userdata('current_section_id', 0);
				$this->session->set_userdata('section_revision', array());

			//delete old application_id from cookies
				delete_cookie('application_id');
			//insert new application_id in cookies
				$this->set_application_id_in_cookies($application_id);
				redirect('/form', 'refresh');
			}else{
				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
					<strong>Error !!</strong> Application do not need any clarifications.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
				redirect('/home', 'refresh');
			}	
		}else{
			//permission issue
			$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Error !!</strong> You do not have permission to access that application.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
			redirect('/home', 'refresh');
		}
	}


	public function process_viewmode($application_id)
	{
		//now check this application belongs to this user or not
		$applicant_id = $this->session->userdata('applicant_id');
		
		if($this->application_model->is_application_owner($application_id, $applicant_id))
		{
				$this->session->set_userdata('application_id', $application_id);
				$this->session->set_userdata('application_type', 'new');
				$this->session->set_userdata('current_section_id', 0);
				$this->session->set_userdata('section_revision', array());
				$this->session->set_userdata('viewmode', TRUE);

			//delete old application_id from cookies
				delete_cookie('application_id');
			//insert new application_id in cookies
				$this->set_application_id_in_cookies($application_id);
				redirect('/form', 'refresh');
			
		}else{
			//permission issue
			$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
				<strong>Error !!</strong> You do not have permission to access that application.
				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
			redirect('/home', 'refresh');
		}
	}


	public function save_clarifications()
	{
		if ($this->input->post()) {
			$application_id = $this->session->userdata('application_id');
			$applicant_id = $this->session->userdata('applicant_id');
			if($this->application_model->is_application_need_clarifications($application_id))
			{
				$section_id = $this->input->post('current_section_id');
				$clarify_response = $this->input->post('clarify_response');
				//now update response in db
				$data = array('clarify_response' => $clarify_response);
				$this->application_model->update_clarify_response($data,$section_id,$application_id);

				//update application logs
				/*
				$insert_data = array('application_id' => $application_id,
					'comment' => 'Clarification response submitted by applicant on section',
					'perform_by' => $applicant_id,
					'section_id' => $section_id,
					'perform_on' => date('Y-m-d H:i:s') );
				$this->application_model->insert_application_log($insert_data);
				*/
				//now redirect to form screen
				redirect('/form', 'refresh');

			}
			
		}else{
			redirect('/', 'refresh');
		}
	}

	public function is_application_id_in_cookies()
	{
		$encrypted_application_id = $this->input->cookie('application_id',true);
		return $application_id = $this->encryption->decrypt($encrypted_application_id);
	}

	public function is_applicant_id_in_cookies()
	{
		$encrypted_applicant_id = $this->input->cookie('applicant_id',true);
		return $applicant_id = $this->encryption->decrypt($encrypted_applicant_id);
	}

	public function is_applicant_email_verified($applicant_id)
	{
		$this->load->model('users_model');
		if($this->users_model->is_email_verified($applicant_id))
		{
			return 1;
		}else{
			return 0;
		}
	}

	public function handle_excel()
	{
		$application_id = $this->session->userdata('application_id');
		//excel_file
		$config['upload_path'] = './files/applicant_uploads/';
        $config['allowed_types'] = 'xlsx';
        $config['max_size'] = 1024;
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('excel_file'))
            {
                //$error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
					<strong>Error !!</strong> File not in required format. Please note we accept only .xlsx file, max size 1 MB.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
                $upload_data = $this->upload->data();
                unlink($upload_data['full_path']);
				redirect('/form', 'refresh');
			exit(); 
            }
           else
           {
           	$upload_data = $this->upload->data();
           	$filename = $upload_data['file_name'];
           	require 'vendor/autoload.php';
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

		$sheetname = "application";
		//$filename = "application.xlsx";
		$reader->setLoadSheetsOnly($sheetname);
		$spreadsheet = $reader->load("files/applicant_uploads/$filename");
		$mcq_array= $spreadsheet->getActiveSheet()->toArray();
		
		$actual_qids_with_section_ids = $this->application_model->get_qid_with_section_id($application_id);
		
		$bulk_response_array = array();
		if(count($mcq_array) == TOTAL_EXCEL_QUESTIONS)
		{
			//delete all old response
			$this->application_model->delete_all_old_respones($application_id);
			//now create bulk array to insert
			foreach ($mcq_array as $key => $single_response_array) {
				if($key > 0)
				{
					
					$temp_qid = $single_response_array[0];
					 $live_qids_array = array_column($actual_qids_with_section_ids, 'qid');
					 $qid_key = array_search($temp_qid, $live_qids_array);
					 if (is_numeric($qid_key)) {
			          $temp_section_id = $actual_qids_with_section_ids[$qid_key]['section_id'];
			          $single_response_array = array("application_id" => $application_id,
			          	"section_id" => $temp_section_id,
			          	"question_id" => $temp_qid,
			          	"answer" => $single_response_array[3],
			          	"response_time" => date('Y-m-d H:i:s'));
			          array_push($bulk_response_array, $single_response_array);

			        }

				}
				
			}

			if(count($bulk_response_array) > 0)
			{
				$this->application_model->insert_bulk_response($bulk_response_array);
				$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
					Excel data imported successfully.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
				unlink($upload_data['full_path']);
				redirect('/form', 'refresh');
				exit();
			}

		}else{
			$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
					<strong>Error !!</strong> Excel not in required format. Rows count mismatch. Please retry
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
				redirect('/form', 'refresh');
				exit();

		}


            }

		
	}




	public function set_application_id_in_cookies($application_id)
	{
		$this->load->library('encryption');
		//encrpyt the application_id
		$encrypted_application_id = $this->encryption->encrypt($application_id);
					//now send this to cookies
		$toal_seconds = TOTAL_SECONDS_FOR_COOKIES_APP;
		$cookie= array(
			'name'   => 'application_id',
			'value'  => $encrypted_application_id,
			'expire' => $toal_seconds, 
		          	//'secure' => TRUE
		);
		$this->input->set_cookie($cookie);
	}
}
