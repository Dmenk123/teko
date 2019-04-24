
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
					<form method="post" action="<?=current_url()?>" class="form-horizontal">
						<label class="control-label col-sm-1" >Instansi :</label>
						<div class="col-sm-6">
							<select class="form-control select2" name="instansi" data-placeholder="Pilih Instansi" onChange="this.form.submit()">
								<option></option>
							<?php foreach($this->instansiData as $iData) : ?>
								<option value="<?=$iData->kode?>" <?php if($this->instansi_post == $iData->kode) { echo 'selected'; } ?>><?=$iData->nama?></option>
							<?php endforeach; ?>
							</select>
						</div>
						<div class="col-sm-5">
							<div class="input-group">
								<input type="text" class="form-control" name="keyword" placeholder="Masukkan Kata Kunci" value="<?php echo $this->keyword_post; ?>">
								<div class="input-group-btn">
									<button id="btn-submit"class="btn btn-default" type="submit">
									<i class="glyphicon glyphicon-search"></i>
									</button>
								</div>
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
                <th>Nama</th>
                <th>Eselon</th>
                <th class="col-md-1">
                    <?php if($this->jumlahData > 0) { echo $this->template_view->getAddButton(); } ?>
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
					<td ><?php echo $showData->nama_eselon; ?></td>
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