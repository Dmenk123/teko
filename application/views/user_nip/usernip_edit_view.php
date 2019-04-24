
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
				<label class="control-label col-sm-4" >Nama Pegawai :</label>
				<div class="col-sm-4">
					<input type="hidden" id="ID" name="ID" value="<?php echo $this->oldData->id; ?>">
					<input type="input" class="form-control required" value="<?php echo $this->oldData->nama; ?>" id="NAMA" name="NAMA" autocomplete="off" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >NIP User :</label>
				<div class="col-sm-4">
					<input type="input" class="form-control required" value="<?php echo $this->oldData->nip; ?>" id="NIP" name="NIP" autocomplete="off" readonly>
				</div>
			</div>		
			<div class="form-group">
				<label class="control-label col-sm-4" >Username :</label>
				<div class="col-sm-3">
					<input type="input" value="<?php echo $this->oldData->nip; ?>" class="form-control required" id="USERNAME" name="USERNAME" autocomplete="off" readonly>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Reset Password :</label>
				<div class="col-sm-6">
			      <label><input type="checkbox" value="t" name="RESET_PASS">Centang pilihan ini, maka password akan direset sesuai dengan NIP pegawai yang bersangkutan.</label>
			    </div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" for="email">Aktif :</label>
				<div class="col-sm-2">
					<select name="ACTIVE" class="form-control required" >
						<option value="">pilih</option>
						<option <?php if($this->oldData->aktif == 't'){echo "selected";} ?> value="t">Ya</option>
						<option <?php if($this->oldData->aktif == 'f'){echo "selected";} ?> value="f">Tidak</option>
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
					<button type="submit" class="btn btn-primary" id="btn_submit"><i class="fa fa-save"></i> Simpan</button>
					<a href="<?=base_url()."".$this->uri->segment(1);?>">
						<span class="btn btn-warning"><i class="fa fa-remove"></i> Batal</span>
					</a>
				</div>
			</div>
		</form>
	</div>
</div>
<!-- /.content -->
<script>
$(document).ready(function() {
	var isChecked = $('input[name="RESET_PASS"]:checked').length > 0;
	if (isChecked == false) {
		$('#btn_submit').attr('disabled', true);
	}else{
		$('#btn_submit').attr('disabled', false);
	}

	$('input[name="RESET_PASS"]').change(function() {
    if(this.checked) {
      $('#btn_submit').attr('disabled', false);
    }else{
			$('#btn_submit').attr('disabled', true);
		}
	});

	$('select[name="ACTIVE"]').change(function (e) { 
		e.preventDefault();
		var cek = $('input[name="RESET_PASS"]:checked').length > 0;
		if(cek == false){
			$('#btn_submit').attr('disabled', false);
		}
	});
});
</script>
  
