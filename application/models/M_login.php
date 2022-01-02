<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_login extends CI_Model {
	function __construct(){
		parent::__construct();
  }

  public function cek_user($email){
    $this->db->select('*');
    $this->db->from('tb_user');
    $this->db->where('email', $email);
    $query = $this->db->get();

    // jika hasil dari query diatas memiliki lebih dari 0 record
    if ($query->num_rows() > 0) {
      return true;
    } else {
      return false;
    }
    
  }

  public function get_user($email){
    $this->db->select('*');
    $this->db->from('tb_user');
    $this->db->where('email', $email);
    $query = $this->db->get();

    // jika hasil dari query diatas memiliki lebih dari 0 record
    if ($query->num_rows() > 0) {
      return $query->row();
    } else {
      return false;
    }
    
  }

  public function get_userByID($id_user){
    $this->db->select('*');
    $this->db->from('tb_user');
    $this->db->where('id_user', $id_user);;
    $query = $this->db->get();

    // jika hasil dari query diatas memiliki lebih dari 0 record
    if ($query->num_rows() > 0) {
      return $query->row();
    } else {
      return false;
    }
    
  }

  function add_user($data_user){
    $this->db->insert('tb_user', $data_user);
    return $this->db->affected_rows() == true;
  }

  function update_password($data_user, $where){
    $this->db->where($where);
    $this->db->update('tb_user', $data_user);
    return $this->db->affected_rows() == true;
  }

	// AKTIVASI

	public function get_aktivasi($id_user){
		$id_user 	= $this->db->escape($id_user);
		$query 		= $this->db->query("SELECT * FROM tb_user WHERE id_user = $id_user");
		if ($query->num_rows() > 0) {
			return $query->row();
		}else {
			return false;
		}
	}

	public function cek_aktivasi($aktivasi){
		$aktivasi = $this->db->escape($aktivasi);
		$query 		= $this->db->query("SELECT * FROM tb_user WHERE aktivasi = $aktivasi");
		return $query->num_rows();
	}

	public function aktivasi_kode($kode_aktivasi, $id_user){

		$db_code 	= $this->encryption->decrypt($this->get_aktivasi($id_user)->aktivasi);

		if ($kode_aktivasi == $db_code) {
			return TRUE;
		}else {
			return FALSE;
		}
	}

	public function create_aktivasi(){

		// CREATE KODE AKTIVASI
		$this->encryption->initialize(array('driver' => 'openssl'));

		do {
			$KODE_AKTIVASI	= random_int(100000, 999999);

			// ENCRYPT KODE AKTIVASI
			$ciphercode 	= $this->encryption->encrypt($KODE_AKTIVASI);
		} while ($this->cek_aktivasi($KODE_AKTIVASI) > 0);

		return $ciphercode;
	}

	public function aktivasi_akun($id_user){

		$data = array('status' => 1);

		$this->db->where('id_user', $id_user);
		$this->db->update('tb_user', $data);
		return ($this->db->affected_rows() != 1) ? false : true;
	}

}
