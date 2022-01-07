<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    // construct function
    public function __construct()
    {
        parent::__construct();
        
        // cek if user already login
        if ($this->session->userdata('logged_in') == false || !$this->session->userdata('logged_in')) {
            if (!empty($_SERVER['QUERY_STRING'])) {
                $uri = uri_string() . '?' . $_SERVER['QUERY_STRING'];
            } else {
                $uri = uri_string();
            }
            
            // send to login first, then continued activities
            $this->session->set_userdata('redirect', $uri);
            $this->session->set_flashdata('error', "Harap login ke akun anda, untuk melanjutkan");
            redirect('login');
        }

        $this->load->model(['M_home', 'M_login']);

        if ($this->M_home->cek_aktivasi($this->session->userdata('id_user')) == true) {
            $this->session->set_flashdata('warning', "Harap melakukan aktivasi akun terlebih dahulu!");
            redirect(site_url('aktivasi-akun'));
        }

        // OTP REQUIRE
        if ($this->session->userdata('otp') == false || !$this->session->userdata('otp')) {
            $this->session->set_flashdata('warning', "Harap melakukan proses OTP !");
            redirect(site_url('otp'));
        }
    }

    public function index()
    {
        $data['user'] = $this->M_home->get_userInfo($this->session->userdata('id_user'));

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('content', $data);
        $this->load->view('templates/footer');
    }

    function simpan_info()
    {
        $email = $this->input->post('email');
        $no_telp = $this->input->post('no_telp');

        if ($this->M_home->cekEmail($email, $this->session->userdata('id_user')) == false) {
            if ($this->M_home->cekNo_telp($no_telp, $this->session->userdata('id_user')) == false) {
                if ($this->M_home->simpan_info() == true) {
                    $this->session->set_flashdata('success', 'Berhasil mengubah informasi pribadi anda !');
                    redirect($this->agent->referrer());
                } else {
                    $this->session->set_flashdata('error', 'Anda tidak melakukan perubahan informasi pribadi anda !');
                    redirect($this->agent->referrer());
                }
            } else {
                $this->session->set_flashdata('warning', "Nomor telepon telah digunakan !");
                redirect($this->agent->referrer());
            }
        } else {
            $this->session->set_flashdata('warning', "Email telah digunakan !");
            redirect($this->agent->referrer());
        }
    }
}
