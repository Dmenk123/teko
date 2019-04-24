<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monitoring_kondisi extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('c_security_user_model');
		$this->load->model('m_monitor_kondisi', 'm_kondisi');
		$this->load->helper('indonesiandate');
		$this->load->model('absensi_log_model');
		$this->load->model('global_model');
    }
    
    public function index(){
		//ini_set('display_errors', 1);
		$tgl = $this->input->get("tanggal");
		$bulan = $this->input->get("bulan");
		$tahun = $this->input->get("tahun");
		$tanggal = date('Y-m-d', strtotime($tahun.'-'.$bulan.'-'.$tgl));      
		$select = "lp.*, lp.id_mesin, mm.nama as nama_mesin, lp.jumlah_data, lp.status, lp.start_download, lp.finish_download, lp.tanggal_load_mulai, lp.tanggal_load_selesai, lp.jam_terakhir_mesin";
		$join = array(
			array(
				"table" => "m_mesin as mm",
				"on"    => "lp.id_mesin = mm.id"
			)
		);
		$order_by = "coalesce(m_mesin.nama,'') asc";
		$where = "cast(lp.start_download as date) = '".$tanggal."' and mm.aktif = 't'";
		$data = $this->m_kondisi->getData("t_log_penarikan as lp", $where, null, "lp.id_mesin", null, null ,null, null, $select, $join);

		$keyMesin = 0;
		$arr_data = [];
		for ($i=0; $i <count($data); $i++) {
			if ($i > 0) {
				if ($data[$i]['id_mesin'] == $arr_data[$keyMesin - 1]['id_mesin']) {
					continue;
				}

				$arr_data[$keyMesin]['id_mesin'] = $data[$i]['id_mesin'];
				$arr_data[$keyMesin]['ip'] = $data[$i]['ip'];
				$arr_data[$keyMesin]['nama_mesin'] = $data[$i]['nama_mesin'];
				$keyMesin++;
			}
		}

		$counterDl = 0;
		$jmlSukses = 0;
		$jmlGagal = 0;
		$jmlDl = 0;
		$jmlLoad = 0;
		for ($i=0; $i <count($data); $i++) { 
			if ($arr_data[$counterDl]['id_mesin'] == $data[$i]['id_mesin']) {
				if ($data[$i]['status'] == 'sukses') {
					$jmlSukses++;
				}
				if ($data[$i]['status'] == 'gagal') {
					$jmlGagal++;
				}
				if (date('Y-m-d', strtotime($data[$i]['tanggal_load_mulai'])) == $tanggal) {
					$jmlLoad++;
				}
				
				$jmlDl++;
				$arr_data[$counterDl]['tanggal'] = $tanggal;
				$arr_data[$counterDl]['jml_dl'] = $jmlDl;
				$arr_data[$counterDl]['jml_sukses'] = $jmlSukses;
				$arr_data[$counterDl]['jml_gagal'] = $jmlGagal;
				$arr_data[$counterDl]['jml_load'] = $jmlLoad;
			}else{
				$counterDl++;
				$jmlDl = 0;
				$jmlSukses = 0;
				$jmlGagal = 0;
				$jmlLoad = 0;

				$arr_data[$counterDl]['tanggal'] = $tanggal;
				$arr_data[$counterDl]['jml_dl'] = $jmlDl;
				$arr_data[$counterDl]['jml_sukses'] = $jmlSukses;
				$arr_data[$counterDl]['jml_gagal'] = $jmlGagal;
				$arr_data[$counterDl]['jml_load'] = $jmlLoad;
			}
		}

		$this->data_log = $arr_data;
		/*if ($this->session->userdata('id_kategori_karyawan') == '1') {
			$this->template_view->load_view('monitoring_kondisi/monitoring_kondisi_view');
		}else{
			$pesan = array(
				'header' => 'Mohon Maaf sedang dalam optimasi query pengambilan data.',
				'isi'	=> "Mohon kembali lagi kurang lebih <strong>15-30 menit</strong>. Terima Kasih" 
			);
			$this->pesan = $pesan;
			$this->template_view->load_view('template/sedang-perbaikan');
		}*/
		$this->template_view->load_view('monitoring_kondisi/monitoring_kondisi_view');
	}
}