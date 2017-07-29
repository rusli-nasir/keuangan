<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sekolah_m extends CI_Model {

	public function jenis_kelas($sekolah_id)
	{
		$tingkat_pendidikan = $this->db->query("select tingkat_pendidikan from sekolah where id='$sekolah_id'")->row_array();
		$tingkat 			= $tingkat_pendidikan['tingkat_pendidikan'];
		$this->db->select('kelas,id');
		$sql = $this->db->get_where('kelas', array('tingkat_pendidikan' => $tingkat));
		return $sql;
	}


	public function kelas_siswa($sekolah_id)
	{
		$sql	= $this->db->query("SELECT
									  kelas_sekolah.id,
									  kelas,
									  nama_jurusan,
									  kelas_sekolah.group
									FROM
									  kelas_sekolah
									  JOIN kelas
									    ON kelas_sekolah.kelas_id = kelas.id
									  JOIN jurusan
									    ON kelas_sekolah.jurusan_id = jurusan.id
									WHERE sekolah_id='$sekolah_id' and jurusan.status='show' and kelas_sekolah.status='show'");
		return $sql;
	}


	public function list_sekolah()
	{
		$sekolah_id = $this->session->sekolah_id;
		if ($sekolah_id =='9') {
			$sql = $this->db->query("select id,nama_sekolah from sekolah order by nama_sekolah asc");
		}else{
			$sql = $this->db->query("select id,nama_sekolah from sekolah where id!='9' order by nama_sekolah asc");
		}
		return $sql;
	}


	public function list_semester()
	{
		$sql = $this->db->query("select * from semester order by id asc");
		return $sql;
	}


	public function list_jurusan($sekolah_id)
	{
		$sql = $this->db->query("SELECT
								  nama_jurusan,
								  jurusan.date_updated,
								  jurusan.id
								FROM
								  jurusan
								  JOIN sekolah
								    ON sekolah.id = jurusan.sekolah_id
								WHERE sekolah.id = '$sekolah_id' and jurusan.status='show'
								ORDER BY nama_jurusan ASC");
		return $sql;
	}


	public function list_kelas($sekolah_id)
	{
		$sql = $this->db->query("SELECT
								  kelas_sekolah.id,
								  kelas,
								  nama_jurusan,
								  username,
								  kelas_sekolah.date_created,
								  kelas_sekolah.date_updated,
								  kelas_sekolah.group
								FROM
								  kelas_sekolah
								  JOIN kelas
								    ON kelas_sekolah.kelas_id = kelas.id
								  JOIN jurusan
								    ON kelas_sekolah.jurusan_id = jurusan.id
								  JOIN cms_user
								    ON cms_user.id = kelas_sekolah.cms_user_id
								WHERE jurusan.sekolah_id = '$sekolah_id' and jurusan.status='show' and kelas_sekolah.status='show'
								ORDER BY nama_jurusan ASC");
		return $sql;
	}


	public function get_kelas_for_submenu($sekolah_id)
	{
		//$sql = $this->db->query("SELECT DISTINCT(kelas),kelas_id FROM kelas_sekolah JOIN kelas ON kelas.id=kelas_sekolah.kelas_id join jurusan on jurusan.id=kelas_sekolah.jurusan_id where sekolah_id='$sekolah_id' order by kelas.id asc");
		$sql = $this->db->query("SELECT 
								    kelas.kelas,
								    kelas_sekolah.kelas_id,
								    kelas_sekolah.id,
								    COUNT(kelas_sekolah.group) AS jumlah_kelas,
								    IF(COUNT(kelas_sekolah.group) = '1',
								        CONCAT_WS(' ',
								                kelas.kelas,
								                IF(jurusan.nama_jurusan = '-',
								                    NULL,
								                    jurusan.nama_jurusan)),
								        NULL) AS kelas_jurusan,
								    jurusan.sekolah_id
								FROM
								    kelas
								        JOIN
								    kelas_sekolah ON kelas_sekolah.kelas_id = kelas.id
								        JOIN
								    jurusan ON jurusan.id = kelas_sekolah.jurusan_id
								WHERE
								    jurusan.sekolah_id = '1'
								GROUP BY kelas.kelas , kelas.id , kelas_sekolah.id
								ORDER BY kelas_id ASC");
		return $sql;
	}


	public function edit_jurusan($id)
	{
		$sql = $this->db->query("SELECT
								  nama_jurusan,
								  date_updated,
								  id
								FROM
								  jurusan
								WHERE id = '$id'");
		return $sql;
	}


	public function add_jurusan()
	{
		$now 			= $this->time();
		$nama_jurusan	= $this->input->post('nama_jurusan');
		$sekolah_id 	= $this->session->sekolah_id;

		$data = array(
		        'nama_jurusan' 	=> $nama_jurusan,
		        'date_created' 	=> $now,
		        'sekolah_id'	=> $sekolah_id,
		        'status'		=> 'show'
		);

		$this->db->insert('jurusan', $data);
	}


	public function delete_jurusan($id)
	{
		$data = array(
		        'status' => 'hide'
		);

		$this->db->where('id', $id);
		$this->db->update('jurusan', $data);
	}


	public function update_jurusan($id)
	{
		$nama_jurusan	= $this->input->post('nama_jurusan');

		$data = array(
		        'nama_jurusan' 	=> $nama_jurusan,
		);

		$this->db->where('id', $id);
		$this->db->update('jurusan', $data);
	}


	public function add_kelas()
	{
		$now 		= $this->time();
		$kelas 		= $this->input->post('kelas');
		$jurusan 	= $this->input->post('jurusan');
		$group 		= $this->input->post('group');
		$cms_user_id= $this->session->user_id;

		$data = array(
		        'kelas_id' 		=> $kelas,
		        'jurusan_id'	=> $jurusan,
		        'cms_user_id'	=> $cms_user_id,
		        'group'			=> $group,
		        'date_created' 	=> $now,
		        'status'		=> 'show'
		);

		$this->db->insert('kelas_sekolah', $data);
	}


	public function edit_kelas($id)
	{
		$sql = $this->db->query("SELECT
								  kelas_sekolah.id,
								  kelas,
								  kelas_id,
								  nama_jurusan,
								  jurusan_id,
								  username,
								  cms_user_id,
								  kelas_sekolah.group
								FROM
								  kelas_sekolah
								  JOIN kelas
								    ON kelas_sekolah.kelas_id = kelas.id
								  JOIN jurusan
								    ON kelas_sekolah.jurusan_id = jurusan.id
								  JOIN cms_user
								    ON cms_user.id = kelas_sekolah.cms_user_id
								WHERE kelas_sekolah.id ='$id'");
		return $sql;
	}


	public function update_kelas($id)
	{
		$kelas 		= $this->input->post('kelas');
		$jurusan 	= $this->input->post('jurusan');
		$group 		= $this->input->post('group');
		$cms_user_id= $this->session->user_id;

		$data = array(
		        'kelas_id' 		=> $kelas,
		        'jurusan_id'	=> $jurusan,
		        'cms_user_id'	=> $cms_user_id,
		        'group'			=> $group
		);

		$this->db->where('id', $id);
		$this->db->update('kelas_sekolah', $data);
	}


	public function delete_kelas($id)
	{
		$data = array(
		        'status' => 'hide'
		);

		$this->db->where('id', $id);
		$this->db->update('kelas_sekolah', $data);
	}


	public function get_jurusan_based_on_kelas_sekolah($kelas_sekolah_id)
	{
		$this->db->select('jurusan_id');
		$sql = $this->db->get_where('kelas_sekolah', array('id' => $kelas_sekolah_id));
		return $sql;
	}


	public function get_jurusan_group_based_on_kelas($kelas_id)
	{
		$sql = $this->db->query("SELECT
								  CONCAT_WS(
								    ' ',
								    jurusan.nama_jurusan,
								    kelas_sekolah.group
								  ) AS kelas,
								  kelas_sekolah.id
								FROM
								  kelas_sekolah
								  JOIN jurusan
								    ON kelas_sekolah.jurusan_id = jurusan.id
								  JOIN kelas
								    ON kelas.id = kelas_sekolah.kelas_id
								WHERE kelas.id='$kelas_id'");
		return $sql;
	}


	public function get_nama_sekolah($sekolah_id)
	{
		$sql = $this->db->query("select nama_sekolah from sekolah where id='$sekolah_id'");
		return $sql;
	}


	public function get_kelas_jurusan_group($kelas_sekolah_id)
	{
		$sql = $this->db->query("
								SELECT
								  CONCAT_WS(
								    ' ',
								    kelas.kelas,
								    jurusan.nama_jurusan,
								    kelas_sekolah.group
								  ) AS kelas
								FROM
								  kelas_sekolah
								  JOIN jurusan
								    ON kelas_sekolah.jurusan_id = jurusan.id
								  JOIN kelas
								    ON kelas.id = kelas_sekolah.kelas_id
								WHERE kelas_sekolah.id = '$kelas_sekolah_id'");
		return $sql;
	}


	public function get_jurusan_group($kelas_sekolah_id)
	{
		$sql = $this->db->query("
								SELECT
								  CONCAT_WS(
								    ' ',
								    jurusan.nama_jurusan,
								    kelas_sekolah.group
								  ) AS kelas,
								  kelas_sekolah.id
								FROM
								  kelas_sekolah
								  JOIN jurusan
								    ON kelas_sekolah.jurusan_id = jurusan.id
								  JOIN kelas
								    ON kelas.id = kelas_sekolah.kelas_id
								WHERE kelas_sekolah.id = '$kelas_sekolah_id'");
		return $sql;
	}


	public function get_nama_bulan($bulan_in_code)
	{
		$sql = $this->db->query("select bulan from bulan where bulan_in_code='$bulan_in_code'");
		return $sql;
	}


	public function daftar_kelas($sekolah_id)
	{
		$sql = $this->db->query("SELECT
								  CONCAT_WS(
								    ' ',
								    kelas.kelas,
								    jurusan.nama_jurusan,
								    kelas_sekolah.group
								  ) AS kelas,
								  kelas_sekolah.id
								FROM
								  kelas_sekolah
								  JOIN jurusan
								    ON jurusan.id = kelas_sekolah.jurusan_id
								  JOIN kelas
								    ON kelas.id = kelas_sekolah.kelas_id
								WHERE jurusan.sekolah_id = '$sekolah_id'
								ORDER BY kelas ASC");
		return $sql;
	}


	public function daftar_bulan_ajaran()
	{
		$sql = $this->db->query("SELECT
								  RIGHT(
								    CONCAT_WS(
								      '-',
								      '',
								      IF(
								        bulan.bulan_in_code > 06,
								        (SELECT
								          LEFT(tahun_ajar.tahun_ajar, 4)
								        FROM
								          tahun_ajar
								        WHERE tahun_ajar.status = 'aktif'),
								        (SELECT
								          LEFT(tahun_ajar.tahun_ajar, 4)
								        FROM
								          tahun_ajar
								        WHERE tahun_ajar.status = 'aktif') + 1
								      ),
								      bulan.bulan_in_code
								    ),
								    7
								  ) AS bulan,
								  bulan.bulan AS nama_bulan,
									bulan.bulan_in_code
								FROM
								  bulan");
		return $sql;
	}


	//waktu saat ini
    public function time()
    {
        $now        = now();
        $timestamp  = '1140153693';
        $timezone   = 'UP7';
        $daylight_saving = FALSE;
        $ax         = gmt_to_local($now, $timezone, $daylight_saving);
        $sekarang   = unix_to_human($ax,true,'eu');
        return $sekarang;
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
