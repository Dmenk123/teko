
<!DOCTYPE html>
<html lang="en">
<head>
	<title>TEKO-CAK | Tanda Kehadiran Online dan Catatan Absensi Karyawan</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="<?php echo base_url();?>assets/Login_v3/text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/Login_v3/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/Login_v3/fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/Login_v3/vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/Login_v3/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/Login_v3/vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/Login_v3/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/Login_v3/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/Login_v3/css/util.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/Login_v3/css/main.css">
<!--===============================================================================================-->
	<link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.png"/>
<!--===============================================================================================-->
</head>
<body>

	<div class="limiter">
		<div class="container-login100" style="background-image: url('<?php echo base_url();?>assets/Login_v3/images/6.jpg');">
			<div class="wrap-login100">
				<form class="login100-form validate-form">
					<center>
						<img src="<?php echo base_url();?>assets/Login_v3/images/Kota Surabaya.png" width="300">
					</center>

					<span class="login100-form-title">
						Teko-Cak
					</span>
					<span class="login100-form-title" style="font-size:small;padding-top:10px;">
						Tanda Kehadiran Online dan Catatan Absensi Karyawan
					</span>
					<br>
					<div class="alert alert-danger display-hide ng-cloak"  style="display:none" id="pesan_error_login" > </div>
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="button" onClick="go('2018')">
							2018
						</button>
					</div>
					<br>
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="button" onClick="go('2019')">
							2019
						</button>
					</div>

				</form>
			</div>
		</div>
	</div>


	<div id="dropDownSelect1"></div>

<!--===============================================================================================-->
	<script src="<?php echo base_url();?>assets/Login_v3/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url();?>assets/Login_v3/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url();?>assets/Login_v3/vendor/bootstrap/js/popper.js"></script>
	<script src="<?php echo base_url();?>assets/Login_v3/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url();?>assets/Login_v3/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url();?>assets/Login_v3/vendor/daterangepicker/moment.min.js"></script>
	<script src="<?php echo base_url();?>assets/Login_v3/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url();?>assets/Login_v3/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url();?>assets/Login_v3/js/main.js"></script>

	<script>

	
		var base_url = "<?php echo base_url();?>";
		var base_url2 = "http://teko-cak.surabaya.go.id";
		var uri_1 = "<?php echo $this->uri->segment(1); ?>";
		var uri_2 = "<?php echo $this->uri->segment(2); ?>";
		var uri_3 = "<?php echo $this->uri->segment(3); ?>";
		var uri_4 = "<?php echo $this->uri->segment(4); ?>";
	</script>

	<script src="<?=base_url();?>assets/js/validate.js"></script>
	<script src="<?=base_url();?>assets/js/module_login.js"></script>

	<script>
		function go(tahun) {
			if(tahun == '2018') {
				location.href = base_url+"/2018";
			}
			else if(tahun == '2019') {
				location.href = base_url+"login/security"; 
			}
			else {
				location.reload();
			}
		}
	</script>

    </body>
    <!-- END BODY -->
</html>
