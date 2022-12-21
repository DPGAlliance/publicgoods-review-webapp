<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	function __construct()
	{
        // Call the Model constructor
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('cookie');
		$this->load->library('encryption');
		$this->load->model('users_model');
		$this->load->database();
	}

	public function signup()
	{
		
		//first we check cookies and session
		if($this->is_applicant_id_in_cookies() OR $this->session->userdata('applicant_id') > 0)
		{
			if($this->is_applicant_id_in_cookies())
			{
				$applicant_id = $this->is_applicant_id_in_cookies();
			}else{
				$applicant_id = $this->session->userdata('applicant_id');
			}
			
			//save this applicant_id to session
			$this->session->set_userdata('applicant_id', $applicant_id);
			//check email verified or not
			if($this->users_model->is_email_verified($applicant_id))
			{
				$this->session->set_userdata('email_verified', 1);
			}else{
				$this->session->set_userdata('email_verified', 0);
			}
			//now move to home screen
			redirect('/home', 'refresh');
			exit();
		}


		$data['page_title'] = 'Create Account - DPGA';
		$data['page_heading'] = 'DPG Create Account';
		//provide a signup form to user
		// $this->load->view('applicant/header', $data);
		// $this->load->view('applicant/signup', $data);
		// $this->load->view('applicant/footer', $data);
		$this->load->view('applicant/left_common', $data);
		$this->load->view('applicant/signup_new', $data);
	}

	public function signup_process()
	{
		if ($this->input->post()) {
			$password = $this->input->post('password');
			$confirm_password = $this->input->post('password');
			if($password != $confirm_password)
			{
				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
					<strong>Error !!</strong> Password not match. Please make sure you enter same value for Password and Confirm Password field.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
				redirect('/signup', 'refresh');
				exit();
			}
			// now check user already exists or not
			$email = $this->input->post('email');
			$user_fullname = $this->input->post('fname');
			
			if($this->users_model->user_already_exists($email))
			{
				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
					<strong>Error !!</strong> This email already registered with us. Please try to <a href='" .base_url('login'). "'>login</a> OR use diffrent email id
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
				redirect('/signup', 'refresh');
			}

			//everthing looks good, Now create user account
			$insert_data = array('email' => $email,
				'password' => md5($password),
				'role' => 4,
				'fname' => $this->input->post('fname'),
				'enroll_time'=> date('Y-m-d H:i:s'),
				'status'=> 2
			);
			$applicant_id = $this->users_model->create_user($insert_data);
			//set applicant_id in session
			$this->session->set_userdata('applicant_id', $applicant_id);
			$this->session->set_userdata('user_fullname', $user_fullname);

			//handle existing application in session/cookies
			$application_id = $this->session->userdata('application_id');
			if($application_id > 0)
			{
				$this->load->model('application_model');
				if(!$this->application_model->is_owner_exists($application_id)){
					//map this application id to applicant
					$this->application_model->map_application_owner($application_id, $applicant_id);
				}
			}

			//generate token
			$token =md5(rand());
			$applicant_id = $this->users_model->get_applicant_id($email);
			$this->users_model->delete_old_tokens($applicant_id, 'verification');
			$insert_data = array('applicant_id' => $applicant_id,
					'token' => $token,
					'type' => 'verification',
					'generated_on' => date('Y-m-d H:i:s'));
			$this->users_model->insert_token($insert_data);

			//handle email alret
			$is_email_service_enable = IS_EMAIL_SERVICE_ENABLE;
			if($is_email_service_enable)
			{
				
				//SMTP Email Start
				$this->load->config('email');
				$this->load->library('email');
				$from = $this->config->item('smtp_user');
				$display_name = $this->config->item('smtp_display_name');
				$to = $this->input->post('email');
				$subject = "Your DPGA account has been created (please verify)";
				$main_url = base_url();
				$data['token_verify_link'] ="" .$main_url. "token/verify/?token=$token&id=$applicant_id";
				$data['applicant_name'] = $this->input->post('fname');
				$message = $this->load->view('emails/e1_signup_confirmation_and_email_verification', $data, true);
				$this->email->set_newline("\r\n");
				$this->email->from($from,$display_name);
				$this->email->to($to);
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();
				//SMTP Email End
			}

			//now redirect user to form
			redirect('/form', 'refresh');
		}
	}

	public function login()
	{
		//first we check cookies and session
		if($this->is_applicant_id_in_cookies() OR $this->session->userdata('applicant_id') > 0)
		{
			if($this->is_applicant_id_in_cookies())
			{
				$applicant_id = $this->is_applicant_id_in_cookies();
			}else{
				$applicant_id = $this->session->userdata('applicant_id');
			}
			
			//save this applicant_id to session
			$this->session->set_userdata('applicant_id', $applicant_id);
			//check email verified or not
			if($this->users_model->is_email_verified($applicant_id))
			{
				$this->session->set_userdata('email_verified', 1);
			}else{
				$this->session->set_userdata('email_verified', 0);
			}
			//now move to home screen
			redirect('/home', 'refresh');
			exit();
		}


		$data['page_title'] = 'Login Account - DPGA';
		$data['page_heading'] = 'DPG Account Login';
		//provide a login form to user
		// $this->load->view('applicant/header', $data);
		// $this->load->view('applicant/login', $data);
		// $this->load->view('applicant/footer', $data);
		$this->load->view('applicant/left_common', $data);
		$this->load->view('applicant/login_new', $data);
	}

	public function login_process()
	{
		if ($this->input->post()) {
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			
			$applicant_id = $this->users_model->is_password_correct($email, $password);

			if($applicant_id)
			{
				//handle remember_me
				if($this->input->post('remember_me') == 'yes')
				{
					//encrpyt the applicant_id
					$encrypted_applicant_id = $this->encryption->encrypt($applicant_id);
					//now send this to cookies
					$toal_seconds = TOTAL_SECONDS_FOR_COOKIES_LOGIN;
					$cookie= array(
						'name'   => 'applicant_id',
						'value'  => $encrypted_applicant_id,
						'expire' => $toal_seconds, 
		          	//'secure' => TRUE
					);
					$this->input->set_cookie($cookie);
				}else{
					//delete any previos cookie for applicant_id
					delete_cookie('applicant_id');
				}

				$user_role = $this->users_model->get_user_role($applicant_id);
				//check email verified or not
					if($this->users_model->is_email_verified($applicant_id))
					{
						$this->session->set_userdata('email_verified', 1);
					}else{
						$this->session->set_userdata('email_verified', 0);
					}
				$user_fullname = $this->users_model->get_user_fullname($applicant_id);
				$this->session->set_userdata('user_fullname', $user_fullname);

				if($user_role == 4)
				{
					//save this applicant_id to session
					$this->session->set_userdata('applicant_id', $applicant_id);
					
					//check ongoing application owner
					//handle existing application in session/cookies
					$application_id = $this->session->userdata('application_id');
					if($application_id > 0)
					{
						$this->load->model('application_model');
						if(!$this->application_model->is_owner_exists($application_id)){
							//map this application id to applicant
							$this->application_model->map_application_owner($application_id, $applicant_id);
						}
					}
					$this->session->set_userdata('user_role_details', "Applicant");
					//now redirect to home
					redirect('/home', 'refresh');
				}else if($user_role == 2 OR $user_role == 3)
				{
					//send to reviewer home screen
					$this->session->set_userdata('user_id', $applicant_id);
					$this->session->set_userdata('user_role', $user_role);
					if($user_role == 2)
					{
						$this->session->set_userdata('user_role_details', "L1 Reviewer");
					}
					if($user_role == 3)
					{
						$this->session->set_userdata('user_role_details', "L2 Reviewer");
					}
					redirect('/reviewer', 'refresh');

				}else if($user_role == 5)
				{
					//send to reviewer home screen
					$this->session->set_userdata('user_id', $applicant_id);
					$this->session->set_userdata('user_role', $user_role);
					$this->session->set_userdata('user_role_details', "Expert");
					redirect('/expert', 'refresh');

				} else{
					//send to admin home screen
					$this->session->set_userdata('user_id', $applicant_id);
					$this->session->set_userdata('user_role', $user_role);
					$this->session->set_userdata('user_role_details', "Admin");
					redirect('/admin/dashboard', 'refresh');
				}


				
			}else{
				//wrong login details
				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
					<strong>Error !!</strong> Account details not correct, Please login with correct details.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
				redirect('/login', 'refresh');
			}
		} else {
			redirect('/login', 'refresh');
		}
	}

	public function home()
	{
		if($this->session->userdata('applicant_id') == 0)
		{
			redirect('/login', 'refresh');
			exit();
		}
		$this->session->set_userdata('viewmode', FALSE);
		$applicant_id = $this->session->userdata('applicant_id');
		//get all projects
		$this->load->model('application_model');
		$data['all_applications'] = $this->application_model->applicant_all_applications($applicant_id);
		$data['all_app_status_for_applicant'] = $this->application_model->all_app_status_for_applicant();

		$data['applicant_details'] = $this->users_model->get_applicant_details($this->session->userdata('applicant_id'));
		$data['dpga_limits'] = $this->application_model->get_dpga_limits();
		
		$data['page_title'] = 'Account home - DPGA';
		$data['page_heading'] = 'home';
		//provide a home to user
		$this->load->view('applicant/header', $data);
		$this->load->view('applicant/home', $data);
		$this->load->view('applicant/footer', $data);
	}

	public function send_verification_email()
	{
		$applicant_id = $this->session->userdata('applicant_id');
		if($applicant_id > 0)
		{
			
			if($this->users_model->is_email_verified($applicant_id))
			{
				//already verified
				$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
					Your email is already verified.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
				redirect('/home', 'refresh');
			}else{
				//need to send an email
				$token =md5(rand());
				$this->users_model->delete_old_tokens($applicant_id, 'verification');
				$insert_data = array('applicant_id' => $applicant_id,
					'token' => $token,
					'type' => 'verification',
					'generated_on' => date('Y-m-d H:i:s'));
				$this->users_model->insert_token($insert_data);
				$applicant_email = $this->users_model->get_applicant_email($applicant_id);

				// now send verification email
				$is_email_service_enable = IS_EMAIL_SERVICE_ENABLE;
				if($is_email_service_enable)
				{
				//SMTP Email Start
				$this->load->config('email');
				$this->load->library('email');
				$from = $this->config->item('smtp_user');
				$display_name = $this->config->item('smtp_display_name');
				$to = $applicant_email;
				$subject = "Link to verify your DPGA account";
				$main_url = base_url();
				$data['token_verify_link'] ="" .$main_url. "token/verify/?token=$token&id=$applicant_id";
				$data['applicant_name'] = $this->users_model->get_user_fullname($applicant_id);
				$message = $this->load->view('emails/e2_email_verification', $data, true);
				$this->email->set_newline("\r\n");
				$this->email->from($from,$display_name);
				$this->email->to($to);
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();
				//SMTP Email End
				}
				$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
					We sent a verification link on your email id. Please click on that link to verify your email id.				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
				redirect('/home', 'refresh');
			}
		}else{
			redirect('/', 'refresh');
		}
	}

	public function verify_token()
	{
		$token = $this->input->get('token');
		$applicant_id =$this->input->get('id');
		
		if($this->users_model->verify_token($token, $applicant_id, 'verification'))
		{
			//token is correct, update status in users table
			$update_data = array('status' => 1);
			$this->users_model->update_user_data($applicant_id, $update_data);
			//delete the token
			$this->users_model->delete_old_tokens($applicant_id, 'verification');
			//set email verify status in session
			$this->session->set_userdata('email_verified', 1);
			$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
				Your email is now verified. Thanks <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
				</div>");
			//redirect to home
			redirect('/home', 'refresh');
			

		}else{
			echo "Link expired. Please regenerate your link";
			echo "<br>";
			echo anchor("/", 'Click here to Homepage', array('title' => 'Click here to Homepage'));
		}
	}

	public function recover_password()
	{
		$data['page_title'] = 'Recover Account Password - DPGA';
		$data['page_heading'] = 'Recover Account Password';
		//provide a recover password form to user
		//$this->load->view('applicant/header', $data);
		//$this->load->view('applicant/recover_password', $data);
		//$this->load->view('applicant/footer', $data);
		$this->load->view('applicant/left_common', $data);
		$this->load->view('applicant/recover_password_new', $data);
	}

	public function generate_password_token()
	{
		if ($this->input->post()) {

			$applicant_email = $this->input->post('email');
			
			
			if($this->users_model->user_already_exists($applicant_email))
			{
				$token =md5(rand());
				$applicant_id = $this->users_model->get_applicant_id($applicant_email);
				$this->users_model->delete_old_tokens($applicant_id, 'reset_password');
				$insert_data = array('applicant_id' => $applicant_id,
					'token' => $token,
					'type' => 'reset_password',
					'generated_on' => date('Y-m-d H:i:s'));
				$this->users_model->insert_token($insert_data);
				
				//now send email
				$is_email_service_enable = IS_EMAIL_SERVICE_ENABLE;
				if($is_email_service_enable)
				{
				//SMTP Email Start
				$this->load->config('email');
				$this->load->library('email');
				$from = $this->config->item('smtp_user');
				$display_name = $this->config->item('smtp_display_name');
				$to = $applicant_email;
				$data['applicant_name'] = $this->users_model->get_user_fullname($applicant_id);
				$subject = "Password reset link for your DPGA account";
				$main_url = base_url();
				$data['token_verify_link'] ="" .$main_url. "reset/password/?token=$token&id=$applicant_id";
				$message = $this->load->view('emails/e13_reset_password', $data, true);
				$this->email->set_newline("\r\n");
				$this->email->from($from,$display_name);
				$this->email->to($to);
				$this->email->subject($subject);
				$this->email->message($message);
				$this->email->send();
				//SMTP Email End
				}
				$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
					We sent a password reset link on your email id. Please click on that link to reset your password.				<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
				redirect('/reset', 'refresh');


			}else{
				//email not exists
				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
					<strong>Error !!</strong> This email not exists in our records. Please recheck email and try.
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
				redirect('/reset', 'refresh');

			}
		}
		else{
			
			redirect('/reset', 'refresh');
		}
	}

	public function reset_password()
	{
		$data['page_title'] = 'Reset Account Password - DPGA';
		$data['page_heading'] = 'Reset Account Password';
		$data['token'] = $this->input->get('token');
		$data['applicant_id'] = $this->input->get('id');
		//provide a reset password form to user
		$this->load->view('applicant/header', $data);
		$this->load->view('applicant/reset_password', $data);
		$this->load->view('applicant/footer', $data);
	}

	public function reset_password_process()
	{
		if ($this->input->post()) {
			$token = $this->input->post('token');
			$applicant_id = $this->input->post('applicant_id');
			$password = $this->input->post('password');
			$confirm_password = $this->input->post('confirm_password');
			if($password == $confirm_password)
			{
				//check token is correct or not
				
				if($this->users_model->verify_token($token, $applicant_id, 'reset_password'))
				{
					//update user password
					$update_data = array('password' => md5($password));
					
					$this->users_model->update_user_data($applicant_id, $update_data);
					//delete the token
					$this->users_model->delete_old_tokens($applicant_id, 'reset_password');
					$this->session->set_flashdata('message', "<div class='alert alert-success alert-dismissible fade show' role='alert'>
						Password changed successfully. Now login with new password
						<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
						</div>");
					redirect('login', 'refresh');

				}else{
					$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
						<strong>Error !!</strong>Password reset link is not correct. Please retry
						<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
						</div>");
					redirect('reset', 'refresh');

				}

				

			}else{

				$this->session->set_flashdata('message', "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
					<strong>Error !!</strong> Password not match
					<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
					</div>");
				$url_string = "/reset/password?token=" .$token. "&id=" .$applicant_id. "";
				redirect($url_string, 'refresh');

			}

		}else{
			redirect('/login', 'refresh');
		}
	}

	public function is_applicant_id_in_cookies()
	{
		$encrypted_applicant_id = $this->input->cookie('applicant_id',true);
		return $applicant_id = $this->encryption->decrypt($encrypted_applicant_id);
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
