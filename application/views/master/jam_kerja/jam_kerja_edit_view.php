

<!-- Content Header (Page header) -->
<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		Tambah Data <?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<form class="form-horizontal" id="form_standar" action="<?=base_url()."".$this->uri->segment(1)."/".$this->uri->segment(2);?>_data">
			<div class="form-group">
				<label class="control-label col-sm-2" >Nama Jam kerja :</label>
				<div class="col-sm-4">
					<input type="hidden" value="<?php echo $this->oldData->id; ?>"  class="form-control required" id="ID"  name="ID">
					<input type="input"  class="form-control required" id="NAMA" value="<?php echo $this->oldData->nama; ?>"  name="NAMA">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" >Jam Masuk :</label>
				<div class="col-sm-2">
					<input type="text" placeholder="contoh 13:45" value="<?php echo $this->oldData->jam_masuk; ?>" class="form-control required" id="JAM_MASUK"  name="JAM_MASUK">
				</div>
				<div class="col-sm-2">
					<div class="checkbox">
					  <label><input type="checkbox" value="t" <?php if($this->oldData->masuk_hari_sebelumnya=='t') echo "checked"; ?> name="MASUK_HARI_SEBELUMNYA"> Hari Sebelumnya</label>
					</div>
				</div>
				<label class="control-label col-sm-2" >Jam Pulang :</label>
				<div class="col-sm-2">
					<input type="input"  placeholder="contoh 13:45"  value="<?php echo $this->oldData->jam_pulang; ?>" class="form-control required" id="JAM_PULANG"  name="JAM_PULANG">
				</div>
				<div class="col-sm-2">
					<div class="checkbox">
						<label><input type="checkbox" value="t" name="PULANG_HARI_BERIKUTNYA" <?php if($this->oldData->pulang_hari_berikutnya=='t') echo "checked"; ?>> Hari Berikutnya</label>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2" >Toleransi Terlambat :</label>
				<div class="col-sm-1">
					<input type="input" placeholder="Menit" value="<?php echo $this->oldData->toleransi_terlambat; ?>" class="form-control required number" id="TOLERANSI_TERLAMBAT"  name="TOLERANSI_TERLAMBAT">
				</div>
				<label class="control-label col-sm-2" >Toleransi Pulang Cepat :</label>
				<div class="col-sm-1">
					<input type="input" placeholder="Menit"  value="<?php echo $this->oldData->toleransi_pulang_cepat; ?>" class="form-control required number" id="TOLERANSI_PULANG_CEPAT"  name="TOLERANSI_PULANG_CEPAT">
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2" >Jam Mulai Scan Masuk :</label>
				<div class="col-sm-2">
					<input type="input" placeholder="contoh 13:45" class="form-control required" value="<?php echo $this->oldData->jam_mulai_scan_masuk; ?>" id="JAM_MULAI_SCAN_MASUK"  name="JAM_MULAI_SCAN_MASUK">
				</div>
				<label class="control-label col-sm-2" >Jam Akhir Scan Masuk :</label>
				<div class="col-sm-2">
					<input type="input" placeholder="contoh 13:45" class="form-control required" value="<?php echo $this->oldData->jam_akhir_scan_masuk; ?>" id="JAM_AKHIR_SCAN_MASUK"  name="JAM_AKHIR_SCAN_MASUK">
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-2" >Jam Mulai Scan Pulang :</label>
				<div class="col-sm-2">
					<input type="input" placeholder="contoh 13:45" class="form-control required" value="<?php echo $this->oldData->jam_mulai_scan_pulang; ?>" id="JAM_MULAI_SCAN_PULANG"  name="JAM_MULAI_SCAN_PULANG">
				</div>
				<label class="control-label col-sm-2" >Jam Akhir Scan Pulang :</label>
				<div class="col-sm-2">
					<input type="input" placeholder="contoh 13:45" class="form-control required" value="<?php echo $this->oldData->jam_akhir_scan_pulang; ?>" id="JAM_AKHIR_SCAN_PULANG"  name="JAM_AKHIR_SCAN_PULANG">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" >Jumlah Hari Kerja :</label>
				<div class="col-sm-1">
					<input type="input" placeholder="" class="form-control required number" value="<?php echo $this->oldData->jml_hari_kerja; ?>" id="JML_HARI_KERJA"  name="JML_HARI_KERJA">
				</div>

			</div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<img src="<?php echo base_url();?>assets/img/loading.gif" id="loading" style="display:none">
					<p id="pesan_error" style="display:none" class="text-warning" style="display:none"></p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
					<a href="<?=base_url()."".$this->uri->segment(1);?>">
						<span class="btn btn-warning"><i class="glyphicon glyphicon-remove"></i> Batal</span>
					</a>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- /.content -->
