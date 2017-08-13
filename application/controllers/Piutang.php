<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Piutang extends CI_Controller {


	public function __construct(){
		parent::__construct();
		$this->load->model('sekolah_m');
		$this->load->model('siswa_m');
		$this->load->model('piutang_m');
		$this->load->model('Crud_m','crud');
	}

/*
	public function getStudentFee($tahunMasuk, $tingkatKelas, $sekolahId)
	{
		$data = array();

		foreach ($tingkatKelas as $key) {

			$namaKategori = explode(',', $namaKategori); 
			$namaKategori = implode("','",$namaKategori);

			$sql = "SELECT
					    sum(biaya) as 'studentFee', kelas_sekolah.id, concat_ws(' ',kelas.kelas,jurusan.nama_jurusan,kelas_sekolah.group) as 'rombel'
					FROM
					    kelas_sekolah
					        JOIN
						kelas on kelas.id=kelas_sekolah.kelas_id
					    join
					    jurusan ON jurusan.id = kelas_sekolah.jurusan_id
					        JOIN
					    kategori_keuangan ON jurusan.id = kategori_keuangan.jurusan_id
					WHERE
							kategori_keuangan.tahun_masuk = '".$angkatan."'
							AND nama_kategori IN ('".$namaKategori."')
							AND kelas_sekolah.kelas_id    = '".$key['id']."'
							and kelas_sekolah.status      = 'show'
							and jurusan.sekolah_id        = '".$sekolahId."'
					GROUP BY kelas_sekolah.id";
			$query = $this->db->query($sql)->result_array();
			$query['query'] = $this->db->last_query();
			array_push($data, $query);
		}

		return $data;
	}
*/

	public function getStudentPayment($kelas_sekolah_id, $kategoriKeuanganId, $siswaId, $groupBy = null)
	{
		$kategoriKeuanganId = implode("','",$kategoriKeuanganId);

		if ($groupBy) {
			$sql = "SELECT 
				    SUM(amount) as 'totalPayment',
				    payment.kategori_keuangan_id
				FROM
				    siswa
				        LEFT JOIN
				    payment ON payment.siswa_id = siswa.id
				WHERE
				    siswa.kelas_sekolah_id='".$kelas_sekolah_id."' and
					siswa.status='aktif'
					and payment.flag='show'
					and payment.kategori_keuangan_id in('".$kategoriKeuanganId."')
					and siswa.id = '".$siswaId."'
					group by payment.kategori_keuangan_id
					order by kategori_keuangan_id asc";
			$query = $this->db->query($sql)->result();
		} else {
			$sql = "SELECT 
				    SUM(amount) as 'totalPayment'
				FROM
				    siswa
				        LEFT JOIN
				    payment ON payment.siswa_id = siswa.id
				WHERE
				    siswa.kelas_sekolah_id='".$kelas_sekolah_id."' and
					siswa.status='aktif'
					and payment.flag='show'
					and payment.kategori_keuangan_id in('".$kategoriKeuanganId."')
					and siswa.id = '".$siswaId."'";
			$query = $this->db->query($sql)->row();
		}
		


		return $query;
	}


	public function getPiutang($sekolahId)
	{
		date_default_timezone_set('Asia/Jakarta');
		$currentTime  = date('Y-m-d H:i:s');
		$namaSekolah  = $this->crud->get('sekolah', array('id' => $sekolahId))->row()->nama_sekolah;
		$kelasJurusan = $this->crud->get_kelas_jurusan(array('jurusan.sekolah_id' => $sekolahId, 'kelas_sekolah.status' => 'show'))->result_array();
		$tmp = array();
		
		foreach ($kelasJurusan as $key) {
			$getSiswa      = $this->get_siswa($key['id']);
			$piutangRombel = array_sum(array_map('current', $getSiswa));
			$studentFee    = array_sum(array_map('next', $getSiswa));
			$percentage    = $piutangRombel / ($studentFee/100);
			$data = array(
				'piutangRombel' => $piutangRombel,
				'sumStudentFee' => $studentFee,
				'kelas'         => $key['kelas'], 
				'siswa'         => $getSiswa,
				'percentage'    => round(100-$percentage,2));

			$dataPiutangRombel = array(
				'sekolahId'     => $sekolahId,
				'piutangRombel' => $data['piutangRombel'],
				'studentFee'    => $data['sumStudentFee'],
				'namaKelas'     => $data['kelas'],
				'percentage'    => $data['percentage'],
				'dateCreated'   => $currentTime,
				'rombelId'      => $key['id']);
			// $this->crud->insert($dataPiutangRombel, 'piutangRombel');

			array_push($tmp, $data);
		}

		$piutangSekolah            = array_sum(array_map('current', $tmp));
		$percentagePenerimaan      = $piutangSekolah / (array_sum(array_map('next', $tmp))/100);
		$tmp['piutangSekolah']     = $piutangSekolah;
		$tmp['percentagePenerimaan'] = round(100-$percentagePenerimaan,2);
		
		$data = array(
			'piutangSekolah' => $piutangSekolah,
			'studentFee'     => array_sum(array_map('next', $tmp)),
			'percentage'     => round(100-$percentagePenerimaan,2),
			'dateCreated'    => $currentTime,
			'namaSekolah'    => $namaSekolah,
			'sekolahId'      => $sekolahId);
		// $this->crud->insert($data, 'piutangSekolah');

		return $tmp;
	}


	public function getPiutangSiswa($siswaId, $sekolahId)
	{
		error_reporting(0);
		date_default_timezone_set('Asia/Jakarta');
		$currentTime = date('Y-m-d H:i:s');
		$kelas = $this->kelas($sekolahId);
		$siswa = $this->crud->get('siswa', array('id' => $siswaId))->row();		
		
		$getPaymentCategory = $this->get_payment_category($siswaId, $sekolahId);
		$getStudentPayment  = $this->getStudentPayment($siswa->kelas_sekolah_id, $getPaymentCategory['kategoriKeuanganId'], $siswaId, '1');
		$tmp  = $this->getStudentPayment($siswa->kelas_sekolah_id, $getPaymentCategory['lastKategoriKeuanganId'], $siswaId, '1');
		array_push($getStudentPayment, $tmp[0]);

		$getStudentPayment2  = $this->getStudentPayment($siswa->kelas_sekolah_id, $getPaymentCategory['kategoriKeuanganId'], $siswaId);
		
		$data = array(
			'nama_siswa'  => $siswa->nama_siswa,
			'payment'     => $getStudentPayment2->totalPayment,
			'listPayment' => $getStudentPayment,
			'listFee'     => $getPaymentCategory, 
			'studentFee'  => $getPaymentCategory['studentFee'],
			'piutang'     => $getPaymentCategory['studentFee'] - $getStudentPayment2->totalPayment
			);

		$dataPiutangSiswa = array(
			'namaSiswa'    => $data['nama_siswa'],
			'piutangSiswa' => $data['piutang'],
			'studentFee'   => $data['studentFee'],
			'rombelId'     => $siswa->kelas_sekolah_id,
			'dateCreated'  => $currentTime);
		// $this->crud->insert($dataPiutangSiswa, 'piutangSiswa');
		// $piutangSiswaId = $this->db->insert_id();

		unset($data['listFee']['kategoriKeuanganId']);
		unset($data['listFee']['lastKategoriKeuanganId']);
		unset($data['listFee']['studentFee']);

		$x = 0;
		foreach ($data['listFee'] as $key) {

			switch ($key['namaKategori']) {
				case 'SPP':
					$dataDetailPiutangSiswa = array(
						'kategoriKeuanganId' => $key['id'],
						'namaKategori'       => $key['nama_kategori'],
						'jurusanId'          => $key['jurusan_id'],
						'tahunMasuk'         => $key['tahun_masuk'],
						'gender'             => $key['gender'],
						'biaya'              => $key['biaya'],
						'totalPayment'       => $getStudentPayment[$x]->totalPayment * 12,
						'piutangSiswaId'     => $piutangSiswaId,
						'dateCreated'        => $currentTime);
					break;
				
				default:
					$dataDetailPiutangSiswa = array(
						'kategoriKeuanganId' => $key['id'],
						'namaKategori'       => $key['nama_kategori'],
						'jurusanId'          => $key['jurusan_id'],
						'tahunMasuk'         => $key['tahun_masuk'],
						'gender'             => $key['gender'],
						'biaya'              => $key['biaya'],
						'totalPayment'       => $getStudentPayment[$x]->totalPayment,
						'piutangSiswaId'     => $piutangSiswaId,
						'dateCreated'        => $currentTime);
					break;
			}
			
			if ($key['id']) {
				// $this->crud->insert($dataDetailPiutangSiswa, 'detailPiutangSiswa');
			}
			$x++;
		}

		return $data;
	}


	public function get_payment_category($siswa_id, $sekolahId) {
		$data               = $this->siswa_m->get_jurusan_tm_gender($siswa_id)->row_array();
		$data['sekolah_id'] = $sekolahId;
		$data               = $this->piutang_m->get_payment_category($data);
		return $data;
    }


    public function get_siswa($kelas_sekolah_id) {
		$sekolahId = $this->db->select('sekolah_id')->from('kelas_sekolah')->join('jurusan','jurusan.id=kelas_sekolah.jurusan_id')->where(array('kelas_sekolah.id' => $kelas_sekolah_id))->get()->row()->sekolah_id;
		$data      = $this->siswa_m->get_siswa($kelas_sekolah_id);
		$tmp       = array();
		foreach ($data as $key) {
			$getPaymentCategory = $this->get_payment_category($key->id, $sekolahId);
			$getStudentPayment  = $this->getStudentPayment($kelas_sekolah_id, $getPaymentCategory['kategoriKeuanganId'], $key->id);
			
			$data = array(
				'piutang'    => $getPaymentCategory['studentFee'] - $getStudentPayment->totalPayment,
				'studentFee' => $getPaymentCategory['studentFee'],
				'nama_siswa' => $key->nama_siswa,
				'payment'    => $getStudentPayment->totalPayment, 
				'detail'     => $this->getPiutangSiswa($key->id, $sekolahId)
				);

			array_push($tmp, $data);
		}

		return $tmp;
    }


    public function listPiutang($sekolahId = null)
	{
		date_default_timezone_set("Asia/Jakarta");
		if (!$sekolahId) {
			$sekolah_id = $this->session->sekolah_id;
		}
		$now = date('Y-m-d');

		$kelas = $this->kelas($sekolahId);
		$data  = array(
			'page'    => 'v2/page/listPiutang',
			'menu'    => 'Daftar Sisa Pembayaran',
			'submenu' => $kelas,
			'title'   => 'List '.$now,
			'data'    => $this->getPiutang($sekolahId)
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


	public function getListPiutang($sekolahId = null)
	{
		date_default_timezone_set('Asia/Jakarta');
		$now = date('Y-m-d');
		if (!$sekolahId) {
			$sekolahId = $sekolah_id = $this->session->sekolah_id;
		}

		$namaSekolah = $this->crud->get('sekolah', array('id' => $sekolahId))->row()->nama_sekolah;

		$rombel = $this->db->select('rombelId')->from('piutangRombel')->where(array('sekolahId' => $sekolahId))->order_by('namaKelas', 'asc')->get()->result_array();

		$piutang = array();
		foreach ($rombel as $key) {
			$tmp = $this->piutang_m->getListPiutang(array('piutangRombel.rombelId' => $key['rombelId']));
			array_push($piutang, $tmp);
		}

		$siswaPiutang = $this->piutang_m->getSiswaPiutang()->result_array();

		$kelas = $this->kelas($sekolahId);
		$data  = array(
			'page'    => 'v2/page/listPiutangSekolah',
			'menu'    => 'Daftar Sisa Pembayaran Tahun Ajaran 2016-2017 '.$namaSekolah,
			'submenu' => $kelas,
			'title'   => 'List '.$now,
			'data'    => $piutang,
			'siswa'   => $siswaPiutang
			);

		$this->parser->parse('v2/lte', $data);
	}


	public function getDetailPiutangSiswa($idpiutangSiswa){
	
		$condition = "piutangSiswaId = '".$idpiutangSiswa."' and (biaya > totalPayment or totalPayment is null)";
		$data      = $this->crud->get('detailPiutangSiswa', $condition)->result_array();
		if($data){
            $tmp = "<option value=''>Pilih Pembayaran</option>";
            foreach($data as $row) {
                $tmp .= "<option value='".$row['iddetailPiutangSiswa']."'>".$row['namaKategori']."(Rp.".number_format($row['biaya']-$row['totalPayment'], 0 , '' , '.' ).")</option>";
            }
        } else {
            $tmp = "<option value=''>Pilih Pembayaran</option>";
        }
        die($tmp);
	}


	public function payment_in()
	{
		$sekolahId            = $_SESSION['sekolah_id'];
		$iddetailPiutangSiswa = $this->input->post('iddetailPiutangSiswa');
		$amount               = $this->input->post('amount');
		$piutang              = $this->crud->get('detailPiutangSiswa', array('iddetailPiutangSiswa' => $iddetailPiutangSiswa))->row();
	
		if ($amount > $piutang->biaya - $piutang->totalPayment) {
			$data = array('status' => 'false', 'info' => 'Pembayaran berlebih, mohon ulangi dan periksa kembali');
		
		} else {
			$data = array('iddetailPiutangSiswa' => $iddetailPiutangSiswa, 'amount' => $amount);
			$this->piutang_m->updatePiutang($data);
			$data = array('status' => 'true', 'info' => 'Pembayaran Diterima');
		} 

		$this->session->set_flashdata($data);
		redirect(base_url('piutang/getListPiutang/'.$sekolahId));
	}


    public function revisiPiutang($sekolahId)
    {
        $piutangSekolah = $this->crud->get('piutangSekolah', array('sekolahId' => $sekolahId))->result_array();

        foreach ($piutangSekolah as $pSekolah){
            $dataPiutangSekolah = array();
            $piutangRombel = $this->crud->get('piutangRombel', array('sekolahId' => $sekolahId))->result_array();

            foreach ($piutangRombel as $pRombel){
                $piutangSiswa = $this->crud->get('piutangSiswa', array('rombelId' => $pRombel['rombelId']))->result_array();
                $dataPiutangRombel = array();

                foreach ($piutangSiswa as $pSiswa){
                    $detailPiutang = $this->db->select('totalPayment')->from('detailPiutangSiswa')->where('piutangSiswaId', $pSiswa['idpiutangSiswa'])->get()->result_array();
                    $revisiPiutangSiswa = array(
                        'piutangSiswa' => $pSiswa['studentFee']-array_sum(array_map('current', $detailPiutang)),
                        'totalPayment' => array_sum(array_map('current', $detailPiutang)),
                        'idpiutangSiswa' => $pSiswa['idpiutangSiswa'],
                        'namaSiswa' => $pSiswa['namaSiswa']
                    );

                    $this->crud->update(array('piutangSiswa' => $revisiPiutangSiswa['piutangSiswa']), 'piutangSiswa', array('idpiutangSiswa' => $pSiswa['idpiutangSiswa']));
//                    print_r($revisiPiutangSiswa);
                    array_push($dataPiutangRombel, $revisiPiutangSiswa);
                }

                $revisiPiutangRombel = array(
                    'piutangRombel' => array_sum(array_map('current', $dataPiutangRombel)),
                    'rombelId' => $pRombel['rombelId'],
                    'namaKelas' => $pRombel['namaKelas'],
                    'percentage' => $pRombel['percentage'],
                    'studentFee' => $pRombel['studentFee']
                );
                $percentage = round(100 - ($revisiPiutangRombel['piutangRombel'] / ($revisiPiutangRombel['studentFee']/100)), 2);
                $this->crud->update(array('piutangRombel' => $revisiPiutangRombel['piutangRombel'], 'percentage' => $percentage), 'piutangRombel', array('rombelId' => $pRombel['rombelId']));
//                print_r($revisiPiutangRombel);
                array_push($dataPiutangSekolah, $revisiPiutangRombel);
            }

            $revisiPiutangSekolah = array(
                'namaSekolah' => $pSekolah['namaSekolah'],
                'percentage' => $pSekolah['percentage'],
                'studentFee' => $pSekolah['studentFee'],
                'piutangSekolah' => array_sum(array_map('current', $dataPiutangSekolah)),
            );
            $percentage = round(100 - ($revisiPiutangSekolah['piutangSekolah'] / ($revisiPiutangSekolah['studentFee']/100)), 2);
            $this->crud->update(array('piutangSekolah' => $revisiPiutangSekolah['piutangSekolah'], 'percentage' => $percentage), 'piutangSekolah', array('sekolahId' => $pSekolah['sekolahId']));
//            print_r($revisiPiutangSekolah);
        }

    }

}