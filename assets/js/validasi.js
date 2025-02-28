$(document).ready(function() {
	$("#paging").validate();

	// Untuk form surat memeriksa nomor surat secara remote/ajax
	$("#validasi.form-surat").validate({
		ignore: '#wrapper-mandiri input[name=nomor]',
		errorElement: "label",
		errorClass: "error",
		highlight:function (element){
			$(element).closest(".form-group").addClass("has-error");
		},
		unhighlight:function (element){
			$(element).closest(".form-group").removeClass("has-error");
		},
		errorPlacement: function (error, element) {
			if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else if (element.hasClass('select2')) {
				error.insertAfter(element.next('span'));
			} else {
				error.insertAfter(element);
			}
		},
		// https://www.bladephp.co/jquery-validation-remote-codeigniter
		rules: {
			url_surat: {
				required: true
			},
			nomor: {
				required: true,
				remote: {
					url: $('#url_remote').val(),
					type: "post",
					data:{
						url: function() {
							return $('#url_surat').val()
						}
					}
				}
			}
		},
		messages: {
			nomor: {
				remote: "Nomor surat itu sudah digunakan",
			},
		},
		success: function() {
			refreshFormCsrf();
		},
		invalidHandler: function () {
			refreshFormCsrf();
		},
		submitHandler: function(form) {
			refreshFormCsrf();
			form.submit();
		}
	});

	// Untuk form surat masuk/keluar memeriksa nomor urut secara remote/ajax
	$("#validasi.nomor-urut").validate({
		errorElement: "label",
		errorClass: "error",
		highlight:function (element){
			$(element).closest(".form-group").addClass("has-error");
		},
		unhighlight:function (element){
			$(element).closest(".form-group").removeClass("has-error");
		},
		errorPlacement: function (error, element) {
			if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else if (element.hasClass('select2')) {
				error.insertAfter(element.next('span'));
			} else {
				error.insertAfter(element);
			}
		},
		// https://www.bladephp.co/jquery-validation-remote-codeigniter
		rules: {
			nomor_urut: {
				required: true,
				remote: {
					url: $('#url_remote').val(),
					type: "post",
					data:{
						nomor_urut_lama: function() {
							return $('#nomor_urut_lama').val()
						}
					}
				}
			}
		},
		messages: {
			nomor_urut: {
				remote: "Nomor urut itu sudah digunakan",
			},
		},
		success: function() {
			csrf_semua_form();
		}
	});

	$("#validasi").validate({
		errorElement: "label",
		errorClass: "error",
		highlight:function (element){
			$(element).closest(".form-group").addClass("has-error");
		},
		unhighlight:function (element) {
			$('.select2').on("select2:close", function (e) {  
				$(this).valid(); 
			});

			$(element).closest(".form-group").removeClass("has-error");
		},
		errorPlacement: function (error, element) {
			if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else if (element.hasClass('select2')) {
				error.insertAfter(element.next('span'));
			} else {
				error.insertAfter(element);
			}
		}
	});

	$("#validasi-proses").validate({
		ignore: ".ignore",
		errorElement: "label",
		errorClass: "error",
		highlight:function (element){
			$(element).closest(".form-group").addClass("has-error");
		},
		unhighlight:function (element){
			$(element).closest(".form-group").removeClass("has-error");
		},
		errorPlacement: function (error, element) {
			if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
				element.parent().focus();
			} else if (element.hasClass('select2')) {
				error.insertAfter(element.next('span'));
				element.next('span').focus();
			} else {
				error.insertAfter(element);
				element.focus();
			}
		},
		invalidHandler: function(e, validator){
			if(validator.errorList.length && $('#tabs').length) {
				$('#tabs a[href="#' + $(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show');
			}
		},
		submitHandler: function(form) {
			Swal.fire({
				title: 'Sedang Menyimpan',
				allowOutsideClick: false,
				allowEscapeKey: false,
				showConfirmButton: false,
				didOpen: () => {
					Swal.showLoading()
				}
			});
			form.submit();
		}
	});

	$("#mainform").validate({
		errorElement: "label",
		errorClass: "error",
		highlight:function (element){
			$(element).closest(".form-group").addClass("has-error");
		},
		unhighlight:function (element) {
			$('.select2').on("select2:close", function (e) {  
				$(this).valid(); 
			});

			$(element).closest(".form-group").removeClass("has-error");
		},
		errorPlacement: function (error, element) {
			if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else if (element.hasClass('select2')) {
				error.insertAfter(element.next('span'));
			} else {
				error.insertAfter(element);
			}
		}
	});

	$('.form-validasi').each(function(index, el){
		$(el).validate({
			errorElement: "label",
			errorClass: "error",
			highlight:function (element){
				$(element).closest(".form-group").addClass("has-error");
			},
			unhighlight:function (element){
				$(element).closest(".form-group").removeClass("has-error");
			},
			errorPlacement: function (error, element) {
				if (element.parent('.input-group').length) {
					error.insertAfter(element.parent());
				} else if (element.hasClass('select2')) {
					error.insertAfter(element.next('span'));
				} else {
					error.insertAfter(element);
				}
			}
		});
	});

	jQuery.validator.addMethod("nik", function(value, element) {
		nik_valid = /^\d*$/.test(value) && (value.length == 16) && (value.indexOf('0') != 0);
		return this.optional(element) || nik_valid;
	}, "NIK harus bilangan 16 digit dan tidak boleh diawali 0");

	// TODO : Jika validasi no_kk sudah siap seperti nik sementara, silahkan gunakan validasi nik dengan pesan yg dinamis
	jQuery.validator.addMethod("no_kk", function(value, element) {
		no_kk_valid = /^\d*$/.test(value) && (value.length == 16) && (value.indexOf('0') != 0);
		return this.optional(element) || no_kk_valid;
	}, "Nomor KK harus bilangan 16 digit dan tidak boleh diawali 0");

	jQuery.validator.addMethod("angka", function(value, element) {
		angka_valid = /^\d*$/.test(value);
		return this.optional(element) || angka_valid;
	}, "Harus Berisi Angka");

	jQuery.validator.addMethod("luas", function(value, element) {
		luas_valid = /^\d+(\.\d+)*$/.test(value);
		return this.optional(element) || luas_valid;
	}, "Harus Berisi Angka dan untuk koma gunakan \"titik\"");

	jQuery.validator.addMethod("nama", function(value, element) {
		valid = /^[a-zA-Z '\.,\-]+$/.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip");
	
	jQuery.validator.addMethod("nama_desa", function(value, element) {
		valid = /^[a-zA-Z0-9 '\.,`\-\/\(\)]+$/.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik, garis miring dan strip");

	jQuery.validator.addMethod("nama_suku", function(value, element) {
		valid = /^[a-zA-Z ]+$/.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alpha dan spasi");

	jQuery.validator.addMethod("alfanumerik", function(value, element) {
		valid = /^[a-zA-Z0-9 ]+$/i.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alfanumerik");

	jQuery.validator.addMethod("nama_terbatas", function(value, element) {
		valid = /^[a-zA-Z0-9 \/\-]+$/i.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alfanumerik, spasi, slash dan strip");

	jQuery.validator.addMethod("nama_surat", function(value, element) {
		valid = /^[a-zA-Z0-9 ()\-]+$/i.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alfanumerik, spasi, strip, (, )");

	jQuery.validator.addMethod("nama_produk", function(value, element) {
		valid = /^[a-zA-Z0-9()&_:=°% \-]+$/i.test(value);
		return this.optional(element) || valid;
	}, `Hanya boleh berisi karakter alfanumerik, spasi, strip, (, ), &, :, =, °, %`);

	jQuery.validator.addMethod("nomor_sk", function(value, element) {
		valid = /^[a-zA-Z0-9 \.\-\/,]+$/i.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alfanumerik, spasi, titik, garis miring dan strip");

	jQuery.validator.addMethod("peraturan_desa", function(value, element) {
		valid = /^[a-zA-Z0-9 \.\-\/,()]+$/i.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alfanumerik, spasi, titik, garis miring, (, ) dan strip");

	jQuery.validator.addMethod("alfanumerik_titik", function(value, element) {
		valid = /^[a-zA-Z0-9\.]+$/i.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alfanumerik dan titik");

	jQuery.validator.addMethod("alfanumerik_spasi", function(value, element) {
		valid = /^[a-zA-Z0-9 ]+$/i.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alfanumerik dan spasi");

	jQuery.validator.addMethod("bilangan_titik", function(value, element) {
		valid = /^[0-9\.]+$/.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter numerik dan titik");

	jQuery.validator.addMethod("strip_tags", function(value, element) {
		var strippedText = value.replace(/<\/?[^>]+(>|$)/g, "");
		return strippedText === value;
	}, "Tidak boleh mengandung tag HTML");

	jQuery.validator.addMethod("judul", function(value, element) {
		const valid = /^[a-zA-Z0-9()[\]&_:;=°%'".,/\- ]+$/i.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alfanumerik, spasi, strip, titik, koma (,), [, ], &, :, ;, =, °, %, ', \", -, dan /");

	jQuery.validator.addMethod("judul_tinymce", function(value, element) {
		valid = /^[a-zA-Z0-9\s]+$/.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter huruf besar, huruf kecil, dan spasi");


	jQuery.validator.addMethod("prefix_tinymce", function(value, element) {
		valid = /^[a-z_]+$/.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alpha kecil dan garis bawah (_)");

	jQuery.validator.addMethod(
	"short_url",
	function (value, element) {
		valid = value.length <= 150;
		return this.optional(element) || valid;
		},
		"Maksimal 150 karakter. Silahkan menyingkat url menggunakan <a href='https://s.id/' target='_blank'>s.id</a> atau atau sejenisnya.",
	);

	$('.bilangan_titik').each(function() {
		$(this).rules("add", {
			bilangan_titik: true,
		});
	});

	jQuery.validator.addMethod("bilangan_spasi", function(value, element) {
		valid = /^[0-9 ]+$/.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter numerik dan spasi");

	$('.bilangan_spasi').each(function() {
		$(this).rules("add",
			{
				bilangan_spasi: true,
			});
	});
	
	var pesanSandi = (typeof SYARAT_SANDI == 'undefined') ? '' : SYARAT_SANDI;

	// Ketentuan kata sandi sesuai US National Institute of Standards and Technology (NIST)
	//https://en.wikipedia.org/wiki/Password_policy#:~:text=Passwords%20must%20be%20at%20least,should%20be%20acceptable%20in%20passwords
	jQuery.validator.addMethod("pwdLengthNist", function(value, element) {
		valid = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,20}$/.test(value);
		return this.optional(element) || valid;
	}, pesanSandi);
	

	$('.pwdLengthNist').each(function() {
		$(this).rules("add",
			{
				pwdLengthNist: true,
			});
	});

	// Ketentuan kata sandi sesuai US National Institute of Standards and Technology (NIST)
	jQuery.validator.addMethod("pwdLengthNist_atau_kosong", function(value, element) {
		valid = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,20}$/.test(value);
		return this.optional(element) || valid;
	}, pesanSandi);

	jQuery.validator.addMethod("bilangan", function(value, element) {
		valid = /^[0-9]+$/.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter numerik");

	jQuery.validator.addMethod("alamat", function(value, element) {
		valid = /^[a-zA-Z0-9 '\.,\-\/]+$/.test(value);
		return this.optional(element) || valid;
	}, "Hanya boleh berisi karakter alpha, numerik, spasi, titik, koma, strip, tanda petik dan garis miring");

	jQuery.validator.addMethod("username", function(value, element) {
		valid = /^[a-zA-Z0-9]{4,30}$/.test(value);
		return this.optional(element) || valid;
	}, "Username hanya boleh berisi karakter alpha, numerik dan terdiri dari 4 hingga 30 karakter");

	jQuery.validator.addMethod("email", function(value, element) {
		valid = /^[a-zA-Z0-9@._\\-]{4,30}$/.test(value);
		return this.optional(element) || valid;
	}, "Email hanya boleh berisi karakter alpha, numeric, titik, strip, garis bawah, dan terdiri dari 4 hingga 30 karakter");

	jQuery.validator.addMethod("telegram", function(value, element) {
		valid = /^@[a-zA-Z0-9\_]{5,100}$/.test(value);
		return this.optional(element) || valid;
	}, "Username Telegram diawali @ dan berisi minimal 5 karakter alpha, numerik dan garis bawah");

	jQuery.validator.addMethod("pin_mandiri", function(value, element) {
		angka_valid = /^(?=.*\d).{6,6}$/.test(value);
		return this.optional(element) || angka_valid;
	}, "Hanya boleh berisi 6 angka numerik");

	jQuery.validator.addMethod("ip_address", function(value, element) {
		valid = /^([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4}|(\d{1,3}\.){3}\d{1,3}$/.test(value);
		return this.optional(element) || valid;
	}, "Isi IP address yang valid");

	jQuery.validator.addMethod("mac_address", function(value, element) {
		valid = /^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/.test(value);
		return this.optional(element) || valid;
	}, "Isi Mac address yang valid");

	// Untuk tanggal lapor dan tanggal peristiwa
	jQuery.validator.addMethod("tgl_lebih_besar", function(value, element, params)  {
		tgl_minimal = $(params).val().split("-");
		tgl_minimal = new Date(+tgl_minimal[2], tgl_minimal[1] - 1, +tgl_minimal[0]);
		tgl_ini = value.split("-");
		tgl_ini = new Date(+tgl_ini[2], tgl_ini[1] - 1, +tgl_ini[0]);
		if (tgl_ini >= tgl_minimal)
			return true;
		return false;
	}, "Tanggal harus sama atau lebih besar dari tanggal minimal.");

	jQuery.validator.addMethod("jam_lebih_besar", function(value, element, params)  {
		jam_minimal = $(params).val();		
		jam_ini = value;		
		if (jam_ini >= jam_minimal)
			return true;
		return false;
	}, "Jam harus sama atau lebih besar dari jam minimal.");

	jQuery.validator.addMethod("warna", function(value, element) {
		valid = /^#[a-zA-Z0-9#]+$/i.test(value) || /^rgba[a-zA-Z0-9.,()]+$/i.test(value);
		return this.optional(element) || valid;
	}, `Hanya boleh berisi karakter alfanumerik, tagar, titik, koma, buka dan tutup kurung`);

	// https://www.aspsnippets.com/questions/532641/Validation-Latitude-and-Longitude-using-Regular-Expression-in-jQuery/
	jQuery.validator.addMethod("lat", function(value, element) {
		var regexLat = new RegExp('^(\\+|-)?(?:90(?:(?:\\.0{1,18})?)|(?:[0-9]|[1-8][0-9])(?:(?:\\.[0-9]{1,18})?))$');

		return this.optional(element) || regexLat.test(value);
	}, `Isi lat tidak valid`);

	// https://www.aspsnippets.com/questions/532641/Validation-Latitude-and-Longitude-using-Regular-Expression-in-jQuery/
	jQuery.validator.addMethod("lng", function(value, element) {
		var regexLong = new RegExp('^(\\+|-)?(?:180(?:(?:\\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\\.[0-9]{1,18})?))$');
		
		return this.optional(element) || regexLong.test(value);
	}, `Isi lng tidak valid`);

	jQuery.validator.addMethod("telepon", function(value, element) {
 		return this.optional(element) || value.length > 9;
	}, `Minimal 10 dan maksimal 20 karakter`);

	jQuery.validator.addMethod("id_telegram", function(value, element) {
		valid = /^[0-9]{5,10}$/.test(value);
		return this.optional(element) || valid;
	},`Minimal 5 dan maksimal 10 karakter dan harus angka`);

	jQuery.validator.addMethod("kode_isian", function(value, element) {
		valid = /^\[\w+\]$/.test(value);
		return this.optional(element) || valid;
	},`Harus diawali [ dan diakhiri ]`);

	jQuery.validator.addMethod("format_tanggal", function(value, element) {
		// Regex untuk memastikan hanya karakter format tanggal yang valid (d, D, j, m, M, F, Y, y, H, h, i, s, A, a, dll.)
		const regex = /^[djmnMFYyHhisAa]([:\/\-\s]?[djmnMFYyHhisAa])*$/;
		return this.optional(element) || regex.test(value);
	}, "Format tidak valid. Contoh format yang benar: d F Y H:i:s, d-M-Y, Y/m/d, H:i:s");
});

function validate(elementClassId) {
	$(elementClassId).validate({
		ignore: ".ignore",
		errorElement: "label",
		errorClass: "error",
		highlight:function (element){
			$(element).closest(".form-group").addClass("has-error");
		},
		unhighlight:function (element){
			$(element).closest(".form-group").removeClass("has-error");
		},
		errorPlacement: function (error, element) {
			if (element.parent('.input-group').length) {
				error.insertAfter(element.parent());
				// element.parent().focus();
			} else if (element.hasClass('select2')) {
				error.insertAfter(element.next('span'));
				// element.next('span').focus();
			} else {
				error.insertAfter(element);
				// element.focus();
			}
		},
		invalidHandler: function(e, validator){
			if(validator.errorList.length && $('#tabs').length) {
				$('#tabs a[href="#' + $(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show');
			}
		},
	});

	$(elementClassId).on('change', function() {
		$(this).valid();
	});
}
