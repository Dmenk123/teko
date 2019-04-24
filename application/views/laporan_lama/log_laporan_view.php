

<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">
			<form class="form-horizontal" id="form_standar" action="<?=base_url()."".$this->uri->segment(1)?>/save"  method="get">
				<div class="form-group">
					<label class="control-label col-sm-2" >Bulan :</label>
					<div class="col-sm-2">
						<select type="input" class="form-control select2" required id="bulan"  name="bulan">
							<option value="">Silahkan Pilih</option>
							<option <?php if($this->input->get('bulan') == '01') echo "selected";?> value="01">JANUARI</option>
							<option <?php if($this->input->get('bulan') == '02') echo "selected";?> value="02">FEBRUARI</option>
							<option <?php if($this->input->get('bulan') == '03') echo "selected";?> value="03">MARET</option>
							<option <?php if($this->input->get('bulan') == '04') echo "selected";?> value="04">APRIL</option>
							<option <?php if($this->input->get('bulan') == '05') echo "selected";?> value="05">MEI</option>
							<option <?php if($this->input->get('bulan') == '06') echo "selected";?> value="06">JUNI</option>
							<option <?php if($this->input->get('bulan') == '07') echo "selected";?> value="07">JULI</option>
							<option <?php if($this->input->get('bulan') == '08') echo "selected";?> value="08">AGUSTUS</option>
							<option <?php if($this->input->get('bulan') == '09') echo "selected";?> value="09">SEPTEMBER</option>
							<option <?php if($this->input->get('bulan') == '10') echo "selected";?> value="10">OKTOBER</option>
							<option <?php if($this->input->get('bulan') == '11') echo "selected";?> value="11">NOVEMBER</option>
							<option <?php if($this->input->get('bulan') == '12') echo "selected";?> value="12">DESEMBER</option>
						</select>
					</div>
					<label class="control-label col-sm-2" >Tahun :</label>
					<div class="col-sm-2">
						<select type="input" class="form-control select2" required id="tahun"  name="tahun">
							<option value="">Silahkan Pilih</option>
								<?php
								$tahundepan = date('Y') + 1;
								for($i = 2018;$i <= $tahundepan;$i++){
								?>
							<option <?php if($this->input->get('tahun') == $i) echo "selected";?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
								<?php
								}
								?>
						</select>
					</div>
				</div>
				<?php
				if($this->session->userdata('id_kategori_karyawan')!='4' ){
				?>
				<div class="form-group">
					<label class="control-label col-sm-2" >Instansi :</label>
					<div class="col-sm-6">
						<select type="input" class="form-control select2"  required id="id_instansi"  name="id_instansi">
							<option value="">Silahkan Pilih</option>
							<?php
							foreach($this->dataInstansi as $data){
							 ?>
							<option <?php if($this->input->get('id_instansi') == $data->kode) echo "selected";?> value="<?php echo $data->kode; ?>"><?php echo $data->nama; ?></option>
							<?php
							}
						 	?>
						</select>
					</div>
				</div>
				<?php
				}
				?>
				<div class="form-group">
						<label class="control-label col-sm-2" ></label>
						<div class="col-sm-8">
							<img src="<?=base_url();?>assets/img/loading.gif" id="loading" style="display:none">
							<span id="pesan_error"></span>
						</div>
					</div>
				<div class="form-group">
					<label class="control-label col-sm-2" ></label>
					<div class="col-sm-6">
						
						<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>



<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
			Data Kunci Laporan
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">
			<table class="table table-bordered">
				<thead>
				  <tr>
					<th>No</th>
					<th>Nama Instansi</th>
					<th>Bulan</th>
					<th>Tahun</th>
					<th>Tanggal Kunci</th>
					
				  </tr>
				</thead>
				<tbody>
					<?php
					$no = $this->input->get('per_page')+ 1;
					foreach($this->dataLog as $showData ){
						//var_dump($showData);
					?>
					<tr>
						<td align="center"><?php echo $no; ?>.</td>
						<td ><?php echo $showData->nama; ?></td>
						<td ><?php echo $this->bulan[$showData->bulan]; ?></td>
						<td ><?php echo $showData->tahun; ?></td>
						<td ><?php echo $showData->time_stamp_indo; ?></td>
					</tr>
					<?php
					$no++;
					}
					if(!$this->dataLog){
						echo "<tr><td colspan='25' align='center'>Data tidak ada.</td></tr>";
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

