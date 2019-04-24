<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_rumpun_jabatan extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('rumpun_jabatan_model');
	}

	public function index(){
		$like 		  = null;
		$or_like 		= null;
		$order_by 	= 'nama';
		$urlSearch 	= null;

		if($this->input->get('keyword')){
			$like = array('LOWER(nama)' => strtolower($_GET['keyword']));
			$urlSearch = "?keyword=".$_GET['keyword'];
		}

		$this->load->library('pagination');

		$config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch;
		$this->jumlahData 		= $this->rumpun_jabatan_model->getCount("",$like,null,null,null,null,$or_like);
		$config['total_rows'] = $this->jumlahData;
		$config['per_page'] 	= 10;

		$this->showData = $this->rumpun_jabatan_model->showData("",$like,$order_by,$config['per_page'],$this->input->get('per_page'),null,$or_like);
		$this->pagination->initialize($config);

		$this->template_view->load_view('master/rumpun_jabatan/rumpun_jabatan_view');
	}
	public function add(){
		$this->template_view->load_view('master/rumpun_jabatan/rumpun_jabatan_add_view');
	}

	public function add_data(){
		$this->form_validation->set_rules('NAMA', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Nama Rumpun Jabatan Wajib diisi.');
		}
		else{
			$data = array(
				'nama' => $this->input->post('NAMA')
			);

			$query = $this->rumpun_jabatan_model->insert($data);
			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}
		echo(json_encode($status));
	}

	public function edit($IdPrimaryKey){
		$where ="id = '".$IdPrimaryKey."' ";
		$this->oldData = $this->rumpun_jabatan_model->getData($where);
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}
		$order_by = null;

		$this->template_view->load_view('master/rumpun_jabatan/rumpun_jabatan_edit_view');
	}
	public function edit_data(){
		$this->form_validation->set_rules('ID', '', 'trim|required');
		$this->form_validation->set_rules('NAMA', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Nama Rumpun Jabatan Wajib diisi.');
		}
		else{
			$data = array(
				'nama' => $this->input->post('NAMA')
			);

			$where = array('id' => $this->input->post('ID'));
			$query = $this->rumpun_jabatan_model->update($where,$data);
			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}

		echo(json_encode($status));
	}
	public function delete($IdPrimaryKey){

		$where ="id = '".$IdPrimaryKey."' ";
		$this->rumpun_jabatan_model->delete($where);

		redirect(base_url()."".$this->uri->segment(1));
	}

	public function exist_data($IdPrimaryKey) {
		$where ="id = '".$IdPrimaryKey."' ";
		return $this->rumpun_jabatan_model->getCount($where);
	}
}
