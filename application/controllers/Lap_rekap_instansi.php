<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_rekap_instansi extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('jenis_ijin_cuti_model');
		$this->load->model('t_ijin_cuti_model');
		$this->load->model('instansi_model');
		$this->load->model('pegawai_model');

	}


	public function index(){

		if($this->session->userdata('id_kategori_karyawan')=='4' || $this->session->userdata('id_kategori_karyawan')=='6'){
			if ($this->session->userdata('kode_instansi') == '5.09.00.00.00') {
				$whereInstansi =	"m_instansi.kode='5.09.00.00.00' or m_instansi.kode='5.09.00.91.00'";
			}else{
				$whereInstansi =	"m_instansi.kode='".$this->session->userdata('kode_instansi')."' ";
			}
		}
		else{
			$whereInstansi =	"";
		}
		$this->dataInstansi = $this->instansi_model->showData($whereInstansi,"","nama");

		$this->template_view->load_view('laporan/lap_rekap_instansi_view_ali');
		/*if ($this->session->userdata('id_kategori_karyawan') == '1') {
			$this->template_view->load_view('laporan/lap_rekap_instansi_view_ali');
		}else{
			$this->template_view->load_view('template/sedang-perbaikan');
		}*/
	}





}
