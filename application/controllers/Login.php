<?php
defined('BASEPATH') or exit('No direct script access allowed');
ini_set('allow_url_fopen', 1);

use GuzzleHttp\Client;

class Login extends CI_Controller
{

    private $token;
    private $token_wa;
    private $url_wa;

    // Construct
    public function __construct()
    {
        parent::__construct();

        // SET TOKEN for SMS SENDER
        $this->token = 'c05c40f4d9795b24863bff930a33d6f6';

        // SET TOKEN for WA SENDER
        $this->token_wa = '0EZJI9yIlyJmc3x8XyxEulDGlpav4yezMVEGXlB7Me06mt04HGkyG0fabwP2uf0w';

        // SET URL API FOR WA APi
        $this->url_wa = 'https://sambi.wablas.com';

        // LOAD MODEL MASUK
        $this->load->model('M_login');
    }

    public function test_wa()
    {
        $curl = curl_init();
        $payload = [
            "data" => [
                [
                    'phone' => '6285785111746',
                    'message' => 'try message 1',
                    'secret' => false, // or true
                    'priority' => false, // or true
                ]
            ]
        ];

        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                "Authorization: {$this->token_wa}",
                "Content-Type: application/json"
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($curl, CURLOPT_URL, "{$this->url_wa}/api/v2/send-bulk/text");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);

        echo "<pre>";
        print_r($result);
    }

    // MAIN
    public function index()
    {
        if ($this->session->userdata('logged_in') == true || $this->session->userdata('logged_in')) {
            $this->session->set_flashdata('warning', 'Berhasil masuk ke akun.');
            redirect(site_url('home'));
        } else {
            if ($this->input->get('act') == "account-activated") {
                $this->session->set_flashdata('success', 'Berhasil aktivasi akun, silahkan login kedalam akun anda.');
            }
            $this->load->view('authentication/login');
        }
    }

    public function blocked()
    {
        $this->load->view('authentication/blocked');
    }

    public function password_hash()
    {
        echo password_hash("12341234", PASSWORD_DEFAULT);
    }

    public function test_agent()
    {
        if ($this->agent->is_browser()) {
            $agent = $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_mobile()) {
            $agent = $this->agent->mobile();
        } else {
            $agent = 'Data akun gagal didapatkan!';
        }
        echo "Diakses dari :<br/>";

        echo "Browser = " . $agent . "<br/>";

        echo "Sistem Operasi = " . $this->agent->platform() . "<br/>";
        echo "<br>IP: " . $this->input->ip_address();
    }

    public function proses_login()
    {
        // ambil inputan dari view
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        // cek apakah data user ada, berdasarkan email yang dimasukkan
        if ($this->M_login->cek_user($email) == true) {
            // ambil data user, menjadi array
            $user = $this->M_login->get_user($email);

            // cek apakah password yang dimasukkan sama dengan database
            if (password_verify($password, $user->password)) {

                // simpan data user yang login kedalam session 
                $session_data = array(
                    'id_user' => $user->id_user,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'no_telp' => $user->no_telp,
                    'logged_in' => true,
                );

                $this->session->set_userdata($session_data);

                // cek aktivasi
                if ($user->status == 0) {
                    $this->session->set_flashdata('warning', "Harap melakukan aktivasi akun terlebih dahulu!");
                    redirect(site_url('aktivasi-akun'));
                } else {
                    if ($this->session->userdata('redirect')) {
                        $this->session->set_flashdata('success', 'Anda telah masuk. Silahkan melanjutkan aktivitas anda!');
                        redirect($this->session->userdata('redirect'));
                    } else {
                        $this->session->set_flashdata('success', "Selamat datang!");
                        redirect(site_url('home'));
                    }
                }
            } else {
                $this->session->set_flashdata('warning', "Mohon maaf. Password yang Anda masukkan salah!");
                redirect(site_url('login'));
            }
        } else {
            $this->session->set_flashdata('error', "Mohon maaf. Akun tidak terdaftar!");
            redirect(site_url('login'));
        }
    }

    // OTP PROCCESS
    function otp_send()
    {
        if ($this->session->userdata('logged_in') == false || !$this->session->userdata('logged_in')) {
            if (!empty($_SERVER['QUERY_STRING'])) {
                $uri = uri_string() . '?' . $_SERVER['QUERY_STRING'];
            } else {
                $uri = uri_string();
            }
            $this->session->set_userdata('redirect', $uri);
            $this->session->set_flashdata('error', "Harap login ke akun Anda untuk melanjutkan!");
            redirect('login');
        } else {
            // OTP Check
            if ($this->session->userdata('otp') == false || !$this->session->userdata('otp')) {
                $this->load->view('authentication/otp_send');
            } else {
                $this->session->set_flashdata('warning', "Anda telah melakukan proses OTP !");
                redirect(site_url('home'));
            }
        }
    }

    function verifikasi_otp()
    {
        if ($this->session->userdata('logged_in') == false || !$this->session->userdata('logged_in')) {
            if (!empty($_SERVER['QUERY_STRING'])) {
                $uri = uri_string() . '?' . $_SERVER['QUERY_STRING'];
            } else {
                $uri = uri_string();
            }
            $this->session->set_userdata('redirect', $uri);
            $this->session->set_flashdata('error', "Harap login ke akun Anda untuk melanjutkan!");
            redirect('login');
        } else {
            // OTP Check
            if ($this->session->userdata('otp') == false || !$this->session->userdata('otp')) {
                $this->load->view('authentication/otp_login');
            } else {
                $this->session->set_flashdata('warning', "Anda telah melakukan proses OTP !");
                redirect(site_url('home'));
            }
        }
    }


    function proses_verifikasiOtp()
    {

        if ($this->session->userdata('logged_in') == true || $this->session->userdata('logged_in')) {

            $kode_otp = htmlspecialchars($this->input->post('kode_otp'), true);
            $data_otp = $this->M_login->get_dataOTP($this->session->userdata('id_user'));
            // cek apakah waktu token valid kurang dari 1 menit
            if (time() - $data_otp->expired_otp < (60)) {

                if ($this->M_login->cekOtp_kode(str_replace('-', '', $kode_otp), $this->session->userdata('id_user')) == true) {

                    // simpan data user yang login kedalam session 
                    $session_data = array(
                        'otp' => true,
                    );

                    $this->session->set_userdata($session_data);

                    $this->session->set_flashdata('success', "Berhasil verifikasi OTP. Selamat datang!");
                    redirect(site_url('home'));
                } else {
                    $this->session->set_flashdata('error', 'Kode yang anda masukkan salah. Cek kembali email anda!');
                    redirect($this->agent->referrer());
                }
            } else {

                $this->session->set_flashdata('error', 'Anda telah melewati batas waktu OTP, harap mengulang proses OTP. ');
                redirect(site_url('otp'));
            }
        } else {
            if (!empty($_SERVER['QUERY_STRING'])) {
                $uri = uri_string() . '?' . $_SERVER['QUERY_STRING'];
            } else {
                $uri = uri_string();
            }
            $this->session->unset_userdata('redirect');
            $this->session->set_userdata('redirect', $uri);
            $this->session->set_flashdata('error', "Harap login ke akun Anda untuk melanjutkan!");
            redirect('login');
        }
    }

    function send_otp_sms()
    {
        if ($this->session->userdata('logged_in') == true || $this->session->userdata('logged_in')) {

            $email = htmlspecialchars($this->session->userdata('email'), true);

            if ($this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), true)) == false) {

                $this->session->set_flashdata('error', 'Terjadi kesalahan saat mengambil data.');
                redirect(site_url('login'));
            } else {

                // create & save OTP (must call every proccess)
                if ($this->M_login->create_otp($this->session->userdata('id_user')) == true) {

                    $user = $this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), true));

                    if ($user->status != 0) {

                        $client = new Client();

                        $to = $user->no_telp;
                        $otp = $this->encryption->decrypt($user->otp);
                        // $msg     = "#KODE OTP webotpku.xyz#  Jangan bagikan kode ini kepada siapapun. KODE OTP: {$otp}. Hiraukan jika tidak membutuhkan.";
                        $msg = "Hai {$this->session->userdata('nama')}, nomor OTP anda adalah: {$otp}. Jangan bagikan ke siapapun. Kode ini hanya aktif selama 1 menit.";

                        $url = "https://websms.co.id/api/smsgateway-otp?token={$this->token}&to={$to}&msg={$msg}";

                        $response = $client->request('GET', $url);

                        $data = $response->getBody();
                        $read_json = json_decode($data, true);

                        if ($read_json['status'] == "success") {
                            $this->session->set_flashdata('success', 'Berhasil mengirimkan kode OTP ke nomor Anda. Harap cek kontak masuk Anda!');
                            redirect(site_url('verifikasi-otp'));
                        } else {
                            $error = $read_json['message'];
                            $this->session->set_flashdata('error', 'Terjadi kesalahan saat mengirimkan pesan ke nomor anda ' . $error . ' !');
                            redirect(site_url('otp'));
                        }

                        // $this->session->set_flashdata('success', 'Berhasil mengirimkan kode OTP ke nomor Anda. Harap cek kontak masuk Anda!');
                        // redirect(site_url('verifikasi-otp'));
                    } else {
                        $this->session->set_flashdata('warning', 'Harap aktivasi akun Anda terlebih dahulu!');
                        redirect(site_url('aktivasi-akun'));
                    }
                } else {
                    $this->session->set_flashdata('error', 'Terjadi kesalahan saat membuat kode OTP anda !');
                    redirect(site_url('otp'));
                }
            }
        } else {
            if (!empty($_SERVER['QUERY_STRING'])) {
                $uri = uri_string() . '?' . $_SERVER['QUERY_STRING'];
            } else {
                $uri = uri_string();
            }
            $this->session->unset_userdata('redirect');
            $this->session->set_userdata('redirect', $uri);
            $this->session->set_flashdata('error', "Harap login ke akun Anda untuk melanjutkan!");
            redirect('login');
        }
    }

    function send_otp_email()
    {
        if ($this->session->userdata('logged_in') == true || $this->session->userdata('logged_in')) {

            $email = htmlspecialchars($this->session->userdata('email'), true);

            if ($this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), true)) == false) {

                $this->session->set_flashdata('error', 'Terjadi kesalahan saat mengambil data anda !!');
                redirect(site_url('login'));
            } else {

                // create & save OTP (must call every proccess)
                if ($this->M_login->create_otp($this->session->userdata('id_user')) == true) {

                    $aktivasi = $this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), true));

                    if ($aktivasi->status != 0) {
                        $subject = "KODE OTP";
                        $message = "Hai {$this->session->userdata('nama')}, nomor OTPmu adalah: <b>{$this->encryption->decrypt($aktivasi->otp)}</b>. Jangan bagikan ke siapapun.<br> Kode ini hanya aktif selama 1 menit.";

                        if ($this->send_email($email, $subject, $message) == true) {
                            $this->session->set_flashdata('success', 'Berhasil mengirimkan kode OTP ke email Anda. Harap cek kotak masuk atau folder spam Anda!');
                            redirect(site_url('verifikasi-otp'));
                        } else {
                            $this->session->set_flashdata('error', 'Terjadi kesalahan saat mengirimkan pesan ke email anda !');
                            redirect(site_url('otp'));
                        }
                    } else {
                        $this->session->set_flashdata('warning', 'Harap aktivasi akun anda terlebih dahulu !');
                        redirect(site_url('aktivasi-akun'));
                    }
                } else {
                    $this->session->set_flashdata('error', 'Terjadi kesalahan saat membuat kode OTP anda !');
                    redirect(site_url('otp'));
                }
            }
        } else {
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

    function send_otp_wa()
    {
        if ($this->session->userdata('logged_in') == true || $this->session->userdata('logged_in')) {

            $email = htmlspecialchars($this->session->userdata('email'), true);

            if ($this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), true)) == false) {

                $this->session->set_flashdata('error', 'Terjadi kesalahan saat mengambil data.');
                redirect(site_url('login'));
            } else {

                // create & save OTP (must call every proccess)
                if ($this->M_login->create_otp($this->session->userdata('id_user')) == true) {

                    $user = $this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), true));

                    if ($user->status != 0) {

                        $to = $user->no_telp;
                        $otp = $this->encryption->decrypt($user->otp);

                        // $msg     = "#KODE OTP webotpku.xyz#  Jangan bagikan kode ini kepada siapapun. KODE OTP: {$otp}. Hiraukan jika tidak membutuhkan.";
                        $msg = "Hai {$this->session->userdata('nama')}, nomor OTP anda adalah: {$otp}. Jangan bagikan ke siapapun. Kode ini hanya aktif selama 1 menit.";

                        $endpoint = "api/v2/send-bulk/text";

                        $curl = curl_init();
                        $payload = [
                            "data" => [
                                [
                                    'phone' => $to,
                                    'message' => $msg,
                                    'secret' => false, // or true
                                    'priority' => false, // or true
                                ]
                            ]
                        ];

                        curl_setopt(
                            $curl,
                            CURLOPT_HTTPHEADER,
                            array(
                                "Authorization: {$this->token_wa}",
                                "Content-Type: application/json"
                            )
                        );
                        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
                        curl_setopt($curl, CURLOPT_URL, "{$this->url_wa}/{$endpoint}");
                        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                        $result = curl_exec($curl);
                        curl_close($curl);

                        $result = json_decode($result, true);
                        
                        if ($result['status'] == "success") {
                            $this->session->set_flashdata('success', 'Berhasil mengirimkan kode OTP ke nomor Anda. Harap cek kontak masuk Anda!');
                            redirect(site_url('verifikasi-otp'));
                        } else {
                            $error = $result['message'];
                            $this->session->set_flashdata('error', 'Terjadi kesalahan saat mengirimkan pesan ke nomor anda ' . $error . ' !');
                            redirect(site_url('otp'));
                        }

                        // $this->session->set_flashdata('success', 'Berhasil mengirimkan kode OTP ke nomor Anda. Harap cek kontak masuk Anda!');
                        // redirect(site_url('verifikasi-otp'));
                    } else {
                        $this->session->set_flashdata('warning', 'Harap aktivasi akun Anda terlebih dahulu!');
                        redirect(site_url('aktivasi-akun'));
                    }
                } else {
                    $this->session->set_flashdata('error', 'Terjadi kesalahan saat membuat kode OTP anda !');
                    redirect(site_url('otp'));
                }
            }
        } else {
            if (!empty($_SERVER['QUERY_STRING'])) {
                $uri = uri_string() . '?' . $_SERVER['QUERY_STRING'];
            } else {
                $uri = uri_string();
            }
            $this->session->unset_userdata('redirect');
            $this->session->set_userdata('redirect', $uri);
            $this->session->set_flashdata('error', "Harap login ke akun Anda untuk melanjutkan!");
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
            $user = $this->M_login->get_user($email);

            $data['id_user'] = $user->id_user;
            $data['email'] = $email;

            $this->load->view('authentication/ubah_password', $data);
        } else {
            $this->session->set_flashdata('error', 'Tidak dapat menemukan akun dengan email tersebut !');
            redirect(site_url('login'));
        }
    }

    public function proses_lupa()
    {
        $email = $this->input->post('email');

        if ($this->M_login->cek_user($email) == true) {

            $user = $this->M_login->get_user($email);

            $subject = "Pemulihan password - {$user->email}";
            $message = "Hai, {$user->nama}. Kami mendapatkan permintaan pemulihan password atas nama email <b>{$user->email}</b>.<br>Harap klik link berikut ini untuk memulihkan password anda, atau abaikan email ini jika anda tidak merasa melakukan proses pemulihan akun.<br><br><b><i>" . base_url() . "recovery-password/{$email}</i></b>";

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

    public function proses_ubahPassword()
    {
        $id_user = $this->input->post('id_user');
        $password = $this->input->post('password');
        $password_conf = $this->input->post('password_conf');

        if ($password == $password_conf) {
            $data_user = array(
                'password' => password_hash($password, PASSWORD_DEFAULT)
            );
            $where = array('id_user' => $id_user);
            $user = $this->M_login->get_userByID($id_user);

            if ($this->M_login->update_password($data_user, $where)) {

                $now = date("d F Y - H:i");

                $subject = "Perubahan password - {$user->email}";
                $message = "Hai, {$user->nama} password kamu telah dirubah, pada {$now}. Harap hubungi admin jika ini bukan anda atau abaikan email ini.</br></br></br></br>";

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
    public function aktivasi_email()
    {
        if ($this->session->userdata('logged_in') == true) {
            $email = htmlspecialchars($this->session->userdata('email'), true);

            if ($this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), true)) == false) {
                $this->session->set_flashdata('error', 'Terjadi kesalahan saat mengambil data anda !!');
                redirect(site_url('login'));
            } else {
                $aktivasi = $this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), true));

                if ($aktivasi->status == 0) {
                    $subject = "KODE AKTIVASI AKUN";
                    $message = "Kode aktivasi anda: <br><br><center><b style'font-size: 20px;'>{$this->encryption->decrypt($aktivasi->aktivasi)}</b></center><br><br>";

                    if ($this->send_email($email, $subject, $message) == true) {

                        $data['mail'] = $email;
                        $data['kode_aktivasi'] = $this->encryption->decrypt($aktivasi->aktivasi);
                        $this->load->view('authentication/aktivasi', $data);
                    } else {
                        $this->session->set_flashdata('error', 'Terjadi kesalahan saat mengirimkan pesan ke email anda !!');
                        redirect(site_url('aktivasi-akun'));
                    }
                } else {
                    $this->session->set_flashdata('error', 'Anda telah mengaktivasi akun anda !!');
                    redirect(site_url('home'));
                }
            }
        } else {
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

    public function waiting()
    {
        if ($this->session->userdata('logged_in') == true || $this->session->userdata('logged_in')) {

            $email = htmlspecialchars($this->session->userdata('email'), true);

            if ($this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), true)) == false) {

                $this->session->set_flashdata('error', 'Terjadi kesalahan saat mengambil data anda !!');
                redirect(site_url('login'));
            } else {
                $aktivasi = $this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), true));

                if ($aktivasi->status == 0) {
                    $subject = "KODE AKTIVASI AKUN";
                    $message = "Kode aktivasi anda <b>{$this->encryption->decrypt($aktivasi->aktivasi)}</b></br>";

                    if ($this->send_email($email, $subject, $message) == true) {

                        $data['mail'] = $email;
                        $this->load->view('authentication/aktivasi_tunggu', $data);
                    } else {
                        $this->session->set_flashdata('error', 'Terjadi kesalahan saat mengirimkan pesan ke email anda !!');
                        redirect(site_url('aktivasi-akun'));
                    }
                } else {
                    redirect('home');
                }
            }
        } else {
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

    function aktivasi_akun()
    {

        if ($this->session->userdata('logged_in') == true || $this->session->userdata('logged_in')) {

            $kode_aktivasi = htmlspecialchars($this->input->post('kode_aktivasi'), true);
            $aktivasi = $this->M_login->get_aktivasi(htmlspecialchars($this->session->userdata('id_user'), true), true);

            if ($this->M_login->aktivasi_kode(str_replace('-', '', $kode_aktivasi), $this->session->userdata('id_user')) == true) {
                if ($this->M_login->aktivasi_akun($this->session->userdata('id_user')) == true) {

                    // $this->session->set_flashdata('success', 'Berhasil aktivasi akun !!');
                    // redirect('home');

                    // SESS DESTROY
                    $user_data = $this->session->all_userdata();

                    foreach ($user_data as $key => $value) {
                        if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
                            $this->session->unset_userdata($key);
                        }
                    }

                    $this->session->sess_destroy();

                    // SUCCESS
                    redirect(site_url('login?act=account-activated'));
                } else {
                    $this->session->set_flashdata('error', 'Terjadi kesalahan saat mencoba meng-aktivasi akun anda !!');
                    redirect($this->agent->referrer());
                }
            } else {
                $this->session->set_flashdata('error', 'Kode yang anda masukkan salah, cek kembali email anda !!');
                redirect($this->agent->referrer());
            }
        } else {
            if (!empty($_SERVER['QUERY_STRING'])) {
                $uri = uri_string() . '?' . $_SERVER['QUERY_STRING'];
            } else {
                $uri = uri_string();
            }
            $this->session->unset_userdata('redirect');
            $this->session->set_userdata('redirect', $uri);
            $this->session->set_flashdata('error', "Harap login ke akun Anda untuk melanjutkan!");
            redirect('login');
        }
    }

    // LOGOUT
    public function logout()
    {

        // SESS DESTROY
        $user_data = $this->session->all_userdata();

        foreach ($user_data as $key => $value) {
            if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
                $this->session->unset_userdata($key);
            }
        }

        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'Berhasil keluar!');
        redirect(base_url());
    }

    // MAILER SENDER

    function send_email($email, $subject, $message)
    {

        $mail = array(
            'to' => $email,
            'subject' => $subject,
            'message' => $message
        );

        if ($this->mailer->send($mail) == true) {
            return true;
        } else {
            return false;
        }
    }
}
