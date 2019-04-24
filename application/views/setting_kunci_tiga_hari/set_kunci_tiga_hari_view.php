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
	<div class="spinner-loader hidden-msg Fixed">
		<div class="img-pos" style="padding-top: 20%;">
			<img src="<?= base_url();?>assets/img/loading2.gif" style="display:block; margin-left: auto; margin-right: auto;">
		</div>
	</div>

	<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<?php echo $this->template_view->nama_menu('nama_menu'); ?>&nbsp;
		<span>
			(<?php echo $this->selisih; ?> OPD Belum Terkunci)
		</span>
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="col-xs-12"  id="data_list">
				<div>
					<form id="form-ceklis">
						<table id="dynamic-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th style="width: 5%;">No</th>
									<th style="width: 90%; text-align: center;">Nama OPD</th>
									<th style="width: 5%;"><input type="checkbox" id="select_all"/></th>
								</tr>
							</thead>
						</table>
					</form>
				</div>
				<!-- <button class="btn btn-default" id="all-ceklis" style="float:left">Check All</button> -->
				
					<button class="btn btn-success" id="proses-ceklis" style="float:right">Simpan Data</button>
				
				
				<!-- <button class="btn btn-danger" id="proses-ceklis2" style="float:right">Simpan Data</button> -->
			</div>
			<hr>
		</div>
	</div>


	<div class="modal fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="modalGenerateLabel" aria-hidden="true" data-backdrop="static">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header custom-modal-header">
	                <h4 class="modal-title">Proses</h4>
	            </div>
	            <div class="modal-body">
	                <div class="form-group modal-body">
						<div class="loader2">Buka / Kunci Data... <br>&nbsp;<br>&nbsp;<br>&nbsp;<br></div>
					</div>
	                <div class="modal-footer">
	                	
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
<script>
	var save_method; //for save method string
  	var table;
  
	$(document).ready(function($) {
		//datatables
		table = $('#dynamic-table').DataTable({ 
			"responsive": true,
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"order": [], //Initial no order.
			"searching": false,
		    "paging":   false,
			"pageLength": 'ALL',
		   
			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "<?php echo site_url('setting_kunci_tiga_hari/get_data')?>",
				"type": "POST"
			},
			
	   
		  //Set column definition initialisation properties.
		  "columnDefs": [
		  { 
			"targets": [ -1 ], //last column
			"orderable": false, //set not orderable
		  },

		  ],
	   
		});

	    $('#proses-ceklis').on('click', function(event) {
	    	event.preventDefault();
	    	$('#loading').modal('show');
	    	$.ajax({
	    		url: base_url + 'setting_kunci_tiga_hari/simpan_data',
	    		type: 'POST',
	    		dataType: 'json',
	    		data: $('#form-ceklis').serialize(),
	    		success : function(response){
	    			if (response.status) {
	    				alert('Berhasil Setting Kuncian 3 hari');
	    				$('#loading').modal('hide');
	    				$('#dynamic-table').DataTable().ajax.reload()
	    			}
	    		},
	    		error : function (){
	    			$('#loading').modal('hide');
	    			alert('terjadi kesalahan');
	    		}
	    	});
	    });

	    $('#select_all').change(function() {
		    var checkboxes = $(this).closest('form').find(':checkbox');
		    checkboxes.prop('checked', $(this).is(':checked'));
		});
	});// end jquery
</script>
