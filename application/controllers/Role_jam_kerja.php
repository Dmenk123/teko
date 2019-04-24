<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role_jam_kerja extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('role_jam_kerja_model');
		$this->load->model('jam_kerja_model');
		$this->load->model('hari_model');

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
		$this->jumlahData 		= $this->role_jam_kerja_model->getCount($where,$like);
		$config['total_rows'] 	= $this->jumlahData;
		$config['per_page'] 	= 10;
		$this->showData = $this->role_jam_kerja_model->showData($where,$like,$order_by,$config['per_page'],$this->input->get('per_page'));
		//echo $this->db->last_query();
		$this->pagination->initialize($config);
		$this->template_view->load_view('master/role_jam_kerja/role_jam_kerja_view');
	}
	public function add(){
		$this->template_view->load_view('master/role_jam_kerja/role_jam_kerja_add_view');
	}
	public function add_data(){
		$this->form_validation->set_rules('NAMA', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{

			$where ="nama = '".$this->input->post('NAMA')."' ";
			$this->oldData = $this->role_jam_kerja_model->getData($where);

			if($this->oldData){
				$status = array('status' => false , 'pesan' => 'Silahkan ganti Kode.. karena kode sudah terpakai.');
			}
			else{
				$this->load->library('encrypt_decrypt');

				$data = array(
					'id' 	=> $this->encrypt_decrypt->new_id(),
					'nama' 	=> $this->input->post('NAMA'),
					'keterangan' 	=> $this->input->post('KETERANGAN')
				);
				$query = $this->role_jam_kerja_model->insert($data);
				$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
			}
		}

		echo(json_encode($status));
	}


	public function add_data_detail(){
		$this->form_validation->set_rules('ID_ROLE', '', 'trim|required');
		$this->form_validation->set_rules('ID_JAM_KERJA', '', 'trim|required');
		$this->form_validation->set_rules('ID_HARI', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{


				$this->load->library('encrypt_decrypt');

				$data = array(
					'id' 	=> $this->encrypt_decrypt->new_id(),
					'id_hari' 	=> $this->input->post('ID_HARI'),
					'id_role' 	=> $this->input->post('ID_ROLE'),
					'id_jam_kerja' 	=> $this->input->post('ID_JAM_KERJA')
				);
				$query = $this->role_jam_kerja_model->insertDetail($data);
				$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1)."/edit/".$this->input->post('ID_ROLE'));

		}

		echo(json_encode($status));
	}


	public function edit($IdPrimaryKey){

		$where ="id = '".$IdPrimaryKey."' ";
		$this->oldData = $this->role_jam_kerja_model->getData($where);
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}

				$this->dataHari = $this->hari_model->showData("","","id");
				$this->dataJamKerja = $this->jam_kerja_model->showData("","","nama");

				$whereDetail ="m_role_jam_kerja_detail.id_role = '".$IdPrimaryKey."' ";
				$this->showDataDetail = $this->role_jam_kerja_model->showDataDetail($whereDetail,"","m_hari.id");

		$this->template_view->load_view('master/role_jam_kerja/role_jam_kerja_edit_view');
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
			$query = $this->role_jam_kerja_model->update($where,$data);
			//echo $this->db->last_query();
			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}

		echo(json_encode($status));
	}
	public function delete($IdPrimaryKey){

		$where ="id = '".$IdPrimaryKey."' ";
		$this->role_jam_kerja_model->delete($where);

		redirect(base_url()."".$this->uri->segment(1));
	}
	public function delete_detail($IdPrimaryKey){

		$where ="id = '".$IdPrimaryKey."' ";
		$this->oldData = $this->role_jam_kerja_model->getDataDetail($where);
		$idRole = $this->oldData->id_role;

		$where ="id = '".$IdPrimaryKey."' ";
		$this->role_jam_kerja_model->deleteDetail($where);

		redirect(base_url()."".$this->uri->segment(1)."/edit/".$idRole);
	}

}
