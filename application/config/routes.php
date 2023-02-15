<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = "index";
$route['(profile|forgot_password|aktifkan_akun|forgot_password_reset|logout)'] = 'index/$1';
$route['404_override'] = 'Index/notFound';
$route['translate_uri_dashes'] = FALSE;
$route['api/(:any)'] = "api/$1";
$route['api'] = "api/index";

// beranda
// $route['program/hibah-kompetitif'] = "beranda/Program/hibkom";
// $route['program/pwmp'] = "beranda/Program/pwmp";
// $route['program/magang'] = "beranda/Program/magang";
// $route['program/pelatihan'] = "beranda/Program/pelatihan";
// $route['program'] = "beranda/Program";

// auth
// $route['auth/logout'] = "dashboard/index/logout";
// $route['auth/registrasi'] = "auth/register";
$route['auth/lupa-password'] = "auth/Lupa_password";
$route['auth/lupa-password/request'] = "ref_auth/Lupa_password/requestResetToken";
$route['auth/reset-password'] = "ref_auth/Lupa_password/resetPassword";
$route['auth/reset-password/confirm'] = "ref_auth/Lupa_password/confirmResetPassword";


$route['TripayCallback'] = "api/TripayCallback/index";

// profil
// $route['dashboard/profil/ubah'] = "profil/ubah";
// $route['dashboard/usaha/ubah'] = "usaha/ubah";
// $route['dashboard/usaha/berkas'] = "usaha/berkas";


// $route['informasi/pengumuman'] = "hibkom/pengumuman/daftar";
// $route['informasi/pengumuman/(:num)'] = "hibkom/pengumuman/daftar/$1";
// $route['informasi/pengumuman/baca/(:num)'] = "hibkom/pengumuman/baca/$1";

// $route['informasi/berita'] = "hibkom/berita/daftar";
// $route['informasi/berita/(:any)'] = "hibkom/berita/daftar/$1";
// $route['informasi/berita/baca/(:any)'] = "hibkom/berita/baca/$1";

// api
//$route['api/wilayahadministrasi/(:any)'] = "api/WilayahAdministrasi/$1";

// api
$route['api/auth/(:any)'] = "api/Auth/$1";
$route['api/projek/(:any)'] = "api/Projek/$1";
$route['api/profile/(:any)'] = "api/Profile/$1";
