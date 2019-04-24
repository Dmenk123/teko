
<!-- Content Header (Page header) -->
<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">
				<div class="col-sm-2">

				</div>
				<div class="col-sm-2">
				</div>
				<div class="col-sm-8">
					<div class="row">
						<form method="get">
						<div class="col-sm-4 col-md-offset-2">
							<select class="form-control" name="field">
								<option <?php if($this->input->get('field')=='nama') echo "selected"; ?> value="nama">Berdasarkan Nama</option>
								<option <?php if($this->input->get('field')=='kode') echo "selected"; ?> value="kode">Berdasarkan Kode</option>
							</select>
						</div>
						<div class="col-sm-6">
								<div class="input-group">
									<input type="text" class="form-control" name="keyword" placeholder="Masukkan Kata Kunci" value="<?php echo $this->input->get('keyword'); ?>">
									<div class="input-group-btn">
										<button class="btn btn-default" type="submit">
										<i class="glyphicon glyphicon-search"></i>
										</button>
										<?php if($this->input->get('field')){ ?>
										<a href="<?=base_url();?><?php echo $this->uri->segment(1);?>">
											<span class="btn btn-success"><i class="glyphicon glyphicon-refresh"></i></span>
										</a>
										<?php } ?>
									</div>
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
                <th width="5%">No.</th>
                <th width="5%">Kode</th>
                <th>Nama</th>
                <th class="center" width="10%">
									<?php
									//// cara ambil button Add
									echo $this->template_view->getAddButton();
									?>
								</th>

              </tr>
            </thead>
            <tbody>
				<?php
				$no = $this->input->get('per_page')+ 1;
				foreach($this->showData as $showData ){
					//var_dump($showData);
				?>
				<tr>

					<td align="center"><?php echo $no; ?>.</td>
					<td ><?php echo $showData->kode; ?></td>
					<td ><?php echo $showData->nama; ?></td>

					<!--<td ><?php echo ($showData->aktif == 'Y' ? "Ya" : "Tidak"); ?></td>-->
					<td align="center">
						<?php
						////// cara ambil Button Edit ( link edit )
						echo $this->template_view->getEditButton(base_url().$this->uri->segment(1)."/edit/".$showData->kode);
						?>
						&nbsp;
						<?php
						////// cara ambil Button Delete (pesan yang ingin ditampilkan, link Delete)
						echo $this->template_view->getDeleteButton($showData->nama,base_url().$this->uri->segment(1)."/delete/".$showData->kode);
						?>
					</td>
				</tr>
				<?php
				$no++;
				}
				if(!$this->showData){
					echo "<tr><td colspan='25' align='center'>Data tidak ada.</td></tr>";
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
