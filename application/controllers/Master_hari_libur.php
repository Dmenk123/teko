<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_hari_libur extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('hari_libur_model');

	}

	public function index(){

		$like = null;
		$urlSearch = null;
		$order_by ='nama';
		$where = "";

		if($this->input->get('field')){
			$like = array($_GET['field'] => strtoupper($_GET['keyword']));
			$urlSearch = "?field=".$_GET['field']."&keyword=".$_GET['keyword'];
		}

		$config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch;
		$this->jumlahData 		= $this->hari_libur_model->getCount($where,$like);
		$config['total_rows'] 	= $this->jumlahData;
		$config['per_page'] 	= 10;
		$this->showData = $this->hari_libur_model->showData($where,$like,$order_by,$config['per_page'],$this->input->get('per_page'));
		//echo $this->db->last_query();
		$this->pagination->initialize($config);
		$this->template_view->load_view('master/hari_libur/hari_libur_view');
	}
	public function add(){
		$this->template_view->load_view('master/hari_libur/hari_libur_add_view');
	}
	public function add_data(){
		$this->form_validation->set_rules('NAMA', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{

			$maxIDCustomer 	= $this->hari_libur_model->getPrimaryKeyMax();
			$newId 					= $maxIDCustomer->max + 1;

			$data = array(
				'id' 	=> $newId,
				'nama' 	=> $this->input->post('NAMA'),
				'keterangan' 	=> $this->input->post('KETERANGAN')
			);

			$query = $this->hari_libur_model->insert($data);


			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}

		echo(json_encode($status));
	}

	public function edit($IdPrimaryKey){

		$where ="id = '".$IdPrimaryKey."' ";
		$this->oldData = $this->hari_libur_model->getData($where);
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}
		$this->template_view->load_view('master/hari_libur/hari_libur_edit_view');
	}



	public function edit_data(){
		$this->form_validation->set_rules('NAMA', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{

		//var_dump($pass );
			$data = array(
				'nama' 	=> $this->input->post('NAMA'),
				'keterangan' 	=> $this->input->post('KETERANGAN')
			);


			$where = array(
				'id' => $this->input->post('ID')
			);
			$query = $this->hari_libur_model->update($where,$data);
			//echo $this->db->last_query();
			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}

		echo(json_encode($status));
	}
	public function delete($IdPrimaryKey){

		$where ="id = '".$IdPrimaryKey."' ";
		$this->hari_libur_model->delete($where);

		redirect(base_url()."".$this->uri->segment(1));
	}

}
