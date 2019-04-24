<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lap_eksport_kehadiran extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_sebelum_update_model']);
	}



	public function index(){

		$whereInstansi 		=	"kode = '".$this->input->get('id_instansi')."' ";
		$this->dataInstansi = 	$this->instansi_model->getData($whereInstansi,"","");


		$this->load->library('konversi_menit');


		$this->dataTables = "";

		$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
		// Tanggal pertama pada bulan ini
		$tglMulai 	= date('Y-m-01', strtotime($hari_ini));
		$tglMulaiTetap 	= date('Y-m-01', strtotime($hari_ini));
		// Tanggal terakhir pada bulan ini
		$tglSelesai 	= date('Y-m-t', strtotime($hari_ini));
		
		$sekarang = date("Y-m-d");
		
		if(strtotime($tglSelesai) > strtotime($sekarang) ){
			$tglSelesaiLooping = date('Y-m-d');
		}
		else{
			$tglSelesaiLooping = $tglSelesai;
		}
		


		
		$kodeAwalDinas	=	substr($this->input->get('id_instansi'),0,4);


		$this->dataTable = "";
		
		while (strtotime($tglMulai) <= strtotime($tglSelesaiLooping)) {				
			//echo $tglMulai."<br>";
			
			$queryPegawai 	=	$this->db->query("
			select
				m.id as id_pegawai,m.nip,m.nama, pukh.kode_unor as unor ,m_jenis_jabatan.nama as nama_unor,m.aktif,m_golongan.deskripsi,m_golongan.nama as nama_golongan,m_rumpun_jabatan.nama as nama_rumpun_jabatan
			from m_pegawai m
				
				LEFT JOIN m_rumpun_jabatan ON m.kode_rumpun_jabatan = m_rumpun_jabatan.id
				LEFT JOIN m_jenis_jabatan ON   m_jenis_jabatan.kode=m.kode_jenis_jabatan 		
				LEFT JOIN m_golongan ON m_golongan.kode = m.kode_golongan_akhir
				LEFT JOIN LATERAL (
					SELECT kode_unor
					FROM m_pegawai_unit_kerja_histori h
					WHERE tgl_mulai <= '".$tglMulaiTetap."' and m.id = h.id_pegawai
					ORDER BY tgl_mulai DESC
					LIMIT 1
				) pukh ON true
				
			where
			pukh.kode_unor like '".$kodeAwalDinas."%'

			order by 
				m_jenis_jabatan.urut,
				m_golongan.nama desc
			");
			$this->dataPegawai	=	$queryPegawai->result();
			
			$tglIndo = date('d-m-Y', strtotime($tglMulai));
			
			foreach($this->dataPegawai as $pegawai){
				
				
				$dataPresensi 	=	$this->data_mentah_sebelum_update_model->getData("id_pegawai = '".$pegawai->id_pegawai."' and tanggal = '".$tglMulai."'  ");
				//var_dump($dataPresensi );
				$this->dataTable .= "<tr>";
				$this->dataTable .= "<td>".$pegawai->nama."</td>";
				$this->dataTable .= "<td>".$pegawai->nip."</td>";
				$this->dataTable .= "<td>".$pegawai->nama_unor."</td>";
				$this->dataTable .= "<td>".$tglIndo."</td>";
				
				if($dataPresensi){
					
					$jumlahLembur			=	$this->konversi_menit->hitung($dataPresensi->lembur);
					$jumlahTelat			=	$this->konversi_menit->hitung($dataPresensi->datang_telat);
					$jumlahCepatPulang		=	$this->konversi_menit->hitung($dataPresensi->pulang_cepat);
					
					$this->dataTable .= "<td align='center'>".$dataPresensi->finger_masuk_jam."</td>";
					$this->dataTable .= "<td align='center'>".$dataPresensi->finger_pulang_jam."</td>";
					$this->dataTable .= "<td align='center'>".sprintf("%02d", $jumlahTelat['jam_angka'])." : ".sprintf("%02d", $jumlahTelat['menit_angka'])."</td>";
					$this->dataTable .= "<td align='center'>".sprintf("%02d", $jumlahCepatPulang['jam_angka'])." : ".sprintf("%02d", $jumlahCepatPulang['menit_angka'])."</td>";
					$this->dataTable .= "<td align='center'>".sprintf("%02d", $jumlahLembur['jam_angka'])." : ".sprintf("%02d", $jumlahLembur['menit_angka'])."</td>";
				}
				else{
					$this->dataTable .= "<td></td>";
					$this->dataTable .= "<td></td>";
					$this->dataTable .= "<td></td>";
					$this->dataTable .= "<td></td>";
					$this->dataTable .= "<td></td>";
					$this->dataTable .= "<td></td>";
				}
				
				$this->dataTable .= "</tr>";
				
				//echo $pegawai->nama." ".$tglIndo."<br>";
			}
			
			$tglMulai = date ("Y-m-d", strtotime("+1 days", strtotime($tglMulai)));		
		}
	
	//var_dump($this->dataTable);

		

		$this->load->view('cetak/lap_eksport_kehadiran_view');
	}
}
