
function grafikBatang(container, title, categories, series) {
    Highcharts.chart(container, {
        chart: {
            type: 'column'
        },
        title: {
            text: title
        },
        xAxis: {
            categories,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: ''
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr style="min-width:300px;"><td width="100" style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                minPointLength: 3
            }
        },
        series
    });
}

function setActivePPIUDropdown(ppiu, namaProv) {
    $($('#button-dropdown-ppiu').find('h5')[0]).text(ppiu)

    $($('#button-dropdown-ppiu').find('small')[0]).text(`Unit Pelaksana Proyek Provinsi ${namaProv}`)
}

function setActiveDropdown(selector, title, subtitle) {
    $($(selector).find('h5')[0]).text(title)
    $($(selector).find('small')[0]).text(subtitle)
}

function resetActiveWidget(kategori) {
    let parrent = $($(kategori).children()[0]).children()
    $('.widget-card').removeClass('active')

    $($(parrent[0]).children()[0]).addClass('active')
}

function informasiPerProvinsi(kodeProv) {
    //showLoading()
    $.ajax({
        type: "get",
        url: `${apiBaseUrl}/ppiu/detailPPIU/${kodeProv}`,
        success: function (response) {
            let person = response.data.person
            $("#person-mobilizer").find('.text-jumlah').text(person.mobilizer.jumlah)
            $("#person-fasilitator").find('.text-jumlah').text(person.fasilitator.jumlah)
            $("#person-mentor").find('.text-jumlah').text(person.mentor.jumlah)
            $("#person-cpm").find('.text-jumlah').text(person.cpm.jumlah)
            // $("#person-peserta-pelatihan").find('.text-jumlah').text(person.peserta_pelatihan.jumlah)
            // $("#person-financial-advisor").find('.text-jumlah').text(person.financial_advisor.jumlah)

            // $('.widget-data').click({ data: response.data }, handleWidgetClick);

            $.ajax({
                type: "get",
                url: `${apiBaseUrl}/ppiu/grafikPPIU/${kodeProv}`,
                success: function (response) {
                    $('#tab-kategori > li').click({ data: response.data }, handleTabClick);
                    grafikPPIU(response.data)
                    hideLoading()
                }
            });
        }
    });
}

function handleWidgetClick(event) {
    let kategori = $(this).parent().parent().parent().data('kategori')
    let subkategori = $(this).data('subkategori')


    $(this).parent().siblings().children().removeClass('active')
    $(this).addClass('active')
}

function setGrafikPerson(data) {
    let series = [];
    let financial_advisor = [];
    let fasilitator = [];
    let mentor = [];
    let mobilizer = [];
    let cpm = [];
    let peserta_pelatihan = [];
    // let kep = [];
    let categories = [];

    data.forEach(function (el) {
        // peserta_pelatihan.push(parseInt((el.peserta_pelatihan != null) ? el.peserta_pelatihan : 0));
        // financial_advisor.push(parseInt((el.financial_advisor != null) ? el.financial_advisor : 0));
        fasilitator.push(parseInt((el.fasilitator != null) ? el.fasilitator : 0));
        mentor.push(parseInt((el.mentor != null) ? el.mentor : 0));
        mobilizer.push(parseInt((el.mobilizer != null) ? el.mobilizer : 0));
        cpm.push(parseInt((el.cpm != null) ? el.cpm : 0));

        if ('nama_kab' in el) {
            categories.push(el.nama_kab);
        } else {
            categories.push(el.nama_prop);
        }
    });

    // series.push({ name: 'Financial Advisor', data: financial_advisor });
    // series.push({ name: 'Peserta Pelatihan', data: peserta_pelatihan });
    series.push({ name: 'Fasilitator', data: fasilitator });
    series.push({ name: 'Mentor', data: mentor });
    series.push({ name: 'Mobilizer', data: mobilizer });
    series.push({ name: 'Penerima Manfaat', data: cpm });

    grafikBatang('grafik-ppiu', '', categories, series)
}

function grafikPPIU(data) {
    let kategori = "person"
    let categories = []
    let series = []

    switch (kategori) {
        case 'person':
            setGrafikPerson(data[kategori])
            break;

        default:
            break;
    }
}

function handleTabClick(event) {
    event.preventDefault();

    // let kategori = $(this).data('kategori')

    // resetActiveWidget(`#${kategori}`)

    // let kategori = $(this).parent().parent().parent().data('kategori')
    // let subkategori = $(this).data('subkategori')


    // $(this).parent().siblings().children().removeClass('active')
    // $(this).addClass('active')

    grafikPPIU(event.data.data)
}

function informasiDashboard() {
    $.ajax({
        type: "get",
        url: `${apiBaseUrl}/ppiu`,
        success: function (response) {
            $('#jumlah_provinsi').text(response.data.jumlah_provinsi)
            $('#jumlah_kabupaten').text(response.data.jumlah_kabupaten)

            setActivePPIUDropdown(response.data.ppiu[0].nama_prop, response.data.ppiu[0].nama_prop)

            if (![2].includes(response.group_id)) {
                setActivePPIUDropdown('Semua Provinsi', 'semua Provinsi')

                $('#dropdown-ppiu').append(`
                <div type="button" data-kode-prov="all"  data-nama-prov="semua Provinsi" data-ppiu="Semua Provinsi"  class="dropdown-item p-3 d-flex flex-column">
                <h5>Semua Provinsi</h5>
                <small class="text-fadedtx-gray-500">Unit Pelaksana Proyek Provinsi semua provinsi</small>
            </div>
                `)
            } else {
                $.getJSON(`${apiBaseUrl}/ppiu/dit`, null,
                    function (data, textStatus, jqXHR) {
                        const kabs = data.data

                        kabs.forEach(element => {
                            $("#section-dit").removeClass('d-none');
                            $("#list-dit").append(
                                `<div class="col-sm-6 col-md-3">
                                <div class="card mb-3 rounded-lg border-0 shadow-sm">
                                    <div class="card-body">
                                        ${element.nama_kab}
                                    </div>
                                </div>
                            </div>`
                            )
                        });
                    }
                );
            }

            response.data.ppiu.forEach(ppiu => {
                $('#dropdown-ppiu').append(`
            <div type="button" data-kode-prov="${ppiu.kode_prop}"  data-nama-prov="${ppiu.nama_prop}" data-ppiu="${ppiu.nama_prop}"  class="dropdown-item p-3 d-flex flex-column">
            <h5>${ppiu.nama_prop}</h5>
            <small class="text-fadedtx-gray-500">Unit Pelaksana Proyek Provinsi ${ppiu.nama_prop}</small>
        </div>
            `)
            });

            $("#dropdown-ppiu > div").click(function (e) {
                e.preventDefault();
                let kodeProv = $(this).data('kode-prov')
                let namaProv = $(this).data('nama-prov')
                let ppiu = $(this).data('ppiu')

                setActivePPIUDropdown(ppiu, namaProv)
                informasiPerProvinsi(kodeProv)

                $('#tab-kategori a').removeClass('active')
                $($('#tab-kategori a')[0]).addClass('active')

                $('#tab-kategori-content > div').removeClass('show active')
                $($('#tab-kategori-content > div')[0]).addClass('show active')
            });
            // set per prov
            informasiPerProvinsi(response.data.ppiu[0].kode_prop)

        }
    });
}

function init() {
    map.addSource("states", {
        type: "geojson",
        data: `${provinceJson}`
    });

    map.addLayer({
        id: 'background',
        type: 'background',
        paint: { 'background-color': 'white' }
    });

    map.addLayer({
        id: "states-layer",
        type: "fill",
        source: "states",
        paint: {
            "fill-color": '#bfbfbf',
        }
    });

    map.addLayer({
        id: "state-borders",
        type: "line",
        source: "states",
        layout: {},
        paint: {
            "line-color": "#8d8a8a",
            "line-width": 0.15
        }
    });
}

function grafikHitkom(kode_prop, initial = false) {
    //showLoading()
    $.getJSON(`${apiBaseUrl}/hibkom/${kode_prop}`, null,
        function (data, textStatus, jqXHR) {
            const provs = data.data

            if (initial) {
                if (![2].includes(data.group_id)) {
                    $("#dropdown-hibkom").append(`
                <div type="button" data-kode-prov="all" data-nama-prov="Semua Provinsi" class="dropdown-item p-3 d-flex flex-column">
                <h5>Semua Provinsi</h5>
                <small class="text-fadedtx-gray-500">Status Hibah Kompetitif semua Provinsi</small>
            </div>
            `);

                } else {
                    setActiveDropdown("#button-dropdown-hibkom", provs[0].nama_prop, `Status Hibah Kompetitif ${provs[0].nama_prop}`)
                }

                provs.forEach(prop => {
                    $("#dropdown-hibkom").append(`
        <div type="button" data-kode-prov="${prop.kode_prop}" data-nama-prov="${prop.nama_prop}" class="dropdown-item p-3 d-flex flex-column">
            <h5>${prop.nama_prop}</h5>
            <small class="text-fadedtx-gray-500">Status Hibah Kompetitif ${prop.nama_prop}</small>
        </div>
        `);
                });


                $("#dropdown-hibkom > div").click(function (e) {
                    e.preventDefault();
                    let kodeProv = $(this).data('kode-prov')
                    let namaProv = $(this).data('nama-prov')

                    setActiveDropdown("#button-dropdown-hibkom", namaProv, `Status Hibah Kompetitif ${namaProv}`)

                    grafikHitkom(kodeProv);
                });
            }

            let jumlah = 0;
            let in_mobilizer = 0;
            let in_dit = 0;
            let in_ppiu = 0;
            let in_npmu = 0;
            let in_reviewer = 0;
            let belum_diproses = 0;

            let categories = []
            let series = [
                {
                    name: 'IN_MOBILIZER',
                    data: []
                },
                {
                    name: 'IN_DIT',
                    data: []
                },
                {
                    name: 'IN_PPIU',
                    data: []
                },
                {
                    name: 'IN_NPMU',
                    data: []
                },
                {
                    name: 'IN_REVIEWER',
                    data: []
                },
                {
                    name: 'BELUM DIPROSES',
                    data: []
                },
            ]

            provs.forEach(prov => {
                categories.push(prov.nama_prop)

                prov.data.forEach(status => {
                    jumlah += parseInt(status.jumlah)

                    switch (status.status) {
                        case 'IN_MOBILIZER':
                            in_mobilizer += parseInt(status.jumlah)
                            series[0].data.push(parseInt(status.jumlah))
                            break;

                        case 'IN_DIT':
                            in_dit += parseInt(status.jumlah)
                            series[1].data.push(parseInt(status.jumlah))
                            break;

                        case 'IN_PPIU':
                            in_ppiu += parseInt(status.jumlah)
                            series[2].data.push(parseInt(status.jumlah))
                            break;

                        case 'IN_NPMU':
                            in_npmu += parseInt(status.jumlah)
                            series[3].data.push(parseInt(status.jumlah))
                            break;

                        case 'IN_REVIEWER':
                            in_reviewer += parseInt(status.jumlah)
                            series[4].data.push(parseInt(status.jumlah))
                            break;

                        case 'NULL':
                            belum_diproses += parseInt(status.jumlah)
                            series[5].data.push(parseInt(status.jumlah))
                            break;

                        default:
                            break;
                    }
                });
            });

            setWidgetHibkom(jumlah, belum_diproses, in_mobilizer, in_dit, in_ppiu, in_npmu, in_reviewer)
            grafikBatang('grafik-hibkom', '', categories, series)


            hideLoading()
        }
    );
}

function setWidgetHibkom(jumlah, belum_diproses, in_mobilizer, in_dit, in_ppiu, in_npmu, in_reviewer) {
    $("#hibkom-total").find(".text-jumlah").text(jumlah)
    $("#hibkom-null").find(".text-jumlah").text(belum_diproses)
    $("#hibkom-in_mobilizer").find(".text-jumlah").text(in_mobilizer)
    $("#hibkom-in_dit").find(".text-jumlah").text(in_dit)
    $("#hibkom-in_ppiu").find(".text-jumlah").text(in_ppiu)
    $("#hibkom-in_npmu").find(".text-jumlah").text(in_npmu)
    $("#hibkom-in_reviewer").find(".text-jumlah").text(in_reviewer)
}

//showLoading()

$(document).ready(function () {
    map.once('style.load', function (e) {
        init();
        informasiDashboard()

        $.getJSON(`${apiBaseUrl}/wilayahadministrasi/propinsi`, null,
            function (data, textStatus, jqXHR) {
                const provs = data.data

                if (provs) {
                    let expression = ["match", ["get", "id"]];
                    let color = '#3AB50D';

                    provs.forEach(function (row) {
                        expression.push(parseInt(row.kode_prop) / 100, color);

                        var popup = new mapboxgl.Popup()
                            .setText(row.nama_prop)

                        let marker = new mapboxgl
                            .Marker()
                            .setLngLat([row.longitude, row.latitude])
                            .setPopup(popup)
                            .addTo(map)
                    });
                    expression.push('#bfbfbf');

                    map.setPaintProperty("states-layer", 'fill-color', expression);
                } else {
                    map.setPaintProperty("states-layer", 'fill-color', '#bfbfbf');
                }

                grafikHitkom('all', true)


            }
        );
    });



});