<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_user extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('c_security_user_model');
		$this->load->model('kategori_user_model');
		$this->load->model('t_hak_akses_model');
		$this->load->model('user_model');

	}

	public function index(){

		$like = null;
		$urlSearch = null;
		$order_by ='fullname';
		$where = "username != ''";

		if($this->input->get('field')){
			$like = array($_GET['field'] => strtoupper($_GET['keyword']));
			$urlSearch = "?field=".$_GET['field']."&keyword=".$_GET['keyword'];
		}

		$config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch;
		$this->jumlahData 		= $this->user_model->getCount($where,$like);
		$config['total_rows'] 	= $this->jumlahData;
		$config['per_page'] 	= 10;
		$this->showData = $this->user_model->showData($where,$like,$order_by,$config['per_page'],$this->input->get('per_page'));
		//echo $this->db->last_query();
		$this->pagination->initialize($config);
		$this->template_view->load_view('user/user_view');
	}
	public function add(){
		$order_by = 'nama_kategori_user';
		$this->dataKategoriUser = 	$this->kategori_user_model->showData("","",$order_by);
		$this->template_view->load_view('user/user_add_view');
	}
	public function add_data(){
		$this->form_validation->set_rules('FULLNAME', '', 'trim|required');
		$this->form_validation->set_rules('PASSWORD', '', 'trim|required');
		$this->form_validation->set_rules('USERNAME', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{

			$this->load->library('encrypt_decrypt');
			$pass = $this->encrypt_decrypt->dec_enc('encrypt',$this->input->post('PASSWORD'));
			$data = array(
				'id' => $this->encrypt_decrypt->new_id(),
				'photo' => 'img/user/no_photo.jpg',
				'fullname' => $this->input->post('FULLNAME'),
				'username' => $this->input->post('USERNAME'),
				'id_kategori_user' => $this->input->post('ID_KATEGORI_USER'),
				'password_new' => $pass,
				'active' => $this->input->post('ACTIVE')
			);

			$query = $this->c_security_user_model->insert($data);


			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}

		echo(json_encode($status));
	}

	public function edit($IdPrimaryKey){

		$this->load->library('encrypt_decrypt');

		$where ="id = '".$IdPrimaryKey."' ";
		$this->oldData = $this->c_security_user_model->getData($where);
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}
		$orderBy = " nama_kategori_user";
		$this->dataKategoriUser = 	$this->kategori_user_model->showData("",$orderBy);
		$this->template_view->load_view('user/user_edit_view');
	}



	public function edit_data(){
		$this->form_validation->set_rules('ID', '', 'trim|required');
		$this->form_validation->set_rules('FULLNAME', '', 'trim|required');
		$this->form_validation->set_rules('PASSWORD', '', 'trim|required');
		$this->form_validation->set_rules('USERNAME', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{


			$this->load->library('encrypt_decrypt');
			$pass = $this->encrypt_decrypt->dec_enc('encrypt',$this->input->post('PASSWORD'));
		//var_dump($pass );
			$data = array(
				'fullname' => $this->input->post('FULLNAME'),
				'username' => $this->input->post('USERNAME'),
				'id_kategori_user' => $this->input->post('ID_KATEGORI_USER'),
				'password_new' => $pass,
				'active' => $this->input->post('ACTIVE')
			);


			$where = array(
					'id' => $this->input->post('ID')
			);
			$query = $this->c_security_user_model->update($where,$data);
			//echo $this->db->last_query();
			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}

		echo(json_encode($status));
	}
	public function delete($IdPrimaryKey){

		$where ="id = '".$IdPrimaryKey."' ";
		$this->c_security_user_model->delete($where);

		redirect(base_url()."".$this->uri->segment(1));
	}

}
