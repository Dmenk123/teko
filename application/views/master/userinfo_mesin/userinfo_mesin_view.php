
<!-- Content Header (Page header) -->
<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="row">
					<form class="form-horizontal" method="get">
						<div class="form-group">
							<label class="control-label col-sm-3" >Pilih Mesin</label>
							<div class="col-sm-5">
								<?php if ((int)$this->session->userdata('id_kategori_karyawan') > 2) { ?>
									<?php $opsi = "<option></option>"; ?>
								<?php }else{ ?>
									<?php $opsi = "<option value=''>Tampilkan Semua</option>"; ?>
								<?php } ?>
								<select class="form-control select2 required" id="id_mesin" name="id_mesin" data-placeholder="Pilih Mesin">
									<?php echo $opsi; ?>
									<?php
										foreach ($this->data_mesin as $data) 
										{
											echo '
									<option value="'.$data->id.'"
												';

											if(($this->input->get('id_mesin')) && ($data->id == $this->input->get('id_mesin')))
											{
												echo ' selected';
											}

											echo '>'.$data->nama.'
									</option>';
										}
									?>

								</select>
							</div>
							<div class="col-sm-4">
								<button class="btn btn-primary" type="submit">
									<i class="glyphicon glyphicon-search"></i> Tampilkan
								</button>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3" >ID/Nama User</label>
							<div class="col-sm-5">
								<input type="input" class="form-control required" id="user" name="user" placeholder="ID/Nama User" value="<?php echo $this->input->get('user'); ?>">
								<span><strong>catatan : Mohon kosongi ID/Nama User apabila ingin menampilkan semua.</strong></span>
							</div>
							<div class="col-sm-4">
								<?php if($this->input->get('id_mesin') || $this->input->get('user')){ ?>
								<a href="<?=base_url();?><?php echo $this->uri->segment(1);?>">
									<span class="btn btn-success"><i class="glyphicon glyphicon-refresh"></i></span>
								</a>
								<?php } ?>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-sm-12">
	        	<table class="table table-bordered table-hover table-striped">
		            <thead>
		              	<tr>
			                <th width="5%" rowspan="2" style="vertical-align: middle;">No.</th>
			              	<th colspan="3" style="text-align: center; border-bottom: 1px solid #E0E0E0;">Mesin</th>
			              	<th colspan="3" style="text-align: center; border-bottom: 1px solid #E0E0E0;">Master Pegawai</th>
			              	<th rowspan="2" style="vertical-align: middle;">Mapping Pegawai</th>
		              	</tr>
		              	<tr>
			                <th width="10%">User ID</th>
			                <th width="15%">Nama User</th>
			                <th width="10%">NIP</th>
			                <th width="15%">Nama Pegawai</th>
			                <th width="8%">SKPD</th>
			                <th width="15%">Unit Organisasi Kerja</th>
		              	</tr>
		            </thead>
		            <tbody>
						<?php
						$no = $this->input->get('per_page')+ 1;
						if(isset($this->showData))
						{
							foreach($this->showData as $showData )
							{
							//var_dump($showData);
						?>
						<tr>

							<td align="center"><?php echo $no; ?>.</td>
							<td ><?php echo $showData->user_id; ?></td>
							<td ><?php echo $showData->user_name; ?></td>
							<td ><?php echo $showData->nip_pegawai; ?></td>
							<td ><?php echo $showData->nama_pegawai; ?></td>
							<td ></td>
							<td ><?php echo $showData->nama_unor; ?></td>

							<td align="center">
								<form class="form-horizontal" action="<?=base_url()."".$this->uri->segment(1);?>/unmap" method="post">
								<a href="<?php echo base_url().$this->uri->segment(1)."/map/".$showData->id; ?>" class="btn btn-info btn-sm" role="button">
									<i class="glyphicon glyphicon-search"></i> Map
								</a>								
									<input type="hidden" name="id_mesin_user" value="<?=$showData->id;?>">
									<input type="hidden" name="id_mesin" value="<?=$showData->id_mesin;?>">
									<input type="hidden" name="user_id" value="<?=$showData->user_id;?>">
									<button class="btn btn-info btn-sm" type="submit">
										<i class="glyphicon glyphicon-unchecked"></i> Unmap
									</button>
								</form>

								<!-- <?php
								////// cara ambil Button Edit ( link edit )
								echo $this->template_view->getEditButton(base_url().$this->uri->segment(1)."/edit/".$showData->id);
								?>
								&nbsp;
								<?php
								////// cara ambil Button Delete (pesan yang ingin ditampilkan, link Delete)
								echo $this->template_view->getDeleteButton($showData->id,base_url().$this->uri->segment(1)."/delete/".$showData->id);
								?> -->
							</td>
						</tr>

						<?php
						$no++;

							}
						}
						?>
		            </tbody>
		        </table>
		        		<?php
						if(!isset($this->showData))
						{
							?>
							<div style="height: 35vh;"></div>
							<?php
						}
						?>
		        <center>
		        	<?php
		        		if(isset($this->showData))
						{
					   		echo $this->pagination->create_links().'
					<br>
					<span class="btn btn-default">Jumlah Data : <b>'.$this->jumlahData.'</b></span>';
						}
					?>
				</center>
	    	</div>
		</div>
	</div>	
</div>
<!-- /.content -->
