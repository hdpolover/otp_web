<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    // construct function
	public function __construct()
	{
        parent::__construct();
        
        // cek if user already login
		if ($this->session->userdata('logged_in') == FALSE || !$this->session->userdata('logged_in')) {
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
        
        $this->load->model('M_home');
	}

    public function index()
    {
        $data['history_ip'] = $this->M_home->get_historyIP($this->session->userdata('id_user'));

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('content', $data);
        $this->load->view('templates/footer');
    }

    public function pengaturan()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('pengaturan');
        $this->load->view('templates/footer');
    }
}
