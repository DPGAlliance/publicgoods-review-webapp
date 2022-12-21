<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crons extends CI_Controller {

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


	public function index()
	{
		echo "DPGA crons handler";
	}

	//we assume admin run this cron on every hour
	public function add_late_tag_l1()
	{
		//get all applications which is under l1 review status = 2
		$status = array(2);
		$role = 2;
		$all_apps_array = $this->application_model->applications_list_crons($status, 0);
		$dpga_limits = $this->application_model->get_dpga_limits();
		//get L1 review time limit
		$limit_in_hours =  $dpga_limits[0]['l1review'];
		if(count($all_apps_array) > 0)
		{
			foreach ($all_apps_array as $key => $single_app) {
				$application_id = $single_app['id'];
				$l1_assign_on = $single_app['l1_assign_on'];
				$l1_timer_status = $single_app['l1_timer'];
				$tags_string = $single_app['tags'];
				$tag_id = 4;

                $allow_till = strtotime("+" .$limit_in_hours. " hours", strtotime($single_app['l1_assign_on']));

                  $time_remaining = $allow_till - strtotime("now");
                  if($time_remaining > 0)
                  {
                    //nothing to do
                  }else{
                    // add late tag
                    if($l1_timer_status == 1)
                    {
                    	$this->update_tag_in_application($application_id, $tags_string, $tag_id);
                    	echo "Tag added for $application_id <br>";
                    }

                  }
			}
		}else{
			echo "No suitable application found";
		}
	}


	//we assume admin run this cron on every hour
	public function add_late_tag_l2()
	{
		//get all applications which is under l2 review status = 4
		$status = array(4);
		$role = 3;
		$all_apps_array = $this->application_model->applications_list_crons($status, 0);
		$dpga_limits = $this->application_model->get_dpga_limits();
		//get L1 review time limit
		$limit_in_hours =  $dpga_limits[0]['l2review'];
		if(count($all_apps_array) > 0)
		{
			foreach ($all_apps_array as $key => $single_app) {
				$application_id = $single_app['id'];
				$l2_assign_on = $single_app['l2_assign_on'];
				$l2_timer_status = $single_app['l2_timer'];
				$tags_string = $single_app['tags'];
				$tag_id = 4;

                $allow_till = strtotime("+" .$limit_in_hours. " hours", strtotime($single_app['l2_assign_on']));

                $consultation_duration = $single_app['consultation_duration'];
                 $allow_till = $allow_till + $consultation_duration;

                  $time_remaining = $allow_till - strtotime("now");
                  if($time_remaining > 0)
                  {
                    //nothing to do
                  }else{
                    // add late tag
                    if($l2_timer_status == 1)
                    {
                    	$this->update_tag_in_application($application_id, $tags_string, $tag_id);
                    	echo "Tag added for $application_id <br>";
                    }
                  }
			}
		}else{
			echo "No suitable application found";
		}
	}


	//we assume admin run this cron one time on every day
	public function handle_pending_clarifications()
	{

		$status = array(6);
		$all_apps_array = $this->application_model->applications_list_crons($status, 0);

		if(count($all_apps_array) > 0)
		{
			foreach ($all_apps_array as $key => $single_app) {

				$application_id = $single_app['id'];
				$to_clarifications = $single_app['to_clarifications'];
				$clarifications_days = $single_app['clarifications_days'];
				$clarifications_submit = $single_app['clarifications_submit'];

				$allow_till = strtotime("+" .$clarifications_days. " days", strtotime($single_app['to_clarifications']));
				$time_remaining = $allow_till - strtotime("now");
                  if($time_remaining > 0)
                  {
                    //nothing to do
                  }else{
                    // take action 
                    if($clarifications_submit == 0)
                    {
                    	$update_data = array('status' => 7,
                    		'nominee' => 0,
							'review_complete_on' => date('Y-m-d H:i:s'));
                    	$this->application_model->update_app_data($update_data, $application_id);
                    	$solution_name = $this->application_model->get_solution_name($application_id);
						$log_string = "System removed $solution_name ($application_id) as a nominee and moved it to ineligible because clarifications were not submitted within time allotted.";
		  				//update application logs
						$insert_data = array('application_id' => $application_id,
						'comment' => $log_string,
						'perform_on' => date('Y-m-d H:i:s') );
						$this->application_model->insert_application_log($insert_data);

						//send email alert
						$this->email_alert_for_fails_to_clarify_on_time($application_id);
                    	echo "move to ineligible  $application_id <br>";
                    }
                  }

			}
		}else{
			echo "No suitable application found";
		}
	}


	//we assume admin run this cron one time on every day
	public function move_to_expire()
	{

		$status = array(8);
		$all_apps_array = $this->application_model->applications_list_crons($status, 1);

		if(count($all_apps_array) > 0)
		{
			foreach ($all_apps_array as $key => $single_app) {

				$application_id = $single_app['id'];
				$update_data = array('status' => 9,
                    		'nominee' => 0);
                $this->application_model->update_app_data($update_data, $application_id);
                $solution_name = $this->application_model->get_solution_name($application_id);
				$log_string = "System moved $solution_name ($application_id) to expired.";
		  				//update application logs
						$insert_data = array('application_id' => $application_id,
						'comment' => $log_string,
						'perform_on' => date('Y-m-d H:i:s') );
						$this->application_model->insert_application_log($insert_data);
                    	echo "move to expired  $application_id <br>";
			}
		}else{
			echo "No suitable application found";
		}
	}


	public function update_tag_in_application($application_id, $tags_string, $tag_id)
	{
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
		 $tag_name = $this->application_model->get_tag_name($tag_id);
		$solution_name = $this->application_model->get_solution_name($application_id);
		$log_string = "System add new tag $tag_name to $solution_name ($application_id)";
		  //update application logs
				$insert_data = array('application_id' => $application_id,
					'comment' => $log_string,
					'perform_on' => date('Y-m-d H:i:s') );
				$this->application_model->insert_application_log($insert_data);
				return true;
		  } 
	}


	public function email_alert_for_fails_to_clarify_on_time($application_id)
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
				$subject = "Your DPG application for $solution_name ($application_id) was closed";
				$message = $this->load->view('emails/e10_applicant_fails_to_clarify_on_time', $data, true);
				
				$this->email->set_newline("\r\n");
				$this->email->from($from,$display_name);
				$this->email->to($to);
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();
				//SMTP Email End

		}
	}


	public function reminder_to_finish_application($days_count)
	{
		//current date - days diffrence
		$filter_string = "- $days_count day";
		$date1_start = date('Y-m-d 00:00:01',strtotime($filter_string));
		$date2_end = date('Y-m-d 23:59:59',strtotime($filter_string));
		$status_array = array(0);

		$app_list = $this->application_model->get_list_of_pending_applications($status_array, $date1_start, $date2_end);
		if(count($app_list) > 0)
		{
			foreach ($app_list as $key => $single_app_data) {
				$application_id = $single_app_data['id'];
				//send email alert
				$this->email_alert_for_reminder_to_finish_application($application_id);
				echo "Email send for reminder_to_finish_application - $application_id";
			}
		}else{
			echo "No data found for reminder_to_finish_application";
		}

	}


	public function email_alert_for_reminder_to_finish_application($application_id)
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
				$subject = "Your DPG application for $solution_name ($application_id) is incomplete";
				$message = $this->load->view('emails/r3_reminder_to_finish_application', $data, true);
				
				$this->email->set_newline("\r\n");
				$this->email->from($from,$display_name);
				$this->email->to($to);
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();
				//SMTP Email End

		}
	}


	public function reminder_for_respond_with_clarifications($days_count)
	{
		//current date - days diffrence
		$filter_string = "- $days_count day";
		$date1_start = date('Y-m-d 00:00:01',strtotime($filter_string));
		$date2_end = date('Y-m-d 23:59:59',strtotime($filter_string));
		$status_array = array(6);
		$app_list = $this->application_model->list_clarifications_applications($status_array, $date1_start, $date2_end);
		if(count($app_list) > 0)
		{
			foreach ($app_list as $key => $single_app_data) {
				$application_id = $single_app_data['id'];
				$clarifications_days = $single_app_data['clarifications_days'];
				//send email alert
				$this->email_alert_for_reminder_for_respond_with_clarifications($application_id, $clarifications_days);
				echo "Email send for reminder_to_finish_application - $application_id";
			}
		}else{
			echo "No data found for reminder_for_respond_with_clarifications";
		}
	}


	public function email_alert_for_reminder_for_respond_with_clarifications($application_id, $clarifications_days)
	{
		$is_email_service_enable = IS_EMAIL_SERVICE_ENABLE;
		

		if($is_email_service_enable)
		{
			$data['solution_name'] = $solution_name = $this->application_model->get_solution_name($application_id);
			$applicant_id = $this->application_model->get_applicant_id($application_id);
			$data['application_id'] = $application_id;
			$data['clarifications_days'] = $clarifications_days;
				$this->load->model('users_model');
				//SMTP Email Start
				$this->load->config('email');
				$this->load->library('email');
				$from = $this->config->item('smtp_user');
				$display_name = $this->config->item('smtp_display_name');
				$to = $this->users_model->get_applicant_email($applicant_id);
				$data['applicant_name'] = $this->users_model->get_user_fullname($applicant_id);
				$data['app_login_link'] = base_url("login");
				$subject = "Important: Your DPG application for $solution_name ($application_id) requires clarification (reminder)";
				$message = $this->load->view('emails/r7_reminder_for_respond_with_clarifications', $data, true);
				
				$this->email->set_newline("\r\n");
				$this->email->from($from,$display_name);
				$this->email->to($to);
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();
				//SMTP Email End
		}
	}



	public function reminder_apply_for_renewal()
	{
		$dpga_limits = $this->application_model->get_dpga_limits();
		$days_to_refresher =  $dpga_limits[0]['to_refresher'];
		$filter_string = "+ $days_to_refresher day";
		$expiry_date = date('Y-m-d',strtotime($filter_string));

		$status_array = array(8);
		$app_list = $this->application_model->list_applications_for_renewal($status_array, $expiry_date);
		if(count($app_list) > 0)
		{
			foreach ($app_list as $key => $single_app_data) {
				$application_id = $single_app_data['id'];
				$expire_on = $single_app_data['expire_on'];
				if($this->application_model->is_child_created($application_id))
				{
					echo "No suitable data found for reminder_for_respond_with_clarifications";
				}else{
					
					//send email alert
					$this->email_alert_for_reminder_apply_for_renewal($application_id,$expire_on);
					echo "Email send for reminder_apply_for_renewal - $application_id";
				}
				
			}
		}else{
			echo "No data found for reminder_for_respond_with_clarifications";
		}		
	}


	public function email_alert_for_reminder_apply_for_renewal($application_id, $expire_on)
	{
		$is_email_service_enable = IS_EMAIL_SERVICE_ENABLE;
		

		if($is_email_service_enable)
		{
			$data['solution_name'] = $solution_name = $this->application_model->get_solution_name($application_id);
			$applicant_id = $this->application_model->get_applicant_id($application_id);
			$data['application_id'] = $application_id;
			$data['expire_on'] = date("d-m-Y", strtotime($expire_on));
			$data['app_login_link'] = base_url("login");
				$this->load->model('users_model');
				//SMTP Email Start
				$this->load->config('email');
				$this->load->library('email');
				$from = $this->config->item('smtp_user');
				$display_name = $this->config->item('smtp_display_name');
				$to = $this->users_model->get_applicant_email($applicant_id);
				$data['applicant_name'] = $this->users_model->get_user_fullname($applicant_id);
				$subject = "Annual reminder to renew the DPG status of $solution_name ($application_id)";
				$message = $this->load->view('emails/r11_reminder_apply_for_renewal', $data, true);
				
				$this->email->set_newline("\r\n");
				$this->email->from($from,$display_name);
				$this->email->to($to);
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();
				//SMTP Email End
		}
	}


	public function reminder_for_renewal_expired_dpgs()
	{
		$expiry_date = date('Y-m-d');

		$status_array = array(8);
		$app_list = $this->application_model->list_applications_for_renewal($status_array, $expiry_date);
		if(count($app_list) > 0)
		{
			foreach ($app_list as $key => $single_app_data) {
				$application_id = $single_app_data['id'];
				$expire_on = $single_app_data['expire_on'];
				if($this->application_model->is_child_created($application_id))
				{
					echo "No suitable data found for reminder_for_renewal_expired_dpgs";
				}else{
					
					//send email alert
					$this->email_alert_for_renewal_for_expired_dpgs($application_id);
					echo "Email send for reminder_for_renewal_expired_dpgs - $application_id";
				}
				
			}
		}else{
			echo "No data found for reminder_for_renewal_expired_dpgs";
		}		
	}


	public function email_alert_for_renewal_for_expired_dpgs($application_id)
	{
		$is_email_service_enable = IS_EMAIL_SERVICE_ENABLE;
		

		if($is_email_service_enable)
		{
			$data['solution_name'] = $solution_name = $this->application_model->get_solution_name($application_id);
			$applicant_id = $this->application_model->get_applicant_id($application_id);
			$data['application_id'] = $application_id;
			$data['app_login_link'] = base_url("login");
				$this->load->model('users_model');
				//SMTP Email Start
				$this->load->config('email');
				$this->load->library('email');
				$from = $this->config->item('smtp_user');
				$display_name = $this->config->item('smtp_display_name');
				$to = $this->users_model->get_applicant_email($applicant_id);
				$data['applicant_name'] = $this->users_model->get_user_fullname($applicant_id);
				$subject = "The DPG status of $solution_name ($application_id) is expiring today";
				$message = $this->load->view('emails/r12_renewal_for_expired_dpgs', $data, true);
				
				$this->email->set_newline("\r\n");
				$this->email->from($from,$display_name);
				$this->email->to($to);
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();
				//SMTP Email End
		}
	}



	public function generate_pr_on_github()
	{
		//get last application which submitted and pr_status = 0
		$app_array = $this->application_model->get_last_application_for_pr();
		if(count($app_array) == 1)
		{
			$application_id = $app_array[0]['id'];
			$dpga_limits = $this->application_model->get_dpga_limits();
			$github_token =  $dpga_limits[0]['github_token'];
			$github_repo_name =  $dpga_limits[0]['github_repo_name'];
			$github_owner_name =  $dpga_limits[0]['github_owner_name'];
			$github_main_branch_name =  $dpga_limits[0]['github_main_branch_name'];

			//create a feature branch
			$solution_name = $this->application_model->get_solution_name($application_id);
			$new_solution_name = preg_replace('/\s+/', '_', $solution_name);
			$feature_branch_name = "$new_solution_name-$application_id";

			$branch_sha = $this->get_branch_sha($github_token, $github_owner_name, $github_repo_name, $github_main_branch_name);

			if($branch_sha != "")
			{
				$this->create_feature_branch($github_token, $github_repo_name, $github_main_branch_name, $feature_branch_name, $branch_sha, $github_owner_name);


			$this->upload_file_on_feature_branch($github_token, $github_repo_name, $github_main_branch_name, $feature_branch_name, $branch_sha, $github_owner_name, $application_id);

			$pr_title = "Add DPG: $solution_name ($application_id)";

			$this->create_pr_github($github_token, $github_repo_name, $github_main_branch_name, $feature_branch_name, $branch_sha, $github_owner_name, $application_id, $pr_title);

			//now update PR status in db
			$update_data = array("pr_status" => 1);
			$this->application_model->update_app_data($update_data, $application_id);
			echo "PR Created on github for application id - $application_id";
			} else{
				echo "Branch SHA not found, May be due to token error";
			}

			




		}else{
			echo "No data found for generate_pr_on_github";
		}

	}


	public function get_branch_sha($github_token, $github_owner_name, $github_repo_name, $github_main_branch_name)
	{
		/*
		$per_page = 10;
		$page_number = 1;
		$url = "https://api.github.com/repos/$github_owner_name/$github_repo_name/branches?per_page=$per_page&page=$page_number";
		$authorization = "Authorization: Bearer $github_token";

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$headers = array(
		   "Accept: application/vnd.github+json",
		   $authorization,
		   "User-Agent: Awesome-Octocat-App"
		);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		//for debug only!
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$resp = curl_exec($curl);
		curl_close($curl);
		print_r($resp);
		$all_branch_array = json_decode($resp);
		//echo "<pre>";
		//print_r($all_branch_array);
		//echo "<pre>";
		foreach ($all_branch_array as $key => $single_branch_data) {
			// print_r($single_branch_data);
			$temp_branch_name = $single_branch_data->name;
			if($temp_branch_name == $github_main_branch_name)
			{
				$commit_data = $single_branch_data->commit;

				//print_r($commit_data);
				return $commit_data->sha;
				exit();
			}
		}
		*/

		$per_page = 100;
		$page_number = 1;
		
		$all_branch_array = $this->get_branch_list($github_token, $github_owner_name, $github_repo_name, $github_main_branch_name, $per_page, $page_number);
		foreach ($all_branch_array as $key => $single_branch_data) {
			// print_r($single_branch_data);
			$temp_branch_name = $single_branch_data->name;
			if($temp_branch_name == $github_main_branch_name)
			{
				$commit_data = $single_branch_data->commit;

				//print_r($commit_data);
				return $commit_data->sha;
				exit();
			}
		}
		//not found then refetch on next page
		$page_number = $page_number+1;
		$all_branch_array = $this->get_branch_list($github_token, $github_owner_name, $github_repo_name, $github_main_branch_name, $per_page, $page_number);
		foreach ($all_branch_array as $key => $single_branch_data) {
			// print_r($single_branch_data);
			$temp_branch_name = $single_branch_data->name;
			if($temp_branch_name == $github_main_branch_name)
			{
				$commit_data = $single_branch_data->commit;

				//print_r($commit_data);
				return $commit_data->sha;
				exit();
			}
		}

		//not found then refetch on next page
		$page_number = $page_number+1;
		$all_branch_array = $this->get_branch_list($github_token, $github_owner_name, $github_repo_name, $github_main_branch_name, $per_page, $page_number);
		foreach ($all_branch_array as $key => $single_branch_data) {
			// print_r($single_branch_data);
			$temp_branch_name = $single_branch_data->name;
			if($temp_branch_name == $github_main_branch_name)
			{
				$commit_data = $single_branch_data->commit;

				//print_r($commit_data);
				return $commit_data->sha;
				exit();
			}
		}

		//not found then refetch on next page
		$page_number = $page_number+1;
		$all_branch_array = $this->get_branch_list($github_token, $github_owner_name, $github_repo_name, $github_main_branch_name, $per_page, $page_number);
		foreach ($all_branch_array as $key => $single_branch_data) {
			// print_r($single_branch_data);
			$temp_branch_name = $single_branch_data->name;
			if($temp_branch_name == $github_main_branch_name)
			{
				$commit_data = $single_branch_data->commit;

				//print_r($commit_data);
				return $commit_data->sha;
				exit();
			}
		}

		//not found then refetch on next page
		$page_number = $page_number+1;
		$all_branch_array = $this->get_branch_list($github_token, $github_owner_name, $github_repo_name, $github_main_branch_name, $per_page, $page_number);
		foreach ($all_branch_array as $key => $single_branch_data) {
			// print_r($single_branch_data);
			$temp_branch_name = $single_branch_data->name;
			if($temp_branch_name == $github_main_branch_name)
			{
				$commit_data = $single_branch_data->commit;

				//print_r($commit_data);
				return $commit_data->sha;
				exit();
			}
		}

		//not found then refetch on next page
		$page_number = $page_number+1;
		$all_branch_array = $this->get_branch_list($github_token, $github_owner_name, $github_repo_name, $github_main_branch_name, $per_page, $page_number);
		foreach ($all_branch_array as $key => $single_branch_data) {
			// print_r($single_branch_data);
			$temp_branch_name = $single_branch_data->name;
			if($temp_branch_name == $github_main_branch_name)
			{
				$commit_data = $single_branch_data->commit;

				//print_r($commit_data);
				return $commit_data->sha;
				exit();
			}
		}



	}


	public function get_branch_list($github_token, $github_owner_name, $github_repo_name, $github_main_branch_name, $per_page, $page_number)
	{
		$url = "https://api.github.com/repos/$github_owner_name/$github_repo_name/branches?per_page=$per_page&page=$page_number";
		$authorization = "Authorization: Bearer $github_token";

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$headers = array(
		   "Accept: application/vnd.github+json",
		   $authorization,
		   "User-Agent: Awesome-Octocat-App"
		);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		//for debug only!
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$resp = curl_exec($curl);
		curl_close($curl);
		// print_r($resp);
		return $all_branch_array = json_decode($resp);
	}


	public function create_feature_branch($github_token, $github_repo_name, $github_main_branch_name, $feature_branch_name, $branch_sha, $github_owner_name)
	{
		$feature_branch_string = "refs/heads/$feature_branch_name";
		$data = array("ref" => $feature_branch_string, "sha" => $branch_sha);                                                                    
		$data_string = json_encode($data);                                                                                   
          $url ="https://api.github.com/repos/$github_owner_name/$github_repo_name/git/refs";
          $authorization = "Authorization: Bearer $github_token";                                                                                                      
		$ch = curl_init($url);                                                                      
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);



curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
   "Accept: application/vnd.github+json",
   $authorization,
   "User-Agent: Awesome-Octocat-App",                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
);                                                                                                                   
                                                                                                                     
$result = curl_exec($ch);

curl_close($ch);
//var_dump($result);
//print_r($result);
	}


	public function upload_file_on_feature_branch($github_token, $github_repo_name, $github_main_branch_name, $feature_branch_name, $branch_sha, $github_owner_name, $application_id)
	{
		$str = 'This is an encoded string';
//echo base64_encode($str);
//$api_url = "https://app.digitalpublicgoods.net/api/dpg/10001";
$api_url = base_url("api/github/$application_id");
//$json = file_get_contents($api_url);
$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  

//$response = file_get_contents($api_url, false, stream_context_create($arrContextOptions));


$cdata = array("name" => "DPGA WebApp Cron", "email" => "noreply@digitalpublicgoods.net");

$data = array("message" => "New application received on DPGA WebApp",
	"branch" => $feature_branch_name,
	"committer"=> $cdata,
	//"content"=> "bXkgbmV3IGZpbGUgY29udGVudHM="
	"content"=> base64_encode(file_get_contents($api_url, false, stream_context_create($arrContextOptions)))
	
);

/*
{"message":"my commit message","committer":{"name":"Monalisa Octocat","email":"octocat@github.com"},"content":"bXkgbmV3IGZpbGUgY29udGVudHM="}   
*/                                                                 
$data_string = json_encode($data);

$url ="https://api.github.com/repos/$github_owner_name/$github_repo_name/contents/$application_id.json";
          $authorization = "Authorization: Bearer $github_token";                                                                                   
                                                                                                                     
$ch = curl_init($url);                                                                      
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);



curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
   "Accept: application/vnd.github+json",
   $authorization,
   "User-Agent: Awesome-Octocat-App",                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
);                                                                                                                   
                                                                                                                     
$result = curl_exec($ch);

curl_close($ch);
//var_dump($result);


	}



	public function create_pr_github($github_token, $github_repo_name, $github_main_branch_name, $feature_branch_name, $branch_sha, $github_owner_name, $application_id,$pr_title)
	{
$public_app_link = base_url("a/$application_id");

$data = array("title" => $pr_title,
	"body" => "Public Link : $public_app_link",
	"head" => $feature_branch_name,
	"base" => $github_main_branch_name);



$data_string = json_encode($data);                                                                                   
$url = "https://api.github.com/repos/$github_owner_name/$github_repo_name/pulls";
$authorization = "Authorization: Bearer $github_token";                                                                                               
$ch = curl_init($url);                                                                      
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);



curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
   "Accept: application/vnd.github+json",
   $authorization,
   "User-Agent: Awesome-Octocat-App",                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
);                                                                                                                   
                                                                                                                     
$result = curl_exec($ch);

curl_close($ch);
// var_dump($result);
	}



public function email_check()
{
	$is_email_service_enable = IS_EMAIL_SERVICE_ENABLE;
		

		if($is_email_service_enable)
		{
			
				$this->load->config('email');
				$this->load->library('email');
				$from = $this->config->item('smtp_user');
				$display_name = $this->config->item('smtp_display_name');
				$to = "govind.prajapat.39@gmail.com";
				
				$subject = "DPGA Email confirm";
				$message = "This is my message for DPGA email";
				
				$this->email->set_newline("\r\n");
				$this->email->from($from,$display_name);
				$this->email->to($to);
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();
				echo $this->email->print_debugger();
				//SMTP Email End
				echo "email sent";
		}
}


	

}