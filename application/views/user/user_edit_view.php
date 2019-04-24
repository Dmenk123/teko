
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
					<label class="control-label col-sm-4" >Nama User :</label>
					<div class="col-sm-4">
						<input type="hidden" id="ID" name="ID" value="<?php echo $this->oldData->id; ?>">
						<input type="input" class="form-control required" value="<?php echo $this->oldData->fullname; ?>" id="FULLNAME" name="FULLNAME">
					</div>
				</div>	
				<div class="form-group">
					<label class="control-label col-sm-4" for="email">Role User :</label>
					<div class="col-sm-4">
						<select class="form-control"  name="ID_KATEGORI_USER">
							<option value="">Silahkan Pilih</option>
							<?php 
							foreach($this->dataKategoriUser as $kat_user){
							?>
							<option <?php if($this->oldData->id_kategori_user == $kat_user->id_kategori_user) echo "selected"; ?> value="<?php echo $kat_user->id_kategori_user ?>"><?php echo $kat_user->nama_kategori_user ?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4" for="email">OPD :</label>
					<div class="col-sm-4">
						<select class="form-control" name="OPD">
							<option value="">Silahkan Pilih</option>
							<?php 
							foreach($instansi as $opd){
								$selected = ($this->oldData->kode_instansi == $opd->kode)?'selected':'';
								echo '<option value="'.$opd->kode.'" '.$selected.'>'.$opd->nama.'</option>';
							}
							?>
						</select>
					</div>
				</div>		
				<div class="form-group">
					<label class="control-label col-sm-4" >Username :</label>
					<div class="col-sm-3">
						<input type="input" value="<?php echo $this->oldData->username; ?>" class="form-control required" id="USERNAME" name="USERNAME">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4" >Password :</label>
					<div class="col-sm-2">
						<input type="password" value="<?php echo $this->encrypt_decrypt->dec_enc('decrypt',$this->oldData->password_new); ?>" class="form-control required" id="PASSWORD" name="PASSWORD">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4" >Ulangi Password :</label>
					<div class="col-sm-2">
						<input type="password" value="<?php echo $this->encrypt_decrypt->dec_enc('decrypt',$this->oldData->password_new); ?>" class="form-control required" id="REPASS" name="REPASS">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4" for="email">Aktif :</label>
					<div class="col-sm-2">
						<select name="ACTIVE" class="form-control required" >
							<option value="">pilih</option>
							<option <?php if($this->oldData->active == 't'){echo "selected";} ?> value="t">Ya</option>
							<option <?php if($this->oldData->active == 'f'){echo "selected";} ?> value="f">Tidak</option>
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
							<span class="btn btn-warning"><i class="fa fa-remove"></i> Batal</span>
						</a>
					</div>
				</div>
			</form>
		
	</div>
</div>
<!-- /.content -->
  
