<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('biaya_m');
		$this->load->model('sekolah_m');
		$this->load->model('siswa_m');
		$this->load->model('keuangan_m');
		$this->load->model('Crud_m', 'crud');
		$this->load->model('keuangan_m');
		if (!$this->session->username or !$this->session->privilege) {
			redirect(base_url('v2/login'));
		}
	}


	public function index()
	{
		$sekolah_id = $this->session->sekolah_id;
		$kelas      = $this->kelas($sekolah_id);

		$data = array(
			'page'    => 'v2/page/dashboard',
			'menu'    => 'Home',
			'submenu' => $kelas
			);
		$this->parser->parse('v2/lte', $data);
	}


	public function pdf()
	{
		$this->load->library('PdfGenerator');
		$this->load->helper('file');
		$id    = $_REQUEST['id'];
		$times = $_REQUEST['times'];
		$type  = $_REQUEST['type'];
		
		switch ($type) {
			case '1': 
				$data               = $this->harian_in2($id,$times);
				$data['type']       = $type;
				$data['keterangan'] = 'Penerimaan Harian ['.$times.']';
				$size = 'A2';
				break;

			case '2':
				$data               = $this->bulanan_in2($id,$times);
				$data['type']       = $type;
				$data['keterangan'] = 'Penerimaan Bulanan ['.$times.']';
				$size = 'A1';
				break;

			case '3':
				$data               = $this->kelas_in2($id, $times);
				$data['type']       = $type;
				$data['keterangan'] = 'Penerimaan Kelas '.$data['nama_kelas'].' ['.$times.']';
				$size               = 'A1';
				break;

			default:
				# code...
				break;
		}		
		
		$this->pdfgenerator->generate($this->load->view('well', $data, true), 'contoh', $size);
	}


	public function harian_in2($sekolah_id = null, $now = null)
	{
		//error_reporting(0);
		date_default_timezone_set("Asia/Jakarta");

		if (!$now) {
			$now = $this->uri->segment(5);
			if (!$now) {
				$now = date('Y-m-d');
			}
		}

		if (!$sekolah_id) {
			$sekolah_id	= $this->session->sekolah_id;
		}

		$buff    = array();
		$student = $this->keuangan_m->studentPaymentPerday($sekolah_id, $now);

		$condition                = array('nama_kategori != ' => 'SPP', 'sekolah_id' => $sekolah_id);
		$getCategoryPaymentNonSPP = $this->crud->get_category_payment($condition)->result_array();
		$getBulan                 = $this->crud->get('bulan', array())->result_array();
		$tahun_ajar               = $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		$tahun_pelajaran          = explode('-', $tahun_ajar);
		$semester1                = $tahun_pelajaran['0'];
		$semester2                = $tahun_pelajaran['1'];

		foreach ($student->result_array() as $keyStudent) {

			$condition  = array('siswa.id' => $keyStudent['siswa_id']);
			$getStudent = $this->crud->get('siswa', $condition)->row();
			$getKelas   = $this->crud->get_kelas_jurusan_based_student($condition)->row();
			$SPP        = array();

			foreach ($getBulan as $keyBulan) {
				if ($keyBulan['id'] > 6) {
					// TAHUN AJARAN GENAP
					$semester = $semester2;
				} else {
					// TAHUN AJARAN GANJIL
					$semester = $semester1;
				}
				
				$condition = array(
					'kategori_keuangan.nama_kategori' => 'SPP',
					'payment.siswa_id'                => $keyStudent['siswa_id'], 
					'payment.tahun'                   => $semester,
					'payment.bulan_id'                => $keyBulan['id'],
					'payment.flag'                    => 'show',
					'date(payment.date_created)'      => $now);
				$getSPP = $this->crud->get_payment_spp_based_student($condition, 'debt')->result_array();

				$resSPP = array();
				foreach ($getSPP as $keyGetSPP) {
					if ($keyGetSPP['tahun'] != $semester) {
						$debt = true;
					} else{
						$debt = false;
					}

					$tmpSPP2 = array(
						'Amount'  => $keyGetSPP['amount'],
						'Created' => $keyGetSPP['created'],
						'Id'      => $keyGetSPP['id'],
						'Tahun'   => $keyGetSPP['tahun'],
						'Debt'    => $debt);
					array_push($resSPP, $tmpSPP2);
				}
				$SPPtotal = array_sum(array_column($resSPP, 'Amount'));
				$tmpSPP = array(
					'data'   => $resSPP,
					'Bulan'  => $keyBulan['id'],
					'Amount' => $SPPtotal);
				array_push($SPP, $tmpSPP);
			}

			
			$NonSPP       = array();
			$totalNonSPP2 = array();
			foreach ($getCategoryPaymentNonSPP as $keyPaymentNonSPP) {
				$condition = array(
					'kategori_keuangan.nama_kategori' => $keyPaymentNonSPP['nama_kategori'],
					'payment.siswa_id'                => $keyStudent['siswa_id'], 
					'payment.flag'                    => 'show',
					'date(payment.date_created)'      => $now);
				$getPaymentNonSPP = $this->crud->get_payment_spp_based_student($condition);
				
				$condition      = array('siswa.id' => $keyStudent['siswa_id']);
				$getDetailSiswa = $this->crud->get_kelas_jurusan_based_student($condition)->row();
				
				switch ($keyPaymentNonSPP['nama_kategori']) {
					case 'Mid Ganjil':
					case 'Mid Genap':
					case 'Semester Ganjil':
					case 'Semester Genap':
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id);
						# code...
						break;
					
					case 'Komite':
					case 'Perpisahan':
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id,
							'tahun_masuk'   => $getDetailSiswa->tahun_masuk);
						break;
					
					case 'Prakerin':
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id,
							'tahun_masuk'   => $getDetailSiswa->tahun_masuk,
							'jurusan_id'    => $getDetailSiswa->jurusan_id,
							'gender'        => $getDetailSiswa->gender);
						break;

					default:
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id, 
							'tahun_masuk'   => $getDetailSiswa->tahun_masuk, 
							'gender'        => $getDetailSiswa->gender, 
							'jurusan_id'    => $getDetailSiswa->jurusan_id, 
							'semester_id'   => $getDetailSiswa->semester_id);
						break;
				}
				$biaya = $this->crud->get('kategori_keuangan', $condition)->row()->biaya;
				
				$NonSPP2 = array();
				foreach ($getPaymentNonSPP->result_array() as $keyGetPaymentNonSPP) {
					
					$tmpNonSPP2 = array(
						'Amount'  => $keyGetPaymentNonSPP['amount'],
						'Created' => $keyGetPaymentNonSPP['created'],
						'Id'      => $keyGetPaymentNonSPP['id']);
						// 'Q'       => $this->db->last_query());
					array_push($NonSPP2, $tmpNonSPP2);
				}
				
				$totalNonSPP = array_sum(array_column($NonSPP2, 'Amount'));
				$sisa        = $biaya - $totalNonSPP;
				if ($sisa >= 0 or !$biaya) {
					$lunas = '';
				} else if($sisa < 0){
					$sisa  = $totalNonSPP - $biaya;
					$lunas = 'Berlebih '.$sisa;
					$sisa  = 0;
				} else {
					$lunas = 'Lunas '.$biaya;
				}
				
				$tmpNonSPP      = array(
					'Nama_kategori' => $keyPaymentNonSPP['nama_kategori'],
					'Data'          => $NonSPP2,
					'Status'        => $lunas,
					'Sisa'          => $sisa
					);
				array_push($NonSPP, $tmpNonSPP);
				array_push($totalNonSPP2, array('totalNonSPP' => $totalNonSPP));
			}

			$lunas1 = array_sum(array_column($SPP, 'Amount'));
			$lunas2 = array_sum(array_column($totalNonSPP2, 'totalNonSPP'));
			$total = $lunas1 + $lunas2;
			$tmp = array(
				'siswa_id'      => $keyStudent['siswa_id'],
				'NIS'           => $getStudent->nis,
				'Nama'          => $getStudent->nama_siswa,
				'Kelas'         => $getKelas->kelas,
				'SPP'           => $SPP,
				'PaymentNonSPP' => $NonSPP,
				'Total'         => $total);
			array_push($buff, $tmp);
		}
		$condition         = array('sekolah_id' => $sekolah_id, 'date(date_created)' => $now);
		$lainnya           = $this->crud->get('payment_lainnya', $condition);
		$penerimaanLainnya = $lainnya->result_array();
		$totalLainnya      = array_sum(array_column($penerimaanLainnya, 'amount'));
		$totalPenerimaan   = array_sum(array_column($buff, 'Total')) + $totalLainnya;

		$kelas        = $this->kelas($sekolah_id);
		$sekolah      = $this->crud->get('sekolah', array('id' => $sekolah_id))->row()->nama_sekolah;
		$kelasJurusan = $this->crud->get_kelas_jurusan(array('jurusan.sekolah_id' => $sekolah_id, 'kelas_sekolah.status' => 'show'))->result_array();
		$kategoriPenerimaanLainnya = $this->crud->get('kategori_keuangan_lainnya', array())->result_array();
		
		$datas = array(
			'payment'                   => $buff,
			'totalPenerimaan'           => $totalPenerimaan,
			'paymentLainnya'            => $penerimaanLainnya,
			'kategoriPaymentNonSPP'     => $getCategoryPaymentNonSPP,
			'totalLainnya'              => $totalLainnya,
			'tanggal'                   => $now,
			'sekolah'                   => $sekolah,
			'sekolah_id'                => $sekolah_id,
			'bulan'                     => array_column($getBulan, 'bulanAlias'),
			'kelasJurusan'              => $kelasJurusan,
			'kategoriPenerimaanLainnya' => $kategoriPenerimaanLainnya,
			'tahunajaran'               => $tahun_ajar);

		return $datas;
	}


	public function harian_in($sekolah_id = null, $now = null)
	{
		error_reporting(0);
		date_default_timezone_set("Asia/Jakarta");
		$now = $this->input->get('date');

		if (!$now) {
			$now = $this->uri->segment(5);
			if (!$now) {
				$now = date('Y-m-d');
			}
		}

		if (!$sekolah_id) {
			$sekolah_id	= $this->session->sekolah_id;
		}
		
		$buff    = array();
		$student = $this->keuangan_m->studentPaymentPerday($sekolah_id, $now);

		$condition                = array('nama_kategori != ' => 'SPP', 'sekolah_id' => $sekolah_id);
		$getCategoryPaymentNonSPP = $this->crud->get_category_payment($condition)->result_array();
		$getBulan                 = $this->crud->get('bulan', array())->result_array();
		$tahun_ajar               = $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		$tahun_pelajaran          = explode('-', $tahun_ajar);
		$semester1                = $tahun_pelajaran['0'];
		$semester2                = $tahun_pelajaran['1'];

		foreach ($student->result_array() as $keyStudent) {

			$condition  = array('siswa.id' => $keyStudent['siswa_id']);
			$getStudent = $this->crud->get('siswa', $condition)->row();
			$getKelas   = $this->crud->get_kelas_jurusan_based_student($condition)->row();
			$SPP        = array();

			foreach ($getBulan as $keyBulan) {
				if ($keyBulan['id'] > 6) {
					// TAHUN AJARAN GENAP
					$semester = $semester2;
				} else {
					// TAHUN AJARAN GANJIL
					$semester = $semester1;
				}
				
				$condition = array(
					'kategori_keuangan.nama_kategori' => 'SPP',
					'payment.siswa_id'                => $keyStudent['siswa_id'], 
					'payment.tahun'                   => $semester,
					'payment.bulan_id'                => $keyBulan['id'],
					'payment.flag'                    => 'show',
					'date(payment.date_created)'      => $now);
				$getSPP = $this->crud->get_payment_spp_based_student($condition, 'debt')->result_array();

				$resSPP = array();
				foreach ($getSPP as $keyGetSPP) {
					if ($keyGetSPP['tahun'] != $semester) {
						$debt = true;
					} else{
						$debt = false;
					}

					$tmpSPP2 = array(
						'Amount'  => $keyGetSPP['amount'],
						'Created' => $keyGetSPP['created'],
						'Id'      => $keyGetSPP['id'],
						'Tahun'   => $keyGetSPP['tahun'],
						'Debt'    => $debt);
					array_push($resSPP, $tmpSPP2);
				}
				$SPPtotal = array_sum(array_column($resSPP, 'Amount'));
				$tmpSPP = array(
					'data'   => $resSPP,
					'Bulan'  => $keyBulan['id'],
					'Amount' => $SPPtotal);
				array_push($SPP, $tmpSPP);
			}

			
			$NonSPP       = array();
			$totalNonSPP2 = array();
			foreach ($getCategoryPaymentNonSPP as $keyPaymentNonSPP) {
				$condition = array(
					'kategori_keuangan.nama_kategori' => $keyPaymentNonSPP['nama_kategori'],
					'payment.siswa_id'                => $keyStudent['siswa_id'], 
					'payment.flag'                    => 'show',
					'date(payment.date_created)'      => $now);
				$getPaymentNonSPP = $this->crud->get_payment_spp_based_student($condition);
				
				$condition      = array('siswa.id' => $keyStudent['siswa_id']);
				$getDetailSiswa = $this->crud->get_kelas_jurusan_based_student($condition)->row();
				
				switch ($keyPaymentNonSPP['nama_kategori']) {
					case 'Mid Ganjil':
					case 'Mid Genap':
					case 'Semester Ganjil':
					case 'Semester Genap':
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id);
						# code...
						break;
					
					case 'Komite':
					case 'Perpisahan':
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id,
							'tahun_masuk'   => $getDetailSiswa->tahun_masuk);
						break;
					
					case 'Prakerin':
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id,
							'tahun_masuk'   => $getDetailSiswa->tahun_masuk,
							'jurusan_id'    => $getDetailSiswa->jurusan_id,
							'gender'        => $getDetailSiswa->gender);
						break;

					default:
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id, 
							'tahun_masuk'   => $getDetailSiswa->tahun_masuk, 
							'gender'        => $getDetailSiswa->gender, 
							'jurusan_id'    => $getDetailSiswa->jurusan_id, 
							'semester_id'   => $getDetailSiswa->semester_id);
						break;
				}
				$biaya = $this->crud->get('kategori_keuangan', $condition)->row()->biaya;
				
				$NonSPP2 = array();
				foreach ($getPaymentNonSPP->result_array() as $keyGetPaymentNonSPP) {
					
					$tmpNonSPP2 = array(
						'Amount'  => $keyGetPaymentNonSPP['amount'],
						'Created' => $keyGetPaymentNonSPP['created'],
						'Id'      => $keyGetPaymentNonSPP['id']);
						// 'Q'       => $this->db->last_query());
					array_push($NonSPP2, $tmpNonSPP2);
				}
				
				$totalNonSPP = array_sum(array_column($NonSPP2, 'Amount'));
				$sisa        = $biaya - $totalNonSPP;
				if ($sisa >= 0 or !$biaya) {
					$lunas = '';
				} else if($sisa < 0){
					$sisa  = $totalNonSPP - $biaya;
					$lunas = 'Berlebih '.$sisa;
					$sisa  = 0;
				} else {
					$lunas = 'Lunas '.$biaya;
				}
				
				$tmpNonSPP      = array(
					'Nama_kategori' => $keyPaymentNonSPP['nama_kategori'],
					'Data'          => $NonSPP2,
					'Status'        => $lunas,
					'Sisa'          => $sisa
					);
				array_push($NonSPP, $tmpNonSPP);
				array_push($totalNonSPP2, array('totalNonSPP' => $totalNonSPP));
			}

			$lunas1 = array_sum(array_column($SPP, 'Amount'));
			$lunas2 = array_sum(array_column($totalNonSPP2, 'totalNonSPP'));
			$total = $lunas1 + $lunas2;
			$tmp = array(
				'siswa_id'      => $keyStudent['siswa_id'],
				'NIS'           => $getStudent->nis,
				'Nama'          => $getStudent->nama_siswa,
				'Kelas'         => $getKelas->kelas,
				'SPP'           => $SPP,
				'PaymentNonSPP' => $NonSPP,
				'Total'         => $total);
			array_push($buff, $tmp);
		}
		$condition         = array('sekolah_id' => $sekolah_id, 'date(date_created)' => $now);
		$lainnya           = $this->crud->get('payment_lainnya', $condition);
		$penerimaanLainnya = $lainnya->result_array();
		$totalLainnya      = array_sum(array_column($penerimaanLainnya, 'amount'));
		$totalPenerimaan   = array_sum(array_column($buff, 'Total')) + $totalLainnya;

		$kelas        = $this->kelas($sekolah_id);
		$sekolah      = $this->crud->get('sekolah', array('id' => $sekolah_id))->row()->nama_sekolah;
		$kelasJurusan = $this->crud->get_kelas_jurusan(array('jurusan.sekolah_id' => $sekolah_id, 'kelas_sekolah.status' => 'show'))->result_array();
		$kategoriPenerimaanLainnya = $this->crud->get('kategori_keuangan_lainnya', array('flag' => 'SHOW'))->result_array();
		
		$datas = array(
			'payment'                   => $buff,
			'totalPenerimaan'           => $totalPenerimaan,
			'paymentLainnya'            => $penerimaanLainnya,
			'kategoriPaymentNonSPP'     => $getCategoryPaymentNonSPP,
			'totalLainnya'              => $totalLainnya,
			'tanggal'                   => $now,
			'sekolah'                   => $sekolah,
			'sekolah_id'                => $sekolah_id,
			'bulan'                     => array_column($getBulan, 'bulanAlias'),
			'kelasJurusan'              => $kelasJurusan,
			'kategoriPenerimaanLainnya' => $kategoriPenerimaanLainnya);

		$data = array(
			'page'        => 'v2/page/harian_in',
			'menu'        => 'Harian',
			'submenu'     => $kelas,
			'data'        => $datas,
			'tahunAjaran' => $tahun_ajar
			);

		$this->parser->parse('v2/lte', $data);
	}


	public function bulanan_in($sekolah_id = null, $month = null)
	{
		error_reporting(0);
		date_default_timezone_set("Asia/Jakarta");

		if (!$sekolah_id and !$month) {
			$sekolah_id = $this->session->sekolah_id;
			$month      = $this->input->post('month');
			$year       = $this->input->post('year');
			$month      = $year.'-'.$month;
			if (!$year) {
				$month = date('Y-m');
			}
		} else if($this->uri->segment('5')){
			$month = $this->uri->segment('5');
		} else {
			$month = $_GET['date'];
		}

		$buff    = array();
		$student = $this->keuangan_m->studentPaymentPermonth($sekolah_id, $month);

		$condition                = array('nama_kategori != ' => 'SPP', 'sekolah_id' => $sekolah_id);
		$getCategoryPaymentNonSPP = $this->crud->get_category_payment($condition)->result_array();
		$getBulan                 = $this->crud->get('bulan', array())->result_array();
		$tahun_ajar               = $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		$tahun_pelajaran          = explode('-', $tahun_ajar);
		$semester1                = $tahun_pelajaran['0'];
		$semester2                = $tahun_pelajaran['1'];

		foreach ($student->result_array() as $keyStudent) {

			$condition  = array('siswa.id' => $keyStudent['siswa_id']);
			$getStudent = $this->crud->get('siswa', $condition)->row();
			$getKelas   = $this->crud->get_kelas_jurusan_based_student($condition)->row();
			$SPP        = array();

			foreach ($getBulan as $keyBulan) {
				if ($keyBulan['id'] > 6) {
					// TAHUN AJARAN GENAP
					$semester = $semester2;
				} else {
					// TAHUN AJARAN GANJIL
					$semester = $semester1;
				}
				
				$condition = array(
					'kategori_keuangan.nama_kategori' => 'SPP',
					'payment.siswa_id'                => $keyStudent['siswa_id'], 
					'payment.tahun'                   => $semester,
					'payment.bulan_id'                => $keyBulan['id'],
					'payment.flag'                    => 'show',
					'date(payment.date_created)'      => $month);
				$getSPP = $this->crud->get_payment_spp_based_student($condition, 'monthly')->result_array();

				$resSPP = array();
				foreach ($getSPP as $keyGetSPP) {

					$tmpSPP2 = array(
						'Amount'  => $keyGetSPP['amount'],
						'Created' => $keyGetSPP['created'],
						'Id'      => $keyGetSPP['id']);
					array_push($resSPP, $tmpSPP2);
				}
				$SPPtotal = array_sum(array_column($resSPP, 'Amount'));
				$tmpSPP = array(
					'data'   => $resSPP,
					'Bulan'  => $keyBulan['id'],
					'Amount' => $SPPtotal);
				array_push($SPP, $tmpSPP);
			}
			
			$NonSPP       = array();
			$totalNonSPP2 = array();
			foreach ($getCategoryPaymentNonSPP as $keyPaymentNonSPP) {
				$condition = array(
					'kategori_keuangan.nama_kategori' => $keyPaymentNonSPP['nama_kategori'],
					'payment.siswa_id'                => $keyStudent['siswa_id'], 
					'payment.flag'                    => 'show',
					'date(payment.date_created)'      => $month);
				$getPaymentNonSPP = $this->crud->get_payment_spp_based_student($condition, 'monthly');
				
				$condition      = array('siswa.id' => $keyStudent['siswa_id']);
				$getDetailSiswa = $this->crud->get_kelas_jurusan_based_student($condition)->row();
				
				switch ($keyPaymentNonSPP['nama_kategori']) {
					case 'Mid Ganjil':
					case 'Mid Genap':
					case 'Semester Ganjil':
					case 'Semester Genap':
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id);
						# code...
						break;
					
					case 'Komite':
					case 'Perpisahan':
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id,
							'tahun_masuk'   => $getDetailSiswa->tahun_masuk);
						break;
					
					case 'Prakerin':
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id,
							'tahun_masuk'   => $getDetailSiswa->tahun_masuk,
							'jurusan_id'    => $getDetailSiswa->jurusan_id,
							'gender'        => $getDetailSiswa->gender);
						break;

					default:
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id, 
							'tahun_masuk'   => $getDetailSiswa->tahun_masuk, 
							'gender'        => $getDetailSiswa->gender, 
							'jurusan_id'    => $getDetailSiswa->jurusan_id, 
							'semester_id'   => $getDetailSiswa->semester_id);
						break;
				}
				$biaya = $this->crud->get('kategori_keuangan', $condition)->row()->biaya;

				$NonSPP2 = array();
				foreach ($getPaymentNonSPP->result_array() as $keyGetPaymentNonSPP) {
					
					$tmpNonSPP2 = array(
						'Amount'  => $keyGetPaymentNonSPP['amount'],
						'Created' => $keyGetPaymentNonSPP['created'],
						'Id'      => $keyGetPaymentNonSPP['id']);
						// 'Q'       => $this->db->last_query());
					array_push($NonSPP2, $tmpNonSPP2);
				}
				
				$totalNonSPP = array_sum(array_column($NonSPP2, 'Amount'));
				$sisa        = $biaya - $totalNonSPP;
				if ($sisa >= 0 or !$biaya) {
					$lunas = '';
				} else if($sisa < 0){
					$sisa  = $totalNonSPP - $biaya;
					$lunas = 'Berlebih '.$sisa;
					$sisa  = 0;
				} else {
					$lunas = 'Lunas '.$biaya;
				}
				
				$tmpNonSPP      = array(
					'Nama_kategori' => $keyPaymentNonSPP['nama_kategori'],
					'Data'          => $NonSPP2,
					'Status'        => $lunas,
					'Sisa'          => $sisa
					);
				array_push($NonSPP, $tmpNonSPP);
				array_push($totalNonSPP2, array('totalNonSPP' => $totalNonSPP));
			}

			$lunas1 = array_sum(array_column($SPP, 'Amount'));
			$lunas2 = array_sum(array_column($totalNonSPP2, 'totalNonSPP'));
			$total = $lunas1 + $lunas2;
			$tmp = array(
				'siswa_id'      => $keyStudent['siswa_id'],
				'NIS'           => $getStudent->nis,
				'Nama'          => $getStudent->nama_siswa,
				'Kelas'         => $getKelas->kelas,
				'SPP'           => $SPP,
				'PaymentNonSPP' => $NonSPP,
				'Total'         => $total);
			array_push($buff, $tmp);
		}
		$condition         = array('sekolah_id' => $sekolah_id, 'date(date_created)' => $month);
		$lainnya           = $this->crud->get_payment_lainnya_monthly($condition);
		$penerimaanLainnya = $lainnya->result_array();
		$totalLainnya      = array_sum(array_column($penerimaanLainnya, 'amount'));
		$totalPenerimaan   = array_sum(array_column($buff, 'Total')) + $totalLainnya;

		$kelas        = $this->kelas($sekolah_id);
		$sekolah      = $this->crud->get('sekolah', array('id' => $sekolah_id))->row()->nama_sekolah;
		$kelasJurusan = $this->crud->get_kelas_jurusan(array('jurusan.sekolah_id' => $sekolah_id))->result_array();
		$kategoriPenerimaanLainnya = $this->crud->get('kategori_keuangan_lainnya', array())->result_array();
		
		$datas = array(
			'payment'                   => $buff,
			'totalPenerimaan'           => $totalPenerimaan,
			'paymentLainnya'            => $penerimaanLainnya,
			'kategoriPaymentNonSPP'     => $getCategoryPaymentNonSPP,
			'totalLainnya'              => $totalLainnya,
			'tanggal'                   => $month,
			'sekolah'                   => $sekolah,
			'sekolah_id'                => $sekolah_id,
			'bulan'                     => array_column($getBulan, 'bulanAlias'),
			'kelasJurusan'              => $kelasJurusan,
			'kategoriPenerimaanLainnya' => $kategoriPenerimaanLainnya);

		$data = array(
			'page'    => 'v2/page/bulanan_in',
			'menu'    => 'Penerimaan Bulanan',
			'submenu' => $kelas,
			'data'    => $datas
			);
		$this->parser->parse('v2/lte', $data);
	}


	public function bulanan_in2($sekolah_id = null, $month = null)
	{
		//error_reporting(0);
		date_default_timezone_set("Asia/Jakarta");

		if (!$sekolah_id and !$month) {
			$sekolah_id = $this->session->sekolah_id;
			$month      = $this->input->post('month');
			$year       = $this->input->post('year');
			$month      = $year.'-'.$month;
			if (!$year) {
				$month = date('Y-m');
			}
		}

		$buff    = array();
		$student = $this->keuangan_m->studentPaymentPermonth($sekolah_id, $month);

		$condition                = array('nama_kategori != ' => 'SPP', 'sekolah_id' => $sekolah_id);
		$getCategoryPaymentNonSPP = $this->crud->get_category_payment($condition)->result_array();
		$getBulan                 = $this->crud->get('bulan', array())->result_array();
		$tahun_ajar               = $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		$tahun_pelajaran          = explode('-', $tahun_ajar);
		$semester1                = $tahun_pelajaran['0'];
		$semester2                = $tahun_pelajaran['1'];

		foreach ($student->result_array() as $keyStudent) {

			$condition  = array('siswa.id' => $keyStudent['siswa_id']);
			$getStudent = $this->crud->get('siswa', $condition)->row();
			$getKelas   = $this->crud->get_kelas_jurusan_based_student($condition)->row();
			$SPP        = array();

			foreach ($getBulan as $keyBulan) {
				if ($keyBulan['id'] > 6) {
					// TAHUN AJARAN GENAP
					$semester = $semester2;
				} else {
					// TAHUN AJARAN GANJIL
					$semester = $semester1;
				}
				
				$condition = array(
					'kategori_keuangan.nama_kategori' => 'SPP',
					'payment.siswa_id'                => $keyStudent['siswa_id'], 
					'payment.tahun'                   => $semester,
					'payment.bulan_id'                => $keyBulan['id'],
					'payment.flag'                    => 'show',
					'date(payment.date_created)'      => $month);
				$getSPP = $this->crud->get_payment_spp_based_student($condition, 'monthly')->result_array();

				$resSPP = array();
				foreach ($getSPP as $keyGetSPP) {

					$tmpSPP2 = array(
						'Amount'  => $keyGetSPP['amount'],
						'Created' => $keyGetSPP['created'],
						'Id'      => $keyGetSPP['id']);
					array_push($resSPP, $tmpSPP2);
				}
				$SPPtotal = array_sum(array_column($resSPP, 'Amount'));
				$tmpSPP = array(
					'data'   => $resSPP,
					'Bulan'  => $keyBulan['id'],
					'Amount' => $SPPtotal);
				array_push($SPP, $tmpSPP);
			}
			
			$NonSPP       = array();
			$totalNonSPP2 = array();
			foreach ($getCategoryPaymentNonSPP as $keyPaymentNonSPP) {
				$condition = array(
					'kategori_keuangan.nama_kategori' => $keyPaymentNonSPP['nama_kategori'],
					'payment.siswa_id'                => $keyStudent['siswa_id'], 
					'payment.flag'                    => 'show',
					'date(payment.date_created)'      => $month);
				$getPaymentNonSPP = $this->crud->get_payment_spp_based_student($condition, 'monthly');
				
				$condition      = array('siswa.id' => $keyStudent['siswa_id']);
				$getDetailSiswa = $this->crud->get_kelas_jurusan_based_student($condition)->row();
				
				switch ($keyPaymentNonSPP['nama_kategori']) {
					case 'Mid Ganjil':
					case 'Mid Genap':
					case 'Semester Ganjil':
					case 'Semester Genap':
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id);
						# code...
						break;
					
					case 'Komite':
					case 'Perpisahan':
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id,
							'tahun_masuk'   => $getDetailSiswa->tahun_masuk);
						break;
					
					case 'Prakerin':
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id,
							'tahun_masuk'   => $getDetailSiswa->tahun_masuk,
							'jurusan_id'    => $getDetailSiswa->jurusan_id,
							'gender'        => $getDetailSiswa->gender);
						break;

					default:
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id, 
							'tahun_masuk'   => $getDetailSiswa->tahun_masuk, 
							'gender'        => $getDetailSiswa->gender, 
							'jurusan_id'    => $getDetailSiswa->jurusan_id, 
							'semester_id'   => $getDetailSiswa->semester_id);
						break;
				}
				$biaya = $this->crud->get('kategori_keuangan', $condition)->row()->biaya;

				$NonSPP2 = array();
				foreach ($getPaymentNonSPP->result_array() as $keyGetPaymentNonSPP) {
					
					$tmpNonSPP2 = array(
						'Amount'  => $keyGetPaymentNonSPP['amount'],
						'Created' => $keyGetPaymentNonSPP['created'],
						'Id'      => $keyGetPaymentNonSPP['id']);
						// 'Q'       => $this->db->last_query());
					array_push($NonSPP2, $tmpNonSPP2);
				}
				
				$totalNonSPP = array_sum(array_column($NonSPP2, 'Amount'));
				$sisa        = $biaya - $totalNonSPP;
				if ($sisa >= 0 or !$biaya) {
					$lunas = '';
				} else if($sisa < 0){
					$sisa  = $totalNonSPP - $biaya;
					$lunas = 'Berlebih '.$sisa;
					$sisa  = 0;
				} else {
					$lunas = 'Lunas '.$biaya;
				}
				
				$tmpNonSPP      = array(
					'Nama_kategori' => $keyPaymentNonSPP['nama_kategori'],
					'Data'          => $NonSPP2,
					'Status'        => $lunas,
					'Sisa'          => $sisa
					);
				array_push($NonSPP, $tmpNonSPP);
				array_push($totalNonSPP2, array('totalNonSPP' => $totalNonSPP));
			}

			$lunas1 = array_sum(array_column($SPP, 'Amount'));
			$lunas2 = array_sum(array_column($totalNonSPP2, 'totalNonSPP'));
			$total = $lunas1 + $lunas2;
			$tmp = array(
				'siswa_id'      => $keyStudent['siswa_id'],
				'NIS'           => $getStudent->nis,
				'Nama'          => $getStudent->nama_siswa,
				'Kelas'         => $getKelas->kelas,
				'SPP'           => $SPP,
				'PaymentNonSPP' => $NonSPP,
				'Total'         => $total);
			array_push($buff, $tmp);
		}
		$condition         = array('sekolah_id' => $sekolah_id, 'date(date_created)' => $month);
		$lainnya           = $this->crud->get_payment_lainnya_monthly($condition);
		$penerimaanLainnya = $lainnya->result_array();
		$totalLainnya      = array_sum(array_column($penerimaanLainnya, 'amount'));
		$totalPenerimaan   = array_sum(array_column($buff, 'Total')) + $totalLainnya;

		$kelas        = $this->kelas($sekolah_id);
		$sekolah      = $this->crud->get('sekolah', array('id' => $sekolah_id))->row()->nama_sekolah;
		$kelasJurusan = $this->crud->get_kelas_jurusan(array('jurusan.sekolah_id' => $sekolah_id))->result_array();
		$kategoriPenerimaanLainnya = $this->crud->get('kategori_keuangan_lainnya', array())->result_array();
		
		$datas = array(
			'payment'                   => $buff,
			'totalPenerimaan'           => $totalPenerimaan,
			'paymentLainnya'            => $penerimaanLainnya,
			'kategoriPaymentNonSPP'     => $getCategoryPaymentNonSPP,
			'totalLainnya'              => $totalLainnya,
			'tanggal'                   => $month,
			'sekolah'                   => $sekolah,
			'sekolah_id'                => $sekolah_id,
			'bulan'                     => array_column($getBulan, 'bulanAlias'),
			'kelasJurusan'              => $kelasJurusan,
			'kategoriPenerimaanLainnya' => $kategoriPenerimaanLainnya,
			'tahunajaran'               => $tahun_ajar);

		
		return $datas;
	}


	public function getq($sekolah_id)
	{
		$condition = array('jurusan.sekolah_id' => $sekolah_id, 'kelas_sekolah.status' => 'show');
		$this->crud->get_kelas_jurusan($condition, 'sidebar')->result_array();
		echo $this->db->last_query();
	}


	public function kelas_in($kelas_sekolah_id, $month = null)
	{
		error_reporting(0);
		date_default_timezone_set("Asia/Jakarta");

		$sekolah_id = $this->session->sekolah_id;
		$month = $_GET['date'];
		if (!$month) {
			$month = date('Y-m');
		}

		$buff            = array();
		$tahun_ajar      = $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		$tahun_pelajaran = explode('-', $tahun_ajar);
		$semester1       = $tahun_pelajaran['0'];
		$semester2       = $tahun_pelajaran['1'];
		$S1              = $semester1.'-07-01';
		$S2              = $semester2.'-06-30';
		$student         = $this->keuangan_m->studentPaymentPermonthOnClass($kelas_sekolah_id, $S1, $S2);

		$condition                = array('nama_kategori != ' => 'SPP', 'sekolah_id' => $sekolah_id);
		$getCategoryPaymentNonSPP = $this->crud->get_category_payment($condition)->result_array();
		$getBulan                 = $this->crud->get('bulan', array())->result_array();

		foreach ($student->result_array() as $keyStudent) {

			$condition  = array('siswa.id' => $keyStudent['siswa_id']);
			$getStudent = $this->crud->get('siswa', $condition)->row();
			$getKelas   = $this->crud->get_kelas_jurusan_based_student($condition)->row();
			$SPP        = array();

			foreach ($getBulan as $keyBulan) {
				if ($keyBulan['id'] > 6) {
					// TAHUN AJARAN GENAP
					$semester = $semester2;
				} else {
					// TAHUN AJARAN GANJIL
					$semester = $semester1;
				}
				
				$condition = array(
					'kategori_keuangan.nama_kategori' => 'SPP',
					'payment.siswa_id'                => $keyStudent['siswa_id'], 
					'payment.tahun'                   => $semester,
					'payment.bulan_id'                => $keyBulan['id'],
					'payment.flag'                    => 'show',
					'semester1'                       => $S1,
					'semester2'                       => $S2
					// 'date(payment.date_created)'      => $month
					);
				$getSPP = $this->crud->get_payment_spp_based_student($condition, 'tahunajaran')->result_array();

				$resSPP = array();
				foreach ($getSPP as $keyGetSPP) {

					$tmpSPP2 = array(
						'Amount'  => $keyGetSPP['amount'],
						'Created' => $keyGetSPP['created'],
						'Id'      => $keyGetSPP['id']);
					array_push($resSPP, $tmpSPP2);
				}
				$SPPtotal = array_sum(array_column($resSPP, 'Amount'));
				$tmpSPP = array(
					'data'   => $resSPP,
					'Bulan'  => $keyBulan['id'],
					'Amount' => $SPPtotal);
				array_push($SPP, $tmpSPP);
			}
			
			$NonSPP       = array();
			$totalNonSPP2 = array();
			foreach ($getCategoryPaymentNonSPP as $keyPaymentNonSPP) {
				$condition = array(
					'kategori_keuangan.nama_kategori' => $keyPaymentNonSPP['nama_kategori'],
					'payment.siswa_id'                => $keyStudent['siswa_id'], 
					'payment.flag'                    => 'show',
					'semester1'                       => $S1,
					'semester2'                       => $S2);
				$getPaymentNonSPP = $this->crud->get_payment_spp_based_student($condition, 'tahunajaran');
				
				$condition      = array('siswa.id' => $keyStudent['siswa_id']);
				$getDetailSiswa = $this->crud->get_kelas_jurusan_based_student($condition)->row();
				
				switch ($keyPaymentNonSPP['nama_kategori']) {
					case 'Mid Ganjil':
					case 'Mid Genap':
					case 'Semester Ganjil':
					case 'Semester Genap':
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id);
						# code...
						break;
					
					case 'Komite':
					case 'Perpisahan':
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id,
							'tahun_masuk'   => $getDetailSiswa->tahun_masuk);
						break;
					
					case 'Prakerin':
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id,
							'tahun_masuk'   => $getDetailSiswa->tahun_masuk,
							'jurusan_id'    => $getDetailSiswa->jurusan_id,
							'gender'        => $getDetailSiswa->gender);
						break;

					default:
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id, 
							'tahun_masuk'   => $getDetailSiswa->tahun_masuk, 
							'gender'        => $getDetailSiswa->gender, 
							'jurusan_id'    => $getDetailSiswa->jurusan_id, 
							'semester_id'   => $getDetailSiswa->semester_id);
						break;
				}
				$biaya = $this->crud->get('kategori_keuangan', $condition)->row()->biaya;

				$NonSPP2 = array();
				foreach ($getPaymentNonSPP->result_array() as $keyGetPaymentNonSPP) {
					
					$tmpNonSPP2 = array(
						'Amount'  => $keyGetPaymentNonSPP['amount'],
						'Created' => $keyGetPaymentNonSPP['created'],
						'Id'      => $keyGetPaymentNonSPP['id']);
						// 'Q'       => $this->db->last_query());
					array_push($NonSPP2, $tmpNonSPP2);
				}
				
				$totalNonSPP = array_sum(array_column($NonSPP2, 'Amount'));
				$sisa        = $biaya - $totalNonSPP;
				if ($sisa >= 0 or !$biaya) {
					$lunas = '';
				} else if($sisa < 0){
					$sisa  = $totalNonSPP - $biaya;
					$lunas = 'Berlebih '.$sisa;
					$sisa  = 0;
				} else {
					$lunas = 'Lunas '.$biaya;
				}
				
				$tmpNonSPP      = array(
					'Nama_kategori' => $keyPaymentNonSPP['nama_kategori'],
					'Data'          => $NonSPP2,
					'Status'        => $lunas,
					'Sisa'          => $sisa
					);
				array_push($NonSPP, $tmpNonSPP);
				array_push($totalNonSPP2, array('totalNonSPP' => $totalNonSPP));
			}

			$lunas1 = array_sum(array_column($SPP, 'Amount'));
			$lunas2 = array_sum(array_column($totalNonSPP2, 'totalNonSPP'));
			$total = $lunas1 + $lunas2;
			$tmp = array(
				'siswa_id'      => $keyStudent['siswa_id'],
				'NIS'           => $getStudent->nis,
				'Nama'          => $getStudent->nama_siswa,
				'Kelas'         => $getKelas->kelas,
				'SPP'           => $SPP,
				'PaymentNonSPP' => $NonSPP,
				'Total'         => $total);
			array_push($buff, $tmp);
		}

		$totalPenerimaan   = array_sum(array_column($buff, 'Total'));

		$kelas        = $this->kelas($sekolah_id);
		$sekolah      = $this->crud->get('sekolah', array('id' => $sekolah_id))->row()->nama_sekolah;
		$kelasJurusan = $this->crud->get_kelas_jurusan(array('jurusan.sekolah_id' => $sekolah_id))->result_array();
		$nama_kelas   = $this->crud->get_kelas_jurusan(array('kelas_sekolah.id' => $kelas_sekolah_id))->row()->kelas;

		$datas = array(
			'payment'               => $buff,
			'totalPenerimaan'       => $totalPenerimaan,
			'kategoriPaymentNonSPP' => $getCategoryPaymentNonSPP,
			'tanggal'               => $month,
			'sekolah'               => $sekolah,
			'sekolah_id'            => $sekolah_id,
			'bulan'                 => array_column($getBulan, 'bulanAlias'),
			'kelasJurusan'          => $kelasJurusan,
			'kategoriPenerimaanLainnya' => $kategoriPenerimaanLainnya);

		$data = array(
			'page'    => 'v2/page/kelas_in',
			'menu'    => 'Penerimaan Kelas ('.trim($nama_kelas).')',
			'submenu' => $kelas,
			'data'    => $datas
			);
		$this->parser->parse('v2/lte', $data);
	}


	public function kelas_in2($kelas_sekolah_id, $month = null)
	{
		//error_reporting(0);
		date_default_timezone_set("Asia/Jakarta");

		$jurusan_id = $this->crud->get('kelas_sekolah', array('id' => $kelas_sekolah_id))->row()->jurusan_id;
		$sekolah_id = $this->crud->get('jurusan', array('id' => $jurusan_id))->row()->sekolah_id;

		if (!$month) {
			$month = date('Y-m');
		}

		$buff            = array();
		$tahun_ajar      = $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		$tahun_pelajaran = explode('-', $tahun_ajar);
		$semester1       = $tahun_pelajaran['0'];
		$semester2       = $tahun_pelajaran['1'];
		$S1              = $semester1.'-07-01';
		$S2              = $semester2.'-06-30';
		$student         = $this->keuangan_m->studentPaymentPermonthOnClass($kelas_sekolah_id, $S1, $S2);

		$condition                = array('nama_kategori != ' => 'SPP', 'sekolah_id' => $sekolah_id);
		$getCategoryPaymentNonSPP = $this->crud->get_category_payment($condition)->result_array();
		$getBulan                 = $this->crud->get('bulan', array())->result_array();

		foreach ($student->result_array() as $keyStudent) {

			$condition  = array('siswa.id' => $keyStudent['siswa_id']);
			$getStudent = $this->crud->get('siswa', $condition)->row();
			$getKelas   = $this->crud->get_kelas_jurusan_based_student($condition)->row();
			$SPP        = array();

			foreach ($getBulan as $keyBulan) {
				if ($keyBulan['id'] > 6) {
					// TAHUN AJARAN GENAP
					$semester = $semester2;
				} else {
					// TAHUN AJARAN GANJIL
					$semester = $semester1;
				}
				
				$condition = array(
					'kategori_keuangan.nama_kategori' => 'SPP',
					'payment.siswa_id'                => $keyStudent['siswa_id'], 
					'payment.tahun'                   => $semester,
					'payment.bulan_id'                => $keyBulan['id'],
					'payment.flag'                    => 'show',
					'semester1'                       => $S1,
					'semester2'                       => $S2
					// 'date(payment.date_created)'      => $month
					);
				$getSPP = $this->crud->get_payment_spp_based_student($condition, 'tahunajaran')->result_array();

				$resSPP = array();
				foreach ($getSPP as $keyGetSPP) {

					$tmpSPP2 = array(
						'Amount'  => $keyGetSPP['amount'],
						'Created' => $keyGetSPP['created'],
						'Id'      => $keyGetSPP['id']);
					array_push($resSPP, $tmpSPP2);
				}
				$SPPtotal = array_sum(array_column($resSPP, 'Amount'));
				$tmpSPP = array(
					'data'   => $resSPP,
					'Bulan'  => $keyBulan['id'],
					'Amount' => $SPPtotal);
				array_push($SPP, $tmpSPP);
			}
			
			$NonSPP       = array();
			$totalNonSPP2 = array();
			foreach ($getCategoryPaymentNonSPP as $keyPaymentNonSPP) {
				$condition = array(
					'kategori_keuangan.nama_kategori' => $keyPaymentNonSPP['nama_kategori'],
					'payment.siswa_id'                => $keyStudent['siswa_id'], 
					'payment.flag'                    => 'show',
					'semester1'                       => $S1,
					'semester2'                       => $S2);
				$getPaymentNonSPP = $this->crud->get_payment_spp_based_student($condition, 'tahunajaran');
				
				$condition      = array('siswa.id' => $keyStudent['siswa_id']);
				$getDetailSiswa = $this->crud->get_kelas_jurusan_based_student($condition)->row();
				
				switch ($keyPaymentNonSPP['nama_kategori']) {
					case 'Mid Ganjil':
					case 'Mid Genap':
					case 'Semester Ganjil':
					case 'Semester Genap':
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id);
						# code...
						break;
					
					case 'Komite':
					case 'Perpisahan':
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id,
							'tahun_masuk'   => $getDetailSiswa->tahun_masuk);
						break;
					
					case 'Prakerin':
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id,
							'tahun_masuk'   => $getDetailSiswa->tahun_masuk,
							'jurusan_id'    => $getDetailSiswa->jurusan_id,
							'gender'        => $getDetailSiswa->gender);
						break;

					default:
						$condition = array(
							'nama_kategori' => $keyPaymentNonSPP['nama_kategori'], 
							'sekolah_id'    => $sekolah_id, 
							'tahun_masuk'   => $getDetailSiswa->tahun_masuk, 
							'gender'        => $getDetailSiswa->gender, 
							'jurusan_id'    => $getDetailSiswa->jurusan_id, 
							'semester_id'   => $getDetailSiswa->semester_id);
						break;
				}
				$biaya = $this->crud->get('kategori_keuangan', $condition)->row()->biaya;

				$NonSPP2 = array();
				foreach ($getPaymentNonSPP->result_array() as $keyGetPaymentNonSPP) {
					
					$tmpNonSPP2 = array(
						'Amount'  => $keyGetPaymentNonSPP['amount'],
						'Created' => $keyGetPaymentNonSPP['created'],
						'Id'      => $keyGetPaymentNonSPP['id']);
						// 'Q'       => $this->db->last_query());
					array_push($NonSPP2, $tmpNonSPP2);
				}
				
				$totalNonSPP = array_sum(array_column($NonSPP2, 'Amount'));
				$sisa        = $biaya - $totalNonSPP;
				if ($sisa >= 0 or !$biaya) {
					$lunas = '';
				} else if($sisa < 0){
					$sisa  = $totalNonSPP - $biaya;
					$lunas = 'Berlebih '.$sisa;
					$sisa  = 0;
				} else {
					$lunas = 'Lunas '.$biaya;
				}
				
				$tmpNonSPP      = array(
					'Nama_kategori' => $keyPaymentNonSPP['nama_kategori'],
					'Data'          => $NonSPP2,
					'Status'        => $lunas,
					'Sisa'          => $sisa
					);
				array_push($NonSPP, $tmpNonSPP);
				array_push($totalNonSPP2, array('totalNonSPP' => $totalNonSPP));
			}

			$lunas1 = array_sum(array_column($SPP, 'Amount'));
			$lunas2 = array_sum(array_column($totalNonSPP2, 'totalNonSPP'));
			$total = $lunas1 + $lunas2;
			$tmp = array(
				'siswa_id'      => $keyStudent['siswa_id'],
				'NIS'           => $getStudent->nis,
				'Nama'          => $getStudent->nama_siswa,
				'Kelas'         => $getKelas->kelas,
				'SPP'           => $SPP,
				'PaymentNonSPP' => $NonSPP,
				'Total'         => $total);
			array_push($buff, $tmp);
		}

		$totalPenerimaan   = array_sum(array_column($buff, 'Total'));

		$kelas        = $this->kelas($sekolah_id);
		$sekolah      = $this->crud->get('sekolah', array('id' => $sekolah_id))->row()->nama_sekolah;
		$kelasJurusan = $this->crud->get_kelas_jurusan(array('jurusan.sekolah_id' => $sekolah_id))->result_array();
		$nama_kelas   = $this->crud->get_kelas_jurusan(array('kelas_sekolah.id' => $kelas_sekolah_id))->row()->kelas;

		$datas = array(
			'payment'                   => $buff,
			'totalPenerimaan'           => $totalPenerimaan,
			'kategoriPaymentNonSPP'     => $getCategoryPaymentNonSPP,
			'tanggal'                   => $month,
			'sekolah'                   => $sekolah,
			'sekolah_id'                => $sekolah_id,
			'bulan'                     => array_column($getBulan, 'bulanAlias'),
			'kelasJurusan'              => $kelasJurusan,
			'kategoriPenerimaanLainnya' => $kategoriPenerimaanLainnya,
			'tahunajaran'               => $tahun_ajar,
			'nama_kelas'                => $nama_kelas);

		return $datas;
	}


	public function harian_out($sekolah_id=null,$now=null)
	{
		date_default_timezone_set("Asia/Jakarta");
		if (!$sekolah_id) {
			$sekolah_id = $this->session->sekolah_id;
		}

		$now = $this->input->get('date');
		if (!$now) {
			$now = $this->uri->segment(5);
			if (!$now) {
				$now = date('Y-m-d');
			}
		}

		$total_harian_out = $this->keuangan_m->get_total_out_harian($sekolah_id,$now)->row('total_harian_out');
		$list_harian_out  = $this->keuangan_m->list_harian_out($sekolah_id,$now)->result_array();
		$kelas            = $this->kelas($sekolah_id);
		$sekolah          = $this->crud->get('sekolah', array('id' => $sekolah_id))->row()->nama_sekolah;

		$datas = array(
			'tanggal'    => $now,
			'sekolah'    => $sekolah,
			'sekolah_id' => $sekolah_id
			);

		$data = array(
			'page'             => 'v2/page/harian_out',
			'menu'             => 'Pengeluaran Harian',
			'submenu'          => $kelas,
			'data'             => $datas,
			'list_harian_out'  => $list_harian_out,
			'total_harian_out' => $total_harian_out,
			'list_pengeluaran' => $this->keuangan_m->list_pengeluaran()->result_array()
			);
		$this->parser->parse('v2/lte', $data);
	}


	public function bulanan_out($now=null, $sekolah_id=null)
	{
		date_default_timezone_set("Asia/Jakarta");

		if (!$sekolah_id) {
			$sekolah_id = $this->session->sekolah_id;
		}

		$now = $this->input->get('date');
		if (!$now) {
			$now = $this->uri->segment(4);
			if (!$now) {
				$now = date('Y-m');
			}
		}

		$total_harian_out = $this->keuangan_m->get_total_out_harian($sekolah_id,$now)->row('total_harian_out');
		$list_harian_out  = $this->keuangan_m->list_bulanan_out($now,$sekolah_id)->result_array();
		$kelas            = $this->kelas($sekolah_id);
		$sekolah          = $this->crud->get('sekolah', array('id' => $sekolah_id))->row()->nama_sekolah;

		$datas = array(
			'tanggal'    => $now,
			'sekolah'    => $sekolah,
			'sekolah_id' => $sekolah_id
			);

		$data = array(
			'page'             => 'v2/page/bulanan_out',
			'menu'             => 'Pengeluaran Bulanan',
			'submenu'          => $kelas,
			'data'             => $datas,
			'list_harian_out'  => $list_harian_out,
			'total_harian_out' => $total_harian_out
			);
		$this->parser->parse('v2/lte', $data);

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


	public function biaya()
	{
		$sekolah_id = $this->session->sekolah_id;
		$kelas      = $this->kelas($sekolah_id);
		$datas      = $this->biaya_m->list_pembayaran($sekolah_id)->result_array();
		$data  = array(
			'page'     => 'v2/page/biaya',
			'menu'     => 'Biaya',
			'submenu'  => $kelas,
			'data'     => $datas,
			'jurusan'  => $this->sekolah_m->list_jurusan($sekolah_id)->result_array(),
			'semester' => $this->sekolah_m->list_semester()->result_array(),
			);
		$this->parser->parse('v2/lte', $data);
	}


	public function jurusan()
	{
		$sekolah_id = $this->session->sekolah_id;
		$kelas      = $this->kelas($sekolah_id);
		$data  = array(
			'page'     => 'v2/page/jurusan',
			'menu'     => 'Daftar Jurusan',
			'submenu'  => $kelas,
			'jurusan'  => $this->sekolah_m->list_jurusan($sekolah_id)->result_array()
			);
		$this->parser->parse('v2/lte', $data);
	}


	public function listkelas()
	{
		$sekolah_id = $this->session->sekolah_id;
		$kelas      = $this->kelas($sekolah_id);
		$data  = array(
			'page'        => 'v2/page/kelas',
			'menu'        => 'Daftar Kelas',
			'submenu'     => $kelas,
			'jurusan'     => $this->sekolah_m->list_jurusan($sekolah_id)->result_array(),
			'list_kelas'  => $this->sekolah_m->list_kelas($sekolah_id)->result_array(),
			'jenis_kelas' => $this->sekolah_m->jenis_kelas($sekolah_id)->result_array()
			);
		$this->parser->parse('v2/lte', $data);
	}


	public function siswa()
	{
		$sekolah_id = $this->session->sekolah_id;
		$kelas      = $this->kelas($sekolah_id);
		
		$data  = array(
			'page'        => 'v2/page/siswa',
			'menu'        => 'Daftar Siswa',
			'submenu'     => $kelas,
			'siswa'       => $this->siswa_m->list_siswa($sekolah_id)->result_array(),
			'kelas_siswa' => $this->sekolah_m->kelas_siswa($sekolah_id)->result_array()
			);
		$this->parser->parse('v2/lte', $data);
	}


	public function permintaan($month=null,$sekolah_id=null)
	{
		error_reporting(0);
		if (!$sekolah_id) {
			$sekolah_id	= $this->session->sekolah_id;
		}

		if (!$month) {
			$month = $_REQUEST['date'];
			if (!$month) {
				$month = date('Y-m');
			}
		}

		if ($this->session->privilege_id =='1') {
			$permintaan = $this->keuangan_m->list_permintaan($month,$sekolah_id);
		}else{
			$permintaan = $this->keuangan_m->list_permintaan_yayasan($month);
		}

		$kelas = $this->kelas($sekolah_id);
		$title = 'Daftar Permintaan '.$month;
		$data  = array(
			'page'             => 'v2/page/permintaan',
			'menu'             => 'Permintaan',
			'title'            => $title,
			'submenu'          => $kelas,
			'list_bulan'       => $this->keuangan_m->list_bulan()->result_array(),
			'list_permintaan'  => $permintaan['list_permintaan']->result_array(),
			'total_permintaan' => $permintaan['total_permintaan']->row('total_permintaan')
			);
		$this->parser->parse('v2/lte', $data);
	}


	public function harian_in_yayasan($sekolah_id=null)
	{
		error_reporting(0);
		date_default_timezone_set("Asia/Jakarta");
		if (!$sekolah_id) {
			$sekolah_id = $this->session->sekolah_id;
		}

		$now = $this->input->get('date');
		if (!$now) {
			$now = $this->uri->segment(4);
			if (!$now) {
				$now = date('Y-m-d');
			}
		}

		$payment_lainnya = $this->keuangan_m->list_harian_in_lainnya($sekolah_id,$now);

		$kelas = $this->kelas($sekolah_id);
		$data  = array(
			'page'                        => 'v2/page/harian_in_yayasan',
			'menu'                        => 'Penerimaan Harian',
			'submenu'                     => $kelas,
			'title'                       => 'Penerimaan Harian '.$now,
			'payment_lainnya'             => $payment_lainnya['sql1']->result_array(),
			'get_total_in_harian_lainnya' => $payment_lainnya['sql2']->row('total'),
			'dateType'                    => 'datepicker',
			'action'                      => 'harian_in_yayasan'
			);

		$this->parser->parse('v2/lte', $data);
	}


	public function bulanan_in_yayasan($now=null)
	{
		if (!$now) {
			$now = $this->input->get('date');
			if (!$now) {
				$now = date('Y-m');
			} 
		}

		$sekolah_id      = $this->session->sekolah_id;
		$payment_lainnya = $this->keuangan_m->list_harian_in_lainnya($sekolah_id,$now);
		$kelas           = $this->kelas($sekolah_id);

		$data  = array(
			'page'                        => 'v2/page/harian_in_yayasan',
			'menu'                        => 'Penerimaan Bulanan',
			'submenu'                     => $kelas,
			'title'                       => 'Penerimaan Bulan '.$now,
			'payment_lainnya'             => $payment_lainnya['sql1']->result_array(),
			'get_total_in_harian_lainnya' => $payment_lainnya['sql2']->row('total'),
			'dateType'                    => 'datepickerMonth',
			'action'                      => 'bulanan_in_yayasan',
			'returnURL'                   => 'bln'
			);

		$this->parser->parse('v2/lte', $data);
	}


	public function harian_out_yayasan($sekolah_id=null,$now=null)
	{
		date_default_timezone_set("Asia/Jakarta");
		if (!$sekolah_id) {
			$sekolah_id = $this->session->sekolah_id;
		}

		$now = $this->input->get('date');
		if (!$now) {
			$now = date('Y-m-d');
		}
		
 		$kelas = $this->kelas($sekolah_id);
		$data  = array(
			'page'                     => 'v2/page/harian_out_yayasan',
			'menu'                     => 'Pengeluaran Harian',
			'submenu'                  => $kelas,
			'title'                    => 'Pengeluaran '.$now,
			'list_harian_out_yayasan'  => $this->keuangan_m->list_harian_out_yayasan($now)->result_array(),
			'total_harian_out_yayasan' => $this->keuangan_m->get_total_out_harian($sekolah_id,$now)->row('total_harian_out'),
			'dateType'                 => 'datepicker',
			'action'                   => 'harian_out_yayasan',
			'list_pengeluaran'         => $this->keuangan_m->list_pengeluaran()->result_array()
			);

		$this->parser->parse('v2/lte', $data);
	}


	public function rekap_harian_yayasan()
	{
		date_default_timezone_set("Asia/Jakarta");
		$now        = $this->input->get('date');
		$sekolah_id = $this->session->sekolah_id;
		if (!$now) {
			$now = date('Y-m-d');
		}

		$buff        = array();
		$listSekolah = $this->sekolah_m->list_sekolah()->result_array();
		
		foreach ($listSekolah as $key) {
			$harianIn  = $this->keuangan_m->get_total_in_harian($key['id'], $now)->row();
			$inLainnya = $this->keuangan_m->get_total_in_harian_lainnya($key['id'], $now)->row()->total_harian;
			$harianOut = $this->keuangan_m->get_total_out_harian($key['id'], $now)->row();
			$selisih   = ($harianIn->total_harian + $inLainnya) - $harianOut->total_harian_out; 
			$tmp       = array(
				'tanggal'   => $now,
				'sekolah'   => $key['nama_sekolah'],
				'in'        => $harianIn->total_harian + $inLainnya,
				'out'       => $harianOut->total_harian_out,
				'selisih'   => $selisih,
				'idSekolah' => $key['id']);
			array_push($buff, $tmp);
		}

		$kelas = $this->kelas($sekolah_id);
		$data  = array(
			'page'    => 'v2/page/rekap_harian_yayasan',
			'menu'    => 'Rekap Harian',
			'submenu' => $kelas,
			'title'   => 'Rekap Harian '.$now,
			'data'    => $buff
			);

		$this->parser->parse('v2/lte', $data);
	}

	/*
	public function rekap_bulanan_yayasan($tahun_ajar = null)
	{
		$sekolah_id = $this->session->sekolah_id;
		
		if (!$tahun_ajar) {
			$tahun_ajar = $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		}
		$tahun_pelajaran = explode('-', $tahun_ajar);
		$semester1       = $tahun_pelajaran['0'];
		$semester2       = $tahun_pelajaran['1'];

		$list_sekolah = $this->sekolah_m->list_sekolah()->result_array();
		$list_bulan   = $this->keuangan_m->list_bulan()->result_array();
		$buff2        = array();
		
		foreach ($list_bulan as $key) {
			if ($key['id'] > 6) {
				// TAHUN AJARAN GENAP 
				$semester = $semester2; 
			} else {
				// TAHUN AJARAN GANJIL 
				$semester = $semester1; 
			}
			$month  = $semester.'-'.$key['bulan_in_code'];
			$month2 = $key['bulan'].' - '.$semester;
			$buff   = array();

			foreach ($list_sekolah as $keySekolah) {
				$harianIn  = $this->keuangan_m->get_total_in_harian($keySekolah['id'], $month)->row();
				$inLainnya = $this->keuangan_m->get_total_in_harian_lainnya($keySekolah['id'], $month)->row()->total_harian;
				$harianOut = $this->keuangan_m->get_total_out_harian($keySekolah['id'], $month)->row();
				$selisih   = ($harianIn->total_harian + $inLainnya) - $harianOut->total_harian_out;
				$tmp = array(
					'Uang Masuk'  => $harianIn->total_harian + $inLainnya,
					'Uang Keluar' => $harianOut->total_harian_out,
					'Selisih'     => $selisih,
					'Sekolah'     => $keySekolah['nama_sekolah'],
					'idSekolah'   => $keySekolah['id']
					);
				array_push($buff, $tmp);
			}
			
			$totalMasuk   = array_sum(array_column($buff, 'Uang Masuk'));
			$totalKeluar  = array_sum(array_column($buff, 'Uang Keluar'));
			$totalSelisih = array_sum(array_column($buff, 'Selisih'));
			
			$tmpBuff2 = array(
				'bulan'        => $month2,
				'bulanInCode'  => $month,
				'buff'         => $buff,
				'totalMasuk'   => $totalMasuk,
				'totalKeluar'  => $totalKeluar,
				'totalSelisih' => $totalSelisih
				);
			array_push($buff2, $tmpBuff2);
		}

		$total = array();
		foreach ($list_sekolah as $keySekolah) {
			$In      = $this->keuangan_m->get_total_in_tahunan($keySekolah['id'], $tahun_ajar)->row();
			$InOther = $this->keuangan_m->get_total_in_tahunan_lainnya($keySekolah['id'], $tahun_ajar)->row()->total_harian;
			$Out     = $this->keuangan_m->get_total_out_tahunan($keySekolah['id'], $tahun_ajar)->row();
			$sisa    = ($In->total_harian + $InOther) - $Out->total_harian_out;
			$tmp     = array(
				'Uang Masuk'  => $In->total_harian + $InOther,
				'Uang Keluar' => $Out->total_harian_out,
				'Selisih'     => $sisa,
				'Sekolah'     => $keySekolah['nama_sekolah']
				);
			array_push($total, $tmp);
		}

		$totalMasuk   = array_sum(array_column($total, 'Uang Masuk')); 
		$totalKeluar  = array_sum(array_column($total, 'Uang Keluar')); 
		$totalSelisih = array_sum(array_column($total, 'Selisih'));
		$allTotal     = array(
			'Uang Masuk'  => $totalMasuk,
			'Uang Keluar' => $totalKeluar,
			'Selisih'     => $totalSelisih);

		// print_r($total);
		// exit();
		$kelas = $this->kelas($sekolah_id);

		$data  = array(
			'page'         => 'v2/page/rekap_bulanan_yayasan',
			'menu'         => 'Rekap Tahun Ajaran',
			'submenu'      => $kelas,
			'title'        => 'Rekap '.$tahun_ajar,
			'data'         => $buff2,
			'list_sekolah' => $list_sekolah,
			'total'        => $total,
			'allTotal'     => $allTotal,
			'actived'      => 'btn-primary',
			'unactive'     => 'btn-default'
			);

		$this->parser->parse('v2/lte', $data);
	}
	*/


	public function rekap_bulanan_yayasan($tahun_ajar = null)
	{
		$sekolah_id = $this->session->sekolah_id;
		
		if (!$tahun_ajar) {
			$tahun_ajar = $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		}
		$tahun_pelajaran = explode('-', $tahun_ajar);
		$semester1       = $tahun_pelajaran['0'];
		$semester2       = $tahun_pelajaran['1'];

		$list_sekolah = $this->sekolah_m->list_sekolah()->result_array();
		$list_bulan   = $this->keuangan_m->list_bulan()->result_array();
		$buff2        = array();
		
		foreach ($list_bulan as $key) {
			if ($key['id'] > 6) {
				// TAHUN AJARAN GENAP 
				$semester = $semester2; 
			} else {
				// TAHUN AJARAN GANJIL 
				$semester = $semester1; 
			}
			$month  = $semester.'-'.$key['bulan_in_code'];
			$month2 = $key['bulan'].' - '.$semester;
			$buff   = array();

			foreach ($list_sekolah as $keySekolah) {
				$harianIn  = $this->keuangan_m->get_total_in_harian($keySekolah['id'], $month)->row();
				$inLainnya = $this->keuangan_m->get_total_in_harian_lainnya($keySekolah['id'], $month)->row()->total_harian;
				$harianOut = $this->keuangan_m->get_total_out_harian($keySekolah['id'], $month)->row();
				$selisih   = ($harianIn->total_harian + $inLainnya) - $harianOut->total_harian_out;
				$tmp = array(
					'Uang Masuk'  => $harianIn->total_harian + $inLainnya,
					'Uang Keluar' => $harianOut->total_harian_out,
					'Selisih'     => $selisih,
					'Sekolah'     => $keySekolah['nama_sekolah'],
					'idSekolah'   => $keySekolah['id']
					);
				array_push($buff, $tmp);
			}
			
			$totalMasuk   = array_sum(array_column($buff, 'Uang Masuk'));
			$totalKeluar  = array_sum(array_column($buff, 'Uang Keluar'));
			$totalSelisih = array_sum(array_column($buff, 'Selisih'));
			
			$tmpBuff2 = array(
				'bulan'        => $month2,
				'bulanInCode'  => $month,
				'buff'         => $buff,
				'totalMasuk'   => $totalMasuk,
				'totalKeluar'  => $totalKeluar,
				'totalSelisih' => $totalSelisih
				);
			array_push($buff2, $tmpBuff2);
		}

		$total = array();
		foreach ($list_sekolah as $keySekolah) {
			$In      = $this->keuangan_m->get_total_in_tahunan($keySekolah['id'], $tahun_ajar)->row();
			$InOther = $this->keuangan_m->get_total_in_tahunan_lainnya($keySekolah['id'], $tahun_ajar)->row()->total_harian;
			$Out     = $this->keuangan_m->get_total_out_tahunan($keySekolah['id'], $tahun_ajar)->row();
			$sisa    = ($In->total_harian + $InOther) - $Out->total_harian_out;
			$tmp     = array(
				'Uang Masuk'  => $In->total_harian + $InOther,
				'Uang Keluar' => $Out->total_harian_out,
				'Selisih'     => $sisa,
				'Sekolah'     => $keySekolah['nama_sekolah']
				);
			array_push($total, $tmp);
		}

		$totalMasuk   = array_sum(array_column($total, 'Uang Masuk')); 
		$totalKeluar  = array_sum(array_column($total, 'Uang Keluar')); 
		$totalSelisih = array_sum(array_column($total, 'Selisih'));
		$allTotal     = array(
			'Uang Masuk'  => $totalMasuk,
			'Uang Keluar' => $totalKeluar,
			'Selisih'     => $totalSelisih);

		// print_r($total);
		// exit();
		$kelas = $this->kelas($sekolah_id);

		$data  = array(
			'page'         => 'v2/page/rekap_bulanan_yayasan',
			'menu'         => 'Rekap Tahun Ajaran',
			'submenu'      => $kelas,
			'title'        => 'Rekap '.$tahun_ajar,
			'data'         => $buff2,
			'list_sekolah' => $list_sekolah,
			'total'        => $total,
			'allTotal'     => $allTotal,
			'actived'      => 'btn-primary',
			'unactive'     => 'btn-default'
			);

		$this->parser->parse('v2/lte', $data);
	}


	public function cetakPDF()
	{
		# code...
	}


}