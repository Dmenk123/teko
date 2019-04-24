

<!-- Content Header (Page header) -->
<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		Tambah Data <?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<form class="form-horizontal" id="form_standar" action="<?=base_url()."".$this->uri->segment(1)."/".$this->uri->segment(2);?>_data">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label col-sm-3" >NIP :</label>
					<div class="col-sm-9">
						<input type="input" class="form-control required" id="NIP"  name="NIP" onkeyup="formatangka(this);">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Nama :</label>
					<div class="col-sm-9">
						<input type="input" class="form-control required" id="NAMA"  name="NAMA">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Tempat Lahir :</label>
					<div class="col-sm-9">
						<input type="input" class="form-control" id="TEMPAT_LAHIR"  name="TEMPAT_LAHIR">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Jenis Kelamin :</label>
					<div class="col-sm-9">
						<select class="form-control select2 required" id="KODE_JENIS_KELAMIN" name="KODE_JENIS_KELAMIN" data-placeholder="Pilih Jenis Kelamin">
							<option></option>
						<?php foreach($this->jenisKelaminData as $jkData) : ?>
							<option value="<?=$jkData->kode?>"><?=$jkData->nama?></option>
						<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Golongan Akhir :</label>
					<div class="col-sm-9">
						<select class="form-control select2split" id="KODE_GOLONGAN_AKHIR" name="KODE_GOLONGAN_AKHIR" data-placeholder="Pilih Golongan Akhir">
							<option></option>
						<?php foreach($this->golonganData as $gData) : ?>
							<option value="<?=$gData->kode?>"><?=$gData->deskripsi?>.<?=$gData->nama?></option>
						<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Jenis Jabatan :</label>
					<div class="col-sm-9">
						<select class="form-control select2 required" id="KODE_JENIS_JABATAN" name="KODE_JENIS_JABATAN" data-placeholder="Pilih Jenis Jabatan">
							<option></option>
						<?php foreach($this->jenisJabatanData as $jjData) : ?>
							<option value="<?=$jjData->kode?>"><?=$jjData->nama?></option>
						<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Status Pegawai :</label>
					<div class="col-sm-9">
						<select class="form-control select2 required" id="KODE_STATUS_PEGAWAI" name="KODE_STATUS_PEGAWAI" data-placeholder="Pilih Status Pegawai">
							<option></option>
						<?php foreach($this->statusPegawaiData as $spData) : ?>
							<option value="<?=$spData->kode?>"><?=$spData->nama?></option>
						<?php endforeach; ?>
						</select>
					</div>
				</div>
				<br/>
				<br/>
				<div class="form-group">
					<label class="control-label col-sm-3" >History Role Jam Kerja :</label>
					<div class="col-sm-9">
						<table class="table table-bordered">
		            <thead>
		              <tr>
										<th>Tanggal</th>
										<th>Role Jam Kerja</th>
		                <th class="col-md-1">
												<button type="button" class="btn-right btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#modalRoleHistory">
                        	<span class="glyphicon glyphicon-plus-sign"></span>
                        </button>
		                </th>
		              </tr>
		            </thead>
		            <tbody id="tbody-role">
								</tbody>
							</table>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label col-sm-3" >No. Reg :</label>
					<div class="col-sm-9">
						<input type="input" class="form-control" id="NO_REGISTRASI"  name="NO_REGISTRASI">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Gelar Depan :</label>
					<div class="col-sm-3">
						<input type="input" class="form-control" id="GELAR_DEPAN"  name="GELAR_DEPAN">
					</div>
					<label class="control-label col-sm-3" >Gelar Belakang :</label>
					<div class="col-sm-3">
						<input type="input" class="form-control" id="GELAR_BELAKANG"  name="GELAR_BELAKANG">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Tgl Lahir :</label>
					<div class="col-sm-9">
						<input type="input" class="form-control datePickerLoss" id="TGL_LAHIR"  name="TGL_LAHIR">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >No. HP :</label>
					<div class="col-sm-9">
						<input type="input" class="form-control" id="NO_HP"  name="NO_HP" onkeyup="formatangka(this);">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Eselon :</label>
					<div class="col-sm-9">
						<select class="form-control select2" id="KODE_ESELON" name="KODE_ESELON" data-placeholder="Pilih Eselon">
							<option></option>
						<?php foreach($this->eselonData as $eData) : ?>
							<option value="<?=$eData->kode?>"><?=$eData->nama_eselon?></option>
						<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >TMT Gol. Akhir :</label>
					<div class="col-sm-9">
						<input type="input" class="form-control" id="TMT_GOLONGAN_AKHIR"  name="TMT_GOLONGAN_AKHIR" disabled="true">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Masuk Roster :</label>
					<div class="col-sm-9">
						<input type="checkbox" class="form-control" id="ROSTER" name="ROSTER" value="true">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Aktif :</label>
					<div class="col-sm-9">
						<input type="checkbox" class="form-control" id="AKTIF"  name="AKTIF" value="true" checked>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >History Unit Kerja :</label>
					<div class="col-sm-9">
						<table class="table table-bordered">
								<thead>
									<tr>
										<th>Tanggal</th>
										<th>Unit Kerja</th>
										<th>Instansi</th>
										<th class="col-md-1">
											<button type="button" class="btn-right btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#modalUnorHistory">
												<span class="glyphicon glyphicon-plus-sign"></span>
											</button>
										</th>
									</tr>
								</thead>
								<tbody id="tbody-unor">
								</tbody>
							</table>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-4 col-sm-10">
					<img src="<?php echo base_url();?>assets/img/loading.gif" id="loading" style="display:none">
					<p id="pesan_error" style="display:none" class="text-warning" style="display:none"></p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-4 col-sm-10">
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
					<a href="<?=base_url()."".$this->uri->segment(1);?>">
						<span class="btn btn-warning"><i class="glyphicon glyphicon-remove"></i> Batal</span>
					</a>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="modal fade" id="modalRoleHistory" tabindex="-1" role="dialog" aria-labelledby="modalRoleHistoryLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Tambah Role Jam Kerja</h4>
            </div>
            <div class="modal-body">
                <form id="formRole" name="formRole">
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
																<label class="control-label col-sm-3" >Per Tanggal :</label>
                                <div class="col-sm-9">
                                    <input type="input" class="form-control datePickerLoss" id="TGL_MULAI"  name="TGL_MULAI">
                                </div>
                            </div>

                            <div class="form-group">
																<label class="control-label col-sm-3" >Role Jam Kerja :</label>
																<div class="col-sm-9">
																	<select class="form-control select2 required" id="ID_ROLE_JAM_KERJA" name="ID_ROLE_JAM_KERJA" data-placeholder="Pilih Role Jam Kerja">
																		<option></option>
																	<?php foreach($this->roleJamKerjaData as $rjkData) : ?>
																		<option value="<?=$rjkData->id?>"><?=$rjkData->nama?></option>
																	<?php endforeach; ?>
																	</select>
																</div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onClick="tambah_role()">Tambah</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUnorHistory" tabindex="-1" role="dialog" aria-labelledby="modalUnorHistory" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Tambah Role Jam Kerja</h4>
            </div>
            <div class="modal-body">
                <form id="formUnor" name="formUnor">
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
																<label class="control-label col-sm-3" >Per Tanggal :</label>
                                <div class="col-sm-9">
                                    <input type="input" class="form-control datePickerLoss" id="TGL_MULAI"  name="TGL_MULAI">
                                </div>
                            </div>

                            <div class="form-group">
																<label class="control-label col-sm-3" >Instansi :</label>
																<div class="col-sm-9">
																	<select class="form-control select2 required" id="KODE_INSTANSI" name="KODE_INSTANSI" data-placeholder="Pilih Instansi" onChange="get_unor(this)">
																		<option></option>
																	<?php foreach($this->instansiData as $iData) : ?>
																		<option value="<?=$iData->kode?>"><?=$iData->nama?></option>
																	<?php endforeach; ?>
																	</select>
																</div>
                            </div>
														<div class="form-group">
																<label class="control-label col-sm-3" >Unit Kerja :</label>
																<div class="col-sm-9">
																	<select class="form-control select2 required" id="KODE_UNOR" name="KODE_UNOR" data-placeholder="Pilih Unit Kerja">
																		<option></option>
																	</select>
																</div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onClick="tambah_unor()">Tambah</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
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
<!-- /.content -->
<script>
	function get_unor(el){
		var kd_instansi = $(el).val();
		if(kd_instansi != '') {
			$.ajax({
				url: "<?php echo base_url()?>master_unor_kerja/getUnorByInstansi",
				type: 'POST',
				dataType:'json',
				cache: false,
				data: {
					KODE_INSTANSI : kd_instansi
				},
				success: function(data) {
					if (data.status) {
						$('#KODE_UNOR').empty();
						$('#KODE_UNOR').append("<option></option>");
						for (i = 0; i < data.unor.length; i++) {
							$('#KODE_UNOR').append("<option value='"+data.unor[i]["kode"]+"'>"+data.unor[i]["nama"]+"</option>");
						}
					}
					else {
						$('#pesan_isi').html(data.pesan);
						$('#pesan_modal').modal('show');
					}
				}
			});
		}
		else {
			$('#KODE_UNOR').empty();
			$('#KODE_UNOR').append("<option></option>");
		}
	}

	function tambah_role(){
		if ($("#formRole #TGL_MULAI").val() != '' && $("#formRole #ID_ROLE_JAM_KERJA").val() != '') {
			$('#tbody-role').append(
				'<tr>' +
					'<td><input type="hidden" name="ROLE_TGL_MULAI[]" value="'+$("#formRole #TGL_MULAI").val()+'">'+$("#formRole #TGL_MULAI").val()+'</td>' +
					'<td><input type="hidden" name="ROLE_ID_ROLE_JAM_KERJA[]" value="'+$("#formRole #ID_ROLE_JAM_KERJA").val()+'">'+$("#formRole #ID_ROLE_JAM_KERJA option:selected" ).text()+'</td>' +
					'<td>' +
						'<button type="button" class="btn-right btn btn-danger btn-sm btn-block" onClick="remove(this)">' +
							'<span class="glyphicon glyphicon-remove-sign"></span>' +
						'</button></td>' +
				'</tr>'
			);

			$('#modalRoleHistory').modal('hide');
			reset('formRole');
		}
		else {
			$('#pesan_isi').html("Per Tanggal dan Role Jam Kerja harus terisi");
			$('#pesan_modal').modal('show');
		}
	}

	function tambah_unor(){
		if ($("#formUnor #TGL_MULAI").val() != '' && $("#formUnor #KODE_INSTANSI").val() != '' && $("#formUnor #KODE_UNOR").val() != '') {
			$('#tbody-unor').append(
				'<tr>' +
					'<td><input type="hidden" name="UNOR_TGL_MULAI[]" value="'+$("#formUnor #TGL_MULAI").val()+'">'+$("#formUnor #TGL_MULAI").val()+'</td>' +
					'<td><input type="hidden" name="UNOR_KODE_UNOR[]" value="'+$("#formUnor #KODE_UNOR").val()+'">'+$("#formUnor #KODE_UNOR option:selected" ).text()+'</td>' +
					'<td><input type="hidden" name="UNOR_KODE_INSTANSI[]" value="'+$("#formUnor #KODE_INSTANSI").val()+'">'+$("#formUnor #KODE_INSTANSI option:selected" ).text()+'</td>' +
					'<td>' +
						'<button type="button" class="btn-right btn btn-danger btn-sm btn-block" onClick="remove(this)">' +
							'<span class="glyphicon glyphicon-remove-sign"></span>' +
						'</button></td>' +
				'</tr>'
			);

			$('#modalUnorHistory').modal('hide');
			reset('formUnor');
		}
		else {
			$('#pesan_isi').html("Per Tanggal, Instansi dan Unit Kerja harus terisi");
			$('#pesan_modal').modal('show');
		}
	}

	function remove(el){
		$(el).closest("tr").remove();
	}

	function reset(id_form){
		$("#"+id_form)[0].reset();
		$("#"+id_form+" .select2").val('').trigger('change.select2');
	}
</script>
