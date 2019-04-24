<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jenis_ijin_cuti extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('jenis_ijin_cuti_model');

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
		$this->jumlahData 		= $this->jenis_ijin_cuti_model->getCount($where,$like);
		$config['total_rows'] 	= $this->jumlahData;
		$config['per_page'] 	= 10;
		$this->showData = $this->jenis_ijin_cuti_model->showData($where,$like,$order_by,$config['per_page'],$this->input->get('per_page'));
		//echo $this->db->last_query();
		$this->pagination->initialize($config);
		$this->template_view->load_view('master/jenis_ijin_cuti/jenis_ijin_cuti_view');
	}
	public function add(){
		$this->template_view->load_view('master/jenis_ijin_cuti/jenis_ijin_cuti_add_view');
	}
	public function add_data(){
		$this->form_validation->set_rules('NAMA', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{

			$where ="kode = '".$this->input->post('KODE')."' ";
			$this->oldData = $this->jenis_ijin_cuti_model->getData($where);

			if($this->oldData){
				$status = array('status' => false , 'pesan' => 'Silahkan ganti Kode.. karena kode sudah terpakai.');
			}
			else{
				$this->load->library('encrypt_decrypt');

				$data = array(
					'id' 	=> $this->encrypt_decrypt->new_id(),
					'nama' 	=> $this->input->post('NAMA'),
					'jumlah' 	=> $this->input->post('JUMLAH'),
					'kode' 	=> $this->input->post('KODE'),
					'keterangan' 	=> $this->input->post('KETERANGAN')
				);
				$query = $this->jenis_ijin_cuti_model->insert($data);
				$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
			}
		}

		echo(json_encode($status));
	}

	public function edit($IdPrimaryKey){

		$where ="id = '".$IdPrimaryKey."' ";
		$this->oldData = $this->jenis_ijin_cuti_model->getData($where);
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}
		$this->template_view->load_view('master/jenis_ijin_cuti/jenis_ijin_cuti_edit_view');
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
				'jumlah' 	=> $this->input->post('JUMLAH'),
				'kode' 	=> $this->input->post('KODE'),
				'keterangan' 	=> $this->input->post('KETERANGAN')
			);


			$where = array(
				'id' => $this->input->post('ID')
			);
			$query = $this->jenis_ijin_cuti_model->update($where,$data);
			//echo $this->db->last_query();
			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}

		echo(json_encode($status));
	}
	public function delete($IdPrimaryKey){

		$where ="id = '".$IdPrimaryKey."' ";
		$this->jenis_ijin_cuti_model->delete($where);

		redirect(base_url()."".$this->uri->segment(1));
	}

}
