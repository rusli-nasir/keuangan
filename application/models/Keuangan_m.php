<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_m extends CI_Model {

	public function tahun_ajar()
	{
		$sql = $this->db->query("SELECT * FROM tahun_ajar WHERE status='aktif'");
		return $sql;
	}


	public function list_bulan_spp($siswa_id)
	{
		$sql = $this->db->query("SELECT bulan_id,amount FROM payment WHERE siswa_id='$siswa_id'");
		return $sql;
	}


	public function list_harian_out($sekolah_id,$now=null)
	{
		if (empty($now)) {
			$now = date('Y-m-d');
		}

		$sql = $this->db->query("SELECT
								  kategori_keuangan_pengeluaran.nama_kategori,
								  kategori_keuangan_pengeluaran.id as kid,
								  keterangan,
								  amount,
								  payment_out.id as payid
								FROM
								  payment_out
								  JOIN kategori_keuangan_pengeluaran
								    ON kategori_keuangan_pengeluaran.id = payment_out.kategori_keuangan_pengeluaran_id
								WHERE payment_out.date_created LIKE '%$now%'
								AND payment_out.sekolah_id = '$sekolah_id'");
		return $sql;
	}


	public function get_list_harian_out($payment_id)
	{
		$sekolah_id = $this->session->sekolah_id;
		$sql = $this->db->query("SELECT
								  kategori_keuangan_pengeluaran.nama_kategori,
								  kategori_keuangan_pengeluaran.id as kategori_keuangan_pengeluaran_id,
								  keterangan,
								  amount,
								  payment_out.id as id,
								  DATE_FORMAT(payment_out.date_created,'%d-%m-%Y') as date_created
								FROM
								  payment_out
								  JOIN kategori_keuangan_pengeluaran
								    ON kategori_keuangan_pengeluaran.id = payment_out.kategori_keuangan_pengeluaran_id
								WHERE payment_out.id = '$payment_id'
								AND payment_out.sekolah_id = '$sekolah_id'");
		return $sql;
	}


	public function list_harian_out_yayasan($now)
	{
		$sql = $this->db->query("SELECT
								  kategori_keuangan_pengeluaran.nama_kategori,
								  kategori_keuangan_pengeluaran_id,
								  keterangan,
								  amount,
								  username,
								  payment_out.id,
								  DATE_FORMAT(payment_out.date_created,'%d-%m-%Y') as date_created
								FROM
								  payment_out
								  JOIN kategori_keuangan_pengeluaran
								    ON kategori_keuangan_pengeluaran.id = payment_out.kategori_keuangan_pengeluaran_id
								  JOIN cms_user
								  	ON cms_user.id=payment_out.cms_user_id
								WHERE payment_out.date_created LIKE '%$now%'
								AND payment_out.sekolah_id = '9'");
		return $sql;
	}


	public function list_harian_in($sekolah_id,$now=null)
	{
		if (empty($now)) {
			$now = date('Y-m-d');
		}

        $this->db->select('payment.*');
        $this->db->from('payment');
        $this->db->join('kategori_keuangan', 'kategori_keuangan.id = payment.kategori_keuangan_id');
        $this->db->where(array('date(payment.date_created)' => $now, 'kategori_keuangan.sekolah_id' => $sekolah_id));
        $sql = $this->db->get();
        return $sql;

		// $sql = $this->db->query("SELECT
		// 						  nis,
		// 						  payment.id AS payment_id,
		// 						  nama_siswa,
		// 						  kelas,
		// 						  kelas_sekolah.group,
		// 						  nama_kategori,
		// 						  amount,
		// 						  bulan_id,
		// 						  username,
		// 						  payment.date_created,
		// 						  nama_jurusan,
		// 						  siswa_id,
		// 						  kategori_keuangan.biaya
		// 						FROM
		// 						  payment
		// 						  JOIN siswa
		// 						    ON payment.siswa_id = siswa.id
		// 						  JOIN kategori_keuangan
		// 						    ON payment.kategori_keuangan_id = kategori_keuangan.id
		// 						  JOIN kelas_sekolah
		// 						    ON siswa.kelas_sekolah_id = kelas_sekolah.id
		// 						  JOIN kelas
		// 						    ON kelas_sekolah.kelas_id = kelas.id
		// 						  JOIN cms_user
		// 						    ON payment.cms_user_id = cms_user.id
		// 						  JOIN jurusan
		// 						    ON jurusan.id = kelas_sekolah.jurusan_id
		// 						WHERE jurusan.sekolah_id = '$sekolah_id'
		// 						and payment.date_created like '%$now%'
		// 						GROUP by nis
		// 						ORDER BY nama_siswa ASC ");
		// return $sql;
	}


	public function studentPaymentPerday($sekolah_id, $now)
	{	
		$this->db->select("payment.siswa_id");
		$this->db->from('payment');
        $this->db->join('kategori_keuangan', 'kategori_keuangan.id = payment.kategori_keuangan_id');
        $this->db->where(array('date(payment.date_created)' => $now, 'payment.flag' => 'show', 'kategori_keuangan.sekolah_id' => $sekolah_id));
        $this->db->group_by('payment.siswa_id');
        $this->db->order_by('payment.siswa_id', 'DESC');
        $sql = $this->db->get();
        return $sql;
	}


	public function studentPaymentPermonth($sekolah_id, $month)
	{	
		$this->db->select("payment.siswa_id");
		$this->db->from('payment');
        $this->db->join('kategori_keuangan', 'kategori_keuangan.id = payment.kategori_keuangan_id');
        $this->db->where(array('payment.flag' => 'show', 'kategori_keuangan.sekolah_id' => $sekolah_id));
        $this->db->like('date(payment.date_created)',$month);
        $this->db->group_by('payment.siswa_id');
        $this->db->order_by('payment.siswa_id', 'DESC');
        $sql = $this->db->get();
        return $sql;
	}


	public function studentPaymentPermonthOnClass($kelas_sekolah_id, $semester1, $semester2)
	{	
		$this->db->select("payment.siswa_id");
		$this->db->from('payment');
        $this->db->join('siswa', 'siswa.id = payment.siswa_id');
        $this->db->where(array('payment.flag' => 'show', 'siswa.kelas_sekolah_id' => $kelas_sekolah_id));
        // $this->db->like('date(payment.date_created)',$month);
        $this->db->where("date(payment.date_created) BETWEEN '$semester1' AND '$semester2'");
        $this->db->group_by('payment.siswa_id');
        $this->db->order_by('payment.siswa_id', 'DESC');
        $sql = $this->db->get();
        return $sql;
	}



	public function print_harian_lainnya($sekolah_id,$now=null)
	{
		if (empty($now)) {
			$now = date('Y-m-d');
		}

		$sql = $this->db->query("SELECT
								  nama_kategori,
								  keterangan,
								  FORMAT(payment_lainnya.amount,0) as Jumlah
								FROM
								  payment_lainnya
								  JOIN kategori_keuangan_lainnya
								  on kategori_keuangan_lainnya.id=payment_lainnya.kategori_keuangan_lainnya_id
								WHERE sekolah_id = '$sekolah_id'
								and payment_lainnya.date_created like '%$now%'
								ORDER BY payment_lainnya.date_created ASC ");

		$sql2 = $this->db->query("SELECT
								  sum(amount) as total_lainnya
								FROM
								  payment_lainnya
								WHERE sekolah_id = '$sekolah_id'
								and date_created like '%$now%'
								ORDER BY date_created ASC ");

		$data = array(	'sql1' => $sql,
						'sql2'	=> $sql2);

		return $data;
	}


	public function print_bulanan_lainnya($sekolah_id,$month)
	{
		$sql = $this->db->query("SELECT
								  nama_kategori,
								  keterangan,
								  FORMAT(payment_lainnya.amount,0) as Jumlah
								FROM
								  payment_lainnya
								  JOIN kategori_keuangan_lainnya
								  on kategori_keuangan_lainnya.id=payment_lainnya.kategori_keuangan_lainnya_id
								WHERE sekolah_id = '$sekolah_id'
								and payment_lainnya.date_created like '%$month%'
								ORDER BY payment_lainnya.date_created ASC ");

		$sql2 = $this->db->query("SELECT
								  sum(amount) as total_lainnya
								FROM
								  payment_lainnya
								WHERE sekolah_id = '$sekolah_id'
								and date_created like '%$month%'
								ORDER BY date_created ASC ");

		$data = array(	'sql1' => $sql,
						'sql2'	=> $sql2);

		return $data;
	}


	public function list_harian_in_lainnya($sekolah_id,$now=null)
	{
		if (!$now) {
			$now = date('Y-m-d');
		}

		$sql = $this->db->query("SELECT
								  DATE_FORMAT(payment_lainnya.date_created,'%d-%m-%Y') as date_created,
								  DATE_FORMAT(payment_lainnya.date_updated,'%d-%m-%Y') as date_updated,
								  nama_kategori,
								  keterangan,
								  amount,
								  payment_lainnya.id
								FROM
								  payment_lainnya
								  JOIN kategori_keuangan_lainnya
								  on kategori_keuangan_lainnya.id=payment_lainnya.kategori_keuangan_lainnya_id
								WHERE sekolah_id = '$sekolah_id'
								and payment_lainnya.date_created like '%$now%'
								ORDER BY payment_lainnya.date_created ASC ");
		// echo $this->db->last_query();
		$sql2 = $this->db->query("SELECT
								  sum(amount) as total
								FROM
								  payment_lainnya
								WHERE sekolah_id = '$sekolah_id'
								and date_created like '%$now%'
								ORDER BY date_created ASC ");

		$data = array(	'sql1' => $sql,
						'sql2'	=> $sql2);
		return $data;
	}


	public function list_bulanan_in_lainnya($sekolah_id,$month)
	{
		$sql = $this->db->query("SELECT
								  payment_lainnya.date_created,
								  nama_kategori,
								  keterangan,
								  amount,
								  payment_lainnya.id
								FROM
								  payment_lainnya
								  JOIN kategori_keuangan_lainnya
								  on kategori_keuangan_lainnya.id=payment_lainnya.kategori_keuangan_lainnya_id
								WHERE sekolah_id = '$sekolah_id'
								and payment_lainnya.date_created like '%$month%'
								ORDER BY payment_lainnya.date_created ASC ");

		$sql2 = $this->db->query("SELECT
								  sum(amount) as total
								FROM
								  payment_lainnya
								WHERE sekolah_id = '$sekolah_id'
								and date_created like '%$month%'
								ORDER BY date_created ASC ");

		$data = array(	'sql1' => $sql,
						'sql2'	=> $sql2);
		return $data;
	}


	public function list_kas_akhir_tahun($sekolah_id)
	{
		$sql = $this->db->query("SELECT
								  payment_lainnya.date_created,
								  nama_kategori,
								  keterangan,
								  amount,
								  payment_lainnya.id
								FROM
								  payment_lainnya
								  JOIN kategori_keuangan_lainnya
								  on kategori_keuangan_lainnya.id=payment_lainnya.kategori_keuangan_lainnya_id
								WHERE sekolah_id = '$sekolah_id'
								and payment_lainnya.kategori_keuangan_lainnya_id ='3'
								and payment_lainnya.date_created like '%2015%'
								ORDER BY payment_lainnya.date_created ASC ");

		$sql2 = $this->db->query("SELECT
								  sum(amount) as total
								FROM
								  payment_lainnya
								WHERE sekolah_id = '$sekolah_id'
								and payment_lainnya.kategori_keuangan_lainnya_id ='3'
								and payment_lainnya.date_created like '%2015%'
								ORDER BY date_created ASC ");

		$data = array(	'sql1' => $sql,
						'sql2'	=> $sql2);
		return $data;
	}


	public function list_bulanan_in($kelas_sekolah_id)
	{
		$sekolah_id = $this->session->sekolah_id;
		$tahun_ajar = $this->tahun_ajar()->row('tahun_ajar');
		$tahun_pelajaran 	= explode('-', $tahun_ajar);
		$semester1 			= $tahun_pelajaran['0'].'-07-01';
		$semester2 			= $tahun_pelajaran['1'].'-06-30';

		$sql = $this->db->query("SELECT
								  nis,
								  payment.id AS payment_id,
								  nama_siswa,
								  kelas,
								  kelas_sekolah.group,
								  nama_kategori,
								  amount,
								  bulan_id,
								  username,
								  payment.date_created,
								  nama_jurusan,
								  siswa_id,
								  kategori_keuangan.biaya
								FROM
								  payment
								  JOIN siswa
								    ON payment.siswa_id = siswa.id
								  JOIN kategori_keuangan
								    ON payment.kategori_keuangan_id = kategori_keuangan.id
								  JOIN kelas_sekolah
								    ON siswa.kelas_sekolah_id = kelas_sekolah.id
								  JOIN kelas
								    ON kelas_sekolah.kelas_id = kelas.id
								  JOIN cms_user
								    ON payment.cms_user_id = cms_user.id
								  JOIN jurusan
								    ON jurusan.id = kelas_sekolah.jurusan_id
									#JOIN bulan
									# 	ON bulan.id = payment.bulan_id
								WHERE jurusan.sekolah_id = '$sekolah_id'
								and kelas_sekolah.id='$kelas_sekolah_id'
								and date(payment.date_created) between '$semester1' and '$semester2'
								GROUP by nis
								ORDER BY nama_siswa ASC ");
		return $sql;
	}


	public function list_bulanan_out($now,$sekolah_id=null)
	{
		if (!$sekolah_id) {
			$sekolah_id = $this->session->sekolah_id;
		}

		$sql = $this->db->query("SELECT
								  kategori_keuangan_pengeluaran.nama_kategori,
								  kategori_keuangan_pengeluaran.id as kid,
								  keterangan,
								  amount,
								  payment_out.id as payid,
								  DATE_FORMAT(payment_out.date_created,'%d-%m-%Y') as date_created,
								  DATE_FORMAT(payment_out.date_updated,'%d-%m-%Y') as date_updated
								FROM
								  payment_out
								  JOIN kategori_keuangan_pengeluaran
								    ON kategori_keuangan_pengeluaran.id = payment_out.kategori_keuangan_pengeluaran_id
								WHERE payment_out.date_created LIKE '%$now%'
								AND payment_out.sekolah_id = '$sekolah_id'
								order by date_created desc");
		return $sql;
	}


	public function list_bulanan_out_for_yayasan($now,$sekolah_id)
	{
		$sql = $this->db->query("SELECT
								  kategori_keuangan_pengeluaran.nama_kategori,
								  keterangan,
								  amount,
								  left(payment_out.date_created,10) as date_created
								FROM
								  payment_out
								  JOIN kategori_keuangan_pengeluaran
								    ON kategori_keuangan_pengeluaran.id = payment_out.kategori_keuangan_pengeluaran_id
								WHERE
								payment_out.date_created LIKE '%$now%'
								AND payment_out.sekolah_id = '$sekolah_id'
								order by date_created asc");
		return $sql;
	}


	public function list_kategori_keuangan_non_spp_table($sekolah_id)
	{
		$sql = $this->db->query("SELECT
								  DISTINCT(nama_kategori)
								FROM
								  kategori_keuangan
								WHERE sekolah_id = '$sekolah_id'
								  AND nama_kategori != 'SPP'
								ORDER BY nama_kategori ASC");
		return $sql;
	}


	public function list_kategori_keuangan_non_spp($sekolah_id)
	{
		$sql = $this->db->query("SELECT
								  nama_kategori,
								  kategori_keuangan.id,
								  biaya
								FROM
								  kategori_keuangan
								  JOIN jurusan
								    ON jurusan.id = kategori_keuangan.jurusan_id
								  JOIN sekolah
								    ON sekolah.id = jurusan.sekolah_id
								WHERE jurusan.sekolah_id = '$sekolah_id'
								  AND nama_kategori != 'SPP'
								ORDER BY nama_kategori ASC");
		return $sql;
	}


	public function list_pengeluaran()
	{
		$sql = $this->db->query("SELECT id,nama_kategori FROM kategori_keuangan_pengeluaran ORDER BY nama_kategori ASC");
		return $sql;
	}


	public function list_bulan()
	{
		$sql = $this->db->query("select id,bulan,bulan_in_code from bulan order by id asc");
		return $sql;
	}


	public function list_penerimaan_lainnya()
	{
		$sql = $this->db->query("select * from kategori_keuangan_lainnya order by id asc");
		return $sql;
	}


	public function get_nama_kategori_keuangan2($id)
	{
		$sql = $this->db->query("SELECT nama_kategori from kategori_keuangan where id='$id'");
		return $sql;
		// $this->db->Select('nama_kategori');
		// $sql = $this->db->get_where('kategori_keuangan',$condition);
		// return $sql;
	}


	public function get_nama_kategori_keuangan($kategori_keuangan_id)
	{
		$sql = $this->db->query("SELECT nama_kategori from kategori_keuangan where id='$kategori_keuangan_id'");
		return $sql;
	}


	public function get_payment_annualy($nama_kategori)
	{
		$nama_kategori_span = substr($nama_kategori, 0,12);
		if (($nama_kategori=='SPP')or($nama_kategori_span == 'SPP Semester')) {
			$sql = $this->db->query("select id,bulan as annualy from bulan order by id asc")->result();
			return $sql;
		}
		// jika span ingin hanya list bulan yang sedang berjalan dimunculkan, maka aktifkan komentar dibawah
		// else if($nama_kategori_span == 'SPP Semester'){
		// 	$current_month = date('m');
		// 	$sql = $this->db->query("select id,bulan as annualy from bulan where bulan_in_code = '$current_month'")->result();
		// 	return $sql;
		// }
		// else if($nama_kategori =='Mid' or $nama_kategori=='Semester'){
		// 	$sql = $this->db->query("select id, keterangan as annualy from tahun_ajaran order by keterangan asc")->result();
		// 	return $sql;
		// }
		else {
			return $data = '';
		}
	}


	public function get_payment_category($data)
	{
		$sekolah_id = $this->session->sekolah_id;
		$jurusan_id = $data['jurusan_id'];
		$tahun_masuk= $data['tahun_masuk'];
		$gender		= $data['gender'];
		$semester 	= $data['semester_id'];
		if ($semester < 2) {
			$before_semester = '1';
		}else{
			if ($semester % 2 == 0) {
				$before_semester = $semester - 1;
			}else{
				$before_semester = $semester - 1;
			}
		}

		if ($gender=='L') {
			$gender = 'P';
		}else if ($gender == 'P'){
			$gender = 'L';
		}

		$sql = $this->db->query("SELECT
								  nama_kategori,
								  biaya,
								  jurusan_id,
								  tahun_masuk,
								  gender,
								  kategori_keuangan.id
								FROM
								  kategori_keuangan
								WHERE (
								    jurusan_id = '$jurusan_id'
								    OR ISNULL(jurusan_id)
								  )
								  AND (
								    (
								      tahun_masuk = '$tahun_masuk'
								      OR ISNULL(tahun_masuk)
								    )
								    AND (
								      gender != '$gender'
								      OR (ISNULL(gender)
								        AND ISEMPTY(gender))
								    )
									AND
								    (ISNULL(semester_id) OR semester_id='0' OR semester_id='$semester' OR semester_id='$before_semester')
								  )
								And sekolah_id = '$sekolah_id'");
		
		if ($sql->num_rows() > 0) return $sql->result();
	}


	public function edit_siswa($id)
	{
		$sql = $this->db->query("SELECT
								  nis,
								  nama_siswa,
								  kelas,
								  nama_jurusan,
								  siswa.date_created,
								  siswa.date_updated,
								  username,
								  kelas_sekolah.group,
								  siswa.id,
								  kelas_sekolah.id as kelas_sekolah_id
								FROM
								  siswa
								  JOIN kelas_sekolah
								    ON kelas_sekolah.id = siswa.kelas_id
								  JOIN jurusan
								    ON kelas_sekolah.jurusan_id = jurusan.id
								  JOIN kelas
								    ON kelas_sekolah.kelas_id = kelas.id
								  JOIN cms_user
								  	ON cms_user.id=siswa.cms_user_id
								where siswa.id='$id'");
		return $sql;
	}


	public function add_payment_in()
	{

		$now                  = $this->time();
		$siswa_id             = $this->input->post('siswa_id');
		$kategori_keuangan_id = $this->input->post('kategori_keuangan_id');
		$amount               = $this->input->post('amount');
		$annualy              = $this->input->post('annualy');
		$cms_user_id          = $this->session->user_id;
		
		$nama_kategori = $this->get_nama_kategori_keuangan2($kategori_keuangan_id)->row('nama_kategori');

		$data = array(
				'siswa_id'             => $siswa_id,
				'kategori_keuangan_id' => $kategori_keuangan_id,
				'amount'               => $amount,
				'date_created'         => $now,
				'cms_user_id'          => $cms_user_id,
				'flag'                 => 'show'
		);

		$condition = array(
			'kategori_keuangan_id' => $kategori_keuangan_id,
			'date(date_created)'   => date_format(date_create($now),"Y-m-d"),
			'siswa_id'             => $siswa_id,
			'flag'                 => 'show');

		$nama_kategori_spp_span = substr($nama_kategori, 0, 12);
		if ($nama_kategori == 'SPP' or $nama_kategori_spp_span == 'SPP Semester') {
			$tahun_ajar 		= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
			$tahun_pelajaran 	= explode('-', $tahun_ajar);
			$semester1 			= $tahun_pelajaran['0'];
			$semester2 			= $tahun_pelajaran['1'];
			// if ($annualy >= 7 ) {
			// 	$current_semester = $semester2;
			// } else {
			// 	$current_semester = $semester1;
			// }
			$current_semester      = $this->input->post('years');
			$data['bulan_id']      = $annualy;
			$data['tahun']         = $current_semester;
			$condition['bulan_id'] = $annualy;
			$condition['tahun']    = $current_semester;
		}

		$exist = $this->crud->paid($condition);
		if ($exist->num_rows() > 0) {
			$data['amount'] = $amount + $exist->row()->paid;
			$this->db->update('payment', array('flag' => 'hide'), $condition);
			$this->db->insert('payment', $data);
		} else {
			$this->db->insert('payment', $data);
		}
	}


	public function add_payment_in_lainnya()
	{
		date_default_timezone_set('Asia/Jakarta');
		$now                       = date('Y-m-d H:i:s');
		$kategori_keuangan_lainnya = $this->input->post('kategori_keuangan_id');
		$amount                    = $this->input->post('amount');
		$keterangan                = $this->input->post('keterangan');
		$date_entry3               = $this->input->post('date_entry');

		if (!$date_entry3) {
			$date_entry = $now;
		}else{
			$date_entry = $date_entry3;
		}

		$cms_user_id = $this->session->user_id;
		$sekolah_id  = $this->session->sekolah_id;

		$data = array(
		        'kategori_keuangan_lainnya_id'	=> $kategori_keuangan_lainnya,
		        'amount'		=> $amount,
		        'date_created'	=> $date_entry,
		        'cms_user_id'	=> $cms_user_id,
		        'sekolah_id'	=> $sekolah_id,
		        'keterangan'	=> $keterangan
		);

		if ($kategori_keuangan_lainnya == '3') {
			$data['date_created'] = '2015-12-31';
		}

		$this->db->insert('payment_lainnya', $data);
	}


	public function edit_payment_in_lainnya()
	{
		$now 		= $this->time();
		$kategori_keuangan_lainnya = $this->input->post('kategori_keuangan_id');
		$amount		= $this->input->post('amount');
		$keterangan	= $this->input->post('keterangan');
		$date_entry3= $this->input->post('date_entry');
		if (empty($date_entry3)) {
			$date_entry = $now;
		}else{
			$date_entry2= explode('-', $date_entry3);
			$date_entry = $date_entry2['2'].'-'.$date_entry2['1'].'-'.$date_entry2['0'];
		}

		$cms_user_id= $this->session->user_id;
		$sekolah_id = $this->session->sekolah_id;
		$id 		= $this->input->post('id');

		$data = array(
		        'kategori_keuangan_lainnya_id'	=> $kategori_keuangan_lainnya,
		        'amount'		=> $amount,
		        'cms_user_id'	=> $cms_user_id,
		        'sekolah_id'	=> $sekolah_id,
		        'keterangan'	=> $keterangan,
		        'date_created'	=> $date_entry
		);

		$this->db->where('id', $id);
		$this->db->update('payment_lainnya', $data);
	}


	public function add_payment_in_yayasan()
	{
		$now 		= $this->time();
		$amount		= $this->input->post('amount');
		$keterangan	= $this->input->post('keterangan');
		$cms_user_id= $this->session->user_id;

		$data = array(
		        'kategori_keuangan_id'	=> '207',
		        'amount'		=> $amount,
		        'date_created'	=> $now,
		        'cms_user_id'	=> $cms_user_id,
		        'flag'			=> 'show'
		);

		$this->db->insert('payment', $data);
	}


	public function add_payment_out()
	{
		$now 		= $this->time();
		$kategori_pengeluaran_id = $this->input->post('kategori_pengeluaran');
		$keterangan	= $this->input->post('keterangan');
		$amount		= $this->input->post('amount');
		$cms_user_id= $this->session->user_id;
		$sekolah_id = $this->session->sekolah_id;

		$data = array(
		        'kategori_keuangan_pengeluaran_id' => $kategori_pengeluaran_id,
		        'keterangan'	=> $keterangan,
		        'amount'		=> $amount,
		        'date_created'	=> $now,
		        'cms_user_id'	=> $cms_user_id,
		        'sekolah_id'	=> $sekolah_id
		);

		$this->db->insert('payment_out', $data);
	}


	public function add_payment_yayasan_out()
	{
		$now 		= $this->time();
		$kategori_pengeluaran_id = $this->input->post('kategori_pengeluaran');
		$keterangan	= $this->input->post('keterangan');
		$amount		= $this->input->post('amount');
		$cms_user_id= $this->session->user_id;
		$date_entry3= $this->input->post('date_entry');
		if (!$date_entry3) {
			$date_entry = $now;
		}else{
			$date_entry = $date_entry3;
		}

		$data = array(
		        'kategori_keuangan_pengeluaran_id' => $kategori_pengeluaran_id,
		        'keterangan'	=> $keterangan,
		        'amount'		=> $amount,
		        'date_created'	=> $date_entry,
		        'cms_user_id'	=> $cms_user_id,
		        'sekolah_id'	=> '9'
		);

		$this->db->insert('payment_out', $data);
	}


	public function check_payment2()
	{
		$year = date('Y');
		$kategori_keuangan_id	= $this->input->post('kategori_keuangan_id');
		$amount					= $this->input->post('amount');
		$annualy				= $this->input->post('annualy');
		$siswa_id				= $this->input->post('siswa_id');

		$nama_kategori 			= $this->get_nama_kategori_keuangan($kategori_keuangan_id)->row('nama_kategori');
		if ($nama_kategori == 'SPP') {
			$tahun_ajar 		= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
			$tahun_pelajaran 	= explode('-', $tahun_ajar);
			$semester1 			= $tahun_pelajaran['0'];
			$semester2 			= $tahun_pelajaran['1'];
			if ($annualy >= 7 ) {
				$current_semester = $semester2;
			} else {
				$current_semester = $semester1;
			}
			$data['bulan_id']	= $annualy;
			$data['tahun']		= $current_semester;
			$paid 	= $this->db->query("SELECT SUM(amount) as paid FROM payment WHERE bulan_id='$annualy' AND tahun='$current_semester' AND kategori_keuangan_id='$kategori_keuangan_id' AND siswa_id='$siswa_id'")->row('paid');
		}else if($nama_kategori == 'Prakerin' or $nama_kategori == 'Komite'){
			$paid 	= $this->db->query("SELECT SUM(amount) as paid FROM payment WHERE kategori_keuangan_id='$kategori_keuangan_id' AND siswa_id='$siswa_id'")->row('paid');
		}else if($nama_kategori == 'Perpisahan'){
			$paid 	= $this->db->query("SELECT SUM(amount) as paid FROM payment WHERE kategori_keuangan_id='$kategori_keuangan_id' AND siswa_id='$siswa_id'")->row('paid');
		}else{
			$nama_kategori = substr($nama_kategori, 0, 12);
			if ($nama_kategori == 'SPP Semester') {
				$paid 	= $this->db->query("SELECT SUM(amount) as paid FROM payment WHERE kategori_keuangan_id='$kategori_keuangan_id' AND siswa_id='$siswa_id'")->row('paid');
			}else{
				$paid   = 0;
			}
		}

		$sql  = $this->db->query("SELECT biaya FROM kategori_keuangan WHERE id='$kategori_keuangan_id'")->row('biaya');

		if ($amount > $sql) {
			return false;
		}else{
			$paid2 = $paid+$amount;
			if ($paid2 > $sql) {
				return false;
			} else {
				return true;
			}
		}
	}


	public function check_payment()
	{
		$this->load->model('crud_m', 'crud');
		$current_semester     = $this->input->post('years');
		$kategori_keuangan_id = $this->input->post('kategori_keuangan_id');
		$amount               = $this->input->post('amount');
		$annualy              = $this->input->post('annualy');
		$siswa_id             = $this->input->post('siswa_id');

		$nama_kategori = $this->db->get_where('kategori_keuangan', array('id' => $kategori_keuangan_id))->row()->nama_kategori;

		// $tahun_ajar      = $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		// $tahun_pelajaran = explode('-', $tahun_ajar);
		// $semester1       = $tahun_pelajaran['0'];
		// $semester2       = $tahun_pelajaran['1'];
		// if ($annualy >= 7 ) {
		// 	$current_semester = $semester2;
		// } else {
		// 	$current_semester = $semester1;
		// }
		
		switch ($nama_kategori) {
			case 'SPP':
				$data      = array('bulan_id' => $annualy, 'tahun' => $current_semester);
				$condition = array(
					'bulan_id'             => $annualy, 
					'tahun'                => $current_semester, 
					'kategori_keuangan_id' => $kategori_keuangan_id, 
					'siswa_id'             => $siswa_id,
					'flag'                 => 'show');
				$paid = $this->crud->paid($condition)->row()->paid;
				break;
			
			case 'Prakerin':
			case 'Komite':
			case 'Perpisahan':
			case 'Pendaftaran Ulang':
			case 'PSB':
			case 'Ujian Pra USEK':
			case 'Ujian Pra UN':
			case 'UAS':
			case 'UAN':
				$condition = array(
					'kategori_keuangan_id' => $kategori_keuangan_id,
					'siswa_id'             => $siswa_id,
					'flag'                 => 'show');
				$paid = $this->crud->paid($condition)->row()->paid;
				break;

			case 'Semester Ganjil':
			case 'Semester Genap':
			case 'Mid Ganjil':
			case 'Mid Genap':
				$condition = array(
					'kategori_keuangan_id' => $kategori_keuangan_id,
					'siswa_id'             => $siswa_id,
					'flag'                 => 'show',
					'tahun'                => $current_semester);
				$paid = $this->crud->paid($condition)->row()->paid;
				break;

			case 'SPP Semester 1':
			case 'SPP Semester 2':
			case 'SPP Semester 3':
			case 'SPP Semester 4':
			case 'SPP Semester 5':
			case 'SPP Semester 6':
				$condition = array(
					'siswa_id'             => $siswa_id,
					'flag'                 => 'show',
					'kategori_keuangan_id' => $kategori_keuangan_id);
				$conditionOr = array(
					'currentYear' => $current_semester,
					'afterYear'   => $current_semester+1,
					'beforeYear'  => $current_semester-1);
				$paid = $this->crud->paid($condition, $conditionOr)->row()->paid;
				// var_dump($paid);
				// echo $this->db->last_query();
				// exit();
				break;

			default:
				$nama_kategori = substr($nama_kategori, 0, 12);
				switch ($nama_kategori) {
					case 'SPP Semester':
						$condition = array(
							'kategori_keuangan_id' => $kategori_keuangan_id,
							'siswa_id'             => $siswa_id,
							'flag'                 => 'show');
						$paid = $this->crud->paid($condition)->row()->paid;
						break;
					
					default:
						$paid = 0;
						break;
				}
				break;
		}
		// echo $this->db->last_query();
		$biaya = $this->crud->get('kategori_keuangan', array('id' => $kategori_keuangan_id))->row()->biaya;

		if ($amount > $biaya) {
			$status = false;
		}else{

			$paymentId = $this->input->post('id');
			if ($paymentId) {
				$valueEdit = $this->crud->get('payment', array('id' => $paymentId))->row()->amount;
				$paid2     = $paid - $valueEdit + $amount;
				$str       = $paid.' - '.$valueEdit.' + '.$amount.' = '.$paid2;
			} else {
				$paid2 = $paid + $amount;
				$str   = $paid.' + '.$amount.' = '.$paid2;
			}
			
			if ($paid2 > $biaya) {
				$status = false;
			} else {
				$status = true;
			}

				$respon = array('data' => $str, 'status' => $status);
				// return $respon;
		} 
		return $status;
	}


	public function check_edit_payment()
	{
		$year = date('Y');
		$kategori_keuangan_id	= $this->input->post('kategori_keuangan_id');
		$amount					= $this->input->post('amount');
		$annualy				= $this->input->post('annualy');
		$siswa_id				= $this->input->post('siswa_id');

		$nama_kategori 			= $this->get_nama_kategori_keuangan($kategori_keuangan_id)->row('nama_kategori');
		if ($nama_kategori == 'SPP') {
			$tahun_ajar 		= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
			$tahun_pelajaran 	= explode('-', $tahun_ajar);
			$semester1 			= $tahun_pelajaran['0'];
			$semester2 			= $tahun_pelajaran['1'];
			if ($annualy >= 7 ) {
				$current_semester = $semester2;
			} else {
				$current_semester = $semester1;
			}
			$data['bulan_id']	= $annualy;
			$data['tahun']		= $current_semester;
			$paid 	= $this->db->query("SELECT SUM(amount) as paid FROM payment WHERE bulan_id='$annualy' AND tahun='$current_semester' AND kategori_keuangan_id='$kategori_keuangan_id' AND siswa_id='$siswa_id'")->row('paid');
		}else if($nama_kategori == 'Prakerin' or $nama_kategori == 'Komite'){
			$paid 	= $this->db->query("SELECT SUM(amount) as paid FROM payment WHERE kategori_keuangan_id='$kategori_keuangan_id' AND siswa_id='$siswa_id'")->row('paid');
		}else if($nama_kategori == 'Perpisahan'){
			$paid 	= $this->db->query("SELECT SUM(amount) as paid FROM payment WHERE kategori_keuangan_id='$kategori_keuangan_id' AND siswa_id='$siswa_id'")->row('paid');
		}else{
			$nama_kategori = substr($nama_kategori, 0, 12);
			if ($nama_kategori == 'SPP Semester') {
				$paid 	= $this->db->query("SELECT SUM(amount) as paid FROM payment WHERE kategori_keuangan_id='$kategori_keuangan_id' AND siswa_id='$siswa_id'")->row('paid');
			}else{
				$paid   = 0;
			}
		}

		$sql  = $this->db->query("SELECT biaya FROM kategori_keuangan WHERE id='$kategori_keuangan_id'")->row('biaya');

		if ($amount > $sql) {
			return false;
		}else{
			return true;
		}
	}


	public function get_total_in_harian_per_siswa($siswa_id)
	{
		$sql = $this->db->query("SELECT SUM(amount) FROM payment WHERE siswa_id='$siswa_id'");
		return $sql;
	}


	public function get_total_in_harian($sekolah_id,$now=null)
	{
		if (!$now) {
			$now = date('Y-m-d');
		}
		$sql = $this->db->query("SELECT
								  SUM(amount) AS total_harian
								FROM
								  payment
								  JOIN kategori_keuangan
								    ON kategori_keuangan.id = payment.kategori_keuangan_id
								WHERE payment.date_created LIKE '%$now%'
								  AND sekolah_id = '$sekolah_id'
								  AND flag = 'show'");
		return $sql;
	}


	public function get_total_in_tahunan($sekolah_id, $tahun_ajar)
	{
		$tahun_pelajaran = explode('-', $tahun_ajar);
		$semester1       = $tahun_pelajaran['0'];
		$semester2       = $tahun_pelajaran['1'];

		$sql = $this->db->query("SELECT
								  SUM(amount) AS total_harian
								FROM
								  payment
								  JOIN kategori_keuangan
								    ON kategori_keuangan.id = payment.kategori_keuangan_id
								WHERE payment.date_created between '".$semester1."-07-01' and '".$semester2."-06-30'
								  AND sekolah_id = '$sekolah_id'");
		return $sql;
	}


	public function get_total_in_tahunan_lainnya($sekolah_id, $tahun_ajar)
	{
		$tahun_pelajaran = explode('-', $tahun_ajar);
		$semester1       = $tahun_pelajaran['0'];
		$semester2       = $tahun_pelajaran['1'];

		$sql = $this->db->query("SELECT
								  SUM(amount) AS total_harian
								FROM
								  payment_lainnya
								WHERE date_created between '".$semester1."-07-01' and '".$semester2."-06-30'
								  AND sekolah_id = '$sekolah_id'");
		return $sql;
	}


	public function get_total_out_tahunan($sekolah_id,$tahun_ajar)
	{
		$tahun_pelajaran = explode('-', $tahun_ajar);
		$semester1       = $tahun_pelajaran['0'];
		$semester2       = $tahun_pelajaran['1'];
		
		$sql = $this->db->query("SELECT SUM(amount) AS total_harian_out FROM payment_out WHERE date_created between '".$semester1."-07-01' and '".$semester2."-06-30' AND sekolah_id = '$sekolah_id'");
		return $sql;
	}



	public function get_total_in_harian_lainnya($sekolah_id,$now=null)
	{
		if (!$now) {
			$now = date('Y-m-d');
		}
		$sql = $this->db->query("SELECT
								  SUM(amount) AS total_harian
								FROM
								  payment_lainnya
								WHERE date(payment_lainnya.date_created) LIKE '%$now%'
								  AND sekolah_id = '$sekolah_id'");
		return $sql;
	}


	public function get_total_out_harian($sekolah_id,$now=null)
	{
		if (!$now) {
			$now = date('Y-m-d');
		}
		$sql = $this->db->query("SELECT SUM(amount) AS total_harian_out FROM payment_out WHERE date_created LIKE '%$now%' AND sekolah_id='$sekolah_id'");
		return $sql;
	}


	public function get_total_in_bulanan($kelas_sekolah_id)
	{
		$tahun_ajar 		= $this->tahun_ajar()->row('tahun_ajar');
		$tahun_pelajaran 	= explode('-', $tahun_ajar);
		$semester1 			= $tahun_pelajaran['0'].'-07-01';
		$semester2 			= $tahun_pelajaran['1'].'-06-30';
		$sql = $this->db->query("SELECT
								  SUM(amount) as total_bulanan
								FROM
								  payment
								  JOIN siswa
								    ON siswa.id = payment.siswa_id
								  JOIN kelas_sekolah
								    ON kelas_sekolah.id = siswa.kelas_sekolah_id
								WHERE kelas_sekolah_id = '$kelas_sekolah_id'
								AND date(payment.date_created) between '$semester1' and '$semester2'");
		return $sql;
	}


	public function get_total_in_bulanan_yayasan($sekolah_id,$month)
	{
		$sql = $this->db->query("
								SELECT
								  SUM(payment.amount) AS Total
								FROM
								  siswa
								  JOIN kelas_sekolah
								    ON kelas_sekolah.id = siswa.kelas_sekolah_id
								  JOIN kelas
								    ON kelas.id = kelas_sekolah.kelas_id
								  JOIN jurusan
								    ON jurusan.id = kelas_sekolah.jurusan_id
								  JOIN sekolah
								    ON sekolah.id = jurusan.sekolah_id
								  JOIN payment
								    ON payment.siswa_id = siswa.id
								  JOIN kategori_keuangan
								    ON kategori_keuangan.id = payment.kategori_keuangan_id
								  LEFT JOIN bulan
								    ON bulan.id = payment.bulan_id
								WHERE DATE(payment.date_created) LIKE '%$month%'
								  AND sekolah.id = '$sekolah_id'");
		return $sql;
	}


	public function get_total_out_bulanan($now,$sekolah_id=null)
	{
		$sekolah_id = $this->session->sekolah_id;
		$sql = $this->db->query("SELECT SUM(amount) AS total_bulanan_out FROM payment_out WHERE date_created LIKE '%$now%' AND sekolah_id='$sekolah_id'");
		return $sql;
	}


	public function get_total_out_bulanan_for_yayasan($now,$sekolah_id)
	{
		$sql = $this->db->query("SELECT SUM(amount) AS total_bulanan_out FROM payment_out WHERE date_created LIKE '%$now%' AND sekolah_id='$sekolah_id'");
		return $sql;
	}


	public function delete_payment_out($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('payment_out');
	}


	public function edit_payment_out()
	{
		$now                     = $this->time();
		$id                      = $this->input->post('id');
		$kategori_pengeluaran_id = $this->input->post('kategori_pengeluaran');
		$keterangan              = $this->input->post('keterangan');
		$amount                  = $this->input->post('amount');
		$date_entry3             = $this->input->post('date_entry');
		
		if (!$date_entry3) {
			$date_entry = $now;
		}else{
			$date_entry2= explode('-', $date_entry3);
			$date_entry = $date_entry2['2'].'-'.$date_entry2['1'].'-'.$date_entry2['0'];
		}

		$cms_user_id= $this->session->user_id;

		$data = array(
		        'kategori_keuangan_pengeluaran_id' => $kategori_pengeluaran_id,
		        'keterangan'	=> $keterangan,
		        'amount'		=> $amount,
		        'cms_user_id'	=> $cms_user_id,
		        'date_created'	=> $date_entry
		);

		$this->db->where('id', $id);
		$this->db->update('payment_out', $data);
	}


	public function get_payment_id($payment_id)
	{
		$sql = $this->db->query("SELECT
								  kelas_sekolah.id AS kelas_sekolah_id,
								  kelas.kelas,
								  jurusan.nama_jurusan,
								  siswa.nama_siswa,
								  siswa.id AS siswa_id,
								  kategori_keuangan.nama_kategori,
								  kategori_keuangan.id AS kategori_keuangan_id,
								  bulan.bulan,
								  --bulan.bulan_in_code,
								  payment.bulan_id as bulan_in_code,
								  payment.amount,
								  payment.id as pay_id,
								  payment.tahun
								FROM
								  payment
								  JOIN kategori_keuangan
								    ON kategori_keuangan.id = payment.kategori_keuangan_id
								  JOIN siswa
								    ON siswa.id = payment.siswa_id
								  JOIN kelas_sekolah
								    ON kelas_sekolah.id = siswa.kelas_sekolah_id
								  LEFT JOIN bulan
								    ON bulan.id = payment.bulan_id
								  JOIN jurusan
								    ON jurusan.id = kelas_sekolah.jurusan_id
								  JOIN kelas
								    ON kelas.id = kelas_sekolah.kelas_id
								WHERE payment.id = '$payment_id'
								#GROUP BY payment.id");
		return $sql;
	}


	public function get_payment_id_lainnya($payment_id)
	{
		$sql = $this->db->query("SELECT
								  DATE_FORMAT(payment_lainnya.date_created,'%d-%m-%Y') as date_created,
								  nama_kategori,
								  keterangan,
								  amount,
								  payment_lainnya.id,
								  kategori_keuangan_lainnya.id as kategori_id
								FROM
								  payment_lainnya
								  JOIN kategori_keuangan_lainnya
								  on kategori_keuangan_lainnya.id=payment_lainnya.kategori_keuangan_lainnya_id
								WHERE payment_lainnya.id = '$payment_id'");
		return $sql;
	}


	public function edit_payment_in()
	{
		date_default_timezone_set('Asia/Jakarta');
		$id                   = $this->input->post('id');
		$bulan                = $this->input->post('annualy');
		$kelas                = $this->input->post('kelas');
		$amount               = $this->input->post('amount');
		$siswa                = $this->input->post('siswa_id');
		$kategori_keuangan_id = $this->input->post('kategori_keuangan_id');
		$cms_user_id          = $this->session->user_id; 
		$current_semester     = $this->input->post('years');
		$currentDate          = date('Y-m-d');
		$data = array(
				'kategori_keuangan_id' => $kategori_keuangan_id,
				'siswa_id'             => $siswa,
				'amount'               => $amount,
				'cms_user_id'          => $cms_user_id,
				'tahun'                => $current_semester
		);

		$nama_kategori 			= $this->get_nama_kategori_keuangan($kategori_keuangan_id)->row('nama_kategori');
		switch ($nama_kategori) {
			case 'SPP':
			case 'SPP Semester 1':
			case 'SPP Semester 2':
			case 'SPP Semester 3':
			case 'SPP Semester 4':
			case 'SPP Semester 5':
			case 'SPP Semester 6':
				$data['bulan_id']	= $bulan;
				#UNTUK MENGHAPUS DATA YANG SUDAH PERNAH ADA DENGAN KONDISI YANG SAMA
				$condition = array(
					'kategori_keuangan_id' => $kategori_keuangan_id,
					'siswa_id'             => $siswa,
					'bulan_id'             => $bulan,
					'tahun'                => $current_semester,
					'id != ' => $id,
					'date(date_created)'   => $currentDate
			        );
				$this->db->where($condition);
				$this->db->update('payment', array('flag' => 'hide'));
				break;
			
			case 'Prakerin':
			case 'Komite':
			case 'Perpisahan':
			case 'Pendaftaran Ulang':
			case 'PSB':
			case 'Ujian Pra USEK':
			case 'Ujian Pra UN':
			case 'UAS':
			case 'UAN':
				#UNTUK MENGHAPUS DATA YANG SUDAH PERNAH ADA DENGAN KONDISI YANG SAMA
				$condition = array(
					'kategori_keuangan_id' => $kategori_keuangan_id,
					'siswa_id'             => $siswa,
					'id != ' => $id,
					'date(date_created)'   => $currentDate
			        );
				$this->db->where($condition);
				$this->db->update('payment', array('flag' => 'hide'));
				break;

			case 'Semester Ganjil':
			case 'Semester Genap':
			case 'Mid Ganjil':
			case 'Mid Genap':
				#UNTUK MENGHAPUS DATA YANG SUDAH PERNAH ADA DENGAN KONDISI YANG SAMA
				$condition = array(
					'kategori_keuangan_id' => $kategori_keuangan_id,
					'siswa_id'             => $siswa,
					'tahun'                => $current_semester,
					'id != ' => $id,
					'date(date_created)' => $currentDate
			        );
				$this->db->where($condition);
				$this->db->update('payment', array('flag' => 'hide'));
				break;

			default:
				
				break;
		}

		$this->db->where('id', $id);
		$this->db->update('payment', $data);
	}


	public function delete_harian_in($payment_id)
	{
		$this->db->where('id', $payment_id);
		$this->db->delete('payment');
	}


	public function delete_harian_in_lainnya($payment_id)
	{
		$this->db->where('id', $payment_id);
		$this->db->delete('payment_lainnya');
	}


	public function get_kategori_keuangan_id($payment_id)
	{
		$sql = $this->db->query("SELECT
								  kategori_keuangan_lainnya_id
								FROM
								  payment_lainnya
								WHERE id = '$payment_id'");
		return $sql;
	}


	public function list_permintaan($month,$sekolah_id)
	{
		$sql = $this->db->query("select permintaan.*, sekolah.nama_sekolah from permintaan join sekolah on sekolah.id=permintaan.sekolah_id where permintaan.date_created like '%$month%' and sekolah_id='$sekolah_id' order by date_created DESC");
		$sql2= $this->db->query("select sum(amount) as total_permintaan from permintaan where date_created like '%$month%' and sekolah_id='$sekolah_id'");
		$data['list_permintaan'] = $sql;
		$data['total_permintaan']= $sql2;
		return $data;
	}


	public function list_permintaan_yayasan($month)
	{
		$sql = $this->db->query("select permintaan.*, sekolah.nama_sekolah from permintaan join sekolah on sekolah.id=permintaan.sekolah_id where permintaan.date_created like '%$month%' order by date_created DESC");
		$sql2= $this->db->query("select sum(amount) as total_permintaan from permintaan where date_created like '%$month%'");
		$data['list_permintaan'] = $sql;
		$data['total_permintaan']= $sql2;
		return $data;
	}


	public function delete_permintaan($id)
	{
		$sql = $this->db->query("delete from permintaan where id='$id'");
		return $sql;
	}


	public function edit_permintaan()
	{
		$id			= $this->input->post('id');
		$keterangan	= $this->input->post('keterangan');
		$amount		= $this->input->post('amount');
		$cms_user_id= $this->session->user_id;

		$data = array(
		        'keterangan'	=> $keterangan,
		        'amount'		=> $amount,
		        'cms_user_id'	=> $cms_user_id,
		        'status'		=> 'Terkirim'
		);

		$this->db->where('id', $id);
		$this->db->update('permintaan', $data);
	}


	public function add_permintaan()
	{
		$now 		= $this->time();
		$keterangan	= $this->input->post('keterangan');
		$amount		= $this->input->post('amount');
		$cms_user_id= $this->session->user_id;
		$sekolah_id = $this->session->sekolah_id;

		$data = array(
		        'keterangan'	=> $keterangan,
		        'amount'		=> $amount,
		        'date_created'	=> $now,
		        'cms_user_id'	=> $cms_user_id,
		        'sekolah_id'	=> $sekolah_id,
		        'status'		=> 'Terkirim'
		);

		$this->db->insert('permintaan', $data);
	}


	public function permintaan_diterima($id)
	{
		$cms_user_id= $this->session->user_id;

		$data = array(
		        'cms_user_id'	=> $cms_user_id,
		        'status'		=> 'Diterima'
		);

		$this->db->where('id', $id);
		$this->db->update('permintaan', $data);
	}


	public function permintaan_ditolak($id)
	{
		$cms_user_id= $this->session->user_id;

		$data = array(
		        'cms_user_id'	=> $cms_user_id,
		        'status'		=> 'Ditolak'
		);

		$this->db->where('id', $id);
		$this->db->update('permintaan', $data);
	}


	public function list_pembayaran_komite()
	{
		$sql = $this->db->query("SELECT
								  siswa.nis AS NIS,
								  siswa.nama_siswa AS Nama_Siswa,
								  GROUP_CONCAT(
								    FORMAT(payment.amount, 0),
								    ' ',
								    '<i>',
								    LEFT(payment.date_created, 10),
								    '</i>',
								    '<br>'
								  ) AS Pembayaran,
								  FORMAT(
								    kategori_keuangan.biaya - SUM(payment.amount),
								    0
								  ) AS Sisa,
								  CONCAT_WS(
								    ' ',
								    kelas.kelas,
								    jurusan.nama_jurusan,
								    kelas_sekolah.group
								  ) AS Kelas
								FROM
								  payment
								  JOIN kategori_keuangan
								    ON kategori_keuangan.id = payment.kategori_keuangan_id
								  JOIN siswa
								    ON siswa.id = payment.siswa_id
								  JOIN kelas_sekolah
								    ON kelas_sekolah.id = siswa.kelas_sekolah_id
								  JOIN jurusan
								    ON kelas_sekolah.jurusan_id = jurusan.id
								  JOIN kelas
								    ON kelas.id = kelas_sekolah.kelas_id
								WHERE kategori_keuangan.id IN ('31', '32')
								GROUP BY siswa.nis
								ORDER BY payment.date_created ASC ");
		return $sql;
	}


	//waktu saat ini
    public function time()
    {
		date_default_timezone_set('Asia/Jakarta');
		$sekarang = date('Y-m-d H:i:s');
        return $sekarang;
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
