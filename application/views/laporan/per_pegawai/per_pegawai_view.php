<!-- Content Header (Page header) -->
<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<i class="fa fa-gift"></i>
		<?php //echo $this->template_view->nama_menu('nama_menu'); ?>
		Laporan Absensi Per Pegawai
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">
        <div class="col-md-12">
            <div class="form-group col-md-8">
                <div class="col-md-2">
                    <label class="control-label" for="nip">NIP</label>
                </div>
                <div class="col-xs-10" style="padding-left: 15px; padding-right: 15px">
                    <div class=" input-group">
                        <input id="nip" name="nip" class="form-control" placeholder="Pilih Pegawai" required="" disabled="disabled" aria-invalid="true" type="text">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modalcariPegawai"><i class="glyphicon glyphicon-search"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <div>
                    <input name="nama" disabled="true" class="form-control" id="nama" aria-invalid="false" type="text">
										<input style="display:none;" name="id_pegawai" disabled="true" class="form-control" id="id_pegawai" aria-invalid="false" type="text">
                </div>
            </div>
        </div>
    </div>
		<div class="row">
        <div class="col-md-12">
            <div class="form-group col-md-8">
                <div class="col-md-2">
                    <label class="control-label">Tanggal</label>
                </div>
                <div class="col-md-5">
                    <input class="form-control form-control-inline input-medium datePicker" name="tanggal1" id="tanggal1" data-autoclose="1" data-date-format="dd/mm/yyyy" required="" aria-invalid="false" type="text" placeholder="Tanggal Mulai">
                </div>
                <div class="col-md-5">
                    <input class="form-control form-control-inline input-medium datePicker" name="tanggal2" id="tanggal2" data-autoclose="1" data-date-format="dd/mm/yyyy" required="" aria-invalid="false" type="text" placeholder="Tanggal Akhir">
                </div>
            </div>
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

<div class="modal fade" id="modalcariPegawai" tabindex="-1" role="dialog" aria-labelledby="modalcariPegawai" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Cari Pegawai</h4>
            </div>
            <div class="modal-body">
							<div class="form-group input-group" style="margin-top:5px;">
									<input id="keyword-pegawai" name="keyword" class="col-md-2 form-control" placeholder="Masukkan pencarian" autofocus="" aria-invalid="false" type="text">
									<span class="input-group-btn">
											<button id="btn-filter-pegawai" class="btn btn-default" type="button"><i class="glyphicon glyphicon-search"></i>
											</button>
									</span>
							</div>
							<div id="pegawai-list">
          			<div class="table-responsive">
              		<table id="tablePegawai" class="table table-striped table-sm table-hover table-select" width="100%">
                  	<thead>
                    	<tr>
												<th>Id</th>
                      	<th>NIP</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Instansi</th>
                        <th>Status</th>
                      </tr>
                		</thead>
                  	<tbody>
										</tbody>
									</table>
								</div>
							</div>
            </div>
        </div>
    </div>
</div>

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
																				<a onClick="printPerPegawaiSby('pdf')">Pdf </a>
																		</li>
																		<li>
																				<a onClick="printPerPegawaiSby('xls')">Excel </a>
																		</li>
																		<li>
																				<a onClick="printPerPegawaiSby('html')">Web </a>
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

	function printPerPegawaiSby(mode) {
		if((angka1 + angka2) == $("#angkaJawaban").val()) {
			if($("#id_pegawai").val() == '') {
				$("#pesan_isi").html("Pilih Pegawai Terlebih Dahulu");
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
					var param = $("#id_pegawai").val() + "||" + $("#tanggal1").val() + "||" + $("#tanggal2").val() + "||" + mode;
					var p = encodeURIComponent(window.btoa(param));
					window.open('<?=base_url()?>cetak/cetakperpegawai?p=' + p, '_blank');
				}
			}
		}
		else {
			$("#pesan_isi").html("Jawaban Anda Salah");
			$('#pesan_modal').modal('show');
		}
	}
</script>
