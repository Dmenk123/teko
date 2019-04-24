

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
				<label class="control-label col-sm-4" >Nama Menu :</label>
				<div class="col-sm-4">
					<input type="input" class="form-control required" id="NAMA_MENU"  name="NAMA_MENU">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Judul Menu :</label>
				<div class="col-sm-4">
					<input type="input" class="form-control" id="JUDUL_MENU"  name="JUDUL_MENU">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Link Menu :</label>
				<div class="col-sm-4">
					<input type="input" class="form-control" id="LINK_MENU"  name="LINK_MENU">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Icon Menu :</label>
				<div class="col-sm-4">
					<input type="input" class="form-control" id="ICON_MENU"  name="ICON_MENU">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Tingkat Menu :</label>
				<div class="col-sm-4">
					<input type="input" class="form-control required" id="TINGKAT_MENU"  name="TINGKAT_MENU">
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-4" >Urutan Menu :</label>
				<div class="col-sm-4">
					<input type="input" class="form-control required" id="URUTAN_MENU"  name="URUTAN_MENU">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Aktif Menu :</label>
				<div class="col-sm-4">
					<select class="form-control  required" name="AKTIF_MENU">
						<option value="Y">Ya </option>
						<option value="N">Tidak </option>

					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-4" >Add Button :</label>
				<div class="col-sm-4">
					<select class="form-control required" name="ADD_BUTTON">
						<option value="Y">Ya </option>
						<option value="N">Tidak </option>

					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-4" >Edit Button :</label>
				<div class="col-sm-4">
					<select class="form-control required" name="EDIT_BUTTON">
						<option value="Y">Ya </option>
						<option value="N">Tidak </option>

					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-4" >Delete Button :</label>
				<div class="col-sm-4">
					<select class="form-control required" name="DELETE_BUTTON">
						<option value="Y">Ya </option>
						<option value="N">Tidak </option>

					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" for="email">Parent Menu :</label>
				<div class="col-sm-4">
					<select class="form-control required" name="ID_PARENT">
						<option value="0">Jenis Pertama </option>
						<?php
						foreach($this->dataMenu as $kat_user){
						?>
						<option value="<?php echo $kat_user->id_menu ?>"><?php echo $kat_user->nama_menu ?></option>
						<?php
						}
						?>
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