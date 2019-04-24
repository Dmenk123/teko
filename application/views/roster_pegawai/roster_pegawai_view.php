

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
					<label class="control-label col-sm-2" >Bulan :</label>
					<div class="col-sm-2">
						<select type="input" class="form-control select2" required id="bulan"  name="bulan">
							<option value="">Silahkan Pilih</option>
							<option <?php if($this->input->get('bulan') == '01') echo "selected";?> value="01">JANUARI</option>
							<option <?php if($this->input->get('bulan') == '02') echo "selected";?> value="02">FEBRUARI</option>
							<option <?php if($this->input->get('bulan') == '03') echo "selected";?> value="03">MARET</option>
							<option <?php if($this->input->get('bulan') == '04') echo "selected";?> value="04">APRIL</option>
							<option <?php if($this->input->get('bulan') == '05') echo "selected";?> value="05">MEI</option>
							<option <?php if($this->input->get('bulan') == '06') echo "selected";?> value="06">JUNI</option>
							<option <?php if($this->input->get('bulan') == '07') echo "selected";?> value="07">JULI</option>
							<option <?php if($this->input->get('bulan') == '08') echo "selected";?> value="08">AGUSTUS</option>
							<option <?php if($this->input->get('bulan') == '09') echo "selected";?> value="09">SEPTEMBER</option>
							<option <?php if($this->input->get('bulan') == '10') echo "selected";?> value="10">OKTOBER</option>
							<option <?php if($this->input->get('bulan') == '11') echo "selected";?> value="11">NOVEMBER</option>
							<option <?php if($this->input->get('bulan') == '12') echo "selected";?> value="12">DESEMBER</option>
						</select>
					</div>
					<label class="control-label col-sm-2" >Tahun :</label>
					<div class="col-sm-2">
						<select type="input" class="form-control select2" required id="tahun"  name="tahun">
							<option value="">Silahkan Pilih</option>
								<?php
								$tahundepan = date('Y') + 1;
								for($i = 2018;$i <= $tahundepan;$i++){
								?>
							<option <?php if($this->input->get('tahun') == $i) echo "selected";?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
								<?php
								}
								?>
						</select>
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
					<label class="control-label col-sm-2" >Nama Pegawai :</label>

					<div class="col-sm-6" style="display:nosnes" id="div_pesan_pegawai">

						<input class="form-control" type="text" id="pegawai_autocomplete" <?php if($this->input->get('id_instansi')=='')echo "readonly"; ?> placeholder="<?php if($this->input->get('id_instansi') == ''){ echo "silahkan pilih Instansi";}else{if($this->input->get('id_pegawai') == '') echo "Silahkan Pilih Pegawai";} ?>" value="<?php if($this->input->get('id_pegawai') != ''){echo $this->dataPegawai->nama;} ?>" >



						<input class="form-control" type="hidden" value="<?php echo $this->input->get('id_pegawai') ?>" id="id_pegawai"  name="id_pegawai"  >

					</div>
				<!--	<div class="col-sm-6" style="display:<?php if($this->input->get('id_instansi') == '') echo "none";?>" id="div_id_pegawai">
						<select type="input"  class="form-control  select2" required id="id_pegawai" >

							<?php
							foreach($this->dataPegawai as $data){
							?>
							 <option <?php if($this->input->get('id_pegawai') == $data->id) echo "selected";?> value="<?php echo $data->id; ?>"><?php echo $data->nama." ( ".$data->nip." )"; ?></option>
							<?php
							}
						 	?>
						</select>
					</div>-->

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
		if($this->input->get('id_instansi')){
		?>
		<hr>
		<div class="row">
			<div class="col-sm-12">
				<div style="overflow: auto; width:100%;">
				<?php echo $this->dataRoster; ?>
				</div>
			</div>
		</div>
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
				data	: 	{term:request.term	,	kode_instansi	:	$('#id_instansi').val(), bulan : $('#bulan').val(), tahun : $('#tahun').val()},
				success	: 	response,
				dataType: 	'json'
			});
		},
		select	: 	function (e, ui) {

			$("#id_pegawai").val(ui.item.id_pegawai);

		}
	}, {minLength: 3 });


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
