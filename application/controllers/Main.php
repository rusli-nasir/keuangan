<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

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
		$this->load->model('siswa_m');
		$this->load->model('keuangan_m');
		// $this->load->helper('url');
		if (($this->session->username==null) or ($this->session->privilege==null)) {
			redirect(base_url().'login');
		}
	}


	public function index()
	{
		$sekolah_id         = $this->session->sekolah_id;
		$data['kelas']      = $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();
		$data['tahun_ajar'] = $this->keuangan_m->tahun_ajar()->row('tahun_ajar');

		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('main/index');
		$this->load->view('main/footer');
	}


	public function jurusan()
	{
		$sekolah_id			= $this->session->sekolah_id;
		$data['jurusan']	= $this->sekolah_m->list_jurusan($sekolah_id)->result_array();
		$data['info']		= $this->session->flashdata('info');
		$data['kelas'] 		= $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();
		$data['tahun_ajar']		= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');

		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('jurusan/index',$data);
		$this->load->view('main/footer');
	}


	public function biaya()
	{
		$sekolah_id		= $this->session->sekolah_id;
		$data['biaya']	= $this->biaya_m->list_pembayaran($sekolah_id)->result_array();
		$data['jurusan']= $this->sekolah_m->list_jurusan($sekolah_id)->result_array();
		$data['info']	= $this->session->flashdata('info');
		$data['kelas'] 	= $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();
		$data['semester'] = $this->sekolah_m->list_semester()->result_array();
		$data['tahun_ajar']		= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');

		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('biaya/index',$data);
		$this->load->view('main/footer');
	}


	public function kelas()
	{
		$sekolah_id	= $this->session->sekolah_id;
		$data['jurusan']	= $this->sekolah_m->list_jurusan($sekolah_id)->result_array();
		$data['list_kelas']		= $this->sekolah_m->list_kelas($sekolah_id)->result_array();
		$data['jenis_kelas']= $this->sekolah_m->jenis_kelas($sekolah_id)->result_array();
		$data['info']		= $this->session->flashdata('info');
		$data['kelas'] 		= $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();
		$data['tahun_ajar']		= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');

		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('kelas/index',$data);
		$this->load->view('main/footer');
	}


	public function siswa()
	{
		$sekolah_id			= $this->session->sekolah_id;
		$data['siswa']		= $this->siswa_m->list_siswa($sekolah_id)->result_array();
		$data['kelas_siswa']= $this->sekolah_m->kelas_siswa($sekolah_id)->result_array();
		$data['info']		= $this->session->flashdata('info');
		$data['kelas'] 		= $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();
		$data['tahun_ajar']		= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');

		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('siswa/index',$data);
		$this->load->view('main/footer');
	}


	public function harian_in($sekolah_id=null,$now=null)
	{
		date_default_timezone_set("Asia/Jakarta");
		$now = $this->input->get('date');
		if (empty($now)) {
			$now = $this->uri->segment(4);
			if (empty($now)) {
				$now = date('Y-m-d');
			}
		}

		if (empty($sekolah_id)) {
			$sekolah_id	= $this->session->sekolah_id;
		}


		$data['payment']		= $this->keuangan_m->list_harian_in($sekolah_id,$now)->result_array();
		$data['payment_non_spp_table'] = $this->keuangan_m->list_kategori_keuangan_non_spp_table($sekolah_id)->result_array();
		$data['kelas_siswa']	= $this->sekolah_m->kelas_siswa($sekolah_id)->result_array();
		$data['info']			= $this->session->flashdata('info');
		$payment_lainnya		= $this->keuangan_m->list_harian_in_lainnya($sekolah_id,$now);
		$data['payment_lainnya']= $payment_lainnya['sql1']->result_array();
		$data['get_total_in_harian_lainnya'] = $payment_lainnya['sql2']->row('total');
		$data['total_harian']	= $this->keuangan_m->get_total_in_harian($sekolah_id,$now)->row('total_harian') + $data['get_total_in_harian_lainnya'];
		$data['kelas'] 			= $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();
		$data['tahun_ajar']		= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		$data['now']			= $now;
		$data['nama_sekolah']	= $this->sekolah_m->get_nama_sekolah($sekolah_id)->row('nama_sekolah');
		$data['list_penerimaan_lainnya']	= $this->keuangan_m->list_penerimaan_lainnya()->result_array();

		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('keuangan/harian_in',$data);
		$this->load->view('main/footer');
	}


	public function harian_in_yayasan($sekolah_id=null)
	{
		if (empty($sekolah_id)) {
			$sekolah_id			= $this->session->sekolah_id;
		}

		date_default_timezone_set("Asia/Jakarta");
		$now = $this->input->get('date');
		if (empty($now)) {
			$now = $this->uri->segment(4);
			if (empty($now)) {
				$now = date('Y-m-d');
			}
		}

		$data['kelas']				= $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();
		$data['tahun_ajar']			= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		$payment_lainnya			= $this->keuangan_m->list_harian_in_lainnya($sekolah_id,$now);
		$data['payment_lainnya']	= $payment_lainnya['sql1']->result_array();
		$data['info']				= $this->session->flashdata('info');
		$data['get_total_in_harian_lainnya'] = $payment_lainnya['sql2']->row('total');
		$data['now']				= $now;

		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('keuangan/harian_in_yayasan',$data);
		$this->load->view('main/footer');
	}


	public function bulanan_in($kelas_sekolah_id)
	{
		$sekolah_id				= $this->session->sekolah_id;
		$data['payment']		= $this->keuangan_m->list_bulanan_in($kelas_sekolah_id)->result_array();
		$data['payment_non_spp_table'] = $this->keuangan_m->list_kategori_keuangan_non_spp_table($sekolah_id)->result_array();
		$data['kelas_siswa']	= $this->sekolah_m->kelas_siswa($sekolah_id)->result_array();
		$data['info']			= $this->session->flashdata('info');
		$data['total_bulanan']	= $this->keuangan_m->get_total_in_bulanan($kelas_sekolah_id)->row('total_bulanan');
		$data['kelas']			= $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();
		$data['tahun_ajar']		= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');


		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('keuangan/bulanan_in',$data);
		$this->load->view('main/footer');
	}


	public function harian_out($sekolah_id=null,$now=null)
	{
		date_default_timezone_set("Asia/Jakarta");
		if (empty($sekolah_id)) {
			$sekolah_id			= $this->session->sekolah_id;
		}

		$now = $this->input->get('date');
		if (empty($now)) {
			$now = $this->uri->segment(4);
			if (empty($now)) {
				$now = date('Y-m-d');
			}
		}

		$data['kelas']				= $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();
		$data['tahun_ajar']			= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		$data['list_harian_out']	= $this->keuangan_m->list_harian_out($sekolah_id,$now)->result_array();
		$data['list_pengeluaran']	= $this->keuangan_m->list_pengeluaran()->result_array();
		$data['info']				= $this->session->flashdata('info');
		$data['total_harian_out']	= $this->keuangan_m->get_total_out_harian($sekolah_id,$now)->row('total_harian_out');
		$data['now']				= $now;
		$data['nama_sekolah']		= $this->sekolah_m->get_nama_sekolah($sekolah_id)->row('nama_sekolah');

		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('keuangan/harian_out',$data);
		$this->load->view('main/footer');
	}


	public function harian_out_yayasan($sekolah_id=null,$now=null)
	{
		date_default_timezone_set("Asia/Jakarta");
		if (empty($sekolah_id)) {
			$sekolah_id			= $this->session->sekolah_id;
		}

		$now = $this->input->post('date');
		if (empty($now)) {
			$now = date('Y-m-d');
		}

		$data['kelas']				= $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();
		$data['tahun_ajar']			= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		$data['list_harian_out_yayasan']= $this->keuangan_m->list_harian_out_yayasan($now)->result_array();
		$data['now']				= $now;
		$data['list_pengeluaran']	= $this->keuangan_m->list_pengeluaran()->result_array();
		$data['info']				= $this->session->flashdata('info');
		$data['total_harian_out_yayasan']	= $this->keuangan_m->get_total_out_harian($sekolah_id,$now)->row('total_harian_out');
		$data['sekolah_id']			= $this->session->sekolah_id;

		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('keuangan/harian_out_yayasan',$data);
		$this->load->view('main/footer');
	}


	public function bulanan_out($now=null)
	{
		if (empty($now)) {
			$now = $this->input->post('tahun').'-'.$this->input->post('bulan');
			$data['now']		= $now;
		}

		$sekolah_id				= $this->session->sekolah_id;
		$data['list_bulanan_out']= $this->keuangan_m->list_bulanan_out($now)->result_array();
		$data['info']			= $this->session->flashdata('info');
		$data['total_bulanan_out']= $this->keuangan_m->get_total_out_bulanan($now)->row('total_bulanan_out');
		$data['kelas']			= $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();
		$data['tahun_ajar']		= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		$data['sekolah_id']		= $this->session->sekolah_id;
		$data['list_bulan']		= $this->keuangan_m->list_bulan()->result_array();

		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('keuangan/bulanan_out',$data);
		$this->load->view('main/footer');
	}


	public function bulanan_in_yayasan($now=null)
	{
		if (empty($now)) {
			$now = $this->input->post('tahun').'-'.$this->input->post('bulan');
			if ($now =='-') {
				$now 			= date('Y-m');
			}

				$data['now']	= $now;
		}

		$sekolah_id				= $this->session->sekolah_id;
		$data['tahun_ajar']		= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		$payment_lainnya		= $this->keuangan_m->list_harian_in_lainnya($sekolah_id,$now);
		$data['list_bulanan_in']= $payment_lainnya['sql1']->result_array();
		$data['total_bulanan_in']= $payment_lainnya['sql2']->row('total');
		$data['info']			= $this->session->flashdata('info');
		$data['list_bulan']		= $this->keuangan_m->list_bulan()->result_array();

		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('keuangan/bulanan_in_yayasan',$data);
		$this->load->view('main/footer');
	}


	public function rekap_harian_yayasan()
	{
		date_default_timezone_set("Asia/Jakarta");
		$now = $this->input->get('date');
		if (empty($now)) {
			$now = date('Y-m-d');
		}


		$data['kelas']			= $this->sekolah_m->get_kelas_for_submenu($sekolah_id=null)->result_array();
		$data['tahun_ajar']		= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		$data['list_bulan']		= $this->keuangan_m->list_bulan()->result_array();
		$data['list_sekolah']	= $this->sekolah_m->list_sekolah()->result_array();
		$data['now']			= $now;

		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('keuangan/rekap_harian_yayasan');
		$this->load->view('main/footer');
	}


	public function rekap_bulanan_yayasan($tahun_ajar=null)
	{
		$sekolah_id 			= $this->session->sekolah_id;
		$data['kelas']			= $this->sekolah_m->get_kelas_for_submenu($sekolah_id=null)->result_array();
		$data['list_bulan']		= $this->keuangan_m->list_bulan()->result_array();
		$data['list_sekolah']	= $this->sekolah_m->list_sekolah()->result_array();
		if (!empty($tahun_ajar)) {
			$data['tahun_ajar']		=	$tahun_ajar;
		}else{
			$data['tahun_ajar']		= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		}

		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('keuangan/rekap_bulanan_yayasan');
		$this->load->view('main/footer');
	}


	public function kas_akhir_tahun($sekolah_id=null)
	{
		date_default_timezone_set("Asia/Jakarta");

		if (empty($sekolah_id)) {
			$sekolah_id	= $this->session->sekolah_id;
		}


		$data['kelas_siswa']	= $this->sekolah_m->kelas_siswa($sekolah_id)->result_array();
		$data['info']			= $this->session->flashdata('info');
		$payment_lainnya		= $this->keuangan_m->list_kas_akhir_tahun($sekolah_id);
		$data['payment_lainnya']= $payment_lainnya['sql1']->result_array();
		$data['get_total_in_harian_lainnya'] = $payment_lainnya['sql2']->row('total');
		$data['kelas'] 			= $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();
		$data['tahun_ajar']		= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		$data['nama_sekolah']	= $this->sekolah_m->get_nama_sekolah($sekolah_id)->row('nama_sekolah');

		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('keuangan/kas_akhir_tahun',$data);
		$this->load->view('main/footer');
	}


	public function permintaan($month=null,$sekolah_id=null)
	{
		if (empty($sekolah_id)) {
			$sekolah_id	= $this->session->sekolah_id;
		}

		if (empty($month)) {
			$bulan = $this->input->post('bulan');
			$tahun = $this->input->post('tahun');
			if (empty($bulan)) {
				$month = date('Y-m');
			}else{
				$month = $tahun.'-'.$bulan;
			}
		}

		if ($this->session->privilege_id =='1') {
			$permintaan				= $this->keuangan_m->list_permintaan($month,$sekolah_id);
		}else{
			$permintaan				= $this->keuangan_m->list_permintaan_yayasan($month);
		}

		$data['list_permintaan'] 	= $permintaan['list_permintaan']->result_array();
		$data['total_permintaan']	= $permintaan['total_permintaan']->row('total_permintaan');
		$data['nama_sekolah']		= $this->sekolah_m->get_nama_sekolah($sekolah_id)->row('nama_sekolah');
		$data['kelas'] 				= $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();
		$data['list_bulan']			= $this->keuangan_m->list_bulan()->result_array();
		$data['info']				= $this->session->flashdata('info');
		$data['month']				= $month;
		$data['tahun_ajar']			= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');

		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('keuangan/permintaan',$data);
		$this->load->view('main/footer');
	}


	public function komite()
	{
		$sekolah_id 					= $this->session->sekolah_id;
		$data['list_pembayaran_komite']	= $this->keuangan_m->list_pembayaran_komite()->result_array();
		$data['kelas'] 					= $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();

		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('keuangan/komite',$data);
		$this->load->view('main/footer');
	}

	public function time()
    {
        $now        = now();
        $timestamp  = '1140153693';
        $timezone   = 'UP7';
        $daylight_saving = FALSE;
        $ax         = gmt_to_local($now, $timezone, $daylight_saving);
        $sekarang   = unix_to_human($ax,true,'eu');
       	echo $sekarang;
    }


    public function testing($nama_kategori)
    {
    	$data		= $this->keuangan_m->get_payment_annualy($nama_kategori);
    	echo $this->db->last_query();
    	echo $data;
    }
}
