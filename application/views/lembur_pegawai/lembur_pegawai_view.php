
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
					<label class="control-label col-sm-3" >No Surat :</label>
					<div class="col-sm-8">
						<input type="text" class="form-control required " id="no_surat"  name="no_surat">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Tanggal Surat :</label>
					<div class="col-sm-6">
						<input type="input" class="form-control datePickerFormLembur required" autocomplete="off" id="tgl_surat"  name="tgl_surat">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Tgl. Lembur :</label>
					<div class="col-sm-6">
						<input type="text" class="form-control datePickerFormLembur required" autocomplete="off" id="tgl_lembur"  name="tgl_lembur">
					</div>
				</div>
				<div class="form-group row">
					<label class="control-label col-sm-3" >Jam Mulai :</label>
					<div class="col-sm-2">
						<input type="text" class="form-control required" autocomplete="off" id="jam_mulai"  name="jam_mulai">
					</div>
					<label class="control-label col-sm-3" >Jam Selesai :</label>
					<div class="col-sm-2">
						<input type="text" class="form-control required" autocomplete="off" id="jam_selesai"  name="jam_selesai">
					</div>
					<div class="col-sm-2">
						<div class="checkbox">
							<label><div><span><input type="checkbox" value="t" name="pulang_besoknya"></span></div> Pulang Hari Besoknya</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3"></label>
					<label class="control-label col-sm-8" style="text-align:left;">NB: Mohon Checklist Pulang hari besoknya apabila lembur hingga hari berikutnya.</label>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Keterangan :</label>
					<div class="col-sm-8">
						<textarea class="form-control " id="keterangan"  name="keterangan"></textarea>
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
					<div class="form-group">
						<label class="control-label col-sm-3" ></label>
						<div class="col-sm-8">
							<img src="<?=base_url();?>assets/img/loading.gif" id="loading" style="display:none">
							<span id="pesan_error"></span>
						</div>
					</div>
					<div class="col-sm-12">
						<div class="pull-right">

						<input type="hidden" id="file_lampiran" name="file_lampiran">

						<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
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
							<input type="file" class="form-control" name="userfile" accept="image/jpg, image/gif, application/pdf">
							<input type="hidden" value="lembur" name="folder">
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

	$(function () {
			$('#jam_mulai').datetimepicker({
					startView: 0,
					format: 'hh:ii',
					autoclose: true
			});
			$('#jam_selesai').datetimepicker({
					startView: 0,
					format: 'hh:ii',
					autoclose: true
			});
	});


$( "#nip_autocomplete" ).autocomplete({
	source: function (request, response) {
		$.ajax({
			type	: 	"POST",
			url		:	base_url+'search/pegawai/',
			data	: 	{term:request.term	,	kode_instansi	:	"<?php if($this->session->userdata('id_kategori_karyawan')=='4' || $this->session->userdata('id_kategori_karyawan')=='3'){ echo $this->session->userdata('kode_instansi'); } ?>", tanggal : $("#tgl_lembur").val()},
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
