<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">
			<?php if ($this->session->flashdata('feedback_success')) { ?>
            <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
            <?= $this->session->flashdata('feedback_success') ?>
            </div>
            <?php } elseif ($this->session->flashdata('feedback_failed')) { ?>
            <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-remove"></i> Maaf!</h4>
            <?= $this->session->flashdata('feedback_failed') ?>
            </div>
            <?php } ?>

			<form class="form-horizontal"  method="get">
				<div class="form-group">
					<label class="control-label col-sm-2" >Bulan :</label>
					<div class="col-sm-2">
						<select type="input" class="form-control select2" required id="bulan"  name="bulan">
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
						<button id="btnGenerate" onclick="proses_generate();" type="button" class="btn btn-success"><i class="fa fa-refresh"></i> Update</button>
						<?php } ?>
					</div>
				</div>

			</form>
		</div>
		<?php
		if($this->input->get('pns')){
		?>
			<script>
				window.open('<?=base_url();?>cetak_new/lap_skor2/?bulan=<?php echo $this->input->get("bulan"); ?>&tahun=<?php echo $this->input->get("tahun"); ?>&id_instansi=<?php echo $this->input->get("id_instansi"); ?>&pns=<?php echo $this->input->get("pns"); ?>&type=<?php echo $this->input->get("type"); ?>')
			</script>
		<?php
		}
		?>
	</div>
</div>
<!-- /.content -->
<!-- Modal -->
<div id="modalWarningTampilkan" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Peringatan!</h4>
      </div>
      <div class="modal-body">
        <p>Proses Generate laporan di Pengguna lainnya sedang diproses dan <strong>Belum Selesai</strong>.</p>
		<table border="1" width="100%">
			<tr>
				<td>Tanggal</td>
				<td><?= $this->session->flashdata('feedback_warning_tampilkan')['data_generate']['created_at'] ?></td>
			</tr>
			<tr>
				<td>Nama Pegawai</td>
				<td><?= $this->session->flashdata('feedback_warning_tampilkan')['data_generate']['fullname'] ?></td>
			</tr>
			<tr>
				<td>Proses Generate</td>
				<td><?= $this->session->flashdata('feedback_warning_tampilkan')['jml_tergenerate'] . ' dari ' . $this->session->flashdata('feedback_warning_tampilkan')['jml_pegawai'] ?></td>
			</tr>
		</table>
      </div>
      <div class="modal-footer">
		<?php
			$bulan_get = $this->session->flashdata('feedback_warning_tampilkan')['uri']['bulan'];
			$tahun_get = $this->session->flashdata('feedback_warning_tampilkan')['uri']['tahun'];
			$id_instansi_get = $this->session->flashdata('feedback_warning_tampilkan')['uri']['id_instansi'];
			$pns_get = $this->session->flashdata('feedback_warning_tampilkan')['uri']['pns'];
		?>
		<a href="<?= base_url('cetak_new/lap_rekap_instansi2?bulan='.$bulan_get.'&tahun='.$tahun_get.'&id_instansi='.$id_instansi_get.'&pns='.$pns_get.'&lanjut_cetak=1') ?>" class="btn btn-danger">Lanjutkan Cetak</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div>

  </div>
</div>

<div id="modalWarningUpdate" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Peringatan!</h4>
      </div>
      <div class="modal-body">
        <p>Proses Update laporan di Pengguna lainnya sedang diproses dan <strong>Belum Selesai</strong>.</p>
		<table border="1" width="100%">
			<tr>
				<td>Tanggal</td>
				<td><span class="tbl-tgl-upd"></span></td>
			</tr>
			<tr>
				<td>Nama Pegawai</td>
				<td><span class="tbl-namapeg-upd"></span></td>
			</tr>
			<tr>
				<td>Proses Update</td>
				<td><span class="tbl-proc-upd"></span></td>
			</tr>
		</table>
      </div>
      <div class="modal-footer">
		<a class="btn btn-danger anchor-stop-upd">Stop Proses</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div>

  </div>
</div>

<!-- progress bar modal -->
<div class="modal fade" id="pesan_modal" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Proses Update Laporan, Mohon menunggu.</h4>
			</div>
			<div class="modal-body">
				<span id="proses-data"></span>
				<div class="progress">
				 	<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
				    0%
				  	</div>
				</div>
				<p id="pesan_isi"></p>
			</div>
		</div>
	</div>
</div>

<!-- pesan isi 2 modal -->
<div class="modal fade" id="pesan_modal2" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
    <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Pesan Pemberitahuan</h4>
			</div>
			<div class="modal-body">
				<p id="pesan_isi2"></p>
			</div>
			<div class="modal-footer">
				<button type="button" id="pesan_modal-ok" class="btn btn-primary" data-dismiss="modal">OK</button>
			</div>
		</div>
	</div>
</div>

<!-- pesan isi 3 modal -->
<div class="modal fade" id="pesan_modal3" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
    <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Pesan Pemberitahuan</h4>
			</div>
			<div class="modal-body">
				<p id="pesan_isi3"></p>
			</div>
			<div class="modal-footer">
				<button type="button" id="pesan_modal3-ok" class="btn btn-primary" data-dismiss="modal">Tunggu</button>
				<button type="button" id="pesan_modal3-stop" class="btn btn-danger" data-dismiss="modal">Hentikan Proses Update yang sedang berjalan</button>
			</div>
		</div>
	</div>
</div>


<script>
	$(function () {
		<?php if ($this->session->flashdata('feedback_warning_tampilkan')) { ?>
		$('#modalWarningTampilkan').modal('show');
		<?php } ?>

		$('#pns').change(function(event) {
			if ($(this).val() != "") {
				$("#btnGenerate").prop('disabled', false);
			}else{
				$("#btnGenerate").prop('disabled', true);
			}
		});

		$("#pesan_modal").on("hidden.bs.modal", function(){
	      $('#pesan_isi').html('');
	    });

		/*$("#btnGenerate").click(function(){
			$(this).prop('disabled', true);
			$('#btnTampilkan').prop('disabled', true);

			bulan = $('#bulan').val();
			tahun = $('#tahun').val();
			id_instansi = $('#id_instansi').val();
			pns = $('#pns').val();

			location.href = "<?= base_url('cetak_new/lap_skor2/generate/') ?>?bulan=" + bulan + "&tahun=" + tahun + "&id_instansi=" + id_instansi + "&pns=" + pns;
		});*/
	});

	function proses_generate() {
		$("#btnGenerate").prop('disabled', true);
		$('#btnTampilkan').prop('disabled', true);

		var data = {
			bulan : $('#bulan').val(),
			tahun : $('#tahun').val(),
			id_instansi : $('#id_instansi').val(),
			pns_get : $('#pns').val()
		};

		//console.log(data);
		$('#pesan_modal').modal('show');
		$.ajax({
			url:  base_url + 'cetak_new/lap_skor/generate',
			type: 'get',
			dataType: 'json',
			data: data,
			success: function(data){
				if (data.status == 'gagal') {
					$('#pesan_modal').modal('hide');
					$('#pesan_isi2').html(data.pesan);
					$('#pesan_modal2').modal('show');
					$('#btnTampilkan').prop('disabled', false);
					$("#btnGenerate").prop('disabled', true);
				}else if(data.status == 'antri'){
					$('#pesan_modal').modal('hide');
					$('.tbl-tgl-upd').text(data.data_generate['created_at']);
					$('.tbl-namapeg-upd').text(data.data_generate['fullname']);
					$('.tbl-proc-upd').text(data.jml_tergenerate+' dari '+ data.jml_pegawai);
					$('.anchor-stop-upd').attr("href", base_url + "cetak_new/lap_skor2/stop?bulan="+$('#bulan').val()+"&tahun="+$('#tahun').val()+"&id_instansi="+$('#id_instansi').val()+"&pns="+$('#pns').val());
					$('#modalWarningUpdate').modal('show');
					$('#btnTampilkan').prop('disabled', false);
					$("#btnGenerate").prop('disabled', false);
				}else{
					var i = 0;
					var i_max = data.pesan.length;
					proses_generate_peg(i, i_max, data);
					console.log("maksimal = " + i_max, data);
				}
			}
		});
	}

	function proses_generate_peg(i, i_max, data_kirim) {
		//console.log(i);
		var persen = Math.round((i / i_max) * 100);
		$('.progress-bar').attr("aria-valuenow", persen).css("width", persen+'%').text(persen + '%');
		$('#proses-data').text('Proses ke : '+ i +' dari total '+ i_max +' data.');
		var data_pegawai = {
			id_pegawai 		: data_kirim.pesan[i].id_pegawai,
			nip 			: data_kirim.pesan[i].nip,
			nama 			: data_kirim.pesan[i].nama,
			nama_unor 		: data_kirim.pesan[i].nama_unor,
			jabatan 		: data_kirim.pesan[i].nama_jabatan,
			rumpun_jabatan 	: data_kirim.pesan[i].nama_rumpun_jabatan,
			golongan 		: data_kirim.pesan[i].nama_golongan,
			meninggal     	: data_kirim.pesan[i].meninggal,
			urut     		: data_kirim.pesan[i].urut,
			tgl_mulai_peg 	: data_kirim.tgl_mulai,
			tgl_akhir_peg 	: data_kirim.tgl_selesai,
			bulan			: data_kirim.bulan,
			tahun			: data_kirim.tahun,
			id_instansi_peg : data_kirim.kd_instansi,
			pns 			: data_kirim.pns,
			urut2 			: i
		};
		console.log(data_pegawai);
		$.ajax({
			url:  base_url + 'cetak_new/lap_skor/proses_generate_perpegawai',
			type: 'POST',
			dataType: 'json',
			data: data_pegawai,
			success: function(data){
				if (data.status == 'gagal') {
					$('#pesan_isi').html(isi);
				}
				else
				{
					i = i + 1;
					console.log(i);
					if(i < i_max)
					{
						proses_generate_peg(i, i_max, data_kirim);
					}
					else
					{
						$.ajax({
							url:  base_url + 'cetak_new/lap_skor/update_selesai_gen_laporan',
							type: 'POST',
							dataType: 'json',
							data: {
								bulan_update		: data_pegawai.bulan,
								tahun_update 		: data_pegawai.tahun,
								id_instansi_update 	: data_pegawai.id_instansi_peg,
								pns_update			: data_pegawai.pns,
								data_pegawai_update : data_kirim.pesan
							},
							success: function(data){
								if (data.status = 'sukses') {
									$('#pesan_isi').html(data.pesan);
									$('#pesan_modal').modal('hide');
									$.alert({
										theme: 'modern',
										closeIcon: true,
										animation: 'scale',
										type: 'green',
										title: 'SUKSES',
										content: 'Data Laporan Skor sukses di update !',
										buttons: {
									        somethingElse: {
									            text: 'Ok',
									            btnClass: 'btn-blue',
									            keys: ['enter', 'shift'],
									            action: function(){
									                //location.reload();
									                $("#btnGenerate").prop('disabled', false);
													$('#btnTampilkan').prop('disabled', false);
									                window.open(base_url + 'cetak_new/lap_skor2?bulan='+data_pegawai.bulan+'&tahun='+data_pegawai.tahun+'&id_instansi='+data_pegawai.id_instansi_peg+'&pns='+data_pegawai.pns+'');
									            }
									        }
									    }
									});
								}else{
									$('#pesan_isi').html('Terjadi Kesalahan update data laporan');
									$('#pesan_modal').modal('hide');
									$.alert({
										theme: 'modern',
										closeIcon: true,
										animation: 'scale',
										type: 'red',
										title: 'GAGAL',
										content: 'Terjadi Kesalahan update laporan, mohon hubungi Admin Aplikasi',
										buttons: {
									        somethingElse: {
									            text: 'Ok',
									            btnClass: 'btn-blue',
									            keys: ['enter', 'shift'],
									            action: function(){
									                //location.reload();
									                $("#btnGenerate").prop('disabled', false);
													$('#btnTampilkan').prop('disabled', false);
									            }
									        }
									    }
									});
								}
							}
						});
					}
				}
			}
		});
	}

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

	$('#dataTable').DataTable({
			"bPaginate": false,
			"showNEntries": false
	});

    $('[data-toggle="tooltip"]').tooltip();
</script>
