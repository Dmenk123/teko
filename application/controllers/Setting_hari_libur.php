<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_hari_libur extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('m_setting_hari_libur','s_libur');
		$this->load->helper('indonesiandate');
	}

	public function index(){
		$this->data_libur = $this->s_libur->get_data_libur();
		$this->template_view->load_view('setting_hari_libur/set_hari_libur_view');
	}

	public function get_data(){
		$list = $this->s_libur->get_datatables($this->input->get('tahun_tampil'));
				
		$data = array();
		$no = $this->input->post('start');
		foreach ($list as $pages) {
			$no++;
			$row = array(); 
			$row[] = $no;
			$row[] = $pages->tanggal;
			$row[] = $pages->nama;
			$row[] = $pages->keterangan;
			$row[] = '<button type="button" class="btn-right btn btn-warning btn-xs" data-toggle="modal" onclick="edit_transaksi('."'".$pages->id."'".')">
                    	<span class="glyphicon glyphicon-pencil"></span>
                     </button>
                     <button type="button" class="btn-right btn btn-danger btn-xs" data-toggle="modal" onclick="hapus_transaksi('."'".$pages->id."'".')">
                    	<span class="glyphicon glyphicon-remove"></span>
                     </button>';
			$data[] = $row;
		}
	
		$output = array(
						"draw" 				=> $this->input->post('draw'),
						"recordsTotal" 		=> $this->s_libur->count_all($this->input->get('tahun_tampil')),
						"recordsFiltered" 	=> $this->s_libur->count_filtered($this->input->get('tahun_tampil')),
						"data" 				=> $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function tambah_hari_libur_insert(){
		$tanggal_raw	=	explode('/',$this->input->post('TGL_LIBUR'));
		$tanggal	=	$tanggal_raw[2]."-".$tanggal_raw[1]."-".$tanggal_raw[0];

		$id_libur 	= $this->input->post('ID_HARI_LIBUR'); 
		$keterangan = $this->input->post('KETERANGAN'); 

		$data['keterangan'] = $keterangan;
		$data['tanggal'] = $tanggal;
		$data['id_libur'] = $id_libur;
		$data['userupd'] = $this->session->userdata('id_karyawan');
		
		$insert = $this->s_libur->insert($data);
		if ($insert) {
			$status = true;
		}else{
			$status = false;
		}

		echo json_encode([
			'status' => $status
		]);
	}

	public function hapus_hari_libur()
	{
		$id = $this->input->post('id');
		$where = "id = '".$id."' ";

		$data_row = $this->s_libur->get_by_id($id);
		$delete = $this->s_libur->delete($where);

		if ($delete) {
			$status = true;
		}else{
			$status = false;
		}

		echo json_encode([
			'status' => $status,
			'namaharilibur' => $data_row->nama
		]);
	}

	public function edit_data()
	{
		$data_row = $this->s_libur->get_by_id($this->input->post('id'));
		echo json_encode([
			'status' => true,
			'data' => $data_row
		]);
	}

	public function update_data()
	{
		$tanggal_raw	=	explode('/',$this->input->post('TGL_LIBUR_EDIT'));
		$tanggal	=	$tanggal_raw[2]."-".$tanggal_raw[1]."-".$tanggal_raw[0];

		$id_hari_libur = $this->input->post('ID_HARI_LIBUR_EDIT');
		$keterangan = $this->input->post('KETERANGAN_EDIT');
		$id = $this->input->post('ID_EDIT');

		$where = "id = '".$id."' ";
		$data = array(
			'id' => $id,
			'keterangan' => $keterangan,
			'tanggal' => $tanggal,
			'id_libur' => $id_hari_libur,
			'timeupd' => date('Y-m-d H:i:s'),
			'userupd' => $this->session->userdata('id_karyawan'),
		);

		$update = $this->s_libur->update_data($where, $data);

		if ($update) {
			$status = true;
		}else{
			$status = false;
		}

		echo json_encode([
			'status' => $status
		]);
	}

	/*public function simpan_data(){
		$trun = $this->db->query('DELETE FROM t_kunci_tiga_hari');
		for ($i=0; $i < count($this->input->post('cek_kunci')); $i++) { 
			$q = "
				INSERT INTO t_kunci_tiga_hari (id, kode_instansi, tanggal, is_kunci) 
				VALUES (uuid_generate_v1(), '".$this->input->post('cek_kunci')[$i]."', '".date('Y-m-d H:i:s')."', 'T') RETURNING id";
			$res = $this->db->query($q);
		};
		
		echo json_encode([
			'status' => true
		]);
	}*/
}
