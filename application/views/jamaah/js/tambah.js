function validasi_nohp(telepon) {
    if (telepon == '' || !telepon.match(/^0[0-9]{9,13}$/)) {
        $('#no_hp').css({
            'color': '#B94A48',
            'background': '#F2DEDE',
            'border': 'solid 1px #EED3D7'
        });
    } else {
        $('#no_hp').css({
            'color': '#468847',
            'background': '#DFF0D8',
            'border': 'solid 1px #D6E9C6'
        });
    }
}

$(document).ready(function () {
    cekFile('#url_foto');
    

    $('#url_foto').bind('change', function(){
        if($(this).files[0].size > 3000){
            Swal.fire('warning!', "ukuran file terlalu besar", 'error');
        }
    });

    $('.datepicker').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        orientation: "top",
        endDate: "yesterday"

    });

    $.getJSON(
        `${site_url}api/referensi/provinsi`,
        null,
        function (data, textStatus, jqXHR) {
            if (data != null) {
                data.data.forEach((element) => {
                    $('#kode_provinsi').append(`<option value='${element.kode_provinsi}'>${element.nama_provinsi}</option>`);
                });
            }
        });
    $.getJSON(
        `${site_url}api/masjid/getMasjid`,
        null,
        function (data, textStatus, jqXHR) {
            if (data != null) {
                data.data.forEach((element) => {
                    $('#id_masjid').append(`<option value='${element.id}'>${element.nama}</option>`);
                });
            }
        });

    $('#kode_provinsi').change(function (e) {
        $('#kode_kabupaten').html('<option value="">Pilih Kabupaten/Kota</option>');
        let kode_provinsi = $(this).val();
        $.getJSON(
            `${site_url}api/referensi/kabupaten/${kode_provinsi}`,
            null,
            function (data, textStatus, jqXHR) {
                if (data != null) {
                    data.data.forEach((element) => {
                        $('#kode_kabupaten').append(`<option value="${element.kode_kab_kota}">${element.nama_kab_kota}</option>`);
                    });
                }
            });
    })
    $('#kode_kabupaten').change(function (e) {
        $('#kode_kecamatan').html('<option value="">Pilih kecamatan</option>');
        let kode_provinsi = $(this).val();
        $.getJSON(
            `${site_url}api/referensi/kecamatan/${kode_provinsi}`,
            null,
            function (data, textStatus, jqXHR) {
                if (data != null) {
                    data.data.forEach((element) => {
                        $('#kode_kecamatan').append(`<option value="${element.kode_kecamatan}">${element.nama_kecamatan}</option>`);
                    });
                }
            });
    });

    
    $('#form-jamaah').submit(function (e) {
        e.preventDefault();
        if($(this).parsley().isValid()){
            var no_hp = $('#no_hp').val();
            if (no_hp.match(/^0[0-9]{9,13}$/)) {
                Swal.fire({
                    icon: "question",
                    title: "Peringatan",
                    text: "Apakah anda yakin ingin menyimpan data jamaah?",
                    showCancelButton: true,
                    cancelButtonText: "Batal",
                    confirmButtonText: "Simpan",
                    confirmButtonColor: "#008080",
                    reverseButtons: true,
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'ajax',
                            method: 'POST',
                            url: site_url + 'jamaah/index/simpan',
                            data: new FormData($('#form-jamaah')[0]),
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            // beforeSend: function () { },
                            success: function (response) {
                                if ((typeof response === 'string' || response instanceof String)) {
                                    Swal.fire('Gagal!', 'Aplikasi gagal terhubung dengan server. Silahkan hubungi admin.', 'error');
                                }
                                if (response.status == 201) {
                                    Swal.fire('Pendataan Berhasil!', response.message, 'success').then(function () {
                                        location.href = site_url+'jamaah/index';
                                    })
                                }
                            },
                            error: function (xmlhttprequest, textstatus, message) {
                                // text status value : abort, error, parseerror, timeout
                                // default xmlhttprequest = xmlhttprequest.responseJSON.message
                                Swal.fire('Gagal!', xmlhttprequest.responseJSON.message, 'error');
                                console.log(xmlhttprequest.responseJSON);
                            },
                        });
                    }
                });
            } else {
                Swal.fire('Nomor Telepon', 'Nomor telepon tidak sesuai, nomor harus diawali dengan 0, minimal 10 digit dan maksimal 14 digit.<br>Contoh: 0811231231', 'warning');
            }
            
        }
    });
});

$("#yes").click(function() {
    if ($('#yes').val() == 'yes') {
        $('#tambah_foto').removeAttr('hidden');
    }
    $(this).addClass('text-white');
    document.getElementById('yes').style.backgroundColor = '#008080';
});