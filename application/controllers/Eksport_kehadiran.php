<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Eksport_kehadiran extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('jenis_ijin_cuti_model');
		$this->load->model('t_ijin_cuti_model');
		$this->load->model('instansi_model');
		$this->load->model('pegawai_model');

	}


	public function index(){

		if($this->session->userdata('id_kategori_karyawan')=='4' || $this->session->userdata('id_kategori_karyawan')=='3'){
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
		
		
		$wherePegawai ="id = '".$this->input->get('id_pegawai')."' ";	
		$this->dataPegawai = $this->pegawai_model->getData($wherePegawai,"","nama");
		
		$this->template_view->load_view('laporan/eksport_kehadiran_view');
	}

	public function ubah_status(){

		if($this->input->post('status')=='1'){
			$data = array(
				'status' 	=> '1'
			);
		}
		else{
			$data = array(
				'status' 	=> null
			);
		}

		$where = array(
			'id' => $this->input->post('id_t_ijin')
		);

		$query = $this->t_ijin_cuti_model->update($where,$data);
		$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));

		echo(json_encode($status));
	}



}
