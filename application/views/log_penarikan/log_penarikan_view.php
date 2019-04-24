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
		<?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">
			<div class="col-xs-12"  id="data_list">
				<div class="panel panel-primary">
			      	<div class="panel-heading"><strong>Perhatian</strong></div>
			      	<div class="panel-body"><p class="text-danger font-weight-bold"><span class="blink">Fitur Penarikan Data Dari Mesin Sudah Di Jalankan Otomatis By Sistem, Tidak Perlu Penarikan Data lagi <br> Mohon Di pastikan bahwa Tanggal Download Dan Tanggal Load Sudah Terupdate</span></p></div>
						
			    </div>
				<!-- <div type="button" id="pingg" class="btn btn-primary pull-right"><i class="fa fa-rss" aria-hidden="true"></i> Ping Mesin</div> -->
					<br>&nbsp;
				<div>
					<table id="dynamic-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th width="5%">No</th>
								<th width="20%">Nama OPD</th>
								<th width="10%">IP Address</th>
								<th width="10%">Tgl Download</th>
								<th width="5%">Data</th >
								<th width="10%">Tgl Load</th>
								<th width="10%">Tgl Generate</th>
								<th width="10%">Status Mesin</th>
								<th width="12%">Aksi</th>
							</tr>
						</thead>
					</table>
				</div>
				<!-- <form class="form-horizontal" id=""  method="get">
				<div class="col-sm-8">

				<div class="form-group">
					<label class="control-label col-sm-3" >Tgl. Mulai :</label>
					<div class="col-sm-3">
						<input type="text" value="<?=$this->input->get('tgl_mulai');?>" data-date-format='dd/mm/yyyy'  autocomplete="off" class="form-control datePicker " required id="tgl_mulai"  name="tgl_mulai">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" >Tgl. Selesai :</label>
					<div class="col-sm-3">
						<input type="text" value="<?=$this->input->get('tgl_selesai');?>"  data-date-format='dd/mm/yyyy'  autocomplete="off" class="form-control  datePicker " required  id="tgl_selesai"  name="tgl_selesai">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-3" ></label>
					<div class="col-sm-6">

						<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
					</div>
				</div>


			</div>

			</form> -->

			</div>
		<hr>
	</div>
</div>
</div>




<script>
<?php $sess = $this->session->userdata('id_kategori_karyawan'); ?>
var save_method; //for save method string
  	var table;
   	var base_url = '<?php echo base_url();?>';
	
		jQuery(function($) {
			
			//datatables
			table = $('#dynamic-table').DataTable({ 
				"responsive": true,
				"processing": true, //Feature control the processing indicator.
				"serverSide": true, //Feature control DataTables' server-side processing mode.
				"order": [], //Initial no order.
			   
				// Load data for the table's content from an Ajax source
				"ajax": {
					"url": "<?php echo site_url('log_penarikan/get_data')?>",
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
			
			$( "#pingg" ).click(function() {
				$('#loading').modal('show');
				$('.loader2').text('Proses Ping Mesin');
				$.ajax({
					url: "<?php echo site_url('log_penarikan/ping_mesin')?>",
					Type:'POST',
					dataType:'json',
					success : function(data){
							if(data.status){
									$.alert({
										theme: 'modern',
										closeIcon: true,
										animation: 'scale',
										type: 'green',
										title: 'Berhasil',
										content: data.pesan,
									});
							}else{
								$.alert({
										theme: 'modern',
										closeIcon: true,
										animation: 'scale',
										type: 'red',
										title: 'Gagal',
										content: data.pesan,
									});
							}
						$('#loading').modal('hide');
						}
						
					})
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
		
		<?php if($sess == 1 OR $sess == 2){?>
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
						var link = 'https://downloader-tekocak.surabaya.go.id/autolog/downloader/tarik_finger_ajax/'+id;
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
		
		function load_data(id){
			$.confirm({
				title: 'Load Data?',
				content: 'Data mesin finger Akan Di Upload',
				theme: 'modern',
				closeIcon: true,
				animation: 'scale',
				type: 'green',
				buttons: {
					Ya: function () {
						var link = 'https://downloader-tekocak.surabaya.go.id/autolog/downloader/load_data_ajax/'+id;
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
		
		function hapus_mesin(id){
			$.confirm({
				title: 'Hapus Log Finger?',
				content: 'Data Log mesin finger Akan Di Hapus',
				theme: 'modern',
				closeIcon: true,
				animation: 'rotate',
				type: 'red',
				buttons: {
					Ya: function () {
						var link = 'https://downloader-tekocak.surabaya.go.id/autolog/downloader/hapus_data_mesin/'+id;
						window.open(link, "_blank");
					},
					Tidak: function () {
							// $('#finish_project').hide();
							$.alert('Tidak jadi!');
					}
				}
				//  window.open('http://www.example.com?ReportID=1', '_blank');
			});
		}

		function generate_data(kd_instansi) {
			$('#modal_generate_instansi').modal('show');
			$.ajax({
				url:  base_url + '/log_penarikan/get_nama_instansi/'+ kd_instansi,
				type: 'GET',
				dataType: 'json',
				success: function(data){
					$('#spn-nama').text(data.nama_ins.nama);
				}
			});
			$('#id_instansi').val(kd_instansi);
		}

		function proses_generate() {
			$('#modal_generate_instansi').modal('hide');
			$('#pesan_modal').modal('show');
			$.ajax({
				url:  base_url + 'cetak_new/lap_absensi_lembur_opt/get_pegawai_garbos',
				type: 'POST',
				dataType: 'json',
				data: $('#form_generate_ins').serialize(),
				success: function(data){
					if (data.status == 'gagal') {
						$('#pesan_modal').modal('hide');
						$('#pesan_isi2').html(data.pesan);
						$('#pesan_modal2').modal('show');
					}else if (data.status == 'antrian') {
						var isi = "Antrian Generate Sedang Penuh, Silahkan Coba Kembali Beberapa Saat Lagi, <br/><br/> Berikut List Yang Masih Ada Dalam Antrian : <br>";
						for (i = 0; i < data.pesan.length; i++) {
							isi += (i+1) + ". " + data.pesan[i].nama_instansi + " Waktu Mulai Generate : " + data.pesan[i].start_at + "<br>";
						}
						$('#pesan_modal').modal('hide');
						$('#pesan_isi2').html(isi);
						$('#pesan_modal2').modal('show');
					}else{
						var i = 0;
						var i_max = data.pesan.length;
						proses_generate_peg(i, i_max, data);
						console.log("maksimal = " + i_max);
					}
				}
			});
		}

		function proses_generate_peg(i, i_max, data_kirim) {
			//console.log(i); aktifkan jika develop
			var persen = Math.round((i / i_max) * 100);
			$('.progress-bar').attr("aria-valuenow", persen).css("width", persen+'%').text(persen + '%');
			$('#proses-data').text('Proses ke : '+ i +' dari total '+ i_max +' data.');

			$.ajax({
				url:  base_url + 'cetak_new/lap_absensi_lembur_opt/GeneratePerPegawaiManual',
				type: 'POST',
				dataType: 'json',
				data: {
					id_pegawai 			: data_kirim.pesan[i].id,
					tgl_mulai_peg 	: data_kirim.tgl_mulai,
					tgl_akhir_peg 	: data_kirim.tgl_selesai,
					id_instansi_peg : data_kirim.kd_instansi
				},
				success: function(data){
					if (data.status == 'gagal') {
						$('#pesan_isi').html(isi);
					} else {
						i = i + 1;
						console.log(i);
						if(i < i_max){
							proses_generate_peg(i, i_max, data_kirim);
						}
						else {
							$.ajax({
								url:  base_url + 'cetak_new/lap_absensi_lembur_opt/update_selesai',
								type: 'POST',
								dataType: 'json',
								data: {
									id_user					: data_kirim.id_user_upd,
									kode_instansi 	: data_kirim.kode_instansi_upd,
									start_at				: data_kirim.start_at_upd
								},
								success: function(data){
									$('#pesan_isi').html('Data berhasil digenerate');
									$('#pesan_modal').modal('hide');
									$.alert({
										theme: 'modern',
										closeIcon: true,
										animation: 'scale',
										type: 'green',
										title: 'SUKSES',
										content: 'Data sukses di generate !',
										buttons: {
													somethingElse: {
															text: 'Ok',
															btnClass: 'btn-blue',
															keys: ['enter', 'shift'],
															action: function(){
																	location.reload();
															}
													}
											}
									});
									//$('.hidden-msg').hide();
								}
							});
						}
					}
				}
			});
		}
		
		<?php } ?>

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

<div class="modal fade" id="modal_generate_instansi" tabindex="-1" role="dialog" aria-labelledby="modalGenerateInsLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Generate Laporan <span id="spn-nama"></span></h4>
            </div>
            <div class="modal-body">
                <form id="form_generate_ins" name="formGenerateIns">
                    <div class="panel-body">
                        <div class="form-horizontal">
                            <div class="form-group">
																<label class="control-label col-sm-3" >Tanggal Mulai :</label>
                                <div class="col-sm-9">
                                    <input type="hidden" class="form-control" id="id_instansi" name="id_instansi">
                                    <input type="input" class="form-control datePickerMaxToday required" data-date-format='dd/mm/yyyy'  id="tgl_mulai"  name="tgl_mulai">
                                </div>
                            </div>

                            <div class="form-group">
																<label class="control-label col-sm-3" >Tanggal Akhir :</label>
                                <div class="col-sm-9">
                                    <input type="input" class="form-control datePickerMaxToday required" data-date-format='dd/mm/yyyy'  id="tgl_akhir" name="tgl_akhir">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                	<div class="col-md-12">
                		<span style="float:left"><strong>Mohon Pilih Maksimal 7 hari periode tanggal, agar proses tidak lama.</strong></span>
                	</div>

                	<div class="col-md-12" style="margin-top: 20px;">
                		<button type="button" class="btn btn-primary" onClick="proses_generate()">Generate</button>
                    	<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                	</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pesan_modal" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Proses Generate, Mohon menunggu.</h4>
			</div>
			<div class="modal-body">
				<span id="proses-data"></span>
				<div class="progress">
				 	<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
				    0%
				  	</div>
				</div>
				<p id="pesan_isi"></p>
			</div>
		</div>
	</div>
</div>