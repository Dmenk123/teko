
<!-- Content Header (Page header) -->
<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<i class="fa fa-gift"></i>
		<?php //echo $this->template_view->nama_menu('nama_menu'); ?>
		Skor Kehadiran/ Unit Kerja
		</div>

	</div>
	<div class="portlet-body">
		<div class="panel-body">
			<div class="form-horizontal col-md-12">
				<div class="col-md-6">
					<div class="form-group row">
						<div class="col-md-3">
							<label class="control-label" for="tanggal1">Tanggal Awal</label>
						</div>
						<div class="col-md-6">
							<input class="form-control form-control-inline input-medium datePicker" name="tanggal1" id="tanggal1" data-autoclose="1" data-date-format="dd/mm/yyyy" required="" aria-invalid="false" type="text" placeholder="Tanggal Mulai">
						</div>
					</div>
				</div>
			<div class="col-md-6">
				<div class="form-group row">
					<div class="col-md-3">
						<label class="control-label" for="tanggal2">Tanggal Akhir</label>
					</div>
					<div class="col-md-6">
						<input class="form-control form-control-inline input-medium datePicker" name="tanggal2" id="tanggal2" data-autoclose="1" data-date-format="dd/mm/yyyy" required="" aria-invalid="false" type="text" placeholder="Tanggal Akhir">
					</div>
				</div>
			</div>
			</div>
			<div class="form-horizontal col-md-12">
				<div class="col-md-6">
					<div class="form-group">
						<div class="col-md-3">
							<label class="control-label">Unit Kerja</label>
						</div>
						<div class="col-md-8">
							<select class="form-control select2 required" id="kode_instansi" name="kode_instansi" data-placeholder="Pilih Instansi">
								<option></option>
							<?php foreach($this->instansiData as $iData) : ?>
								<option value="<?=$iData->kode?>"><?=$iData->nama?></option>
							<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="form-horizontal col-md-12">
				<div class="col-md-6">
					<div class="form-group">
						<div class="col-md-3">
							<label for="opsiStatus" class="control-label">Status Pegawai</label>
						</div>
						<div class="col-md-3">
							<div class="input-group col-md-12">
								<select class="form-control select2 required" id="status_pegawai" name="status_pegawai" data-placeholder="Pilih Status">
									<option></option>
									<option value="1">PNS</option>
									<option value="0">NON PNS</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="col-md-3">
						<button type="button" class="btn-right btn btn-primary btn-sm btn-block" onClick="open_captcha()">
								<span class="glyphicon glyphicon-print"></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- /.content -->

<div class="modal fade" id="modalCaptcha" tabindex="-1" role="dialog" aria-labelledby="modalCaptcha" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Print</h4>
            </div>
            <div class="modal-body">
							<div class="panel-body">
								<div class="form-horizontal">
										<div class="form-group">
												<label class="control-label col-md-4">
														Jawab pertanyaan berikut  <span class="required">*</span>
												</label>
												<div class="col-md-4">
														<span id="soal" class="font-red-mint">8 + 3 = </span>
												</div>
												<div class="col-md-4">
														<input id="angkaJawaban" name="angkaJawaban" min=0 class="form-control" onKeyup="formatangka(this)" required="" aria-invalid="false" type="number">
												</div>
										</div>
										<div class="form-group col-md-6">
												<div class="col-md-12">
														<div class="btn-group">
																<button type="button" class="btn green">Export ke</button>
																<button type="button" class="btn green dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-angle-down"></i></button>
																<ul class="dropdown-menu" role="menu">
																		<li>
																				<a onClick="printSkor('pdf')">Pdf </a>
																		</li>
																		<li>
																				<a onClick="printSkor('xls')">Excel </a>
																		</li>
																		<li>
																				<a onClick="printSkor('html')">Web </a>
																		</li>
																</ul>
														</div>
												</div>
										</div>
								</div>
							</div>
            </div>
						<div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

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

<script>
	var angka1;
	var angka2;

	function open_captcha() {
		angka1 = Math.floor(Math.random() * 10);
		angka2 = Math.floor(Math.random() * 10);
		$("#soal").html(angka1 + " + " + angka2 + " = ");
		$("#angkaJawaban").val("");
		$('#modalCaptcha').modal('show');
	}

	function printSkor(mode) {
		if((angka1 + angka2) == $("#angkaJawaban").val()) {
			if($("#kode_instansi").val() == '') {
				$("#pesan_isi").html("Pilih Unit Kerja Terlebih Dahulu");
				$('#pesan_modal').modal('show');
			}
			else if($("#status_pegawai").val() == '') {
				$("#pesan_isi").html("Pilih Status Pegawai Terlebih Dahulu");
				$('#pesan_modal').modal('show');
			}
			else if($("#tanggal1").val() == '') {
				$("#pesan_isi").html("Masukkan Tanggal Mulai Terlebih Dahulu");
				$('#pesan_modal').modal('show');
			}
			else if($("#tanggal2").val() == '') {
				$("#pesan_isi").html("Masukkan Tanggal Akhir Terlebih Dahulu");
				$('#pesan_modal').modal('show');
			}
			else {
				var d1 = $("#tanggal1").val();
				var d2 = $("#tanggal2").val();
				dsplit1 = d1.split('/');
				dsplit2 = d2.split('/');

				var date1 = new Date(dsplit1[2],dsplit1[1]-1,dsplit1[0]);
				var date2 = new Date(dsplit2[2],dsplit2[1]-1,dsplit2[0]);
				if (date1 > date2) {
					$("#pesan_isi").html("Tanggal Mulai Tidak Boleh Lebih Besar Dari Tanggal Akhir");
					$('#pesan_modal').modal('show');
				}
				else {
					var param = $("#kode_instansi").val() + "||" + $("#status_pegawai").val() + "||" + $("#tanggal1").val() + "||" + $("#tanggal2").val() + "||" + mode;
					var p = encodeURIComponent(window.btoa(param));
					window.open('<?=base_url()?>cetak/cetakskor?p=' + p, '_blank');
				}
			}
		}
		else {
			$("#pesan_isi").html("Jawaban Anda Salah");
			$('#pesan_modal').modal('show');
		}
	}
</script>
