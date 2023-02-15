<div class="container-fluid p-0 h-100">
    <div class="row no-gutters h-100 full-height">
        <div class="image col-lg-6 d-none d-lg-flex bg" style="background-image:url('<?= base_url('public') ?>/images/error.svg'); background-size: 90vh;">
            <div class="d-flex h-100 p-h-40 p-v-15 flex-column justify-content-between">
                <a href="<?= site_url() ?>">
                    <div>
                        <img style="max-width: 50px;" class="img-fluid mx-3 my-3" src="<?= base_url('public') ?>/images/sajada-white.png" alt="logo-sajada">
                    </div>
                </a>
                <!-- <div>
                    <h1 class="text-white m-b-20 font-weight-bold"><?= SITENAME ?></h1>
                    <p class="text-white font-size-16 lh-2 w-80 opacity-08">Climb leg rub face on everything give attitude nap all day for under the bed. Chase mice attack feet but rub face on everything hopped up.</p>
                </div> -->
                <div class="d-flex justify-content-between">
					<span class="text-white mx-3 my-3">&copy; 2022 - <?= date('Y') ?> &mdash; <a href="<?= site_url() ?>" class="text-white"><?= SITENAME?></a></span>
				</div>
            </div>
        </div>
        <div class="col-lg-6 bg-white">
            <div class="container h-100">
                <div class="row no-gutters h-100 align-items-center justify-content-center">
                    <div class="p-v-30 text-center">
                        <h1 class="font-weight-semibold display-1 text-danger lh-1-2">404</h1>
                        <h2 class="font-weight-light font-size-30">Page Not Found</h2>
                        <p class="lead m-b-30">Halaman tidak ditemukan atau sudah tidak ada</p>
                        <a href="<?= base_url('dashboard') ?>" class="btn text-white" style="background-color: #008080 !important;">Kembali ke Halaman Awal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>