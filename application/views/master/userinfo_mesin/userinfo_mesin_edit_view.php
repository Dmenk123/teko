
<!-- Content Header (Page header) -->
<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		Mapping <?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="col-sm-12">
				<form class="form-horizontal" method="get">
					<!-- <div class="form-group">
						<div class="form-group">
							<?php
							$user_id = $user_name = $nip_pegawai = "-";
							if($this->data->user_id)
							{ $user_id = $this->data->user_id; }
							if($this->data->user_name)
							{ $user_name = $this->data->user_name; }
							if($this->data->nip_pegawai)
							{ $nip_pegawai = $this->data->nip_pegawai; }
							?>	
							<label class="col-sm-1" align="right">User ID</label>
							<label class="col-sm-2">: <?php echo $user_id; ?></label>	
							<label class="col-sm-2" align="right">Nama User</label>
							<label class="col-sm-5">: <?php echo $user_name; ?></label>
						</div>
						<div class="row">
							<label class="col-sm-1" align="right">NIP</label>
							<label class="col-sm-5">: <?php echo $nip_pegawai; ?></label>	
						</div>	
						<br>			
					</div> -->
					<h3>Lookup Pegawai</h3>
					<hr>
					<div class="form-group">
						<div class="col-sm-12">
							<div class="input-group">
								<input type="text" class="form-control" name="keyword" placeholder="Masukkan NIP/Nama Pegawai" value="<?php echo $this->input->get('keyword'); ?>">
								<div class="input-group-btn">
									<button class="btn btn-default" type="submit">
										<i class="glyphicon glyphicon-search"></i>
									</button>
								</div>
							</div>
						</div>
					</div>
				</form>
				<table class="table table-bordered table-hover table-striped">
		            <thead>
		              	<tr>
			                <th width="10%">NIP</th>
			                <th width="30%">Nama</th>
			                <th width="20%">Jabatan</th>
			                <th width="20%">Instansi</th>
			                <th width="20%">Status</th>
		              	</tr>
		            </thead>
		            <tbody>
		            	<?php
							foreach($this->data_pegawai as $data )
							{
							?>
						<tr>
							<td><?php echo $data->nip; ?></td>
							<td><?php echo $data->nama; ?></td>
							<td><?php echo $data->nama_jabatan; ?></td>
							<td><?php echo $data->nama_instansi; ?></td>
							<td style="position: relative;"><?php echo $data->nama_status; ?>
								<form class="form-horizontal" action="<?=base_url()."".$this->uri->segment(1)."/".$this->uri->segment(2);?>_data" method="post">
									<input type="hidden" name="id_mesin_user" value="<?=$this->data->id;?>">
									<input type="hidden" name="id_pegawai" value="<?=$data->id;?>">
									<input type="hidden" name="user_id" value="<?=$this->data->user_id;?>">
									<input type="hidden" name="id_mesin" value="<?=$this->data->id_mesin;?>">
									<button class="btn btn-info btn-sm" style="position: absolute; right: 0px; top: 3px;" type="submit">
										<i class="glyphicon glyphicon-search"></i> Map
									</button>
								</form>
							</td>
						</tr>
						<?php
						}
						?>
		            </tbody>
		        </table>
		        <center>
					<?php echo $this->pagination->create_links();?>
					<br>
					<span class="btn btn-default">Jumlah Data : <b><?php echo $this->jumlahData;?></b></span>
				</center>
			</div>
		</div>
	</div>
</div>
<!-- /.content -->
