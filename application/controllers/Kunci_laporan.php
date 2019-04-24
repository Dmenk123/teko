<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kunci_laporan extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('log_laporan_model');
		$this->load->model('instansi_model');
	}


	public function index(){
		$this->bulan = array (1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);

		$year = date('Y');

		//jika kategori sbg admin skpd
		if($this->session->userdata('id_kategori_karyawan') == '4'){
			//$whereInstansi =	"m_instansi.kode ='".$this->session->userdata('kode_instansi')."' ";
			$whereInstansi = 	"m_instansi.kode = '".$this->session->userdata('kode_instansi')."'";
			$whereLog 	   = 	"m_instansi.kode = '".$this->session->userdata('kode_instansi')."' AND log_laporan.is_kunci = 'Y' AND EXTRACT(YEAR from tgl_log) = '$year'";
		}
		else{
			$whereInstansi =	"";
			$whereLog 	   = 	"EXTRACT(YEAR from tgl_log) = '$year'";
		}

		$this->dataInstansi = $this->instansi_model->showData($whereInstansi,"","nama");	
		$this->dataLog = $this->log_laporan_model->showData($whereLog,"","nama");

		/*if ($this->session->userdata('id_kategori_karyawan') == '1') {
			$this->template_view->load_view('laporan/log_laporan_view');
		}else{
			$this->template_view->load_view('template/sedang-perbaikan');
		}*/

		$this->template_view->load_view('laporan/log_laporan_view');
	}
	
	public function save(){
		$this->load->library('encrypt_decrypt');
		$this->form_validation->set_rules('bulan', '', 'trim|required');
		$this->form_validation->set_rules('tahun', '', 'trim|required');
		$bulan = $this->input->post("bulan");
		$tahun = $this->input->post("tahun");
		$id_instansi = $this->input->post('id_instansi');
		$tgl_sekarang = date('Y-m-d');
		$dapat_simpan = true;

		$namaBulan = array(
			'01' => 'JANUARI',
			'02' => 'FEBRUARI',
			'03' => 'MARET',
			'04' => 'APRIL',
			'05' => 'MEI',
			'06' => 'JUNI',
			'07' => 'JULI',
			'08' => 'AGUSTUS',
			'09' => 'SEPTEMBER',
			'10' => 'OKTOBER',
			'11' => 'NOVEMBER',
			'12' => 'DESEMBER'
		);

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, bulan dan tahun Wajib diisi.');
		}
		else{
			$hari_ini 		= $tahun."-".$bulan."-01"; 
			$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));
			
			if($this->session->userdata('id_kategori_karyawan')=='4'){
				$sudahAda	=	$this->log_laporan_model->getData("
					kd_instansi = '".$this->session->userdata('kode_instansi')."' and tgl_log = '".$this->tgl_terakhir."' and is_kunci='Y' and time_stamp_buka is null
				");
			}
			else{
				$sudahAda	=	$this->log_laporan_model->getData("
					kd_instansi = '".$id_instansi."' and tgl_log = '".$this->tgl_terakhir."' and is_kunci='Y' and time_stamp_buka is null
				");
			}
						
			if ($sudahAda)	{
				$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kunci Laporan untuk Bulan ini sudah ada.');
			}
			else 
			{
				if($this->session->userdata('id_kategori_karyawan') == '4' ){
					$kdInstansi 	= $this->session->userdata('kode_instansi');
				}
				else{
					$kdInstansi 	= $id_instansi;
				}

				$query_kode_sik = $this->db->query("select kode_sik, nama from m_instansi where kode = '".$kdInstansi."'");
				$data_kode_sik = $query_kode_sik->row();
				
				if (substr($data_kode_sik->nama, 0, 9) != 'Kecamatan') {
					if (substr($data_kode_sik->nama, 0, 9) == 'Kelurahan') {
						$is_kelurahan = true;
					}else{
						$is_kelurahan = false;
					}
					$kode_instansi_all = $kdInstansi;
					$whereQuery = "kode = '".$kode_instansi_all."'";
					$is_kecamatan = false;
				} else {
					$kode_instansi_all = substr($kdInstansi, 0, 5);
					$whereQuery = "kode LIKE '".$kode_instansi_all.'%'."'";
					$is_kecamatan = true;
				}

				//jika bukan kecamatan, hanya melakukan pengecekan update laporan
				if (!$is_kecamatan) {
					$hari_ini 					= date($this->input->post("tahun")."-".$this->input->post("bulan")."-01"); 
					$this->tgl_terakhir 		= date('Y-m-t', strtotime($hari_ini));
					
					//cek apa semua laporan sudah diupdate
					$arifyunianto = $this->cek_update_laporan($kdInstansi, $bulan, $tahun, $tgl_sekarang);
										
					$arr_isupdate = [];
					$arr_jenislap = [];
					foreach ($arifyunianto as $val) {
						if (!$val['is_update']) {
							$arr_jenislap[] = $val['jenis_lap'];
						}
						$arr_isupdate[] = $val['is_update'];
					}

					//cek apakah array arif yunianto ada false nya atau tidak
					if (in_array(false, $arr_isupdate)){
						$dapat_simpan = false;
					}else{
						$dapat_simpan = true;
					}
					
					if (!$dapat_simpan) {
						$pesan ="
							<p><strong>Gagal melakukan penguncian dikarenakan terdapat laporan yang belum update sampai tgl penguncian</strong></p>
							<p>Berikut laporan yang belum dilakukan update pada tahun ".$tahun." dan bulan ".$namaBulan[$bulan]." di tanggal ".date('d-m-Y', strtotime($tgl_sekarang))."</p>
						";

						for ($i=0; $i <count($arr_jenislap); $i++) { 
							$pesan .= "<li>".$arr_jenislap[$i]."</li>";
						}
						
						$status_req = false;
					}else{
						$status_req = true;
						$pesan ="<p><strong>Laporan Sukses dikunci</strong></p>";

						$input['id_log_laporan']	= $this->encrypt_decrypt->new_id();
						$input['kd_instansi']		= $kdInstansi;
						$input['tgl_log'] 			= $this->tgl_terakhir;
						$input['time_stamp']	 	= date('Y-m-d H:i:s');
						$input['id_user']			= $this->session->userdata('id_karyawan');
						$input['is_kunci'] 			= 'Y';
						
						$query = $this->log_laporan_model->insert($input);
					}

					$status = array(
						'status' => $status_req,
						'redirect_link' => base_url()."".$this->uri->segment(1),
						'pesan' => $pesan
					);
				}
				//jika kecamatan maka ada aturan untuk cek apakah semua kelurahan sudah di kunci atau belum
				else
				{
					$data_kecamatan = $this->log_laporan_model->cek_list_kecamatan($whereQuery);
					$whereQuery2 = "kd_instansi in (
										select kode from m_instansi where kode ilike '".$kode_instansi_all.'%'."'
									)
									and to_char(ll.tgl_log, 'YYYY') = '".$tahun."'
									and to_char(ll.tgl_log, 'MM') = '".$bulan."'
									and ll.is_kunci = 'Y'
									and ll.time_stamp_buka is null
					";
					////////////////////////////////////// start cek kuncian laporan /////////////////////////////////////
					$cek_kunci = $this->log_laporan_model->cek_kunci_kecamatan($whereQuery2);
					
					//array kode instansi yg sudah terkunci
					$arr_sdh_kunci = [];
					foreach ($cek_kunci as $value) {
						$arr_sdh_kunci[] = $value['kd_instansi'];
					}

					//array instansi yg belum terkunci
					$arr_blm_kunci = [];
					foreach ($data_kecamatan as $val) {
						//jika tidak ada pada array yg belum dikunci
						if (!in_array($val['kode'], $arr_sdh_kunci))
						{
							if (substr($val['nama'], 0, 9) != 'Kecamatan') {
								$arr_blm_kunci[] = array (
									'nama' => $val['nama'],
									'kode' => $val['kode']
								);
							}
						}
					}
					
					if ((count($data_kecamatan) - 1) !== count($cek_kunci)) {
						$dapat_simpan = false;
					}

					//jika pada step ini gagal simpan, langsung selesai
					if (!$dapat_simpan) {
						$status_req = false;
						$pesan ="
							<p><strong>Gagal melakukan penguncian dikarenakan terdapat kelurahan yang belum mengunci laporan pada bulan ".$namaBulan[$bulan]." tahun ".$tahun."</strong></p>
							<p>Berikut Kelurahan yang belum melakukan penguncian pada tahun ".$tahun." dan bulan ".$namaBulan[$bulan]."</p>
						";
						
						foreach ($arr_blm_kunci as $data) {
							$pesan .= "<li>".$data['nama']."</li>";
						}

						$status = array(
							'status' => $status_req,
							'redirect_link' => base_url()."".$this->uri->segment(1),
							'pesan' => $pesan
						);

						echo(json_encode($status));exit;
					}
					////////////////////////////////////// end cek kuncian laporan /////////////////////////////////////

					//cek apa semua laporan sudah diupdate
					$arifyunianto = $this->cek_update_laporan($kdInstansi, $bulan, $tahun, $tgl_sekarang);				
					$arr_isupdate = [];
					$arr_jenislap = [];
					foreach ($arifyunianto as $val) {
						if (!$val['is_update']) {
							$arr_jenislap[] = $val['jenis_lap'];
						}
						$arr_isupdate[] = $val['is_update'];
					}

					//cek apakah array arif yunianto ada false nya atau tidak
					if (in_array(false, $arr_isupdate)){
						$dapat_simpan = false;
					}else{
						$dapat_simpan = true;
					}
					
					//jika sampai akhir flag dapat simpan == true
					if ($dapat_simpan) {
						$hari_ini 					= date($this->input->post("tahun")."-".$this->input->post("bulan")."-01"); 
						$this->tgl_terakhir 		= date('Y-m-t', strtotime($hari_ini));
						
						$input['id_log_laporan']	= $this->encrypt_decrypt->new_id();
						$input['kd_instansi']		= $kdInstansi;
						$input['tgl_log'] 			= $this->tgl_terakhir;
						$input['time_stamp']	 	= date('Y-m-d H:i:s');
						$input['id_user']			= $this->session->userdata('id_karyawan');
						$input['is_kunci'] 			= 'Y';

						$query = $this->log_laporan_model->insert($input);
						$status_req = true;
						$pesan = "<p><strong>Sukses Mengunci Laporan pada Tahun ".$tahun." dan ".$namaBulan[$bulan]."</strong></p>";
					}else{
						$status_req = false;
						$pesan ="
							<p><strong>Gagal melakukan penguncian dikarenakan terdapat laporan yang belum update sampai tgl penguncian</strong></p>
							<p>Berikut laporan yang belum dilakukan update pada tahun ".$tahun." dan bulan ".$namaBulan[$bulan]." di tanggal ".date('d-m-Y', strtotime($tgl_sekarang))."</p>
						";
						
						for ($i=0; $i <count($arr_jenislap); $i++) { 
							$pesan .= "<li>".$arr_jenislap[$i]."</li>";
						}
					}

					$status = array(
							'status' => $status_req,
							'redirect_link' => base_url()."".$this->uri->segment(1),
							'pesan' => $pesan
						);
				}
			}
		}

		echo(json_encode($status));
	}

	public function cek_update_laporan($kdInstansi, $bulan, $tahun, $tgl_sekarang)
	{
		$data_arr = [];
		$q_upd_lap_skor = $this->log_laporan_model->get_tgl_akhir_skor($kdInstansi, $bulan, $tahun);
		$upd_lap_skor = ($q_upd_lap_skor) ? date('Y-m-d', strtotime($q_upd_lap_skor[0]['finished_at'])) : null;
		if ($tgl_sekarang == $upd_lap_skor) {
			$data_arr[] = array ('jenis_lap' => 'Laporan Skor', 'is_update' => true);
		}else{
			$data_arr[] = array ('jenis_lap' => 'Laporan Skor', 'is_update' => false);
		}
		
		$q_upd_lap_makan = $this->log_laporan_model->get_tgl_akhir_makan($kdInstansi, $bulan, $tahun);
		$upd_lap_makan = ($q_upd_lap_makan) ? date('Y-m-d', strtotime($q_upd_lap_makan[0]['created_at'])) : null;
		if ($tgl_sekarang == $upd_lap_makan) {
			$data_arr[] = array ('jenis_lap' => 'Laporan Uang Makan', 'is_update' => true);
		}else{
			$data_arr[] = array ('jenis_lap' => 'Laporan Uang Makan', 'is_update' => false);
		}

		$q_upd_lap_lembur = $this->log_laporan_model->get_tgl_akhir_lembur($kdInstansi, $bulan, $tahun);
		$upd_lap_lembur = ($q_upd_lap_lembur) ? date('Y-m-d', strtotime($q_upd_lap_lembur[0]['finished_at'])) : null;
		if ($tgl_sekarang == $upd_lap_lembur) {
			$data_arr[] = array ('jenis_lap' => 'Laporan Lembur', 'is_update' => true);
		}else{
			$data_arr[] = array ('jenis_lap' => 'Laporan Lembur', 'is_update' => false);
		}

		return $data_arr;
	}

	public function edit($idLog)
	{
		$where = "id_log_laporan = '".$idLog."' ";
		$datalog = $this->log_laporan_model->getData($where);
		$tgl_log = $datalog->tgl_log;

		$data['time_stamp_buka'] = date('Y-m-d H:i:s');
		$data['user_buka'] = $this->session->userdata('id_karyawan');
		$data['is_kunci'] = 'N';
		
		$update = $this->log_laporan_model->update($where, $data);
		$this->db->trans_begin();
		if ($update > 0) {
			$output = [
				'status' => true,
				'pesan' => 'Data Log laporan tanggal : <strong>'.$tgl_log.'</strong> berhasil dibuka'
			];
		}
		
		echo json_encode($output);
	}

}//end class
