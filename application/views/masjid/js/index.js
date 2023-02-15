let tabel_masjid;
$(document).ready(function () {
   $('#btn-tambah-masjid').click(function (e) {
    e.preventDefault();
    $('#modal-masjid').modal('show');
   });
    $('#jenis_masjid').change(function () {
        tabel_masjid.draw();
    });
    $('#typologi_masjid').change(function () {
        tabel_masjid.draw();
    });

    $('#btn-cari').click(function (e) {
        e.preventDefault();
        tabel_masjid.draw();
    })

    $.getJSON(
        `${site_url}api/referensi/typologi`,
        null,
        function (data, textStatus, jqXHR) {
            if (data != null) {
                data.data.forEach((element) => {
                    $('#typologi_masjid').append(`<option value='${element.id}'>${element.name}</option>`)
                });
            }
        });
    tabel_masjid = $('#tabel-masjid').DataTable({
        "sDom": "<'row' <'col-md-12'>><'row'<'col-md-6 col-sm-12'><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        "sServerMethod": "POST",
        "autoWidth": false,
        "bSort": false,
        "pageLength": 15,
        "bProcessing": false,
        "bServerSide": true,
        "fnServerParams": function (aoData) {
            var search = $('[name=txt_cari]').val();
            var jenis_masjid = $('[name=jenis_masjid]').val();;
            var typologi_masjid = $('[name=typologi_masjid]').val();;
            aoData.push({
                name: "sSearch",
                "value": search
            });
            aoData.push({
                name: "jenis_masjid",
                "value": jenis_masjid
            });
            aoData.push({
                name: "typologi_masjid",
                "value": typologi_masjid
            });
        },
        "fnStateSaveParams": function (oSetings, sValue) {
            // body...
        },
        "fnStateLoadParams": function (oSetings, oData) {
            // body...
        },
        'sAjaxSource': site_url + "masjid/index/DataTableMasjid",
        'aoColumns': [
            { mDataProp: 'nomor' },
            { mDataProp: 'nama_masjid' },
            { mDataProp: 'takmir' },
            { mDataProp: 'jenis_masjid' },
            { mDataProp: 'provinsi' },
            { mDataProp: 'kabupaten' },
            { mDataProp: 'kecamatan' },
            { mDataProp: 'alamat' },
            { mDataProp: 'kelola' },
        ],
    });

    $('#tabel-masjid tbody').on('click', '#btn-hapus', function (e) {
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
                    url: site_url + 'masjid/index/hapus',
                    data: { kode: kode },
                    dataType: 'json',
                    // beforeSend: function () {
                    // showLoading();
                    // },
                    success: function (response) {
                        // hideLoading();
                        if (response.status == 201) {
                            Swal.fire('Sukses!', response.message, 'success').then(function () {
                                tabel_masjid.draw();
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
});