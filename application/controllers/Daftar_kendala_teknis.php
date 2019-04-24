<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_kendala_teknis extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('absensi_log_model');
		$this->load->model('instansi_model');
		$this->load->model('global_model');

	}


	public function index()
	{
		if($this->session->userdata('id_kategori_karyawan')=='4' || $this->session->userdata('id_kategori_karyawan')=='11')
		{
			if ($this->session->userdata('kode_instansi') == '5.09.00.00.00') {
				$whereInstansi =	"m_instansi.kode='5.09.00.00.00' or m_instansi.kode='5.09.00.91.00'";
			}else{
				$whereInstansi =	"m_instansi.kode='".$this->session->userdata('kode_instansi')."' ";
			}
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

			$mulai =	$tmulai[2]."-".$tmulai[1]."-".$tmulai[0];
			$akhir =	$thingga[2]."-".$thingga[1]."-".$thingga[0];

			// $mulai 		= new DateTime( $tgl_mulai );
			// $selesai 	= new DateTime( $tgl_selesai );
			// $mulai 		= $mulai->format("Y-m-d");
			// $selesai 	= $selesai->format("Y-m-d");
			$queryPegawai 	=	$this->db->query("
				SELECT
					peg.nama as nama_pegawai,
					peg.nip,
					peg.id as id_pegawai,
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
			
			//$this->dataKendala 	= 	$queryPegawai->result();
			$data 	= $queryPegawai->result();
			$tgl_log_laporan = date('Y-m-t', strtotime($mulai));
			if ($data) {
				for ($i=0; $i < count($data); $i++) 
				{ 
					$cek_unor = $this->db->query("
						SELECT
							h.kode_unor,  mi.kode as kode_instansi, mi.nama as nama_instansi
						FROM
							m_pegawai_unit_kerja_histori h
							LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
							LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode 
							WHERE h.tgl_mulai <= '".$tgl_log_laporan."' 
								and h.id_pegawai = '".$data[$i]->id_pegawai."'
						ORDER BY h.tgl_mulai DESC LIMIT 1
					")->row();
					$data[$i]->kode_instansi = $cek_unor->kode_instansi;
				}
			}
			
			//var_dump($this->dataKendala);exit;
			//echo $this->db->last_query();
		}

		//batasan CUTOFF LAPORAN
		$tgl_batas 			= "2018-12-01";
		$time 				= strtotime($mulai);
		$batas_cutoff_time	= strtotime($tgl_batas);
		$tgl_mulai 			= date('Y-m-d',$time);
		$tgl_akhir 			= date('Y-m-t',$time);

		//jika tanggal ijin lebih dari tgl cut off tentukan method kunci
		if ($time > $batas_cutoff_time ){ 
			$kunci_bulanan = true;
			$kunci_ceklis  = false; 
		} else { 
			$kunci_bulanan = false;
			$kunci_ceklis  = true; 
		}

		for ($i=0; $i < count($data); $i++) 
		{ 
			//cek method kunci
			if ($kunci_ceklis) 
			{
				$cek_kunci = $this->db->query("select * from t_kunci_upload where kode_instansi = '".$data[$i]->kode_instansi."'")->row();
			}
			//cek method kunci
			elseif ($kunci_bulanan) 
			{
				$cek_kunci = $this->db->query("
			 		select * from log_laporan 
			 		where tgl_log = '".$tgl_akhir."' and
			 		kd_instansi = '".$data[$i]->kode_instansi."' and
			 		is_kunci = 'Y' and 
			 		time_stamp_buka is null
			 	")->row();
			}

			if ($cek_kunci) {
				$data[$i]->kunci = true;
			}else{
				$data[$i]->kunci = false;
			}				
		}
		$this->dataKendala = $data;
		$config['total_rows'] 	= count($this->dataKendala);
		$config['per_page'] 	= 10;
		$this->pagination->initialize($config);
		
		$this->template_view->load_view('daftar_kendala_teknis/daftar_kendala_teknis_view');
	}

	public function delete($cek){

		$dataKendalaTeknis	=	$this->absensi_log_model->getData("absensi_log.id = '".$cek."'");

		//var_dump($dataKendalaTeknis);

		$where = array(
			'id' => $cek
		);

		#LOG START
		$ijin		=	$this->global_model->get_by_id('absensi_log',$where);
		$data_log = [
				'id_user'			=> $this->session->userdata()['id_karyawan'],
				'aksi'				=> 'REMOVE KENDALA TEKNIS',
				'tanggal'			=> date('Y-m-d H:i:s'),
				'data'				=> json_encode($ijin),
				'file_lampiran'		=> ($ijin->file_lampiran)?$ijin->file_lampiran:null,
				'lampiran_blob'		=> ($ijin->lampiran)?$ijin->lampiran:null,
				'lampiran_blob_type'=> ($ijin->lampiran_type)?$ijin->lampiran_type:null
			];
		$this->global_model->save($data_log,'log_tekocak');
		#LOG FINISH
		
		$query = $this->absensi_log_model->delete($where);

		//var_dump($query);
		//$date_kendala_teknis 			= new DateTime( $tanggal );
		$this->load->library('migrasi_data');
		$this->migrasi_data->cek_ulang_data_mentah($dataKendalaTeknis->tanggal_untuk_insert, $dataKendalaTeknis->id_pegawai, "update", false);


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
