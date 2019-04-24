

<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">
			<?php if ($this->session->flashdata('feedback_success')) { ?>
            <div class="alert alert-success alert-dismissible" style="margin-left: 30px;margin-right: 30px;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
            <?= $this->session->flashdata('feedback_success') ?>
            </div>
            <?php } elseif ($this->session->flashdata('feedback_failed')) { ?>
            <div class="alert alert-danger alert-dismissible" style="margin-left: 30px;margin-right: 30px;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-remove"></i> Maaf!</h4>
            <?= $this->session->flashdata('feedback_failed') ?>
            </div>
            <?php } ?>

			<form class="form-horizontal"  method="get">
				<div class="form-group">
					<label class="control-label col-sm-2" >Bulan :</label>
					<div class="col-sm-2">
					<?php if($this->session->userdata('id_kategori_karyawan') != '12'){ ?>
						<select type="input" class="form-control select2" required id="bulan" name="bulan" <?php if($this->session->userdata('id_kategori_karyawan') == '12') { ?>onChange="getInstansi()"<?php } ?>>
							<option value="">Silahkan Pilih</option>
							<option <?php if($this->input->get('bulan') == '01' || date('m') == '01') echo "selected";?> value="01">JANUARI</option>
							<option <?php if($this->input->get('bulan') == '02' || date('m') == '02') echo "selected";?> value="02">FEBRUARI</option>
							<option <?php if($this->input->get('bulan') == '03' || date('m') == '03') echo "selected";?> value="03">MARET</option>
							<option <?php if($this->input->get('bulan') == '04' || date('m') == '04') echo "selected";?> value="04">APRIL</option>
							<option <?php if($this->input->get('bulan') == '05' || date('m') == '05') echo "selected";?> value="05">MEI</option>
							<option <?php if($this->input->get('bulan') == '06' || date('m') == '06') echo "selected";?> value="06">JUNI</option>
							<option <?php if($this->input->get('bulan') == '07' || date('m') == '07') echo "selected";?> value="07">JULI</option>
							<option <?php if($this->input->get('bulan') == '08' || date('m') == '08') echo "selected";?> value="08">AGUSTUS</option>
							<option <?php if($this->input->get('bulan') == '09' || date('m') == '09') echo "selected";?> value="09">SEPTEMBER</option>
							<option <?php if($this->input->get('bulan') == '10' || date('m') == '10') echo "selected";?> value="10">OKTOBER</option>
							<option <?php if($this->input->get('bulan') == '11' || date('m') == '11') echo "selected";?> value="11">NOVEMBER</option>
							<option <?php if($this->input->get('bulan') == '12' || date('m') == '12') echo "selected";?> value="12">DESEMBER</option>
						</select>
					<?php }else{ ?>
						<select type="input" class="form-control select2" required id="bulan" name="bulan" <?php if($this->session->userdata('id_kategori_karyawan') == '12') { ?>onChange="getInstansi()"<?php } ?>>
							<option value="">Silahkan Pilih</option>
							<option value="01">JANUARI</option>
							<option value="02">FEBRUARI</option>
							<option value="03">MARET</option>
							<option value="04">APRIL</option>
							<option value="05">MEI</option>
							<option value="06">JUNI</option>
							<option value="07">JULI</option>
							<option value="08">AGUSTUS</option>
							<option value="09">SEPTEMBER</option>
							<option value="10">OKTOBER</option>
							<option value="11">NOVEMBER</option>
							<option value="12">DESEMBER</option>
						</select>
					<?php } ?>
					</div>
					<label class="control-label col-sm-2" >Tahun :</label>
					<div class="col-sm-2">
						<select type="input" class="form-control select2" required id="tahun"  name="tahun">
							<option value="">Silahkan Pilih</option>
							<?php
								$tahundepan = date('Y') + 1;
								for($i = 2019;$i <= $tahundepan;$i++){
							?>
								<option <?php if($this->input->get('tahun') == $i || date('Y') == $i) echo "selected";?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>
				<?php if($this->session->userdata('id_kategori_karyawan') <> '12') { ?>
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
						<input class="form-control" required type="hidden" value="<?php echo $this->input->get('id_pegawai') ?>" id="id_pegawai"  name="id_pegawai">

						<?php
						if($this->input->get('id_pegawai')=='' && $this->input->get('id_instansi')!=''){
							if(!$this->dataPegawai){
							?>	<br>
								Silahkan pilih Pegawai terlebih dahulu ...
							<?php
							}
						}
						?>
					</div>
				<!--	<div class="col-sm-6" style="display:<?php if($this->input->get('id_instansi') == '') echo "none";?>" id="div_id_pegawai">
					<select type="input"  class="form-control  select2" required id="id_pegawai" >

						<?php
						//foreach($this->dataPegawai as $data){
						?>
						 <option <?php if($this->input->get('id_pegawai') == $data->id) echo "selected";?> value="<?php echo $data->id; ?>"><?php echo $data->nama." ( ".$data->nip." )"; ?></option>
						<?php
						//}
					 	?>
					</select>
				</div>-->

				</div>
				<?php } else { ?>
				<input class="form-control" required type="hidden" value="" id="id_instansi"  name="id_instansi"  >
				<input class="form-control" required type="hidden" value="<?php echo $this->session->userdata('id_karyawan') ?>" id="id_pegawai"  name="id_pegawai"  >
				<?php } ?>
				<div class="form-group">
					<label class="control-label col-sm-2" ></label>
					<div class="col-sm-6">
						<!-- <button type="button" class="btn btn-success tarik"><i class="fa fa-refresh"></i> Update</button> -->
						<button id="btnTampilkan" type="submit" name="type" value="html" class="btn btn-primary">Tampilkan</button>
						<div class="btn-group">
								<button type="button" class="btn btn-default"><i class="fa fa-file"></i> Export Ke</button>
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-angle-down"></i></button>
								<ul class="dropdown-menu" role="menu">
										<li>
												<button id="btnTampilkan" type="submit" class="btn btn-danger btn-block" name="type" value="pdf">Pdf</button>
										</li>
										<li>
												<button id="btnTampilkan" type="submit" class="btn btn-success btn-block" name="type" value="xls">Excel</button>
										</li>
								</ul>
						</div>
						<?php if($this->session->userdata('kategori_karyawan') != 'BPK') { ?>
						<button id="btnGenerate" type="button" class="btn btn-success" <?php if($this->session->userdata('id_kategori_karyawan') == '12') { ?>style="display:none;"<?php } ?>><i class="fa fa-refresh"></i> Update</button>
						<?php } ?>
					</div>
				</div>

			</form>
		</div>
		<?php
		if($this->input->get('id_pegawai')){
			if($this->dataPegawai){
		?>
			<script>
				window.open('<?=base_url();?>cetak_new/lap_per_pegawai2/?bulan=<?php echo $this->input->get("bulan"); ?>&tahun=<?php echo $this->input->get("tahun"); ?>&id_instansi=<?php echo $this->input->get("id_instansi"); ?>&id_pegawai=<?php echo $this->input->get("id_pegawai"); ?>&type=<?php echo $this->input->get("type"); ?>')
			</script>
		<?php
			}
		}
		?>
		<div class="modal fade" id="pesan_modal" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
		    <div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Pesan Pemberitahuan</h4>
					</div>
					<div class="modal-body">
						<p id="pesan_isi"></p>
					</div>
					<div class="modal-footer">
						<button type="button" id="pesan_modal-ok" class="btn btn-primary" data-dismiss="modal">OK</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /.content -->
<script>
	$(function () {
		$("#btnGenerate").click(function(){
			// $(this).prop('disabled', true);
			// $('#btnTampilkan').prop('disabled', true);

			bulan = $('#bulan').val();
			tahun = $('#tahun').val();
			id_pegawai = $('#id_pegawai').val();
			pns = $('#pns').val();
			id_instansi = $('#id_instansi').val();

			var link = "<?= base_url('cetak_new/lap_per_pegawai2/generate/') ?>?bulan=" + bulan + "&tahun=" + tahun + "&id_pegawai=" + id_pegawai + "&id_instansi=" + id_instansi;
			window.open(link,'_blank');
		});
	})

	function ganti_instansi(kodeInstansi){
		$("#pegawai_autocomplete").removeAttr("readonly");
		$("#pegawai_autocomplete").focus();
		$("#pegawai_autocomplete").attr("placeholder", "Silahkan Ketik NIP atau Nama Pegawai");
	}

	<?php if($this->session->userdata('id_kategori_karyawan') == '12') { ?>
	function getInstansi(){
		if($("#bulan").val() == "" || $("#tahun").val() == "") {
			$("#btnGenerate").hide();
		}
		else {
			$.ajax({
				url: base_url+'master_pegawai/getInstansi',
				type:'POST',
				dataType:'json',
				data: {
					id_pegawai: $('#id_pegawai').val(),
					tanggal: $('#tahun').val() + "-" + $('#bulan').val() + "-01"
				},
				success: function(data){
					if(data.status){
						$('#id_instansi').val(data.unor.kode_instansi);
						$("#btnGenerate").show();
					}
					else {
						$('#pesan_isi').html(data.pesan);
						$('#pesan_modal').modal('show');
						$("#btnGenerate").hide();
					}
				}
			});
		}
	}
	<?php } ?>

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
			$('#')
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
