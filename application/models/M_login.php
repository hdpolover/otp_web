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

}
