function toRp(angka){
    var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
    var rev2    = '';
    for(var i = 0; i < rev.length; i++){
        rev2  += rev[i];
        if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
            rev2 += '.';
        }
    }
    return 'Rp. ' + rev2.split('').reverse().join('') ;
}

function toRibuan(angka){
    var rev     = parseInt(angka, 10).toString().split('').reverse().join('');
    var rev2    = '';
    for(var i = 0; i < rev.length; i++){
        rev2  += rev[i];
        if((i + 1) % 3 === 0 && i !== (rev.length - 1)){
            rev2 += '.';
        }
    }
    return  rev2.split('').reverse().join('');
}

function formatRibuan(objek) {
   a = objek.value;
   b = a.replace(/[^\d]/g,"");
   c = "";
   panjang = b.length;
   j = 0;
   for (i = panjang; i > 0; i--) {
     j = j + 1;
     if (((j % 3) == 1) && (j != 1)) {
       c = b.substr(i-1,1) + "." + c;
     } else {
       c = b.substr(i-1,1) + c;
     }
   }
   objek.value = c;
}


// Standard Form
/*$('#form_standar').validate({
	rules: {
		PASSWORD: "required",
		REPASS: {
		  equalTo: "#PASSWORD"
		}
	},
	submitHandler: function(form) {
		var urlTujuan = $("#form_standar").attr('action');
		$.ajax({
			url: urlTujuan,
			type:'POST',
			dataType:'json',
			data: $('#form_standar').serialize(),
			beforeSend: function(){
				$('#loading').show();
				$('#pesan_error').hide();
			},
			success: function(data){
				if( data.status ){
					$('.page-footer').append('<div class="modal fade" id="container-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="myModalLabel">Pesan Pemberitahuan</h4></div><div class="modal-body"><h4>Data berhasil disimpan.</h4></div><div class="modal-footer"><a href="'+data.redirect_link+'"> <button type="button" class="btn btn-primary">Ok</button></a></div></div></div></div>');
					$('#container-modal').modal('show');
				}
				else{
					$('#loading').hide(); $('#pesan_error').show(); $('#pesan_error').html(data.pesan);
				}
			},
			error : function(data) {
				$('#pesan_error').html('maaf telah terjadi kesalahan dalam program, silahkan anda mengakses halaman lainnya.'); $('#pesan_error').show(); $('#loading').hide();
				//$('#pesan_error').html( '<h3>Error Response : </h3><br>'+JSON.stringify( data ));
			}
		})
	}
});*/

$('#form_standar').validate({
	rules: {
		PASSWORD: "required",
		REPASS: {
		  equalTo: "#PASSWORD"
		}
	},
	submitHandler: function(form) {
		var urlTujuan = $("#form_standar").attr('action');
		var formData = new FormData($('#form_standar')[0]);
		$.ajax({
			url: urlTujuan,
			type:'POST',
			data: formData,
			contentType: false,
			processData: false,
			dataType:'json',
			// data: $('#form_standar').serialize(),
			beforeSend: function(){
				$('#loading').show();
				$('#pesan_error').hide();
			},
			success: function(data){
				if( data.status ){
					$('.page-footer').append('<div class="modal fade" id="container-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="myModalLabel">Pesan Pemberitahuan</h4></div><div class="modal-body"><h4>Data berhasil disimpan.</h4></div><div class="modal-footer"><a href="'+data.redirect_link+'"> <button type="button" class="btn btn-primary">Ok</button></a></div></div></div></div>');
					$('#container-modal').modal('show');
				}
				else{
					$('#loading').hide(); $('#pesan_error').show(); $('#pesan_error').html(data.pesan);
				}
			},
			error : function(data) {
				$('#pesan_error').html('maaf telah terjadi kesalahan dalam program, silahkan anda mengakses halaman lainnya.'); $('#pesan_error').show(); $('#loading').hide();
				//$('#pesan_error').html( '<h3>Error Response : </h3><br>'+JSON.stringify( data ));
			}
		})
	}
});

$('#PASSWORD_LOGIN').keydown(function(event){
    var keyCode = (event.keyCode ? event.keyCode : event.which);
    if (keyCode == 13) {
        $('#startSearch').trigger('click');
    }
});


//// login jika session habis
$('#form_login').validate({
	submitHandler: function(form) {
		$.ajax({
			url: base_url+'login/login_data',
			type:'POST',
			dataType:'json',
			data: $('#form_login').serialize(),
			beforeSend: function(){
				$('#loading_login').show();
				$('#pesan_error_login').hide();
			},
			success: function(data){
				if( data.status ){
					if($('#forAction').val()=='disableModal'){
						$('#modalLogin').hide('scale',function(){
							location.reload();
						});
					}
					else{
						$('#modalLogin').slideUp('scale',function(){
							location.href= data.redirect_link;
						});
					}
				}
				else{
					$('#loading_login').hide(); $('#pesan_error_login').show(); $('#pesan_error_login').html(data.pesan);
				}
			},
			error : function(data) {
				$('#pesan_error_login').html('maaf telah terjadi kesalahan dalam program, silahkan anda mengakses halaman lainnya.'); $('#pesan_error_login').show(); $('#loading_login').hide();
				//$('#pesan_error').html( '<h3>Error Response : </h3><br>'+JSON.stringify( data ));
			}
		})
	}
});



function tampil_pesan_hapus(pesan_hapus,link_hapus){
	$('.page-footer').append('<div class="modal fade" id="container-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="myModalLabel">Pesan Konfirmasi</h4></div><div class="modal-body"><h4>Apakah anda yakin akan menghapus data <b>'+pesan_hapus+'</b> ..?</h4></div><div class="modal-footer"><div class="pull-left"><button type="button" class="btn btn-warning" data-dismiss="modal">Tidak</button></div><a href="'+link_hapus+'"> <button type="button" class="btn btn-primary">Ya</button></a></div></div></div></div>');
	$('#container-modal').modal('show');

}

function tampil_pesan_custom(isiPesan, label, link_hapus){
	$('.page-footer').append('<div class="modal fade" id="container-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false"> <div class="modal-dialog" role="document"> <div class="modal-content"><div class="modal-header"> <h4 class="modal-title" id="myModalLabel">Pesan Konfirmasi</h4> </div> <div class="modal-body"><h4>'+isiPesan+' <b>'+label+'</b> ..?</h4></div><div class="modal-footer"><div class="pull-left"><button type="button" class="btn btn-warning" data-dismiss="modal">Tidak</button></div><a href="'+link_hapus+'"> <button type="button" class="btn btn-primary">Ya</button></a></div></div></div></div>');
	$('#container-modal').modal('show');

}

function tampil_pesan_ajax(isiPesan, label, method, param){
	var str = method+"('"+param+"')";
	$('.page-footer').append(
		'<div class="modal fade" id="container-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false">'+
			'<div class="modal-dialog" role="document"> <div class="modal-content"><div class="modal-header">'+
				'<h4 class="modal-title" id="myModalLabel">Pesan Konfirmasi</h4>'+
			'</div>'+
			'<div class="modal-body">'+
				'<h4>'+isiPesan+' <b>'+label+'</b> ..?</h4>'+
			'</div>'+
			'<div class="modal-footer">'+
				'<div class="pull-left">'+
					'<button type="button" class="btn btn-warning" data-dismiss="modal">Tidak</button>'+
				'</div>'+
				'<button type="button" class="btn btn-primary" onclick="'+str+'" >Ya</button>'+
			'</div>'+
		'</div></div></div>');
	$('#container-modal').modal('show');

}

function showModalLogOut(link){
	$('.page-footer').append('<div class="modal fade" id="modalLogout" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="myModalLabel">Pesan Konfirmasi</h4></div><div class="modal-body"><h4>Apakah anda yakin akan keluar  ..?</h4></div><div class="modal-footer"><div class="pull-left"><button type="button" class="btn btn-warning" data-dismiss="modal">Tidak</button></div><a href="'+link+'"> <button type="button" class="btn btn-primary">Ya</button></a></div></div></div></div>');
	$('#modalLogout').modal('show');

}

function checkAllDeleteButton(){
	if ($('#checkAllDelete').is(':checked')) {
		$('input:checkbox').prop('checked', true);
	}
	else{
		$('input:checkbox').prop('checked', false);
	}
}

$("#NAMA_KARYAWAN_AUTOCOMPLETE").autocomplete({
	source:base_url+'karyawan/search_karyawan/',
	select: function (e, ui) {
		$("#ID_KARYAWAN").val(ui.item.id_karyawan);
	}
});



/** contoh upload **/

$('#form_updload').ajaxForm({
	url: base_url+'save_upload/foto/',
	type: 'post',
	dataType: 'json',
	resetForm: false,
	beforeSubmit: function() {
		$('#loading_input_foto_karyawan').show();
	},
	success: function(data) {
		if(data.status){

		}
		else{
			$('#pesan_error_input_foto_karyawan').html(data.pesan);
			$('#pesan_error_input_foto_karyawan').show();
		}
	},
	error : function(data) {
		alert("error .. return bukan Json");
	}
});

// js function to get uri string value
function getParameterByName(name, url) {
	if (!url) url = window.location.href;
	name = name.replace(/[\[\]]/g, '\\$&');
	var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
		results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

function formatDate(date, date_type = null) {
	var d = new Date(date),
		month = '' + (d.getMonth() + 1),
		day = '' + d.getDate(),
		year = d.getFullYear();

	if (month.length < 2) month = '0' + month;
	if (day.length < 2) day = '0' + day;

	if (date_type == 'hari') {
		return day;
	} else if (date_type == 'bulan') {
		return month;
	} else if (date_type == 'tahun') {
		return year;
	} else {
		date_type = null;
	}

	if (date_type == null) {
		return [day, month, year].join('-');
	}
}

//////////////////////// end ////////////////////////
