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


	public function getSiswaPiutang()
	{
		$sekolahId = $_SESSION['sekolah_id'];
		$query = $this->db->select('namaSiswa,idpiutangSiswa')
		->from('piutangSiswa')
		->join('detailPiutangSiswa', 'detailPiutangSiswa.piutangSiswaId=piutangSiswa.idpiutangSiswa')
		->join('piutangRombel','piutangRombel.rombelId = piutangSiswa.rombelId')
		->join('piutangSekolah','piutangSekolah.sekolahId = piutangRombel.sekolahId')
		->where('detailPiutangSiswa.biaya > detailPiutangSiswa.totalPayment')
		->where('piutangSekolah.sekolahId', $sekolahId)
		->or_where('detailPiutangSiswa.totalPayment', null)
		->group_by('namaSiswa')
		->order_by('namaSiswa','asc')
		->get();
		
		return $query;
	}


	public function updatePiutang($data)
	{
		$cmsUserId            = $_SESSION['user_id'];
		$currentDate          = date('Y-m-d H:i:s');
		$currentMonth         = date('m');
		$iddetailPiutangSiswa = $data['iddetailPiutangSiswa'];
		$amount               = $data['amount'];

		$piutangId = $this->db->select('
			detailPiutangSiswa.kategoriKeuanganId,
			detailPiutangSiswa.biaya, 
			detailPiutangSiswa.totalPayment, 
			piutangSiswa.piutangSiswa, 
			piutangRombel.piutangRombel, 
			piutangRombel.percentage,
			piutangRombel.studentFee as studentFeeRombel, 
			piutangSekolah.piutangSekolah,
			piutangSekolah.studentFee as studentFeeSekolah,
			idpiutangSiswa,
			piutangRombel.rombelId,
			piutangSekolah.sekolahId,
			piutangSiswa.namaSiswa')
		->from('detailPiutangSiswa')
		->join('piutangSiswa','piutangSiswa.idpiutangSiswa = detailPiutangSiswa.piutangSiswaId')
		->join('piutangRombel','piutangRombel.rombelId = piutangSiswa.rombelId')
		->join('piutangSekolah','piutangSekolah.sekolahId = piutangRombel.sekolahId')
		->where('iddetailPiutangSiswa',$iddetailPiutangSiswa)
		->get()
		->row();

		// echo $this->db->last_query();
		// exit();

		$totalPayment      = $amount + $piutangId->totalPayment;
		$piutangSiswa      = $piutangId->piutangSiswa - $amount;
		$piutangRombel     = $piutangId->piutangRombel - $amount;
		$piutangSekolah    = $piutangId->piutangSekolah - $amount;
		$percentageRombel  = round(100-$piutangRombel / ($piutangId->studentFeeRombel/100),2);
		$percentageSekolah = round(100-$piutangSekolah / ($piutangId->studentFeeSekolah/100),2);

		$idpiutangSiswa     = $piutangId->idpiutangSiswa;
		$rombelId           = $piutangId->rombelId;
		$sekolahId          = $piutangId->sekolahId;
		$kategoriKeuanganId = $piutangId->kategoriKeuanganId;
		$siswaId            = $this->db->select('id')->from('siswa')->where('nama_siswa', $piutangId->namaSiswa)->get()->row()->id;

		$data = array('totalPayment' => $totalPayment);
		$this->db->update('detailPiutangSiswa', $data, array('iddetailPiutangSiswa' => $iddetailPiutangSiswa));

		$data = array('piutangSiswa' => $piutangSiswa);
		$this->db->update('piutangSiswa', $data, array('idpiutangSiswa' => $idpiutangSiswa));

		$data = array('piutangRombel' => $piutangRombel, 'percentage' => $percentageRombel);
		$this->db->update('piutangRombel', $data, array('rombelId' => $rombelId));

		$data = array('piutangSekolah' => $piutangSekolah, 'percentage' => $percentageSekolah);
		$this->db->update('piutangSekolah', $data, array('sekolahId' => $sekolahId));		

		switch ($currentMonth) {
			case '07':
				$bulanId = '1';
				break;

			case '08':
				$bulanId = '2';
				break;

			case '09':
				$bulanId = '3';
				break;

			case '10':
				$bulanId = '4';
				break;

			case '11':
				$bulanId = '5';
				break;

			case '12':
				$bulanId = '6';
				break;

			case '01':
				$bulanId = '7';
				break;

			case '02':
				$bulanId = '8';
				break;

			case '03':
				$bulanId = '9';
				break;

			case '04':
				$bulanId = '10';
				break;

			case '05':
				$bulanId = '11';
				break;

			case '06':
				$bulanId = '12';
				break;
			
			default:
				# code...
				break;
		}

		$data = array(
			'siswa_id'             => $siswaId,
			'kategori_keuangan_id' => $kategoriKeuanganId,
			'amount'               => $amount,
			'date_created'          => $currentDate,
			'date_updated'          => $currentDate,
			'cms_user_id'          => $cmsUserId,
			'bulan_id'             => $bulanId,
			'tahun'                => date('Y'));
		$this->db->insert('payment', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */