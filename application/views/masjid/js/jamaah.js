let tabel_jamaah;

$(document).ready(function () {

    cekFile('#url-foto-tambah');
    cekFile('#url-foto-edit');

    $('#btn-cari-jamaah').click(function (e) {
        e.preventDefault();
        tabel_jamaah.draw();
    })

    $('.datepicker').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        orientation: "bottom",
        endDate: "yesterday"

    });

    $("#yes").click(function() {
        if ($('#yes').val() == 'yes') {
            $('#ubah_foto').removeAttr('hidden');
        }
        $('#no').removeClass('text-white');
        $(this).addClass('text-white');
        var element = document.getElementById('no');
        element.style.removeProperty("background-color");
        document.getElementById('yes').style.backgroundColor = '#008080';
    });
    
    $("#no").click(function() {
        if ($('#no').val() == 'no') {
            $('#ubah_foto').attr('hidden', true);
        }
        var element = document.getElementById('yes');
        element.style.removeProperty("background-color");
        $(this).addClass('text-white');
        $('#yes').removeClass('text-white');
        document.getElementById('no').style.backgroundColor = '#008080';
    });

    $("#tambah").click(function() {
        if ($('#tambah').val() == 'yes') {
            $('#tambah_foto').removeAttr('hidden');
        }
        $(this).addClass('text-white');
        document.getElementById('tambah').style.backgroundColor = '#008080';
    });

    // table jamaah
    tabel_jamaah = $('#tabel-jamaah').DataTable({
        "sDom": "<'row' <'col-md-12'>><'row'<'col-md-6 col-sm-12'><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        "sServerMethod": "POST",
        "autoWidth": false,
        "bSort": false,
        "pageLength": 15,
        "bProcessing": false,
        "bServerSide": true,
        "fnServerParams": function (aoData) {
            var search = $('[name=txt_cari_jamaah]').val();
            aoData.push({
                name: "sSearch",
                "value": search
            });
            aoData.push({
                name: "id_masjid",
                "value": id_masjid
            });
        },
        "fnStateSaveParams": function (oSetings, sValue) {
            // body...
        },
        "fnStateLoadParams": function (oSetings, oData) {
            // body...
        },
        'sAjaxSource': site_url + "masjid/jamaah/DataTablejamaah",
        'aoColumns': [
            { mDataProp: 'nomor' },
            { mDataProp: 'nama_lengkap' },
            // { mDataProp: 'jenis_kelamin' },
            { mDataProp: 'nomor_hp' },
            { mDataProp: 'email' },
            // { mDataProp: 'provinsi' },
            // { mDataProp: 'kabupaten' },
            // { mDataProp: 'kecamatan' },
            // { mDataProp: 'kelola' },
        ],
    });

    //edit jamaah
    $('#tabel-jamaah tbody').on('click', '#btn-edit', function (e) {
        e.preventDefault();
        $('#edit-foto').show();
        let kode = $(this).attr('data');
        $.ajax({
            type: 'ajax',
            method: 'POST',
            url: site_url + 'masjid/jamaah/edit',
            data: { kode: kode },
            dataType: 'json',
            // beforeSend : function(){},
            success: function (response) {
                if ((typeof response === 'string' || response instanceof String)) {
                    Swal.fire('Gagal!', 'Aplikasi gagal terhubung dengan server. Silahkan hubungi admin.', 'error');
                }
                if (response.status == 201) {
                    let result = response.data;
                    $('#form-jamaah').find('#id-jamaah').html('<input type="hidden" name="id">');
                    $('#form-jamaah').find('input[name=id_masjid]').val(id_masjid);
                    $('#form-jamaah').find('input[name=id_user]').val(result.id_user);
                    $('#form-jamaah').find('input[name=id]').val(kode);
                    $('#form-jamaah').find('input[name=nama]').val(result.nama);
                    $('#form-jamaah').find('input[name=email]').val(result.email);
                    $('#form-jamaah').find('input[name=no_hp]').val(result.no_hp);
                    $('#form-jamaah').find('select[name=jabatan]').val(result.jabatan);
                    $('#form-jamaah').find('textarea[name=alamat]').val(result.alamat);
                    $('#form-jamaah').find('input[name=foto]').val(kode);
                    $('#form-jamaah').find('input[name=tempat_lahir]').val(result.tempat_lahir);
                    $('#form-jamaah').find(`:radio[name=jenis_kelamin][value="${result.jenis_kelamin}"]`).prop('checked', true);
                    $('#form-jamaah').find('input[name=tanggal_lahir]').val(result.tanggal_lahir).trigger('input');

                    $.getJSON(
                        `${site_url}api/referensi/provinsi`,
                        null,
                        function (data, textStatus, jqXHR) {
                            if (data != null) {
                                $('#kode_provinsi').html('<option value="">Pilih Provinsi</option>');
                                data.data.forEach((element) => {
                                    $('#form-jamaah').find('#kode_provinsi_jamaah').append(`<option value='${element.kode_provinsi}' ` + (element.kode_provinsi == result.kode_provinsi ? 'selected' : '')+`>${element.nama_provinsi}</option>`);
                                });
                            }
                        });

                    $.getJSON(
                        `${site_url}api/referensi/kabupaten/${result.kode_provinsi}`,
                        null,
                        function (data, textStatus, jqXHR) {
                            if (data != null) {
                                data.data.forEach((element) => {
                                    $('#kode_kabupaten_jamaah').append(`<option value="${element.kode_kab_kota}" ` + (result.kode_kabupaten == element.kode_kab_kota ? 'selected' : '')+`>${element.nama_kab_kota}</option>`);
                                });
                            }
                        });

                    $.getJSON(
                        `${site_url}api/referensi/kecamatan/${result.kode_kabupaten}`,
                        null,
                        function (data, textStatus, jqXHR) {
                            if (data != null) {
                                data.data.forEach((element) => {
                                    $('#kode_kecamatan_jamaah').append(`<option value="${element.kode_kecamatan}" ` + (element.kode_kecamatan == result.kode_kecamatan ? 'selected' : '')+`>${element.nama_kecamatan}</option>`);
                                });
                            }
                        });
                    var gambar = "";
                    if(result.url_foto == null){
                        gambar = "<br><img class='img-thumbnail' src='" + site_url + "public/images/avatar.jpeg' width='100px' height='100px'/><input name='foto' value='' type='hidden'>";
                    }else{
                        gambar = "<br><img class='img-thumbnail' src='" + site_url + "public/uploads/jamaah/" + result.url_foto + "' width='100px' height='100px'/><input name='foto' type='hidden' value='" + result.url_foto + "'>";
                    }

                    $('#modal-jamaah').find('#form-jamaah').attr('action', `${site_url}jamaah/index/update`);
                    $('#modal-jamaah').find('.modal-title').text('Ubah data jamaah masjid');
                    $('#tambah-foto').hide();
                    $('#gambar').html(gambar);
                    $('#modal-jamaah').modal('show');
                }
            },
            error: function (xmlhttprequest, textstatus, message) {
                // text status value : abort, error, parseerror, timeout
                // default xmlhttprequest = xmlhttprequest.responseJSON.message
                console.log(xmlhttprequest.responseJSON);
            },
        });
    });

    // hapus
    $('#tabel-jamaah tbody').on('click', '#btn-hapus', function (e) {
        e.preventDefault();
        let kode = $(this).attr('data');
        Swal.fire({
            icon: 'info',
            title: 'Perhatian!',
            text: 'Data yang dihapus tidak bisa dikembalikan. Apakah anda yakin ?',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus !',
            confirmButtonColor: '#0D9448',
            cancelButtonText: 'Tidak',
            cancelButtonColor: '#d33',
            reverseButtons: true,
        }).then(function (isvalid) {
            if (isvalid.value) {
                $.ajax({
                    type: 'ajax',
                    method: 'post',
                    url: site_url + 'jamaah/index/remove',
                    data: { id: kode },
                    dataType: 'json',
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
                            tabel_jamaah.draw();
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
                    // beforeSend: function () {
                    // showLoading();
                    // },
                    // success: function (response) {
                    //     // hideLoading();
                    //     if (response.status == 201) {
                    //         Swal.fire('Sukses!', response.message, 'success').then(function () {
                    //             tabel_jamaah.draw();
                    //         })
                    //     }
                    // },
                    // error: function (xmlresponse) {
                    //     Swal.fire("Gagal!", xmlresponse.responseJSON.message, 'error');
                    //     console.log(xmlresponse);
                    // }
                })
            }
        });
    });

    // tambah
    $('#btn-tambah-jamaah').click(function (e) {
        e.preventDefault();
        $('#form-jamaah')[0].reset();
        $('#edit-foto').hide();
        $('#tambah-foto').show();
        $('#form-jamaah').find('input[name=id_masjid]').val(id_masjid);
        $('#form-jamaah').find('select[name=kode_provinsi]').html('<option value="">Pilih Provinsi</option>');
        $('#form-jamaah').find('select[name=kode_kabupaten]').html('<option value="">Pilih Kabupaten/Kota</option>');
        $('#form-jamaah').find('select[name=kode_kecamatan]').html('<option value="">Pilih kecamatan</option>');
        $('#modal-jamaah').modal('show');
        $('#modal-jamaah').find('.modal-title').text('Tambah jamaah Masjid');
        $('#modal-jamaah').find('#form-jamaah').attr('action', `${site_url}jamaah/index/simpan`);
        $.getJSON(
            `${site_url}api/referensi/provinsi`,
            null,
            function (data, textStatus, jqXHR) {
                if (data != null) {
                    $('#form-jamaah').find('select[name=kode_provinsi]').html('<option value="">Pilih Provinsi</option>');
                    data.data.forEach((element) => {
                        $('#form-jamaah').find('select[name=kode_provinsi]').append(`<option value='${element.kode_provinsi}'>${element.nama_provinsi}</option>`);
                    });
                }
            });
    });

    $.getJSON(
        `${site_url}api/referensi/provinsi`,
        null,
        function (data, textStatus, jqXHR) {
            if (data != null) {
                data.data.forEach((element) => {
                    $('#kode_provinsi_jamaah').append(`<option value='${element.kode_provinsi}'>${element.nama_provinsi}</option>`);
                });
            }
        });

    $('#kode_provinsi_jamaah').change(function (e) {
        $('#kode_kabupaten_jamaah').html('<option value="">Pilih Kabupaten/Kota</option>');
        let kode_provinsi = $(this).val();
        $.getJSON(
            `${site_url}api/referensi/kabupaten/${kode_provinsi}`,
            null,
            function (data, textStatus, jqXHR) {
                if (data != null) {
                    data.data.forEach((element) => {
                        $('#kode_kabupaten_jamaah').append(`<option value="${element.kode_kab_kota}">${element.nama_kab_kota}</option>`);
                    });
                }
            });
    })
    $('#kode_kabupaten_jamaah').change(function (e) {
        $('#kode_kecamatan_jamaah').html('<option value="">Pilih kecamatan</option>');
        let kode_provinsi = $(this).val();
        $.getJSON(
            `${site_url}api/referensi/kecamatan/${kode_provinsi}`,
            null,
            function (data, textStatus, jqXHR) {
                if (data != null) {
                    data.data.forEach((element) => {
                        $('#kode_kecamatan_jamaah').append(`<option value="${element.kode_kecamatan}">${element.nama_kecamatan}</option>`);
                    });
                }
            });
    });


    // submit function

    $('#form-jamaah').submit(function (w) {
        w.preventDefault();
        if($(this).parsley().isValid()){
            var no_hp = $('#no_hp_jamaah').val();
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
                            url: $(this).attr('action'),
                            data: new FormData($(this)['0']),
                            contentType: false,
                            processData: false,
                            dataType: 'json',
                            // beforeSend : function(){},
                            success: function (response) {
                                if ((typeof response === 'string' || response instanceof String)) {
                                    Swal.fire('Gagal!', 'Aplikasi gagal terhubung dengan server. Silahkan hubungi admin.', 'error');
                                }
                                if (response.status == 201) {
                                    Swal.fire('Berhasil!', response.message, 'success').then(function () {
                                        $('#form-jamaah')[0].reset();
                                        $('#kode_provinsi').html('<option value="">Pilih Provinsi</option>');
                                        $('#kode_kabupaten').html('<option value="">Pilih Kabupaten/Kota</option>');
                                        $('#kode_kecamatan').html('<option value="">Pilih kecamatan</option>');
                                        $('#modal-jamaah').modal('hide');
                                        tabel_jamaah.draw();
                                    });
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
            }else{
                Swal.fire('Nomor Telepon', 'Nomor telepon tidak sesuai, nomor harus diawali dengan 0, minimal 10 digit dan maksimal 14 digit.<br>Contoh: 0811231231', 'warning');
            }
        }

    })
});

  //clear modal
$('#modal-jamaah').on('hidden.bs.modal', function () {
    $(this).find('#form-jamaah')[0].reset();
    $('#form-jamaah').parsley().reset();
});