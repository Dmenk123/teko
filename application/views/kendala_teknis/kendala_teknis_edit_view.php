<!-- 
3 = Pegawai tidak bisa finger / ditolak
2 = Mesin Tidak Berfungsi
4 = Penugasan
1 = Listrik Padam
 -->

<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">
			<form class="form-horizontal" id="form_standar" action="<?=base_url()."".$this->uri->segment(1)."/".$this->uri->segment(2);?>_data" method="get">


				<input type="hidden" value="<?php echo $this->oldData->id_log_absensi ?>"  name="id_log_absensi">
				<input type="hidden" size="100" value="<?php echo $this->url; ?>" id="redirect"  name="redirect">

				<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label col-sm-3" >Dispensasi :</label>
					<div class="col-sm-8">
						<select class="form-control required select2" id="dispensasi"  name="dispensasi">
							<option value="">Silahkan Pilih</option>
							<option <?php if($this->oldData->dispensasi == '1')  echo "selected"; ?> value="1">Listrik Padam</option>
							<option <?php if($this->oldData->dispensasi == '2')  echo "selected"; ?> value="2">Mesin tidak berfungsi</option>
							<option <?php if($this->oldData->dispensasi == '3')  echo "selected"; ?> value="3">Pegawai tidak bisa Finger / ditolak</option>
							<option <?php if($this->oldData->dispensasi == '4')  echo "selected"; ?> value="4">Penugasan</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Tanggal :</label>
					<div class="col-sm-3">
						<input type="input" class="form-control datePicker required" value="<?php echo $this->oldData->tanggal_untuk_edit ?>" autocomplete="off" id="tanggal"  name="tanggal">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Jam & Menit :</label>
					<div class="col-sm-3">
						<select class="form-control required " id="jam"  name="jam">
							<option value="">Jam</option>
							<?php
							for($i=1; $i<24 ; $i++){
							?>
							<option <?php if($this->oldData->jam_untuk_edit == $i)  echo "selected"; ?> value="<?php echo sprintf("%02d", $i);; ?>"><?php echo sprintf("%02d", $i);; ?></option>
							<?php
							}
							?>
						</select>
					</div>
					<div class="col-sm-3">
						<select class="form-control required " id="menit"  name="menit">
							<option value="">Menit</option>
							<?php
							for($i=0; $i<60 ; $i++){
							?>
							<option <?php if($this->oldData->menit_untuk_edit == $i)  echo "selected"; ?> value="<?php echo sprintf("%02d", $i);; ?>"><?php echo sprintf("%02d", $i);; ?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-3" >Keterangan :</label>
					<div class="col-sm-8">
						<textarea class="form-control required " id="keterangan"  name="keterangan"><?php echo $this->oldData->keterangan ?></textarea>
					</div>
				</div>

				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label col-sm-3" >NIP :</label>
						<div class="col-sm-9">
							<input type="text" class="form-control required" placeholder="Masukkan NIP atau Nama" id="nip_autocomplete"  name="nip" value="<?php echo $this->dataPegawai->nip ?>">
							<input type="hidden"  id="id_pegawai"  name="id_pegawai" value="<?php echo $this->dataPegawai->id_pegawai ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" >Nama :</label>
						<div class="col-sm-9">
							<input type="text" class="form-control " readonly id="nama" value="<?php echo $this->dataPegawai->nama ?>"  name="nama">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" >Instansi :</label>
						<div class="col-sm-9">
							<input type="text" class="form-control " readonly id="instansi" value="<?php echo $this->dataPegawai->nama_instansi ?>"  name="instansi">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" >Jabatan :</label>
						<div class="col-sm-9">
							<input type="text" class="form-control " readonly id="jabatan" value="<?php echo $this->dataPegawai->nama_jabatan ?>"  name="jabatan">
						</div>
					</div>
					<div class="col-sm-12">
						<div class="pull-right">

						<input type="hidden" id="file_lampiran" name="file_lampiran" value="<?php echo $this->oldData->file_lampiran ?>">

						<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>

						<span class="btn btn-warning" onclick="window.history.back();"><i class="glyphicon glyphicon-remove"></i> Batal</span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" ></label>
						<div class="col-sm-8">
							<img src="<?=base_url();?>assets/img/loading.gif" id="loading" style="display:none">
							<span id="pesan_error"></span>
						</div>
					</div>
				</div>


			</form>

		</div>

		<div class="row">

			<form class="form-horizontal" id="form_upload">
				<div class="col-sm-6">

					<div class="form-group">
						<label class="control-label col-sm-3" >
							<br><br>
							Lampiran :
						</label>
						<div class="col-sm-8">
							<hr>
							<input type="file" class="form-control"  name="userfile">
							<input type="hidden" value="kendala_teknis" name="folder">
						</div>
					</div>
					<div class="form-group" >
						<label class="control-label col-sm-3" ></label>
						<div class="col-sm-9" id="div_surat">
							Tampilkan Lampiran :  <a href="#" onclick="tampil_surat('<?php  echo $this->oldData->file_lampiran; ?>')">
								<?php  echo $this->oldData->file_lampiran; ?>
							</a>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" ></label>
						<div class="col-sm-8">
							<button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Upload</button>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-3" ></label>
						<div class="col-sm-8">
							<span id="loading_upload"></span>
						</div>
					</div>
				</div>
			</form>
		</div>
</div>

<div class="modal fade" id="modal_tampil_surat" tabindex="-1" role="dialog" aria-labelledby="modal_tampil_surat" aria-hidden="true" data-backdrop="stastic">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×	</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Lihat Lampiran Surat</h4>
            </div>
            <div class="modal-body">
				<iframe id="frame_surat" width="100%" height="500px"></iframe>
				<span id="pesan_file_garbis_lama"></span>
            </div>
        </div>
    </div>
</div>

<script>

function tampil_surat(nama_file){
	$('#modal_tampil_surat').modal('show');

	if(nama_file){
		$('#frame_surat').show();
		$('#frame_surat').attr('src',base_url+"upload/kendala_teknis/"+nama_file);
		$('#pesan_file_garbis_lama').hide();
	}
	else{

		$('#frame_surat').hide();
		$('#pesan_file_garbis_lama').show();
		$('#pesan_file_garbis_lama').html('Maaf, File Lampiran tidak dapat dilihat');
	}

}

$( "#nip_autocomplete" ).autocomplete({
	source: function (request, response) {
		$.ajax({
			type	: 	"POST",
			url		:	base_url+'search/pegawai/',
			data	: 	{term:request.term	,	kode_instansi	:	"<?php if($this->session->userdata('id_kategori_karyawan')=='4' || $this->session->userdata('id_kategori_karyawan')=='3'){ echo $this->session->userdata('kode_instansi'); } ?>"},
			success	: 	response,
			dataType: 	'json'
		});
	},
	select	: 	function (e, ui) {

		$("#nama").val(ui.item.nama);
		$("#instansi").val(ui.item.instansi);
		$("#jabatan").val(ui.item.jabatan);
		$("#id_pegawai").val(ui.item.id_pegawai);

		ganti_nip(ui.item.nip);
	}
}, {minLength: 5 });


function ganti_nip(nip){
	$("#nip_autocomplete").val(nip);
}

$('#form_upload').ajaxForm({
	url: base_url+'upload/surat/',
	type: 'post',
	dataType: 'json',
	resetForm: false,
	beforeSubmit: function() {
		$('#loading_upload').html('Proses Upload ...');
	},
	success: function(data) {
		if(data.status){
			$('#file_lampiran').val(data.nama_file);
			$('#loading_upload').html('Lampiran berhasil diupload.');
			$('#div_surat').html("Lihat Lampiran Baru : <a href='#' onclick=\"tampil_surat('"+data.nama_file+"')\">"+data.nama_file+"</a>");

		}
		else{
			$('#loading_upload').html('<label for="tgl_surat" class="error">Lampiran gagal diupload, pastikan format File adalah pdf.</label>');
			$('#file_lampiran').val('');
		}
	},
	error : function(data) {
		alert("error .. return bukan Json");
		$('#loading_upload').html('');
		$('#file_lampiran').val('');
	}
});
</script>
