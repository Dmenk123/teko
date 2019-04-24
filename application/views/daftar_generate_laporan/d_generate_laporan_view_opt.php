	<div class="spinner-loader hidden-msg Fixed">
		<div class="img-pos" style="padding-top: 20%;">
			<img src="<?= base_url();?>assets/img/loading2.gif" style="display:block; margin-left: auto; margin-right: auto;">
		</div>
	</div>

	<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<div class="panel panel-primary">
	      	<div class="panel-heading">
	      		<strong>Perhatian</strong>
	      	</div>
	      	<div class="panel-body">
	      		<p class="text-danger font-weight-bold">
	      			<span>Proses Generate Sudah Di Jalankan Otomatis By Sistem, Tidak Perlu melakukan proses generate Data lagi <br> Mohon Di pastikan bahwa Tanggal Start at dan Update at sudah sesuai, bila belum baru dapat dilakukan proses generate sesuai kebutuhan.</span>
	      		</p>
	      	</div>
	    </div>
		<div class="row">
			<div class="col-sm-2"></div>
			<div class="col-sm-2"></div>
			<div class="col-sm-8">
				<div class="row">
					<form method="get">
						<div class="col-sm-4 col-md-offset-2">
							<select class="form-control" name="field">
								<option <?php if($this->input->get('field')=='nama') echo "selected"; ?> value="nama">Berdasarkan Nama</option>
								<option <?php if($this->input->get('field')=='running_by') echo "selected"; ?> value="running_by">Berdasarkan Type</option>
							</select>
						</div>
						<div class="col-sm-6">
							<div class="input-group">
								<input type="text" class="form-control" name="keyword" placeholder="Masukkan Kata Kunci" value="<?php echo $this->input->get('keyword'); ?>">
								<div class="input-group-btn">
									<button class="btn btn-default" type="submit">
									<i class="glyphicon glyphicon-search"></i>
									</button>
									<?php if($this->input->get('field')){ ?>
									<a href="<?=base_url();?><?php echo $this->uri->segment(1);?>">
										<span class="btn btn-success"><i class="glyphicon glyphicon-refresh"></i></span>
									</a>
									<?php } ?>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<br>
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
			$no = $this->input->get('per_page')+ 1;
			foreach($this->dataSch as $data){

			?>
				<tr>
					<td><?php  echo $no; ?></td>
					<td><?php  echo $data->nama; ?></td>
					<td><?php  echo $data->lu; ?></td>
					<td><?php  echo $data->rb; ?></td>
					<td><?php  echo $data->sa; ?></td>
					<td><?php  echo $data->fa; ?></td>
					<td style="width:10%;">
						<!-- <button type="button"
								class="btn btn-primary btn-sm"
								onclick="tarik_data('<?php echo $data->id_upd; ?>')">
							Aksi
						</button> -->
						<select class="form-control" name="opsi_gen" onchange="opsiGen(this,'<?php echo $data->kode; ?>','<?php echo $data->nama; ?>')">
							<option value="">-- Opsi --</option>
							<option value="nama">By : Pegawai</option>
							<option value="ins_pns">By : PNS</option>
							<option value="ins_os">By : OS</option>
							<option value="instansi">By : Instansi</option>
						</select>
					</td>
				</tr>

			<?php
			$no++;
			}
			?>

			</tbody>
		</table>
		<center>
			<?php if ($this->session->userdata('id_kategori_karyawan') == '1') { ?>
				<?php echo $this->pagination->create_links();?>
				<br>
				<span class="btn btn-default">Jumlah Data : <b><?php echo $this->jumlahData;?></b></span>
			<?php } ?>
		</center>
	</div>
</div>


<div class="modal fade" id="modal_generate_instansi" tabindex="-1" role="dialog" aria-labelledby="modalGenerateInsLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Generate Laporan</h4>
            </div>
            <div class="modal-body">
                <form id="form_generate_ins" name="formGenerateIns">
                    <div class="panel-body">
                        <div class="form-horizontal">

                            <div class="form-group">
								<label class="control-label col-sm-3" >Tanggal Mulai :</label>
                                <div class="col-sm-9">
                                    <input type="hidden" class="form-control" id="id_instansi" name="id_instansi">
                                    <input type="input" class="form-control datePickerMaxToday required" data-date-format='dd/mm/yyyy'  id="tgl_mulai"  name="tgl_mulai" autocomplete="off">
                                </div>
                            </div>

							<?php if ((int)$this->session->userdata('id_kategori_karyawan') <= 2) { ?>
							<div class="form-group">
								<label class="control-label col-sm-3" >Tanggal Akhir :</label>
								<div class="col-sm-9">
									<input type="input" class="form-control datePickerLoss required" data-date-format='dd/mm/yyyy'  id="tgl_akhir" name="tgl_akhir" autocomplete="off">
								</div>
							</div>
							<?php }else{ ?>
							<div class="form-group">
								<label class="control-label col-sm-3" >Tanggal Akhir :</label>
								<div class="col-sm-9">
									<input type="input" class="form-control datePickerMaxToday required" data-date-format='dd/mm/yyyy'  id="tgl_akhir" name="tgl_akhir" autocomplete="off">
								</div>
							</div>
							<?php } ?>

                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                	<div class="col-md-12">
                		<span style="float:left"><strong>Mohon Pilih Maksimal 7 hari periode tanggal, agar proses tidak lama.</strong></span>
                	</div>

                	<div class="col-md-12" style="margin-top: 20px;">
                		<button type="button" class="btn btn-primary" onClick="proses_generate()">Generate</button>
                    	<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_generate_pegawai" tabindex="-1" role="dialog" aria-labelledby="modalGeneratePegLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Generate Laporan per Pegawai</h4>
            </div>
            <div class="modal-body">
                <form id="form_generate_peg" name="formGeneratePeg">
                    <div class="panel-body">
						<div class="form-horizontal">
						
                            <div class="form-group">
									<label class="control-label col-sm-3" >Instansi :</label>
                                <div class="col-sm-9">
                                    <input type="hidden" class="form-control required" id="id_instansi_peg"  name="id_instansi" readonly>
									<input type="text" class="form-control required" id="nama_instansi_peg"  name="nama_instansi" readonly>
                                </div>
                            </div>

                            <div class="form-group">
									<label class="control-label col-sm-3" >Tanggal Mulai :</label>
                                <div class="col-sm-9">
                                    <input type="input" class="form-control datePickerMaxToday required" data-date-format='dd/mm/yyyy'  id="tgl_mulai_peg"  name="tgl_mulai_peg" autocomplete="off">
                                </div>
                            </div>
							
							<?php if ((int)$this->session->userdata('id_kategori_karyawan') <= 2) { ?>
							<div class="form-group">
								<label class="control-label col-sm-3" >Tanggal Akhir :</label>
                                <div class="col-sm-9">
                                    <input type="input" class="form-control datePickerLoss required" data-date-format='dd/mm/yyyy'  id="tgl_akhir_peg"  name="tgl_akhir_peg" onchange="enable_nama(this.value)" autocomplete="off">
                                </div>
                            </div>
							<?php }else{ ?>
							<div class="form-group">
								<label class="control-label col-sm-3" >Tanggal Akhir :</label>
                                <div class="col-sm-9">
                                    <input type="input" class="form-control datePickerMaxToday required" data-date-format='dd/mm/yyyy'  id="tgl_akhir_peg"  name="tgl_akhir_peg" onchange="enable_nama(this.value)" autocomplete="off">
                                </div>
                            </div>
							<?php } ?>

							<div class="form-group">
									<label class="control-label col-sm-3" >Nama :</label>
                                <div class="col-sm-9">
									<input class="form-control" type="text" required id="pegawai_autocomplete" name="peg_gen" readonly>
									<input class="form-control" type="hidden" required id="id_pegawai" name="id_pegawai">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                	<div class="col-md-12">
                		<span style="float:left"><strong>Mohon Pilih Maksimal 7 hari periode tanggal, agar proses tidak lama.</strong></span>
                	</div>

                	<div class="col-md-12" style="margin-top: 20px;">
                		<button type="button" class="btn btn-primary" onClick="proses_generate_single()">Generate</button>
                    	<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_generate_pns" tabindex="-1" role="dialog" aria-labelledby="modalGeneratePns" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Generate Laporan By : Instansi (PNS)</h4>
            </div>
            <div class="modal-body">
                <form id="form_generate_pns" name="formGeneratePns">
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
									<label class="control-label col-sm-3" >Tanggal Mulai :</label>
                                <div class="col-sm-9">
                                    <input type="hidden" class="form-control" id="id_instansi_pns" name="id_instansi">
                                    <input type="input" class="form-control datePickerMaxToday required" data-date-format='dd/mm/yyyy'  id="tgl_mulai_pns"  name="tgl_mulai" autocomplete="off">
                                </div>
                            </div>
							
							<?php if ((int)$this->session->userdata('id_kategori_karyawan') <= 2) { ?>
                            <div class="form-group">
									<label class="control-label col-sm-3" >Tanggal Akhir :</label>
                                <div class="col-sm-9">
                                    <input type="input" class="form-control datePickerLoss required" data-date-format='dd/mm/yyyy'  id="tgl_akhir_pns"  name="tgl_akhir" autocomplete="off">
                                </div>
                            </div>
							<?php }else{ ?>
							<div class="form-group">
									<label class="control-label col-sm-3" >Tanggal Akhir :</label>
                                <div class="col-sm-9">
                                    <input type="input" class="form-control datePickerMaxToday required" data-date-format='dd/mm/yyyy'  id="tgl_akhir_pns"  name="tgl_akhir" autocomplete="off">
                                </div>
                            </div>
							<?php } ?>

                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                	<div class="col-md-12">
                		<span style="float:left"><strong>Mohon Pilih Maksimal 7 hari periode tanggal, agar proses tidak lama.</strong></span>
                	</div>

                	<div class="col-md-12" style="margin-top: 20px;">
                		<button type="button" class="btn btn-primary" onClick="proses_generate_pns()">Generate</button>
                    	<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_generate_os" tabindex="-1" role="dialog" aria-labelledby="modalGenerateOs" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Generate Laporan By : Instansi (OS)</h4>
            </div>
            <div class="modal-body">
                <form id="form_generate_os" name="formGenerateOs">
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
								<label class="control-label col-sm-3" >Tanggal Mulai :</label>
                                <div class="col-sm-9">
                                    <input type="hidden" class="form-control" id="id_instansi_os" name="id_instansi">
                                    <input type="input" class="form-control datePickerMaxToday required" data-date-format='dd/mm/yyyy'  id="tgl_mulai_os"  name="tgl_mulai" autocomplete="off">
                                </div>
                            </div>
							
							<?php if ((int)$this->session->userdata('id_kategori_karyawan') <= 2) { ?>
                            <div class="form-group">
								<label class="control-label col-sm-3" >Tanggal Akhir :</label>
                                <div class="col-sm-9">
                                    <input type="input" class="form-control datePickerLoss required" data-date-format='dd/mm/yyyy'  id="tgl_akhir_os"  name="tgl_akhir" autocomplete="off">
                                </div>
                            </div>
							<?php }else{ ?>
							<div class="form-group">
								<label class="control-label col-sm-3" >Tanggal Akhir :</label>
                                <div class="col-sm-9">
                                    <input type="input" class="form-control datePickerMaxToday required" data-date-format='dd/mm/yyyy'  id="tgl_akhir_os"  name="tgl_akhir" autocomplete="off">
                                </div>
                            </div>
							<?php } ?>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                	<div class="col-md-12">
                		<span style="float:left"><strong>Mohon Pilih Maksimal 7 hari periode tanggal, agar proses tidak lama.</strong></span>
                	</div>

                	<div class="col-md-12" style="margin-top: 20px;">
                		<button type="button" class="btn btn-primary" onClick="proses_generate_os()">Generate</button>
                    	<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pesan_modal" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Proses Generate, Mohon menunggu.</h4>
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

<script>


$( "#pegawai_autocomplete" ).autocomplete({
	source: function (request, response) {
		$.ajax({
			type	: 	"POST",
			url		:	base_url+'daftar_generate_laporan/autocomplete_pegawai',
			data	: 	{
				term:request.term,
				kode_instansi : $('#id_instansi_peg').val(),
				tgl_mulai	: $('#tgl_mulai_peg').val(),
				tgl_akhir	: $('#tgl_akhir_peg').val()
			},
			success	: 	response,
			dataType: 	'json'
		});
	},
	select	: 	function (e, ui) {
		$("#id_pegawai").val(ui.item.id_pegawai);
	}
}, {minLength: 3 });

function enable_nama(tanggal){
	$("#pegawai_autocomplete").removeAttr("readonly");
	$("#pegawai_autocomplete").focus();
	$("#pegawai_autocomplete").attr("placeholder", "Silahkan Ketik NIP atau Nama Pegawai");
}

function opsiGen(sel, id, nama){
	if(sel.value == "instansi"){
		$('#modal_generate_instansi').modal('show');
		$('#id_instansi').val(id);
	}
	else if(sel.value == "nama") {
		$('#modal_generate_pegawai').modal('show');
		$('#id_instansi_peg').val(id);
		$('#nama_instansi_peg').val(nama);
	}else if(sel.value == "ins_pns") {
		$('#modal_generate_pns').modal('show');
		$('#id_instansi_pns').val(id);
	}else if(sel.value == "ins_os") {
		$('#modal_generate_os').modal('show');
		$('#id_instansi_os').val(id);
	}
}

/*function proses_generate() {
	$('#modal_generate_instansi').modal('hide');
	$('.hidden-msg').show();
	$.ajax({
		url:  base_url + 'cetak_new/lap_absensi_lembur_opt/GeneratePerPegawaiManual',
		type: 'POST',
		dataType: 'json',
		data: $('#form_generate_ins').serialize(),
		success: function(data){
			if (data.status == 'gagal') {
				$('.hidden-msg').hide();
				$.alert({
					theme: 'modern',
					closeIcon: true,
					animation: 'scale',
					type: 'red',
					title: 'GAGAL',
					content: data.pesan,
				});
			}else{
				$('.hidden-msg').hide();
				$.alert({
					theme: 'modern',
					closeIcon: true,
					animation: 'scale',
					type: 'green',
					title: 'SUKSES',
					content: 'Data berhasil digenerate',
				});
				location.reload();
			}
		}
	});
}*/

/*function proses_generate_peg() {
	$('#modal_generate_pegawai').modal('hide');
	//$('.hidden-msg').show();
	$.ajax({
		url:  base_url + 'cetak_new/lap_absensi_lembur_opt/GeneratePerPegawaiManual',
		type: 'POST',
		dataType: 'json',
		data: $('#form_generate_peg').serialize(),
		success: function(data){
			if (data.status == 'gagal') {
				//$('.hidden-msg').hide();
				$.alert({
					theme: 'modern',
					closeIcon: true,
					animation: 'scale',
					type: 'red',
					title: 'GAGAL',
					content: data.pesan,
				});
			}else if (data.status == 'antrian') {
				var isi = "Antrian Generate Sedang Penuh, Silahkan Coba Kembali Beberapa Saat Lagi, <br/><br/> Berikut List Yang Masih Ada Dalam Antrian : ";
				for (i = 0; i < data.pesan.length; i++) {
					isi += (i+1) + ". " + data.pesan[i].nama_instansi + " Waktu Mulai Generate : " + data.pesan[i].start_at + "<br>";
				}
				$('#pesan_isi').html(isi);
				$('#pesan_modal').modal('show');
			}else{
				var i = 0;
				var i_max = data.pesan.length;
				proses_generate_peg_2(i, i_max, data);
				console.log("maksimal = " + i_max);
			}
		}
	});
}*/

/*function proses_generate_os() {
	$('#modal_generate_os').modal('hide');
	$('.hidden-msg').show();
	$.ajax({
		url:  base_url + 'cetak_new/lap_absensi_lembur_opt/MigrasiPerbagian_InsertManualOs',
		type: 'POST',
		dataType: 'json',
		data: $('#form_generate_os').serialize(),
		success: function(data){
			if (data.status == 'gagal') {
				$('.hidden-msg').hide();
				$.alert({
					theme: 'modern',
					closeIcon: true,
					animation: 'scale',
					type: 'red',
					title: 'GAGAL',
					content: data.pesan,
				});
			}else{
				$('.hidden-msg').hide();
				$.alert({
					theme: 'modern',
					closeIcon: true,
					animation: 'scale',
					type: 'green',
					title: 'SUKSES',
					content: 'Data berhasil digenerate',
				});
				location.reload();
			}
		}
	});
}*/

function proses_generate() {
	$('#modal_generate_instansi').modal('hide');
	$('#pesan_modal').modal('show');
	$.ajax({
		url:  base_url + 'cetak_new/lap_absensi_lembur_opt/get_pegawai_garbos',
		type: 'POST',
		dataType: 'json',
		data: $('#form_generate_ins').serialize(),
		success: function(data){
			if (data.status == 'gagal') {
				$('#pesan_modal').modal('hide');
				$('#pesan_isi2').html(data.pesan);
				$('#pesan_modal2').modal('show');
			}else if (data.status == 'antrian') {
				var isi = "Antrian Generate Sedang Penuh, Silahkan Coba Kembali Beberapa Saat Lagi, <br/><br/> Berikut List Yang Masih Ada Dalam Antrian : <br>";
				for (i = 0; i < data.pesan.length; i++) {
					isi += (i+1) + ". " + data.pesan[i].nama_instansi + " Waktu Mulai Generate : " + data.pesan[i].start_at + "<br>";
				}
				$('#pesan_modal').modal('hide');
				$('#pesan_isi2').html(isi);
				$('#pesan_modal2').modal('show');
			}else{
				var i = 0;
				var i_max = data.pesan.length;
				proses_generate_peg(i, i_max, data);
				console.log("maksimal = " + i_max);
			}
		}
	});
}

function proses_generate_pns() {
	$('#modal_generate_pns').modal('hide');
	$('#pesan_modal').modal('show');
	$.ajax({
		url:  base_url + 'cetak_new/lap_absensi_lembur_opt/get_pegawai_garbos/pns',
		type: 'POST',
		dataType: 'json',
		data: $('#form_generate_pns').serialize(),
		success: function(data){
			if (data.status == 'gagal') {
				$('#pesan_modal').modal('hide');
				$('#pesan_isi2').html(data.pesan);
				$('#pesan_modal2').modal('show');
			}else if (data.status == 'antrian') {
				var isi = "Antrian Generate Sedang Penuh, Silahkan Coba Kembali Beberapa Saat Lagi, <br/><br/> Berikut List Yang Masih Ada Dalam Antrian : <br>";
				for (i = 0; i < data.pesan.length; i++) {
					isi += (i+1) + ". " + data.pesan[i].nama_instansi + " Waktu Mulai Generate : " + data.pesan[i].start_at + "<br>";
				}
				$('#pesan_modal').modal('hide');
				$('#pesan_isi2').html(isi);
				$('#pesan_modal2').modal('show');
			}else{
				var i = 0;
				var i_max = data.pesan.length;
				proses_generate_peg(i, i_max, data);
				console.log("maksimal = " + i_max);
			}
		}
	});
}

function proses_generate_os() {
	$('#modal_generate_os').modal('hide');
	$('#pesan_modal').modal('show');
	$.ajax({
		url:  base_url + 'cetak_new/lap_absensi_lembur_opt/get_pegawai_garbos/os',
		type: 'POST',
		dataType: 'json',
		data: $('#form_generate_os').serialize(),
		success: function(data){
			if (data.status == 'gagal') {
				$('#pesan_modal').modal('hide');
				$('#pesan_isi2').html(data.pesan);
				$('#pesan_modal2').modal('show');
			}else if (data.status == 'antrian') {
				var isi = "Antrian Generate Sedang Penuh, Silahkan Coba Kembali Beberapa Saat Lagi, <br/><br/> Berikut List Yang Masih Ada Dalam Antrian : <br>";
				for (i = 0; i < data.pesan.length; i++) {
					isi += (i+1) + ". " + data.pesan[i].nama_instansi + " Waktu Mulai Generate : " + data.pesan[i].start_at + "<br>";
				}
				$('#pesan_modal').modal('hide');
				$('#pesan_isi2').html(isi);
				$('#pesan_modal2').modal('show');
			}else{
				var i = 0;
				var i_max = data.pesan.length;
				proses_generate_peg(i, i_max, data);
				console.log("maksimal = " + i_max);
			}
		}
	});
}

function proses_generate_single() {
	$('#modal_generate_pegawai').modal('hide');
	$('.hidden-msg').show();
	$.ajax({
		url:  base_url + 'cetak_new/lap_absensi_lembur_opt/GeneratePerPegawaiManual',
		type: 'POST',
		dataType: 'json',
		data: $('#form_generate_peg').serialize(),
		success: function(data){
			if (data.status == 'gagal') {
				$('.hidden-msg').hide();
				//alert(data.pesan);
				$.alert({
					theme: 'modern',
					closeIcon: true,
					animation: 'scale',
					type: 'red',
					title: 'GAGAL',
					content: data.pesan,
				});
			}else{
				$('.hidden-msg').hide();
				//alert('Data berhasil digenerate');
				$.alert({
					theme: 'modern',
					closeIcon: true,
					animation: 'scale',
					type: 'green',
					title: 'SUKSES',
					content: data.pesan,
				});
				location.reload();
			}
		}
	});
}

//fungsi rekursif melakukan generate INSTANSI, PNS, OS
function proses_generate_peg(i, i_max, data_kirim) {
	//console.log(i); aktifkan jika develop
	var persen = Math.round((i / i_max) * 100);
	$('.progress-bar').attr("aria-valuenow", persen).css("width", persen+'%').text(persen + '%');
	$('#proses-data').text('Proses ke : '+ i +' dari total '+ i_max +' data.');

	$.ajax({
		url:  base_url + 'cetak_new/lap_absensi_lembur_opt/GeneratePerPegawaiManual',
		type: 'POST',
		dataType: 'json',
		data: {
			id_pegawai 		: data_kirim.pesan[i].id,
			tgl_mulai_peg 	: data_kirim.tgl_mulai,
			tgl_akhir_peg 	: data_kirim.tgl_selesai,
			id_instansi_peg : data_kirim.kd_instansi
		},
		success: function(data){
			if (data.status == 'gagal') {
				$('#pesan_isi').html(isi);
			} else {
				i = i + 1;
				console.log(i);
				if(i < i_max){
					proses_generate_peg(i, i_max, data_kirim);
				}
				else {
					$.ajax({
						url:  base_url + 'cetak_new/lap_absensi_lembur_opt/update_selesai',
						type: 'POST',
						dataType: 'json',
						data: {
							id_user 		: data_kirim.id_user_upd,
							kode_instansi 	: data_kirim.kode_instansi_upd,
							start_at		: data_kirim.start_at_upd
						},
						success: function(data){
							$('#pesan_isi').html('Data berhasil digenerate');
							$('#pesan_modal').modal('hide');
							$.alert({
								theme: 'modern',
								closeIcon: true,
								animation: 'scale',
								type: 'green',
								title: 'SUKSES',
								content: 'Data sukses di generate !',
								buttons: {
							        somethingElse: {
							            text: 'Ok',
							            btnClass: 'btn-blue',
							            keys: ['enter', 'shift'],
							            action: function(){
							                location.reload();
							            }
							        }
							    }
							});
							//$('.hidden-msg').hide();
						}
					});
				}
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
