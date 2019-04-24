
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
						<input type="text" value="<?=$this->input->get('tgl_mulai');?>" data-date-format='dd/mm/yyyy'  autocomplete="off" class="form-control datePicker " required id="tgl_mulai"  name="tgl_mulai">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Tgl. Selesai :</label>
					<div class="col-sm-3">
						<input type="text" value="<?=$this->input->get('tgl_selesai');?>"  data-date-format='dd/mm/yyyy'  autocomplete="off" class="form-control  datePicker " required  id="tgl_selesai"  name="tgl_selesai">
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
		if($this->input->get('tgl_mulai') && $this->input->get('tgl_selesai')){
		?>

		<table class="table table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th>OPD</th>
					<th>Last Update</th>
					<th>Running By</th>
					<th>Start at</th>
					<th>Finish</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>

			<?php
			$no = 0;
			foreach($this->dataSch as $data){
			$no++;
			?>
				<tr>
					<td><?php  echo $no++; ?></td>
					<td><?php  echo $data->nama_upd; ?></td>
					<td><?php  echo $data->date; ?></td>
					<td><?php  echo $data->running_by; ?></td>
					<td><?php  echo $data->start_at; ?></td>
					<td><?php  echo $data->finish_at; ?></td>
					<td>
						<button type="button" 
								class="btn btn-primary btn-sm" 
								onclick="tarik_data('<?php echo $data->id_upd; ?>')"> 
							Tarik
						</button>
					</td>
				</tr>

			<?php
			}
			?>

			</tbody>
		</table>

		<?php
		}
		?>

	</div>
</div>





<script>

function tarik_data(idTarik){
	$.ajax({
    		url:  base_url + 'cetak_new/lap_absensi_lembur/MigrasiPerbagian_InsertManual',
    		type: 'POST',
    		dataType: 'json',
    		data: {idTarik: idTarik},
    		success: function(data){
    			if (data.status == 'sukses') {
    				alert('Data berhasil ditarik');
    				location.reload();	
    			}
				
			}
    	});
}

function modal_status(id_t_ijin){
	$('#modal_status').modal('show');
	$('#id_t_ijin').val(id_t_ijin);


}

function simpan_status(){
	$.ajax({
			url: base_url+'daftar_lembur_pegawai/ubah_status',
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

</script>
