<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_home extends CI_Model {
	function __construct(){
		parent::__construct();
  }
  
  public function get_historyIP($id_user){
    $this->db->select('*');
    $this->db->from('tb_login_ip');
    $this->db->where('id_user', $id_user);
    $query = $this->db->get();
    
    // jika hasil dari query diatas memiliki lebih dari 0 record
    if ($query->num_rows() > 0) {
      return $query->result();
    } else {
      return false;
    }
    
  }
  
}
