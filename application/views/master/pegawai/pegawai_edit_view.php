

<!-- Content Header (Page header) -->
<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		Tambah Data <?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">

			<div class="row">

			<form class="form-horizontal" id="form_standar"  action="<?=base_url()."".$this->uri->segment(1)."/".$this->uri->segment(2);?>_data">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label col-sm-3" >NIP :</label>
					<div class="col-sm-9">
						<input type="hidden" value="<?php echo $this->oldData->id_pegawai; ?>"  class="form-control required" id="ID"  name="ID">
						<input type="input" class="form-control required" id="NIP"  name="NIP" onkeyup="formatangka(this);" value="<?=$this->oldData->nip;?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Nama :</label>
					<div class="col-sm-9">
						<input type="input" class="form-control required" id="NAMA"  name="NAMA" value="<?=$this->oldData->nama;?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Tempat Lahir :</label>
					<div class="col-sm-9">
						<input type="input" class="form-control" id="TEMPAT_LAHIR"  name="TEMPAT_LAHIR" value="<?=$this->oldData->tempat_lahir;?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Jenis Kelamin :</label>
					<div class="col-sm-9">
						<select class="form-control select2 required" id="KODE_JENIS_KELAMIN" name="KODE_JENIS_KELAMIN" data-placeholder="Pilih Jenis Kelamin">
							<option></option>
						<?php foreach($this->jenisKelaminData as $jkData) : ?>
							<option value="<?=$jkData->kode?>" <?php if($this->oldData->kode_jenis_kelamin == $jkData->kode){ echo 'selected'; } ?>><?=$jkData->nama?></option>
						<?php endforeach; ?>
						</select>
					</div>
				</div>
			<!--	<div class="form-group">
					<label class="control-label col-sm-3" >Golongan Akhir :</label>
					<div class="col-sm-9">
						<select class="form-control select2split" id="KODE_GOLONGAN_AKHIR" name="KODE_GOLONGAN_AKHIR" data-placeholder="Pilih Golongan Akhir">
							<option></option>
						<?php foreach($this->golonganData as $gData) : ?>
							<option value="<?=$gData->kode?>" <?php if($this->oldData->kode_golongan_akhir == $gData->kode){ echo 'selected'; } ?>><?=$gData->deskripsi?>.<?=$gData->nama?></option>
						<?php endforeach; ?>
						</select>
					</div>
				</div>-->
			<!--	<div class="form-group">
					<label class="control-label col-sm-3" >Jenis Jabatan :</label>
					<div class="col-sm-9">
						<select class="form-control select2 required" id="KODE_JENIS_JABATAN" name="KODE_JENIS_JABATAN" data-placeholder="Pilih Jenis Jabatan">
							<option></option>
						<?php foreach($this->jenisJabatanData as $jjData) : ?>
							<option value="<?=$jjData->kode?>" <?php if($this->oldData->kode_jenis_jabatan == $jjData->kode){ echo 'selected'; } ?>><?=$jjData->nama?></option>
						<?php endforeach; ?>
						</select>
					</div>
				</div> -->
				<div class="form-group">
					<label class="control-label col-sm-3" >Status Pegawai :</label>
					<div class="col-sm-9">
						<select class="form-control select2 required" id="KODE_STATUS_PEGAWAI" name="KODE_STATUS_PEGAWAI" data-placeholder="Pilih Status Pegawai">
							<option></option>
						<?php foreach($this->statusPegawaiData as $spData) : ?>
							<option value="<?=$spData->kode?>" <?php if($this->oldData->kode_status_pegawai == $spData->kode){ echo 'selected'; } ?>><?=$spData->nama?></option>
						<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >No. Reg :</label>
					<div class="col-sm-9">
						<input type="input" class="form-control" id="NO_REGISTRASI"  name="NO_REGISTRASI" value="<?=$this->oldData->no_registrasi;?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Gelar Depan :</label>
					<div class="col-sm-3">
						<input type="input" class="form-control" id="GELAR_DEPAN"  name="GELAR_DEPAN" value="<?=$this->oldData->gelar_depan;?>">
					</div>
					<label class="control-label col-sm-3" >Gelar Belakang :</label>
					<div class="col-sm-3">
						<input type="input" class="form-control" id="GELAR_BELAKANG"  name="GELAR_BELAKANG" value="<?=$this->oldData->gelar_belakang;?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Tgl Lahir :</label>
					<div class="col-sm-9">
						<input type="input" class="form-control datePickerLoss" id="TGL_LAHIR"  name="TGL_LAHIR" value="<?=date('d/m/Y',strtotime($this->oldData->tgl_lahir));?>">
					</div>
				</div>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-10">
					<img src="<?php echo base_url();?>assets/img/loading.gif" id="loading" style="display:none">
					<p id="pesan_error" style="display:none" class="text-warning" style="display:none"></p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-10">
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
					<a href="<?=base_url()."".$this->uri->segment(1);?>">
						<span class="btn btn-warning"><i class="glyphicon glyphicon-arrow-left"></i> Kembali</span>
					</a>
				</div>
			</div>
			<br>
			<br>

			</div>
			<div class="col-sm-6">
				
				<div class="form-group">
					<label class="control-label col-sm-3" >No. HP :</label>
					<div class="col-sm-9">
						<input type="input" class="form-control" id="NO_HP"  name="NO_HP" onkeyup="formatangka(this);" value="<?=$this->oldData->no_hp;?>">
					</div>
				</div>
			<!--	<div class="form-group">
					<label class="control-label col-sm-3" >Eselon :</label>
					<div class="col-sm-9">
						<select class="form-control select2" id="KODE_ESELON" name="KODE_ESELON" data-placeholder="Pilih Eselon">
							<option></option>
						<?php foreach($this->eselonData as $eData) : ?>
							<option value="<?=$eData->kode?>" <?php if($this->oldData->kode_eselon == $eData->kode){ echo 'selected'; } ?>><?=$eData->nama_eselon?></option>
						<?php endforeach; ?>
						</select>
					</div>
				</div> -->
				<div class="form-group">
					<label class="control-label col-sm-3" >TMT Gol. Akhir :</label>
					<div class="col-sm-9">
						<input type="input" class="form-control" id="TMT_GOLONGAN_AKHIR"  name="TMT_GOLONGAN_AKHIR" disabled="true" value="<?=$this->oldData->tmt_golongan_akhir;?>">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Masuk Roster :</label>
					<div class="col-sm-9">
						<input type="checkbox" class="form-control" id="ROSTER" name="ROSTER" value="true" <?php if($this->oldData->roster){ echo 'checked'; } ?>>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Aktif :</label>
					<div class="col-sm-9">
						<input type="checkbox" class="form-control" id="AKTIF"  name="AKTIF" value="true" <?php if($this->oldData->aktif){ echo 'checked'; } ?>>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Meninggal : </label>
					<div class="col-sm-9">
						<input type="checkbox" class="form-control" id="MENINGGAL"  name="MENINGGAL" value="true" <?php if($this->oldData->meninggal == 't') { echo 'checked'; } ?>>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Tgl Meninggal :</label>
					<div class="col-sm-9">
						<input type="input" class="form-control datePickerLoss" id="TGL_MENINGGAL"  name="TGL_MENINGGAL" value="<?php if ($this->oldData->tgl_meninggal <> null) { echo date('d/m/Y',strtotime($this->oldData->tgl_meninggal)); }?>">
					</div>
				</div>
				<?php
					if($this->oldData->dokumen_kematian){
						echo '<div class="form-group">
								<label class="control-label col-sm-3" >Upload Dokumen Kematian:</label>
								<div class="col-sm-9">';
						echo '<img src='.base_url().'upload/meninggal/'.$this->oldData->dokumen_kematian.' width="375px">';
						echo '</div>
								</div>';
						#img(['src' => 'upload/'.$this->oldData->dokumen_kematian]);
					}
				?>
				<div class="form-group">
					<label class="control-label col-sm-3" >Upload Dokumen Kematian:</label>
					<div class="col-sm-9">
						<input type="file" class="form-control" name="photo">
					</div>
				</div>
			</div>

		</form>
			</div>
			<hr>
			<div class="row">
			<div class="col-sm-6">

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
									<?php for($i=0;$i<count($this->jamKerjaHistoriData);$i++) { ?>
										<tr>
											<td><?=date('d/m/Y',strtotime($this->jamKerjaHistoriData[$i]->tgl_mulai));?></td>
											<td><?=$this->jamKerjaHistoriData[$i]->nama_role_jam_kerja;?><br></td>
											<td>

												<button type="button" class="btn-right btn btn-danger btn-sm btn-block" onclick="tampil_pesan_hapus('<?=$this->jamKerjaHistoriData[$i]->nama_role_jam_kerja;?>','<?=base_url();?>master_pegawai/role_jam_kerja_delete?id_pegawai=<?=$this->jamKerjaHistoriData[$i]->id_pegawai;?>&id_histori=<?=$this->jamKerjaHistoriData[$i]->id;?>')">
													<span class="glyphicon glyphicon-remove-sign"></span>
												</button>
											</td>

										</tr>
									<?php } ?>
								</tbody>
							</table>
					</div>
				</div>





				<div class="form-group">
					<label class="control-label col-sm-3" >History Unit Kerja :</label>
					<div class="col-sm-9">
						<table class="table table-bordered">
								<thead>
									<tr>
										<th>Tanggal</th>
										<th>Instansi</th>
										<th>Unit Kerja</th>
										<th class="col-md-1">
											<button type="button" class="btn-right btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#modalUnorHistory">
												<span class="glyphicon glyphicon-plus-sign"></span>
											</button>
										</th>
									</tr>
								</thead>
								<tbody id="tbody-unor">
									<?php for($i=0;$i<count($this->unitKerjaHistoriData);$i++) { ?>
										<tr>
											<td><?php //var_dump($this->instansiKerjaHistoriData); ?> <?=date('d/m/Y',strtotime($this->unitKerjaHistoriData[$i]->tgl_mulai));?></td>
											<td><?=$this->unitKerjaHistoriData[$i]->nama_instansi;?></td>
											<td><?=$this->unitKerjaHistoriData[$i]->nama_unor;?></td>
											<td>
												<button type="button" class="btn-right btn btn-danger btn-sm btn-block" onclick="tampil_pesan_hapus('<?=$this->unitKerjaHistoriData[$i]->nama_unor;?>','<?=base_url();?>master_pegawai/unor_delete?id_pegawai=<?=$this->unitKerjaHistoriData[$i]->id_pegawai;?>&id_histori=<?=$this->unitKerjaHistoriData[$i]->id;?>')">
													<span class="glyphicon glyphicon-remove-sign"></span>
												</button></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
					</div>
				</div>

				</div>

			<div class="col-sm-6">

				<div class="form-group">
					<label class="control-label col-sm-3" >History Jabatan :</label>
					<div class="col-sm-9">
						<table class="table table-bordered">
								<thead>
									<tr>
										<th>Tanggal</th>
										<th>Jabatan</th>
										<th class="col-md-1">
											<button type="button" class="btn-right btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#modalJabatanHistory">
												<span class="glyphicon glyphicon-plus-sign"></span>
											</button>
										</th>
									</tr>
								</thead>
								<tbody id="tbody-unor">
									<?php for($i=0;$i<count($this->jabatanHistoriData);$i++) { ?>
										<tr>
											<td><?=date('d/m/Y',strtotime($this->jabatanHistoriData[$i]->tgl_mulai));?></td>
											<td><?=$this->jabatanHistoriData[$i]->nama_jenis_jabatan;?></td>
											<td>
												<button type="button" class="btn-right btn btn-danger btn-sm btn-block" onclick="tampil_pesan_hapus('<?=$this->jabatanHistoriData[$i]->nama_jenis_jabatan;?>','<?=base_url();?>master_pegawai/jabatan_delete?id_pegawai=<?=$this->jabatanHistoriData[$i]->id_pegawai;?>&id_histori=<?=$this->jabatanHistoriData[$i]->id;?>')">
													<span class="glyphicon glyphicon-remove-sign"></span>
												</button></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
					</div>
				</div>


				<div class="form-group">
					<label class="control-label col-sm-3" >History Golongan :</label>
					<div class="col-sm-9">
						<table class="table table-bordered">
								<thead>
									<tr>
										<th>Tanggal</th>
										<th>Golongan</th>
										<th class="col-md-1">
											<button type="button" class="btn-right btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#modalGolonganHistory">
												<span class="glyphicon glyphicon-plus-sign"></span>
											</button>
										</th>
									</tr>
								</thead>
								<tbody id="tbody-unor">
									<?php for($i=0;$i<count($this->golonganHistoriData);$i++) { ?>
										<tr>
											<td><?=date('d/m/Y',strtotime($this->golonganHistoriData[$i]->tgl_mulai));?></td>
											<td><?=$this->golonganHistoriData[$i]->nama_golongan;?></td>
											<td>
												<button type="button" class="btn-right btn btn-danger btn-sm btn-block" onclick="tampil_pesan_hapus('<?=$this->golonganHistoriData[$i]->nama_golongan;?>','<?=base_url();?>master_pegawai/golongan_delete?id_pegawai=<?=$this->golonganHistoriData[$i]->id_pegawai;?>&id_histori=<?=$this->golonganHistoriData[$i]->id;?>')">
													<span class="glyphicon glyphicon-remove-sign"></span>
												</button></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-3" >History Eselon :</label>
					<div class="col-sm-9">
						<table class="table table-bordered">
								<thead>
									<tr>
										<th>Tanggal</th>
										<th>Eselon</th>
										<th class="col-md-1">
											<button type="button" class="btn-right btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#modalEselonHistory">
												<span class="glyphicon glyphicon-plus-sign"></span>
											</button>
										</th>
									</tr>
								</thead>
								<tbody id="tbody-unor">
									<?php for($i=0;$i<count($this->eselonHistoriData);$i++) { ?>
										<tr>
											<td><?=date('d/m/Y',strtotime($this->eselonHistoriData[$i]->tgl_mulai));?></td>
											<td><?=$this->eselonHistoriData[$i]->nama_eselon;?></td>
											<td>
												<button type="button" class="btn-right btn btn-danger btn-sm btn-block" onclick="tampil_pesan_hapus('<?=$this->eselonHistoriData[$i]->nama_eselon;?>','<?=base_url();?>master_pegawai/eselon_delete?id_pegawai=<?=$this->eselonHistoriData[$i]->id_pegawai;?>&id_histori=<?=$this->eselonHistoriData[$i]->id;?>')">
													<span class="glyphicon glyphicon-remove-sign"></span>
												</button></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-3" >History Rumpun Jabatan :</label>
					<div class="col-sm-9">
						<table class="table table-bordered">
								<thead>
									<tr>
										<th>Tanggal</th>
										<th>Rumpun Jabatan</th>
										<th class="col-md-1">
											<button type="button" class="btn-right btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#modalRumpunJabatanHistory">
												<span class="glyphicon glyphicon-plus-sign"></span>
											</button>
										</th>
									</tr>
								</thead>
								<tbody id="tbody-unor">
									<?php for($i=0;$i<count($this->rumpunJabatanHistoriData);$i++) { ?>
										<tr>
											<td><?=date('d/m/Y',strtotime($this->rumpunJabatanHistoriData[$i]->tgl_mulai));?></td>
											<td><?=$this->rumpunJabatanHistoriData[$i]->nama_rumpun_jabatan;?></td>
											<td>
												<button type="button" class="btn-right btn btn-danger btn-sm btn-block" onclick="tampil_pesan_hapus('<?=$this->rumpunJabatanHistoriData[$i]->nama_rumpun_jabatan;?>','<?=base_url();?>master_pegawai/rumpun_jabatan_delete?id_pegawai=<?=$this->rumpunJabatanHistoriData[$i]->id_pegawai;?>&id_histori=<?=$this->rumpunJabatanHistoriData[$i]->id;?>')">
													<span class="glyphicon glyphicon-remove-sign"></span>
												</button></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
					</div>
				</div>



			</div>
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
                <form id="form_role_jam_kerja" name="formRole">
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
								<label class="control-label col-sm-3" >Per Tanggal :</label>
                                <div class="col-sm-9">
                                    <input type="input" class="form-control datePickerLoss required" data-date-format='dd/mm/yyyy' id="TGL_MULAI" name="TGL_MULAI">
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
                    <button type="button" class="btn btn-primary" onClick="tambah_transaksi('role_jam_kerja')">Tambah</button>
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
                <h4 class="modal-title">Tambah Unor</h4>
            </div>
            <div class="modal-body">
                <form id="form_unor" name="formUnor">
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
									<label class="control-label col-sm-3" >Per Tanggal :</label>
                                <div class="col-sm-9">
                                    <input type="input" class="form-control datePicker" data-date-format='dd/mm/yyyy' id="TGL_MULAI"  name="TGL_MULAI">
                                </div>
                            </div>

                            <div class="form-group">
								<label class="control-label col-sm-3" >Instansi :</label>
								<div class="col-sm-9">
									<select class="form-control select2 required"  id="KODE_INSTANSI" name="KODE_INSTANSI" data-placeholder="Pilih Instansi" onChange="get_unor(this)">
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
                            <div class="form-group">
                            	<label class="control-label col-sm-3" >Pindah Unor :</label>
                            	<div class="col-sm-9">
                            		<div class="checkbox">
	                            	  	<label><input type="checkbox" value="t" id="cek-unor" name="cek-unor">Ya, Pindah sesuai tanggal</label>
	                            	</div>
                            	</div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"  onClick="tambah_transaksi('unor')">Tambah</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalJabatanHistory" tabindex="-1" role="dialog" aria-labelledby="modalJabatanHistory" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Tambah Jabatan</h4>
            </div>
            <div class="modal-body">
                <form id="form_jabatan" name="form_jabatan">
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
								<label class="control-label col-sm-3" >Per Tanggal :</label>
                                <div class="col-sm-9">
                                    <input type="input" class="form-control datePickerLoss" data-date-format='dd/mm/yyyy' id="TGL_MULAI"  name="TGL_MULAI">
                                </div>
                            </div>

                            <div class="form-group">
								<label class="control-label col-sm-3" >Jabatan :</label>
								<div class="col-sm-9">
									<select class="form-control select2 required" id="KODE_JABATAN" name="KODE_JABATAN" data-placeholder="Pilih Jabatan" >
										<option></option>
										<?php foreach($this->jabatanData as $iData) : ?>
											<option value="<?=$iData->kode?>"><?=$iData->nama?></option>
										<?php endforeach; ?>
									</select>

									<?php //var_dump($this->jabatanData);?>
								</div>
                            </div>
							<div class="form-group">
                            	<label class="control-label col-sm-3" >Pindah Jabatan :</label>
                            	<div class="col-sm-9">
                            		<div class="checkbox">
	                            	  	<label><input type="checkbox" value="t" id="cek-jabatan" name="cek-jabatan">Ya, Pindah sesuai tanggal</label>
	                            	</div>
                            	</div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" id="btn_jabatan" class="btn btn-primary" onClick="tambah_transaksi('jabatan')">Tambah</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalGolonganHistory" tabindex="-1" role="dialog" aria-labelledby="modalGolonganHistory" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Tambah Golongan</h4>
            </div>
            <div class="modal-body">
                <form id="form_golongan" name="form_golongan">
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
								<label class="control-label col-sm-3" >Per Tanggal :</label>
                                <div class="col-sm-9">
                                    <input type="input" class="form-control datePickerLoss" data-date-format='dd/mm/yyyy' id="TGL_MULAI"  name="TGL_MULAI">
                                </div>
                            </div>

                            <div class="form-group">
								<label class="control-label col-sm-3" >Golongan :</label>
								<div class="col-sm-9">
									<select class="form-control select2 required" id="KODE_GOLONGAN" name="KODE_GOLONGAN" data-placeholder="Pilih Golongan" >
										<option></option>
										<?php foreach($this->golonganData as $iData) : ?>
											<option value="<?=$iData->kode?>"><?=$iData->nama?></option>
										<?php endforeach; ?>
									</select>

									<?php //var_dump($this->jabatanData);?>
								</div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" id="btn_jabatan" class="btn btn-primary" onClick="tambah_transaksi('golongan')">Tambah</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEselonHistory" tabindex="-1" role="dialog" aria-labelledby="modalEselonHistory" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Tambah Eselon</h4>
            </div>
            <div class="modal-body">
                <form id="form_eselon" name="form_eselon">
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
																<label class="control-label col-sm-3" >Per Tanggal :</label>
                                <div class="col-sm-9">
                                    <input type="input" class="form-control datePickerLoss" data-date-format='dd/mm/yyyy' id="TGL_MULAI"  name="TGL_MULAI">
                                </div>
                            </div>

                            <div class="form-group">
																<label class="control-label col-sm-3" >Eselon :</label>
																<div class="col-sm-9">
																	<select class="form-control select2 required" id="KODE_ESELON" name="KODE_ESELON" data-placeholder="Pilih Eselon" >
																		<option></option>
																		<?php foreach($this->eselonData as $iData) : ?>
																			<option value="<?=$iData->kode?>"><?=$iData->nama_eselon?></option>
																		<?php endforeach; ?>
																	</select>

																	<?php //var_dump($this->jabatanData);?>
																</div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" id="btn_jabatan" class="btn btn-primary" onClick="tambah_transaksi('eselon')">Tambah</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRumpunJabatanHistory" tabindex="-1" role="dialog" aria-labelledby="modalRumpunJabatanHistory" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Tambah Rumpun Jabatan</h4>
            </div>
            <div class="modal-body">
                <form id="form_rumpun_jabatan" name="form_rumpun_jabatan">
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
																<label class="control-label col-sm-3" >Per Tanggal :</label>
                                <div class="col-sm-9">
                                    <input type="input" class="form-control datePickerLoss" data-date-format='dd/mm/yyyy' id="TGL_MULAI"  name="TGL_MULAI">
                                </div>
                            </div>

                            <div class="form-group">
																<label class="control-label col-sm-3" >Rumpun Jabatan :</label>
																<div class="col-sm-9">
																	<select class="form-control select2 required" id="ID_RUMPUN_JABATAN" name="ID_RUMPUN_JABATAN" data-placeholder="Pilih Rumpun Jabatan" >
																		<option></option>
																		<?php foreach($this->rumpunJabatanData as $iData) : ?>
																			<option value="<?=$iData->id?>"><?=$iData->nama?></option>
																		<?php endforeach; ?>
																	</select>

																	<?php //var_dump($this->jabatanData);?>
																</div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" id="btn_jabatan" class="btn btn-primary" onClick="tambah_transaksi('rumpun_jabatan')">Tambah</button>
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
<!-- Modal Hapus -->
<div class="modal fade" id="hapus" tabindex="-1" role="dialog" aria-labelledby="hapus" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
				<h4 class="modal-title custom_align" id="Heading">Hapus data</h4>
			</div>
			<div id="hapus-body" class="modal-body">
			</div>
			<div id="hapus-footer" class="modal-footer ">
				<button id="hapus-footer-ya" type="button" class="btn btn-success"><span class="glyphicon glyphicon-ok-sign"></span> Ya</button>
				<button id="hapus-footer-tidak" type="button" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span> Tidak</button>
			</div>
		</div>
	</div>
	<!-- /.modal-content -->
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

	function hapus_role(el, id){
		var currentRow = $(el).closest("tr");
		var nama = currentRow.find("td:eq(1)").text();
		var hapus = 0;
		var isi = "<span class='glyphicon glyphicon-warning-sign'></span> Apakah anda yakin untuk menghapus data role " + nama + " <font style='color:red;'>(Data akan langsung dihapus dari database)</font> ?";
		$('#hapus #hapus-body').empty();
		$('#hapus #hapus-body').html(isi);
		$('#hapus').modal('show');
		$("#hapus #hapus-footer #hapus-footer-ya").unbind().click(function(e){
			e.preventDefault();
			hapus = 1;
			$('#hapus').modal('hide');
			$.ajax({
				url:'<?=base_url()."".$this->uri->segment(1)."/";?>delete_role',
				dataType:'json',
				type:'POST',
				data: {
					id : id
				},
				success: function(data){
					$('#pesan_isi').html(data.pesan);
					$('#pesan_modal').modal('show');
					if(data.status){
						$("#pesan_modal #pesan_modal-ok").unbind().click(function(e){
							e.preventDefault();
							$('#pesan_modal').modal('hide');
							$(el).closest("tr").remove();
						});
					}
				}
			})
		});
		$("#hapus #hapus-footer #hapus-footer-tidak").unbind().click(function(e){
			e.preventDefault();
			$('#hapus').modal('hide');
		});
	}

	function hapus_unor(el, id_instansi, id_unor){
		var currentRow = $(el).closest("tr");
		var nama_instansi = currentRow.find("td:eq(1)").text();
		var nama_unor = currentRow.find("td:eq(2)").text();
		var hapus = 0;
		var isi = "<span class='glyphicon glyphicon-warning-sign'></span> Apakah anda yakin untuk menghapus data unit kerja " + nama_unor + " dari instansi "+ nama_instansi +" <font style='color:red;'>(Data akan langsung dihapus dari database)</font> ?";
		$('#hapus #hapus-body').empty();
		$('#hapus #hapus-body').html(isi);
		$('#hapus').modal('show');
		$("#hapus #hapus-footer #hapus-footer-ya").unbind().click(function(e){
			e.preventDefault();
			hapus = 1;
			$('#hapus').modal('hide');
			$.ajax({
				url:'<?=base_url()."".$this->uri->segment(1)."/";?>delete_unor',
				dataType:'html',
				type:'POST',
				data: {
					id_instansi : id_instansi,
					id_unor : id_unor
				},
				success: function(data){
					$('#pesan_isi').html(data.pesan);
					$('#pesan_modal').modal('show');
					if(data.status){
						//$("#pesan_modal #pesan_modal-ok").unbind().click(function(e){
						//	e.preventDefault();
						//	$('#pesan_modal').modal('hide');
						//	$(el).closest("tr").remove();
						//});


					}
					location.reload();
				}
			})
		});
		$("#hapus #hapus-footer #hapus-footer-tidak").unbind().click(function(e){
			e.preventDefault();
			$('#hapus').modal('hide');
		});
	}


	function tambah_transaksi(uerel){
		$.ajax({
			url: base_url + 'master_pegawai/'+ uerel +'_insert?id_pegawai=<?php echo $this->oldData->id_pegawai; ?>',
			type:'POST',
			dataType:'json',
			data: $('#form_'+uerel).serialize(),
			success: function(data){
				if( data.status ){
					location.reload();
				}
				else{
					alert('Gagal Simpan');
				}
			},
			error : function(data) {
				// alert('Gagal Simpan Ajax');
				//$('#pesan_error').html( '<h3>Error Response : </h3><br>'+JSON.stringify( data ));
				alert('Berhasil ditambahkan');
				location.reload();
			}
		})
	}

</script>
