<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function index()
    {
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar');
        $this->load->view('content');
        $this->load->view('templates/footer');
    }

    public function a()
    {
        $this->load->view('login_1');
    }

    public function b()
    {
        $this->load->view('register_1');
    }
}
