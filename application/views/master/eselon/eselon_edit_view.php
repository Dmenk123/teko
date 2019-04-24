

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
					<input type="hidden" class="form-control required" id="KODE_LAMA"  name="KODE_LAMA" value="<?=$this->oldData->kode;?>">
					<input type="input" class="form-control required" id="KODE"  name="KODE" value="<?=$this->oldData->kode;?>">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Nama Eselon :</label>
				<div class="col-sm-4">
					<input type="input" class="form-control" id="NAMA_ESELON"  name="NAMA_ESELON" value="<?=$this->oldData->nama_eselon;?>">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Tunjangan :</label>
				<div class="col-sm-4">
					<input type="number" min=0 step=0.1 class="form-control" id="TUNJANGAN"  name="TUNJANGAN" value="<?=$this->oldData->tunjangan;?>">
				</div>
			</div>
			<!--<div class="form-group">
				<label class="control-label col-sm-4" >Kode Golongan Max :</label>
				<div class="col-sm-4">
					<input type="input" class="form-control" id="KODE_GOL_MAX"  name="KODE_GOL_MAX" value="<?=$this->oldData->kode_gol_max;?>">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-4" >Kode Golongan Min :</label>
				<div class="col-sm-4">
					<input type="input" class="form-control" id="KODE_GOL_MIN"  name="KODE_GOL_MIN" value="<?=$this->oldData->kode_gol_min;?>">
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-sm-4" >Urut :</label>
				<div class="col-sm-4">
					<input type="input" class="form-control" id="URUT"  name="URUT" value="<?=$this->oldData->urut;?>">
				</div>
			</div>-->

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
