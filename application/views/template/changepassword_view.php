

<!-- Content Header (Page header) -->
<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		Ubah Data Password
		</div>

	</div>
	<div class="portlet-body">
		<form class="form-horizontal" id="form_standar" action="<?=base_url()."".$this->uri->segment(1);?>/prosess">
			<div class="form-group">
				<label class="control-label col-sm-4" >Password Lama :</label>
				<div class="col-sm-4">
					<input type="password" class="form-control required" id="lama"  name="lama">
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-sm-4" >Password baru :</label>
				<div class="col-sm-4">
					<input type="password" class="form-control required" id="PASSWORD"  name="PASSWORD">
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-label col-sm-4" >Ulangi Password baru :</label>
				<div class="col-sm-4">
					<input type="password" class="form-control required" id="REPASS"  name="REPASS">
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
