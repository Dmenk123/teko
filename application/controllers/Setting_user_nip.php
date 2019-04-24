<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_user_nip extends CI_Controller {



	public function __construct() {
		parent::__construct();
		ini_set('display_errors', 1);
		$this->load->model('c_security_user_model');
		$this->load->model('kategori_user_model');
		$this->load->model('t_hak_akses_model');
		$this->load->model('usernip_model');
		$this->load->model('global_model');

	}

	public function index(){

		$like = null;
		$urlSearch = null;
		$order_by ='nama';
		$where = "kode_status_pegawai != '5'";

		if($this->input->get('field')){
			$like = array($_GET['field'] => strtoupper($_GET['keyword']));
			$urlSearch = "?field=".$_GET['field']."&keyword=".$_GET['keyword'];
		}

		$config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch;
		$this->jumlahData 		= $this->usernip_model->getCount($where,$like);
		$config['total_rows'] 	= $this->jumlahData;
		$config['per_page'] 	= 10;
		$this->showData = $this->usernip_model->showData($where,$like,$order_by,$config['per_page'],$this->input->get('per_page'));
		//echo $this->db->last_query();
		$this->pagination->initialize($config);
		$this->template_view->load_view('user_nip/usernip_view');
	}

	public function edit($IdPrimaryKey){

		$this->load->library('encrypt_decrypt');

		$where ="id = '".$IdPrimaryKey."' ";
		$this->oldData = $this->usernip_model->getData($where);
		
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}

		// $orderBy = " nama";
		// $this->dataKategoriUser = 	$this->kategori_user_model->showData("",$orderBy);

		#$this->template_view->load_view('user/user_edit_view');
		//$data['instansi']		= $this->kategori_user_model->select_data("m_instansi");
		$this->template_view->load_view('user_nip/usernip_edit_view');
	}

	public function edit_data(){
		$this->form_validation->set_rules('ACTIVE', '', 'required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{
			if ($this->input->post('RESET_PASS') == 't') {
				// $pass_reset = sha1(md5($this->input->post('NIP')));
				$this->db->trans_begin();
				$data = array('password' => null);
				$where = array('id' => $this->input->post('ID'));
				$this->usernip_model->updateData($data, $where, 'm_pegawai');

				#LOG DATA
				$select = $this->global_model->get_by_id('m_pegawai',$where);
				$data_log = [
								'id_user'	=> $this->session->userdata()['id_karyawan'],
								'aksi'		=> 'EDIT USER NIP (RESET PASSWORD)',
								'tanggal'	=> date('Y-m-d H:i:s'),
								'data'		=> json_encode($select)
							];

				$query = $this->c_security_user_model->update($where,$data);
				$this->global_model->save($data_log,'log_tekocak');

				$this->db->trans_status();
				if ($this->db->trans_status() === FALSE){
					$this->db->trans_rollback();
					$status = array('status' => false , 'redirect_link' => base_url()."".$this->uri->segment(1));
				}
				else{
					$this->db->trans_commit();
					$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
				}
			}
		}
		echo(json_encode($status));
	}

	/* public function add(){
		$sess = $this->session->userdata();
	
		$order_by = 'nama';
		$this->dataKategoriUser = 	$this->kategori_user_model->showData("","",$order_by);
		#$this->template_view->load_view('user/user_add_view');
		$data['instansi']		= $this->kategori_user_model->select_data("m_instansi");
		$this->template_view->load_view('user/user_add_view',$data);
	} */

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
				'kode_instansi' => $this->input->post('OPD'),
				'id_kategori_user' => $this->input->post('ID_KATEGORI_USER'),
				'password_new' => $pass,
				'active' => $this->input->post('ACTIVE')
			);

			$data_log = [
							'id_user'	=> $this->session->userdata()['id_karyawan'],
							'aksi'		=> 'ADD USER',
							'tanggal'	=> date('Y-m-d H:i:s'),
							'data'		=> json_encode($data)
						];

			$query = $this->c_security_user_model->insert($data);
			$this->global_model->save($data_log,'log_tekocak');
			

			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}

		echo(json_encode($status));
	}*/

	/* public function delete($IdPrimaryKey){
		$where1 =  ['id' => $IdPrimaryKey];
		$select = $this->global_model->get_by_id('c_security_user_new',$where1);
		$data_log = [
							'id_user'	=> $this->session->userdata()['id_karyawan'],
							'aksi'		=> 'REMOVE USER',
							'tanggal'	=> date('Y-m-d H:i:s'),
							'data'		=> json_encode($select)
						];

		$where ="id = '".$IdPrimaryKey."' ";
		$this->c_security_user_model->delete($where);
		$this->global_model->save($data_log,'log_tekocak');
		redirect(base_url()."".$this->uri->segment(1));
	} */

}
