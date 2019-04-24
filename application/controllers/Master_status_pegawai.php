<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_status_pegawai extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('status_pegawai_model');
	}

	public function index(){
		$like 		  = null;
		$or_like 		= null;
		$order_by 	= 'kode';
		$urlSearch 	= null;

		if($this->input->get('keyword')){
			$like = array('LOWER(kode)' => strtolower($_GET['keyword']));
			$or_like = array('LOWER(nama)' => strtolower($_GET['keyword']), 'LOWER(prosentasi)' => strtolower($_GET['keyword']));
			$urlSearch = "?keyword=".$_GET['keyword'];
		}

		$this->load->library('pagination');

		$config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch;
		$this->jumlahData 		= $this->status_pegawai_model->getCount("",$like,null,null,null,null,$or_like);
		$config['total_rows'] 	= $this->jumlahData;
		$config['per_page'] 	= 10;

		$this->showData = $this->status_pegawai_model->showData("",$like,$order_by,$config['per_page'],$this->input->get('per_page'),null,$or_like);
		$this->pagination->initialize($config);

		$this->template_view->load_view('master/status_pegawai/status_pegawai_view');
	}
	public function add(){
		$this->template_view->load_view('master/status_pegawai/status_pegawai_add_view');
	}

	public function add_data(){
		$this->form_validation->set_rules('KODE', '', 'trim|required');
		$this->form_validation->set_rules('NAMA', '', 'trim|required');
		$this->form_validation->set_rules('PROSENTASI', '', 'trim');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kode dan Nama Status Pegawai Wajib diisi.');
		}
		else{
			if ($this->exist_data($this->input->post('KODE')) > 0)	{
				$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kode Status Pegawai telah dipakai sebelumnya.');
			}
			else {
				$data = array(
					'kode' => $this->input->post('KODE'),
					'nama' => $this->input->post('NAMA'),
					'prosentasi' => $this->input->post('PROSENTASI')
				);

				$query = $this->status_pegawai_model->insert($data);
				$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
			}
		}

		echo(json_encode($status));
	}
	public function edit($IdPrimaryKey){
		$where ="kode = '".$IdPrimaryKey."' ";
		$this->oldData = $this->status_pegawai_model->getData($where);
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}
		$order_by = null;

		$this->template_view->load_view('master/status_pegawai/status_pegawai_edit_view');
	}
	public function edit_data(){
		$this->form_validation->set_rules('KODE_LAMA', '', 'trim|required');
		$this->form_validation->set_rules('KODE', '', 'trim|required');
		$this->form_validation->set_rules('NAMA', '', 'trim|required');
		$this->form_validation->set_rules('PROSENTASI', '', 'trim');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kode dan Nama Status Pegawai Wajib diisi.');
		}
		else{
			if ($this->input->post('KODE') <> $this->input->post('KODE_LAMA') && $this->exist_data($this->input->post('KODE')) > 0)	{
				$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kode Status Pegawa telah dipakai sebelumnya.');
			}
			else {
				$data = array(
					'kode' => $this->input->post('KODE'),
					'nama' => $this->input->post('NAMA'),
					'prosentasi' => $this->input->post('PROSENTASI')
				);


				$where = array('kode' => $this->input->post('KODE_LAMA'));
				$query = $this->status_pegawai_model->update($where,$data);
				$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
			}
		}

		echo(json_encode($status));
	}
	public function delete($IdPrimaryKey){

		$where ="kode = '".$IdPrimaryKey."' ";
		$this->status_pegawai_model->delete($where);

		redirect(base_url()."".$this->uri->segment(1));
	}

	public function exist_data($IdPrimaryKey) {
		$where ="kode = '".$IdPrimaryKey."' ";
		return $this->status_pegawai_model->getCount($where);
	}
}
