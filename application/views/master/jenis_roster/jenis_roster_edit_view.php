
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
						<input type="input" class="form-control required" value="<?php echo $this->oldData->kode; ?>" id="KODE"  name="KODE">
						<input type="hidden" class="form-control required" value="<?php echo $this->oldData->id; ?>" id="ID"  name="ID">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4" >Label :</label>
					<div class="col-sm-2">
						<input type="input" class="form-control required" value="<?php echo $this->oldData->label; ?>" id="LABEL"  name="LABEL">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4" >Nama :</label>
					<div class="col-sm-4">
						<input type="input" class="form-control required" value="<?php echo $this->oldData->nama; ?>" id="NAMA"  name="NAMA">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-4" >status :</label>
					<div class="col-sm-2">
						<select type="input" class="form-control required" id="STATUS"  name="STATUS">
							<option value="">Silahkan Pilih</option>
							<option   <?php if($this->oldData->status == '2') echo "selected"; ?> value="2">LIBUR</option>
							<option  <?php if($this->oldData->status == '1') echo "selected"; ?> value="1">MASUK</option>
							<option  <?php if($this->oldData->status == '3') echo "selected"; ?> value="3">STAND BY</option>
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

							<option <?php if($data->id == $this->oldData->id_jam_kerja) echo "selected"; ?> value="<?php echo $data->id;?>"><?php echo $data->nama;?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4"> Keterangan :</label>
					<div class="col-sm-4">
						<textarea class="form-control" id="KETERANGAN" value="" name="KETERANGAN"><?php echo $this->oldData->keterangan; ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4" >Urut :</label>
					<div class="col-sm-1">
						<input type="input" class="form-control number" value="<?php echo $this->oldData->urut; ?>" id="URUT"  name="URUT">
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
