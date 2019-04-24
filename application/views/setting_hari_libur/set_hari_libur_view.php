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
			<?php echo $this->template_view->nama_menu('nama_menu'); ?>
			</div>
		</div>
		<div class="portlet-body">
			<div class = "row">
				<div class="col-xs-12" id="data_list">
					<div class="panel panel-primary">
						<div class="panel-heading"><strong>Perhatian</strong></div>
						<div class="panel-body">
							<p class="text-danger font-weight-bold">Dimohon menghindari penggunaan tanda baca <strong>petik/quotes (' dan ")</strong> pada pengisian keterangan hari libur, karena dapat menyebabkan tidak dapat terbaca pada laporan.</p> 
							<p class="text-danger font-weight-bold">Sebagai pengganti dapat digunakan tanda baca <strong>backtick (`)</strong> agar dapat terbaca oleh sistem.</p>
						</div>
					</div>
					<div class="form-group">
						<form>
							<label class="control-label col-sm-2">Tahun :</label>
							<div class="col-sm-6">
								<select class="form-control select2 required" id="tahun_tampil" name="tahun_tampil" data-placeholder="Pilih Tahun">
									<option value="">Silahkan Pilih</option>
									<option <?php if($this->input->get('tahun_tampil') == '2019') echo "selected";?> value="<?php echo '2019'; ?>"><?php echo '2019'; ?></option>
									<option <?php if($this->input->get('tahun_tampil') == '2020') echo "selected";?> value="<?php echo '2020'; ?>"><?php echo '2020'; ?></option>
								</select>
								<br><br>
								<button type="submit" id="btn-filter" class="btn btn-primary"><i class="fa fa-save"></i> Tampilkan</button>
							</div>
						</form>
					</div>
					<?php if ($this->input->get('tahun_tampil')) { ?>
						<div>
						<table id="dynamic-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th style="width: 5%;">No</th>
									<th style="width: 13%; text-align: center;">Tanggal</th>
									<th style="width: 35%; text-align: center;">Hari Libur</th>
									<th style="width: 35%; text-align: center;">Keterangan</th>
									<th style="width: 7%;">
										<button class="btn btn-primary btn-block" id="btnAddLibur">
											<i class="fa fa-plus"></i>
										</button>
									</th>
								</tr>
							</thead>
						</table>
					</div>
					<?php } ?>
				</div>
				<hr>
			</div>
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
						<div class="loader2">Menyimpan Data... <br>&nbsp;<br>&nbsp;<br>&nbsp;<br></div>
					</div>
	                <div class="modal-footer">
	                	
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

	<div class="modal fade" id="modalAddLibur" tabindex="-1" role="dialog" aria-labelledby="modalAddLiburLabel" aria-hidden="true" data-backdrop="static">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header custom-modal-header">
	                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
	                <h4 class="modal-title">Setting hari libur</h4>
	            </div>
	            <div class="modal-body">
	                <form id="form_tambah_hari_libur" name="formAddLibur">
	                    <div class="panel-body">
	                        <div class="form-horizontal">
	                            <div class="form-group">
									<label class="control-label col-sm-3" >Per Tanggal :</label>
	                                <div class="col-sm-9">
	                                    <input type="input" class="form-control datePicker required" data-date-format='dd/mm/yyyy' id="TGL_LIBUR" name="TGL_LIBUR">
	                                </div>
	                            </div>

	                            <div class="form-group">
									<label class="control-label col-sm-3" >Hari Libur:</label>
									<div class="col-sm-9">
										<select class="form-control select2 required" id="ID_HARI_LIBUR" name="ID_HARI_LIBUR" data-placeholder="Pilih Hari libur">
											<option></option>
										<?php foreach($this->data_libur as $data_lbr) : ?>
											<option value="<?=$data_lbr->id?>"><?=$data_lbr->nama?></option>
										<?php endforeach; ?>
										</select>
									</div>
	                            </div>

	                            <div class="form-group">
									<label class="control-label col-sm-3" >Keterangan :</label>
	                                <div class="col-sm-9">
	                                    <input type="input" class="form-control required" id="KETERANGAN" name="KETERANGAN">
	                                </div>
	                            </div>

	                        </div>
	                    </div>
	                </form>
	                <div class="modal-footer">
	                    <button type="button" class="btn btn-primary" onClick="tambah_transaksi('tambah_hari_libur')">Tambah</button>
	                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

	<div class="modal fade" id="modalEditLibur" tabindex="-1" role="dialog" aria-labelledby="modalEditLiburLabel" aria-hidden="true" data-backdrop="static">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header custom-modal-header">
	                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
	                <h4 class="modal-title">Edit hari libur</h4>
	            </div>
	            <div class="modal-body">
	                <form id="form_edit_hari_libur" name="formEditLibur">
	                    <div class="panel-body">
	                        <div class="form-horizontal">
	                            <div class="form-group">
									<label class="control-label col-sm-3" >Per Tanggal :</label>
	                                <div class="col-sm-9">
	                                    <input type="input" class="form-control datePicker required" data-date-format='dd/mm/yyyy' id="TGL_LIBUR_EDIT" name="TGL_LIBUR_EDIT">
	                                </div>
	                            </div>

	                            <div class="form-group">
									<label class="control-label col-sm-3" >Hari Libur:</label>
									<div class="col-sm-9">
										<select class="form-control select2 required" id="ID_HARI_LIBUR_EDIT" name="ID_HARI_LIBUR_EDIT" data-placeholder="Pilih Hari libur">
											<option></option>
										<?php foreach($this->data_libur as $data_lbr) : ?>
											<option value="<?=$data_lbr->id?>"><?=$data_lbr->nama?></option>
										<?php endforeach; ?>
										</select>
									</div>
	                            </div>

	                            <div class="form-group">
									<label class="control-label col-sm-3" >Keterangan :</label>
	                                <div class="col-sm-9">
	                                    <input type="input" class="form-control required" id="KETERANGAN_EDIT" name="KETERANGAN_EDIT">
	                                    <input type="hidden" class="form-control required" id="ID_EDIT" name="ID_EDIT">
	                                </div>
	                            </div>

	                        </div>
	                    </div>
	                </form>
	                <div class="modal-footer">
	                    <button type="button" class="btn btn-primary" onClick="update_transaksi()">Tambah</button>
	                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

	<script>
		var save_method; //for save method string
	  	var table;
	  	var tahun;
	  	<?php if ($this->input->get('tahun_tampil')) { ?>
	  		tahun = <?php echo $this->input->get('tahun_tampil'); ?>
	  	<?php } ?>
	  	
		$(document).ready(function($) {
			 // select class modal apabila bs.modal hidden
			$("#modalAddLibur").on("hidden.bs.modal", function(){
			    $('#form_tambah_hari_libur')[0].reset(); 
			    $("#ID_HARI_LIBUR").select2("val", "");
		  	});

		  	$("#modalEditLibur").on("hidden.bs.modal", function(){
			    $('#form_edit_hari_libur')[0].reset(); 
			    $("#ID_HARI_LIBUR_EDIT").select2("val", "");
		  	});

			//datatables
			table = $('#dynamic-table').DataTable({ 
				"responsive": true,
				"processing": true, //Feature control the processing indicator.
				"serverSide": true, //Feature control DataTables' server-side processing mode.
				"order": [], //Initial no order.
			   
				// Load data for the table's content from an Ajax source
				"ajax": {
					"url": "<?php echo site_url('setting_hari_libur/get_data?tahun_tampil=')?>"+tahun,
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

		    // $('#proses-ceklis').on('click', function(event) {
		    // 	event.preventDefault();
		    // 	$('#loading').modal('show');
		    // 	$.ajax({
		    // 		url: base_url + 'setting_kunci_tiga_hari/simpan_data',
		    // 		type: 'POST',
		    // 		dataType: 'json',
		    // 		data: $('#form-ceklis').serialize(),
		    // 		success : function(response){
		    // 			if (response.status) {
		    // 				alert('Berhasil Setting Kuncian 3 hari');
		    // 				$('#loading').modal('hide');
		    // 				$('#dynamic-table').DataTable().ajax.reload()
		    // 			}
		    // 		},
		    // 		error : function (){
		    // 			$('#loading').modal('hide');
		    // 			alert('terjadi kesalahan');
		    // 		}
		    // 	});
		    // });

		    $('#btnAddLibur').on('click', function(event) {
		    	event.preventDefault();
		    	$('#modalAddLibur').modal('show');
		    });

		    $('#select_all').change(function() {
			    var checkboxes = $(this).closest('form').find(':checkbox');
			    checkboxes.prop('checked', $(this).is(':checked'));
			});
		});// end jquery

		function tambah_transaksi(uerel){
			$('#loading').modal('show');
			$.ajax({
				url: base_url + 'setting_hari_libur/'+ uerel +'_insert',
				type:'POST',
				dataType:'json',
				data: $('#form_'+uerel).serialize(),
				success: function(data){
					if( data.status ){
						$('#loading').modal('hide');
						$('#modalAddLibur').modal('hide');
						$.alert({
							theme: 'modern',
							closeIcon: true,
							animation: 'scale',
							type: 'green',
							title: 'SUKSES',
							content: 'Data Berhasil ditambahkan',
						});
						$('#dynamic-table').DataTable().ajax.reload();
					}
					else{
						$('#loading').modal('hide');
						$('#modalAddLibur').modal('hide');
						$.alert({
							theme: 'modern',
							closeIcon: true,
							animation: 'scale',
							type: 'red',
							title: 'GAGAL',
							content: 'Data Gagal ditambahkan',
						});
						$('#dynamic-table').DataTable().ajax.reload();
					}
				},
				error : function(data) {
					$('#loading').modal('hide');
					$('#modalAddLibur').modal('hide');
					$.alert({
						theme: 'modern',
						closeIcon: true,
						animation: 'scale',
						type: 'red',
						title: 'GAGAL',
						content: 'Terjadi kesalahan',
					});
					$('#dynamic-table').DataTable().ajax.reload();
				}
			})
		}

		function hapus_transaksi(id) {
			$.confirm({
			    title: 'Hapus Data hari Libur!',
			    content: 'Hapus Data hari Libur!',
			    buttons: {
			        confirm: function () {
			            $.ajax({
							url: base_url + 'setting_hari_libur/hapus_hari_libur',
							type:'POST',
							dataType:'json',
							data: {id:id},
							success: function(data){
								if (data.status) {
									$.alert({
										theme: 'modern',
										closeIcon: true,
										animation: 'scale',
										type: 'green',
										title: 'SUKSES',
										content: 'Berhasil hapus data '+data.namaharilibur,
									});
									$('#dynamic-table').DataTable().ajax.reload();
								}else{
									$.alert({
										theme: 'modern',
										closeIcon: true,
										animation: 'scale',
										type: 'red',
										title: 'GAGAL',
										content: 'Berhasil hapus data '+data.namaharilibur,
									});
									$('#dynamic-table').DataTable().ajax.reload();
								}
							},
							error : function(data) {
								$.alert({
									theme: 'modern',
									closeIcon: true,
									animation: 'scale',
									type: 'red',
									title: 'GAGAL',
									content: 'Terjadi kesalahan',
								});
								$('#dynamic-table').DataTable().ajax.reload();
							}
						});
					},
			        cancel: function () {
			            $.alert('Hapus Data dibatalkan');
			        },
			    }
			});
			
		}

		function edit_transaksi(id) {
			$('#modalEditLibur').modal('show');
			$.ajax({
				url: base_url + 'setting_hari_libur/edit_data',
				type:'POST',
				dataType:'json',
				data: {id:id},
				success: function(data){
					if( data.status ){
						var tgl_split = data.data.tanggal.split("-");
						var tgl = tgl_split[2] + "/" + tgl_split[1] + "/" + tgl_split[0];

						$('#TGL_LIBUR_EDIT').val(tgl);
						$("#ID_HARI_LIBUR_EDIT").select2("val", data.data.id_hari);
						$('#KETERANGAN_EDIT').val(data.data.keterangan);
						$('#ID_EDIT').val(data.data.id);
					}
				},
				error : function(data) {
					$('#loading').modal('hide');
					$('#modalAddLibur').modal('hide');
					$.alert({
						theme: 'modern',
						closeIcon: true,
						animation: 'scale',
						type: 'red',
						title: 'GAGAL',
						content: 'Terjadi kesalahan',
					});
					$('#dynamic-table').DataTable().ajax.reload();
				}
			})
		}

		function update_transaksi() {
			$('#modalEditLibur').modal('show');
			$.ajax({
				url: base_url + 'setting_hari_libur/update_data',
				type:'POST',
				dataType:'json',
				data: $('#form_edit_hari_libur').serialize(),
				success: function(data){
					if( data.status ){
						$('#modalEditLibur').modal('hide');
						$.alert({
							theme: 'modern',
							closeIcon: true,
							animation: 'scale',
							type: 'green',
							title: 'SUKSESu',
							content: 'Berhasil Update Data Master',
						});
						$('#dynamic-table').DataTable().ajax.reload();
					}
				},
				error : function(data) {
					$('#loading').modal('hide');
					$('#modalAddLibur').modal('hide');
					$.alert({
						theme: 'modern',
						closeIcon: true,
						animation: 'scale',
						type: 'red',
						title: 'GAGAL',
						content: 'Terjadi kesalahan',
					});
					$('#dynamic-table').DataTable().ajax.reload();
				}
			})
		}
	</script>
