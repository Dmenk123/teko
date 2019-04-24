
<!-- Content Header (Page header) -->
<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<i class="fa fa-institution"></i> <?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">

				<div class="col-sm-12">
					<form method="get">
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
					</form>
				</div>
		</div>
		<br>
		<div class="row">
			<div class="col-sm-12">
        <table class="table table-bordered">
            <thead>
              <tr>
								<th>No</th>
								<th>Kode</th>
                <th>Nama Eselon</th>
                <th>Tunjangan</th>
                <th class="col-md-1">
                    <?php echo $this->template_view->getAddButton(); ?>
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
					<td ><?php echo $showData->nama_eselon; ?></td>
					<td ><?php echo $showData->tunjangan; ?></td>
					<td align="center">
						<?php
						////// cara ambil Button Edit ( link edit )
						echo $this->template_view->getEditButton(base_url().$this->uri->segment(1)."/edit/".$showData->kode);
						?>
						&nbsp;
						<?php
						////// cara ambil Button Delete (pesan yang ingin ditampilkan, link Delete)
						echo $this->template_view->getDeleteButton($showData->nama_eselon,base_url().$this->uri->segment(1)."/delete/".$showData->kode);
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
