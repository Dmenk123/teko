<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_kendala_teknis extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('absensi_log_model');
		$this->load->model('instansi_model');

	}


	public function index()
	{
		if($this->session->userdata('id_kategori_karyawan')=='4' || $this->session->userdata('id_kategori_karyawan')=='3')
		{
			$whereInstansi =	"m_instansi.kode='".$this->session->userdata('kode_instansi')."' ";
		}
		else
		{
			$whereInstansi =	"";
		}

		$this->dataInstansi = $this->instansi_model->showData($whereInstansi,"","nama");
		if($this->input->get('id_instansi'))
		{
			$tgl_mulai   = $this->input->get('tgl_mulai');
			$tgl_selesai = $this->input->get('tgl_selesai');
			$id_instansi = $this->input->get('id_instansi');
			$tmulai = explode('/', $tgl_mulai);
			$thingga = explode('/', $tgl_selesai);

			$akhir =	$thingga[2]."-".$thingga[1]."-".$thingga[0];
			$mulai =	$tmulai[2]."-".$tmulai[1]."-".$tmulai[0];

			// $mulai 			  = new DateTime( $tgl_mulai );
			// $selesai 			= new DateTime( $tgl_selesai );
			// $mulai 				= $mulai->format("Y-m-d");
			// $selesai 			= $selesai->format("Y-m-d");
			$queryPegawai 	=	$this->db->query("
				SELECT
					peg.nama as nama_pegawai,
					peg.nip,
					absensi_log.keterangan,
					absensi_log.dispensasi,
					absensi_log.tanggal,
					absensi_log.file_lampiran,
					absensi_log.lampiran,
					absensi_log.lampiran_type,
					absensi_log.tanggal as tanggal_update,
					absensi_log.id as id_log_absensi
				FROM
					absensi_log
					JOIN mesin_user ON mesin_user.user_id = absensi_log.badgenumber AND mesin_user.id_mesin = absensi_log.id_mesin
					JOIN m_pegawai as peg ON peg.id = mesin_user.id_pegawai
				WHERE
					(file_lampiran IS NOT NULL
					OR lampiran IS NOT NULL)
					AND mesin_user.id_pegawai IN 
					(
						SELECT
							m.id
						FROM
							m_pegawai m
							LEFT JOIN LATERAL 
							(
								SELECT
									-- h.kode_unor,
									-- h.tgl_mulai,
									-- muok.nama AS nama_unor,
									mi.kode AS kode_instansi
									-- mi.nama AS nama_instansi
								FROM
									m_pegawai_unit_kerja_histori h
								LEFT JOIN m_unit_organisasi_kerja muok ON h.kode_unor = muok.kode
								LEFT JOIN m_instansi mi ON muok.kode_instansi = mi.kode
								WHERE
									h.tgl_mulai <= '$akhir'
								AND m.id = h.id_pegawai
								ORDER BY
									h.tgl_mulai DESC
								LIMIT 1
							) pukh ON TRUE
						WHERE
							pukh.kode_instansi = '$id_instansi'
					)
					AND absensi_log.tanggal BETWEEN '$mulai' AND '$akhir'
				ORDER BY  peg.nama ASC
			");
			
			$this->dataKendala 	= 	$queryPegawai->result();
			$config['total_rows'] 	= count($this->dataKendala);
			$config['per_page'] 	= 10;
			$this->pagination->initialize($config);

			//var_dump($this->dataKendala);exit;
			//echo $this->db->last_query();
		}

		// $config['total_rows'] = $this->jumlahData;
		// $config['per_page'] 	= 10;
		$this->template_view->load_view('daftar_kendala_teknis/daftar_kendala_teknis_view');
	}

	public function delete($cek){

		$dataKendalaTeknis	=	$this->absensi_log_model->getData("absensi_log.id = '".$cek."'");

		//var_dump($dataKendalaTeknis);

		$where = array(
			'id' => $cek
		);
		$query = $this->absensi_log_model->delete($where);

		//var_dump($query);
		//$date_kendala_teknis 			= new DateTime( $tanggal );
		$this->load->library('migrasi_data');
		$this->migrasi_data->cek_ulang_data_mentah($dataKendalaTeknis->tanggal_untuk_insert, $dataKendalaTeknis->id_pegawai, "update", true);


		$url = "tgl_mulai=".$this->input->get('tgl_mulai')."&tgl_selesai=".$this->input->get('tgl_selesai')."&id_instansi=".$this->input->get('id_instansi')."";



		// echo "<h1>$cek</h1> <br> $url";
		redirect(base_url()."".$this->uri->segment(1)."?".$url);

	}

	public function cek_kunci_laporan()
	{
		$tanggal1	=	explode('/',$this->input->get('tgl1'));
		$tgl1		=	$tanggal1[2]."-".$tanggal1[1]."-".$tanggal1[0];

		$tanggal2	=	explode('/',$this->input->get('tgl2'));
		$tgl2		=	$tanggal2[2]."-".$tanggal2[1]."-".$tanggal2[0];

		//cari tgl terakhir
		$tgl_akhir1 = date('Y-m-t', strtotime($tgl1));
		$tgl_akhir2 = date('Y-m-t', strtotime($tgl2));
				
		$whereLog = "tgl_log BETWEEN '".$tgl_akhir1."' AND '".$tgl_akhir2."' AND kd_instansi='".$this->input->get('instansi')."' AND is_kunci = 'Y'";
		$tabel = 'log_laporan';

		$cek = $this->query_kunci_laporan($whereLog, $tabel);
		if (!empty($cek)) {
			$output['status'] = true;
			$output['data'] = $cek;
		}else{
			$output['status'] = false;
		}
		
		echo json_encode($output);
	}

	public function query_kunci_laporan($where, $tabel)
	{
		$this->db->where($where);
		$this->db->order_by('time_stamp', 'desc');
		return $this->db->get($tabel)->row();  
	}



}
