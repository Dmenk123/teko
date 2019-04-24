<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_jenis_jabatan extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('jenis_jabatan_model');
	}

	public function index(){
		$like 		  = null;
		$or_like 		= null;
		$order_by 	= 'nama';
		$urlSearch 	= null;

		if($this->input->get('keyword')){
			$like = array('LOWER(nama)' => strtolower($_GET['keyword']));
			$or_like = array('LOWER(to_char(urut, \'999\'))' => strtolower($_GET['keyword']));
			$urlSearch = "?keyword=".$_GET['keyword'];
		}

		$this->load->library('pagination');

		$config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch;
		$this->jumlahData 		= $this->jenis_jabatan_model->getCount("",$like,null,null,null,null,$or_like);
		$config['total_rows'] = $this->jumlahData;
		$config['per_page'] 	= 10;

		$this->showData = $this->jenis_jabatan_model->showData("",$like,$order_by,$config['per_page'],$this->input->get('per_page'),null,$or_like);
		$this->pagination->initialize($config);

		$this->template_view->load_view('master/jenis_jabatan/jenis_jabatan_view');
	}
	public function add(){
		$this->template_view->load_view('master/jenis_jabatan/jenis_jabatan_add_view');
	}

	public function add_data(){
		$this->form_validation->set_rules('NAMA', '', 'trim|required');
		$this->form_validation->set_rules('URUT', '', 'trim|numeric');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Nama Jenis Jabatan Wajib diisi.');
		}
		else{
			$urut = $this->input->post('URUT');
			if($urut == '') {
				$urut = null;
			}
			$data = array(
				'nama' => $this->input->post('NAMA'),
				'urut' => $urut
			);

			$query = $this->jenis_jabatan_model->insert($data);
			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}
		echo(json_encode($status));
	}

	public function edit($IdPrimaryKey){
		$where ="kode = '".$IdPrimaryKey."' ";
		$this->oldData = $this->jenis_jabatan_model->getData($where);
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}
		$order_by = null;

		$this->template_view->load_view('master/jenis_jabatan/jenis_jabatan_edit_view');
	}
	public function edit_data(){
		$this->form_validation->set_rules('KODE', '', 'trim|required');
		$this->form_validation->set_rules('NAMA', '', 'trim|required');
		$this->form_validation->set_rules('URUT', '', 'trim|numeric');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Nama Jenis Jabatan Wajib diisi.');
		}
		else{
			$urut = $this->input->post('URUT');
			if($urut == '') {
				$urut = null;
			}
			$data = array(
				'nama' => $this->input->post('NAMA'),
				'urut' => $urut
			);

			$where = array('kode' => $this->input->post('KODE'));
			$query = $this->jenis_jabatan_model->update($where,$data);
			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}

		echo(json_encode($status));
	}
	public function delete($IdPrimaryKey){

		$where ="kode = '".$IdPrimaryKey."' ";
		$this->jenis_jabatan_model->delete($where);

		redirect(base_url()."".$this->uri->segment(1));
	}

	public function exist_data($IdPrimaryKey) {
		$where ="kode = '".$IdPrimaryKey."' ";
		return $this->jenis_jabatan_model->getCount($where);
	}
}
