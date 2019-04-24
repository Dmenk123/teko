
	<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">
			<form class="form-horizontal" id=""  method="get">
				<div class="col-md-12">
					<div class="col-md-6">
						<div class="form-group row">
							<div class="col-md-4">
								<label class="control-label" >Tanggal Awal</label>
							</div>
							<div class="col-md-8">
								<input type="text" value="<?=$this->input->get('tgl_mulai');?>" class="form-control" required id="tgl_mulai" name="tgl_mulai"  autocomplete="off">
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group row">
							<div class="col-md-4">
								<label class="control-label" >Tanggal Akhir</label>
							</div>
							<div class="col-md-8">
								<input type="text" value="<?=$this->input->get('tgl_akhir');?>" class="form-control" required id="tgl_akhir" name="tgl_akhir"  autocomplete="off">
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="col-md-12">
						<div class="form-group row">
							<div class="col-md-2">
								<label class="control-label" >Pilih Mesin</label>
							</div>
							<?php
								$get_id_instansi = $this->input->get('id_instansi') ? explode(';', $this->input->get('id_instansi')) : null;
							?>
							<div class="col-md-10">
								<select class="form-control select2" required id="id_instansi" name="id_instansi">
									<option value="">-- Silahkan Pilih --</option>
									<?php foreach ($mesin as $key => $value) { ?>
									<option value="<?= $value->kode_instansi . ';' . $value->id ?>"
									<?php if($get_id_instansi and $get_id_instansi[1] == $value->id) echo "selected";?>>
										<?= $value->nama ?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" name="id_mesin" id="id_mesin">
				<div class="col-md-12">
					<div class="col-md-12">
						<div class="form-group row">
							<div class="col-md-2">
								<label class="control-label" >Pegawai</label>
							</div>
							<div class="col-md-10">
								<select class="form-control select2" id="id_pegawai" name="id_pegawai">
									<option value="">-- Silahkan Pilih --</option>
								</select>
								<span><strong>catatan : Mohon kosongi pilihan ini apabila ingin menampilkan semua.</strong></span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12" style="text-align:right">
					<div class="col-md-12">
						<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tampilkan</button>
					</div>
				</div>
			</form>

		</div>
		<hr>

		<?php if($this->input->get('tgl_mulai') and $this->input->get('tgl_akhir') and $this->input->get('id_mesin')) { ?>
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>No.</th>
					<th>Tanggal</th>
					<th>ID Finger</th>
					<th>Nama Pegawai</th>
					<th>Unit Organisasi Kerja</th>
					<th>Unit Kerja</th>
					<th>Keterangan</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($log_mesin as $key => $value) { ?>
				<tr>
					<td><?= ($key+1) ?></td>
					<td><?= date('d-m-Y H:i:s', strtotime($value['tanggal'])) ?></td>
					<td><?= $value['badgenumber'] ?></td>
					<td><?= $value['nama_pegawai'] ?></td>
					<td><?= $value['unor_header'] ?></td>
					<td><?= $value['nama_unor'] ?></td>
					<td></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>

	</div>
</div>

<script src="<?=base_url();?>assets/global/plugins/datatables/media/js/moment.min.js" type="text/javascript"></script>
<script src="<?=base_url();?>assets/global/plugins/datatables/media/js/time.js" type="text/javascript"></script>
<script>
	$(function () {
		$.fn.dataTable.moment('DD-MM-YYYY');

		$('.table').DataTable({
			paging       : true,
			lengthChange : true,
			searching    : true,
			ordering     : true,
			aaSorting    : [],
			columnDefs: [
				{ orderable: false, targets: -1 }
			],
		})

		// $('#id_instansi').change(function () {
		// 	value = $(this).val().split(';');

		// 	$('#id_pegawai').html('');
		// 	$('#id_mesin').val(value[1]);

		// 	$.ajax({
		// 		url: "<?= base_url('log_absen_per_mesin/get_pegawai_by_instansi') ?>" + '/' + value[0],
		// 		data: {tgl_mulai: $("#tgl_mulai").val(), tgl_akhir: $("#tgl_akhir").val()},
		// 		type:'POST'
		// 	}).done(function(response) {
		// 		$('#id_pegawai').html(response);

		// 		$('#id_pegawai').val('<?= $this->input->get('id_pegawai') ?>').trigger('change.select2');
		// 	});
		// })

		$('#id_instansi').change(function () {
			value = $(this).val().split(';');
			$('#id_pegawai').html('');
			$('#id_mesin').val(value[1]);
			console.log(value[1]);
			$.ajax({
				url: "<?= base_url('log_absen_per_mesin/get_pegawai_by_mesin') ?>" + '/' + value[1],
				type: "text"
			}).done(function(response) {
				$('#id_pegawai').html(response);

				$('#id_pegawai').val('<?= $this->input->get('id_pegawai') ?>').trigger('change.select2');
			});
		})

		$("#id_instansi").trigger( "change" );

		$('#tgl_mulai, #tgl_akhir').datepicker({
			autoclose: true,
			todayHighlight: true,
			format:'dd-mm-yyyy'
		});

		
	})
	// function cekPegawai() {
	// 	value = $("#id_instansi").val().split(';');

	// 	$('#id_pegawai').html('');
	// 	$('#id_mesin').val(value[1]);

	// 	$.ajax({
	// 		url: "<?= base_url('log_absen_per_mesin/get_pegawai_by_instansi') ?>" + '/' + value[0],
	// 		data: {tgl_mulai: $("#tgl_mulai").val(), tgl_akhir: $("#tgl_akhir").val()},
	// 		type:'POST'
	// 	}).done(function(response) {
	// 		$('#id_pegawai').html(response);

	// 		$('#id_pegawai').val('<?= $this->input->get('id_pegawai') ?>').trigger('change.select2');
	// 	});
	// }
	
</script>
