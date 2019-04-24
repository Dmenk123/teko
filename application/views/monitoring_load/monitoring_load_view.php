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
	<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<?php echo $this->template_view->nama_menu('nama_menu').' | Jumlah Antrian : '.$this->data2[0]['jml']; ?>
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="col-xs-12" style="margin-bottom: 30px;">
				<button type="button" id="load-paksa" class="btn btn-primary pull-right" ><i class="fa fa-warning" aria-hidden="true"></i> Paksa Load Antrian</button>	
			</div>
			
			<div class="col-xs-12"  id="data_list">
					<table id="dynamic-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>No</th>
								<th>IP Address</th>
								<th>Nama</th>
								<th>Finish Download</th>
								<th>Start Load</th>
								<th>Finish Load</th>
								<th>Jumlah</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		<hr>
	</div>
</div>
</div>


<script>
	var save_method; //for save method string
	var table;
	var base_url = '<?php echo base_url();?>';
	
	jQuery(function($) {
		table = $('#dynamic-table').DataTable({ 
			"responsive": true,
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"order": [], //Initial no order.
				
			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "<?php echo site_url('monitoring_load/get_data')?>",
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
	});

	$('#load-paksa').click(function() {
		$.confirm({
			title: 'Load Paksa daftar antrian?',
            content: 'Data antrian akan di load paksa',
			theme: 'modern',
			closeIcon: true,
			animation: 'scale',
			type: 'green',
			buttons: {
				Ya: function () {
					$.confirm({
						title: 'Yakin ?',
			            content: 'Data akan di load paksa..',
						theme: 'modern',
						closeIcon: true,
						animation: 'scale',
						type: 'yellow',
						buttons: {
							Ya: function () {
								$.confirm({
									title: 'Konfirmasi ulang',
						            content: 'Data antrian akan di load paksa',
									theme: 'modern',
									closeIcon: true,
									animation: 'scale',
									type: 'red',
									buttons: {
										Ya: function () {
											var link = "http://downloader-tekocak.surabaya.go.id/autolog/downloader/load_data/true";
											window.open(link,'_blank');
						                },
						                Tidak: function () {
						                    $.alert('Canceled!');
						                }
									}
									
								});
								
			                },
			                Tidak: function () {
			                   // $('#finish_project').hide();
			                    $.alert('Canceled!');
			                }
						}
						
					});
					
                },
                Tidak: function () {
                   // $('#finish_project').hide();
                    $.alert('Canceled!');
                }
			}
			
		});
	});

	function reload(){
	  var table = $('#dynamic-table').DataTable();
	   
		$('#dynamic-table').DataTable().ajax.reload(null,false);
	}

	function ping_mesin_manual(id){
		$('.loader2').text('Ping Mesin');
		$.confirm({
			title: 'Ping Mesin Finger?',
            content: 'Data mesin finger Akan Di Ping',
			theme: 'modern',
			closeIcon: true,
			animation: 'scale',
			type: 'green',
			buttons: {
				 Ya: function () {
					$('#loading').modal('show');
						$.ajax({
							url: "<?php echo site_url('log_penarikan/ping_mesin/')?>"+ id,
							dataType: "JSON",
							success: function (data) {
						  if(data.status){
							$.alert({
								theme: 'modern',
								closeIcon: true,
								animation: 'scale',
								type: 'green',
								title: 'Berhasil',
								content: data.pesan,
							});
							reload();
							$('#loading').modal('hide');
						  }else{
							$.alert({
								theme: 'modern',
								closeIcon: true,
								animation: 'scale',
								type: 'red',
								title: 'oops',
								content: data.pesan,
							});
							$('#loading').modal('hide');
						  }
                      }
                  });
                },

                Tidak: function () {
                   // $('#finish_project').hide();
                    $.alert('Canceled!');
                }
			}
			
		});
	}
	
	function tarik_finger(id){
		//$('#loading').modal('show');
		$.confirm({
			title: 'Tarik Finger?',
			content: 'Data mesin finger Akan Di Tarik',
			theme: 'modern',
			closeIcon: true,
			animation: 'scale',
			type: 'green',
			buttons: {
				Ya: function () {
					var link = 'http://downloader-tekocak.surabaya.go.id/autolog/downloader/tarik_finger_ajax/'+id;
					window.open(link, "_blank");
				},
				Tidak: function () {
						// $('#finish_project').hide();
						$.alert('Canceled!');
				}
			}
			//  window.open('http://www.example.com?ReportID=1', '_blank');
		});
	}

	function pop_biasa(id) {
		$.confirm({
			title: 'Tarik Finger?',
			content: 'Data mesin finger Akan Di Tarik',
			theme: 'modern',
			closeIcon: true,
			animation: 'scale',
			type: 'green',
			buttons: {
				Ya: function () {
					$.alert('Maaf fitur tidak tersedia, mohon hubungi administrator');
				},
				Tidak: function () {
					// $('#finish_project').hide();
					$.alert('Canceled!');
				}
			}
			
		});
	}
</script>


<div class="modal fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="modalGenerateLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Proses</h4>
            </div>
            <div class="modal-body">
                <div class="form-group modal-body">
					<div class="loader2">Mengambil Data... <br>&nbsp;<br>&nbsp;<br>&nbsp;<br></div>
				</div>
                <div class="modal-footer">
                	
                </div>
            </div>
        </div>
    </div>
</div>