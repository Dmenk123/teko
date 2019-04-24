
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
				<label class="control-label col-sm-4" >Kode :</label>
				<div class="col-sm-4">
					<input type="hidden" class="form-control required" id="KODE_LAMA"  name="KODE_LAMA" value="<?=$this->oldData->kode;?>">
					<input type="input" class="form-control required" id="KODE"  name="KODE" value="<?=$this->oldData->kode;?>">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Nama Unit Organisasi Kerja :</label>
				<div class="col-sm-4">
					<input type="input" class="form-control required" id="NAMA"  name="NAMA" value="<?=$this->oldData->nama;?>">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Nomor Registrasi :</label>
				<div class="col-sm-4">
					<input type="input" class="form-control" id="NO_REGISTRASI"  name="NO_REGISTRASI" value="<?=$this->oldData->no_registrasi;?>">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >SRC Nama :</label>
				<div class="col-sm-4">
					<input type="input" class="form-control" id="SRC_NAMA"  name="SRC_NAMA" value="<?=$this->oldData->src_nama;?>">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Instansi :</label>
				<div class="col-sm-4">
					<select class="form-control select2" name="KODE_INSTANSI" data-placeholder="Pilih Instansi">
						<option></option>
					<?php foreach($this->instansiData as $iData) : ?>
						<option value="<?=$iData->kode?>" <?php if($this->oldData->kode_instansi == $iData->kode) { echo 'selected'; } ?>><?=$iData->nama?></option>
					<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Eselon :</label>
				<div class="col-sm-4">
					<select class="form-control select2" name="KODE_ESELON" data-placeholder="Pilih Eselon">
						<option></option>
					<?php foreach($this->eselonData as $eData) : ?>
						<option value="<?=$eData->kode?>" <?php if($this->oldData->kode_eselon == $eData->kode) { echo 'selected'; } ?>><?=$eData->nama_eselon?></option>
					<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Apakah SKPD :</label>
				<div class="col-sm-4">
					<?php if($this->oldData->kode_skpd == 1) { $kd_skpd = 1; } else { $kd_skpd = 0; } ?>
					<select class="form-control select2" name="KODE_SKPD">
						<option value="0" <?php if($kd_skpd == 0) { echo 'selected'; } ?>>Tidak</option>
						<option value="1" <?php if($kd_skpd == 1) { echo 'selected'; } ?>>YA</option>
					</select>
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

<!-- /.content -->
