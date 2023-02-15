mapboxgl.accessToken = `${mapboxKey}`;

if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function (position) {
        $(".marker").remove();
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [(referensi.longitude == null ? position.coords.longitude : referensi.longitude), (referensi.latitude == null ? position.coords.latitude : referensi.latitude)],
            zoom: 13
        });

        map.flyTo({
            center: [(referensi.longitude == null ? position.coords.longitude : referensi.longitude), (referensi.latitude == null ? position.coords.latitude : referensi.latitude)],
            essential: true, // this animation is considered essential with respect to prefers-reduced-motion
        });
        const marker = new mapboxgl.Marker({
            draggable: false
        })
            .setLngLat([(referensi.longitude == null ? position.coords.longitude : referensi.longitude), (referensi.latitude == null ? position.coords.latitude : referensi.latitude)])
            .addTo(map);

        function onDragEnd() {
            const lngLat = marker.getLngLat();
            $("#longitude").val(lngLat.lng)
            $("#latitude").val(lngLat.lat)
        }

        marker.on('dragend', onDragEnd);

        $("#longitude").val(position.coords.longitude);
        $("#latitude").val(position.coords.latitude);
    });
} else {
    Swal.fire('Error!', 'Browser anda tidak mendukung untuk menentukan lokasi anda.', 'error');
}

let tabel_pengurus;
$(document).ready(function () {

    $('#jabatan').change(function () {
        tabel_pengurus.draw();
    })

    $('#btn-cari').click(function (e) {
        e.preventDefault();
        tabel_pengurus.draw();
    })

    cekFile('#url-foto-tambah');
    cekFile('#url-foto-edit');

    $('.datepicker').datepicker({
        format: "yyyy-mm-dd",
        autoclose: true,
        orientation: "bottom",
        endDate: "yesterday"
    });

    $("#yes").click(function () {
        if ($('#yes').val() == 'yes') {
            $('#ubah_foto').removeAttr('hidden');
        }
        $('#no').removeClass('text-white');
        $(this).addClass('text-white');
        var element = document.getElementById('no');
        element.style.removeProperty("background-color");
        document.getElementById('yes').style.backgroundColor = '#008080';
    });

    $("#no").click(function () {
        if ($('#no').val() == 'no') {
            $('#ubah_foto').attr('hidden', true);
        }
        var element = document.getElementById('yes');
        element.style.removeProperty("background-color");
        $(this).addClass('text-white');
        $('#yes').removeClass('text-white');
        document.getElementById('no').style.backgroundColor = '#008080';
    });

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
            // { mDataProp: 'kelola' },
        ],
    });


    //edit
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
                    $('#form-pengurus').find('input[name=id_masjid]').val(id_masjid);
                    $('#form-pengurus').find('input[name=id_user]').val(result.id_user);
                    $('#form-pengurus').find('input[name=id]').val(kode);
                    $('#form-pengurus').find('input[name=nama]').val(result.nama);
                    $('#form-pengurus').find('input[name=email]').val(result.email);
                    $('#form-pengurus').find('input[name=no_hp]').val(result.no_hp);
                    $('#form-pengurus').find('select[name=jabatan]').val(result.jabatan);
                    $('#form-pengurus').find('textarea[name=alamat]').val(result.alamat);
                    $('#form-pengurus').find('input[name=tempat_lahir]').val(result.tempat_lahir);
                    $('#form-pengurus').find(`:radio[name=jenis_kelamin][value="${result.jenis_kelamin}"]`).prop('checked', true);
                    $('#form-pengurus').find('input[name=tanggal_lahir]').val(result.tanggal_lahir).trigger('input');
                    $('#form-pengurus').find('input[name=foto]').val(kode);

                    $.getJSON(
                        `${site_url}api/referensi/provinsi`,
                        null,
                        function (data, textStatus, jqXHR) {
                            if (data != null) {
                                $('#kode_provinsi').html('<option value="">Pilih Provinsi</option>');
                                data.data.forEach((element) => {
                                    $('#form-pengurus').find('#kode_provinsi').append(`<option value='${element.kode_provinsi}' ` + (element.kode_provinsi == result.kode_provinsi ? 'selected' : '') + `>${element.nama_provinsi}</option>`);
                                });
                            }
                        });

                    $.getJSON(
                        `${site_url}api/referensi/kabupaten/${result.kode_provinsi}`,
                        null,
                        function (data, textStatus, jqXHR) {
                            if (data != null) {
                                data.data.forEach((element) => {
                                    $('#kode_kabupaten').append(`<option value="${element.kode_kab_kota}" ` + (result.kode_kabupaten == element.kode_kab_kota ? 'selected' : '') + `>${element.nama_kab_kota}</option>`);
                                });
                            }
                        });

                    $.getJSON(
                        `${site_url}api/referensi/kecamatan/${result.kode_kabupaten}`,
                        null,
                        function (data, textStatus, jqXHR) {
                            if (data != null) {
                                data.data.forEach((element) => {
                                    $('#kode_kecamatan').append(`<option value="${element.kode_kecamatan}" ` + (element.kode_kecamatan == result.kode_kecamatan ? 'selected' : '') + `>${element.nama_kecamatan}</option>`);
                                });
                            }
                        });
                    var gambar = "";
                    if (result.url_foto == null) {
                        gambar = "<br><img class='img-thumbnail' src='" + site_url + "public/images/avatar.jpeg' width='100px' height='100px'/><input name='foto' value='' type='hidden'>";
                    } else {
                        gambar = "<br><img class='img-thumbnail' src='" + site_url + "public/uploads/pengurus/" + result.url_foto + "' width='100px' height='100px'/><input name='foto' type='hidden' value='" + result.url_foto + "'>";
                    }

                    $('#modal-pengurus').find('#form-pengurus').attr('action', `${site_url}pengurus/index/update`);
                    $('#modal-pengurus').find('.modal-title').text('Ubah data pengurus masjid');
                    $('#tambah-foto').hide();
                    $('#edit-foto').show();
                    $('#gambar').html(gambar);
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
                    url: site_url + 'pengurus/index/remove',
                    data: { id: kode },
                    dataType: 'json',
                    beforeSend: function () {
                        showLoading();
                    },
                    success: function (response) {
                        hideLoading();
                        Swal.fire({
                            confirmButtonColor: "#3ab50d",
                            icon: "success",
                            title: `${response.message.title}`,
                            html: `${response.message.body}`,
                        }).then((result) => {
                            tabel_pengurus.draw();
                        });
                    },
                    error: function (request, status, error) {
                        hideLoading();
                        Swal.fire({
                            confirmButtonColor: "#3ab50d",
                            icon: "error",
                            title: `${request.responseJSON.message.title}`,
                            html: `${request.responseJSON.message.body}`,
                        });
                    },
                })
            }
        });
    });

    //tambah
    $('#btn-tambah-pengurus').click(function (e) {
        e.preventDefault();
        $('#edit-foto').hide();
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
            data: new FormData($('#form-pengurus')[0]),
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

//clear modal
$('#modal-pengurus').on('hidden.bs.modal', function () {
    $(this).find('#form-pengurus')[0].reset();
    $('#form-pengurus').parsley().reset();
});