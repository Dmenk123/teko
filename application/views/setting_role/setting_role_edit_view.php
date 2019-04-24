
<!-- Content Header (Page header) -->


<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		Ubah Data <?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<form class="form-horizontal" id="form_standar" action="<?=base_url()."".$this->uri->segment(1)."/".$this->uri->segment(2);?>_data">
			<input type="hidden" value="<?php echo $this->oldData->id_kategori_user;?>" name="ID_KATEGORI_USER">

			<div class="form-group">
				<label class="control-label col-sm-3">Role User :</label>
				<div class="col-sm-6">
					<input type="input" class="form-control required" id="NAMA_KATEGORI_USER" value="<?php echo $this->oldData->nama_kategori_user;?>" name="NAMA_KATEGORI_USER">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-3">Keterangan : <br>jika diperlukan *</label>
				<div class="col-sm-6">
					<textarea class="form-control" id="KETERANGAN" name="KETERANGAN"><?php echo $this->oldData->keterangan;?></textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6 col-sm-offset-3 "><hr></div>

			</div>
			<div class="form-group">
				<?php echo $this->checkboxMenu; ?>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<img src="<?php echo base_url();?>assets/img/loading.gif" id="loading" style="display:none">
					<p id="pesan_error" style="display:none" class="text-warning" style="display:none"></p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Ubah Data</button>
					<a href="<?=base_url()."".$this->uri->segment(1);?>">
						<span class="btn btn-warning"><i class="glyphicon glyphicon-remove"></i> Batal</span>
					</a>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- /.content -->
