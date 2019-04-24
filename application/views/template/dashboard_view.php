<style type="text/css">
	hr {
			display: block;
			height: 1px;
			border: 0;
			border-top: 4px solid #ccc;
			margin: 1em 0;
			padding: 0; 
		}
</style>

<div class="row">
	<div class="col-md-12">
		<div class="portlet box purple">
			<div class="portlet-title">
				<div class="caption">
					Dashboard
				</div>
			</div>
			<div class="portlet-body">
				<h2>Selamat datang
				<?php echo $this->session->userdata('nama_karyawan'); ?></h2>
				<hr>
				<h4>Panduan Aplikasi Teko-Cak</h4>
				<p>Panduan ini berisi tata cara dan langkah penyelesaian mandiri oleh operator OPD bila masih ditemukan data pegawai yang datanya belum update, belum digenerate dan belum ditarik. Sehingga melalui panduan ini setiap operator OPD dapat melakukan langkah dan solusinya. Semoga memudahkan dan bermanfaat. Terima kasih.</p>
				<h3><a target="_blank" href="<?php echo base_url('assets/panduan/Langkah_Pengecekan_Tekocak.pdf') ?>">Download Panduan Teko-Cak</a></h3>
				
				<?php if (
				$this->session->userdata('id_kategori_karyawan') == 4 || $this->session->userdata('id_kategori_karyawan') == 11) { ?>
					<?php if($this->session->userdata('username') != 'yudho') { ?>
						<div class="col-md-6" style="margin-top:20px;">
							<h4>Log Absensi (10 Terakhir)</h4>
							<div class="panel-group">
								<div class="panel panel-default">
								<div class="panel-heading"><strong>Jam Cek Log terakhir per <?php echo $this->session->userdata('nama_karyawan')?></strong></div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-bordered">
											<thead>
											<tr>
												<th>Nama Pegawai</th>
												<th>Nip</th>
												<th>Jam Ceklog</th>
											</tr>
											</thead>
											<tbody>
											<?php foreach ($this->cek_log as $value) { ?>
												<tr>
													<td><?php echo $value['nama']; ?></td>
													<td><?php echo $value['nip']; ?></td>
													<td><?php echo $value['jam_masuk']; ?></td>
												</tr>
											<?php } ?>
											</tbody>
										</table>
									</div>
									<p style="text-align:right;"><a target="_blank" href="<?php echo base_url('log_absen_per_mesin') ?>">Lihat Selengkapnya...</a></p>
								</div>
								</div>
							</div>
						</div>
						
						<div class="col-md-6" style="margin-top:20px;">
							<h4>&nbsp;</h4>
							<div class="panel-group">
								<div class="panel panel-default">
								<div class="panel-heading"><strong>Jam Proses Tarik dan Load Terakhir <?php echo $this->session->userdata('nama_karyawan')?></strong></div>
									<div class="panel-body">
										<table class="table table-bordered">
											<thead>
											<tr>
												<th>Nama OPD</th>
												<th>Download</th>
												<th>Load</th>
											</tr>
											</thead>
											<tbody>
											<?php foreach ($this->cek_dl as $dl) { ?>
												<tr>
													<td><?php echo $dl['nama']; ?></td>
													<td><?php echo $dl['jm_dl']; ?></td>
													<td><?php echo $dl['jm_ld']; ?></td>
												</tr>
											<?php } ?>
											</tbody>
										</table>
										<p style="text-align:right;"><a target="_blank" href="<?php echo base_url('log_penarikan') ?>">Lihat Selengkapnya...</a></p>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="panel-group">
								<div class="panel panel-default">
								<div class="panel-heading"><strong>Generate terakhir <?php echo $this->session->userdata('nama_karyawan')?></strong></div>
									<div class="panel-body">
										<table class="table table-bordered">
											<thead>
											<tr>
												<th>Nama OPD</th>
												<th>Start At</th>
												<th>Finish At</th>
											</tr>
											</thead>
											<tbody>
											<?php foreach ($this->cek_gen as $gen) { ?>
												<tr>
													<td><?php echo $gen['nama']; ?></td>
													<td><?php echo $gen['mulai']; ?></td>
													<td><?php echo $gen['selesai']; ?></td>
												</tr>
											<?php } ?>
											</tbody>
										</table>
										<p style="text-align:right;"><a target="_blank" href="<?php echo base_url('daftar_generate_laporan') ?>">Lihat Selengkapnya...</a></p>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				<?php } ?>

				<?php if (
				$this->session->userdata('id_kategori_karyawan') == 1 || $this->session->userdata('id_kategori_karyawan') == 2) { ?>
					<div class="col-md-12" style="margin-top:20px;">
						<h4>Presentase Penarikan by Sistem (Otomatis)</h4>
						<div class="panel-group">
							<div class="panel panel-default">
							<div class="panel-heading"><strong>Presentase penarikan mesin otomatis per <?php echo date('d-M-Y');?></strong></div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-bordered">
										<thead>
										<tr>
											<th>No</th>
											<th>Mesin</th>
											<th>Ip Address</th>
											<th>Jml Tarik</th>
											<th>Jml Sukses</th>
											<th>Jml Gagal</th>
											<th>Presentase</th>
										</tr>
										</thead>
										<tbody>
										<?php 
										$no = 1;
										foreach ($this->dboard1 as $value) { 
										?>
											<tr>
												<td><?php echo $no; ?></td>
												<td><?php echo $value['nama_mesin']; ?></td>
												<td><?php echo $value['ip']; ?></td>
												<td><?php echo $value['jml_dl']; ?></td>
												<td><?php echo $value['jml_sukses']; ?></td>
												<td><?php echo $value['jml_gagal']; ?></td>
												<td><?php echo $value['presentase'].' %'; ?></td>
											</tr>
										<?php
										$no++; 
										} 
										?>
										</tbody>
									</table>
								</div>
								<?php 
								$tgl = date('d');
								$bln = date('m');
								$thn = date('Y');
								?>
								<p style="text-align:right;"><a target="_blank" href='<?php echo base_url("monitoring_kondisi?tanggal=$tgl&bulan=$bln&tahun=$thn"); ?>''>Lihat Selengkapnya...</a></p>
							</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>