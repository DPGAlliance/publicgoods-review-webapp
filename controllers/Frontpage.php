<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frontpage extends CI_Controller {

	function __construct()
	{
        // Call the Model constructor
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->helper('form');
	}

	public function index()
	{
		// $this->load->view('frontpage');
		redirect('/signup', 'refresh');
	}
}
