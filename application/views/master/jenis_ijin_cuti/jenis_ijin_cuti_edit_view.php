
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
						<input type="input" class="form-control required" value="<?php echo $this->oldData->kode; ?>" id="KODE"  name="KODE">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4" >Jumlah :</label>
					<div class="col-sm-2">
						<input type="input" class="form-control number" value="<?php echo $this->oldData->jumlah; ?>" id="JUMLAH"  name="JUMLAH">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4" >Nama Hari Libur :</label>
					<div class="col-sm-4">
						<input type="hidden" id="ID" name="ID" value="<?php echo $this->oldData->id; ?>">
						<input type="input" class="form-control required" value="<?php echo $this->oldData->nama; ?>" id="NAMA" name="NAMA">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4"> Keterangan :</label>
					<div class="col-sm-4">
						<textarea class="form-control required" id="KETERANGAN"  name="KETERANGAN"><?php echo $this->oldData->keterangan; ?></textarea>
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
