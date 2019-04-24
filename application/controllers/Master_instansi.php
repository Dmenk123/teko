<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_instansi extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('instansi_model');
	}

	public function index(){
		$like 		  = null;
		$or_like 		= null;
		$order_by 	= 'kode, nama';
		$urlSearch 	= null;

		if($this->input->get('keyword')){
			$like = array('LOWER(kode)' => strtolower($_GET['keyword']));
			$or_like = array('LOWER(nama)' => strtolower($_GET['keyword']));
			$urlSearch = "?keyword=".$_GET['keyword'];
		}

		$this->load->library('pagination');

		$config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch;
		$this->jumlahData 		= $this->instansi_model->getCount("",$like,null,null,null,null,$or_like);
		$config['total_rows'] = $this->jumlahData;
		$config['per_page'] 	= 10;

		$this->showData = $this->instansi_model->showData("",$like,$order_by,$config['per_page'],$this->input->get('per_page'),null,$or_like);
		$this->pagination->initialize($config);

		$this->template_view->load_view('master/instansi/instansi_view');
	}

	public function add(){
		$this->template_view->load_view('master/instansi/instansi_add_view');
	}

	public function add_data(){
		$this->form_validation->set_rules('KODE', '', 'trim|required');
		$this->form_validation->set_rules('NAMA', '', 'trim|required');
		$this->form_validation->set_rules('INSTANSI_TDD', '', 'trim');
		$this->form_validation->set_rules('NAMA_TDD', '', 'trim');
		$this->form_validation->set_rules('NIP_TDD', '', 'trim');
		$this->form_validation->set_rules('PANGKAT_TDD', '', 'trim');
		//$this->form_validation->set_rules('KODE_SIK', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kode dan Nama Instansi Wajib diisi.');
		}
		else{
			if ($this->exist_data($this->input->post('KODE')) > 0)	{
				$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kode Instansi telah dipakai sebelumnya.');
			}
			else {
				$data = array(
					'kode' => $this->input->post('KODE'),
					'nama' => $this->input->post('NAMA'),
					'instansi_tdd' => $this->input->post('INSTANSI_TDD'),
					'nama_tdd' => $this->input->post('NAMA_TDD')	,
					'nip_tdd' => $this->input->post('NIP_TDD')	,
					'pangkat_tdd' => $this->input->post('PANGKAT_TDD')
				);

				$query = $this->instansi_model->insert($data);
				$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
			}
		}
		echo(json_encode($status));
	}

	public function edit($IdPrimaryKey){
		$where ="kode = '".$IdPrimaryKey."' ";
		$this->oldData = $this->instansi_model->getData($where);
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}
		$order_by = null;

		$this->template_view->load_view('master/instansi/instansi_edit_view');
	}
	public function edit_data(){
		$this->form_validation->set_rules('KODE_LAMA', '', 'trim|required');
		$this->form_validation->set_rules('KODE', '', 'trim|required');
		$this->form_validation->set_rules('NAMA', '', 'trim|required');
		$this->form_validation->set_rules('INSTANSI_TDD', '', 'trim');
		$this->form_validation->set_rules('NAMA_TDD', '', 'trim');
		$this->form_validation->set_rules('NIP_TDD', '', 'trim');
		$this->form_validation->set_rules('PANGKAT_TDD', '', 'trim');
		//$this->form_validation->set_rules('KODE_SIK', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kode dan Nama Instansi Wajib diisi.');
		}
		else{
			if ($this->input->post('KODE') <> $this->input->post('KODE_LAMA') && $this->exist_data($this->input->post('KODE')) > 0)	{
				$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kode Instansi telah dipakai sebelumnya.');
			}
			else {
				$data = array(
					'kode' => $this->input->post('KODE'),
					'nama' => $this->input->post('NAMA'),
					'instansi_tdd' => $this->input->post('INSTANSI_TDD'),
					'nama_tdd' => $this->input->post('NAMA_TDD')	,
					'nip_tdd' => $this->input->post('NIP_TDD')	,
					'pangkat_tdd' => $this->input->post('PANGKAT_TDD')
				);

				$where = array('kode' => $this->input->post('KODE_LAMA'));
				$query = $this->instansi_model->update($where,$data);
				$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
			}
		}

		echo(json_encode($status));
	}
	public function delete($IdPrimaryKey){

		$where ="kode = '".$IdPrimaryKey."' ";
		$this->instansi_model->delete($where);

		redirect(base_url()."".$this->uri->segment(1));
	}

	public function exist_data($IdPrimaryKey) {
		$where ="kode = '".$IdPrimaryKey."' ";
		return $this->instansi_model->getCount($where);
	}
}
