<!doctype html>
<html lang="en">

<head>
	<title><?= SITENAME ?> | <?= (isset($title) ? $title : 'Home') ?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<meta name="description" content="Mooli Bootstrap 4x admin is super flexible, powerful, clean &amp; modern responsive admin dashboard with unlimited possibilities.">
	<meta name="author" content="GetBootstrap, design by: puffintheme.com">

	<!-- FAVICONS -->
	<link href="<?= base_url('public') ?>/images/sajada-logo-teal.png" rel="icon">
	<link href="<?= base_url('public') ?>/images/sajada-logo-teal.png" rel="apple-touch-icon">

	<!-- VENDOR CSS -->
	<link rel="stylesheet" href="<?= base_url('public') ?>/libs/bootstrap4/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?= base_url('public') ?>/libs/fontawesome/css/all.min.css">
	<link rel="stylesheet" href="<?= base_url('public') ?>/libs/fontawesome/css/fontawesome.min.css">
	<link rel="stylesheet" href="<?= base_url('public') ?>/libs/animate-css/vivify.min.css">

	<link rel="stylesheet" href="<?= base_url('public') ?>/libs/chartist/css/chartist.min.css">
	<link rel="stylesheet" href="<?= base_url('public') ?>/libs/chartist-plugin-tooltip/chartist-plugin-tooltip.css">
	<link rel="stylesheet" href="<?= base_url('public') ?>/libs/c3/c3.min.css" />
	<link rel="stylesheet" href="<?= base_url('public') ?>/libs/toastr/toastr.min.css">
	<link rel="stylesheet" href="<?= base_url('public') ?>/libs/jvectormap/jquery-jvectormap-2.0.3.min.css" />

	<!-- datatable -->
	<link rel="stylesheet" href="<?= base_url('public') ?>/libs/datatables/datatables-bs4/css/dataTables.bootstrap4.css">
	<!-- end datatable -->
	<!-- select2 -->
	<link rel="stylesheet" href="<?= base_url('public') ?>/libs/select2/select2.min.css">
	<link rel="stylesheet" href="<?= base_url('public') ?>/libs/select2-bootstrap4-theme/select2-bootstrap4.min.css">
	<!-- end select2 -->

	<!-- sweet alert -->
	<link rel="stylesheet" href="<?= base_url('public') ?>/libs/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
	<!-- end sweet alert -->

	<!-- date -->
	<link rel="stylesheet" href="<?= base_url('public') ?>/libs/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
	<link rel="stylesheet" href="<?= base_url('public') ?>/assets/loading/loading_page.css">
	<!-- parsleyjs -->
	<link rel="stylesheet" href="<?= base_url('public') ?>/libs/parsleyjs/parsley.css" />

	<!-- MAIN CSS -->
	<link rel="stylesheet" href="<?= base_url('public') ?>/back/css/mooli.min.css">
	<script src="<?= base_url('public') ?>/libs/jquery/jquery-3.3.1.min.js"></script>

	<?php
	if (isset($output->css_files)) {
		foreach ($output->css_files as $file) {
			echo '<link type="text/css" rel="stylesheet" href="' . $file . '"/>';
		}
	}
	?>
</head>

<body>
	<div id="body" class="theme-cyan">
		<div id="spinner-front">
			<img src="<?= base_url('public') ?>/images/sajada-logo-teal.png" width="50px"><br>
			Harap tunggu...
		</div>
		<div id="spinner-back"></div>
		<!-- Page Loader -->
		<div class="page-loader-wrapper">
			<div class="loader">
				<div class="m-t-30"><img src="<?= base_url('public') ?>/images/sajada-logo-teal.png" width="40" height="40" alt="Mooli"></div>
				<p>Please wait...</p>
			</div>
		</div>
		<!-- Overlay For Sidebars -->
		<div class="overlay"></div>
		<div id="wrapper">
			<!-- Page top navbar -->
			<nav class="navbar navbar-fixed-top">
				<div class="container-fluid">
					<div class="navbar-left">
						<div class="navbar-btn">
							<a href="#"><img src="<?= base_url('public') ?>/images/sajada-logo-teal.png" alt="Mooli Logo" class="img-fluid logo"></a>
							<button type="button" class="btn-toggle-offcanvas"><i class="fa fa-align-left"></i></button>
						</div>
					</div>
					<div class="navbar-right">
						<div id="navbar-menu">
							<ul class="nav navbar-nav">
								<p id="btnFullscreen"></p>
							</ul>
						</div>
					</div>
				</div>
			</nav>
			<!-- Main left sidebar menu -->
			<div id="left-sidebar" class="sidebar">
				<a href="#" class="menu_toggle"><i class="fa fa-angle-left"></i></a>
				<div class="navbar-brand">
					<a href="<?php echo site_url('dashboard') ?>">
						<img src="<?php echo base_url('public') ?>/images/sajada-logo-white.png" alt="Mooli Logo" class="img-fluid logo"><span> SAJADA</span></a>
					<button type="button" class="btn-toggle-offcanvas btn btn-sm float-right"><i class="fa fa-close"></i></button>
				</div>
				<div class="sidebar-scroll">
					<div class="user-account">
						<div class="user_div">
							<img src="<?php echo base_url('public') ?>/back/images/user.png" class="user-photo" alt="User Profile Picture">
						</div>
						<div class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown"><strong><?php echo getSessionName() ?></strong></a>
							<ul class="dropdown-menu dropdown-menu-right account vivify flipInY">
								<li><a href="<?php echo site_url('dashboard/profil') ?>"><i class="fa fa-user"></i>My Profile</a></li>
								<li class="divider"></li>
								<li><a href="<?php echo site_url('auth/logout') ?>"><i class="fa fa-power-off"></i>Logout</a></li>
							</ul>
						</div>
					</div>
					<?php $this->load->view('template/partials/main-side-menu') ?>
				</div>
			</div>
			<!-- Main body part  -->
			<div id="main-content">
				{CONTENT}
			</div>
		</div>
	</div>
</body>

</html>