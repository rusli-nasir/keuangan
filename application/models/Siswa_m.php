<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Siswa_m extends CI_Model {

	public function list_siswa($sekolah_id)
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
								  gender,
								  tahun_masuk,
								  kelas_sekolah.group
								FROM
								  siswa
								  JOIN kelas_sekolah
								    ON kelas_sekolah.id = siswa.kelas_sekolah_id
								  JOIN jurusan
								    ON kelas_sekolah.jurusan_id = jurusan.id
								  JOIN kelas
								    ON kelas_sekolah.kelas_id = kelas.id
								  JOIN cms_user
								  	ON cms_user.id=siswa.cms_user_id
								WHERE flag='show' and jurusan.sekolah_id='$sekolah_id'
								ORDER BY nama_siswa ASC");
		return $sql;
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
								  kelas_sekolah.id as kelas_sekolah_id,
								  gender,
								  tahun_masuk
								FROM
								  siswa
								  JOIN kelas_sekolah
								    ON kelas_sekolah.id = siswa.kelas_sekolah_id
								  JOIN jurusan
								    ON kelas_sekolah.jurusan_id = jurusan.id
								  JOIN kelas
								    ON kelas_sekolah.kelas_id = kelas.id
								  JOIN cms_user
								  	ON cms_user.id=siswa.cms_user_id
								where siswa.id='$id'");
		return $sql;
	}


	public function add_siswa()
	{
		$now 		= $this->time();
		$nis		= $this->input->post('nis');
		$nama_siswa	= $this->input->post('nama_siswa');
		$kelas_id	= $this->input->post('kelas');
		$cms_user_id= $this->session->user_id;
		$gender 	= $this->input->post('gender');
		$tahun_masuk= $this->input->post('tahun_masuk');

		$data = array(
		        'nis' 			=> $nis,
		        'nama_siswa'	=> $nama_siswa,
		        'kelas_sekolah_id'=> $kelas_id,
		        'status'		=> 'aktif',
		        'cms_user_id'	=> $cms_user_id,
		        'date_created' 	=> $now,
		        'flag'			=> 'show',
		        'gender'		=> $gender,
		        'tahun_masuk'	=> $tahun_masuk
		);

		$this->db->insert('siswa', $data);
	}


	public function delete_siswa($id)
	{
		$data = array(
		        'flag' => 'hide'
		);

		$this->db->where('id', $id);
		$this->db->update('siswa', $data);
	}


	public function update_siswa($id)
	{
		$nis		= $this->input->post('nis');
		$nama_siswa	= $this->input->post('nama_siswa');
		$kelas_id	= $this->input->post('kelas');
		$cms_user_id= $this->session->user_id;
		$gender 	= $this->input->post('gender');
		$tahun_masuk= $this->input->post('tahun_masuk');

		$data = array(
		        'nis' 			=> $nis,
		        'nama_siswa'	=> $nama_siswa,
		        'kelas_sekolah_id'=> $kelas_id,
		        'cms_user_id'	=> $cms_user_id,
		        'gender'		=> $gender,
		        'tahun_masuk'	=> $tahun_masuk
		);

		$this->db->where('id', $id);
		$this->db->update('siswa', $data);
	}


	public function get_jurusan_tm_gender($siswa_id)
	{
		$sql = $this->db->query("SELECT
								  tahun_masuk,
								  gender,
								  jurusan_id,
								  semester_id
								FROM
								  siswa
								  JOIN kelas_sekolah
								    ON kelas_sekolah.id = siswa.kelas_sekolah_id
								where siswa.id='$siswa_id'");
		return $sql;
	}


	public function get_siswa($kelas_sekolah_id)
	{
		$this->db->select('id,nama_siswa');
		$this->db->order_by("nama_siswa", "ASC");
        $this->db->where("kelas_sekolah_id", $kelas_sekolah_id);
				$this->db->where('status', 'aktif');
        $query = $this->db->get("siswa");
        if ($query->num_rows() > 0) return $query->result();
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

		public function list_tahun_masuk()
		{
			$sql = $this->db->query('select distinct(tahun_masuk) as tahun_masuk from siswa order by tahun_masuk asc');
			return $sql;
		}


		public function list_jurusan_based_on_tahun_masuk($tahun_masuk)
		{
			$sekolah_id = $_SESSION['sekolah_id'];
			$sql = $this->db->query("SELECT
															  DISTINCT(jurusan.nama_jurusan) AS nama_jurusan,
															  jurusan.id
															FROM
															  jurusan
															  JOIN kelas_sekolah
															    ON kelas_sekolah.jurusan_id = jurusan.id
															  JOIN siswa ON siswa.kelas_sekolah_id=kelas_Sekolah.id
															WHERE jurusan.sekolah_id = '".$sekolah_id."'
															  AND siswa.tahun_masuk = '".$tahun_masuk."'");
			return $sql;
		}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
