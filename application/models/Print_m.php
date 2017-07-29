<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Print_m extends CI_Model {


	public function tahun_ajar()
	{
		$sql = $this->db->query("SELECT * FROM tahun_ajar WHERE status='aktif'");
		return $sql;
	}


	public function harian_nasional($date)
	{
		$sekolah_id = $this->session->sekolah_id;
		if ($sekolah_id==1) {
			$sql = $this->db->query("
									SELECT 
									  siswa.nis,
									  siswa.nama_siswa,
									  CONCAT_WS(
									    ' ',
									    kelas.kelas,
									    jurusan.nama_jurusan
									  ) AS Kelas,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '07',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Juli,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '08',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Agustus,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '09',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS September,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '10',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Oktober,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '11',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS November,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '12',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Desember,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '01',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Januari,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '02',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Februari,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '03',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Maret,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '04',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS April,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '05',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Mei,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '06',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Juni,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Komite',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Komite,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Mid Ganjil',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Mid_Ganjil,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Mid Genap',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Mid_Genap,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Perpisahan',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Perpisahan,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Prakerin',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Prakerin,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Semester Ganjil',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Semester_Ganjil,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Semester Genap',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Semester_Genap,
									  FORMAT(SUM(payment.amount),0) AS Total
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
									WHERE DATE(payment.date_created)='$date' AND sekolah.id='$sekolah_id'
									  GROUP BY siswa.nis ");
		} else {
			$sql = $this->db->query("
									SELECT 
									  siswa.nis,
									  siswa.nama_siswa,
									  CONCAT_WS(
									    ' ',
									    kelas.kelas,
									    jurusan.nama_jurusan,
									    kelas_sekolah.group
									  ) AS Kelas,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '07',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Juli,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '08',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Agustus,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '09',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS September,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '10',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Oktober,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '11',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS November,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '12',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Desember,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '01',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Januari,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '02',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Februari,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '03',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Maret,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '04',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS April,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '05',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Mei,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '06',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS Juni,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'SPP Semester 1',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS SPP_Semester_1,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'SPP Semester 2',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS SPP_Semester_2,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'SPP Semester 3',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS SPP_Semester_3,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'SPP Semester 4',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS SPP_Semester_4,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'SPP Semester 5',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS SPP_Semester_5,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'SPP Semester 6',FORMAT(payment.amount,0),NULL)SEPARATOR '<br>')AS SPP_Semester_6,
									  FORMAT(SUM(payment.amount),0) AS Total
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
									WHERE DATE(payment.date_created)='$date' AND sekolah.id='$sekolah_id'
									  GROUP BY siswa.nis");
		}
		
		return $sql;
	}


	public function bulanan_sekolah($kelas_sekolah_id)
	{
		$sekolah_id 		= $this->session->sekolah_id;
		$tahun_ajar 		= $this->keuangan_m->tahun_ajar()->row('tahun_ajar');
		$tahun_pelajaran 	= explode('-', $tahun_ajar);
		$semester1 			= $tahun_pelajaran['0'].'-07-01';
		$semester2 			= $tahun_pelajaran['1'].'-06-30';
		
		if ($sekolah_id==1) {
			$sql 	= $this->db->query("
										SELECT 
										  siswa.nis,
										  siswa.nama_siswa,
										  CONCAT_WS(
										    ' ',
										    kelas.kelas,
										    jurusan.nama_jurusan
										  ) AS Kelas,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '07',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Juli,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '08',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Agustus,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '09',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS September,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '10',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Oktober,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '11',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS November,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '12',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Desember,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '01',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Januari,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '02',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Februari,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '03',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Maret,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '04',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS April,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '05',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Mei,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '06',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Juni,
										  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Komite',concat_ws(' ',REPLACE(CAST(FORMAT(payment.amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Komite,
										  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Mid Ganjil',concat_ws(' ',REPLACE(CAST(FORMAT(payment.amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Mid_Ganjil,
										  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Mid Genap',concat_ws(' ',REPLACE(CAST(FORMAT(payment.amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Mid_Genap,
										  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Perpisahan',concat_ws(' ',REPLACE(CAST(FORMAT(payment.amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Perpisahan,
										  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Prakerin',concat_ws(' ',REPLACE(CAST(FORMAT(payment.amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Prakerin,
										  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Semester Ganjil',concat_ws(' ',REPLACE(CAST(FORMAT(payment.amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Semester_Ganjil,
										  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Semester Genap',concat_ws(' ',REPLACE(CAST(FORMAT(payment.amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Semester_Genap,
										  FORMAT(SUM(payment.amount),0) AS Total 
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
										WHERE DATE(payment.date_created) BETWEEN '$semester1' AND '$semester2'
										  AND sekolah.id = '$sekolah_id' AND kelas_sekolah_id='$kelas_sekolah_id' 
										GROUP BY siswa.nis");
		} else {
			$sql 	= $this->db->query("
										SELECT 
										  siswa.nis,
										  siswa.nama_siswa,
										  CONCAT_WS(
										    ' ',
										    kelas.kelas,
										    jurusan.nama_jurusan,
										    kelas_sekolah.group
										  ) AS Kelas,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '07',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Juli,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '08',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Agustus,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '09',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS September,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '10',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Oktober,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '11',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS November,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '12',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Desember,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '01',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Januari,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '02',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Februari,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '03',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Maret,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '04',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS April,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '05',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Mei,
										  GROUP_CONCAT(IF(bulan.bulan_in_code = '06',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Juni,
										  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'SPP Semester 1',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS SPP_Semester_1,
										  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'SPP Semester 2',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS SPP_Semester_2,
										  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'SPP Semester 3',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS SPP_Semester_3,
										  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'SPP Semester 4',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS SPP_Semester_4,
										  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'SPP Semester 5',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS SPP_Semester_5,
										  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'SPP Semester 6',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS SPP_Semester_6,
										  FORMAT(SUM(payment.amount),0) AS Total
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
										WHERE DATE(payment.date_created) BETWEEN '$semester1' AND '$semester2'
										  AND sekolah.id = '$sekolah_id' AND kelas_sekolah_id='$kelas_sekolah_id' 
										GROUP BY siswa.nis");
		}

		return $sql;
		
	}


	public function bulanan_yayasan($sekolah_id,$month)
	{
		if ($sekolah_id==1) {
			$sql = $this->db->query("
									SELECT 
									  LEFT(payment.date_created, 10) AS Tanggal,
									  siswa.nis,
									  siswa.nama_siswa,
									  CONCAT_WS(
									    ' ',
									    kelas.kelas,
									    jurusan.nama_jurusan
									  ) AS Kelas,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '07',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Juli,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '08',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Agustus,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '09',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS September,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '10',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Oktober,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '11',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS November,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '12',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Desember,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '01',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Januari,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '02',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Februari,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '03',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Maret,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '04',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS April,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '05',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Mei,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '06',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Juni,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Komite',concat_ws(' ',REPLACE(CAST(FORMAT(payment.amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Komite,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Mid Ganjil',concat_ws(' ',REPLACE(CAST(FORMAT(payment.amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Mid_Ganjil,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Mid Genap',concat_ws(' ',REPLACE(CAST(FORMAT(payment.amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Mid_Genap,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Perpisahan',concat_ws(' ',REPLACE(CAST(FORMAT(payment.amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Perpisahan,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Prakerin',concat_ws(' ',REPLACE(CAST(FORMAT(payment.amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Prakerin,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Semester Ganjil',concat_ws(' ',REPLACE(CAST(FORMAT(payment.amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Semester_Ganjil,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'Semester Genap',concat_ws(' ',REPLACE(CAST(FORMAT(payment.amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Semester_Genap,
									  FORMAT(SUM(payment.amount),0) AS Total 
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
									  AND sekolah.id = '$sekolah_id' 
									GROUP BY siswa.nis 
									ORDER BY tanggal ASC");
		} else {
			$sql = $this->db->query("
									SELECT 
									  LEFT(payment.date_created, 10) AS Tanggal,
									  siswa.nis,
									  siswa.nama_siswa,
									  CONCAT_WS(
									    ' ',
									    kelas.kelas,
									    jurusan.nama_jurusan
									  ) AS Kelas,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '07',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Juli,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '08',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Agustus,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '09',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS September,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '10',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Oktober,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '11',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS November,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '12',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Desember,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '01',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Januari,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '02',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Februari,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '03',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Maret,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '04',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS April,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '05',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Mei,
									  GROUP_CONCAT(IF(bulan.bulan_in_code = '06',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS Juni,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'SPP Semester 1',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS SPP_Semester_1,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'SPP Semester 2',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS SPP_Semester_2,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'SPP Semester 3',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS SPP_Semester_3,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'SPP Semester 4',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS SPP_Semester_4,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'SPP Semester 5',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS SPP_Semester_5,
									  GROUP_CONCAT(IF(kategori_keuangan.nama_kategori = 'SPP Semester 6',concat_ws(' ',REPLACE(CAST(FORMAT(amount,0) AS CHAR), ',', '.'),'<i>&#40;',left(payment.date_created,10),'&#41;</i>'),NULL)SEPARATOR '<br>')AS SPP_Semester_6,
									  FORMAT(SUM(payment.amount),0) AS Total 
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
									  AND sekolah.id = '$sekolah_id' 
									GROUP BY siswa.nis 
									ORDER BY tanggal ASC");
		}

		return $sql;		
	}


	public function list_bulanan_out_for_yayasan($now,$sekolah_id)
	{
		$sql = $this->db->query("SELECT 
								  left(payment_out.date_created,10) as Tanggal,
								  kategori_keuangan_pengeluaran.nama_kategori as Nama_Kategori,
								  keterangan,
								  FORMAT(amount,0) as amount
								FROM
								  payment_out 
								  JOIN kategori_keuangan_pengeluaran 
								    ON kategori_keuangan_pengeluaran.id = payment_out.kategori_keuangan_pengeluaran_id 
								WHERE 
								payment_out.date_created LIKE '%$now%'
								AND payment_out.sekolah_id = '$sekolah_id'
								order by payment_out.date_created asc");
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