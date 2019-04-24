<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {



	public function __construct() {
		parent::__construct();
		$this->load->model('c_security_user_model');
		$this->load->model('kategori_user_model');

		$session = $this->session->userdata('id_karyawan');
		if($session){
			redirect('dashboard', 'refresh');
		}
	}

	public function index(){
		/** UNTUK MELIHAT PASSWORD :D */
		// $this->load->library('encrypt_decrypt');
		// echo "<pre>";
		// print_r ($this->encrypt_decrypt->dec_enc('decrypt', 'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09'));
		// echo "</pre>";
		$sess = $this->session->userdata();
		// if($sess){
			// redirect(base_url('dashboard'), 'refresh');
		// }else{


		$this->load->view('template/awal_view');

	}

	public function security(){
		/** UNTUK MELIHAT PASSWORD :D */
		// $this->load->library('encrypt_decrypt');
		// echo "<pre>";
		// print_r ($this->encrypt_decrypt->dec_enc('decrypt', 'a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09'));
		// echo "</pre>";
		$sess = $this->session->userdata();
		// if($sess){
			// redirect(base_url('dashboard'), 'refresh');
		// }else{


		$this->load->view('template/login_view');

	}

	public function login_data(){
		/// libarbry encrypt password
		$this->load->library('encrypt_decrypt');

		$this->form_validation->set_rules('USERNAME_LOGIN', '', 'trim|required');
		$this->form_validation->set_rules('PASSWORD_LOGIN', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal Login, pastikan telah mengisi semua inputan.');
		}
		else{
			$data = array(
				'username' => $this->input->post('USERNAME'),
				'id_kategori_user' => $this->input->post('ID_KATEGORI_USER'),
				'password' => $this->input->post('PASSWORD')
			);

			$pass = $this->encrypt_decrypt->dec_enc('encrypt',$this->input->post('PASSWORD_LOGIN'));
			//var_dump($pass);
			$dataUser = $this->c_security_user_model->getData("username = '".$this->input->post('USERNAME_LOGIN') ."' and password_new='".$pass."' and active='t'");
			//$dataUser = $this->c_security_user_model->getData("username = '".$this->input->post('USERNAME_LOGIN') ."' and active='t'");
			//echo $this->db->last_query();
			//var_dump($dataUser);
			if($dataUser){
				//allow hanya untuk developer dan bkd
				//if ($dataUser->id_kategori_user == '1' || $dataUser->id_kategori_user == '3') {

					$dataKatUser = $this->kategori_user_model->getData("id_kategori_user = '".$dataUser->id_kategori_user."' ");
					$data_ip = $this->c_security_user_model->get_data_ip($dataUser->kode_instansi);
					//echo $this->db->last_query();
					$sess_array = array(
						'nama_karyawan' => $dataUser->fullname,
						'id_karyawan' => $dataUser->id,
						'id_kategori_karyawan' => $dataUser->id_kategori_user,
						'username' => $dataUser->username,
						'kode_instansi' => $dataUser->kode_instansi,
						'kategori_karyawan' => $dataKatUser->nama_kategori_user,
						'data_ip' => $data_ip
					);
					$this->session->set_userdata($sess_array);

					$status = array('status' => true,'redirect_link' => base_url()."dashboard");
					//gagal jika maintenace
					//$status = array('status' => false,'pesan' => 'Mohon Maaf Untuk sementara ini anda tidak dapat login.<br><br>');

				// }else{
				// 	$status = array('status' => false,'pesan' => 'Maaf untuk sementara ini login tidak bisa digunakan.<br><br>');
				// }

			}
			else{
				//cek apakah pegawai
				$peg = $this->db->query("
					select
						*
					from
						m_pegawai
					where
						nip = '".$this->input->post('USERNAME_LOGIN')."' and
						kode_status_pegawai <> '5' and
						aktif
				")->row_array();
				if($peg) {
					$masuk = false;
					if($peg['password'] <> null) {
						if(sha1(md5($this->input->post('PASSWORD_LOGIN'))) == $peg['password']) {
							$masuk = true;
						}
					}
					else {
						if($this->input->post('USERNAME_LOGIN') == $this->input->post('PASSWORD_LOGIN')) {
							$masuk = true;
							$update = "
								UPDATE m_pegawai
								SET
									password = '".sha1(md5($this->input->post('PASSWORD_LOGIN')))."'
								WHERE
									id  = '".$peg['id']."'";

							$update_password = $this->db->query($update);
						}
					}

					if($masuk) {
						$data_ip = $this->c_security_user_model->get_data_ip($dataUser->kode_instansi);
						//echo $this->db->last_query();
						$sess_array = array(
							'nama_karyawan' => $peg['nama'],
							'id_karyawan' => $peg['id'],
							'id_kategori_karyawan' => 12,
							'username' => $this->input->post('USERNAME_LOGIN'),
							'kode_instansi' => '',
							'kategori_karyawan' => 'Pegawai',
							'data_ip' => $data_ip
						);
						$this->session->set_userdata($sess_array);

						$status = array('status' => true,'redirect_link' => base_url()."dashboard");
					}
					else {
						$status = array('status' => false,'pesan' => 'Login gagal, pastikan Username dan Password anda benar.<br><br>');
					}
				}
				else {
					$status = array('status' => false,'pesan' => 'Login gagal, pastikan Username dan Password anda benar.<br><br>', 'pass' => $pass);
				}
			}
		}

		echo(json_encode($status));
	}


}
