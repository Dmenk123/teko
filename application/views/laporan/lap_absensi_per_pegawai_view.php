

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
								for($i = 2019;$i <= $tahundepan;$i++){
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
						<input class="form-control" type="text" required id="pegawai_autocomplete" <?php if($this->input->get('id_instansi')=='')echo "readonly"; ?> placeholder="Silahkan Pilih Instansi" value="<?php if($this->input->get('id_instansi') != ''){ if($this->dataPegawai){echo $this->dataPegawai->nama;}} ?>" >
						<input class="form-control" required type="hidden" value="<?php echo $this->input->get('id_pegawai') ?>" id="id_pegawai"  name="id_pegawai"  >

						<?php
						if($this->input->get('id_pegawai') =='' && $this->input->get('id_instansi') != '')
						{
							if(!$this->dataPegawai)
							{
							?>	<br>
								Silahkan pilih Pegawai terlebih dahulu ...
							<?php
							}
						}
						?>
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
		if($this->input->get('id_pegawai')){
			if($this->dataPegawai){
		?>
			<script>
				window.open('<?=base_url();?>cetak_new/lap_absensi_per_pegawai/?bulan=<?php echo $this->input->get("bulan"); ?>&tahun=<?php echo $this->input->get("tahun"); ?>&id_instansi=<?php echo $this->input->get("id_instansi"); ?>&id_pegawai=<?php echo $this->input->get("id_pegawai"); ?>')
			</script>
		<?php
			}
		}
		?>
	</div>
</div>

<script>
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
				data	: 	{term:request.term	,	kode_instansi	:	$('#id_instansi').val(), bulan	:	$('#bulan').val(), tahun	:	$('#tahun').val() },
				success	: 	response,
				dataType: 	'json'
			});
		},
		select	: 	function (e, ui) {

			$("#id_pegawai").val(ui.item.id_pegawai);

		}
	}, {minLength: 3 });

	$('#dataTable').DataTable({
			"bPaginate": false,
			"showNEntries": false
	});
</script>
