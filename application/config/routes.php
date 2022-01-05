<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['lupa-password'] = 'login/lupa_password';
$route['recovery-password/(:any)'] = 'login/ubah_password/$1';

$route['aktivasi-akun'] = 'login/aktivasi_email';

$route['otp'] = 'login/otp_send';
$route['send-otp/email'] = 'login/send_otp_email';
$route['send-otp/sms'] = 'login/send_otp_sms';
$route['verifikasi-otp'] = 'login/verifikasi_otp';

$route['logout'] = 'login/logout';


$route['pengaturan'] = 'home/pengaturan';

$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = false;
