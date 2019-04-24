<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_agama extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('agama_model');

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
		$this->jumlahData 		= $this->agama_model->getCount($where,$like);
		$config['total_rows'] 	= $this->jumlahData;
		$config['per_page'] 	= 10;
		$this->showData = $this->agama_model->showData($where,$like,$order_by,$config['per_page'],$this->input->get('per_page'));
		//echo $this->db->last_query();
		$this->pagination->initialize($config);
		$this->template_view->load_view('master/agama/agama_view');
	}
	public function add(){
		$this->template_view->load_view('master/agama/agama_add_view');
	}
	public function add_data(){
		$this->form_validation->set_rules('NAMA', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{

			$maxIDCustomer 	= $this->agama_model->getPrimaryKeyMax();
			$newId 					= $maxIDCustomer->max + 1;

			if(	$newId < 10){
				$newId = '0'.$newId;
			}

			$data = array(
				'kode' 	=> $newId,
				'nama' 	=> $this->input->post('NAMA')
			);

			$query = $this->agama_model->insert($data);


			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}

		echo(json_encode($status));
	}

	public function edit($IdPrimaryKey){

		$where ="kode = '".$IdPrimaryKey."' ";
		$this->oldData = $this->agama_model->getData($where);
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}
		$this->template_view->load_view('master/agama/agama_edit_view');
	}



	public function edit_data(){
		$this->form_validation->set_rules('NAMA', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{


			$this->load->library('encrypt_decrypt');
			$pass = $this->encrypt_decrypt->dec_enc('encrypt',$this->input->post('PASSWORD'));
		//var_dump($pass );
			$data = array(
				'nama' => $this->input->post('NAMA')
			);


			$where = array(
				'kode' => $this->input->post('KODE')
			);
			$query = $this->agama_model->update($where,$data);
			//echo $this->db->last_query();
			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}

		echo(json_encode($status));
	}
	public function delete($IdPrimaryKey){

		$where ="kode = '".$IdPrimaryKey."' ";
		$this->agama_model->delete($where);

		echo $this->db->last_query();
				//	redirect(base_url()."".$this->uri->segment(1));
	}

}
