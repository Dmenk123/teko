
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
					<label class="control-label col-sm-3">Role User :</label>
					<div class="col-sm-6">
						<input type="input" class="form-control required" id="NAMA_KATEGORI_USER" name="NAMA_KATEGORI_USER">
					</div>
				</div>	
				<div class="form-group">
					<label class="control-label col-sm-3">Keterangan : <br>jika diperlukan *</label>
					<div class="col-sm-6">
						<textarea class="form-control" id="KETERANGAN" name="KETERANGAN"></textarea>
					</div>
				</div>	
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<img src="<?php echo base_url();?>assets/img/loading.gif" id="loading" style="display:none">
						<p id="pesan_error" style="display:none" class="text-warning" style="display:none"></p>
					</div>
				</div>			
				<div class="form-group">        
					<div class="col-sm-offset-3 col-sm-9">
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
  
