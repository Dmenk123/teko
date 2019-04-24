<div class="portlet box purple">
	<div class="portlet-title">
		<div class="caption">
		<?php echo $this->template_view->nama_menu('nama_menu'); ?>
		</div>

	</div>
	<div class="portlet-body">
		<div class="row">
			<form class="form-horizontal" id="form_password">
        <div class="form-group">
          <label class="control-label col-sm-2" >Password Saat ini :</label>
          <div class="col-sm-6">
            <input class="form-control" required type="password" value="" id="password_lama"  name="password_lama" placeholder="Password Saat ini" >
          </div>
        </div>
				<div class="form-group">
					<label class="control-label col-sm-2" >Password Baru :</label>
					<div class="col-sm-6">
            <input class="form-control" required type="password" value="" id="password_baru"  name="password_baru" placeholder="Password Baru" >
          </div>
				</div>
        <div class="form-group">
					<label class="control-label col-sm-2" >Konfirmasi Password Baru :</label>
					<div class="col-sm-6">
            <input class="form-control" required type="password" value="" id="konfirmasi_password_baru"  name="konfirmasi_password_baru" placeholder="Konfirmasi Password Baru" >
          </div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2" ></label>
					<div class="col-sm-6">
						<button id="btn_simpan" onClick="simpan()" type="button" class="btn btn-primary">Simpan</button>
					</div>
				</div>
			</form>
		</div>
		<div class="modal fade" id="pesan_modal" tabindex="-1" role="dialog" aria-labelledby="delete" aria-hidden="true">
		    <div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Pesan Pemberitahuan</h4>
					</div>
					<div class="modal-body">
						<p id="pesan_isi"></p>
					</div>
					<div class="modal-footer">
						<button type="button" id="pesan_modal-ok" class="btn btn-primary" data-dismiss="modal">OK</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /.content -->
<script>
  function simpan(){
    if($('#password_baru').val() == $('#konfirmasi_password_baru').val()) {
      $.ajax({
        url: base_url+'ubah_password/ubah_password_pegawai',
        type:'POST',
        dataType:'json',
        data: {
          password_lama: $('#password_lama').val(),
          password_baru: $('#password_baru').val(),
          konfirmasi_password_baru: $('#konfirmasi_password_baru').val()
        },
        success: function(data){
          $('#pesan_isi').html(data.pesan);
          $('#pesan_modal').modal('show');
          if(data.status){
            $('#form_password')[0].reset();
          }
        }
      });
    }
    else {
      $('#pesan_isi').html('Password Baru dan Konfirmasi Password Baru Tidak Sama');
      $('#pesan_modal').modal('show');
    }
	}
</script>
