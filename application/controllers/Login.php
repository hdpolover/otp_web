<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    
    private $token;
    
    // Construct
    public function __construct()
    {
        parent::__construct();
        
        // SET TOKEN for SMS SENDER
        $this->token = 'c05c40f4d9795b24863bff930a33d6f6';
        
        // LOAD MODEL MASUK
        $this->load->model('M_login');
    }
    
    // MAIN
    public function index()
    {
        if($this->session->userdata('logged_in') == true || $this->session->userdata('logged_in'))
        {
            $this->session->set_flashdata('warning', 'Anda telah masuk kedalam akun anda !');
            redirect(site_url('home'));
        }else
        {
            $this->load->view('authentication/login');
        }
    }
    
    public function password_hash(){
        echo password_hash("12341234", PASSWORD_DEFAULT);
    }

    public function test_ip(){
        echo $_SERVER['REMOTE_ADDR'];
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
            if (password_verify($password, $user->password)) {
                
                // simpan data user yang login kedalam session 
                $session_data = array(
                    'id_user'   => $user->id_user,
                    'nama'      => $user->nama,
                    'email'     => $user->email,
                    'logged_in' => true,
                );
                
                $this->session->set_userdata($session_data);
                
                // cek aktivasi
                if ($user->status == 0) {
                    $this->session->set_flashdata('warning', "Harap melakukan aktivasi akun !");
                    redirect(site_url('aktivasi-akun'));
                } else {
                    // arahkan ke halaman admin
                    if ($this->session->userdata('redirect')) {
                        $this->session->set_flashdata('success', 'Hai, anda telah masuk. Silahkan melanjutkan aktivitas anda !!');
                        redirect($this->session->userdata('redirect'));
                    } else {
                        // $this->session->set_flashdata('success', "Hai, Selamat datang !");
                        // redirect(site_url('home'));
                        
                        // OTP FIRST
                        $this->session->set_flashdata('success', "harap verifikasi OTP terlebih dahulu !");
                        redirect(site_url('otp'));
                    }
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
    
    // OTP PROCCESS
    function otp_send(){
        if ($this->session->userdata('logged_in') == FALSE || !$this->session->userdata('logged_in')) {
			if (!empty($_SERVER['QUERY_STRING'])) {
				$uri = uri_string() . '?' . $_SERVER['QUERY_STRING'];
			} else {
				$uri = uri_string();
			}
			$this->session->set_userdata('redirect', $uri);
			$this->session->set_flashdata('error', "Harap login ke akun anda, untuk melanjutkan");
			redirect('login');
		}else{
            $this->load->view('authentication/otp_send');
        }
    }
    
    function verifikasi_otp(){
        if ($this->session->userdata('logged_in') == FALSE || !$this->session->userdata('logged_in')) {
			if (!empty($_SERVER['QUERY_STRING'])) {
				$uri = uri_string() . '?' . $_SERVER['QUERY_STRING'];
			} else {
				$uri = uri_string();
			}
			$this->session->set_userdata('redirect', $uri);
			$this->session->set_flashdata('error', "Harap login ke akun anda, untuk melanjutkan");
			redirect('login');
		}else{
            $this->load->view('authentication/otp_login');
        }
    }
    
    
    function proses_verifikasiOtp(){
        
		if ($this->session->userdata('logged_in') == TRUE || $this->session->userdata('logged_in')) {
            
			$kode_otp 	    = htmlspecialchars($this->input->post('kode_otp'), TRUE);
            
            if ($this->M_login->cekOtp_kode(str_replace('-', '', $kode_otp), $this->session->userdata('id_user')) == TRUE) {
                $this->session->set_flashdata('success', "Berhasil memverifikasi, selamat datang !");
                redirect(site_url('home'));
            }else {
                $this->session->set_flashdata('error', 'Kode yang anda masukkan salah, cek kembali email anda !!');
                redirect($this->agent->referrer());
            }
            
		}else {
			if (!empty($_SERVER['QUERY_STRING'])) {
				$uri = uri_string() . '?' . $_SERVER['QUERY_STRING'];
			} else {
				$uri = uri_string();
			}
			$this->session->unset_userdata('redirect');
			$this->session->set_userdata('redirect', $uri);
			$this->session->set_flashdata('error', "Harap login ke akun anda, untuk melanjutkan");
			redirect('login');
		}
    }
    
    function send_otp_sms(){
		if ($this->session->userdata('logged_in') == TRUE || $this->session->userdata('logged_in')) {
            
			$email 		= htmlspecialchars($this->session->userdata('email'), TRUE);
            
			if ($this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), TRUE)) == FALSE) {
                
				$this->session->set_flashdata('error', 'Terjadi kesalahan saat mengambil data anda !!');
				redirect(site_url('login'));
                
			}else {
                
                // create & save OTP (must call every proccess)
                if ($this->M_login->create_otp($this->session->userdata('id_user')) == TRUE) {
                    
                    $user   = $this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), TRUE));
                    
                    if ($user->status != 0) {
                        
                        $to 	= $user->no_telp;
                        $otp    = $this->encryption->decrypt($user->otp);
                        $msg 	= "kode otp anda: {$otp}";
                        
                        $url    = "https://websms.co.id/api/smsgateway?token={$this->token}&to={$to}&msg={$msg}";
                        // echo $url;
                        $header = array(
                            'Accept: application/json',
                        );
                        
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                        $result = curl_exec($ch);
                        
                        // echo $result;
                        
                        $a = json_decode($result,true);
                        
                        if ($a['status'] == "success") {
                            $this->session->set_flashdata('success', 'Berhasil mengirimkan kode OTP ke nomor anda, harap cek kontak masuk anda !');
                            redirect(site_url('verifikasi-otp'));
                        } else {
                            $error = $a['message'];
                            $this->session->set_flashdata('error', 'Terjadi kesalahan saat mengirimkan pesan ke email anda {$error} !');
                            redirect(site_url('otp'));
                        }
                        
                    }else {
                        $this->session->set_flashdata('warning', 'Harap aktivasi akun anda terlebih dahulu !');
                        redirect(site_url('aktivasi-akun'));
                    }
                    
                } else {
                    $this->session->set_flashdata('error', 'Terjadi kesalahan saat membuat kode OTP anda !');
                    redirect(site_url('otp'));
                }
			}
            
		}else {
			if (!empty($_SERVER['QUERY_STRING'])) {
				$uri = uri_string() . '?' . $_SERVER['QUERY_STRING'];
			} else {
				$uri = uri_string();
			}
			$this->session->unset_userdata('redirect');
			$this->session->set_userdata('redirect', $uri);
			$this->session->set_flashdata('error', "Harap login ke akun anda, untuk melanjutkan");
			redirect('login');
		}
    }
    
    function send_otp_email(){
		if ($this->session->userdata('logged_in') == TRUE || $this->session->userdata('logged_in')) {
            
			$email 		= htmlspecialchars($this->session->userdata('email'), TRUE);
            
			if ($this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), TRUE)) == FALSE) {
                
				$this->session->set_flashdata('error', 'Terjadi kesalahan saat mengambil data anda !!');
				redirect(site_url('login'));
                
			}else {
                
                // create & save OTP (must call every proccess)
                if ($this->M_login->create_otp($this->session->userdata('id_user')) == TRUE) {
                    
                    $aktivasi   = $this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), TRUE));
                    
                    if ($aktivasi->status != 0) {
                        $subject	= "KODE OTP";
                        $message 	= "Kode OTP anda <b>{$this->encryption->decrypt($aktivasi->otp)}</b></br>";
                        
                        if ($this->send_email($email, $subject, $message) == TRUE) {
                            $this->session->set_flashdata('success', 'Berhasil mengirimkan kode OTP ke email anda, harap cek inbox atau folder spam anda !');
                            redirect(site_url('verifikasi-otp'));
                        }else {
                            $this->session->set_flashdata('error', 'Terjadi kesalahan saat mengirimkan pesan ke email anda !');
                            redirect(site_url('otp'));
                        }
                    }else {
                        $this->session->set_flashdata('warning', 'Harap aktivasi akun anda terlebih dahulu !');
                        redirect(site_url('aktivasi-akun'));
                    }
                    
                } else {
                    $this->session->set_flashdata('error', 'Terjadi kesalahan saat membuat kode OTP anda !');
                    redirect(site_url('otp'));
                }
			}
            
		}else {
			if (!empty($_SERVER['QUERY_STRING'])) {
				$uri = uri_string() . '?' . $_SERVER['QUERY_STRING'];
			} else {
				$uri = uri_string();
			}
			$this->session->unset_userdata('redirect');
			$this->session->set_userdata('redirect', $uri);
			$this->session->set_flashdata('error', "Harap login ke akun anda, untuk melanjutkan");
			redirect('login');
		}
    }
    
    // RECOVERY PROCESS
    public function lupa_password()
    {
        $this->load->view('authentication/lupa_password');
    }
    
    public function ubah_password($email = null)
    {
        
        if ($this->M_login->cek_user($email) == true) {
            $user             = $this->M_login->get_user($email);
            
            $data['id_user']  = $user->id_user;
            $data['email']    = $email;
            
            $this->load->view('authentication/ubah_password', $data);
        } else {
            $this->session->set_flashdata('error', 'Tidak dapat menemukan akun dengan email tersebut !');
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
    
	// ACTIVATION ACCOUNT PROCCESS
	public function aktivasi_email(){
		if ($this->session->userdata('logged_in') == TRUE) {
			$email 		= htmlspecialchars($this->session->userdata('email'), TRUE);
            
			if ($this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), TRUE)) == FALSE) {
				$this->session->set_flashdata('error', 'Terjadi kesalahan saat mengambil data anda !!');
				redirect(site_url('login'));
                
			}else {
				$aktivasi = $this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), TRUE));
                
				if ($aktivasi->status == 0) {
					$subject	= "KODE AKTIVASI AKUN";
					$message 	= "Kode aktivasi anda: <br><br><center><b style'font-size: 20px;'>{$this->encryption->decrypt($aktivasi->aktivasi)}</b></center><br><br>";
                    
					if ($this->send_email($email, $subject, $message) == TRUE) {
                        
						$data['mail']			= $email;
						$data['kode_aktivasi']	= $this->encryption->decrypt($aktivasi->aktivasi);
                        $this->load->view('authentication/aktivasi', $data);
                        
					}else {
						$this->session->set_flashdata('error', 'Terjadi kesalahan saat mengirimkan pesan ke email anda !!');
						redirect(site_url('aktivasi-akun'));
					}
				}else {
					redirect('peserta');
				}
			}
		}else {
			if (!empty($_SERVER['QUERY_STRING'])) {
				$uri = uri_string() . '?' . $_SERVER['QUERY_STRING'];
			} else {
				$uri = uri_string();
			}
			$this->session->unset_userdata('redirect');
			$this->session->set_userdata('redirect', $uri);
			$this->session->set_flashdata('error', "Harap login ke akun anda, untuk melanjutkan");
			redirect('login');
		}
	}
    
	public function waiting(){
		if ($this->session->userdata('logged_in') == TRUE || $this->session->userdata('logged_in')) {
            
			$email 		= htmlspecialchars($this->session->userdata('email'), TRUE);
            
			if ($this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), TRUE)) == FALSE) {
                
				$this->session->set_flashdata('error', 'Terjadi kesalahan saat mengambil data anda !!');
				redirect(site_url('login'));
                
			}else {
				$aktivasi = $this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), TRUE));
                
				if ($aktivasi->status == 0) {
					$subject	= "KODE AKTIVASI AKUN";
					$message 	= "Kode aktivasi anda <b>{$this->encryption->decrypt($aktivasi->aktivasi)}</b></br>";
                    
					if ($this->send_email($email, $subject, $message) == TRUE) {
                        
						$data['mail']		= $email;
                        $this->load->view('authentication/aktivasi_tunggu', $data);
                        
					}else {
						$this->session->set_flashdata('error', 'Terjadi kesalahan saat mengirimkan pesan ke email anda !!');
						redirect(site_url('aktivasi-akun'));
					}
				}else {
					redirect('home');
				}
			}
            
		}else {
			if (!empty($_SERVER['QUERY_STRING'])) {
				$uri = uri_string() . '?' . $_SERVER['QUERY_STRING'];
			} else {
				$uri = uri_string();
			}
			$this->session->unset_userdata('redirect');
			$this->session->set_userdata('redirect', $uri);
			$this->session->set_flashdata('error', "Harap login ke akun anda, untuk melanjutkan");
			redirect('login');
		}
	}
    
	function aktivasi_akun(){
        
		if ($this->session->userdata('logged_in') == TRUE || $this->session->userdata('logged_in')) {
            
			$kode_aktivasi 	= htmlspecialchars($this->input->post('kode_aktivasi'), TRUE);
			$aktivasi 		= $this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), TRUE), TRUE);
            
            if ($this->M_login->aktivasi_kode(str_replace('-', '', $kode_aktivasi), $this->session->userdata('id_user')) == TRUE) {
                if ($this->M_login->aktivasi_akun($this->session->userdata('id_user')) == TRUE) {
                    
                    // $this->session->set_flashdata('success', 'Berhasil aktivasi akun !!');
                    // redirect('home');
                    
                    // OTP FIRST
                    $this->session->set_flashdata('success', "Berhasil aktivasi akun, harap verifikasi OTP terlebih dahulu !");
                    redirect(site_url('otp'));
                }else {
                    $this->session->set_flashdata('error', 'Terjadi kesalahan saat mencoba meng-aktivasi akun anda !!');
                    redirect($this->agent->referrer());
                }
            }else {
                $this->session->set_flashdata('error', 'Kode yang anda masukkan salah, cek kembali email anda !!');
                redirect($this->agent->referrer());
            }
            
		}else {
			if (!empty($_SERVER['QUERY_STRING'])) {
				$uri = uri_string() . '?' . $_SERVER['QUERY_STRING'];
			} else {
				$uri = uri_string();
			}
			$this->session->unset_userdata('redirect');
			$this->session->set_userdata('redirect', $uri);
			$this->session->set_flashdata('error', "Harap login ke akun anda, untuk melanjutkan");
			redirect('login');
		}
        
	}
    
	// LOGOUT
	public function logout(){
        
		// SESS DESTROY
		$user_data = $this->session->all_userdata();
        
		foreach ($user_data as $key => $value) {
			if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
				$this->session->unset_userdata($key);
			}
		}
        
		$this->session->sess_destroy();
        
		if ($this->input->get("act")) {
			if (!empty($_SERVER['QUERY_STRING'])) {
				$uri = uri_string() . '?' . $_SERVER['QUERY_STRING'];
			} else {
				$uri = uri_string();
			}
			$this->session->unset_userdata('redirect');
			$this->session->set_userdata('redirect', $uri);
			$this->session->set_flashdata('error', "Harap login ke akun anda, untuk melanjutkan");
			redirect('login');
		}else {
			$this->session->set_flashdata('success','Berhasil keluar!');
			redirect(base_url());
		}
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
