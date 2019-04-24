

<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>
	</div>

	<div class="portlet-body">
		<div class="panel panel-success">
	      	<div class="panel-heading">
	      		<strong>Perhatian</strong>
	      	</div>
	      	<div class="panel-body">
	      		<p class="font-weight-bold">
	      			<span>Dimohon ketika akan melakukan penguncian telah dilakukan cek/update pada laporan <strong>SKOR | UANG MAKAN | LEMBUR</strong>. Terima Kasih.</span>
	      		</p>
	      	</div>
	    </div>
		<div class="">
			<form class="form-horizontal" id="form_standar" action="<?=base_url()."".$this->uri->segment(1)?>/save"  method="get" role="form">
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
								for($i = 2019;$i <= $tahundepan;$i++){
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
			Data Log Kunci Laporan
		</div>
	</div>

	<div class="portlet-body">
		<div class="">
			<table class="table table-bordered">
				<thead>
				  <tr>
					<th style="text-align: center;">No</th>
					<th>Nama Instansi</th>
					<th>Bulan</th>
					<th>Tahun</th>
					<th>Tanggal Kunci</th>
					<th>Tanggal Buka</th>
					<th>Status Kunci</th>
					<?php if(in_array($this->session->userdata('kategori_karyawan'), ['Administrator', 'Keuangan'])) { ?>
					<th>Aksi</th>
					<?php } ?>
				  </tr>
				</thead>
				<tbody>
					<?php $no = $this->input->get('per_page')+ 1; ?>
					<?php
						$status = [
							'Y' => 'Terkunci',
							'N' => 'Tidak',
						];
					?>

					<?php foreach($this->dataLog as $showData ){ ?>
						<tr>
							<td align="center"><?php echo $no; ?>.</td>
							<td ><?php echo $showData->nama; ?></td>
							<td ><?php echo $this->bulan[$showData->bulan]; ?></td>
							<td ><?php echo $showData->tahun; ?></td>
							<td ><?php echo date('d-m-Y H:i:s', strtotime($showData->time_stamp)); ?></td>
							<td ><?php echo $showData->time_stamp_buka ? date('d-m-Y H:i:s', strtotime($showData->time_stamp_buka)) : ''; ?></td>
							<td ><?php echo $status[$showData->is_kunci]; ?></td>
							<?php
								if(in_array($this->session->userdata('kategori_karyawan'), ['Administrator', 'Keuangan'])) {
									if($showData->is_kunci == 'Y') {
							?>
							<td>
								<?php $id = $showData->id_log_laporan;?>
								<!-- onclick="tampil_pesan_custom('Apakah anda yakin akan','Buka Kunci Laporan','<?= base_url(); ?>kunci_laporan/edit/<?=$showData->id_log_laporan;?>')"> -->
								<button type="button" class="btn-right btn btn-warning btn-sm"
									onclick="tampil_pesan_ajax('Apakah anda yakin akan','Buka Kunci Laporan',
									'editKunci', '<?php echo $id;?>') ">
									<i class="fa fa-edit"></i>
								</button>
							</td>
							<?php } else { ?>
							<td></td>
							<?php } } ?>
						</tr>
					<?php $no++; ?>
					<?php } ?>
					<?php if(!$this->dataLog){
						echo "<tr><td colspan='25' align='center'>Data tidak ada.</td></tr>";
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
	$(function () {
		$('.table').DataTable({
			paging       : true,
			lengthChange : true,
			searching    : true,
			ordering     : true,
			aaSorting    : [],
			columnDefs: [
				{ orderable: false, targets: -1 }
			],
		})
	});

	function editKunci(id) {
		$.ajax({
			url: base_url + 'kunci_laporan/edit/'+ id,
			type:'GET',
			dataType:'json',
			beforeSend: function(){
				$('#loading').show();
				$('#pesan_error').hide();
			},
			success: function(data){
				if( data.status ){
					// alert(data.id);
					$('#container-modal').modal('hide');
					$('#pesan_error').show(); $('#pesan_error').html(data.pesan);
					location.reload();
				}
				else{
					$('#loading').hide(); $('#pesan_error').show(); $('#pesan_error').html(data.pesan);
				}
			},
			error : function(data) {
				$('#pesan_error').html('maaf telah terjadi kesalahan dalam program, silahkan anda mengakses halaman lainnya.'); $('#pesan_error').show(); $('#loading').hide();
				//$('#pesan_error').html( '<h3>Error Response : </h3><br>'+JSON.stringify( data ));
			}
		})

	}
</script>
