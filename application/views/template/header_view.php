<?php
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

//var_dump($_SESSION);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>TEKO-CAK | Tanda Kehadiran Online dan Catatan Absensi Karyawan</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.6 -->

	<!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/> -->
	
	<link href="<?=base_url();?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?=base_url();?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?=base_url();?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?=base_url();?>assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
	<link href="<?=base_url();?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
	<!-- END GLOBAL MANDATORY STYLES -->
	<!-- BEGIN PAGE LEVEL STYLES -->
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/global/plugins/select2/select2.css"/>
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
	<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/global/plugins/bootstrap-datetimepicker/css/datetimepicker.css"/>
	<!-- END PAGE LEVEL STYLES -->
	<!-- BEGIN THEME STYLES -->
	<link href="<?=base_url();?>assets/global/css/components.css" rel="stylesheet" type="text/css"/>
	<link href="<?=base_url();?>assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
	<link href="<?=base_url();?>assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
	<link id="style_color" href="<?=base_url();?>assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css"/>
	<link href="<?=base_url();?>assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
	<link href="<?=base_url();?>assets/global/css/jquery-confirm.min.css" rel="stylesheet" type="text/css"/>
	<link rel="shortcut icon" href="<?php echo base_url();?>assets/images/favicon.png"/>


	<!-- jQuery 2.2.3 -->
	<script src="<?=base_url();?>assets/global/plugins/jQuery/jquery-2.2.3.min.js" type="text/javascript"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="<?=base_url();?>assets/global/plugins/jquery-ui.min.js" type="text/javascript"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
		var base_url = "<?php echo base_url();?>";
		var uri_1 = "<?php echo $this->uri->segment(1); ?>";
		var uri_2 = "<?php echo $this->uri->segment(2); ?>";
		var uri_3 = "<?php echo $this->uri->segment(3); ?>";
		var uri_4 = "<?php echo $this->uri->segment(4); ?>";
		$.widget.bridge('uibutton', $.ui.button);
		$(document).ready(function(){
			$('[data-toggle="popover"]').popover();
		});
	</script>



	<script src="<?=base_url();?>assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
	<script src="<?=base_url();?>assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
	<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
	<script src="<?=base_url();?>assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
	<script src="<?=base_url();?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?=base_url();?>assets/global/plugins/datatables/media/js/jquery.dataTables.js" type="text/javascript"></script>
	<script src="<?=base_url();?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" type="text/javascript"></script>
	<script src="<?=base_url();?>assets/js/jquery-confirm.min.js" type="text/javascript"></script>
	<script src="<?=base_url();?>assets/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="<?=base_url();?>assets/js/dob-picker.min.js" type="text/javascript"></script>

	<script src="<?=base_url();?>assets/js/validate.js"></script>
	<script src="<?=base_url();?>assets/js/jquery-upload.js"></script>
	<style>
		label.error { color: red; font-size:11px; }
		.hidden-msg{
			display: none;
		}
		.spinner-loader{
			position: absolute;
			width: 100%;
			height: 100%;
			left: 0;
			top: 0;
		}
		.Fixed
		{
		    position: fixed;
		}
		.ui-autocomplete { z-index:2147483647; }
	</style>

<!-- Matomo -->
<!-- <script type="text/javascript">
  var _paq = window._paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//172.18.1.249/";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '1']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script> -->
<!-- End Matomo Code -->


</head>


<body class="page-header-fixed page-quick-sidebar-over-content ">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner">
		<!-- BEGIN LOGO -->
		<div class="page-logo">
			
			<a style="font-size:17px;padding-top:10px;padding-left:0px;color:#31c7b2" onclick="garbis();">Teko Cak</a>
			<div class="menu-toggler sidebar-toggler">
				
			</div>
		</div>
		<!-- <marquee><h4><font color="white">Untuk sementara waktu data absensi belum terupdate, Mohon maaf atas ketidaknyamanan nya.</font></h4></marquee> -->
		<!-- END LOGO -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		<!-- BEGIN TOP NAVIGATION MENU -->
		<div class="top-menu">



			<ul class="nav navbar-nav pull-left">
				<li class="dropdown dropdown-user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<span class="username username-hide-on-mobile"  id="jam">
					 </span>
					<i class="fa fa-angle-down"></i>
					</a>
				</li>
			</ul>

			<ul class="nav navbar-nav pull-right">
				<li class="dropdown dropdown-user">

					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<!--<img alt="" class="img-circle" src="<?=base_url();?>assets/images/no_photo.jpg"/>-->
					<span class="username username-hide-on-mobile">
					<b><?php echo $_SESSION['nama_karyawan'] ?> | <?php echo $_SESSION['kategori_karyawan'] ?></b> </span>
					<i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu">
						
						<li>
							<a href="<?=base_url();?>dashboard/changepassword">
							<i class="icon-user"></i> Ubah password</a>
						</li>
						<li>
							<a href="#" onclick="showModalLogOut('<?=base_url();?>logout')">
							<i class="icon-key"></i> Log Out </a>
						</li>
					</ul>
				</li>
				<!-- END USER LOGIN DROPDOWN -->
				<!-- BEGIN QUICK SIDEBAR TOGGLER -->

				<!-- END QUICK SIDEBAR TOGGLER -->
			</ul>
		</div>
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<div class="page-sidebar-wrapper">
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<div class="page-sidebar navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			<ul class="page-sidebar-menu " data-auto-scroll="true" data-slide-speed="200">
				<!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->

				<!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->




				<?php echo $tampil_menu; ?>

			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
	</div>

	<div class="page-content-wrapper">
		<div class="page-content">
