<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {


	public function __construct(){
		parent::__construct();
		$this->load->model('login_m');
		$this->load->model('Crud_m', 'crud');
	}


	public function index()
	{
		$data['info'] = $this->session->flashdata('info');
		$this->load->view('v2/page/login', $data);	
	}


	public function process_login()
	{
		$username  = $this->input->post('username');
		$password  = $this->input->post('password');
		$condition = array('username' => $username, 'password' => md5($password));
		$data      = $this->crud->login($condition);
		if ($data->num_rows() > 0) {
			$sess = array(
				'username'     => $data->row()->username, 
				'privilege'    => $data->row()->privilege, 
				'user_id'      => $data->row()->cms_user_id, 
				'sekolah_id'   => $data->row()->sekolah_id, 
				'privilege_id' => $data->row()->privilege_id
				); 
			$this->session->set_userdata($sess);
			redirect(base_url('v2/main'));
		} else {
			$this->session->set_flashdata('info', 'Maaf, Aplikasi saat ini sedang dalam penyesuaian Tahun Ajaran Baru (2016-2017), Info : 08118002255');
			redirect(base_url('v2/login'));
		}
	}


	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('v2/login'));
	}


	public function page404()
	{
		$this->session->sess_destroy();
		$this->load->view('v2/page/404');	
	}
}
