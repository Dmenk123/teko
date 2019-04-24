<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_golongan extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('golongan_model');
	}

	public function index(){
		$like 		  = null;
		$or_like 		= null;
		$order_by 	= 'kode_pangkat, kode_huruf';
		$urlSearch 	= null;

		if($this->input->get('keyword')){
			$like = array('LOWER(kode)' => strtolower($_GET['keyword']));
			$or_like = array('LOWER(deskripsi)' => strtolower($_GET['keyword']), 'LOWER(kode_huruf)' => strtolower($_GET['keyword']), 'LOWER(kode_pangkat)' => strtolower($_GET['keyword']), 'LOWER(nama)' => strtolower($_GET['keyword']));
			$urlSearch = "?keyword=".$_GET['keyword'];
		}

		$this->load->library('pagination');

		$config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch;
		$this->jumlahData 		= $this->golongan_model->getCount("",$like,null,null,null,null,$or_like);
		$config['total_rows'] = $this->jumlahData;
		$config['per_page'] 	= 10;

		$this->showData = $this->golongan_model->showData("",$like,$order_by,$config['per_page'],$this->input->get('per_page'),null,$or_like);
		$this->pagination->initialize($config);

		$this->template_view->load_view('master/golongan/golongan_view');
	}
	public function add(){
		$this->template_view->load_view('master/golongan/golongan_add_view');
	}

	public function add_data(){
		$this->form_validation->set_rules('KODE', '', 'trim|required');
		$this->form_validation->set_rules('NAMA', '', 'trim|required');
		$this->form_validation->set_rules('DESKRIPSI', '', 'trim|required');
		$this->form_validation->set_rules('KODE_PANGKAT', '', 'trim');
		$this->form_validation->set_rules('KODE_HURUF', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kode dan Nama Golongan Wajib diisi.');
		}
		else{
			if ($this->exist_data($this->input->post('KODE')) > 0)	{
				$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kode Golongan telah dipakai sebelumnya.');
			}
			else {
				$data = array(
					'kode' => $this->input->post('KODE'),
					'nama' => $this->input->post('NAMA'),
					'deskripsi' => $this->input->post('DESKRIPSI'),
					'kode_pangkat' => $this->input->post('KODE_PANGKAT'),
					'kode_huruf' => $this->input->post('KODE_HURUF')
				);

				$query = $this->golongan_model->insert($data);
				$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
			}
		}
		echo(json_encode($status));
	}

	public function edit($IdPrimaryKey){
		$where ="kode = '".$IdPrimaryKey."' ";
		$this->oldData = $this->golongan_model->getData($where);
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}
		$order_by = null;

		$this->template_view->load_view('master/golongan/golongan_edit_view');
	}

	public function edit_data(){
		$this->form_validation->set_rules('KODE_LAMA', '', 'trim|required');
		$this->form_validation->set_rules('KODE', '', 'trim|required');
		$this->form_validation->set_rules('NAMA', '', 'trim|required');
		$this->form_validation->set_rules('DESKRIPSI', '', 'trim|required');
		$this->form_validation->set_rules('KODE_PANGKAT', '', 'trim');
		$this->form_validation->set_rules('KODE_HURUF', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kode dan Nama Golongan Wajib diisi.');
		}
		else{
			if ($this->input->post('KODE') <> $this->input->post('KODE_LAMA') && $this->exist_data($this->input->post('KODE')) > 0)	{
				$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kode Golongan telah dipakai sebelumnya.');
			}
			else {
				$data = array(
					'kode' => $this->input->post('KODE'),
					'nama' => $this->input->post('NAMA'),
					'deskripsi' => $this->input->post('DESKRIPSI'),
					'kode_pangkat' => $this->input->post('KODE_PANGKAT'),
					'kode_huruf' => $this->input->post('KODE_HURUF')
				);

				$where = array('kode' => $this->input->post('KODE_LAMA'));
				$query = $this->golongan_model->update($where,$data);
				$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
			}
		}

		echo(json_encode($status));
	}
	public function delete($IdPrimaryKey){

		$where ="kode = '".$IdPrimaryKey."' ";
		$this->golongan_model->delete($where);

		redirect(base_url()."".$this->uri->segment(1));
	}

	public function exist_data($IdPrimaryKey) {
		$where ="kode = '".$IdPrimaryKey."' ";
		return $this->golongan_model->getCount($where);
	}
}
