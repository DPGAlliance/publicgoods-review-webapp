<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

	
	function __construct()
	{
        // Call the Model constructor
		parent::__construct();
		//$this->load->db;
	}

	function user_already_exists($email)
	{
		$this->db->where('email',$email);
		$result = $this->db->get('users')->num_rows();
		if($result == 1){
			return true;
		}else{
			return false;
		}
	}

	function create_user($insert_data)
	{
		$this->db->insert('users', $insert_data);
		$user_id = $this->db->insert_id();
		return  $user_id;
	}

	function is_password_correct($email, $password)
	{
		$wherecond = "(( status ='1' OR status='2') AND (email='" . $email . "') AND (password='" . md5($password) . "'))";
		$this->db->select('id');
		$this->db->where($wherecond);
		$query = $this->db->get('users');
		$result = $query->result_array();
		if(count($result) == 0)
		{
			return false;
		}else{
			return $result[0]['id'];
		}
	}

	function is_email_verified($applicant_id)
	{
		$this->db->select('id');
		$this->db->where('id',$applicant_id);
		$this->db->where('status', 2);
		$result = $this->db->get('users')->num_rows();
		if($result == 0){
			return true;
		}else{
			return false;
		}
	}

	function delete_old_tokens($applicant_id, $type)
	{
		$this->db->where('applicant_id', $applicant_id);
		$this->db->where('type', $type);
		$this->db->delete('email_tokens');
	}

	function insert_token($insert_data)
	{
		$this->db->insert('email_tokens', $insert_data);
	}

	function get_applicant_email($applicant_id)
	{
		$this->db->select('email');
		$this->db->where('id',$applicant_id);
		return $this->db->get('users')->row()->email;
	}

	function get_single_active_applicant_id()
	{
		$this->db->select('id');
		$this->db->where('role', 4);
		$this->db->where('status', 1);
		$this->db->order_by('rand()');
    	$this->db->limit(1);
		return $this->db->get('users')->row()->id;
	}

	function get_applicant_id($email)
	{
		$this->db->select('id');
		$this->db->where('email',$email);
		return $this->db->get('users')->row()->id;
	}

	function verify_token($token, $applicant_id, $type)
	{
		$this->db->select('id');
		$this->db->where('applicant_id',$applicant_id);
		$this->db->where('token',$token);
		$this->db->where('type', $type);
		$result = $this->db->get('email_tokens')->num_rows();
		if($result == 1){
			return true;
		}else{
			return false;
		}
	}

	function update_user_data($applicant_id, $update_data)
	{
		$this->db->where('id', $applicant_id);
		$this->db->update('users', $update_data);
	}

	function get_applicant_details($applicant_id)
	{
		$this->db->select('email,fname,lname');
		$this->db->where('id', $applicant_id);
		$query = $this->db->get('users');
		return $query->result_array();
	}

	function get_user_details($email)
	{
		$this->db->select('*');
		$this->db->where('email', $email);
		$query = $this->db->get('users');
		return $query->result_array();
	}

	function get_all_users()
	{
		$this->db->select('*');
		$this->db->where('role !=', 1);
		$query = $this->db->get('users');
		return $query->result_array();
	}

	function get_all_active_reviewers()
	{
		$this->db->select('id,fname,role');
		$this->db->where_in('role', ['2','3']);
		$query = $this->db->get('users');
		return $query->result_array();
	}

	function get_user_role($applicant_id)
	{
		$this->db->select('role');
		$this->db->where('id',$applicant_id);
		return $this->db->get('users')->row()->role;
	}

	function get_user_fullname($applicant_id)
	{
		$this->db->select('fname');
		$this->db->where('id',$applicant_id);
		return $this->db->get('users')->row()->fname;
	}

	function consultant_list()
	{
		$this->db->select('id,email,fname,lname');
		$this->db->where('role', 5);
		$query = $this->db->get('users');
		return $query->result_array();
	}
}

