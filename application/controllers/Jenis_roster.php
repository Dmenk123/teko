<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jenis_roster extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('jenis_roster_model');
		$this->load->model('jam_kerja_model');

	}

	public function index(){

		$like = null;
		$urlSearch = null;
		$order_by ='m_jenis_roster.nama';
		$where = "";

		if($this->input->get('field')){
			$like = array($_GET['field'] => strtoupper($_GET['keyword']));
			$urlSearch = "?field=".$_GET['field']."&keyword=".$_GET['keyword'];
		}

		$config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch;
		$this->jumlahData 		= $this->jenis_roster_model->getCount($where,$like);
		$config['total_rows'] 	= $this->jumlahData;
		$config['per_page'] 	= 10;
		$this->showData = $this->jenis_roster_model->showData($where,$like,$order_by,$config['per_page'],$this->input->get('per_page'));
		//echo $this->db->last_query();
		$this->pagination->initialize($config);
		$this->template_view->load_view('master/jenis_roster/jenis_roster_view');
	}
	public function add(){


		$this->dataJamKerja = $this->jam_kerja_model->showData("","","nama");

		$this->template_view->load_view('master/jenis_roster/jenis_roster_add_view');
	}
	public function add_data(){
		$this->form_validation->set_rules('NAMA', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{

			$where ="kode = '".$this->input->post('KODE')."' ";
			$this->kembarKode = $this->jenis_roster_model->getData($where);

			$where ="label = '".$this->input->post('LABEL')."' ";
			$this->kembarLabel = $this->jenis_roster_model->getData($where);

			if($this->kembarKode){
				$status = array('status' => false , 'pesan' => 'Maaf, Kode sudah digunakan.');
			}
			elseif($this->kembarLabel){
				$status = array('status' => false , 'pesan' => 'Maaf, Label sudah digunakan.');
			}
			else{
				$this->load->library('encrypt_decrypt');

				$data = array(
					'id' 	=> $this->encrypt_decrypt->new_id(),
					'kode' 	=> $this->input->post('KODE'),
					'nama' 	=> $this->input->post('NAMA'),
					'label' 	=> $this->input->post('LABEL'),
					'status' 	=> $this->input->post('STATUS'),
					'id_jam_kerja' 	=> $this->input->post('ID_JAM_KERJA'),
					'keterangan' 	=> $this->input->post('KETERANGAN'),
					'urut' 	=> $this->input->post('URUT')
				);

				$query = $this->jenis_roster_model->insert($data);
				$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
			}
		}

		echo(json_encode($status));
	}

	public function edit($IdPrimaryKey){

		$where ="id = '".$IdPrimaryKey."' ";
		$this->oldData = $this->jenis_roster_model->getData($where);
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}

		$this->dataJamKerja = $this->jam_kerja_model->showData("","","nama");
		$this->template_view->load_view('master/jenis_roster/jenis_roster_edit_view');
	}



	public function edit_data(){
		$this->form_validation->set_rules('NAMA', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{

		//var_dump($pass );
			$data = array(
				'kode' 	=> $this->input->post('KODE'),
				'nama' 	=> $this->input->post('NAMA'),
				'label' 	=> $this->input->post('LABEL'),
				'status' 	=> $this->input->post('STATUS'),
				'id_jam_kerja' 	=> $this->input->post('ID_JAM_KERJA'),
				'keterangan' 	=> $this->input->post('KETERANGAN'),
				'urut' 	=> $this->input->post('URUT')
			);


			$where = array(
				'id' => $this->input->post('ID')
			);
			$query = $this->jenis_roster_model->update($where,$data);
			//echo $this->db->last_query();
			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}

		echo(json_encode($status));
	}
	public function delete($IdPrimaryKey){

		$where ="id = '".$IdPrimaryKey."' ";
		$this->jenis_roster_model->delete($where);

		redirect(base_url()."".$this->uri->segment(1));
	}

}
