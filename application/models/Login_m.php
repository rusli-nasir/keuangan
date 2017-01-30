<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_m extends CI_Model {

	public function process_login()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$sql 	= "SELECT username, sekolah_id, cms_user.id as cms_user_id, pic.privilege as privilege, pic.id as privilege_id FROM cms_user join pic on cms_user.pic_id=pic.id WHERE username = ? AND password = md5(?)";
		$query	= $this->db->query($sql, array($username, $password));
		if ($query->num_rows()>0) {
			$data['result']	= true;
			$data['data']	= $query->row_array();
		}else{
			$data['result']	= false;
		}
		return $data;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */