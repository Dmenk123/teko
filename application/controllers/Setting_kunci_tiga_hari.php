<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_kunci_tiga_hari extends CI_Controller {

	public function __construct() {
		parent::__construct();

		/*$this->load->model('c_security_user_model');
		$this->load->model('kategori_user_model');
		$this->load->model('user_model');*/
		$this->load->model('m_kunci_tiga_hari','kunci_mod');
		$this->load->helper('indonesiandate');
		$this->load->model('global_model');
	}

	public function index(){
		$count = $this->kunci_mod->hitung_data();
		$count_instansi = $this->kunci_mod->hitung_data_instansi();
		$this->selisih = $count_instansi - $count;
		$this->template_view->load_view('setting_kunci_tiga_hari/set_kunci_tiga_hari_view');
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
		$data_log = array();
		$trun = $this->db->query('DELETE FROM t_kunci_tiga_hari');
		for ($i=0; $i < count($this->input->post('cek_kunci')); $i++) { 
			$q = "
				INSERT INTO t_kunci_tiga_hari (id, kode_instansi, tanggal, is_kunci) 
				VALUES (uuid_generate_v1(), '".$this->input->post('cek_kunci')[$i]."', '".date('Y-m-d H:i:s')."', 'T') RETURNING id";
			$res = $this->db->query($q);
			$data_log[$i]['opd'] = $this->input->post('cek_kunci')[$i];
			$data_log[$i]['tgl'] = $this->input->post('cek_kunci')[$i];
			$data_log[$i]['val'] = 'T';
		};
		
		$data_log_ins = [
			'id_user'			=> $this->session->userdata()['id_karyawan'],
			'aksi'				=> 'SETTING KUNCI TIGA HARI',
			'tanggal'			=> date('Y-m-d H:i:s'),
			'data'				=> json_encode($data_log),
			'file_lampiran'		=> null
		];
		$this->global_model->save($data_log_ins,'log_tekocak');

		echo json_encode([
			'status' => true
		]);
	}
}
