<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['lupa-password']             = 'login/lupa_password';
$route['recovery-password/(:any)']  = 'login/ubah_password/$1';

$route['default_controller']    = 'login';
$route['404_override']          = '';
$route['translate_uri_dashes']  = FALSE;
