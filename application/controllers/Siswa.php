<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siswa extends CI_Controller {

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
		$this->load->model('siswa_m');
		$this->load->model('Crud_m','crud');
		// $this->load->helper('url');
		if (($this->session->username==null) or ($this->session->privilege==null)) {
			redirect(base_url().'login');
		}
	}

	
	public function add_siswa()
	{
		date_default_timezone_set('Asia/Jakarta');
		$now         = date('Y-m-d H:i:s');
		$nis         = $this->input->post('nis');
		$nama_siswa  = $this->input->post('nama_siswa');
		$kelas_id    = $this->input->post('kelas');
		$gender      = $this->input->post('gender');
		$tahun_masuk = $this->input->post('tahun_masuk');
		$cms_user_id = $this->session->user_id;

		$data = array(
				'nis'              => $nis,
				'nama_siswa'       => $nama_siswa,
				'kelas_sekolah_id' => $kelas_id,
				'status'           => 'aktif',
				'cms_user_id'      => $cms_user_id,
				'date_created'     => $now,
				'flag'             => 'show',
				'gender'           => $gender,
				'tahun_masuk'      => $tahun_masuk
		);

		$this->crud->insert($data,'siswa');

		if ($this->db->affected_rows()) {
   			$data = array('status' => 'true', 'info' => 'Siswa berhasil ditambah');
   		} else{
   			$data = array('status' => 'false', 'info' => 'Siswa gagal ditambah');
   		}
   		$this->session->set_flashdata($data);
   		redirect(base_url().'v2/main/siswa');
	}


	public function edit_siswa($id)
	{
		$sekolah_id			= $this->session->sekolah_id;
		$data['siswa']		= $this->siswa_m->edit_siswa($id)->row_array();
		$data['kelas_siswa']= $this->sekolah_m->kelas_siswa($sekolah_id)->result_array();
		$data['kelas']		= $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();
		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('siswa/edit',$data);
		$this->load->view('main/footer');
	}


	public function delete_siswa($id)
	{
		$this->siswa_m->delete_siswa($id);
		$this->session->set_flashdata('info', 'Proses berhasil');
		redirect(base_url().'main/siswa');
	}


	public function update_siswa($id)
	{
		$this->siswa_m->update_siswa($id);
		$this->session->set_flashdata('info', 'Proses berhasil');
		redirect(base_url().'main/siswa');
	}


	public function get_siswa($kelas_sekolah_id) {
        $tmp      = '';
        $data     = $this->siswa_m->get_siswa($kelas_sekolah_id);
        if($data){
            $tmp .=    "<option value=''>Pilih Siswa</option>";    
            foreach($data as $row) {    
                $tmp .= "<option value='".$row->id."'>".$row->nama_siswa."</option>";
            }
        } else {
            $tmp .=    "<option value=''>Pilih Siswa</option>";    
        }
        die($tmp);
    }

}