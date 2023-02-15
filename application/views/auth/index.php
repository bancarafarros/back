<div class="auth-main">
    <div class="auth_div vivify fadeIn">
        <div class="auth_brand">
            <a class="navbar-brand" href="#">
                <img style="max-height: 50px;" src="<?= base_url('public') ?>/images/sajada-teal-horizontal.png" class="img-fluid align-top mr-2" alt="logo-sajada">
            </a>
        </div>
        <div class="card">
            <div class="header">
                <p class="lead">Silahkan login ke dashboard Anda.</p>
            </div>
            <?php if ($this->session->flashdata('errorMessage')) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= $this->session->flashdata('errorMessage'); ?>
                </div>
            <?php endif; ?>
            <div class="body">
                <form class="form-auth-small" action="<?php echo site_url('/') ?>" method="POST">
                    <div class="form-group c_form_group">
                        <label>Username / Email</label>
                        <input type="text" name="username" class="form-control" placeholder="Username / Email" required>
                    </div>
                    <div class="form-group c_form_group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <div class="row d-flex justify-content-around align-items-center">
                            <div class="container-captcha"></div>
                            <button type="button" onclick="loadCaptcha()" class="btn btn-success btn-sm my-auto" style="background-color: #008080 !important;"><i class="fas fa-sync"></i></button>
                        </div>
                    </div>
                    <div class="form-group c_form_group">
                        <label>Captcha</label>
                        <input type="number" name="captcha" placeholder="Masukan Captcha" class="form-control" required>
                    </div>
                    <!-- <div class="form-group clearfix">
                        <label class="fancy-checkbox element-left">
                            <input type="checkbox">
                            <span>Remember me</span>
                        </label>
                    </div> -->
                    <button type="submit" name="login-button" value="login-button" class="btn btn-dark btn-lg btn-block" style="background-color: #008080 !important;">LOGIN</button>
                </form>
            </div>
        </div>
    </div>
    <div class="animate_lines">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
</div>
<script>
    var site_url = '<?php echo site_url() ?>';
    $(function() {
        loadCaptcha();
    })

    function loadCaptcha() {
        $.ajax({
            type: 'ajax',
            url: site_url + '/auth/fetchCaptha',
            dataType: 'json',
            success: function(response) {
                $('.container-captcha').html(response.data);
            },
            error: function(xmlresponse) {
                console.log(xmlresponse);
            },
        });
    }
</script>
<?php $this->load->view('template/template-scripts') ?>