
	<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">
			<form class="form-horizontal" id=""  method="get">
				<div class="col-sm-8">

				<div class="form-group">
					<label class="control-label col-sm-3" >Tgl. Mulai :</label>
					<div class="col-sm-3">
						<input type="text" value="<?=$this->input->get('tgl_mulai');?>"  data-date-format='dd/mm/yyyy'  autocomplete="off" class="form-control datePicker " required id="tgl_mulai"  name="tgl_mulai">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Tgl. Selesai :</label>
					<div class="col-sm-3">
						<input type="text" value="<?=$this->input->get('tgl_selesai');?>" data-date-format='dd/mm/yyyy'  autocomplete="off" class="form-control  datePicker " required  id="tgl_selesai"  name="tgl_selesai">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-3" >Instansi :</label>
					<div class="col-sm-9">
						<select type="input" class="form-control select2" onchange="ganti_instansi(this.value)" required id="id_instansi"  name="id_instansi">
							<option value="">Silahkan Pilih</option>
							<?php
							foreach($this->dataInstansi as $data){
							 ?>
							<option <?php if($this->input->get('id_instansi') == $data->kode) echo "selected";?> value="<?php echo $data->kode; ?>"><?php echo $data->nama; ?></option>
							<?php
							}
						 	?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-3" ></label>
					<div class="col-sm-6">

						<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
					</div>
				</div>


			</div>

			</form>

		</div>
		<hr>

		<?php
		if($this->input->get('id_instansi')){
		
		?>
		<div class = "table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Nama</th>
						<th>Nip</th>
						<th>Keterangan</th>
						<th>Dispensasi Presensi</th>
						<th>Tanggal</th>
						<th>Lampiran</th>
						<th>Tgl Update</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>

				<?php
				$no = $this->input->get('per_page')+ 1;

				foreach($this->dataKendala as $data){
				?>
					<tr>
						<td><?php  echo $data->nama_pegawai; ?></td>
						<td><?php  echo $data->nip; ?></td>
						<td><?php  echo $data->keterangan; ?></td>
						<td>
						<?php
						if($data->dispensasi == '1'){
							echo "Listrik Padam";
						}
						elseif($data->dispensasi == '2'){
							echo "Mesin Tidak Berfungsi";
						}
						elseif($data->dispensasi == '3'){
							echo "Pegawai tidak bisa finger / ditolak";
						}
						else{
							echo "Penugasan";
						}
						?>
						</td>
						<td><?php  echo $data->tanggal; ?></td>

						<td>
							<a href="#" onclick="tampil_surat('<?php  echo $data->file_lampiran; ?>')">
								Lampiran
							</a>
						</td>
						<td><?php  echo $data->tanggal_update; ?></td>
						<td align=center>
							<?php
							$url = "https://". $_SERVER['SERVER_NAME'] . ":" . $_SERVER['REQUEST_URI'];
							if (!$data->kunci) {
							////// cara ambil Button Edit ( link edit )
								echo $this->template_view->getEditButton(base_url()."kendala_teknis/edit/?id_log_absensi=".$data->id_log_absensi."&redirect=".$url);
								?>
								&nbsp;
								<?php
								$tgl_mulai   = $this->input->get('tgl_mulai');
								$tgl_selesai = $this->input->get('tgl_selesai');
								$id_instansi = $this->input->get('id_instansi');

								////// cara ambil Button Delete (pesan yang ingin ditampilkan, link Delete)
								echo $this->template_view->getDeleteButton($data->nama_pegawai,base_url().$this->uri->segment(1)."/delete/".$data->id_log_absensi."/?tgl_mulai=".$this->input->get('tgl_mulai')."&tgl_selesai=".$this->input->get('tgl_selesai')."&id_instansi=".$this->input->get('id_instansi')."");
							}
							?>
						</td>
					</tr>

				<?php
					$no++;
				}
				if(!$this->dataKendala)
				{
						echo "<tr><td colspan='25' align='center'>Data tidak ada.</td></tr>";
				}
				?>

				</tbody>
			</table>
		</div>
		<?php
			if ($this->dataKendala) { ?>
				<center>
					<?php echo $this->pagination->create_links();?>
					<br>
					<span class="btn btn-default">Jumlah Data : <b><?php echo count($this->dataKendala);?></b></span>
				</center>
			<?php } ?>
		<?php }?>
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

<div class="modal fade" id="modal_status" tabindex="-1" role="dialog" aria-labelledby="modal_tampil_surat" aria-hidden="true" data-backdrop="stastic">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×	</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Form Ubah Status</h4>
            </div>
            <div class="modal-body">

				<form class="form-horizontal" id=""  method="get">

				<input id="id_t_ijin" name="id_t_ijin"  type="hidden">

				<div class="form-group">
					<label class="control-label col-sm-3" >Tgl. Selesai :</label>
					<div class="col-sm-3">
						<select type="input" class="form-control select2" onchange="ganti_instansi(this.value)" required id="status"  name="status">
							<option value="">Silahkan Pilih</option>
							<option value="1">Setujui</option>
							<option value="0">Pending</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" ></label>
					<div class="col-sm-7">
						<span class="btn btn-primary" onclick="simpan_status()">Simpan</span>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" ></label>
					<div class="col-sm-9" >
						<span id="pesan_error_status"></span>
					</div>
				</div>
				</form>
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

function modal_status(id_t_ijin){
	$('#modal_status').modal('show');
	$('#id_t_ijin').val(id_t_ijin);


}

function simpan_status(){
	$.ajax({
			url: base_url+'daftar_ijin_cuti_pegawai/ubah_status',
			type:'POST',
			dataType:'json',
			data:{id_t_ijin: $('#id_t_ijin').val(), status : $('#status').val()},
			beforeSend: function(){
				//$('#pesan_error_status').hide();
			},
			success: function(data){
				if( data.status ){
					location.reload();
				}
				else{
					$('#pesan_error_status').html(data.pesan);
				}
			},
			error : function(data) {
				$('#pesan_error_status').html('maaf telah terjadi kesalahan dalam program, silahkan anda mengakses halaman lainnya.'); $('#pesan_error_status').show();
				//$('#pesan_error').html( '<h3>Error Response : </h3><br>'+JSON.stringify( data ));
			}
		})
}

$("#nip_autocomplete").autocomplete({
	source	:	base_url+'search/pegawai/',
	select	: 	function (e, ui) {

		$("#nama").val(ui.item.nama);
		$("#instansi").val(ui.item.instansi);
		$("#jabatan").val(ui.item.jabatan);
		$("#id_pegawai").val(ui.item.id_pegawai);
	}
});

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
		alert("error .. return bukan Json");
		$('#loading_upload').html('');
		$('#file_lampiran').val('');
	}
});
</script>
