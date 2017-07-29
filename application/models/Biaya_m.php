<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Biaya_m extends CI_Model {

	public function list_pembayaran($sekolah_id)
	{
		$sql = $this->db->query("SELECT 
								  kategori_keuangan.*,
								  username,
								  nama_jurusan,
								  semester.semester 
								FROM
								  kategori_keuangan 
								  JOIN cms_user 
								    ON cms_user.id = kategori_keuangan.cms_user_id 
								  LEFT JOIN jurusan 
								    ON jurusan.id = kategori_keuangan.jurusan_id
								  LEFT JOIN semester
								  	ON semester.id = kategori_keuangan.semester_id 
								WHERE kategori_keuangan.sekolah_id = '$sekolah_id'");
		return $sql;
	}


	public function add_biaya()
	{
		$now 			= $this->time();
		$nama_kategori	= $this->input->post('nama_pembayaran');
		$cms_user_id 	= $this->session->user_id;
		$sekolah_id 	= $this->session->sekolah_id;
		$biaya 			= $this->input->post('biaya');
		$jurusan_id		= $this->input->post('jurusan');
		$tahun_masuk	= $this->input->post('tahun_masuk');
		$semester		= $this->input->post('semester');
		$gender			= $this->input->post('gender');

		$check = substr($nama_kategori, 0,3);

		$data = array(
		        'nama_kategori' => $nama_kategori,
		        'jenis_keuangan'=> 'kredit',
		        'date_created' 	=> $now,
		        'cms_user_id'	=> $cms_user_id,
		        'biaya'			=> $biaya,
		        'gender'		=> $gender,
		        'sekolah_id'	=> $sekolah_id,
		        'semester_id'	=> $semester
		);

		if ($check !='Mid' and $check !='Sem') {
			$data['jurusan_id'] = $jurusan_id;
			$data['tahun_masuk'] = $tahun_masuk;
		}

		$this->db->insert('kategori_keuangan', $data);
	}


	public function delete_biaya($id)
	{
		$this->db->delete('kategori_keuangan', array('id' => $id));
	}


	public function edit_pembayaran($id)
	{
		$sql = $this->db->query("SELECT 
								  kategori_keuangan.*,
								  username,
								  nama_jurusan,
								  semester.id as id_semester,
								  semester.semester 
								FROM
								  kategori_keuangan 
								  JOIN cms_user 
								    ON cms_user.id = kategori_keuangan.cms_user_id
								  LEFT JOIN jurusan 
								    ON kategori_keuangan.jurusan_id = jurusan.id
								  LEFT JOIN semester
								  	ON semester.id = kategori_keuangan.semester_id
								WHERE kategori_keuangan.id='$id'");
		return $sql;
	}


	public function update_biaya($id)
	{
		$now 			= $this->time();
		$nama_kategori	= $this->input->post('nama_pembayaran');
		$cms_user_id 	= $this->session->user_id;
		$biaya 			= $this->input->post('biaya');
		$jurusan_id		= $this->input->post('jurusan');
		$tahun_masuk	= $this->input->post('tahun_masuk');
		$gender			= $this->input->post('gender');
		$semester		= $this->input->post('semester');

		$check = substr($nama_kategori, 0,3);

		$data = array(
		        'nama_kategori' => $nama_kategori,
		        'jenis_keuangan'=> 'kredit',
		        'cms_user_id'	=> $cms_user_id,
		        'biaya'			=> $biaya,
		        'gender'		=> $gender,
		        'semester_id'	=> $semester
		);

		if ($check !='Mid' and $check !='Sem') {
			$data['jurusan_id'] = $jurusan_id;
			$data['tahun_masuk'] = $tahun_masuk;
		}

		$this->db->where('id', $id);
		$this->db->update('kategori_keuangan', $data);
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