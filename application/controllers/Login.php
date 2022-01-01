<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        // LOAD MODEL MASUK
        $this->load->model('M_login');
    }
    
    public function index()
    {
        if($this->session->userdata('logged_in') == true || $this->session->userdata('logged_in'))
        {
            $this->session->set_flashdata('warning', 'Anda telah masuk kedalam akun anda !');
            redirect(base_url());
        }else
        {
            $this->load->view('login');
        }
    }
    
    public function lupa_password()
    {
        $this->load->view('lupa_password');
    }
    
    public function ubah_password($email = null)
    {
        
        if ($this->M_login->cek_user($email) == true) {
            $user             = $this->M_login->get_user($email);
            
            $data['id_user']  = $user->id_user;
            $data['email']    = $email;
            
            $this->load->view('ubah_password', $data);
        } else {
            $this->session->set_flashdata('error', 'Tidak dapat menemukan akun dengan email tersebut !');
            redirect(site_url('login'));
        }
    }
    
    public function password_hash(){
        echo password_hash("12341234", PASSWORD_DEFAULT);
    }
    
    public function proses_login()
    {
        // ambil inputan dari view
        $email      = $this->input->post('email');
        $password   = $this->input->post('password');
        
        // cek apakah data user ada, berdasarkan email yang dimasukkan
        if ($this->M_login->cek_user($email) == true) {
            // ambil data user, menjadi array
            $user     = $this->M_login->get_user($email);
            
            // cek apakah password yang dimasukkan sama dengan database
            if (password_verify($password, $user->PASSWORD)) {
                
                // simpan data user yang login kedalam session 
                $session_data = array(
                    'id_user'   => $user->id_user,
                    'nama'      => $user->nama,
                    'email'     => $user->email,
                    'logged_in' => true,
                );
                
                $this->session->set_userdata($session_data);
                
                // arahkan ke halaman admin
                if ($this->session->userdata('redirect')) {
                    $this->session->set_flashdata('success', 'Hai, anda telah masuk. Silahkan melanjutkan aktivitas anda !!');
                    redirect($this->session->userdata('redirect'));
                } else {
                    $this->session->set_flashdata('success', "Hai, admin. Selamat datang !");
                    redirect(site_url('home'));
                }
                
            } else {
                $this->session->set_flashdata('warning', "Mohon maaf, password yang anda masukkan salah !");
                redirect(site_url('login'));
            }
            
        } else {
            $this->session->set_flashdata('error', "Mohon maaf, user tidak terdaftar !");
            redirect(site_url('login'));
        }
        
    }
    
    public function proses_lupa(){
        $email      = $this->input->post('email');
        
        if ($this->M_login->cek_user($email) == true) {
            
            $user     = $this->M_login->get_user($email);
            
            $subject  = "Pemulihan password - {$user->email}";
            $message  = "Hai, {$user->nama} kami mendapatkan permintaan pemulihan password atas nama email {$user->email} harap klik link berikut ini untuk memulihkan password anda, atau abaikan email ini jika anda tidak merasa melakukan proses pemulihan akun.</br>".base_url()."recovery-password/".$email."</br></br></br></br>";
            
            if ($this->send_email($email, $subject, $message)) {
                $this->session->set_flashdata('success', "Berhasil mengirim link pemulihan password anda, harap cek email anda !");
                redirect($this->agent->referrer());
            } else {
                $this->session->set_flashdata('error', "Terjadi kesalahan, saat mengirimkan email pemulihan password, coba lagi nanti !");
                redirect($this->agent->referrer());
            }
            
        } else {
            $this->session->set_flashdata('warning', "Tidak dapat menemukan akun atas nama email {$email}. Pastikan email tersebut telah terdaftar diwebsite kami !");
            redirect($this->agent->referrer());
        }
        
        
    }
    
    public function proses_ubahPassword(){
        $id_user        = $this->input->post('id_user');
        $password       = $this->input->post('password');
        $password_conf  = $this->input->post('password_conf');
        
        if ($password == $password_conf) {
            $data_user  = array(
            'password'  => password_hash($password, PASSWORD_DEFAULT)
            );
            $where      = array('id_user' => $id_user);
            $user       = $this->M_login->get_userByID($id_user);
            
            if ($this->M_login->update_password($data_user, $where)) {
                
                $now      = date("d F Y - H:i");
                
                $subject  = "Perubahan password - {$user->email}";
                $message  = "Hai, {$user->nama} password kamu telah dirubah, pada {$now}. Harap hubungi admin jika ini bukan anda atau abaikan email ini.</br></br></br></br>";
                
                $this->send_email($user->email, $subject, $message);
                
                $this->session->set_flashdata('success', "Berhasil merubah password anda, harap login untuk melanjutkan !");
                redirect(site_url('login'));
            } else {
                $this->session->set_flashdata('error', "Terjadi kesalahan, saat mengubah password anda, coba lagi nanti !");
                redirect($this->agent->referrer());
            }
        } else {
            $this->session->set_flashdata('warning', "Password yang anda masukkan tidak sama, harap coba lagi !");
            redirect($this->agent->referrer());
        }
        
    }
    
    public function keluar(){
        
		// SESS DESTROY
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', "Anda berhasil keluar !");
        redirect(base_url());
    }
    
	// MAILER SENDER
    
    function send_email($email, $subject, $message){
        
        $mail = array(
            'to' 			=> $email,
            'subject'		=> $subject,
            'message'		=> $message
        );
        
        if ($this->mailer->send($mail) == TRUE) {
            return true;
        }else {
            return false;
        }
    }
    
    
}
