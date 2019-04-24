<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Maintenance Teko-Cak Surabaya</title>

	<!-- Google font -->
	<link href="https://fonts.googleapis.com/css?family=Cabin:400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:900" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="<?= base_url('assets/style.css') ?>" />

</head>

<body>

	<div id="notfound">
		<div class="notfound">
			<div class="notfound-404">
				<h3>
				<?php
					date_default_timezone_set("Asia/Jakarta");

					$b = time();
					$hour = date("G",$b);

					if ($hour>=0 && $hour<=11)
					{
					echo "Selamat Pagi";
					}
					elseif ($hour >=12 && $hour<=14)
					{
					echo "Selamat Siang";
					}
					elseif ($hour >=15 && $hour<=17)
					{
					echo "Selamat Sore";
					}
					elseif ($hour >=17 && $hour<=18)
					{
					echo "Selamat Petang";
					}
					elseif ($hour >=19 && $hour<=23)
					{
					echo "Selamat Malam";
					}
					echo ', Mohon Maaf:';
				?>
				<br>
				
				</h3>
				<h1><span>M</span><span>A</span><span>I</span><span>N</span><span>T</span><span>E</span><span>N</span><span>A</span><span>N</span><span>C</span><span>E</span></h1>
			</div>
			<h2>Teko-Cak Surabaya</h2>
		</div>
	</div>

</body>

</html>
