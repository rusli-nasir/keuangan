<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurusan extends CI_Controller {


	public function __construct(){
		parent::__construct();
		$this->load->model('sekolah_m');
		$this->load->model('Crud_m','crud');
		if (($this->session->username==null) or ($this->session->privilege==null)) {
			redirect(base_url().'login');
		}
	}

	
	public function add_jurusan()
	{
		date_default_timezone_set('Asia/Jakarta');
		$now          = date('Y-m-d H:i:s');
		$nama_jurusan = $this->input->post('nama_jurusan');
		$date_created = $now;
		$date_updated = $now;
		$sekolah_id   = $this->session->sekolah_id;

		$data = array(
			'nama_jurusan' => $nama_jurusan,
			'date_created' => $now,
			'date_updated' => $now,
			'sekolah_id'   => $sekolah_id,
			'status'       => 'show');
		$this->crud->insert($data,'jurusan');

		if ($this->db->affected_rows()) {
   			$data = array('status' => 'true', 'info' => 'Jurusan berhasil ditambah');
   		} else{
   			$data = array('status' => 'false', 'info' => 'Jurusan gagal ditambah');
   		}
   		$this->session->set_flashdata($data);
		redirect(base_url().'v2/main/jurusan');
	}


	public function delete_jurusan($id)
	{
		$this->sekolah_m->delete_jurusan($id);
		$this->session->set_flashdata('info', 'Proses berhasil');
		redirect(base_url().'main/jurusan');
	}


	public function update_jurusan()
	{
		$nama_jurusan = $this->input->post('nama_jurusan');
		$id           = $this->input->post('id');
		$data         = array('nama_jurusan' => $nama_jurusan);
		$condition    = array('id' => $id);
		$this->crud->update($data, 'jurusan', $condition);
		
		if ($this->db->affected_rows()) {
   			$data = array('status' => 'true', 'info' => 'Jurusan berhasil diubah');
   		} else{
   			$data = array('status' => 'false', 'info' => 'Jurusan gagal diubah');
   		}
   		$this->session->set_flashdata($data);
		redirect(base_url().'v2/main/jurusan');
	}

}