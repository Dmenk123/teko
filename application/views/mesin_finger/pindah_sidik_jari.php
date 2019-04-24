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
			<form class="form-horizontal"  method="get">
				<div class="form-group">
					<label class="control-label col-sm-2" >Instansi :</label>
					<div class="col-sm-6">
						<select type="input" class="form-control select2" onchange="ganti_instansi(this.value)" required id="id_instansi"  name="id_instansi">
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
				<div class="form-group">
					<label class="control-label col-sm-2" ></label>
					<div class="col-sm-6">
						
						<button type="button" id="btn-filter" class="btn btn-primary"><i class="fa fa-save"></i> Tampilkan</button>
					</div>
				</div>

			</form>
		</div>
		<div class="row">
			<div class="col-xs-12"  id="data_list">
				<div>
					<table id="dynamic-table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Pegawai</th>
								<th>Instansi</th>
								<th>Instansi Mesin</th>
								<th>IP Mesin</th>
								<th>Aksi</th>
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
		// var hand;
  	// var user_id;
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
					"url": "<?php echo site_url('pindah_sidik_jari/list_pegawai')?>",
					"type": "POST",
					"data": function ( data ) {
								data.id_instansi 	= $('#id_instansi').val();
							}
				},
				
		   
			  //Set column definition initialisation properties.
			  "columnDefs": [
			  { 
				"targets": [ -1 ], //last column
				"orderable": false, //set not orderable
			  },
			  ],
		   
			});

			$('#btn-filter').click(function(){ //button filter event click
				table.ajax.reload(null,false);  //just reload table
			});
			$('#btn-reset').click(function(){ //button reset event click
				$('#form-filter')[0].reset();
				table.ajax.reload(null,false);  //just reload table
			});
		    


		    $('#id_instansi_target').change(function() {
		        if (this.value) {
		            $('#lokasi_mesin').select2();
		            var id = $('#id_instansi_target').val();
		            fieldkcd = $("[name='lokasi_mesin']");
		            fieldkcd.html("<option value=''>Loading</option>");
		            fieldkcd.attr('disabled','disabled');
		            $('.input').attr('disabled', 'disabled');
		            $.ajax({
		                url : "<?php echo base_url('pindah_sidik_jari/instansi_mesin/'); ?>"+ id,
		                type : 'POST', 
		                dataType : 'json'
		              }).done(function(response){
		                //console.log(respone);
		                  $('.util-spin').hide();
		                  fieldkcd.removeAttr('disabled');
		                  $('.input').removeAttr('disabled');
		                  fieldkcd.html("<option value=''>--- Pilih Mesin ---</option>");
		                  if(response){
		                      for(i=0;i<response.length;i++){
		                          var optionkcd = "<option value='"+response[i]['ip_address']+"' ";
		                          optionkcd += " >"+ response[i]['ip_address'] + ' - ' +response[i]['nama']+"</option>";
		                          fieldkcd.append(optionkcd);
		                      }
		                  }                 
		              });

		            
		        }
		    });

				
		    
		           

		         

		});

		function get_one(){
				var badge 		= $("[name='badgenumber']").val();
				var fn 				= $("[name='sidik_jari']").val();
				var ip = $("[name='ip_origin']").val(); 
			$.ajax({
					url : "<?php echo base_url('pindah_sidik_jari/get_one_handkey/'); ?>"+ badge+'/' + ip +'/'+ fn,
					type: "POST",
					contentType: false,
					processData: false,
					dataType: "JSON",
					success : function(data){
						$('#data_sidik_jari').val(data[0].handkey);
								// user_id =data.user_id;
							}
				});
		    
		}

		function hand(ip){
			// $('#sidik_jari').select2();
            var badge 		= $("[name='badgenumber']").val();
            fieldkcd = $("[name='sidik_jari']");
            fieldkcd.html("<option value=''>Loading</option>");
            fieldkcd.attr('disabled','disabled');
            $('.input').attr('disabled', 'disabled');
            $.ajax({
                url : "<?php echo base_url('pindah_sidik_jari/get_handkey/'); ?>"+ badge +'/'+ ip+'/false',
                type : 'POST', 
                dataType : 'json'
              }).done(function(response){
                //console.log(respone);
                  $('.util-spin').hide();
                  fieldkcd.removeAttr('disabled');
                  $('.input').removeAttr('disabled');
                  fieldkcd.html("<option value=''>--- Pilih Sidik Jari ---</option>");
                  if(response){
                      for(i=0;i<response.length;i++){
                          var optionkcd = "<option value='"+response[i]['handkey']+"' ";
                          optionkcd += " >"+ response[i]['fn'] +"</option>";
                          fieldkcd.append(optionkcd);

                      }
                  }                 
              });
		}
		
		function reload(){
		  var table = $('#dynamic-table').DataTable();
		   
			$('#dynamic-table').DataTable().ajax.reload(null,false);
		}

		function pindah_sidikjari(id,nama){
			$('#sidik_jari').val('');
			$('#data_sidik_jari').val('');
			get_pegawai(id);
			get_one();
			$('#model_pegawai').modal('show');
			$('#pindah_cuy').trigger("reset");
			$("#id_instansi_target").val('').trigger('change');
			$("#nama").val(nama);
			$("#lokasi_mesin").val('').trigger('change');
		}

		function send_data(){
			var formData = new FormData($('#pindah_cuy')[0]);
			$.ajax({
						url: '<?php echo site_url('pindah_sidik_jari/pindah_data') ?>',
						type: "POST",
						data: formData,
						contentType: false,
						processData: false,
						dataType: "JSON",
		        success : function(data){
									$.alert({
									theme: 'modern',
									closeIcon: true,
									animation: 'scale',
									type: 'green',
									title: 'Berhasil',
									content: data.pesan,
								});
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
						$('#loading').modal('show');
                        $.ajax({
                          url: "<?php echo site_url('log_penarikan/tarik_finger/')?>"+ id,
                          dataType: "JSON",
                          success: function (data) {
							  if(data.status){
								$.alert({
									theme: 'modern',
									closeIcon: true,
									animation: 'scale',
									type: 'green',
									title: 'Berhasil',
									content: 'Data Finger Berhasil Di Tarik',
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
									content: 'Data Finger Gagal Di Tarik',
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

		function get_pegawai(id){
			$.ajax({
		        url : "<?php echo base_url('pindah_sidik_jari/detail_pegawai/'); ?>" + id+"",
		        dataType : 'json',
		        Type:'POST',
		        success : function(data){
		        			$("[name='badgenumber']").val(data.user_id); 
		        			$("[name='ip_origin']").val(data.ip_address); 
		        			hand(data.ip_address);
		        			// user_id =data.user_id;
		        		}
      		});
		}

</script>
<div class="modal fade" id="model_pegawai" tabindex="-1" role="dialog" aria-labelledby="modalGenerateLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Proses</h4>
            </div>
            <div class="modal-body">
                <form id="pindah_cuy" class="form-horizontal" role="form">
					<div id="progressup">
					</div>
					<div class="form-body" id="uploadtan">
						<div class="form-group">
							<label class="control-label col-md-3">Instansi</label>
							<div class="col-md-9">
								<select type="input" class="form-control select2"  id="id_instansi_target"  name="id_instansi_target" required>
									<option value="">Silahkan Pilih</option>
									<?php
									foreach($this->dataInstansi as $data){
									 ?>
									<option <?php if($this->input->get('id_instansi') == $data->kode) echo "selected";?> value="<?php echo $data->kode; ?>"><?php echo $data->nama; ?></option>
									<?php
									}
								 	?>
								</select>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Lokasi Mesin</label>
							<div class="col-md-9">
								<input type="hidden" name="badgenumber">
								<input type="hidden" name="ip_origin">
								<select type="input" class="form-control" id="lokasi_mesin" name="lokasi_mesin">
									<option value=''>--- Pilih Mesin ---</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Sidik Jari</label>
							<div class="col-md-9">
								<select type="input" class="form-control" onclick="get_one();" id="sidik_jari" name="sidik_jari">
									<option value=''>--- Pilih Sidik Jari ---</option>
								</select>
								<input type="hidden" id="data_sidik_jari" name="data_sidik_jari">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Nama</label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="nama" name="nama_user">
							</div>
						</div><div class="form-group">
							<label class="control-label col-md-3"></label>
							<div class="col-md-9">
								<div class="btn btn-info" onclick="send_data()" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</div>
								<div class="btn btn-danger" data-dismiss="modal"><i class="fa fa-arrow-left"></i> Cancel</div>
								<!-- <div class="btn btn-info" class="btn btn-danger"><i class="fa fa-save"></i> Cancel</div> -->
							</div>
						</div>
                  	</div>
              </form>
            </div>
        </div>
    </div>
</div>

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