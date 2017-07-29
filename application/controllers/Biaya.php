<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Biaya extends CI_Controller {

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
		$this->load->model('biaya_m');
		$this->load->model('sekolah_m');
		// $this->load->helper('url');
		if (($this->session->username==null) or ($this->session->privilege==null)) {
			redirect(base_url().'login');
		}
	}


	public function add_biaya()
	{
		$this->biaya_m->add_biaya();
		if ($this->db->affected_rows()) {
			$data = array('status' => 'true', 'info' => 'Proses berhasil');
		} else {
			$data = array('status' => 'false', 'info' => 'Proses gagal');
		}
		$this->session->set_flashdata($data);
		redirect(base_url().'v2/main/biaya');
	}


    public function delete_biaya($id)
    {
    	$this->biaya_m->delete_biaya($id);
    	$this->session->set_flashdata('info', 'Proses berhasil');
    	redirect(base_url().'main/biaya');
    }


    public function edit_biaya($id)
    {
		$sekolah_id		= $this->session->sekolah_id;
		$data['biaya']	= $this->biaya_m->edit_pembayaran($id)->row_array();
		$data['jurusan']= $this->sekolah_m->list_jurusan($sekolah_id)->result_array();
		$data['semester'] = $this->sekolah_m->list_semester()->result_array();
		$data['kelas'] 	= $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();
		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('biaya/edit',$data);
		$this->load->view('main/footer');
	}


	public function update_biaya($id)
	{
		$this->biaya_m->update_biaya($id);
		$this->session->set_flashdata('info', 'Proses berhasil');
    	redirect(base_url().'main/biaya');
	}
}
