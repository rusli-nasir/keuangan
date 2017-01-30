<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct(){
		parent::__construct();
		$this->load->model('login_m');
		// $this->load->helper('url');
		// if ( ($this->session->userdata('member_id')==null) or ($this->session->userdata('username')==null) or ($this->session->userdata('first_name')==null)) {
		// 		redirect('login');
		// }
	}


	public function index()
	{
		$data['info']	= $this->session->flashdata('info');
		$this->load->view('main/header');
		$this->load->view('main/navbar_login',$data);
		$this->load->view('main/login');
	}


	public function process_login()
	{
		$check	= $this->login_m->process_login();
		if ($check['result']) {
			$data = array(
				        'username'	=> $check['data']['username'],
				        'privilege' => $check['data']['privilege'],
				        'user_id' 	=> $check['data']['cms_user_id'],
				        'sekolah_id'=> $check['data']['sekolah_id'],
				        'privilege_id' => $check['data']['privilege_id']
				);

			$this->session->set_userdata($data);
			redirect(base_url().'Main');
		}else{
			$this->session->set_flashdata('info', 'Maaf, Aplikasi saat ini sedang dalam penyesuaian Tahun Ajaran Baru (2016-2017), Info : 08118002255');
			redirect(base_url().'login');
		}
	}


	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url().'login');
	}
}
