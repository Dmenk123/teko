

<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">
			<form class="form-horizontal"  method="get">
				<div class="form-group">
					<label class="control-label col-sm-2" >Tgl. Mulai :</label>
					<div class="col-sm-2">
						<input type="text" class="form-control datePicker " data-date-format='dd/mm/yyyy'  required value="<?php echo $this->input->get('tgl_mulai'); ?>"  autocomplete="off" id="tgl_mulai"  name="tgl_mulai">
					</div>
					<label class="control-label col-sm-2" >Tgl. Selesai :</label>
					<div class="col-sm-2">
						<input type="text" class="form-control  datePicker " data-date-format='dd/mm/yyyy'  required value="<?php echo $this->input->get('tgl_selesai'); ?>" autocomplete="off" id="tgl_selesai"  name="tgl_selesai">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" >Instansi :</label>
					<div class="col-sm-6">
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
					<label class="control-label col-sm-2" >Status Pegawai :</label>
					<div class="col-sm-2">
						<select type="input" class="form-control select2" required id="pns"  name="pns">
							<option value="">Silahkan Pilih</option>
							<option <?php if($this->input->get('pns') == 'y') echo "selected";?> value="y">PNS</option>
							<option <?php if($this->input->get('pns') == 'n') echo "selected";?> value="n">NON PNS</option>
						</select>
					</div>

				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" ></label>
					<div class="col-sm-6">

						<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Tampilkan</button>
					</div>
				</div>

			</form>
		</div>
		<?php
		if($this->input->get('pns')){
		?>
			<script>
				window.open('<?=base_url();?>cetak_new/lap_rekap_instansi/?tgl_mulai=<?php echo $this->input->get("tgl_mulai"); ?>&tgl_selesai=<?php echo $this->input->get("tgl_selesai"); ?>&id_instansi=<?php echo $this->input->get("id_instansi"); ?>&pns=<?php echo $this->input->get("pns"); ?>')
			</script>
		<?php
		}
		?>
	</div>
</div>
<!-- /.content -->

<div id="modal_roster" class="modal fade  full-width" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Form Roster Pegawai</h4>
      </div>
      <div class="modal-body">
				<form class="form-horizontal" action="/action_page.php">
				  <div class="form-group">
				    <label class="control-label col-sm-2" for="email">Pegawai :</label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="nama_pegawai_form" name="NAMA_PEGAWAI">
				      <input type="hidden" class="form-control" id="id_pegawai_form" name="ID_PEGAWAI">
				    </div>
				  </div>
				  <div class="form-group">
				    <label class="control-label col-sm-2" for="pwd">Tanggal :</label>
				    <div class="col-sm-6">
				      <input type="text" class="form-control" id="tanggal_form" name="TANGGAL">
				    </div>
				  </div>
					<div class="form-group">
						<label class="control-label col-sm-2" >Roster :</label>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
								<table class="table table-bordered" id="dataTable">
				            <thead>
				              <tr>
				                <th>Kode</th>
				                <th>Nama</th>
				                <th>Label</th>
				                <th>Jam Kerja</th>
				                <th></th>

				              </tr>
				            </thead>
				            <tbody>
								<?php
								$no = $this->input->get('per_page')+ 1;
								foreach($this->dataRosterTable as $showData ){
									//var_dump($showData);
								?>
								<tr>

									<td ><?php echo $showData->kode; ?></td>
									<td ><?php echo $showData->nama_jenis_roster; ?></td>
									<td ><?php echo $showData->label; ?></td>
									<td ><?php echo $showData->nama_jam_kerja; ?></td>
									<td >
										<i id="iconPilih<?php echo $no; ?>"  data-toggle="tooltip" title="pilih" class="glyphicon glyphicon-edit" style="cursor:pointer;" onclick="save_roster('<?php echo $no; ?>','<?php echo $showData->id; ?>')" ></i>
									</td>

								</tr>
								<?php
								$no++;
								}
								?>
			            </tbody>
			        </table>
						</div>
					</div>

				</form>
      </div>
    </div>

  </div>
</div>

<script>
	/**function ganti_instansi(kodeInstansi){
		$('#id_pegawai').html('<option value="">Silahkan pilih</option>');
		$('#div_pesan_pegawai').html('Pencarian Data Pegawai ...');
		$.ajax({
			url: base_url+'roster_pegawai/show_data_option_pegawai',
			type:'POST',
			dataType:'html',
			data: {kode_instansi : kodeInstansi, bulan : $('#bulan').val(),  tahun : $('#tahun').val()},
			success: function(data){
				$('#id_pegawai').html(data);

				$('#div_pesan_pegawai').hide();
				$('#div_id_pegawai').slideDown('slow');
			}
		})
	}***/
	function ganti_instansi(kodeInstansi){
		$("#pegawai_autocomplete").removeAttr("readonly");
		$("#pegawai_autocomplete").focus();
		$("#pegawai_autocomplete").attr("placeholder", "Silahkan Ketik NIP atau Nama Pegawai");
	}

	$( "#pegawai_autocomplete" ).autocomplete({
		source: function (request, response) {
			$.ajax({
				type	: 	"POST",
				url		:	base_url+'search/pegawai/',
				data	: 	{term:request.term	,	kode_instansi	:	$('#id_instansi').val() },
				success	: 	response,
				dataType: 	'json'
			});
		},
		select	: 	function (e, ui) {

			$("#id_pegawai").val(ui.item.id_pegawai);

		}
	}, {minLength: 5 });


	function show_modal_form_roster(tanggal){
		$('#id_pegawai_form').val($('#id_pegawai').val());
		$('#tanggal_form').val(tanggal);
		$('#nama_pegawai_form').val($( "#id_pegawai option:selected" ).text());
		$('#modal_roster').modal('show');
	}

	$('#dataTable').DataTable({
			"bPaginate": false,
			"showNEntries": false
	});

    $('[data-toggle="tooltip"]').tooltip();

		function save_roster(no,id_rosterKu){
			$('#iconPilih'+no).html('<br>Proses..');
			$.ajax({
				url: base_url+'roster_pegawai/add_data',
				type:'POST',
				dataType:'html',
				data: {id_roster: id_rosterKu, id_roster: id_rosterKu, kode_instansi : $('#id_instansi').val(), bulan : $('#bulan').val(), tanggal : $('#tanggal_form').val(), id_pegawai : '<?php echo $this->input->get('id_pegawai'); ?>', tahun : $('#tahun').val()},
				success: function(data){
					location.reload();
				}
			})

		}
</script>
