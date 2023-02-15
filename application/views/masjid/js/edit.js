
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
            draggable: true
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
$(document).ready(function () {
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
        `${site_url}api/referensi/typologi`,
        null,
        function (data, textStatus, jqXHR) {
            if (data != null) {
                data.data.forEach((element) => {
                    $('#id_ref_typologi').append(`<option value='${element.id}' `+(element.id == referensi.id_ref_typologi ? 'selected' : '')+`>${element.name}</option>`);
                });
            }
        });
    $.getJSON(
        `${site_url}api/referensi/afiliasi`,
        null,
        function (data, textStatus, jqXHR) {
            if (data != null) {
                data.data.forEach((element) => {
                    $('#ref_id_afiliasi').append(`<option value='${element.id}' `+(element.id == referensi.ref_id_afiliasi ? 'selected' : '')+`>${element.name}</option>`);
                });
            }
        });
    $.getJSON(
        `${site_url}api/referensi/statusTanah`,
        null,
        function (data, textStatus, jqXHR) {
            if (data != null) {
                data.data.forEach((element) => {
                    $('#ref_id_status_tanah').append(`<option value='${element.id}' `+(element.id == referensi.ref_id_status_tanah ? 'selected' : '')+`>${element.name}</option>`);
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

    $('#form-masjid-edit').submit(function (e) {
        e.preventDefault();
        if($(this).parsley().isValid()){
            var no_hp = $('#no_hp').val();
            if (no_hp.match(/^0[0-9]{9,13}$/)) {
                Swal.fire({
                    icon: "question",
                    title: "Peringatan",
                    text: "Apakah Anda yakin ingin mengubah data masjid?",
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
                            url: site_url + 'masjid/index/update',
                            data: $('#form-masjid-edit').serialize(),
                            dataType: 'json',
                            // beforeSend: function () { },
                            success: function (response) {
                                if ((typeof response === 'string' || response instanceof String)) {
                                    Swal.fire('Gagal!', 'Aplikasi gagal terhubung dengan server. Silahkan hubungi admin.', 'error');
                                }
                                if (response.status == 201) {
                                    Swal.fire('Pendataan Berhasil!', response.message, 'success').then(function () {
                                        location.href = site_url+'masjid/index';
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
            }else{
                Swal.fire('Nomor Telepon', 'Nomor telepon tidak sesuai, nomor harus diawali dengan 0, minimal 10 digit dan maksimal 14 digit.<br>Contoh: 0811231231', 'warning');
            }
        }
        
    });
});