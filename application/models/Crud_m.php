<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crud_m extends CI_Model {

	public function get($table, $condition)
	{
		$sql = $this->db->get_where($table, $condition);
		return $sql;
	}


	public function get_kelas_jurusan($condition, $type = null)
	{
		if (!$type) {
			$this->db->select('CONCAT_WS(" ", kelas.kelas, jurusan.nama_jurusan, kelas_sekolah.group) as kelas, kelas_sekolah.id,');
		} else {
			$this->db->select('CONCAT_WS(" ", jurusan.nama_jurusan, kelas_sekolah.group) as kelas, kelas_sekolah.id,');
		}
		$this->db->from('kelas_sekolah');
		$this->db->join('jurusan', 'kelas_sekolah.jurusan_id = jurusan.id');
		$this->db->join('kelas', 'kelas.id = kelas_sekolah.kelas_id');
		$this->db->where($condition);
		$sql = $this->db->get();
		return $sql;
	}


	public function get_kelas_jurusan_based_student($condition)
	{
		$this->db->select('CONCAT_WS(" ", kelas.kelas, jurusan.nama_jurusan, kelas_sekolah.group) as kelas, kelas_sekolah.id, siswa.*, kelas_sekolah.jurusan_id');
		$this->db->from('kelas_sekolah');
		$this->db->join('jurusan', 'kelas_sekolah.jurusan_id = jurusan.id');
		$this->db->join('kelas', 'kelas.id = kelas_sekolah.kelas_id');
		$this->db->join('siswa', 'kelas_sekolah.id = siswa.kelas_sekolah_id');
		$this->db->where($condition);
		$sql = $this->db->get();
		return $sql;
	}


	public function get_kelas($sekolah_id)
	{
		$this->db->select('kelas.kelas, kelas.id');
		$this->db->from('kelas');
		$this->db->join('kelas_sekolah', 'kelas_sekolah.kelas_id = kelas.id');
		$this->db->join('jurusan', 'jurusan.id = kelas_sekolah.jurusan_id');
		$this->db->where(array('jurusan.sekolah_id' => $sekolah_id, 'jurusan.status' => 'show'));
		$this->db->group_by('kelas.kelas, kelas.id');
		$sql = $this->db->get();
		return $sql;
	}


	public function get_payment_spp_based_student($condition, $annual = null)
	{
		$this->db->select('payment.amount, payment.id, date(payment.date_created) as created, payment.tahun as tahun');
		$this->db->from('payment');
		$this->db->join('kategori_keuangan', 'kategori_keuangan.id = payment.kategori_keuangan_id');
		if ($annual == 'monthly') {
			$month   = $condition['date(payment.date_created)'];
			unset($condition['date(payment.date_created)']);
			unset($condition['payment.tahun']);
			$this->db->where($condition);
			$this->db->like('date(payment.date_created)', $month);
		} else if($annual == 'debt'){
			unset($condition['payment.tahun']);
			$this->db->where($condition);
		} else if($annual == 'tahunajaran'){
			$semester1 = $condition['semester1'];
			$semester2 = $condition['semester2'];
			$this->db->where("date(payment.date_created) BETWEEN '$semester1' AND '$semester2'");
			unset($condition['semester1']);
			unset($condition['semester2']);
			$this->db->where($condition);
		} else {
			$this->db->where($condition);
		}
		$sql = $this->db->get();
		return $sql;
	}


	public function get_payment_lainnya_monthly($condition)
	{
		$month   = $condition['date(date_created)'];
		unset($condition['date(date_created)']);
		$this->db->select('*');
		$this->db->from('payment_lainnya');
		$this->db->where($condition);
		$this->db->like('date(date_created)',$month);
		$sql = $this->db->get();
		return $sql;
	}


	public function get_category_payment($condition)
	{
		$this->db->select('nama_kategori');
		$this->db->from('kategori_keuangan');
		$this->db->where($condition);
		$this->db->group_by('nama_kategori');
		$sql = $this->db->get();
		return $sql;
	}


	public function login($condition)
	{
		$this->db->select('username, sekolah_id, cms_user.id as cms_user_id, pic.privilege as privilege, pic.id as privilege_id');
		$this->db->from('cms_user');
		$this->db->join('pic', 'cms_user.pic_id=pic.id');
		$this->db->where($condition);
		$sql = $this->db->get();
		return $sql;
	}


	public function paid($condition, $conditionOr = array())
	{
		$this->db->select('SUM(amount) as paid');
		$this->db->from('payment');
		$this->db->where($condition);
		if ($conditionOr) {
			$this->db->group_start(); 
				$this->db->or_where('tahun', $conditionOr['currentYear']);
				$this->db->or_where('tahun', $conditionOr['beforeYear']);
				$this->db->or_where('tahun', $conditionOr['afterYear']);
			$this->db->group_end();
		}
		$sql = $this->db->get();
		// $sql = $this->db->get_where('payment', $condition);
		return $sql;
	}


	public function paidPerpisahan($condition, $conditionOr)
	{
		$x=1;
		$this->db->select('SUM(amount) as paid');
		$this->db->from('payment');
		$this->db->where($condition);
		$this->db->group_start();
			foreach ($conditionOr as $key) {
				if ($x > 1) {
					$where = "tahun='".$key['tahun']."' AND tahun_ajaran_id='".$key['tahun_ajaran_id']."'";
					$this->db->or_where($where);
					$this->db->or_where($key);
				} else {
					$this->db->where($key);
				}
				$x++;
			} 
		$this->db->group_end();
		$sql = $this->db->get();
		return $sql;
	}


	public function update($data, $table, $condition)
	{
		$this->db->update($table, $data, $condition);
	}


	public function delete($table, $condition)
	{
		$this->db->delete($table, $condition); 
	}


	public function insert($data ,$table)
	{
		$this->db->insert($table, $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
