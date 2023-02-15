$('.datepicker').datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
    orientation: "top",
    endDate: "yesterday"

});

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

    $.getJSON(
        `${site_url}api/masjid/getMasjid`,
        null,
        function (data, textStatus, jqXHR) {
            if (data != null) {
                data.data.forEach((element) => {
                    $('#id_masjid').append(`<option value='${element.id}'>${element.nama}</option>`);
                    $('#id_masjid').append(`<option value='${element.id}' `+(element.id == referensi.id_masjid ? 'selected' : '')+`>${element.nama}</option>`);
                });
            }
        });
    $.getJSON(
        `${site_url}api/referensi/provinsi`,
        null,
        function (data, textStatus, jqXHR) {
            if (data != null) {
                data.data.forEach((element) => {
                    $('#kode_provinsi').append(`<option value='${element.kode_provinsi}' `+(element.kode_provinsi == referensi.kode_provinsi ? 'selected' : '')+`>${element.nama_provinsi}</option>`);
                });
            }
        });
    $.getJSON(
        `${site_url}api/referensi/kabupaten/${referensi.kode_provinsi}`,
        null,
        function (data, textStatus, jqXHR) {
            if (data != null) {
                data.data.forEach((element) => {
                    $('#kode_kabupaten').append(`<option value="${element.kode_kab_kota}" `+(referensi.kode_kabupaten == element.kode_kab_kota ? 'selected' : '')+`>${element.nama_kab_kota}</option>`);
                });
            }
        });
    $.getJSON(
        `${site_url}api/referensi/kecamatan/${referensi.kode_kabupaten}`,
        null,
        function (data, textStatus, jqXHR) {
            if (data != null) {
                data.data.forEach((element) => {
                    $('#kode_kecamatan').append(`<option value="${element.kode_kecamatan}" `+(element.kode_kecamatan == referensi.kode_kecamatan ? 'selected' : '')+`>${element.nama_kecamatan}</option>`);
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

    $('#form-pengurus').submit(function (e) {
        e.preventDefault();
        if ($(this).parsley().isValid()) {
            let nama = $('#form-pengurus').find('input[name=nama]').val();
            var no_hp = $('.no_hp').val()
            nama = nama.trim();
            if (!nama) {
                Swal.fire('Error!', 'Nama pengurus tidak boleh kosong', 'error');
            } else if (no_hp.match(/^0[0-9]{9,13}$/)) {
                Swal.fire({
                    icon: "question",
                    title: "Peringatan",
                    text: "Apakah Anda yakin ingin mengubah data pengurus?",
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
                            url: site_url + 'pengurus/index/update',
                            data: new FormData($('#form-pengurus')[0]),
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
                                        location.href = site_url+'pengurus/index';
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
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Pengurus',
                    text: 'Nomor telepon belum sesuai',
                    footer: '<span class="text-danger">example: 08xxxxxx</span>'
                });
            }
            
        }
    });
});

$("#yes").click(function() {
    if ($('#yes').val() == 'yes') {
        $('#foto').show();
    }
    
    $('#url_fotoh').hide();
    $(this).addClass('text-white');
    $("#no").removeClass('text-white');
    var element = document.getElementById('no');
    element.style.removeProperty("background-color");
    document.getElementById('yes').style.backgroundColor = '#008080';
});

$("#no").click(function() {
    if ($('#no').val() == 'no') {
        $('#foto').hide();
    }

    $('#url_fotoh').show();
    $(this).addClass('text-white');
    $("#yes").removeClass('text-white');
    var element = document.getElementById('yes');
    element.style.removeProperty("background-color");
    document.getElementById('no').style.backgroundColor = '#008080';
});