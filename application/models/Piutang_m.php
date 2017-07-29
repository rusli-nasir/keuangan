<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Piutang_m extends CI_Model {

	public function get_payment_category($data)
	{
		$sekolah_id = $data['sekolah_id'];
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
								  biaya,
								  kategori_keuangan.id,
								  nama_kategori,
								  jurusan_id,
								  tahun_masuk,
								  gender
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
		$data                       = $sql->result_array();
		$oneDimensionalArray        = array_map('current', $data);
		$data['kategoriKeuanganId'] = array_map('next', $data);
		$data['studentFee']         = array_sum($oneDimensionalArray);
		return $data;
	}


	public function getListPiutang($condition)
	{
		$query = $this->db->select("
					idpiutangSiswa,
					namaSiswa,
				    piutangSiswa,
				    piutangSiswa.studentFee,
				    piutangRombel,
				    piutangRombel.studentFee AS 'studentFeeRombel',
				    namaKelas,
				    piutangRombel.percentage AS 'percentageRombel',
				    piutangSekolah,
				    piutangSekolah.percentage AS 'percentageSekolah',
				    namaSekolah")
					->from('piutangSiswa')
					->join('piutangRombel', 'piutangRombel.rombelId = piutangSiswa.rombelId')
					->join('piutangSekolah', 'piutangSekolah.sekolahId = piutangRombel.sekolahId')
					->where($condition)
					->order_by('namaSiswa','asc')
					->get()
					->result_array();

		$data = array();
		foreach ($query as $key) {
			$detail = $this->getListPiutangDetail($key['idpiutangSiswa']);
			$tmp    = array(
				'namaSiswa'         => $key['namaSiswa'],
				'piutangSiswa'      => $key['piutangSiswa'],
				'studentFee'        => $key['studentFee'],
				'piutangRombel'     => $key['piutangRombel'],
				'studentFeeRombel'  => $key['studentFeeRombel'],
				'namaKelas'         => $key['namaKelas'],
				'percentageRombel'  => $key['percentageRombel'],
				'piutangSekolah'    => $key['piutangSekolah'],
				'percentageSekolah' => $key['percentageSekolah'],
				'namaSekolah'       => $key['namaSekolah'],
				'detail'            => $detail);
			array_push($data, $tmp);
		}

		return $data;
	}


	public function getListPiutangDetail($piutangSiswaId)
	{
		$query = $this->db->select('namaKategori,biaya,totalPayment')->from('detailPiutangSiswa')->where(array('piutangSiswaId' => $piutangSiswaId))->get()->result_array();
		return $query;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */