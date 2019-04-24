<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lap_per_pegawai extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('jenis_ijin_cuti_model');
		$this->load->model('t_ijin_cuti_model');
		$this->load->model('instansi_model');
		$this->load->model('pegawai_model');

	}


	public function index(){

		if($this->session->userdata('id_kategori_karyawan')=='4' || $this->session->userdata('id_kategori_karyawan')=='6' ){
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

		if($this->input->get('id_pegawai')!='' && $this->input->get('id_instansi')!=''){
			$wherePegawai ="id = '".$this->input->get('id_pegawai')."'";	
			$this->dataPegawai = $this->pegawai_model->getData($wherePegawai,"","nama");
		}
		else{
			$this->dataPegawai =  "";
		}

		$this->template_view->load_view('laporan/lap_per_pegawai_view');
		// if ($this->session->userdata('id_kategori_karyawan') == '1') {
		// 	$this->template_view->load_view('laporan/lap_per_pegawai_view');
		// }else{
		// 	$this->template_view->load_view('template/sedang-perbaikan');
		// }
	}



}
