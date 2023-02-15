let tabel_pengurus;
$(document).ready(function () {

    $('#jabatan').change(function () {
        tabel_pengurus.draw();
    })

    $('#btn-cari').click(function (e) {
        e.preventDefault();
        tabel_pengurus.draw();
    })
    tabel_pengurus = $('#tabel-pengurus').DataTable({
        "sDom": "<'row' <'col-md-12'>><'row'<'col-md-6 col-sm-12'><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        "sServerMethod": "POST",
        "autoWidth": false,
        "bSort": false,
        "pageLength": 15,
        "bProcessing": false,
        "bServerSide": true,
        "fnServerParams": function (aoData) {
            var search = $('[name=txt_cari]').val();
            var jabatan = $('[name=jabatan]').val();;
            aoData.push({
                name: "sSearch",
                "value": search
            });
            aoData.push({
                name: "jabatan",
                "value": jabatan
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
        'sAjaxSource': site_url + "masjid/pengurus/DataTablePengurus",
        'aoColumns': [
            { mDataProp: 'nomor' },
            { mDataProp: 'nama_lengkap' },
            { mDataProp: 'jenis_kelamin' },
            { mDataProp: 'jabatan' },
            { mDataProp: 'nomor_hp' },
            { mDataProp: 'email' },
            { mDataProp: 'provinsi' },
            { mDataProp: 'kabupaten' },
            { mDataProp: 'kecamatan' },
            { mDataProp: 'kelola' },
        ],
    });
    $('#tabel-pengurus tbody').on('click', '#btn-edit', function (e) {
        e.preventDefault();
        let kode = $(this).attr('data');
        $.ajax({
            type: 'ajax',
            method: 'POST',
            url: site_url + 'masjid/pengurus/edit',
            data: { kode: kode },
            dataType: 'json',
            // beforeSend : function(){},
            success: function (response) {
                if ((typeof response === 'string' || response instanceof String)) {
                    Swal.fire('Gagal!', 'Aplikasi gagal terhubung dengan server. Silahkan hubungi admin.', 'error');
                }
                if (response.status == 201) {
                    let result = response.data;
                    $('#form-pengurus').find('#id-pengurus').html('<input type="hidden" name="id">');
                    $('#form-pengurus').find('input[name=id]').val(kode);
                    $('#form-pengurus').find('input[name=nama]').val(result.nama);
                    $('#form-pengurus').find('input[name=email]').val(result.email);
                    $('#form-pengurus').find('input[name=no_hp]').val(result.no_hp);
                    $('#form-pengurus').find('select[name=jabatan]').val(result.jabatan);
                    $('#form-pengurus').find('textarea[name=alamat]').val(result.alamat);
                    $('#form-pengurus').find('input[name=tempat_lahir]').val(result.tempat_lahir);
                    $('#form-pengurus').find(`:radio[name=jenis_kelamin][value="${result.jenis_kelamin}"]`).prop('checked', true);
                    $('#form-pengurus').find('input[name=tanggal_lahir]').val(result.tanggal_lahir).trigger('input');

                    $.getJSON(
                        `${site_url}api/referensi/provinsi`,
                        null,
                        function (data, textStatus, jqXHR) {
                            if (data != null) {
                                $('#kode_provinsi').html('<option value="">Pilih Provinsi</option>');
                                data.data.forEach((element) => {
                                    $('#form-pengurus').find('#kode_provinsi').append(`<option value='${element.kode_provinsi}' ` + (element.kode_provinsi == result.kode_provinsi ? 'selected' : '')+`>${element.nama_provinsi}</option>`);
                                });
                            }
                        });

                    $.getJSON(
                        `${site_url}api/referensi/kabupaten/${result.kode_provinsi}`,
                        null,
                        function (data, textStatus, jqXHR) {
                            if (data != null) {
                                data.data.forEach((element) => {
                                    $('#kode_kabupaten').append(`<option value="${element.kode_kab_kota}" ` + (result.kode_kabupaten == element.kode_kab_kota ? 'selected' : '')+`>${element.nama_kab_kota}</option>`);
                                });
                            }
                        });

                    $.getJSON(
                        `${site_url}api/referensi/kecamatan/${result.kode_kabupaten}`,
                        null,
                        function (data, textStatus, jqXHR) {
                            if (data != null) {
                                data.data.forEach((element) => {
                                    $('#kode_kecamatan').append(`<option value="${element.kode_kecamatan}" ` + (element.kode_kecamatan == result.kode_kecamatan ? 'selected' : '')+`>${element.nama_kecamatan}</option>`);
                                });
                            }
                        });

                    $('#modal-pengurus').find('#form-pengurus').attr('action', `${site_url}masjid/pengurus/update`);
                    $('#modal-pengurus').find('.modal-title').text('Ubah data pengurus masjid');
                    $('#modal-pengurus').modal('show');
                }
            },
            error: function (xmlhttprequest, textstatus, message) {
                // text status value : abort, error, parseerror, timeout
                // default xmlhttprequest = xmlhttprequest.responseJSON.message
                console.log(xmlhttprequest.responseJSON);
            },
        });
    });

    $('#tabel-pengurus tbody').on('click', '#btn-hapus', function (e) {
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
                    url: site_url + 'masjid/pengurus/hapus',
                    data: { kode: kode },
                    dataType: 'json',
                    // beforeSend: function () {
                    // showLoading();
                    // },
                    success: function (response) {
                        // hideLoading();
                        if (response.status == 201) {
                            Swal.fire('Sukses!', response.message, 'success').then(function () {
                                tabel_pengurus.draw();
                            })
                        }
                    },
                    error: function (xmlresponse) {
                        Swal.fire("Gagal!", xmlresponse.responseJSON.message, 'error');
                        console.log(xmlresponse);
                    }
                })
            }
        });
    });
    $('#btn-tambah-pengurus').click(function (e) {
        e.preventDefault();
        $('#form-pengurus')[0].reset();
        $('#form-pengurus').find('input[name=id_masjid]').val(id_masjid);
        $('#kode_provinsi').html('<option value="">Pilih Provinsi</option>');
        $('#kode_kabupaten').html('<option value="">Pilih Kabupaten/Kota</option>');
        $('#kode_kecamatan').html('<option value="">Pilih kecamatan</option>');
        $('#modal-pengurus').modal('show');
        $('#modal-pengurus').find('.modal-title').text('Tambah Pengurus Masjid');
        $('#modal-pengurus').find('#form-pengurus').attr('action', `${site_url}masjid/pengurus/tambah`);
        $.getJSON(
            `${site_url}api/referensi/provinsi`,
            null,
            function (data, textStatus, jqXHR) {
                if (data != null) {
                    $('#kode_provinsi').html('<option value="">Pilih Provinsi</option>');
                    data.data.forEach((element) => {
                        $('#kode_provinsi').append(`<option value='${element.kode_provinsi}'>${element.nama_provinsi}</option>`);
                    });
                }
            });
    });
    $('#kode_provinsi').select2();
    $('#kode_kabupaten').select2();
    $('#kode_kecamatan').select2();
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

    $('#form-pengurus').submit(function (w) {
        w.preventDefault();
        let nama = $('#form-pengurus').find('input[name=nama]').val();
        nama = nama.trim();
        if (!nama) {
            Swal.fire('Gagal!', 'Nama takmir tidak boleh kosong', 'error');
        }

        $.ajax({
            type: 'ajax',
            method: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json',
            // beforeSend : function(){},
            success: function (response) {
                if ((typeof response === 'string' || response instanceof String)) {
                    Swal.fire('Gagal!', 'Aplikasi gagal terhubung dengan server. Silahkan hubungi admin.', 'error');
                }
                if (response.status == 201) {
                    Swal.fire('Berhasil!', response.message, 'success').then(function () {
                        $('#form-pengurus')[0].reset();
                        $('#kode_provinsi').html('<option value="">Pilih Provinsi</option>');
                        $('#kode_kabupaten').html('<option value="">Pilih Kabupaten/Kota</option>');
                        $('#kode_kecamatan').html('<option value="">Pilih kecamatan</option>');
                        $('#modal-pengurus').modal('hide');
                        tabel_pengurus.draw();
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
    })
});