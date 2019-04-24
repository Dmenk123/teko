<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_kunci_upload extends CI_Controller {

	public function __construct() {
		parent::__construct();

		/*$this->load->model('c_security_user_model');
		$this->load->model('kategori_user_model');
		$this->load->model('user_model');*/
		$this->load->model('m_kunci_upload','kunci_mod');
		$this->load->helper('indonesiandate');

	}

	public function index(){
		$this->template_view->load_view('setting_kunci_upload/set_kunci_upload_view');
	}

	public function get_data(){
		$list = $this->kunci_mod->get_datatables();
				
		$data = array();
		$no = $this->input->post('start');
		foreach ($list as $pages) {
			$no++;
			$row = array(); 
			$row[] = $no;
			$row[] = $pages->nama;
			
			if ($pages->is_kunci == 'T') {
				$row[] = '<div><span><input type="checkbox" name="cek_kunci[]" value="'.$pages->kode.'" checked class="cek_kunci"></span></div>';
			}else{
				$row[] = '<div><span><input type="checkbox" name="cek_kunci[]" value="'.$pages->kode.'" class="cek_kunci"></span></div>';
			}
			
			$data[] = $row;
		}
	
		$output = array(
						"draw" 				=> $this->input->post('draw'),
						"recordsTotal" 		=> $this->kunci_mod->count_all(),
						"recordsFiltered" 	=> $this->kunci_mod->count_filtered(),
						"data" 				=> $data,
				);
		//output to json format
		echo json_encode($output);
	}


	public function simpan_data(){
		$trun = $this->db->query('DELETE FROM t_kunci_upload');
		//$data = count($this->input->post('cek_kunci'));
		for ($i=0; $i < count($this->input->post('cek_kunci')); $i++) { 
			if ($trun) {
				$id = $this->kunci_mod->get_last_id();
				$data = [
					'id' => (int)$id->nextval,
					'kode_instansi' => $this->input->post('cek_kunci')[$i],
					'tanggal' => date('Y-m-d H:i:s'),
					'is_kunci' => 'T'
				];
				$this->kunci_mod->insert($data);
			}
		};


		echo json_encode([
			'status' => true
		]);
	}


	/*public function add_data(){
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
	}*/

}
