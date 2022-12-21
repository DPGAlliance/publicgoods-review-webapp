<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Section_model extends CI_Model {

	
	function __construct()
	{
        // Call the Model constructor
		parent::__construct();
		//$this->load->db;
	}


	function list_all_active_sections()
	{
		$this->db->select('id,name,heading,details,visible_order');
		$this->db->where('status', 1);
		$this->db->order_by("visible_order", "asc");
		$query = $this->db->get('sections');
		return $query->result_array();
	}

	function list_all_sections()
	{
		$this->db->select('id,name,heading,details,visible_order,status');
		$this->db->order_by("visible_order", "asc");
		$query = $this->db->get('sections');
		return $query->result_array();
	}

	function is_section_exists($section_id)
	{
		$this->db->where('id',$section_id);
		$result = $this->db->get('sections')->num_rows();
		if($result == 1){
			return true;
		}else{
			return false;
		}
	}

	function insert_bulk_section_data($bulk_insert_data)
	{
		$this->db->insert_batch('section_status', $bulk_insert_data); 
	}

	function insert_bulk_response_data($bulk_response_data)
	{
		$this->db->insert_batch('response', $bulk_response_data);
	}

	function all_section_data_via_application_id($application_id)
	{
		$this->db->select('sections.id,sections.name,sections.ipr,sections.heading,sections.details,sections.visible_order,section_status.application_id,section_status.consultant,section_status.clarify_question,section_status.clarify_response,section_status.notes,section_status.section_id,section_status.filling_status,section_status.consultant,section_status.r1_status,section_status.r2_status,section_status.consultant_question,section_status.consultation_insight');
		$this->db->from('section_status');
		$this->db->join('sections', 'sections.id = section_status.section_id', 'left');
		$this->db->where('section_status.application_id', $application_id);
		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query->result_array();
	}

	function get_ipr_details($application_id)
	{
		$this->db->select('sections.id,sections.name,sections.ipr,section_status.r1_status,section_status.r2_status');
		$this->db->from('section_status');
		$this->db->join('sections', 'sections.id = section_status.section_id', 'left');
		$this->db->where('section_status.application_id', $application_id);
		$query = $this->db->get();
		// echo $this->db->last_query();
		return $query->result_array();
	}

	function count_no_action_on_sections($application_id, $user_role)
	{
		if($user_role == 2)
		{
			$role_key = 'r1_status';
		} else if($user_role == 3)
		{
			$role_key = 'r2_status';
		}

		$this->db->select('id');
		$this->db->where('application_id', $application_id);
		$this->db->where($role_key, 0);
		return $result = $this->db->get('section_status')->num_rows();
	}

	function count_section_failed($application_id, $user_role)
	{
		if($user_role == 2)
		{
			$role_key = 'r1_status';
		} else if($user_role == 3)
		{
			$role_key = 'r2_status';
		}

		$this->db->select('id');
		$this->db->where('application_id', $application_id);
		$this->db->where($role_key, 2);
		return $result = $this->db->get('section_status')->num_rows();
	}

	function count_clarifications($application_id, $user_role)
	{
		if($user_role == 2)
		{
			$role_key = 'r1_status';
		} else if($user_role == 3)
		{
			$role_key = 'r2_status';
		}

		$this->db->select('id');
		$this->db->where('application_id', $application_id);
		$this->db->where($role_key, 3);
		return $result = $this->db->get('section_status')->num_rows();
	}

	function update_section_data($section_id, $application_id, $data)
	{
		$this->db->where('application_id', $application_id);
		$this->db->where('section_id', $section_id);
		$this->db->update('section_status', $data);
	}

	function update_main_section($section_id, $update_data){
		$this->db->where('id', $section_id);
		$this->db->update('sections', $update_data);
	}

	function create_new_section($data)
	{
		$this->db->insert('sections', $data);
		$section_id = $this->db->insert_id();
		return  $section_id;
	}

	function get_section_name($section_id)
	{
		$this->db->select('name');
		$this->db->where('id',$section_id);
		return $this->db->get('sections')->row()->name;
	}
	
}

