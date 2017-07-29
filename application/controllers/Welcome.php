<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {


	public function index()
	{
		date_default_timezone_set('Asia/Jakarta');
		$currentTime = date('Y-m-d H:i:s');
		$this->load->library('excel_reader');
		$this->excel_reader->read('./uploads/SPANMEDAN2017.xls');

		$worksheet = $this->excel_reader->sheets[0];

		$numRows = $worksheet['numRows']; // ex: 14
		$numCols = $worksheet['numCols']; // ex: 4
		$cells   = $worksheet['cells']; // the 1st row are usually the field's name
		foreach ($cells as $key) {
			$data = array(
				'nis'              => $key['2'],
				'nama_siswa'       => $key['1'],
				'kelas_sekolah_id' => '53',
				'status'           => 'aktif',
				'cms_user_id'      => '1',
				'date_created'     => $currentTime,
				'date_updated'     => $currentTime,
				'flag'             => 'show',
				'tahun_masuk'      => '2017',
				'gender'           => $key['3'],
				'semester_id'      => '1');

			$this->db->insert('siswa', $data);
		}

		$worksheet = $this->excel_reader->sheets[1];

		$numRows = $worksheet['numRows']; // ex: 14
		$numCols = $worksheet['numCols']; // ex: 4
		$cells   = $worksheet['cells']; // the 1st row are usually the field's name
		foreach ($cells as $key) {
			$data = array(
				'nis'              => $key['2'],
				'nama_siswa'       => $key['1'],
				'kelas_sekolah_id' => '12',
				'status'           => 'aktif',
				'cms_user_id'      => '1',
				'date_created'     => $currentTime,
				'date_updated'     => $currentTime,
				'flag'             => 'show',
				'tahun_masuk'      => '2017',
				'gender'           => $key['3'],
				'semester_id'      => '1');

			$this->db->insert('siswa', $data);
			// print_r($data);
		}
}
