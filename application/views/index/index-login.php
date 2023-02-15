<div class="auth-main">
    <div class="auth_div vivify fadeIn">
        <div class="auth_brand">
            <a class="navbar-brand" href="#">
                <img src="<?php echo base_url('templates/assets/') ?>images/sajada/sajada-teal-horizontal.png" class="img-fluid align-top mr-2" alt="">
            </a>
        </div>
        <div class="card">
            <div class="header">
                <p class="lead">Login to your account</p>
            </div>
            <div class="body">
                <form class="form-auth-small" action="<?php echo site_url('/') ?>" method="POST">
                    <div class="form-group c_form_group">
                        <label>Email</label>
                        <input type="text" name="username" class="form-control" placeholder="Enter your email address">
                    </div>
                    <div class="form-group c_form_group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password">
                    </div>
                    <div class="form-group clearfix">
                        <label class="fancy-checkbox element-left">
                            <input type="checkbox">
                            <span>Remember me</span>
                        </label>
                    </div>
                    <button type="submit" name="login-button" value="login-button" class="btn btn-dark btn-lg btn-block">LOGIN</button>
                    <div class="bottom">
                        <span class="helper-text m-b-10"><i class="fa fa-lock"></i> <a href="page-forgot-password.html">Forgot password?</a></span>
                        <span>Don't have an account? <a href="3">Register</a></span>
                    </div>
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
<?php $this->load->view('template/template-scripts')?>