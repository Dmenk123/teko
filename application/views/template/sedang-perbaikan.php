<div class="row">
	<div class="col-md-12">
		<div class="portlet box purple">
			<div class="portlet-title">
				<div class="caption">
					Sedang Perbaikan
				</div>
			</div>
			<?php if (isset($this->pesan)) { ?>
				<div class="portlet-body">
					<h3><?php echo $this->pesan['header']; ?></h3>
					<span><?php echo $this->pesan['isi']; ?></span>
				</div>
			<?php }else{  ?>
				<div class="portlet-body">
					<h3>Mohon maaf, fitur sedang dalam perbaikan.</h3>
					<span>Mohon kembali lagi kurang lebih <strong>15 menit.</strong> Terima Kasih.</span>
				</div>
			<?php } ?>
		</div>
	</div>
</div>