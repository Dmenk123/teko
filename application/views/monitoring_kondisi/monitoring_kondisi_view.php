<style>
.loader2 {
  width: 250px;
  height: 50px;
  line-height: 50px;
  text-align: center;
  position: absolute;
  top: 50%;
  left: 50%;
  -webkit-transform: translate(-50%, -50%);
          transform: translate(-50%, -50%);
  font-family: helvetica, arial, sans-serif;
  text-transform: uppercase;
  font-weight: 900;
  color: #ce4233;
  letter-spacing: 0.2em;
}
.loader2::before, .loader2::after {
  content: "";
  display: block;
  width: 15px;
  height: 15px;
  background: #ce4233;
  position: absolute;
  -webkit-animation: load .7s infinite alternate ease-in-out;
          animation: load .7s infinite alternate ease-in-out;
}
.loader2::before {
  top: 0;
}
.loader2::after {
  bottom: 0;
}

@-webkit-keyframes load {
  0% {
    left: 0;
    height: 30px;
    width: 15px;
  }
  50% {
    height: 8px;
    width: 40px;
  }
  100% {
    left: 235px;
    height: 30px;
    width: 15px;
  }
}

@keyframes load {
  0% {
    left: 0;
    height: 30px;
    width: 15px;
  }
  50% {
    height: 8px;
    width: 40px;
  }
  100% {
    left: 235px;
    height: 30px;
    width: 15px;
  }
}

</style>
	<!-- div form -->
	<div class="portlet box purple">
		<div class="portlet-title">
			<div class="caption">
			<?php echo $this->template_view->nama_menu('nama_menu'); ?>
			</div>
		</div>

		<div class="portlet-body">
			<div class="">
				<form class="form-horizontal" method="get">
					<div class="form-group">
						<label class="control-label col-sm-2" >Tanggal :</label>
						<div class="col-sm-4">
							<select id="dobday" class="form-control col-sm-2" style="margin-right: 5px;" name="tanggal" id="tanggal"></select>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2" >Bulan :</label>
						<div class="col-sm-4">
							<select id="dobmonth" class="form-control col-sm-4" style="margin-right: 5px;" name="bulan" id="bulan"></select>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2" >Tahun :</label>
						<div class="col-sm-4">
							<select id="dobyear" class="form-control col-sm-3" style="margin-right: 5px;" name="tahun" id="tahun"></select>
						</div>
					<div>

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
							<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Proses</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- div data -->
	<?php if($this->input->get('tanggal') && $this->input->get('bulan') && $this->input->get('tahun')){ ?>
		<div class="portlet box purple">
			<div class="portlet-title">
				<div class="caption">
					<?php echo "Data proses download dan load mesin per ".date('d M Y', strtotime($this->input->get('tanggal')."-".$this->input->get('bulan')."-".$this->input->get('tahun'))); ?>	
				</div>

			</div>
			<div class="portlet-body">
				<div class="row">			
					<div class="col-xs-12"  id="data_list">
							<table id="dynamic-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th rowspan = "2">No</th>
										<th rowspan = "2">IP Address</th>
										<th rowspan = "2">Nama</th>
										<th rowspan = "2">periode Tanggal</th>
										<th colspan = "3" style="text-align:center;">Tarik</th>
										<th colspan = "1" style="text-align:center;">Load</th>
									</tr>
									<tr>
											<td>Jml</td>
											<td>Sukses</td>
											<td>Gagal</td>
											<td align="center">Jumlah</td>
									</tr>
								</thead>
								<tbody>
									<?php $no = 1; ?>
									<?php foreach ($this->data_log as $key => $value) { ?>
									<tr>
										<td><?php echo $no; ?></td>
										<td><?php echo $value['ip']; ?></td>
										<td><?php echo $value['nama_mesin']; ?></td>
										<td><?php echo date('d-m-Y', strtotime($value['tanggal'])); ?></td>
										<td><?php echo $value['jml_dl']; ?></td>
										<td><?php echo $value['jml_sukses']; ?></td>
										<td><?php echo $value['jml_gagal']; ?></td>
										<td><?php echo $value['jml_load']; ?></td>
									</tr>
									<?php $no++; } ?>
								</tbody>
							</table>
						</div>
					</div>
				<hr>
			</div>
		</div>
	<?php } ?>

<script>
	var save_method; //for save method string
	var table;
	var base_url = '<?php echo base_url();?>';
	jQuery(function($) {
		var tgl_now = new Date();
		/* table = $('#dynamic-table').DataTable({ 
			"responsive": true,
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"order": [], //Initial no order.
				
			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "<?php echo site_url('monitoring_kondisi/get_data')?>",
				"type": "POST"
			},
			
			//Set column definition initialisation properties.
			"columnDefs": [
			{ 
				"targets": [ -1 ], //last column
				"orderable": false, //set not orderable
			},
			],
			
		}); */

		$.dobPicker({
			// Selectopr IDs
			daySelector: '#dobday',
			monthSelector: '#dobmonth',
			yearSelector: '#dobyear',
			
			// Default option values
			dayDefault: 'Silahkan Pilih Tangal',
			monthDefault: 'Silahkan Pilih Bulan',
			yearDefault: 'Silahkan Pilih Tahun',

			// Minimum age
			minimumAge: 0,
			// Maximum age
			maximumAge: 0
		});
		
		//fungsi getParameterByName & formatDate ada di modules.js
		if(document.location.search.length) {
			$('#dobday').val(getParameterByName('tanggal'));
			$('#dobmonth').val(getParameterByName('bulan'));
			$('#dobyear').val(getParameterByName('tahun'));
		}else{
			// alert(formatDate(tgl_now, 'hari'));
			$('#dobday').val(formatDate(tgl_now, 'hari'));
			$('#dobmonth').val(formatDate(tgl_now, 'bulan'));
			$('#dobyear').val(formatDate(tgl_now, 'tahun'));
		}
		
		$('#dynamic-table').DataTable({
			"pageLength": 25
		});
		
	});

	function reload(){
	  var table = $('#dynamic-table').DataTable();
	   
		$('#dynamic-table').DataTable().ajax.reload(null,false);
	}
</script>