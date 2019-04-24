

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
				<div class="col-sm-2">
					<input type="input" class="form-control required" id="KODE"  name="KODE">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Label :</label>
				<div class="col-sm-2">
					<input type="input" class="form-control required" id="LABEL"  name="LABEL">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Nama :</label>
				<div class="col-sm-4">
					<input type="input" class="form-control required" id="NAMA"  name="NAMA">
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-4" >status :</label>
				<div class="col-sm-2">
					<select type="input" class="form-control required" id="STATUS"  name="STATUS">
						<option value="">Silahkan Pilih</option>
						<option value="2">LIBUR</option>
						<option value="1">MASUK</option>
						<option value="3">STAND BY</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Jam kerja :</label>
				<div class="col-sm-4">
					<select type="input" class="form-control required" id="ID_JAM_KERJA"  name="ID_JAM_KERJA">
						<option value="">Silahkan Pilih</option>
						<?php
						foreach($this->dataJamKerja as $data){
						?>

						<option value="<?php echo $data->id;?>"><?php echo $data->nama;?></option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4"> Keterangan :</label>
				<div class="col-sm-4">
					<textarea class="form-control" id="KETERANGAN"  name="KETERANGAN"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Urut :</label>
				<div class="col-sm-1">
					<input type="input" class="form-control number" id="URUT"  name="URUT">
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
