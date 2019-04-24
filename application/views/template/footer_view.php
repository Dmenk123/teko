	</div>
	<!-- END QUICK SIDEBAR -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="page-footer-inner">
		Teko-Cak &copy; Pemerintah Kota Surabaya
	</div>
	<div class="page-footer-tools">
		<span class="go-top">
		<i class="fa fa-angle-up"></i>
		</span>
	</div>
</div>
		<!-- ./wrapper -->

		<div class="modal fade" id="modalLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false">
			<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">
						Waktu anda telah habis, silahkan Login kembali
					</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" id="form_login">
						<div class="form-group">
							<label class="control-label col-sm-4" >Username :</label>
							<div class="col-sm-5">
								<input type="input" class="form-control " required name="USERNAME_LOGIN">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4" >Password :</label>
							<div class="col-sm-5">
								<input type="password" class="form-control " id="PASSWORD_LOGIN" required name="PASSWORD_LOGIN">
								<input type="hidden" id="forAction" value="disableModal">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<img src="<?php echo base_url();?>assets/img/loading.gif" id="loading_login" style="display:none">
								<p id="pesan_error_login" style="display:none" class="text-warning" style="display:none"></p>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-10">
								<button type="submit"  class="btn btn-primary">Login</button>
							</div>
						</div>
					</form>

				</div>
			</div>
			</div>
		</div>




		<script src="<?=base_url();?>assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
		<script src="<?=base_url();?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
		<script src="<?=base_url();?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
		<script src="<?=base_url();?>assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
		<script src="<?=base_url();?>assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
		<script src="<?=base_url();?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
		<!-- END CORE PLUGINS -->
		<!-- BEGIN PAGE LEVEL PLUGINS -->
		<script type="text/javascript" src="<?=base_url();?>assets/global/plugins/select2/select2.min.js"></script>
		<script type="text/javascript" src="<?=base_url();?>assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="<?=base_url();?>assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
		<script type="text/javascript" src="<?=base_url();?>assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
		<script type="text/javascript" src="<?=base_url();?>assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
		<script type="text/javascript" src="<?=base_url();?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
		<script type="text/javascript" src="<?=base_url();?>assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script type="text/javascript" src="<?=base_url();?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
		<!-- END PAGE LEVEL PLUGINS -->
		<!-- BEGIN PAGE LEVEL SCRIPTS -->
		<script src="<?=base_url();?>assets/global/scripts/metronic.js" type="text/javascript"></script>
		<script src="<?=base_url();?>assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
		<script src="<?=base_url();?>assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
		<script src="<?=base_url();?>assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
		<script src="<?=base_url();?>assets/admin/pages/scripts/table-advanced.js"></script>
		<script>

		function garbis(){
			$('.page-footer').append('<div class="modal fade" id="garbismodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="faslse"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"></div><div class="modal-body"><center><h4>Graphic for Attendance Report Biometric Information System</h4><img src="'+base_url+'assets/Login_v3/images/Kota%20Surabaya.png" width="200"><h3><b>Pemerintah Kota Surabaya</b></h3><center></div><div class="modal-footer"><div class="pull-right"></div></div></div></div></div>');
			$('#garbismodal').modal('show');
		}

		jQuery(document).ready(function() {
			Metronic.init(); // init metronic core components
			Layout.init(); // init current layout
			QuickSidebar.init(); // init quick sidebar
			Demo.init(); // init demo features
			TableAdvanced.init();
		});



			$(".select2").select2();

			function formatDesign(item) {
			  var selectionText = item.text.split(".");
			  var $returnString = selectionText[0] + "</br>" + selectionText[1];
			  return $returnString;
			};

			$('.select2split').select2({
			  placeholder: "Select something",
			  formatResult: formatDesign,
			  formatSelection: formatDesign
			});

			$('.datePicker').datepicker({
				autoclose: true,
				todayHighlight: true,
				startDate : new Date('2019-01-01')
			});

			$('.datePickerFormLembur').datepicker({
				autoclose: true,
				todayHighlight: true,
				format: 'dd-mm-yyyy',
				startDate : new Date('2019-01-01')
			});

			$('.datePickerLoss').datepicker({
				autoclose: true,
				todayHighlight: true
			});

			$('.datePickerMaxToday').datepicker({
				autoclose: true,
				todayHighlight: true,
				endDate: '0'
			});

			$('.datePickerFormKendala').datepicker({
				autoclose: true,
				todayHighlight: true,
				startDate : new Date('2019-01-01'),
				endDate: '0'
			});

			<?php
			if( !$this->session->userdata('nama_karyawan') ){
				echo "$('.wrapper').hide();$('#modalLogin').modal('show');";
			}
			?>
			var detik = <?php echo date('s'); ?>;
			var menit = <?php echo date('i'); ?>;
			var jam   = <?php echo date('H'); ?>;

			function clock()
			{
				if (detik!=0 && detik%60==0) {
					menit++;
					detik=0;
				}
				second = detik;

				if (menit!=0 && menit%60==0) {
					jam++;
					menit=0;
				}
				minute = menit;

				if (jam!=0 && jam%24==0) {
					jam=0;
				}
				hour = jam;

				if (detik<10){
					second='0'+detik;
				}
				if (menit<10){
					minute='0'+menit;
				}

				if (jam<10){
					hour='0'+jam;
				}
				waktu = hour+':'+minute+':'+second;


				document.getElementById("jam").innerHTML = "<?php echo date('d-m-Y'); ?> "+ waktu;
				detik++;

				//alert(waktu);
			}

			setInterval(clock,1000);

		function checkDec(el){
			var ex = /^[0-9]+\.?[0-9]*$/;
			if(ex.test(el.value)==false){
				el.value = el.value.substring(0,el.value.length - 1);
			}
		}

		function formatangka(objek) {
		  	a = objek.value;
		  	b = a.replace(/[^\d]/g,"");
		  	c = "";
		  	panjang = b.length;
		  	j = 0;
		  	for (i = panjang; i > 0; i--) {
			    j = j + 1;
			    if (((j % 3) == 1) && (j != 1)) {
					c = b.substr(i-1,1) + c;
					} else {
					c = b.substr(i-1,1) + c;
				}
			}
		  	objek.value = c;
		}

		<?php
		if(isset($this->datatable)) {
			foreach($this->datatable as $dt) {
		?>
		var tabel_<?=$dt['nama']?> = $('#<?=$dt['id']?>').DataTable({
			serverside: true,
			processing: true,
			order: [],
			searching: false,
			lengthChange: false,
			<?php
					if(isset($dt['columnDefs'])) {
			?>
			columnDefs: [
				<?php
						foreach($dt['columnDefs'] as $cd) {
				?>
				{ "visible": false,  "targets": [ <?=$cd?> ] },
				<?php
						}
				?>
	    ],
			<?php
					}
			?>
			ajax: {
				url: '<?=$dt['url']?>',
				type: 'POST',
				data: function(data){
		<?php
				foreach($dt['data'] as $d) {
		?>
					data.<?=$d['nama']?> = <?=$d['sumber']?>;
		<?php
				}
		?>
				}
			}
		});
		<?php
				if(isset($dt['klik_ambil_data'])) {
		?>
		$('#<?=$dt['id']?> tbody').on( 'click', 'tr', function () {
        var data = tabel_<?=$dt['nama']?>.row($(this)).data();
		<?php
					foreach($dt['klik_ambil_data'] as $k) {
		?>
				$("#<?=$k['wadah']?>").val(data[<?=$k['urutan']?>]);
		<?php
					}
					if(isset($dt['modal'])) {
		?>
				$('#<?=$dt['modal']?>').modal('hide');
		<?php
					}
		?>
		} );
		<?php
				}
				if(isset($dt['reload'])) {
		?>
		$('#<?=$dt['reload']?>').click(function() {
			tabel_<?=$dt['nama']?>.ajax.reload(null,false);
		});
		<?php
				}
			}
		 }
		?>
		</script>


		<script src="<?=base_url();?>assets/js/module.js"></script>

	</body>
</html>
