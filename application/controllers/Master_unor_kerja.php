<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_unor_kerja extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model(['unor_kerja_model', 'instansi_model', 'eselon_model']);
	}

	public function index(){
		$like 		= null;
		$or_like 		= null;
		$order_by 	= 'kode';
		$urlSearch 	= null;
		$this->instansi_post = null;
		$this->keyword_post = null;

		$select = "m_unit_organisasi_kerja.*, m_eselon.nama_eselon";
		$join = array(
			array(
				"table" => "m_eselon",
				"on"    => "m_unit_organisasi_kerja.kode_eselon = m_eselon.kode"
			)
		);

		$this->instansiData = $this->instansi_model->showData("","",$order_by);

		$config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch;
		$this->load->library('pagination');

		$param = $this->session->userdata('param');

		if($this->input->post('instansi')){
			$where = array('kode_instansi' => $this->input->post('instansi'));
			if($this->input->post('keyword')) {
				$like = array('LOWER(nama)' => strtolower($this->input->post('keyword')));
			}
			$sess = array (
				'instansi' => $this->input->post('instansi'),
				'keyword' => $this->input->post('keyword')
			);
			$this->session->set_userdata('param', $sess);
			$this->instansi_post = $this->input->post('instansi');
			$this->keyword_post = $this->input->post('keyword');

			$this->jumlahData 		= $this->unor_kerja_model->getCount($where,$like,null,null,null,null,$or_like,$select,$join);
			$config['total_rows'] = $this->jumlahData;
			$config['per_page'] 	= 10;

			$this->showData = $this->unor_kerja_model->showData($where,$like,$order_by,$config['per_page'],$this->input->get('per_page'),null,null,$select,$join);
			$this->pagination->initialize($config);
		}
		else if($param['instansi']) {
			$where = "kode_instansi = '".$param['instansi']."' ";
			$where = array('kode_instansi' => $param['instansi']);
			if($param['keyword']) {
				$like = array('LOWER(nama)' => strtolower($param['keyword']));
			}

			$this->instansi_post = $param['instansi'];
			$this->keyword_post = $param['keyword'];

			$this->jumlahData 		= $this->unor_kerja_model->getCount($where,$like,null,null,null,null,$or_like,$select,$join);
			$config['total_rows'] = $this->jumlahData;
			$config['per_page'] 	= 10;

			$this->showData = $this->unor_kerja_model->showData($where,$like,$order_by,$config['per_page'],$this->input->get('per_page'),null,null,$select,$join);
			$this->pagination->initialize($config);
		}
		else {
			$this->jumlahData 		= 0;
			$config['total_rows'] = $this->jumlahData;
			$config['per_page'] 	= 10;

			$this->showData = array();
			$this->pagination->initialize($config);
		}

		$this->template_view->load_view('master/unor_kerja/unor_kerja_view');
	}
	public function add(){
		$param               = $this->session->userdata('param');
		$this->instansi_post = $param['instansi'];
		$this->keyword_post  = $param['keyword'];

		$order_by 	         = 'kode';

		$this->instansiData  = $this->instansi_model->showData("","",$order_by);
		$this->eselonData    = $this->eselon_model->showData("","",$order_by);

		$this->template_view->load_view('master/unor_kerja/unor_kerja_add_view');
	}

	public function add_data(){
		$this->form_validation->set_rules('KODE', '', 'trim|required');
		$this->form_validation->set_rules('NAMA', '', 'trim|required');
		$this->form_validation->set_rules('NO_REGISTRASI', '', 'trim');
		$this->form_validation->set_rules('SRC_NAMA', '', 'trim');
		$this->form_validation->set_rules('KODE_ESELON', '', 'trim');
		$this->form_validation->set_rules('KODE_INSTANSI', '', 'trim|required');
		$this->form_validation->set_rules('KODE_SKPD', '', 'trim');
		//$this->form_validation->set_rules('PARENT_ID', '', 'trim');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{
			if ($this->exist_data($this->input->post('KODE')) > 0)	{
				$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kode Unit Organisasi Kerja telah dipakai sebelumnya.');
			}
			else {
				$kd_skpd = null;
				$kd_eselon = null;
				if($this->input->post('KODE_SKPD') == 1) {
					$kd_skpd = $this->input->post('KODE_SKPD');
				}
				if($this->input->post('KODE_ESELON') <> '') {
					$kd_eselon = $this->input->post('KODE_ESELON');
				}
				$data = array(
					'kode' => $this->input->post('KODE'),
					'nama' => $this->input->post('NAMA'),
					'no_registrasi' => $this->input->post('NO_REGISTRASI'),
					'src_nama' => $this->input->post('SRC_NAMA'),
					'kode_eselon' => $kd_eselon,
					'kode_instansi' => $this->input->post('KODE_INSTANSI'),
					'kode_skpd' => $kd_skpd,
					'parent_id' => $this->input->post('KODE_INSTANSI')
				);

				$query = $this->unor_kerja_model->insert($data);
				$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));

				$sess = array (
					'instansi' => $this->input->post('KODE_INSTANSI'),
					'keyword' => $this->input->post('NAMA')
				);
				$this->session->set_userdata('param', $sess);
			}
		}

		echo(json_encode($status));
	}
	public function edit($IdPrimaryKey){
		$order_by = null;
		$where = "kode = '".$IdPrimaryKey."' ";
		$this->oldData = $this->unor_kerja_model->getData($where);
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}
		$this->instansiData = $this->instansi_model->showData("","",$order_by);
		$this->eselonData   = $this->eselon_model->showData("","",$order_by);

		$this->template_view->load_view('master/unor_kerja/unor_kerja_edit_view');
	}
	public function edit_data(){
		$this->form_validation->set_rules('KODE_LAMA', '', 'trim|required');
		$this->form_validation->set_rules('KODE', '', 'trim|required');
		$this->form_validation->set_rules('NAMA', '', 'trim|required');
		$this->form_validation->set_rules('NO_REGISTRASI', '', 'trim');
		$this->form_validation->set_rules('SRC_NAMA', '', 'trim');
		$this->form_validation->set_rules('KODE_ESELON', '', 'trim');
		$this->form_validation->set_rules('KODE_INSTANSI', '', 'trim|required');
		$this->form_validation->set_rules('KODE_SKPD', '', 'trim');
		//$this->form_validation->set_rules('PARENT_ID', '', 'trim');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{
			if ($this->input->post('KODE') <> $this->input->post('KODE_LAMA') && $this->exist_data($this->input->post('KODE')) > 0)	{
				$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kode Unit Organisasi Kerja telah dipakai sebelumnya.');
			}
			else {
				$kd_skpd = null;
				$kd_eselon = null;
				if($this->input->post('KODE_SKPD') == 1) {
					$kd_skpd = $this->input->post('KODE_SKPD');
				}
				if($this->input->post('KODE_ESELON') <> '') {
					$kd_eselon = $this->input->post('KODE_ESELON');
				}
				$data = array(
					'kode' => $this->input->post('KODE'),
					'nama' => $this->input->post('NAMA'),
					'no_registrasi' => $this->input->post('NO_REGISTRASI'),
					'src_nama' => $this->input->post('SRC_NAMA'),
					'kode_eselon' => $kd_eselon,
					'kode_instansi' => $this->input->post('KODE_INSTANSI'),
					'kode_skpd' => $kd_skpd,
					'parent_id' => $this->input->post('KODE_INSTANSI')
				);


				$where = array('kode' => $this->input->post('KODE_LAMA'));
				$query = $this->unor_kerja_model->update($where,$data);
				$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));

				$sess = array (
					'instansi' => $this->input->post('KODE_INSTANSI'),
					'keyword' => $this->input->post('NAMA')
				);
				$this->session->set_userdata('param', $sess);
			}
		}

		echo(json_encode($status));
	}

	public function delete($IdPrimaryKey){

		$where ="kode = '".$IdPrimaryKey."' ";
		$this->unor_kerja_model->delete($where);

		redirect(base_url()."".$this->uri->segment(1));
	}

	public function exist_data($IdPrimaryKey) {
		$where ="kode = '".$IdPrimaryKey."' ";
		return $this->unor_kerja_model->getCount($where);
	}

	public function getUnorByInstansi() {
		if($this->input->server('REQUEST_METHOD') == 'POST') {
			$this->form_validation->set_rules('KODE_INSTANSI','Kode Instansi','required');
			if($this->form_validation->run() == true) {
				$kode_instansi = $this->input->post('KODE_INSTANSI');

				$order_by	= 'kode';
				$where    = "kode_instansi = '".$kode_instansi."' ";
				$unor     = $this->unor_kerja_model->showDataArray($where,null,$order_by);
				$status   = array('status' => true, 'unor' => $unor);
			}
			else {
				$status = array('status' => false, 'pesan' => 'Pilih Instansi Terlebih Dahulu');
			}
		}
		else {
			$status = array('status' => false, 'pesan' => 'Anda melakukan akses terlarang pada aplikasi SSW');
		}
		echo(json_encode($status));
	}
}
