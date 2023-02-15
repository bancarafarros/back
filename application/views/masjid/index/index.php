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
                    <?php echo $this->session->flashdata('message'); ?>
                    <div class="row">
                        <?php if (getSessionRole() == 1) : ?>
                            <div class="col-md-8">
                            <button type="button" id="btn-verif" class="btn btn-warning text-white" data-toggle="modal" data-target="#modal-verifikasi">Masjid belum terverifikasi</button>
                            </div>
                            <div class="col-md-4">
                                <a class="btn float-right text-white" href="<?php echo site_url('masjid/index/tambah') ?>" style="background-color: #008080!important;"><i class="fa fa-plus"></i> Tambah Data masjid</a>
                            </div>
                        <?php endif; ?>
                    </div><br>
                    <div class="container">
                        <?= $output->output; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="modal-verifikasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Masjid Belum Terverifikasi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive mt-3">
                <table class="table table-striped table-hover table-bordered table-sm" id="tabel-verifikasi">
                    <thead>
                        <tr>
                            <th>Nama Masjid</th>
                            <th>Nomor HP</th>
                            <th>Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody id="data_naktif">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/template-scripts') ?>
<script>


    $(document).ready(function(){
        var table = $('#tabel-verifikasi').DataTable();

        $('#btn-verif').click(function () {
            $.getJSON("<?= site_url('masjid/index/getMasjidNaktif') ?>", function (json) {
                // console.log(json);
                for (i = 0; i < json.length; i++) {
                    table.row.add([json[i].nama, json[i].no_hp, '<button type="button" class="btn btn-sm btn-warning aktifMasjid text-center" data-index="' + json[i].id + '"><i class="fa fa-check"></i></button>']).draw();
                }
            });
        });

        $(document).on('click keypress', '.aktifMasjid', function (e) {
            Swal.fire({
                icon: "warning",
                title: "Masjid",
                text: "Apakah anda ingin mengaktifkan Masjid ini?",
                showCancelButton: true,
                cancelButtonText: "Batal",
                confirmButtonText: "Proses",
                reverseButtons: true,
            }).then((result) => {
                // console.log(id);
                if (result.value) {
                    $.ajax({
                        type: "post",
                        dataType: 'json',
                        url: site_url + '/masjid/index/verifikasi/' + $(this).data('index'),
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
                                location.reload();
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
        });

        $('#modal-verifikasi').on('hidden.bs.modal', function () {
            table.clear();
        })
    });

    function hapus(id) {
        Swal.fire({
            icon: "question",
            title: "Peringatan",
            text: "Apakah anda yakin ingin menghapus data masjid ini?",
            showCancelButton: true,
            cancelButtonText: "Batal",
            confirmButtonText: "Ya, Lanjut",
            confirmButtonColor: "#ed0c0c",
            cancelButtonColor: "#008080",
            reverseButtons: true,
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "post",
                    dataType: 'json',
                    url: site_url + '/masjid/index/remove',
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