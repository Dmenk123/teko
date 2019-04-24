
<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">
			<form class="form-horizontal" id="form_standar" action="<?=base_url()."".$this->uri->segment(1)."/".$this->uri->segment(2);?>_data" method="get">

				<input type="hidden" value="<?php echo $this->oldData->id_t_ijin; ?>" id="id_t_ijin"  name="id_t_ijin">
				<input type="hidden" size="100" value="<?php echo $this->url; ?>" id="redirect"  name="redirect">

				<div class="col-sm-6">
				<div class="form-group">

					<label class="control-label col-sm-3" >Jenis Ijin :</label>
					<div class="col-sm-8">
						<select type="input" class="form-control select2 required" onchange="ganti_instansi(this.value)" id="id_jenis_ijin_cuti"  name="id_jenis_ijin_cuti">
							<option value="">Silahkan Pilih</option>
							<?php
							foreach($this->dataJenisIjinCuti as $data){
							 ?>
							<option  <?php if( $this->oldData->id_cuti ==  $data->id){echo "selected";} ?> value="<?php echo $data->id; ?>"><?php echo $data->nama; ?></option>
							<?php
							}
						 	?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >No Surat :</label>
					<div class="col-sm-8">
						<input type="text" class="form-control required " value="<?php echo $this->oldData->no_surat; ?>" id="no_surat"  name="no_surat">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Tanggal Surat :</label>
					<div class="col-sm-6">
						<input type="input" class="form-control datePicker required" autocomplete="off" value="<?php echo $this->oldData->tgl_surat_form; ?>" id="tgl_surat"  name="tgl_surat">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Tgl. Mulai :</label>
					<div class="col-sm-6">
						<input type="text" class="form-control datePicker required" autocomplete="off" value="<?php echo $this->oldData->tgl_mulai_form; ?>" id="tgl_mulai"  name="tgl_mulai">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Tgl. Selesai :</label>
					<div class="col-sm-6">
						<input type="text" class="form-control  datePicker required" autocomplete="off"  value="<?php echo $this->oldData->tgl_selesai_form; ?>" id="tgl_selesai"  name="tgl_selesai">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Keterangan :</label>
					<div class="col-sm-8">
						<textarea class="form-control " id="keterangan"  name="keterangan"><?php echo $this->oldData->no_surat; ?></textarea>
					</div>
				</div>

				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label col-sm-3" >NIP :</label>
						<div class="col-sm-9">
							<input type="text" class="form-control required"  value="<?php echo $this->oldData->nip; ?>" placeholder="Masukkan NIP atau Nama" id="nip_autocomplete"  name="nip">
							<input type="hidden"   id="id_pegawai"  value="<?php echo $this->oldData->id_pegawai; ?>"  name="id_pegawai">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" >Nama :</label>
						<div class="col-sm-9">
							<input type="text" class="form-control " readonly id="nama" value="<?php echo $this->oldData->nama_pegawai; ?>"  name="nama">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" >Instansi :</label>
						<div class="col-sm-9">
							<input type="text" class="form-control " readonly id="instansi"  value="<?php echo $this->instansi->nama_instansi; ?>" name="instansi">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" >Jabatan :</label>
						<div class="col-sm-9">
							<input type="text" class="form-control " readonly id="jabatan"  value="<?php echo $this->oldData->nama_jabatan; ?>" name="jabatan">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3" >Cuti per Tahun :</label>
						<div class="col-sm-9">
							<input type="text" class="form-control " readonly id="cuti_per_tahun"  name="cuti_per_tahun">
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

						<input type="hidden" id="file_lampiran" value="<?php  echo $this->oldData->file_lampiran; ?>" name="file_lampiran">

						<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>

						<span class="btn btn-warning" onclick="window.history.back();"><i class="glyphicon glyphicon-remove"></i> Batal</span>

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
							<input type="hidden" value="cuti" name="folder">
						</div>
					</div>
					<div class="form-group" id="div_surat">
						<label class="control-label col-sm-3" ></label>
						<div class="col-sm-9">
							Lihat File :  <a href="#" onclick="tampil_surat('<?php  echo $this->oldData->file_lampiran; ?>')">
								<?php  echo $this->oldData->no_surat; ?>
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
					<h4 class="modal-title">Lihat Surat</h4>
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
		$('#frame_surat').attr('src',base_url+"upload/cuti/"+nama_file);
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
			$('#div_surat').hide();
			$('#loading_upload').html('Lampiran berhasil diupload.');
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
