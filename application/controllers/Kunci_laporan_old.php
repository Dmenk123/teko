<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kunci_laporan extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('log_laporan_model');
		$this->load->model('instansi_model');

	}


	public function index(){
		$this->bulan = array (1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);

		if($this->session->userdata('id_kategori_karyawan')=='4' ){
			$whereInstansi =	"m_instansi.kode='".$this->session->userdata('kode_instansi')."' ";
		}
		else{
			$whereInstansi =	"";
		}
		$this->dataInstansi = $this->instansi_model->showData($whereInstansi,"","nama");	
		
		$this->dataLog = $this->log_laporan_model->showData($whereInstansi,"","nama");	
	//	var_dump($this->dataLog);
		
		$this->template_view->load_view('laporan/log_laporan_view');
	}
	
	public function save(){
		$this->form_validation->set_rules('bulan', '', 'trim|required');
		$this->form_validation->set_rules('tahun', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, bulan dan tahun Wajib diisi.');
		}
		else{
			$hari_ini 		= $this->input->post("tahun")."-".$this->input->post("bulan")."-01"; 
			$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));
			
			if($this->session->userdata('id_kategori_karyawan')=='4' ){
				$sudahAda	=	$this->log_laporan_model->getData("kd_instansi = '".$this->session->userdata('kode_instansi')."' and tgl_log = '".$this->tgl_terakhir."' ");
			}
			else{
				$sudahAda	=	$this->log_laporan_model->getData("kd_instansi = '".$this->input->post('id_instansi')."' and tgl_log = '".$this->tgl_terakhir."' ");
			}
						
			if ($sudahAda)	{
				$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kunci Laporan untuk Bulan ini sudah ada.');
			}
			else {
				
				$max	=	$this->log_laporan_model->getPrimaryKeyMax();
				$newId 	=	$max->max + 1;
				
				
				
				if($this->session->userdata('id_kategori_karyawan')=='4' ){
					$data['kd_instansi'] = $this->session->userdata('kode_instansi');
				}
				else{
					$data['kd_instansi'] = $this->input->post('id_instansi');
				}
				
				$hari_ini 		= date($this->input->post("tahun")."-".$this->input->post("bulan")."-01"); 
				$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));				
				$data['tgl_log']	 = $this->tgl_terakhir;		
				
				$data['id_log_laporan']	 	= $newId ;					
				$data['time_stamp']	 		= date('Y-m-d H:i:s');
				$data['id_user']			= $this->session->userdata('id_karyawan');

				$query = $this->log_laporan_model->insert($data);
				$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
			}
		}

		echo(json_encode($status));
	}





}
