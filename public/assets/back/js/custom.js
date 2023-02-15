/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

/**
 * Easy selector helper function
 */
const select = (el, all = false) => {
	el = el.trim()
	if (all) {
		return [...document.querySelectorAll(el)]
	} else {
		return document.querySelector(el)
	}
}
/**
 * Easy on scroll event listener 
 */
const onscroll = (el, listener) => {
	el.addEventListener('scroll', listener)
}
/**
   * Back to top button
   */
let backtotop = select('.back-to-top')
if (backtotop) {
	const toggleBacktotop = () => {
		if (window.scrollY > 100) {
			backtotop.classList.add('active')
		} else {
			backtotop.classList.remove('active')
		}
	}
	window.addEventListener('load', toggleBacktotop)
	onscroll(document, toggleBacktotop)
}

/**
 * select kabupaten/kota
 */
$('[name="kode_provinsi"]').change(function (e) {
	var val = e.target.value;
	$.ajax({
		url: site_url + 'getKabKota',
		type: "GET",
		data: {
			kode_provinsi: val
		},
		dataType: "JSON",
		success: function (data) {
			buatKabKot(data);
			$('[name="kode_kabupaten"]').select2({
				placeholder: '- Pilih Kabupaten/Kota -',
			});
		},
		error: function (xx) {
			alert(xx)
		}
	});
});

function buatKabKot(data) {
	let optionLoop = '<option value>Pilih Kabupaten / Kota</option>';
	Object.keys(data).forEach(key => {
		optionLoop += `<option value="${key.trim()}">${data[key].trim()}</option>`
	});
	$('[name="kode_kecamatan"]').children().remove();
	$('[name="kode_kecamatan"]').select2({
		placeholder: '- Pilih Kecamatan -',
	});
	if ($('[name="kode_kabupaten"]')) {
		$('[name="kode_kabupaten"]').children().remove();
	}
	$('[name="kode_kabupaten"]').append(optionLoop);
}

/**
  * select kecamatan
  */
$('[name="kode_kabupaten"]').change(function (e) {
	var val = e.target.value;
	$.ajax({
		url: site_url + 'getKecamatan',
		type: "GET",
		data: {
			kode_kab_kota: val
		},
		dataType: "JSON",
		success: function (data) {
			buatKecamatan(data);
			$('[name="kode_kecamatan"]').select2({
				placeholder: '- Pilih Kecamatan -',
			});
		},
		error: function (xx) {
			alert(xx)
		}
	});
});

function buatKecamatan(data) {
	let optionLoop = '<option value>Pilih Kecamatan</option>';
	Object.keys(data).forEach(key => {
		optionLoop += `<option value="${key.trim()}">${data[key].trim()}</option>`
	});
	if ($('[name="kode_kecamatan"]')) {
		$('[name="kode_kecamatan"]').children().remove();
	}
	$('[name="kode_kecamatan"]').append(optionLoop);
}

/**
 * select kabupaten/kota instansi
 */
$('[name="instansi_kode_prov"]').change(function (e) {
	var val = e.target.value;
	$.ajax({
		url: site_url + 'getKabKota',
		type: "GET",
		data: {
			kode_provinsi: val
		},
		dataType: "JSON",
		success: function (data) {
			buatKabKotInstansi(data);
			$('[name="instansi_kode_kab"]').select2({
				placeholder: '- Pilih Kabupaten/Kota -',
			});
		},
		error: function (xx) {
			alert(xx)
		}
	});
});

function buatKabKotInstansi(data) {
	let optionLoop = '<option value>Pilih Kabupaten / Kota</option>';
	Object.keys(data).forEach(key => {
		optionLoop += `<option value="${key.trim()}">${data[key].trim()}</option>`
	});
	$('[name="instansi_kode_kec"]').children().remove();
	$('[name="instansi_kode_kec"]').select2({
		placeholder: '- Pilih Kecamatan -',
	});
	if ($('[name="instansi_kode_kab"]')) {
		$('[name="instansi_kode_kab"]').children().remove();
	}
	$('[name="instansi_kode_kab"]').append(optionLoop);
}

/**
  * select kecamatan instansi
  */
$('[name="instansi_kode_kab"]').change(function (e) {
	var val = e.target.value;
	$.ajax({
		url: site_url + 'getKecamatan',
		type: "GET",
		data: {
			kode_kab_kota: val
		},
		dataType: "JSON",
		success: function (data) {
			buatKecamatanInstansi(data);
			$('[name="instansi_kode_kec"]').select2({
				placeholder: '- Pilih Kecamatan -',
			});
		},
		error: function (xx) {
			alert(xx)
		}
	});
});

function buatKecamatanInstansi(data) {
	let optionLoop = '<option value>Pilih Kecamatan</option>';
	Object.keys(data).forEach(key => {
		optionLoop += `<option value="${key.trim()}">${data[key].trim()}</option>`
	});
	if ($('[name="instansi_kode_kec"]')) {
		$('[name="instansi_kode_kec"]').children().remove();
	}
	$('[name="instansi_kode_kec"]').append(optionLoop);
}

/**
 * select prodi
 */
$('[name="perguruan_tinggi"]').change(function (e) {
	var val = e.target.value;
	$.ajax({
		url: site_url + 'getProdi',
		type: "GET",
		data: {
			kode_prodi: val
		},
		dataType: "JSON",
		success: function (data) {
			buatProdi(data);
			$('[name="kode_prodi"]').select2({
				placeholder: '- Pilih Prodi/Jurusan -',
			});
		},
		error: function (xx) {
			alert(xx)
		}
	});
});

function buatProdi(data) {
	let optionLoop = '<option value>Pilih Prodi/Jurusan</option>';
	Object.keys(data).forEach(key => {
		optionLoop += `<option value="${key.trim()}">${data[key].trim()}</option>`
	});
	if ($('[name="kode_prodi"]')) {
		$('[name="kode_prodi"]').children().remove();
	}
	$('[name="kode_prodi"]').append(optionLoop);
}

/**
 * datepicker
 */
$('.datepicker-lahir').datepicker({
	startDate: '-100y',
	endDate: '-17y',
	format: 'yyyy-mm-dd',
	language: 'id',
	daysOfWeekHighlighted: "0",
	autoclose: true,
	todayHighlight: true
});

$('.datepicker2').datepicker({
	endDate: '0y',
	format: 'yyyy-mm-dd',
	language: 'id',
	daysOfWeekHighlighted: "0",
	autoclose: true,
	todayHighlight: true
});

/**
 * select status alumni
 */
let year_now = new Date();
let month = (year_now.getMonth() > 9 ? '' : '0') + (year_now.getMonth() + 1);
let day = (year_now.getDate() > 9 ? '' : '0') + year_now.getDate();
let start_date = year_now.getFullYear() + '-' + month + '-' + day;

$('[name="status_alumni"]').change(function (e) {
	var val = e.target.value;
	if (val == 1) {
		// remove or add d-none
		$('#div-instansi').attr('class', 'form-group');
		$('#div-tgl-mulai').attr('class', 'form-group');
		$('#div-jabatan').attr('class', 'form-group');
		$('#div-prodi').attr('class', 'form-group d-none');
		$('#div-sektor').attr('class', 'form-group');
		$('#div-instansi-provinsi').attr('class', 'form-group');
		$('#div-instansi-kab-kota').attr('class', 'form-group');
		$('#div-instansi-kecamatan').attr('class', 'form-group');
		$('#div-alamat').attr('class', 'form-group');

		// change label
		$('#instansi').html('Nama Instansi <b class="text-danger">*</b>');

		// change placeholder
		$('[name="nama_instansi"]').attr('placeholder', 'Masukkan nama instansi anda');

		// remove or add required
		$('[name="nama_instansi"]').attr('required', true);
		$('[name="tgl_mulai"]').attr('required', true);
		$('[name="jabatan"]').attr('required', true);
		$('[name="prodi"]').attr('required', false);
		$('[name="sektor"]').attr('required', true);
		$('[name="instansi_kode_prov"]').attr('required', true);
		$('[name="instansi_kode_kab"]').attr('required', true);
		$('[name="instansi_kode_kec"]').attr('required', true);
		$('[name="instansi_alamat"]').attr('required', true);

		// remove value
		$('[name="nama_instansi"]').val('');
		$('[name="tgl_mulai"]').val(start_date);
		$('[name="jabatan"]').val('');
		$('[name="prodi"]').val('');
		$('[name="sektor"]').prop('checked', false);
		$('[name="instansi_kode_prov"]').val('').trigger('change');
		$('[name="instansi_kode_kab"]').val('').trigger('change');
		$('[name="instansi_kode_kec"]').val('').trigger('change');
		$('[name="instansi_alamat"]').val('');
	} else if (val == 3) {
		// remove or add d-none
		$('#div-instansi').attr('class', 'form-group');
		$('#div-tgl-mulai').attr('class', 'form-group');
		$('#div-jabatan').attr('class', 'form-group d-none');
		$('#div-prodi').attr('class', 'form-group d-none');
		$('#div-sektor').attr('class', 'form-group');
		$('#div-instansi-provinsi').attr('class', 'form-group');
		$('#div-instansi-kab-kota').attr('class', 'form-group');
		$('#div-instansi-kecamatan').attr('class', 'form-group');
		$('#div-alamat').attr('class', 'form-group');

		// change label
		$('#instansi').html('Nama Usaha <b class="text-danger">*</b>');

		// change placeholder
		$('[name="nama_instansi"]').attr('placeholder', 'Masukkan nama usaha anda');

		// remove or add required
		$('[name="nama_instansi"]').attr('required', true);
		$('[name="tgl_mulai"]').attr('required', true);
		$('[name="jabatan"]').attr('required', false);
		$('[name="prodi"]').attr('required', false);
		$('[name="sektor"]').attr('required', true);
		$('[name="instansi_kode_prov"]').attr('required', true);
		$('[name="instansi_kode_kab"]').attr('required', true);
		$('[name="instansi_kode_kec"]').attr('required', true);
		$('[name="instansi_alamat"]').attr('required', true);

		// remove value
		$('[name="nama_instansi"]').val('');
		$('[name="tgl_mulai"]').val(start_date);
		$('[name="jabatan"]').val('');
		$('[name="prodi"]').val('');
		$('[name="sektor"]').prop('checked', false);
		$('[name="instansi_kode_prov"]').val('').trigger('change');
		$('[name="instansi_kode_kab"]').val('').trigger('change');
		$('[name="instansi_kode_kec"]').val('').trigger('change');
		$('[name="instansi_alamat"]').val('');
	} else if (val == 4) {
		// remove or add d-none
		$('#div-instansi').attr('class', 'form-group');
		$('#div-tgl-mulai').attr('class', 'form-group');
		$('#div-jabatan').attr('class', 'form-group d-none');
		$('#div-prodi').attr('class', 'form-group');
		$('#div-sektor').attr('class', 'form-group');
		$('#div-instansi-provinsi').attr('class', 'form-group');
		$('#div-instansi-kab-kota').attr('class', 'form-group');
		$('#div-instansi-kecamatan').attr('class', 'form-group');
		$('#div-alamat').attr('class', 'form-group d-none');

		// change label
		$('#instansi').html('Nama Perguruan Tinggi <b class="text-danger">*</b>');

		// change placeholder
		$('[name="nama_instansi"]').attr('placeholder', 'Masukkan nama perguruan tinggi anda');

		// remove or add required
		$('[name="nama_instansi"]').attr('required', true);
		$('[name="tgl_mulai"]').attr('required', true);
		$('[name="jabatan"]').attr('required', false);
		$('[name="prodi"]').attr('required', true);
		$('[name="sektor"]').attr('required', true);
		$('[name="instansi_kode_prov"]').attr('required', true);
		$('[name="instansi_kode_kab"]').attr('required', true);
		$('[name="instansi_kode_kec"]').attr('required', true);
		$('[name="instansi_alamat"]').attr('required', false);

		// remove value
		$('[name="nama_instansi"]').val('');
		$('[name="tgl_mulai"]').val(start_date);
		$('[name="jabatan"]').val('');
		$('[name="prodi"]').val('');
		$('[name="sektor"]').prop('checked', false);
		$('[name="instansi_kode_prov"]').val('').trigger('change');
		$('[name="instansi_kode_kab"]').val('').trigger('change');
		$('[name="instansi_kode_kec"]').val('').trigger('change');
		$('[name="instansi_alamat"]').val('');
	} else {
		// remove or add d-none
		$('#div-instansi').attr('class', 'form-group d-none');
		$('#div-tgl-mulai').attr('class', 'form-group d-none');
		$('#div-jabatan').attr('class', 'form-group d-none');
		$('#div-prodi').attr('class', 'form-group d-none');
		$('#div-sektor').attr('class', 'form-group d-none');
		$('#div-instansi-provinsi').attr('class', 'form-group d-none');
		$('#div-instansi-kab-kota').attr('class', 'form-group d-none');
		$('#div-instansi-kecamatan').attr('class', 'form-group d-none');
		$('#div-alamat').attr('class', 'form-group d-none');

		// remove or add required
		$('[name="nama_instansi"]').attr('required', false);
		$('[name="tgl_mulai"]').attr('required', false);
		$('[name="jabatan"]').attr('required', false);
		$('[name="prodi"]').attr('required', false);
		$('[name="sektor"]').attr('required', false);
		$('[name="instansi_kode_prov"]').attr('required', false);
		$('[name="instansi_kode_kab"]').attr('required', false);
		$('[name="instansi_kode_kec"]').attr('required', false);
		$('[name="instansi_alamat"]').attr('required', false);

		// remove value
		$('[name="nama_instansi"]').val('');
		$('[name="tgl_mulai"]').val(start_date);
		$('[name="jabatan"]').val('');
		$('[name="prodi"]').val('');
		$('[name="sektor"]').prop('checked', false);
		$('[name="instansi_kode_prov"]').val('').trigger('change');
		$('[name="instansi_kode_kab"]').val('').trigger('change');
		$('[name="instansi_kode_kec"]').val('').trigger('change');
		$('[name="instansi_alamat"]').val('');
	} {

	}
});

/**
 * only number
 */
$('.number-only').keyup(function (e) {
	if (/\D/g.test(this.value)) {
		// Filter non-digits from input value.
		this.value = this.value.replace(/\D/g, '');
	}
});

/**
 * only alpha
 */
$('.alpha-only').bind('keydown', function (e) {
	if (e.altKey) {
		e.preventDefault();
	} else {
		var key = e.keyCode;
		if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
			e.preventDefault();
		}
	}
});

/**
 * show password
 */
$('.show-password').click(function (e) {
	if ('password' == $('[name="password"]').attr('type')) {
		$('[name="password"]').prop('type', 'text');
		$('.show-password').html('<i class="fas fa-eye text-secondary">');
	} else {
		$('[name="password"]').prop('type', 'password');
		$('.show-password').html('<i class="fas fa-eye-slash text-secondary">');
	}
});

/**
 * show confirm password
 */
$('.show-confirm-password').click(function (e) {
	if ('password' == $('[name="konfirmasi_password"]').attr('type')) {
		$('[name="konfirmasi_password"]').prop('type', 'text');
		$('.show-confirm-password').html('<i class="fas fa-eye text-secondary">');
	} else {
		$('[name="konfirmasi_password"]').prop('type', 'password');
		$('.show-confirm-password').html('<i class="fas fa-eye-slash text-secondary">');
	}
});

/**
 * show old password
 */
$('.show-old-password').click(function (e) {
	if ('password' == $('[name="password_lama"]').attr('type')) {
		$('[name="password_lama"]').prop('type', 'text');
		$('.show-old-password').html('<i class="fas fa-eye text-secondary">');
	} else {
		$('[name="password_lama"]').prop('type', 'password');
		$('.show-old-password').html('<i class="fas fa-eye-slash text-secondary">');
	}
});

/**
 * format ipk 0.00 maks 4.00
 */
$('.ipk').mask('0.00', {
	reverse: true,
	min: 0,
	max: 4
});

/**
 * submit registrasi alumni
 */
$('#form_registrasi').on('submit', function (e) {
	e.preventDefault();
	$.ajax({
		url: site_url + "simpan_registrasi",
		dataType: 'json',
		type: 'POST',
		contentType: false,
		processData: false,
		data: new FormData(this),
		beforeSend: function () {
			loading();
		},
		success: function (data) {
			dismiss_loading();
			if (data.success) {
				swal(
					{
						title: 'Berhasil',
						text: 'Pendaftaran alumni berhasil',
						type: 'success',
						confirmButtonColor: '#396113',
					},
				).then(function () {
					window.location.href = site_url + 'registrasi_berhasil';
				});
			} else {
				if (typeof data.text !== "undefined") {
					swal(
						{
							title: 'Ups...',
							html: data.text,
							type: 'warning',
							confirmButtonColor: '#396113',
						},
					);
				} else {
					swal(
						{
							title: 'Ups...',
							text: 'Data Gagal Disimpan',
							type: 'error',
							confirmButtonColor: '#396113',
						},
					);
				}
			}
		},
		error: function (error) {
			dismiss_loading();
			swal(
				{
					title: 'Galat',
					text: 'Hubungi admin',
					type: 'error',
					confirmButtonColor: '#396113',
				},
			);
		}
	});
});

/**
 * submit registrasi mitra
 */
$('#form_registrasi_mitra').on('submit', function (e) {
	e.preventDefault();
	$.ajax({
		url: site_url + "simpan_registrasi",
		dataType: 'json',
		type: 'POST',
		contentType: false,
		processData: false,
		data: new FormData(this),
		beforeSend: function () {
			loading();
		},
		success: function (data) {
			dismiss_loading();
			if (data.success) {
				swal(
					{
						title: 'Berhasil',
						text: 'Pendaftaran mitra berhasil',
						type: 'success',
						confirmButtonColor: '#396113',
					},
				).then(function () {
					window.location.href = site_url + 'registrasi_berhasil';
				});
			} else {
				if (typeof data.text !== "undefined") {
					swal(
						{
							title: 'Ups...',
							html: data.text,
							type: 'warning',
							confirmButtonColor: '#396113',
						},
					);
				} else {
					swal(
						{
							title: 'Ups...',
							text: 'Data Gagal Disimpan',
							type: 'error',
							confirmButtonColor: '#396113',
						},
					);
				}
			}
		},
		error: function (error) {
			dismiss_loading();
			swal(
				{
					title: 'Galat',
					text: 'Hubungi admin',
					type: 'error',
					confirmButtonColor: '#396113',
				},
			);
		}
	});
});

/**
 * Cek Upload File
 */
function cekFile(input) {
	var limit = 2048576; // 2 MB
	var file_info = $('.file-info');
	$(file_info).removeClass('text-info').removeClass('text-danger').removeClass('text-muted').html('');
	if (input.files && input.files[0]) {
		var filesize = input.files[0].size;
		var filetype = (input.files[0].name).split('.').pop().toLowerCase();
		if (input.name == 'url_photo') {
			var allowed = ['png', 'jpg', 'jpeg'];
			var sizePreview = ['250px', '250px'];
		} else {
			var allowed = ['png', 'jpg', 'jpeg', 'pdf'];
			var sizePreview = ['100%', '800'];
		}
		if (filesize < limit && allowed.includes(filetype)) {
			var reader = new FileReader();
			reader.readAsDataURL(input.files[0]);

			if (filetype == 'pdf') {
				$('#preview').html('<embed src="' + URL.createObjectURL(input.files[0]) + '" class="mt-4" width="' + sizePreview[0] + '" height="' + sizePreview[1] + '" />')
			} else {
				$('#preview').html('<img src="' + URL.createObjectURL(input.files[0]) + '" class="mt-4" alt="your image" style="width: ' + sizePreview[0] + '"/>')
			}

			$(file_info).addClass('text-success').html(input.files[0].name + ' (' + getFileSize(filesize) + ') <i class="fa fa-circle-check text-success"></i>');
		} else {
			$(input).val('');
			$('#preview').html('');
			if (allowed.includes(filetype) === false)
				$(file_info).addClass('text-danger').html("<i>Jenis file '" + filetype.toUpperCase() + "' tidak diijinkan. </i>")
			if (filesize > limit)
				$(file_info).addClass('text-danger').html('<i>Ukuran file tidak boleh lebih dari 2 MB</i>')
		}

	} else {
		$(input).val('');
		$('#preview').html('');
		$(file_info).addClass('text-muted').html("Tidak ada berkas yang dipilih")
	}
}
function getFileSize(_size) {
	var fSExt = new Array('Bytes', 'KB', 'MB', 'GB'), i = 0; while (_size > 900) { _size /= 1024; i++; }
	var exactSize = (Math.round(_size * 100) / 100) + ' ' + fSExt[i];
	return exactSize;
}

/**
 * Ubah Foto
 */
function uploadFoto() {
	var upload = $('[name=new_upload]').val();
	$('[name=url_photo]').val('');
	$('#preview').html('');
	$('.file-info').removeClass('text-info').removeClass('text-danger').removeClass('text-muted').html('Tidak ada berkas yang dipilih');
	if (upload == '1') {
		$('#new-upload').removeClass('d-none');
		$('[name=new_upload]').val('0');
		$('#url_photo').attr('required', true);
	} else {
		$('#new-upload').addClass('d-none');
		$('[name=new_upload]').val('1');
		$('#url_photo').attr('required', false);
	}
}

/**
 * Loading
 */
function loading() {
	document.getElementById("spinner-front").classList.add("show");
	document.getElementById("spinner-back").classList.add("show");
}
function dismiss_loading() {
	document.getElementById("spinner-front").classList.remove("show");
	document.getElementById("spinner-back").classList.remove("show");
}