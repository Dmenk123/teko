<?php
if(!$_SESSION['kategori_karyawan'])
{
	redirect(base_url());
}

	echo $header;
	echo $content;
	echo $footer;
?>
