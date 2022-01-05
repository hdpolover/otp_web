<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_home extends CI_Model
{
  function __construct()
  {
    parent::__construct();
  }

  function cek_aktivasi($id_user)
  {
    $query = $this->db->get_where('tb_user', array('id_user' => $id_user))->row();
    if ($query->status == 0) {
      return true;
    } else {
      return false;
    }

  }

  public function get_userInfo($id_user)
  {
    $this->db->select('*');
    $this->db->from('tb_user');
    $this->db->where('id_user', $id_user);
    $query = $this->db->get();
    
    // jika hasil dari query diatas memiliki lebih dari 0 record
    if ($query->num_rows() > 0) {
      return $query->row();
    } else {
      return false;
    }

  }

  function simpan_info()
  {
    $id_user = $this->session->userdata('id_user');
    $nama = $this->input->post('nama');
    $no_telp = $this->input->post('no_telp');
    $email = $this->input->post('email');

    // simpan data user yang login kedalam session 
    $session_data = array(
      'nama' => $nama,
      'no_telp' => $no_telp,
      'email' => $email
    );

    $this->session->set_userdata($session_data);

    $data = array(
      'nama' => $nama,
      'no_telp' => $no_telp,
      'email' => $email
    );

    $this->db->where('id_user', $id_user);
    $this->db->update('tb_user', $data);
    return $this->db->affected_rows() == true;
  }

}
