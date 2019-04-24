
<!-- Content Header (Page header) -->
<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		Edit Data <?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
			<form class="form-horizontal" id="form_standar" action="<?=base_url()."".$this->uri->segment(1)."/".$this->uri->segment(2);?>_data">
				<div class="form-group">
					<label class="control-label col-sm-4" >Nama :</label>
					<div class="col-sm-4">
						<input type="input" class="form-control required" value="<?php echo $this->oldData->nama; ?>" id="NAMA"  name="NAMA">
						<input type="hidden" class="form-control required" value="<?php echo $this->oldData->id; ?>" id="ID"  name="ID">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4"> Keterangan :</label>
					<div class="col-sm-4">
						<textarea class="form-control" id="KETERANGAN"  name="KETERANGAN"><?php echo $this->oldData->keterangan; ?></textarea>
					</div>
				</div>


				<div class="form-group">
					<div class="col-sm-offset-4 col-sm-10">
						<img src="<?php echo base_url();?>assets/img/loading.gif" id="loading" style="display:none">
						<p id="pesan_error" style="display:none" class="text-warning" style="display:none"></p>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-4 col-sm-10">
						<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
						<a href="<?=base_url()."".$this->uri->segment(1);?>">
							<span class="btn btn-warning"><i class="fa fa-remove"></i> Batal</span>
						</a>
					</div>
				</div>
			</form>

	</div>
</div>
<!-- /.content -->


<!-- Content Header (Page header) -->
<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<?php echo $this->template_view->nama_menu('nama_menu'); ?> Detail
		</div>

	</div>
	<div class="portlet-body">


		<div class="row">
			<div class="col-sm-6">


			<form class="form-horizontal" id="form_role_detail" action="<?=base_url()."".$this->uri->segment(1);?>/add_data_detail">
				<div class="form-group">
					<label class="control-label col-sm-4" >Hari :</label>
					<div class="col-sm-7">
						<select class="form-control required" id="ID_HARI"  name="ID_HARI">
							<option value="">Silahkan Pilih</option>
							<?php
							foreach($this->dataHari as $data){
							 ?>
								<option value="<?php echo $data->id;?>"><?php echo $data->nama;?></option>
							 <?php
								}
								?>
						</select>
						<input type="hidden" class="form-control required" value="<?php echo $this->oldData->id; ?>" id="ID_ROLE"  name="ID_ROLE">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4" >Jam kerja :</label>
					<div class="col-sm-7">
						<select class="form-control required" id="ID_JAM_KERJA"  name="ID_JAM_KERJA">
							<option value="">Silahkan Pilih</option>
							<?php
							foreach($this->dataJamKerja as $data){
							 ?>
							 	<option value="<?php echo $data->id;?>"><?php echo $data->nama;?></option>
							 <?php
						 		}
							  ?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-offset-4 col-sm-10">
						<img src="<?php echo base_url();?>assets/img/loading.gif" id="loading_detail" style="display:none">
						<p id="pesan_error_detail" style="display:none" class="text-warning" style="display:none"></p>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-4 col-sm-10">
						<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
						<a href="<?=base_url()."".$this->uri->segment(1);?>">
						</a>
					</div>
				</div>
			</form>


	</div>
	<div class="col-sm-6">
		<table class="table table-bordered">
				<thead>
					<tr>
						<th width="5%">No.</th>
						<th>Hari</th>
						<th>Nama Jam Kerja</th>
						<th>Jam</th>
						<th></th>

					</tr>
				</thead>
				<tbody>
				<?php
				$no = $this->input->get('per_page')+ 1;
				foreach($this->showDataDetail as $showData ){
					//var_dump($showData);
				?>
				<tr>

					<td align="center"><?php echo $no; ?>.</td>
					<td ><?php echo $showData->nama_hari; ?></td>
					<td ><?php echo $showData->nama_jam_kerja; ?></td>
					<td ><?php echo $showData->jam_masuk; ?> - <?php echo $showData->jam_pulang; ?></td>
					<td >
						<span class="btn btn-danger btn-xs" onclick="tampil_pesan_hapus('<?php echo $showData->nama_hari; ?>','<?=base_url()."".$this->uri->segment(1);?>/delete_detail/<?php echo $showData->id; ?>')"><i class="glyphicon glyphicon-remove"></i></span>
					</td>
				</tr>
				<?php
				$no++;
				}
				if(!$this->showDataDetail){
					echo "<tr><td colspan='25' align='center'>Data tidak ada.</td></tr>";
				}
				?>
				</tbody>
		</table>

	</div>
	</div>
</div>
</div>


<script>

$('#form_role_detail').validate({
	submitHandler: function(form) {
		var urlTujuan = $("#form_role_detail").attr('action');
		$.ajax({
			url: urlTujuan,
			type:'POST',
			dataType:'json',
			data: $('#form_role_detail').serialize(),
			beforeSend: function(){
				$('#loading_detail').show();
				$('#pesan_error_detail').hide();
			},
			success: function(data){
				if( data.status ){
					$('.page-footer').append('<div class="modal fade" id="container-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h4 class="modal-title" id="myModalLabel">Pesan Pemberitahuan</h4></div><div class="modal-body"><h4>Data berhasil disimpan.</h4></div><div class="modal-footer"><a href="'+data.redirect_link+'"> <button type="button" class="btn btn-primary">Ok</button></a></div></div></div></div>');
					$('#container-modal').modal('show');

				}
				else{
					$('#loading_detail').hide(); $('#pesan_error_detail').show(); $('#pesan_error_detail').html(data.pesan);
				}
			},
			error : function(data) {
				$('#pesan_error_detail').html('maaf telah terjadi kesalahan dalam program, silahkan anda mengakses halaman lainnya.'); $('#pesan_error_detail').show(); $('#loading_detail').hide();
				//$('#pesan_error').html( '<h3>Error Response : </h3><br>'+JSON.stringify( data ));
			}
		})
	}
});

</script>
