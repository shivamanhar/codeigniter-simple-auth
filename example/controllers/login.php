<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller{

	function __construct(){
		parent::__construct();

		$this->load->library('session');
	}

	public function index(){
		// Session flash data of signin attempt error
		$data['invalid'] = $this->session->flashdata('login_error');

		// Loading a view
		$this->load->view('login_view', $data);
	}

	public function dologin(){
		// User input
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		// Do you sanitaze here
		// 
		// Do not encrypt password

		// Runs authentication
		$auth = $this->auth->authenticate($username, $password);

		// Conditional to success or failure
		if ($auth['success']){
			redirect('/login/success');
		} else {
			$this->session->set_flashdata('login_error', $auth['message']);
			redirect('/login');
		}
	}

	public function success(){
		echo "You're logged :D <br>";
		echo $this->session->all_userdata();
	}

	public function dologout(){
		$this->auth->un_auth();
		redirect('/login');
	}
}