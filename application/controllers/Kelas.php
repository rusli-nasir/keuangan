<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas extends CI_Controller {

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
		$this->load->model('sekolah_m');
		$this->load->model('Crud_m','crud');
		// $this->load->helper('url');
		if (($this->session->username==null) or ($this->session->privilege==null)) {
			redirect(base_url().'login');
		}
	}

	
	public function add_kelas()
	{
		date_default_timezone_set('Asia/Jakarta');

		$now         = date('Y-m-d H:i:s');
		$kelas       = $this->input->post('kelas');
		$jurusan     = $this->input->post('jurusan');
		$group       = $this->input->post('group');
		$cms_user_id = $this->session->user_id;

		$data = array(
		        'kelas_id' 		=> $kelas,
		        'jurusan_id'	=> $jurusan,
		        'cms_user_id'	=> $cms_user_id,
		        'group'			=> $group,
		        'date_created' 	=> $now,
		        'status'		=> 'show'
		);

		$this->crud->insert($data,'kelas_sekolah');
		
		if ($this->db->affected_rows()) {
			$data = array('status' => 'true', 'info' => 'Proses berhasil');
		} else {
			$data = array('status' => 'false', 'info' => 'Proses gagal');
		}
		$this->session->set_flashdata($data);
		redirect(base_url().'v2/main/listkelas');
	}


	public function edit_kelas($id)
	{
		$sekolah_id	= $this->session->sekolah_id;
		$data['jurusan']	= $this->sekolah_m->list_jurusan($sekolah_id)->result_array();
		$data['kelas']		= $this->sekolah_m->list_kelas($sekolah_id)->result_array();
		$data['jenis_kelas']= $this->sekolah_m->jenis_kelas($sekolah_id)->result_array();
		$data['data_kelas']	= $this->sekolah_m->edit_kelas($id)->row_array();
		$data['kelas'] 		= $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();
		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('kelas/edit',$data);
		$this->load->view('main/footer');
	}


	public function delete_kelas($id)
	{
		$this->sekolah_m->delete_kelas($id);
		$this->session->set_flashdata('info', 'Proses berhasil');
		redirect(base_url().'main/kelas');
	}


	public function update_kelas($id)
	{
		$this->sekolah_m->update_kelas($id);
		$this->session->set_flashdata('info', 'Proses berhasil');
		redirect(base_url().'main/kelas');
	}

}