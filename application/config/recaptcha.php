<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| https://www.google.com/recaptcha/admin
|
| 'site_key' The site key provided by Google
| 'secret_key' The secret key provided by Google. Make sure you keep it SECRET.
|
|
*/
$config['re_keys'] = [
    'site_key'		=> '6LdbVZYaAAAAALOnP_oxAr9FxF6N3WHcC6Qo0dlt',
    'secret_key'	=> '6LdbVZYaAAAAAOb85iegl3wbZUyvZ7s6JiAB5ktV'
];

/*
|--------------------------------------------------------------------------
| reCAPTCHA parameters
|--------------------------------------------------------------------------
| https://developers.google.com/recaptcha/docs/display#render_param
| e.g.,to add the 'data-size' parameter, only add 'size' as the key:
| 'size' => 'compact'
|
*/
$config['re_parameters'] = array(
    'theme'				=> 'light',
);