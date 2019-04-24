

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
				<label class="control-label col-sm-4" >Nama :</label>
				<div class="col-sm-5">
					<input type="input" class="form-control required" id="nama"  name="nama" placeholder="Nama">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Status :</label>
				<div class="col-sm-5" align="left">
					<label class="checkbox-inline">
				      	<input type="checkbox" value="true" id="aktif" name="aktif">Aktif
				    </label>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Jenis Mesin :</label>
				<div class="col-sm-5">
					<select class="form-control select2 required" id="id_jenis_mesin" name="id_jenis_mesin" data-placeholder="Pilih Jenis Mesin">
						<option></option>
					<?php foreach($this->jenis_mesin_data as $data) : ?>
						<option value="<?=$data->id?>"><?=$data->nama?></option>
					<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Area Mesin :</label>
				<div class="col-sm-5">
					<select class="form-control select2 required" id="kode_area_mesin" name="kode_area_mesin" data-placeholder="Pilih Area Mesin">
						<option></option>
					<?php foreach($this->area_mesin_data as $data) : ?>
						<option value="<?=$data->kode?>"><?=$data->nama?></option>
					<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Instansi :</label>
				<div class="col-sm-5">
					<select class="form-control select2 required" id="kode_instansi" name="kode_instansi" data-placeholder="Pilih Instansi">
						<option></option>
					<?php foreach($this->instansi_data as $data) : ?>
						<option value="<?=$data->kode?>"><?=$data->nama?></option>
					<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >IP Address :</label>
				<div class="col-sm-5">
					<input type="input" class="form-control required" id="ip_address" name="ip_address" placeholder="Ip Address">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Password :</label>
				<div class="col-sm-5">
					<input type="password" class="form-control required" id="password" name="password" placeholder="Password">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Port :</label>
				<div class="col-sm-5">
					<input type="input" class="form-control required" id="port" name="port" placeholder="Port">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" ></label>
				<div class="col-sm-5" align="left">
					<label class="checkbox-inline">
				      	<input type="checkbox" value="true" id="hapus_log" name="hapus_log">Hapus log setelah download
				    </label>
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
