<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search_per_pegawai extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();

		$this->load->model('pegawai_model');
	}

	public function cari()
	{
		if(is_numeric($_POST['cari']))
		{
			$like = array("nip" => $_POST['cari']);
		}
		else
		{
			$like = array("nama" => $_POST['cari']);
		}

		$select = "id, nama, nip";
		$order_by = "nama asc";
		$hasil_select = $this->pegawai_model->showData(null, $like, $order_by, null, null, null, null, $select);

		// var_dump($hasil_select);
		// echo $hasil_select->nama;

		if($hasil_select)
		{
			echo '
			<ul>';
			foreach ($hasil_select as $val) 
			{
				echo '
				<li onclick="masuk(\''.$val->id.'\', \''.$val->nip.'\', \''.$val->nama.'\')">'.$val->nip.' - '.$val->nama.'</li>';
			}
			echo '
			</ul>';
		}
		else
		{
			echo 'nope';
		}
	}
}