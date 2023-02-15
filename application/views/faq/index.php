<div class="container-fluid">
    <!-- Page header section  -->
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-lg-4 col-md-12 col-sm-12"></div>
            <div class="col-lg-8 col-md-12 col-sm-12 text-lg-right">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page"><a href="<?= site_url('dashboard') ?>">Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $title ?></li>
                    </ol>
                </nav>

            </div>
        </div>
    </div>
    <div class="row clearfix">

        <div class="col-lg-12 col-md-12">
            <div class="card planned_task">
                <div class="header">
                    <h2><?php echo $title ?></h2>
                </div>
                <div class="body">
                    <div class="container">
                        <?= $output->output; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('template/template-scripts') ?>
<script>
    function hapus(id) {
        Swal.fire({
            icon: "question",
            title: "Peringatan",
            text: "Apakah Anda yakin ingin melanjutkan?",
            showCancelButton: true,
            cancelButtonText: "Batal",
            confirmButtonText: "Ya, Lanjut",
            cancelButtonColor: "#008080",
            confirmButtonColor: "#ed0c0c",
            reverseButtons: true,
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: site_url + 'faq/remove',
                    data: {
                        id: id
                    },
                    beforeSend: function() {
                        showLoading();
                    },
                    success: function(response) {
                        hideLoading();
                        Swal.fire({
                            confirmButtonColor: "#3ab50d",
                            icon: "success",
                            title: `${response.message.title}`,
                            html: `${response.message.body}`,
                        }).then((result) => {
                            $('.fa-refresh').trigger('click');
                        });
                    },
                    error: function(request, status, error) {
                        hideLoading();
                        Swal.fire({
                            confirmButtonColor: "#3ab50d",
                            icon: "error",
                            title: `${request.responseJSON.message.title}`,
                            html: `${request.responseJSON.message.body}`,
                        });
                    },
                });
            }
        });
    }
</script>