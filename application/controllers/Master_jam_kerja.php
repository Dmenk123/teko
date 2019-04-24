<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_jam_kerja extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('jam_kerja_model');

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
		$this->jumlahData 		= $this->jam_kerja_model->getCount($where,$like);
		$config['total_rows'] 	= $this->jumlahData;
		$config['per_page'] 	= 10;
		$this->showData = $this->jam_kerja_model->showData($where,$like,$order_by,$config['per_page'],$this->input->get('per_page'));
		//echo $this->db->last_query();
		$this->pagination->initialize($config);
		$this->template_view->load_view('master/jam_kerja/jam_kerja_view');
	}
	public function add(){
		$this->template_view->load_view('master/jam_kerja/jam_kerja_add_view');
	}
	public function add_data(){
		$this->form_validation->set_rules('NAMA', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{

			$where ="nama = '".$this->input->post('NAMA')."' ";
			$this->oldData = $this->jam_kerja_model->getData($where);

			if($this->oldData){
				$status = array('status' => false , 'pesan' => 'Silahkan ganti Kode.. karena kode sudah terpakai.');
			}
			else{
				$this->load->library('encrypt_decrypt');

				$data = array(
					'id' 	=> $this->encrypt_decrypt->new_id(),
					'jam_akhir_scan_masuk' 	=> $this->input->post('JAM_AKHIR_SCAN_MASUK'),
					'jam_akhir_scan_pulang' 	=> $this->input->post('JAM_AKHIR_SCAN_PULANG'),
					'jam_masuk' 	=> $this->input->post('JAM_MASUK'),
					'jam_mulai_scan_masuk' 	=> $this->input->post('JAM_MULAI_SCAN_MASUK'),
					'jam_mulai_scan_pulang' 	=> $this->input->post('JAM_MULAI_SCAN_PULANG'),
					'jam_pulang' 	=> $this->input->post('JAM_PULANG'),
					'jml_hari_kerja' 	=> $this->input->post('JML_HARI_KERJA'),
					'nama' 	=> $this->input->post('NAMA'),
					'toleransi_pulang_cepat' 	=> $this->input->post('TOLERANSI_PULANG_CEPAT'),
					'toleransi_terlambat' 	=> $this->input->post('TOLERANSI_TERLAMBAT'),
					'pulang_hari_berikutnya' 	=> $this->input->post('PULANG_HARI_BERIKUTNYA'),
					'masuk_hari_sebelumnya' 	=> $this->input->post('MASUK_HARI_SEBELUMNYA'),
					'keterangan' 	=> $this->input->post('KETERANGAN')
				);
				$query = $this->jam_kerja_model->insert($data);
				$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
			}
		}

		echo(json_encode($status));
	}

	public function edit($IdPrimaryKey){

		$where ="id = '".$IdPrimaryKey."' ";
		$this->oldData = $this->jam_kerja_model->getData($where);
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}
		$this->template_view->load_view('master/jam_kerja/jam_kerja_edit_view');
	}



	public function edit_data(){
		$this->form_validation->set_rules('NAMA', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{

		//var_dump($pass );
		$data = array(
			'jam_akhir_scan_masuk' 	=> $this->input->post('JAM_AKHIR_SCAN_MASUK'),
			'jam_akhir_scan_pulang' 	=> $this->input->post('JAM_AKHIR_SCAN_PULANG'),
			'jam_masuk' 	=> $this->input->post('JAM_MASUK'),
			'jam_mulai_scan_masuk' 	=> $this->input->post('JAM_MULAI_SCAN_MASUK'),
			'jam_mulai_scan_pulang' 	=> $this->input->post('JAM_MULAI_SCAN_PULANG'),
			'jam_pulang' 	=> $this->input->post('JAM_PULANG'),
			'jml_hari_kerja' 	=> $this->input->post('JML_HARI_KERJA'),
			'nama' 	=> $this->input->post('NAMA'),
			'toleransi_pulang_cepat' 	=> $this->input->post('TOLERANSI_PULANG_CEPAT'),
			'toleransi_terlambat' 	=> $this->input->post('TOLERANSI_TERLAMBAT'),
			'pulang_hari_berikutnya' 	=> $this->input->post('PULANG_HARI_BERIKUTNYA'),
			'masuk_hari_sebelumnya' 	=> $this->input->post('MASUK_HARI_SEBELUMNYA'),
			'keterangan' 	=> $this->input->post('KETERANGAN')
		);


			$where = array(
				'id' => $this->input->post('ID')
			);
			$query = $this->jam_kerja_model->update($where,$data);
			//echo $this->db->last_query();
			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}

		echo(json_encode($status));
	}
	public function delete($IdPrimaryKey){

		$where ="id = '".$IdPrimaryKey."' ";
		$this->jam_kerja_model->delete($where);

		redirect(base_url()."".$this->uri->segment(1));
	}

}
