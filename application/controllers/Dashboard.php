<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model(['c_security_user_model', 'kategori_user_model', 'mesin_model', 'mesin_user_model', 'global_model', 'm_log_penarikan', 'm_monitor_kondisi']);

	}

	public function index(){

		if ($this->session->userdata('id_kategori_karyawan') == 4 || $this->session->userdata('id_kategori_karyawan') == 11) {

			$kode_instansi_sess = $this->session->userdata('kode_instansi');
			$where_model = "kode = '".$kode_instansi_sess."'";
			$cek_kecamatan = $this->global_model->get_by_id('m_instansi',$where_model);

			if ($this->session->userdata('kode_instansi') == '5.09.00.00.00') {
				$where = "where m_instansi.kode='5.09.00.00.00' or where m_instansi.kode='5.09.00.93.00'";
			}elseif ($this->session->userdata('kode_instansi') == '5.06.00.00.00') {
				$substr_dinkes = substr($this->session->userdata('kode_instansi'),0,5);
				$where = "where m_instansi.kode ilike '".$substr_dinkes."%'";
			}elseif (substr($cek_kecamatan->nama, 0, 9) == 'Kecamatan') {
				$kode_instansi_all = substr($kode_instansi_sess, 0, 5);
				$where = "where m_instansi.kode LIKE '".$kode_instansi_all.'%'."'";
			}
			else{
				$where = "where m_instansi.kode = '".$kode_instansi_sess."'";
			}

			$date_now = date('Y-m-d');
			$dt_now = $date_now.' 00:00:00';
			$dt_end = $date_now.' 23:59:59';
			$query_log = "
				select max(al.tanggal) as jam_masuk, al.badgenumber, msn.nama, mp.nama, mp.nip
				FROM absensi_log al
				join m_mesin msn on al.id_mesin = msn.id
				join mesin_user mu on al.badgenumber || al.id_mesin = mu.user_id || mu.id_mesin
				join m_pegawai mp on mp.id = mu.id_pegawai
				where msn.kode_instansi = '".$this->session->userdata('kode_instansi')."' and al.tanggal >= '".$dt_now."' and al.tanggal <= '".$dt_end."'
				GROUP BY al.badgenumber, al.tanggal::date, msn.nama, mp.nama, mp.nip
				order by jam_masuk desc limit 10
			";
			$query_log2 = "
				SELECT jam_donlod.jm_dl, jam_load.jm_ld, m_mesin.nama
				from m_mesin
				LEFT JOIN LATERAL (
					SELECT max(dl.finish_download) as jm_dl
					FROM t_log_penarikan dl
					WHERE dl.id_mesin = m_mesin.id
					ORDER BY jm_dl DESC LIMIT 1
				)jam_donlod on true
				LEFT JOIN LATERAL (
					SELECT max(ld.tanggal_load_selesai) as jm_ld
					FROM t_log_penarikan ld
					WHERE ld.id_mesin = m_mesin.id
					ORDER BY jm_dl DESC LIMIT 1
				)jam_load on true
				where m_mesin.id in (select id FROM m_mesin where m_mesin.kode_instansi = '".$this->session->userdata('kode_instansi')."' and aktif = 't')
			";
			$query_log3 = "
				SELECT start_gen.mulai, finish_gen.selesai, m_instansi.kode, m_instansi.nama
				FROM m_instansi
				LEFT JOIN LATERAL (
					SELECT max(ts_mulai.start_at) as mulai
					FROM t_cron_scheduler ts_mulai
					WHERE ts_mulai.id_upd = m_instansi.kode
					ORDER BY mulai DESC LIMIT 1
				)start_gen on true
				LEFT JOIN LATERAL (
					SELECT max(ts_selesai.finish_at) as selesai
					FROM t_cron_scheduler ts_selesai
					WHERE ts_selesai.id_upd = m_instansi.kode
					ORDER BY selesai DESC LIMIT 1
				)finish_gen on true
				".$where."
			";

			$this->cek_log = $this->global_model->getData($query_log);
			$this->cek_dl = $this->global_model->getData($query_log2);
			$this->cek_gen = $this->global_model->getData($query_log3);
		}elseif ($this->session->userdata('id_kategori_karyawan') == 1 || $this->session->userdata('id_kategori_karyawan') == 2) {
			$tanggal = date('Y-m-d');      
			$select = "lp.*, lp.id_mesin, mm.nama as nama_mesin, lp.jumlah_data, lp.status, lp.start_download, lp.finish_download, lp.jam_terakhir_mesin";
			$join = array(
				array(
					"table" => "m_mesin as mm",
					"on"    => "lp.id_mesin = mm.id"
				)
			);
			$order_by = "coalesce(m_mesin.nama,'') asc";
			$where = "cast(lp.start_download as date) = '".$tanggal."' and mm.aktif = 't'";
			$data = $this->m_monitor_kondisi->getData("t_log_penarikan as lp", $where, null, "lp.id_mesin", null, null ,null, null, $select, $join);

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
					
					$jmlDl++;
					$arr_data[$counterDl]['tanggal'] = $tanggal;
					$arr_data[$counterDl]['jml_dl'] = $jmlDl;
					$arr_data[$counterDl]['jml_sukses'] = $jmlSukses;
					$arr_data[$counterDl]['jml_gagal'] = $jmlGagal;
					$arr_data[$counterDl]['presentase'] = round(($jmlSukses / $jmlDl) * 100);
				}else{
					$counterDl++;
					$jmlDl = 0;
					$jmlSukses = 0;
					$jmlGagal = 0;
					
					$arr_data[$counterDl]['tanggal'] = $tanggal;
					$arr_data[$counterDl]['jml_dl'] = $jmlDl;
					$arr_data[$counterDl]['jml_sukses'] = $jmlSukses;
					$arr_data[$counterDl]['jml_gagal'] = $jmlGagal;
					$arr_data[$counterDl]['presentase'] = ($jmlSukses > 0) ? round(($jmlSukses / $jmlDl) * 100) : 0 ;
				}
			}

			//fungsi sort array order dengan merubah urutan index arraynya
			$this->sorting_arr($arr_data, 'jml_dl', 'asc');
			
			$dboard1 = [];  
			$count_arr = 0;
			foreach ($arr_data as $key => $value) {
				$dboard1[$count_arr] = $value;
				$count_arr++;
				if ($count_arr >= 25) {
					break;
				}
			}
			
			$this->dboard1 = $dboard1;
		}

		$this->template_view->load_view('template/dashboard_view');
		// if ($this->session->userdata('id_kategori_karyawan') == '1') {
		// 	$this->template_view->load_view('template/dashboard_view');
		// }else{
		// 	$this->template_view->load_view('template/sedang-perbaikan');
		// }
	}

	public function changepassword(){
		$user = $this->session->userdata();
		if($user['id_kategori_karyawan'] == 12) {
			redirect('/ubah_password', 'refresh');
		}
		else {
			$this->template_view->load_view('template/changepassword_view.php');
		}
	}

	public function prosess(){
		/// libarbry encrypt password
		$this->load->library('encrypt_decrypt');

		$pass 		= $this->encrypt_decrypt->dec_enc('encrypt',$this->input->post('lama'));
		$passNew 	= $this->encrypt_decrypt->dec_enc('encrypt',$this->input->post('REPASS'));

		$dataUser = $this->c_security_user_model->getData("id = '".$_SESSION['id_karyawan'] ."' and password_new='".$pass."' and active='t'");

		if($dataUser ){

			$data = array(
				'password_new' 				=> $passNew
			);
			$query = $this->c_security_user_model->update("id = '".$_SESSION['id_karyawan'] ."' and password_new='".$pass."' and active='t'",$data);

			$status = array('true' => false , 'pesan' => 'Proses Ubah Data Password berhasil.');
		}
		else{
			$status = array('status' => false , 'pesan' => 'Proses Ubah Data Password Gagal, dikarenakan Password Lama anda Salah.');
		}

		echo(json_encode($status));

	}

	//public function cek(){
		//$this->load->library('encrypt_decrypt');
		//echo $this->encrypt_decrypt->dec_enc('encrypt','wongdalupraja17112');
		//echo $this->encrypt_decrypt->dec_enc('decrypt','a3FpQTZBNU1XcE9yYzFvTHA2UUZtZz09');
	//}

	function sorting_arr(&$array, $key, $sortType="asc") {
		$sorter=array();
		$ret=array();
		reset($array);
		foreach ($array as $ii => $va) {
			$sorter[$ii]=$va[$key];
		}

		if ($sortType == 'asc') {
			asort($sorter); //sort asc
		}else{
			arsort($sorter); //sort desc
		}

		foreach ($sorter as $ii => $va) {
			$ret[$ii]=$array[$ii];
		}
		$array=$ret;
	}

}
