
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
        <table class="table table-bordered">
            <thead>
              <tr>
                <th width="5%">No.</th>
                <th>Nama</th>
                <th>Jam Datang</th>
                <th>Datang H-1	</th>
                <th>Jam Pulang	</th>
                <th>Pulang H+1	</th>
                <th>Toleransi Terlambat	</th>
                <th>Toleransi Pul. Cpt</th>
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
					<td ><?php echo $showData->nama; ?></td>
					<td ><?php echo $showData->jam_masuk; ?></td>
					<td ><?php echo ($showData->masuk_hari_sebelumnya == 't' ? "✓" : "✘"); ?></td>
					<td ><?php echo $showData->jam_pulang; ?></td>

					<td ><?php echo ($showData->pulang_hari_berikutnya == 't' ? "✓" : "✘"); ?></td>
					<td ><?php echo $showData->toleransi_terlambat; ?></td>
					<td ><?php echo $showData->toleransi_pulang_cepat; ?></td>

					<!--<td ><?php echo ($showData->aktif == 'Y' ? "Ya" : "Tidak"); ?></td>-->
					<td align="center">
						<?php
						////// cara ambil Button Edit ( link edit )
						echo $this->template_view->getEditButton(base_url().$this->uri->segment(1)."/edit/".$showData->id);
						?>
						&nbsp;
						<?php
						////// cara ambil Button Delete (pesan yang ingin ditampilkan, link Delete)
						echo $this->template_view->getDeleteButton($showData->nama,base_url().$this->uri->segment(1)."/delete/".$showData->id);
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
