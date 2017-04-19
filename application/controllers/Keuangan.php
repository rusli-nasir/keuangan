<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keuangan extends CI_Controller {

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
		$this->load->model('keuangan_m');
		$this->load->model('print_m');
		$this->load->model('crud_m', 'crud');
		$this->load->library('export');
		// $this->load->helper('url');
		if (($this->session->username==null) or ($this->session->privilege==null)) {
			redirect(base_url().'login');
		}
	}


	public function send_sms($data)
	{
		$msisdn   = $data['userID'];
		$message  = urlencode($data['message']);
		$division = urlencode('Product Ngobrol');
		$url      = "http://sms-api.jatismobile.com/index.ashx?userid=Ngobrol&password=Ngobrol123&msisdn=".$msisdn."&message=".$message."&sender=Ngobrol&division=".$division."&batchname=default&uploadby=default&channel=2";
		$hasil    =file_get_contents($url);

		if(empty($hasil)){
			$hasil = $this->hit_url($url);
		}

		$data['respon']      = $hasil;
		$data['dateCreated'] = date('Y-m-d H:i:s');
		$this->db->insert('log_sms', $data);
		return $hasil;
	}


	public function payment_in()
	{
		$check_payment = $this->keuangan_m->check_payment();

		if ($check_payment) {
			$this->keuangan_m->add_payment_in();
			// $siswa_id = $this->input->post('siswa_id');
			// $msisdn   = $this->siswa_m->getMsisdn($siswa_id);
			// $sms      = array(
			// 	'userID'  => $msisdn,
			// 	'message' => 'Berhasil'
			// 	);
			
			// if ($msisdn) {
			// 	$this->send_sms($sms);
			// }
			$data = array('status' => 'true', 'info' => 'Pembayaran Diterima');
			$this->session->set_flashdata($data);
		} else {
			$data = array('status' => 'false', 'info' => 'Pembayaran berlebih, mohon ulangi dan periksa kembali');
			$this->session->set_flashdata($data);
		}
		redirect(base_url('v2/Main/harian_in'));
	}


	public function payment_in_lainnya()
	{
		$kategori_keuangan_id = $this->input->post('kategori_keuangan_id');
		$this->keuangan_m->add_payment_in_lainnya();
		if ($this->db->affected_rows()) {
			$data = array('status' => 'true', 'info' => 'Proses berhasil');
		} else {
			$data = array('status' => 'false', 'info' => 'Proses gagal');
		}
		$this->session->set_flashdata($data);
		// redirect(base_url().'v2/main/harian_out');

		if ($this->session->privilege_id == '2') {
			redirect(base_url().'v2/main/harian_in_yayasan');
		}else{
			if ($kategori_keuangan_id!='3') {
				redirect(base_url('v2/Main/harian_in'));
			}else{
				redirect(base_url().'v2/main/kas_akhir_tahun');
			}
		}
	}


	public function payment_in_yayasan()
	{
		$this->keuangan_m->add_payment_in_yayasan();
		$this->session->set_flashdata('info', 'Proses berhasil');
		redirect(base_url().'main/harian_in');
	}


	public function payment_out()
	{
		$this->keuangan_m->add_payment_out();
		if ($this->db->affected_rows()) {
			$data = array('status' => 'true', 'info' => 'Proses berhasil');
		} else {
			$data = array('status' => 'false', 'info' => 'Proses gagal');
		}
		$this->session->set_flashdata($data);
		redirect(base_url().'v2/main/harian_out');
	}


    public function get_payment_out($payment_id,$keterangan=null)
   {
        $sekolah_id     = $this->session->sekolah_id;
        date_default_timezone_set("Asia/Jakarta");
        if (empty($sekolah_id)) {
            $sekolah_id         = $this->session->sekolah_id;
        }

        $now = $this->input->post('date');
        if (empty($now)) {
            $now = date('Y-m-d');
        }

        $data['kelas']         = $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();
        $data['tahun_ajar']    = $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
        $data['row_edit']      = $this->keuangan_m->get_list_harian_out($payment_id)->row_array();
        $data['info']          = $this->session->flashdata('info');
        $data['sekolah_id']    = $this->session->sekolah_id;
        $data['keterangan']    = $keterangan;

        $this->load->view('main/header');
        $this->load->view('main/navbar');
        $this->load->view('main/subnavbar',$data);
        $this->load->view('keuangan/edit_harian_out_yayasan',$data);
        $this->load->view('main/footer');
   }


	public function edit_payment_out()
	{
		$this->keuangan_m->edit_payment_out();
		
		if ($this->db->affected_rows()) {
			$data = array('status' => 'true', 'info' => 'Proses berhasil');
		} else {
			$data = array('status' => 'false', 'info' => 'Proses gagal');
		}
		$this->session->set_flashdata($data);

		// if ($this->session->privilege_id == '2') {
        	$opsi_bulan  = $this->input->post('opsi_bulan');
            if ($opsi_bulan=='bln') {
                 redirect(base_url().'v2/main/bulanan_out/'.date('Y-m'));
            } else {
                 redirect(base_url().'v2/main/harian_out');
            }

	}


	public function edit_payment_yayasan_out()
	{
		$this->keuangan_m->edit_payment_yayasan_out();
		$this->session->set_flashdata('info', 'Proses berhasil');
		redirect(base_url().'main/harian_out');
	}


	public function payment_yayasan_out()
	{
		$this->keuangan_m->add_payment_yayasan_out();
		if ($this->db->affected_rows()) {
			$data = array('status' => 'true', 'info' => 'Proses berhasil');
		} else {
			$data = array('status' => 'false', 'info' => 'Proses gagal');
		}
		$this->session->set_flashdata($data);

		redirect(base_url().'v2/main/harian_out_yayasan');
	}

	public function delete_payment_out($id,$keterangan=null)
	{
        $this->crud->delete('payment_out', array('id' => $id));
        if ($this->db->affected_rows()) {
			$data = array('status' => 'true', 'info' => 'Proses berhasil');
		} else {
			$data = array('status' => 'false', 'info' => 'Proses gagal');
		}
		$this->session->set_flashdata($data);

		// if ($this->session->privilege_id == '2' || $this->session->privilege_id == '1') {
            if ($keterangan) {
                redirect(base_url().'v2/main/bulanan_out/'.$keterangan);
            } else {
            	if ($this->session->privilege_id == '2') {
            		redirect(base_url().'v2/main/harian_out_yayasan');
            	} else {
                	redirect(base_url().'v2/main/harian_out');
            	}
            }

		// }else{
		// 	redirect(base_url().'v2/main/harian_out');
		// }
	}

	public function get_payment_category($siswa_id) {
		$data		= $this->siswa_m->get_jurusan_tm_gender($siswa_id)->row_array();
        $tmp      = '';
        $data     = $this->keuangan_m->get_payment_category($data);
        // echo $this->db->last_query();
        // exit();
       	$check = substr('Mid', 0,3);
        if(!empty($data)){
            $tmp .=    "<option value=''>Pilih Pembayaran</option>";
            foreach($data as $row) {
                $tmp .= "<option value='".$row->id."'>".$row->nama_kategori." (Rp.".number_format($row->biaya, 0 , '' , '.' ).")</option>";
            }
        } else {
            $tmp .=    "<option value=''>Pilih Pembayaran</option>";
        }
        die($tmp);
    }


    public function get_payment_annualy($kategori_keuangan_id) {
		$nama_kategori = $this->keuangan_m->get_nama_kategori_keuangan($kategori_keuangan_id)->row('nama_kategori');
        $tmp      = '';

        $data     = $this->keuangan_m->get_payment_annualy($nama_kategori);
        if(!empty($data)){
            $tmp .=    "<option value=''>Pilih Pembayaran</option>";
            foreach($data as $row) {
                $tmp .= "<option value='".$row->id."'>".$row->annualy."</option>";
            }
        } else {
            $tmp .=    "<option value='-'>-</option>";
        }
        die($tmp);
    }


   public function get_payment_id($payment_id)
   {
		$sekolah_id   = $this->session->sekolah_id;
		$edit         = $this->keuangan_m->get_payment_id($payment_id)->row_array();
		$kelas        = $this->kelas($sekolah_id);
		$kelasJurusan = $this->crud->get_kelas_jurusan(array('jurusan.sekolah_id' => $sekolah_id))->result_array();
   		
		$datas = array('kelasJurusan' => $kelasJurusan);

		$data = array(
			'page'    => 'v2/page/edit_harian_in',
			'menu'    => 'Edit Pembayaran',
			'submenu' => $kelas,
			'data'    => $datas,
			'edit'    => $edit
			);
		$this->parser->parse('v2/lte', $data);
   }


   public function get_payment_id_lainnya($payment_id,$keterangan=null)
   {
		$sekolah_id   = $this->session->sekolah_id;
   		$list_penerimaan_lainnya = $this->keuangan_m->list_penerimaan_lainnya()->result_array();
		$edit         = $this->keuangan_m->get_payment_id_lainnya($payment_id)->row_array();
		$kelas        = $this->kelas($sekolah_id);
		$kelasJurusan = $this->crud->get_kelas_jurusan(array('jurusan.sekolah_id' => $sekolah_id))->result_array();
   		
		$datas = array('kelasJurusan' => $kelasJurusan);

		$data = array(
			'page'                    => 'v2/page/edit_harian_in_lainnya',
			'menu'                    => 'Edit Pembayaran',
			'submenu'                 => $kelas,
			'data'                    => $datas,
			'edit'                    => $edit,
			'keterangan'              => $keterangan,
			'list_penerimaan_lainnya' => $list_penerimaan_lainnya
			);
		$this->parser->parse('v2/lte', $data);

   }


   public function edit_payment_in()
   {
   		$check_payment = $this->keuangan_m->check_payment();
   		// var_dump($check_payment);
   		// exit();
   		// $check_payment = $this->keuangan_m->check_edit_payment();
		if ($check_payment) {
			$this->keuangan_m->edit_payment_in();
			$data = array('status' => 'true', 'info' => 'Pembayaran berhasil diubah');
		} else {
			$data = array('status' => 'false', 'info' => 'Pembayaran berlebih, mohon ulangi dan periksa kembali');
		}
		$this->session->set_flashdata($data);
		redirect(base_url('v2/main/harian_in'));
   }


   public function edit_payment_in_lainnya()
   {
   		date_default_timezone_set('Asia/Jakarta');
		$kategori_keuangan_id = $this->input->post('kategori_keuangan_id');
		$opsi_bulan           = $this->input->post('opsi_bulan');
		$amount               = $this->input->post('amount');
		$keterangan           = $this->input->post('keterangan');
		$date_entry3          = $this->input->post('date_entry');
		$id                   = $this->input->post('id');
		$cms_user_id          = $this->session->user_id;
		$sekolah_id           = $this->session->sekolah_id;
		$now                  = date('Y-m-d H:i:s');

		if (!$date_entry3) {
			$date_entry = $now;
		}else{
			$date_entry2= explode('-', $date_entry3);
			$date_entry = $date_entry2['2'].'-'.$date_entry2['1'].'-'.$date_entry2['0'];
		}

		$data = array(
		        'kategori_keuangan_lainnya_id'	=> $kategori_keuangan_id,
		        'amount'		=> $amount,
		        'cms_user_id'	=> $cms_user_id,
		        'sekolah_id'	=> $sekolah_id,
		        'keterangan'	=> $keterangan,
		        'date_created'	=> $date_entry
		);
		$this->crud->update($data, 'payment_lainnya', array('id' => $id));

       	if ($this->db->affected_rows()) {
   			$data = array('status' => 'true', 'info' => 'Penerimaan berhasil diubah');
   		} else{
   			$data = array('status' => 'false', 'info' => 'Penerimaan gagal diubah');
   		}
   		$this->session->set_flashdata($data);
		
		if ($this->session->privilege_id == '2') {
            if ($opsi_bulan=='bln') {
                 redirect(base_url().'v2/main/bulanan_in_yayasan');
            } else {
			     redirect(base_url().'v2/main/harian_in_yayasan');
            }

		}else{
			if ($kategori_keuangan_id!='3') {
				redirect(base_url().'v2/main/harian_in');
			}else{
				redirect(base_url().'v2/main/kas_akhir_tahun');
			}
		}
   }


   public function delete_harian_in($payment_id)
   {
   		$this->keuangan_m->delete_harian_in($payment_id);
   		if ($this->db->affected_rows()) {
   			$data = array('status' => 'true', 'info' => 'Proses Berhasil');
   		} else{
   			$data = array('status' => 'false', 'info' => 'Proses Gagal');
   		}
   		$this->session->set_flashdata($data);
   		redirect(base_url('v2/main/harian_in'));
   }


   public function delete_harian_in_lainnya($payment_id,$keterangan=null)
   {
		$condition            = array('id' => $payment_id);
		$kategori_keuangan_id = $this->crud->get('payment_lainnya', $condition)->row()->kategori_keuangan_lainnya_id; 
		$this->crud->delete('payment_lainnya', $condition);
   		if ($this->db->affected_rows()) {
   			$data = array('status' => 'true', 'info' => 'Proses Berhasil');
   		} else{
   			$data = array('status' => 'false', 'info' => 'Proses Gagal');
   		}
   		$this->session->set_flashdata($data);

   		if ($this->session->privilege_id == '2') {
            if ($keterangan) {
                 redirect(base_url().'v2/main/bulanan_in_yayasan');
            }else{
			     redirect(base_url().'v2/main/harian_in_yayasan');
            }
		}else{
			if ($kategori_keuangan_id != '3') {
				redirect(base_url().'v2/main/harian_in');
			}else{
				redirect(base_url().'v2/main/kas_akhir_tahun');
			}
		}
   }


   public function delete_permintaan($id)
   {
   		$this->crud->delete('permintaan', array('id' => $id));

   		if ($this->db->affected_rows()) {
   			$data = array('status' => 'true', 'info' => 'Proses Berhasil');
   		} else{
   			$data = array('status' => 'false', 'info' => 'Proses Gagal');
   		}
   		$this->session->set_flashdata($data);

		redirect(base_url().'v2/main/permintaan');
   }


   public function edit_permintaan()
	{
		$this->keuangan_m->edit_permintaan();
		$this->session->set_flashdata('info', 'Proses berhasil');
		redirect(base_url().'main/permintaan');
	}


	public function add_permintaan()
	{
		date_default_timezone_set('Asia/Jakarta');
		$now         = date('Y-m-d H:i:s');
		$keterangan  = $this->input->post('keterangan');
		$amount      = $this->input->post('amount');
		$cms_user_id = $this->session->user_id;
		$sekolah_id  = $this->session->sekolah_id;

		$data = array(
		        'keterangan'	=> $keterangan,
		        'amount'		=> $amount,
		        'date_created'	=> $now,
		        'cms_user_id'	=> $cms_user_id,
		        'sekolah_id'	=> $sekolah_id,
		        'status'		=> 'Terkirim'
		);

		$this->crud->insert($data,'permintaan');
		if ($this->db->affected_rows()) {
   			$data = array('status' => 'true', 'info' => 'Proses Berhasil');
   		} else{
   			$data = array('status' => 'false', 'info' => 'Proses Gagal');
   		}
   		$this->session->set_flashdata($data);

		redirect(base_url().'v2/main/permintaan');
	}


	public function permintaan_diterima($id)
	{
		$data = array(
			'cms_user_id' => $cms_user_id, 
			'status'      => 'Diterima'
		);
		$this->crud->update($data, 'permintaan', array('id' => $id));
		
		if ($this->db->affected_rows()) {
   			$data = array('status' => 'true', 'info' => 'Proses Berhasil');
   		} else{
   			$data = array('status' => 'false', 'info' => 'Proses Gagal');
   		}
   		$this->session->set_flashdata($data);

		redirect(base_url().'v2/main/permintaan');
	}


	public function permintaan_ditolak($id)
	{
		$data = array(
			'cms_user_id' => $cms_user_id, 
			'status'      => 'Ditolak'
		);
		$this->crud->update($data, 'permintaan', array('id' => $id));
		
		if ($this->db->affected_rows()) {
   			$data = array('status' => 'true', 'info' => 'Proses Berhasil');
   		} else{
   			$data = array('status' => 'false', 'info' => 'Proses Gagal');
   		}
   		$this->session->set_flashdata($data);

		redirect(base_url().'v2/main/permintaan');
	}


	public function export_harian_nasional($date=null)
	{
		if(empty($date)){
			$date = date('Y-m-d');
		}
		$sql 						= $this->print_m->harian_nasional($date);
		$nice_date 					= nice_date($date, 'd-m-Y');
		$sekolah_id					= $this->session->sekolah_id;
		$total 						= $this->keuangan_m->get_total_in_harian($sekolah_id,$date)->row('total_harian');
		$payment_lainnya			= $this->keuangan_m->print_harian_lainnya($sekolah_id,$date);
		$data['payment_lainnya']	= $payment_lainnya['sql1'];
		$data['total_lainnya']		= $payment_lainnya['sql2']->row('total_lainnya');

		if ($sql->num_rows() > 0 ) {
			$this->export->to_excel($sql, 'Uang masuk '.$nice_date, $nice_date, $total);
		}

		if ($data['total_lainnya'] > 0) {
			$this->export->to_excel($data['payment_lainnya'], 'Uang masuk lainnya '.$nice_date, $nice_date, $data['total_lainnya']);
		}

		if (($sql->num_rows < 1)and(($data['total_lainnya'] < 1))) {
			redirect(base_url().'main/harian_in/'.$sekolah_id.'/'.$date);
		}
	}


	public function export_bulanan($kelas_sekolah_id)
	{
		$sql 		= $this->print_m->bulanan_sekolah($kelas_sekolah_id);
		$total 		= $this->keuangan_m->get_total_in_bulanan($kelas_sekolah_id)->row('total_bulanan');
		$nama_kelas = $this->sekolah_m->get_kelas_jurusan_group($kelas_sekolah_id)->row('kelas');

		if ($sql->num_rows() > 0 ) {
			$this->export->to_excel($sql, 'Rekap Bulanan '.$nama_kelas, 'Bulanan '.$nama_kelas, $total);
		}

		if ($sql->num_rows < 1) {
			redirect(base_url().'main/bulanan_in/'.$kelas_sekolah_id);
		}
	}


	public function payment_in_perbulan($sekolah_id=null,$month=null)
	{
		if (empty($sekolah_id) and empty($month)) {
			$sekolah_id 	= $this->session->sekolah_id;
			$month 			= $this->input->post('month');
			$year 			= $this->input->post('year');
			$month			= $year.'-'.$month;
			if (empty($year)) {
				$month 		= date('Y-m');
				$year			= date('Y');
			}
		}

		$data['payment']	= $this->print_m->bulanan_yayasan($sekolah_id,$month)->result_array();
		$month_exp	 		= explode('-', $month);
		$bulan_in_code		= $month_exp['1'];
		$data['tahun']		= $year;
		$data['bulan']		= $this->sekolah_m->get_nama_bulan($bulan_in_code)->row('bulan');
		$data['nama_sekolah']= $this->sekolah_m->get_nama_sekolah($sekolah_id)->row('nama_sekolah');
		$data['payment_non_spp_table'] = $this->keuangan_m->list_kategori_keuangan_non_spp_table($sekolah_id)->result_array();
		$data['sekolah_id']	= $sekolah_id;
		$data['month']		= $month;
		$data['tahun_ajar']	= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		$data['kelas']		= $this->sekolah_m->get_kelas_for_submenu($sekolah_id)->result_array();

		$payment_lainnya						= $this->keuangan_m->list_bulanan_in_lainnya($sekolah_id,$month);
		$data['payment_lainnya']				= $payment_lainnya['sql1']->result_array();
		$data['get_total_in_bulanan_lainnya'] 	= $payment_lainnya['sql2']->row('total');
		$data['total']							= $this->keuangan_m->get_total_in_bulanan_yayasan($sekolah_id,$month)->row('Total')+$data['get_total_in_bulanan_lainnya'];
		$data['daftar_bulan']					= $this->sekolah_m->daftar_bulan_ajaran()->result_array();

   		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('keuangan/payment_in_perbulan',$data);
		$this->load->view('main/footer');

	}


	public function export_bulanan_yayasan($sekolah_id,$month)
	{
		$sql 			= $this->print_m->bulanan_yayasan($sekolah_id,$month);
		$total 			= $this->keuangan_m->get_total_in_bulanan_yayasan($sekolah_id,$month)->row('Total');
		$month_exp	 	= explode('-', $month);
		$bulan_in_code	= $month_exp['1'];
		$bulan 			= $this->sekolah_m->get_nama_bulan($bulan_in_code)->row('bulan');
		$nama_sekolah	= $this->sekolah_m->get_nama_sekolah($sekolah_id)->row('nama_sekolah');
		$tahun_ajar 	= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');

		$payment_lainnya			= $this->keuangan_m->print_bulanan_lainnya($sekolah_id,$month);
		$data['payment_lainnya']	= $payment_lainnya['sql1'];
		$data['total_lainnya']		= $payment_lainnya['sql2']->row('total_lainnya');

		if ($sql->num_rows() > 0 ) {
			$this->export->to_excel($sql, 'Rekap Bulan '.$bulan.' '.$nama_sekolah, 'Bulan '.$bulan.' '.$nama_sekolah, $total);
		}

		if ($data['total_lainnya'] > 0) {
			$this->export->to_excel($data['payment_lainnya'], '', $bulan, $data['total_lainnya']);
		}

		if ($sql->num_rows < 1) {
			redirect(base_url().'main/rekap_bulanan_yayasan/'.$tahun_ajar);
		}
	}


	public function payment_out_perbulan($sekolah_id,$month)
	{
		$data['list_bulanan_out']	= $this->keuangan_m->list_bulanan_out_for_yayasan($month,$sekolah_id)->result_array();
		$data['total_bulanan_out']	= $this->keuangan_m->get_total_out_bulanan_for_yayasan($month,$sekolah_id)->row('total_bulanan_out');
		$data['tahun_ajar']			= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		$data['sekolah_id']			= $sekolah_id;
		$data['month']				= $month;
		$month_exp	 				= explode('-', $month);
		$bulan_in_code				= $month_exp['1'];
		$data['bulan']				= $this->sekolah_m->get_nama_bulan($bulan_in_code)->row('bulan');
		$data['nama_sekolah']		= $this->sekolah_m->get_nama_sekolah($sekolah_id)->row('nama_sekolah');

		$this->load->view('main/header');
		$this->load->view('main/navbar');
		$this->load->view('main/subnavbar',$data);
		$this->load->view('keuangan/payment_out_perbulan',$data);
		$this->load->view('main/footer');
	}


	public function export_bulanan_out_yayasan($sekolah_id,$month)
	{
		$sql 			= $this->print_m->list_bulanan_out_for_yayasan($month,$sekolah_id);
		$total 			= $this->keuangan_m->get_total_out_bulanan_for_yayasan($month,$sekolah_id)->row('total_bulanan_out');
		$month_exp	 	= explode('-', $month);
		$bulan_in_code	= $month_exp['1'];
		$bulan 			= $this->sekolah_m->get_nama_bulan($bulan_in_code)->row('bulan');
		$nama_sekolah	= $this->sekolah_m->get_nama_sekolah($sekolah_id)->row('nama_sekolah');
		$tahun_ajar 	= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');

		if ($sql->num_rows() > 0 ) {
			$this->export->to_excel($sql, 'Rekap Pengeluaran Bulan '.$bulan.' '.$nama_sekolah, 'Bulan '.$bulan.' '.$nama_sekolah, $total, 'Uang Keluar');
		}
		if ($sql->num_rows < 1) {
			redirect(base_url().'main/rekap_bulanan_yayasan/'.$tahun_ajar);
		}
	}


	public function kelas($sekolah_id)
	{
		$getKelas = $this->crud->get_kelas($sekolah_id); 
		$kelas    = array();
		
		if ($getKelas->num_rows() > 0) {
			
			foreach ($getKelas->result_array() as $key) {
				$kelas2    = array();
				$condition = array('kelas.id' => $key['id'], 'jurusan.sekolah_id' => $sekolah_id, 'kelas_sekolah.status' => 'show');
				$jurusan   = $this->crud->get_kelas_jurusan($condition, 'sidebar')->result_array();
				
				foreach ($jurusan as $key2) {
					$tmp = array('KELAS_JURUSAN' => $key2['kelas'], 'KELAS_SEKOLAH_ID' => $key2['id']);
					array_push($kelas2, $tmp);
				}
				$tmp2 = array('KELAS' => $key['kelas'], 'DATA' => $kelas2);
				array_push($kelas, $tmp2);
			}
		}

		return $kelas;
	}

}
