<!doctype html>
<html lang="en">

<head>
    <title><?= SITENAME ?> | <?= (!empty($title) ? $title : 'index') ?></title>
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

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="<?= base_url('public') ?>/back/css/mooli.min.css">

    <script src="<?= base_url('public') ?>/libs/jquery/jquery-3.3.1.min.js"></script>
</head>

<body>

    <div id="body" class="theme-cyan">

        {CONTENT}

    </div>

    <script src="<?= base_url('public') ?>/back/bundles/libscripts.bundle.js"></script>
    <script src="<?= base_url('public') ?>/back/bundles/vendorscripts.bundle.js"></script>

    <!-- Vedor js file and create bundle with grunt  -->
    <script>
        $('.choose-skin li').on('click', function() {
            var $body = $('body');
            var $this = $(this);

            var existTheme = $('.choose-skin li.active').data('theme');

            $('.choose-skin li').removeClass('active');
            $body.removeClass('theme-' + existTheme);
            $this.addClass('active');
            var newTheme = $('.choose-skin li.active').data('theme');
            $body.addClass('theme-' + $this.data('theme'));
        });

        // Theme Setting
        $('.themesetting .theme_btn').on('click', function() {
            $('.themesetting').toggleClass('open');
        });
        // dark version
        $(".dark_mode input").on('change', function() {
            if (this.checked) {
                $("body").addClass('dark_active');
            } else {
                $("body").removeClass('dark_active');
            }
        });
    </script>
</body>

</html>