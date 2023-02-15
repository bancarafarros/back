<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<title><?= SITENAME ?> | <?= (!empty($title) ? $title : 'index') ?></title>
	<!-- FAVICONS -->
    <link href="<?= base_url('public') ?>/images/sajada-logo-teal.png" rel="icon">
    <link href="<?= base_url('public') ?>/images/sajada-logo-teal.png" rel="apple-touch-icon">

    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="<?= base_url('public') ?>/libs/bootstrap4/css/bootstrap.min.css">

    <script src="<?= base_url('public') ?>/libs/jquery/jquery-3.3.1.min.js"></script>
</head>

<body>
	<div class="notfound"  style="height: 100vh !important;">
		{CONTENT}
	</div>
</body>

</html>