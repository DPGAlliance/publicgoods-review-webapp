<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application_model extends CI_Model {

	
	function __construct()
	{
        // Call the Model constructor
		parent::__construct();
		//$this->load->db;
	}


	function create_fresh_empty_application()
	{
		$insert_data=array(
			'type'=>'new',
			'added_on'=>date('Y-m-d H:i:s'),
		);
		$this->db->insert('applications', $insert_data);
		$application_id = $this->db->insert_id();
		return  $application_id;
	}

	function create_application_applicant($applicant_id)
	{
		$insert_data=array(
			'type'=>'new',
			'added_on'=>date('Y-m-d H:i:s'),
			'applicant_id'=>$applicant_id,
		);
		$this->db->insert('applications', $insert_data);
		$application_id = $this->db->insert_id();
		return  $application_id;
	}

	function create_application_applicant_via_import($applicant_id, $insert_data)
	{
		$this->db->insert('applications', $insert_data);
		$application_id = $this->db->insert_id();
		return  $application_id;
	}

	function create_refresher_application_applicant($applicant_id,$application_id)
	{
		$insert_data=array(
			'type'=>'refresher',
			'added_on'=>date('Y-m-d H:i:s'),
			'applicant_id'=>$applicant_id,
			'parent_id'=>$application_id
		);
		$this->db->insert('applications', $insert_data);
		$application_id = $this->db->insert_id();
		return  $application_id;
	}

	function get_all_section_questions($section_id)
	{
		$this->db->select('*');
		$this->db->where('status', 1);
		$this->db->where('section_id', $section_id);
		$this->db->order_by("visible_order", "asc");
		$query = $this->db->get('questions');
		return $query->result_array();
	}

	function get_all_section_answers($section_id, $application_id)
	{
		$this->db->select('question_id,answer');
		$this->db->where('application_id', $application_id);
		$this->db->where('section_id', $section_id);
		$query = $this->db->get('response');
		return $query->result_array();
	}

	function remove_old_answers($application_id, $section_id)
	{
		$this->db->where('application_id', $application_id);
		$this->db->where('section_id', $section_id);
		$this->db->delete('response');
	}

	function insert_answers_in_bulk($bulk_insert_data)
	{
		$this->db->insert_batch('response', $bulk_insert_data); 
	}

	function insert_single_answer($insert_data)
	{
		$this->db->insert('response', $insert_data); 
	}

	function count_filled_sections($application_id)
	{
		$this->db->where('application_id',$application_id);
		$this->db->where('filling_status', 1);
		return $result = $this->db->get('section_status')->num_rows();
	}

	function count_total_sections($application_id)
	{
		$this->db->where('application_id',$application_id);
		return $result = $this->db->get('section_status')->num_rows();
	}

	function total_clarifications_asked($application_id)
	{
		$this->db->where('application_id',$application_id);
		$this->db->where('clarify_question !=', "");
		return $result = $this->db->get('section_status')->num_rows();
	}

	function total_clarifications_submitted($application_id)
	{
		$this->db->where('application_id',$application_id);
		$this->db->where('clarify_response !=', "");
		return $result = $this->db->get('section_status')->num_rows();
	}

	function is_owner_exists($application_id)
	{
		$this->db->select('applicant_id');
		$this->db->where('id', $application_id);
		$row = $this->db->get('applications')->row();
		$applicant_id = $row->applicant_id;
		if($applicant_id > 0)
		{
			return true;
		}else{
			return false;
		}
	}

	function get_expiry_date($application_id)
	{
		$this->db->select('expire_on');
		$this->db->where('id', $application_id);
		$row = $this->db->get('applications')->row();
		return $row->expire_on;
	}

	function get_application_status($application_id)
	{
		$this->db->select('status');
		$this->db->where('id', $application_id);
		$row = $this->db->get('applications')->row();
		return $status = $row->status;
	}

	function get_tags_string($application_id)
	{
		$this->db->select('tags');
		$this->db->where('id', $application_id);
		$row = $this->db->get('applications')->row();
		return $tags = $row->tags;
	}

	function get_tag_name($tag_id)
	{
		$this->db->select('name');
		$this->db->where('id', $tag_id);
		$row = $this->db->get('tags')->row();
		return $tags = $row->name;
	}

	function is_application_exists($application_id)
	{
		$this->db->select('id');
		$this->db->where('id', $application_id);
		$query = $this->db->get('applications')->num_rows();
		if($query == 1){
			return true;
		}else{
			return false;
		}
	}

	function is_application_not_submitted($application_id)
	{
		$this->db->select('id');
		$this->db->where('id', $application_id);
		$this->db->where('status', 0);
		$query = $this->db->get('applications')->num_rows();
		if($query == 1){
			return true;
		}else{
			return false;
		}
	}

	function is_application_nominee($application_id)
	{
		$this->db->select('nominee');
		$this->db->where('id', $application_id);
		return $this->db->get('applications')->row()->nominee;
	}

	function is_application_need_clarifications($application_id)
	{
		$this->db->select('id');
		$this->db->where('id', $application_id);
		$this->db->where('status', 6);
		$query = $this->db->get('applications')->num_rows();
		if($query == 1){
			return true;
		}else{
			return false;
		}
	}

	function map_application_owner($application_id, $applicant_id)
	{
		$this->db->where('id', $application_id);
		$this->db->update('applications', array('applicant_id' => $applicant_id));
	}

	function applicant_all_applications($applicant_id)
	{
		$question_id = PROJECT_NAME_ID;
		$this->db->select('applications.*,response.answer');
		$this->db->where('applications.applicant_id', $applicant_id);
		$this->db->join('response', 'response.application_id = applications.id', 'left');
		$this->db->where('response.question_id', $question_id);
		$query = $this->db->get('applications');
		// echo $this->db->last_query();
		return $query->result_array();
	}

	function all_app_status_for_applicant()
	{
		$this->db->select('id, applicant');
		$query = $this->db->get('app_status');
		return $query->result_array();
	}

	function get_new_qids()
	{
		$this->db->select('id as new_qid, section_id');
		$this->db->where('status', 1);
		$query = $this->db->get('questions');
		return $query->result_array();
	}

	function get_old_responses($application_id)
	{
		$this->db->select('section_id, question_id, answer');
		$this->db->where('application_id', $application_id);
		$query = $this->db->get('response');
		return $query->result_array();
	}

	function all_app_status_for_reviewer($user_role)
	{
		if($user_role == 2)
		{
			$this->db->select('id, l1_reviewer AS reviewer');
		}else if($user_role == 3){
			$this->db->select('id, l2_reviewer AS reviewer');
		}else{
			$this->db->select('id, admin AS reviewer');
		}
		$query = $this->db->get('app_status');
		return $query->result_array();
	}


	function is_application_owner($application_id, $applicant_id)
	{
		$this->db->select('id');
		$this->db->where('id', $application_id);
		$this->db->where('applicant_id', $applicant_id);
		$query = $this->db->get('applications')->num_rows();
		if($query == 1){
			return true;
		}else{
			return false;
		}
	}

	function is_assigned_to_me($application_id, $user_role, $user_id)
	{
		if($user_role == 2)
		{
			$this->db->where('applications.current_l1', $user_id);
		} else if ($user_role == 3){
			$this->db->where('applications.current_l2', $user_id);
		}
		$this->db->where('applications.id', $application_id);
		$query = $this->db->get('applications')->num_rows();
		if($query == 1){
			return true;
		}else{
			return false;
		}
	}

	function is_possible_to_review($application_id)
	{
		$this->db->where('applications.id', $application_id);
		$this->db->where('applications.status >', 0);
		$this->db->where('applications.status <', 7);
		$query = $this->db->get('applications')->num_rows();
		if($query == 1){
			return true;
		}else{
			return false;
		}
	}

	function update_application_data($update_data, $application_id, $applicant_id)
	{
		$this->db->where('id', $application_id);
		$this->db->where('applicant_id', $applicant_id);
		$this->db->update('applications', $update_data);
	}

	function consultant_inputs($update_data, $application_id,$consultant_id,$input_id)
	{
		$this->db->where('id', $input_id);
		$this->db->where('application_id', $application_id);
		$this->db->where('consultant_id', $consultant_id);
		$this->db->update('consultant_data', $update_data);
	}

	function update_clarify_response($data,$section_id,$application_id)
	{
		$this->db->where('section_id', $section_id);
		$this->db->where('application_id', $application_id);
		$this->db->update('section_status', $data);
	}


	function insert_application_log($insert_data)
	{
		$this->db->insert('app_logs', $insert_data);
	}

	function get_solution_name($application_id)
	{
		$solution_name_id = PROJECT_NAME_ID;
		$this->db->select('answer');
		$this->db->where('question_id', $solution_name_id);
		$this->db->where('application_id', $application_id);
		$query = $this->db->get('response');
		$result = $query->result_array();
		if(count($result) == 1)
		{
			return $result[0]['answer'];
		}else{
			return "";
		}
	}

	function get_section_name($section_id)
	{
		$this->db->select('name');
		$this->db->where('id', $section_id);
		return $this->db->get('sections')->row()->name;
	}


	function get_queue_applications($user_role)
	{
		
		if($user_role == 2)
		{
			$status = 1;
		} else if ($user_role == 3){
			$status = 3;
		}
		$question_id = PROJECT_NAME_ID;
		$this->db->select('applications.*,response.answer, t2.fname as previous_l1_fname, t2.lname as previous_l1_lname, t3.fname as previous_l2_fname, t3.lname as previous_l2_lname');
		$this->db->join('response', 'response.application_id = applications.id', 'left');
		$this->db->join('users t2', 't2.id = applications.previous_l1', 'left');
		$this->db->join('users t3', 't3.id = applications.previous_l2', 'left');
		$this->db->where('response.question_id', $question_id);
		$this->db->where('applications.status', $status);
		$query = $this->db->get('applications');
		return $query->result_array();
	}

	function get_under_review_application($user_role, $user_id)
	{
		
		$question_id = PROJECT_NAME_ID;
		$this->db->select('applications.*,response.answer');
		$this->db->join('response', 'response.application_id = applications.id', 'left');
		$this->db->where('response.question_id', $question_id);
		if($user_role == 2)
		{
			$status = 2;
			$this->db->where('applications.current_l1', $user_id);
		} else if ($user_role == 3){
			$status = 4;
			$this->db->where('applications.current_l2', $user_id);
		}
		$this->db->where('applications.status', $status);
		$query = $this->db->get('applications');
		return $query->result_array();
	}

	function get_under_consultation_application($user_role, $user_id)
	{
		$question_id = PROJECT_NAME_ID;
		$this->db->select('applications.*,response.answer');
		$this->db->join('response', 'response.application_id = applications.id', 'left');
		$this->db->where('response.question_id', $question_id);
		if($user_role == 2)
		{
			$status = 5;
			$this->db->where('applications.current_l1', $user_id);
		} else if ($user_role == 3){
			$status = 5;
			$this->db->where('applications.current_l2', $user_id);
		}else if ($user_role == 5){
			$status = 5;
		}
		$this->db->where('applications.status', $status);
		$query = $this->db->get('applications');
		return $query->result_array();
	}


	function get_under_consultation_application_for_expert($user_role, $user_id)
	{
		$question_id = PROJECT_NAME_ID;
		$this->db->select('applications.*,response.answer');
		$this->db->join('response', 'response.application_id = applications.id', 'left');
		$this->db->join('section_status', 'section_status.application_id = applications.id', 'left');
		
		$this->db->where('response.question_id', $question_id);
		if ($user_role == 5){
			$status = 5;
			 $this->db->where('find_in_set("'.$user_id.'", section_status.consultant) <> 0');
		}
		$this->db->where('applications.status', $status);
		$this->db->distinct();
		$query = $this->db->get('applications');
		//echo $this->db->last_query();
		return $query->result_array();
	}


	function experts_response($user_role, $user_id)
	{
		$this->db->select('consultant_data.id,consultant_data.application_id,consultant_data.consultant_id,consultant_data.response,users.fname,users.lname');

		$this->db->join('applications', 'applications.id = consultant_data.application_id', 'left');
		$this->db->join('users', 'users.id = consultant_data.consultant_id', 'left');
		
		$this->db->where('applications.status', 5);
		$this->db->where('applications.current_l2', $user_id);
		$query = $this->db->get('consultant_data');
		// echo $this->db->last_query();
		return $query->result_array();
	}

	function experts_response_for_dashboard($user_role, $user_id, $app_ids_array)
	{
		
		$this->db->select('consultant_data.id,consultant_data.application_id,consultant_data.consultant_id,consultant_data.response,users.fname,users.lname');

		$this->db->join('applications', 'applications.id = consultant_data.application_id', 'left');
		$this->db->join('users', 'users.id = consultant_data.consultant_id', 'left');
		
		$this->db->where('applications.status', 5);
		if(count($app_ids_array) > 0)
		{
			$this->db->where_in('consultant_data.application_id', $app_ids_array);
		}
		
		$query = $this->db->get('consultant_data');
		// echo $this->db->last_query();
		return $query->result_array();
	}


	function is_assigned_to_expert($application_id, $user_role, $user_id)
	{
		$this->db->select('section_status.id');
		$this->db->where('find_in_set("'.$user_id.'", section_status.consultant) <> 0');
		$query = $this->db->get('section_status')->num_rows();
		if($query > 0){
			return true;
		}else{
			return false;
		}
	}

	function clarifications_application($user_role, $user_id)
	{
		$question_id = PROJECT_NAME_ID;
		$this->db->select('applications.*,response.answer');
		$this->db->join('response', 'response.application_id = applications.id', 'left');
		$this->db->where('response.question_id', $question_id);
		if($user_role == 2)
		{
			$status = 6;
			$this->db->where('applications.current_l1', $user_id);
		} else if ($user_role == 3){
			$status = 6;
			$this->db->where('applications.current_l2', $user_id);
		}
		$this->db->where('applications.status', $status);
		$query = $this->db->get('applications');
		return $query->result_array();
	}

	function all_tags_list()
	{
		$this->db->select('id,name,weight,mode');
		$this->db->where('status', 1);
		$query = $this->db->get('tags');
		return $query->result_array();
	}

	function verify_application_status($application_id, $status)
	{
		$this->db->select('id');
		$this->db->where('id', $application_id);
		$this->db->where('status', $status);
		$query = $this->db->get('applications')->num_rows();
		if($query == 1){
			return true;
		}else{
			return false;
		}
	}

	function is_already_assigned($application_id, $user_role)
	{

		$this->db->select('id');
		$this->db->where('id', $application_id);
		if($user_role == 2)
		{
			$this->db->where('current_l1 >', '0');
		} else if ($user_role == 3){
			$this->db->where('current_l2 >', '0');
		}
		$query = $this->db->get('applications')->num_rows();
		if($query == 1){
			return true;
		}else{
			return false;
		}
	}

	function is_reviewer_already_busy($user_id, $user_role)
	{
		$this->db->select('id');
		if($user_role == 2)
		{
			$status = 2;
			$this->db->where('current_l1', $user_id);
		} else if ($user_role == 3){
			$status = 4;
			$this->db->where('current_l2', $user_id);
		}
		
		$this->db->where('status', $status);
		$query = $this->db->get('applications')->num_rows();
		//echo $this->db->last_query();
		if($query > 0){
			return true;
		}else{
			return false;
		}
	}

	function assign_application($application_id,$user_role,$user_id)
	{
		if($user_role == 2)
		{
			$data = array(
		        'current_l1' => $user_id,
		        'status' => 2,
		        'l1_assign_on' => date('Y-m-d H:i:s'),
		        'l1_timer'=> 1,
			);
		} else if ($user_role == 3){
			$data = array(
		        'current_l2' => $user_id,
		        'status' => 4,
		        'l2_assign_on' => date('Y-m-d H:i:s'),
		        'l2_timer'=> 1,
			);
		}
		$this->db->where('id', $application_id);
		$this->db->update('applications', $data);
	}

	function get_dpga_limits()
	{
		$this->db->select('*');
		$query = $this->db->get('limits');
		return $query->result_array();
	}

	function ind_application_data($application_id)
	{
		$this->db->select('applications.*,
			t1.fname as l1_fname,
			t1.lname as l1_lname,
			t2.fname as l2_fname,
			t2.lname as l2_lname,
			');
		$this->db->join('users t1', 't1.id = applications.current_l1', 'left');
		$this->db->join('users t2', 't2.id = applications.current_l2', 'left');
		$this->db->where('applications.id', $application_id);
		$query = $this->db->get('applications');
		return $query->result_array();
	}

	function show_timer($application_id, $user_role)
	{
		$this->db->select('l1_timer,l2_timer');
		
		$this->db->where('id', $application_id);
		$query = $this->db->get('applications');
		$result = $query->result_array();

		if($user_role == 2)
		{
			return $result[0]['l1_timer'];
		} else if ($user_role == 3){
			return $result[0]['l2_timer'];
		}	
	}

	function change_to_consultation($user_id, $application_id)
	{
		$this->db->where('id', $application_id);
		$this->db->update('applications', array('current_l2' => $user_id,
			'status' => 5,
			'l2_timer' => 0,
			'from_consultation'=> NULL,
			'to_consultation'=>date('Y-m-d H:i:s')
		));
	}

	function change_to_underreview($user_id, $application_id)
	{
		
		$this->db->select('to_consultation, consultation_duration');
		$this->db->where('id', $application_id);
		$row = $this->db->get('applications')->row();
		$to_consultation = $row->to_consultation;
		$consultation_duration = $row->consultation_duration;

		//now get difference
		//current time - to_consultation time
		$duration_string = strtotime("now") - strtotime($to_consultation);

		$consultation_duration = $consultation_duration + $duration_string;



		$this->db->where('id', $application_id);
		$this->db->update('applications', array('current_l2' => $user_id,
			'status' => 4,
			'l2_timer' => 1,
			'consultation_duration' => $consultation_duration,
			'from_consultation'=>date('Y-m-d H:i:s')
		));
	}

	function count_under_consultation($application_id)
	{
		$this->db->where('application_id',$application_id);
		$this->db->where('r2_status', 4);
		return $result = $this->db->get('section_status')->num_rows();
	}

	function is_application_under_consultation($application_id)
	{
		$this->db->where('id',$application_id);
		$this->db->where('status', 5);
		$rows_count = $this->db->get('applications')->num_rows();
		if($rows_count == 0)
		{
			return false;
		}else{
			return true;
		}
	}

	function is_clarifications_submit($application_id)
	{
		$this->db->where('id',$application_id);
		$this->db->where('clarifications_submit	', 1);
		$rows_count = $this->db->get('applications')->num_rows();
		if($rows_count == 0)
		{
			return false;
		}else{
			return true;
		}
	}

	function update_app_data($update_data, $application_id)
	{
		$this->db->where('id', $application_id);
		$this->db->update('applications', $update_data);
	}

	function get_application_logs($application_id)
	{
		$this->db->select('app_logs.*,users.fname, users.lname, sections.name as section_name');
		$this->db->join('users', 'users.id = app_logs.perform_by', 'left');
		$this->db->join('sections', 'sections.id = app_logs.section_id', 'left');
		$this->db->where('app_logs.application_id', $application_id);
		$this->db->order_by("app_logs.id", "desc");
		$query = $this->db->get('app_logs');
		// echo $this->db->last_query();
		return $query->result_array();
	}

	function list_directory()
	{
		$question_id = PROJECT_NAME_ID;
		$this->db->select('applications.*,response.answer as solution_name');
		$this->db->join('response', 'response.application_id = applications.id', 'left');
		$this->db->where('response.question_id', $question_id);
		$this->db->order_by("applications.id", "desc");
		$query = $this->db->get('applications');
		return $query->result_array();
	}

	function all_application_response($application_id)
	{
		$this->db->select('response.answer,
			response.section_id as response_section_id,
			response.question_id as response_question_id,
			sections.name as section_name,
			questions.name as q_name,
			questions.description as q_description,
			section_status.filling_status,
			section_status.r1_status,
			section_status.r2_status,
			section_status.clarify_question,
			section_status.clarify_response	,
			section_status.notes			
			');


		$this->db->join('sections', 'sections.id = response.section_id', 'left');
		$this->db->join('questions', 'questions.id = response.question_id', 'left');
		$this->db->join('section_status', 'section_status.section_id = response.section_id AND section_status.application_id = response.application_id', 'left');
		
		$this->db->where('response.application_id', $application_id);
		$this->db->order_by("response.section_id", "asc");
		$this->db->order_by("questions.visible_order", "asc");
		$query = $this->db->get('response');
		//echo $this->db->last_query();
		return $query->result_array();
	}

	function replace_consultant_questions($consultant_id, $insert_data)
	{
		$application_id = $insert_data['application_id'];
		$section_id = $insert_data['section_id'];
		//first check record already exists
		$this->db->select('question');
		$this->db->where('consultant_id',$consultant_id);
		$this->db->where('application_id',$application_id);
		$this->db->where('section_id',$section_id);
		$query = $this->db->get('consultant_data');
		$result = $query->result_array();
		if(count($result) == 0)
		{
			

			if($insert_data['question'] == "")
			{
				$this->db->select('consultant_question');
				$this->db->where('application_id',$application_id);
				$this->db->where('section_id',$section_id);
				$query = $this->db->get('section_status');
				$result_new = $query->result_array();
				$insert_data['question'] = $result_new[0]['consultant_question'];
			}

			
			//insert details
			$this->db->insert('consultant_data', $insert_data);
		}else{
			$insert_data['question'] = $result[0]['question'];
			//update details
			$this->db->where('consultant_id',$consultant_id);
			$this->db->where('application_id',$application_id);
			$this->db->where('section_id',$section_id);
			$this->db->update('consultant_data', $insert_data);
		}
	}

	function get_all_consultant_response($section_id, $application_id)
	{
		$this->db->select('consultant_data.id,consultant_data.consultant_id,consultant_data.section_id,consultant_data.question,consultant_data.response, consultant_data.response_on,users.fname, users.lname');
		$this->db->join('users', 'users.id = consultant_data.consultant_id', 'left');
		$this->db->where('consultant_data.application_id', $application_id);
		$this->db->where('consultant_data.section_id', $section_id);
		$query = $this->db->get('consultant_data');
		//echo $this->db->last_query();
		return $query->result_array();
	}

	function get_all_consultant_response_new($current_section_id, $application_id)
	{
		$this->db->select('consultant_data.consultant_id,consultant_data.section_id,consultant_data.question,consultant_data.response, consultant_data.response_on,users.fname, users.lname');
		$this->db->join('users', 'users.id = consultant_data.consultant_id', 'left');
		$this->db->where('consultant_data.application_id', $application_id);
		// $this->db->where('consultant_data.section_id', $section_id);
		$query = $this->db->get('consultant_data');
		//echo $this->db->last_query();
		return $query->result_array();
	}

	public function get_all_dpg_applications_response()
	{
		$this->db->select('response.answer,response.application_id,response.	question_id,users.fname, users.lname, users.email');
		
		$this->db->join('applications', 'applications.id = response.application_id', 'left');
		$this->db->join('users', 'users.id = applications.applicant_id', 'left');
		$this->db->where('applications.status', 8);
		$query = $this->db->get('response');
		return $query->result_array();
	}

	public function get_all_nominee_applications_response()
	{
		$this->db->select('response.answer,response.application_id,response.	question_id,users.fname, users.lname, users.email');
		
		$this->db->join('applications', 'applications.id = response.application_id', 'left');
		$this->db->join('users', 'users.id = applications.applicant_id', 'left');
		$this->db->where('applications.nominee', 1);
		$query = $this->db->get('response');
		return $query->result_array();
	}

	public function get_application_response($application_id)
	{
		$this->db->select('response.answer,response.application_id,response.	question_id,users.fname, users.lname, users.email');
		
		$this->db->join('applications', 'applications.id = response.application_id', 'left');
		$this->db->join('users', 'users.id = applications.applicant_id', 'left');
		$this->db->where('applications.status', 8);
		$this->db->where('response.application_id', $application_id);
		$query = $this->db->get('response');
		return $query->result_array();
	}

	public function get_application_response_for_github($application_id)
	{
		$this->db->select('response.answer,response.application_id,response.	question_id,users.fname, users.lname, users.email');
		
		$this->db->join('applications', 'applications.id = response.application_id', 'left');
		$this->db->join('users', 'users.id = applications.applicant_id', 'left');
		$this->db->where('response.application_id', $application_id);
		$query = $this->db->get('response');
		return $query->result_array();
	}


	public function get_nominee_application_response($application_id)
	{
		$this->db->select('response.answer,response.application_id,response.	question_id,users.fname, users.lname, users.email');
		
		$this->db->join('applications', 'applications.id = response.application_id', 'left');
		$this->db->join('users', 'users.id = applications.applicant_id', 'left');
		$this->db->where('applications.nominee', 1);
		$this->db->where('response.application_id', $application_id);
		$query = $this->db->get('response');
		return $query->result_array();
	}

	function get_qid_with_section_id($application_id)
	{
		$this->db->select('questions.id as qid, sections.id as section_id');
		$this->db->join('sections', 'sections.id = questions.section_id', 'left');
		$this->db->join('section_status', 'section_status.section_id = sections.id', 'left');
		$this->db->where('questions.status', 1);
		$this->db->where('section_status.application_id', $application_id);
		$query = $this->db->get('questions');
		return $query->result_array();
	}

	function get_new_applications()
	{
		$this->db->select('old_dpg_id');
		$this->db->where('old_dpg_id is NOT NULL', NULL, FALSE);
		$query = $this->db->get('applications');
		return $query->result_array();
	}

	function is_already_imported($old_application_id)
	{
		$this->db->select('id');
		$this->db->where('old_dpg_id', $old_application_id);
		$result = $this->db->get('applications')->num_rows();
		if($result == 1)
		{
			return true;
		}else{
			return false;
		}
	}

	function get_application_id($old_application_id)
	{
		$this->db->select('id');
		$this->db->where('old_dpg_id', $old_application_id);
		return $this->db->get('applications')->row()->id;
	}

	function delete_all_old_respones($application_id)
	{
		$this->db->where('application_id', $application_id);
		$this->db->delete('response');
	}

	function insert_bulk_response($bulk_response_array)
	{
		$this->db->insert_batch('response', $bulk_response_array); 
	}

	function delete_all_section_status($application_id)
	{
		$this->db->where('application_id', $application_id);
		$this->db->delete('section_status');
	}

	function delete_all_logs($application_id)
	{
		$this->db->where('application_id', $application_id);
		$this->db->delete('app_logs');
	}

	function delete_application($application_id)
	{
		$this->db->where('id', $application_id);
		$this->db->delete('applications');
	}

	function list_all_questions_in_system($section_id)
	{
		$this->db->select('questions.*, sections.name as section_name');
		$this->db->join('sections', 'sections.id = questions.section_id', 'left');
		if($section_id > 0)
		{
			$this->db->where('questions.section_id', $section_id);
		}
		$this->db->order_by("questions.section_id", "asc");
		$this->db->order_by("questions.visible_order", "asc");

		$query = $this->db->get('questions');
		return $query->result_array();
	}

	function update_question_details($question_id, $data)
	{
		$this->db->where('id', $question_id);
		$this->db->update('questions', $data);
	}

	function create_new_question($data)
	{
		$this->db->insert('questions', $data);
		$question_id = $this->db->insert_id();
		return  $question_id;
	}

	function ind_question_details($question_id)
	{
		$this->db->select('*');
		$this->db->where('id', $question_id);
		$query = $this->db->get('questions');
		return $query->result_array();
	}

	function get_response_details($question_id, $section_id, $application_id)
	{
		$this->db->select('answer');
		$this->db->where('question_id', $question_id);
		$this->db->where('section_id', $section_id);
		$this->db->where('application_id', $application_id);
		$query = $this->db->get('response');
		return $query->result_array();
	}

	function delete_old_response($question_id, $application_id)
	{
		$this->db->where('application_id', $application_id);
		$this->db->where('question_id', $question_id);
		$this->db->delete('response');
	}

	function get_logs_list($application_id, $user_id, $date1, $date2, $rowno, $rowperpage, $apply_filter)
	{
		
		$this->db->select('app_logs.id, app_logs.comment, app_logs.application_id, app_logs.perform_on, users.fname');

		$this->db->join('users', 'users.id = app_logs.perform_by', 'left');
		if($application_id > 0)
		{
			$this->db->where('app_logs.application_id', $application_id);
		}

		if($user_id > 0)
		{
			$this->db->where('app_logs.perform_by', $user_id);
		}

		if($user_id == "system")
		{
			$this->db->where('app_logs.perform_by', NULL);
		}

		if($date1 != 0)
		{
			$this->db->where('app_logs.perform_on >=', $date1);
		}

		if($date2 != 0)
		{
			
			$this->db->where('app_logs.perform_on <=', date('Y-m-d 23:59:59',strtotime($date2)));
		}

		if($apply_filter == 1)
		{
			$this->db->limit($rowperpage, $rowno);
		}
		

		$this->db->order_by("app_logs.perform_on", "desc");
		

		$query = $this->db->get('app_logs');
		//echo $this->db->last_query();
		return $query->result_array();
	}

	function users_list_for_log_filter()
	{
		$this->db->select('users.id, users.fname, roles.role_name');
		$this->db->join('roles', 'users.role = roles.id', 'left');
		$this->db->where('users.status', 1);
		$this->db->order_by("users.fname", "asc");
		$query = $this->db->get('users');
		return $query->result_array();
	}

	function get_rows_limit()
	{
		$this->db->select('log_count');
		$this->db->where('id', 1);
		$row = $this->db->get('limits')->row();
		return $row->log_count;
	}

	function get_limits_data()
	{
		$this->db->select('*');
		$query = $this->db->get('limits');
		return $query->result_array();
	}

	function update_limits($update_data)
	{
		$this->db->where('id', 1);
		$this->db->update('limits', $update_data);
	}

	function applications_list_crons($status, $expired)
	{
		$this->db->select('id,l1_assign_on,l1_timer,tags,l2_assign_on,l2_timer,to_clarifications,clarifications_days,clarifications_submit,consultation_duration');
		$this->db->where_in('status', $status );
		if($expired)
		{
			$this->db->where('expire_on <', date('Y-m-d H:i:s'));
		}
		$query = $this->db->get('applications');
		return $query->result_array();
	}


	function get_parent_id($application_id)
	{
		$this->db->select('parent_id');
		$this->db->where('id', $application_id);
		$row = $this->db->get('applications')->row();
		return $parent_id = $row->parent_id;
	}

	function get_application_type($application_id)
	{
		$this->db->select('type');
		$this->db->where('id', $application_id);
		$row = $this->db->get('applications')->row();
		return $type = $row->type;
	}

	function get_applicant_id($application_id)
	{
		$this->db->select('applicant_id');
		$this->db->where('id', $application_id);
		$row = $this->db->get('applications')->row();
		return $applicant_id = $row->applicant_id;
	}

	function get_applicant_status($application_id)
	{
		$this->db->select('status');
		$this->db->where('id', $application_id);
		$row = $this->db->get('applications')->row();
		return $status = $row->status;
	}

	function count_applications($status_array, $date1, $date2)
	{
		$this->db->select('id');
		$this->db->where_in('status', $status_array);
		$this->db->where('submitted_on >', $date1);
		$this->db->where('submitted_on <', $date2);
		return $this->db->get('applications')->num_rows();
	}

	function count_completed_applications($status_array, $date1, $date2)
	{
		$this->db->select('id');
		$this->db->where_in('status', $status_array);
		$this->db->where('review_complete_on >', $date1);
		$this->db->where('review_complete_on <', $date2);
		return $this->db->get('applications')->num_rows();
	}

	function count_pending_applications($status_array)
	{
		$this->db->select('id');
		$this->db->where_in('status', $status_array);
		return $this->db->get('applications')->num_rows();
	}

	function count_applications_through_status($status)
	{
		$this->db->select('id');
		$this->db->where('status', $status);
		return $this->db->get('applications')->num_rows();
	}


	function count_nominees_applications()
	{
		$this->db->select('id');
		$this->db->where('nominee', 1);
		return $this->db->get('applications')->num_rows();
	}

	function get_graph1_data($date1, $date2)
	{
		$this->db->select('*');
		$this->db->where('date >', $date1);
		$this->db->where('date <', $date2);
		$this->db->order_by("date", "asc");
		$query = $this->db->get('graph_1');

		return $query->result_array();
	}

	function update_graph1_data($type)
	{
		//first we check date is already created or not 
		
		$this->db->select('id');
		$this->db->where('date', date('Y-m-d'));
		$total_rows = $this->db->get('graph_1')->num_rows();
		if($total_rows == 0)
		{
			//create a new row with date
			$insert_data = array('date' => date('Y-m-d'));
			$this->db->insert('graph_1', $insert_data);
		}
		if($type == "application_received")
		{
			$this->db->select('application_received');
			$this->db->where('date', date('Y-m-d'));
		$row = $this->db->get('graph_1')->row();
		$current_count = $row->application_received;
		$new_count = $current_count+1;
		$update_data = array('application_received' => $new_count);
		}

		if($type == "decision_completed")
		{
			$this->db->select('decision_completed');
			$this->db->where('date', date('Y-m-d'));
			$row = $this->db->get('graph_1')->row();
			$current_count = $row->decision_completed;
			$new_count = $current_count+1;
			$update_data = array('decision_completed' => $new_count);
		}

		//now update new entry
		$this->db->where('date', date('Y-m-d'));
		$this->db->update('graph_1', $update_data);
	}

	function status_wise_count()
	{
		$query = $this->db->query('Select status as status_id, count(*) AS total from applications group by status');
		return $query->result_array();
	}

	

function status_wise_count_with_late()
	{
		$query = $this->db->query('SELECT status as status_id, count(*) AS total 
from applications
WHERE FIND_IN_SET(4,tags) > 0
group by status');
		return $query->result_array();
	}

	function days_difference()
	{
		
$query = $this->db->query('SELECT id,added_on,submitted_on,review_complete_on,status, DATEDIFF(review_complete_on, submitted_on) AS days_difference 
FROM `applications` WHERE status IN (7,8)');
		return $query->result_array();
	}


	function get_list_of_pending_applications($status_array, $date1, $date2)
	{
		$this->db->select('id');
		$this->db->where('added_on >=', $date1);
		$this->db->where('added_on <=', $date2);
		$this->db->where_in('status', $status_array);
		$query = $this->db->get('applications');
		return $query->result_array();
	}

	function list_clarifications_applications($status_array, $date1, $date2)
	{
		$this->db->select('id,clarifications_days');
		$this->db->where('to_clarifications >=', $date1);
		$this->db->where('to_clarifications <=', $date2);
		$this->db->where_in('status', $status_array);
		$query = $this->db->get('applications');

		return $query->result_array();
	}

	function list_applications_for_renewal($status_array, $expiry_date)
	{
		$this->db->select('id,expire_on');
		$this->db->where('expire_on', $expiry_date);
		$this->db->where_in('status', $status_array);
		$query = $this->db->get('applications');
		return $query->result_array();
	}

	function is_child_created($application_id)
	{
		$this->db->select('id');
		$this->db->where('parent_id', $application_id);
		$query = $this->db->get('applications')->num_rows();
		if($query == 1){
			return true;
		}else{
			return false;
		}
	}

	function get_last_application_for_pr()
	{
		$this->db->select('id');
		$this->db->where('pr_status', 0);
		$this->db->where('status !=', 0);
		$this->db->order_by("id", "desc");
		$this->db->limit(1);
		$query = $this->db->get('applications');
		return $query->result_array();
	}


	function update_section_status_data($update_data, $application_id)
	{
		$this->db->where('application_id', $application_id);
		$this->db->where('r2_status', 3);
		$this->db->update('section_status', $update_data);
	}
	

	
}

