<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('menu_model');
	}

	public function index(){
		$like 		= null;
		$order_by 	= 'id_menu, nama_menu';
		$urlSearch 	= null;

		if($this->input->get('field')){
			$like = array($_GET['field'] => $_GET['keyword']);
			$urlSearch = "?field=".$_GET['field']."&keyword=".$_GET['keyword'];
		}

		$this->load->library('pagination');

		$config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch;
		$this->jumlahData 		= $this->menu_model->getCount("",$like);
		$config['total_rows'] 	= $this->jumlahData;
		$config['per_page'] 	= 100;

		$this->pagination->initialize($config);
		$this->showData = $this->menu_model->showData("",$like,$order_by,$config['per_page'],$this->input->get('per_page'));
		$this->pagination->initialize($config);

		$this->template_view->load_view('menu/menu_view');
	}
	public function add(){

		$order_by = 'id_parent ,urutan_menu ';
		$this->dataMenu = 	$this->menu_model->showData("","",$order_by);

		$this->template_view->load_view('menu/menu_add_view');
	}

	public function add_data(){
		$this->form_validation->set_rules('NAMA_MENU', '', 'trim|required');
		$this->form_validation->set_rules('TINGKAT_MENU', '', 'trim|required');
		$this->form_validation->set_rules('URUTAN_MENU', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{
			$maxIDCustomer = $this->menu_model->getPrimaryKeyMax();
			$newId = $maxIDCustomer->max + 1;

			$data = array(
				'id_menu' => $newId,
				'id_parent' => $this->input->post('ID_PARENT'),
				'nama_menu' => $this->input->post('NAMA_MENU'),
				'judul_menu' => $this->input->post('JUDUL_MENU'),
				'link_menu' => $this->input->post('LINK_MENU')	,
				'icon_menu' => $this->input->post('ICON_MENU')	,
				'aktif_menu' => $this->input->post('AKTIF_MENU')	,
				'tingkat_menu' => $this->input->post('TINGKAT_MENU')	,
				'urutan_menu' => $this->input->post('URUTAN_MENU')	,
				'add_button' => $this->input->post('ADD_BUTTON')	,
				'edit_button' => $this->input->post('EDIT_BUTTON')	,
				'delete_button' => $this->input->post('DELETE_BUTTON')
			);

			$query = $this->menu_model->insert($data);
			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}

		echo(json_encode($status));
	}
	public function edit($IdPrimaryKey){
		$where ="id_menu = '".$IdPrimaryKey."' ";
		$this->oldData = $this->menu_model->getData($where);

		$order_by = 'id_parent ,urutan_menu ';
		$this->dataMenu = 	$this->menu_model->showData("","",$order_by);

		$this->template_view->load_view('menu/menu_edit_view');
	}
	public function edit_data(){
		$this->form_validation->set_rules('NAMA_MENU', '', 'trim|required');
		$this->form_validation->set_rules('TINGKAT_MENU', '', 'trim|required');
		$this->form_validation->set_rules('URUTAN_MENU', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{

			$data = array(
				'id_parent' => $this->input->post('ID_PARENT'),
				'nama_menu' => $this->input->post('NAMA_MENU'),
				'judul_menu' => $this->input->post('JUDUL_MENU'),
				'link_menu' => $this->input->post('LINK_MENU')	,
				'icon_menu' => $this->input->post('ICON_MENU')	,
				'aktif_menu' => $this->input->post('AKTIF_MENU')	,
				'tingkat_menu' => $this->input->post('TINGKAT_MENU')	,
				'urutan_menu' => $this->input->post('URUTAN_MENU')	,
				'add_button' => $this->input->post('ADD_BUTTON')	,
				'edit_button' => $this->input->post('EDIT_BUTTON')	,
				'delete_button' => $this->input->post('DELETE_BUTTON')
			);


			$where = array('id_menu' => $this->input->post('ID_MENU'));
			$query = $this->menu_model->update($where,$data);
			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}

		echo(json_encode($status));
	}
	public function delete($IdPrimaryKey){									

		$where ="id_menu = '".$IdPrimaryKey."' ";
		$this->menu_model->delete($where);	
		
		redirect(base_url()."".$this->uri->segment(1));				
	}
}
