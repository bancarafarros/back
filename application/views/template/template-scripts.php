<!-- Javascript -->
<script src="<?= base_url('public') ?>/back/bundles/libscripts.bundle.js"></script>
<script src="<?= base_url('public') ?>/back/bundles/vendorscripts.bundle.js"></script>

<!-- Vedor js file and create bundle with grunt  -->
<script src="<?= base_url('public') ?>/back/bundles/flotscripts.bundle.js"></script><!-- flot charts Plugin Js -->
<script src="<?= base_url('public') ?>/back/bundles/c3.bundle.js"></script>
<script src="<?= base_url('public') ?>/back/bundles/apexcharts.bundle.js"></script>
<script src="<?= base_url('public') ?>/back/bundles/jvectormap.bundle.js"></script>
<script src="<?= base_url('public') ?>/libs/toastr/toastr.js"></script>

<!-- datatable -->
<script src="<?= base_url('public') ?>/libs/datatables/datatables.min.js"></script>
<script src="<?= base_url('public') ?>/libs/datatables/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- end datatable -->

<!-- select2 -->
<script src="<?= base_url('public') ?>/libs/select2/select2.min.js"></script>
<!-- end select2 -->

<!-- sweetalert -->
<script src="<?= base_url('public') ?>/libs/sweetalert2/sweetalert2.min.js"></script>
<!-- end sweetalert -->
<!-- datepicker -->
<script src="<?= base_url('public') ?>/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

<!-- parsley -->
<script src="<?= base_url('public') ?>/libs/parsleyjs/parsley.min.js"></script>
<script src="<?= base_url('public') ?>/libs/parsleyjs/i18n/id.js"></script>

<!-- Project core js file minify with grunt -->
<script src="<?= base_url('public') ?>/back/bundles/mainscripts.bundle.js"></script>
<?php
echo '<script>';
if (isset($scripts) && is_array($scripts)) {
    foreach ($scripts as $script) {
        $this->load->view($script);
    }
}
echo '</script>';
?>
<?php
if (isset($output->js_files)) {
    foreach ($output->js_files as $file) {
        echo '<script src="' . $file . '"></script>';
    }
}
?>
<script>
    $(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            // theme: "bootstrap-5",
        });

        $('.datepicker').datepicker({
            language: 'id',
            autoclose: true,
            todayHighlight: true,
            format: "yyyy-mm-dd",
        });
    })
    const validateEmail = (email) => {
        return String(email)
            .toLowerCase()
            .match(
                /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            );
    };
    const base_url = '<?php echo base_url() ?>';
    const site_url = '<?php echo site_url() ?>';

    function onlyNumber(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))

            return false;
        return true;
    }

    function showLoading() {
        document.getElementById("spinner-front").classList.add("show");
        document.getElementById("spinner-back").classList.add("show");
    }

    function hideLoading() {
        document.getElementById("spinner-front").classList.remove("show");
        document.getElementById("spinner-back").classList.remove("show");
    }

    function getFileSize(_size) {
        var fSExt = new Array('Bytes', 'KB', 'MB', 'GB'),
            i = 0;
        while (_size > 900) {
            _size /= 1024;
            i++;
        }
        var exactSize = (Math.round(_size * 100) / 100) + ' ' + fSExt[i];
        return exactSize;
    }

    function imageRequired(status) {
        if (status.checked === true) document.getElementById('file_image').setAttribute('required', '');
        else document.getElementById('file_image').removeAttribute('required');
    }

    function cekFile(selector, selectorPreview = null) {
        $(selector).change(function() {
            var file = $(selector).get(0).files[0];
            // 2 mb
            let maxSize = 3133728;
            var nama_file = file.name;
            if (file) {
                let jenisFile = file.type.split('/')[0];
                let allowed = null;
                let sizePreview = null;
                if (jenisFile == 'image') {
                    allowed = ['png', 'jpg', 'jpeg'];
                } else {
                    allowed = ['pdf'];
                    sizePreview = ['100%', '200'];
                }
                let fileType = (nama_file).split('.').pop().toLowerCase();
                if (file.size > maxSize || !allowed.includes(fileType)) {
                    if (file.size > maxSize) {
                        Swal.fire({
                            confirmButtonColor: "#008080",
                            icon: "error",
                            title: "Peringatan",
                            text: "File Anda Melebihi batas 3MB, silahkan ganti file Anda",
                        }).then((result) => {
                            $(selector).val(null);
                        });
                    } else {
                        Swal.fire({
                            confirmButtonColor: "#008080",
                            icon: "error",
                            title: "Peringatan",
                            text: "Tipe file tidak diizinkan, silahkan ganti file Anda",
                        }).then((result) => {
                            $(selector).val(null);
                        });
                    }
                } else {
                    if (selectorPreview != null) {
                        var reader = new FileReader();
                        if (jenisFile == 'image') {
                            reader.onload = function() {
                                $(selectorPreview).html('<img src="' + reader.result + '" class="img-fluid">')
                            };
                            reader.readAsDataURL(file);
                        } else {
                            $(selectorPreview).html('<embed src="' + URL.createObjectURL(file) + '#toolbar=0&navpanes=0&scrollbar=0" class="mt-4" width="' + sizePreview[0] + '" height="' + sizePreview[1] + '">')
                            reader.readAsDataURL(file);
                        }
                    }
                }
            } else {
                Swal.fire({
                    confirmButtonColor: "#3ab50d",
                    icon: "error",
                    title: "Peringatan",
                    text: "File kosong",
                }).then((result) => {
                    $(selector).val(null);
                });
            }
        });
    }
</script>