<form class="form-group col-sm-10 col-md-6 col-lg-4 mx-auto" action="<?= base_url('auth/lupa-password/request') ?>" method="POST">
    <h4 class="text-center mb-4">Lupa Password</h4>

    <?php if (!empty($this->session->flashdata('message'))) :  ?>
        <div class="alert <?= $this->session->flashdata("message")["tipe"] ?> alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-triangle"></i> Perhatian</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>

            <p> <?= $this->session->flashdata("message")["message"] ?></p>
        </div>

    <?php endif ?>

    <div class="my-5">
        <div class="form-group">
            <input class="form-control" type="text" placeholder="NIK/ Username" name="username" required>
        </div>

    </div>

    <div class="my-5 ">
        <button type="submit" class="my-3 btn bg-light-green btn-block rounded-lg py-2 px-4 text-light">
            <i class="fas fa-paper-plane fa-fw"></i>
            <span>Kirim</span>
        </button>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('.show-password').click(function(e) {
            e.preventDefault();
            $('[name=password]').attr('type', 'text');
            $('.show-password').addClass('d-none');
            $('.hide-password').removeClass('d-none');
        })

        $('.hide-password').click(function(e) {
            e.preventDefault();
            $('[name=password]').attr('type', 'password');
            $('.show-password').removeClass('d-none');
            $('.hide-password').addClass('d-none');
        })
    });
</script>