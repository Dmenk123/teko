<!-- 3 = Pegawai tidak bisa finger / ditolak
2 = Mesin Tidak Berfungsi
4 = Penugasan
1 = Listrik Padam -->


<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">
			<form class="form-horizontal" id="form_standar" action="<?=base_url()."".$this->uri->segment(1)."/".$this->uri->segment(2);?>_data" method="get">
				<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label col-sm-3" >Dispensasi :</label>
					<div class="col-sm-8">
						<select class="form-control required select2" id="dispensasi"  name="dispensasi">
							<option value="">Silahkan Pilih</option>
							<option value="1">Listrik Padam</option>
							<option value="2">Mesin tidak berfungsi</option>
							<option value="3">Pegawai tidak bisa Finger / ditolak</option>
							<option value="4">Penugasan</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Tanggal :</label>
					<div class="col-sm-3">
						<input type="input" class="form-control datePickerFormKendala required" autocomplete="off" id="tanggal"  name="tanggal">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Jam & Menit :</label>
					<div class="col-sm-3">
						<select class="form-control required " id="jam"  name="jam">							
							<option value="">Jam</option>
							<?php
							for($i=0; $i<24 ; $i++){
							?>
							<option value="<?php echo sprintf("%02d", $i);; ?>"><?php echo sprintf("%02d", $i);; ?></option>							
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
							<option value="<?php echo sprintf("%02d", $i);; ?>"><?php echo sprintf("%02d", $i);; ?></option>							
							<?php
							}
							?>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label col-sm-3" >Keterangan :</label>
					<div class="col-sm-8">
						<textarea class="form-control required " id="keterangan"  name="keterangan"></textarea>
					</div>
				</div>

				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label col-sm-3" >NIP :</label>
						<div class="col-sm-9">
							<input type="text" class="form-control required" placeholder="Masukkan NIP atau Nama" id="nip_autocomplete"  name="nip">
							<input type="hidden"  id="id_pegawai"  name="id_pegawai">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" >Nama :</label>
						<div class="col-sm-9">
							<input type="text" class="form-control " readonly id="nama"  name="nama">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" >Instansi :</label>
						<div class="col-sm-9">
							<input type="text" class="form-control " readonly id="instansi"  name="instansi">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" >Jabatan :</label>
						<div class="col-sm-9">
							<input type="text" class="form-control " readonly id="jabatan"  name="jabatan">
						</div>
					</div>
					<div class="col-sm-12">
						<div class="pull-right">

						<input type="hidden" id="file_lampiran" name="file_lampiran">

						<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
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
							<input type="file" class="form-control"  name="userfile" accept="image/jpg, image/gif, application/pdf">
							<input type="hidden" value="kendala_teknis" name="folder">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-6"><strong>NB: Maksimal berukuran 2 MB</strong></label>
						<div class="col-sm-6">
							<button type="submit" class="btn btn-success" style="float:right; margin-right: 50px">
								<i class="fa fa-save"></i> Upload
							</button>
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

<script>


$( "#nip_autocomplete" ).autocomplete({
	source: function (request, response) {
		$.ajax({
			type	: 	"POST",
			url		:	base_url+'search/pegawai/',
			data	: 	{term:request.term	,	kode_instansi	:	"<?php if($this->session->userdata('id_kategori_karyawan')=='3'){ echo $this->session->userdata('kode_instansi'); } ?>"},
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
		}
		else{
			$('#loading_upload').html('<label for="tgl_surat" class="error">Lampiran gagal diupload, pastikan format File adalah pdf.</label>');
			$('#file_lampiran').val('');
		}
	},
	error : function(data) {
		alert("error .. file upload maksimal 2 MB");
		$('#loading_upload').html('');
		$('#file_lampiran').val('');
	}
});
</script>
