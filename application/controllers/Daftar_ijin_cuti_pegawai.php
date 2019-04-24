<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_ijin_cuti_pegawai extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('jenis_ijin_cuti_model');
		$this->load->model('t_ijin_cuti_model');
		$this->load->model('instansi_model');
		$this->load->model('data_mentah_model');
		$this->load->model('global_model');
	}


	public function index(){

		// if($this->session->userdata('id_kategori_karyawan')=='4' || $this->session->userdata('id_kategori_karyawan')=='3'){
		if($this->session->userdata('id_kategori_karyawan')=='4' || $this->session->userdata('id_kategori_karyawan')=='11'){
			if ($this->session->userdata('kode_instansi') == '5.09.00.00.00') {
				$whereInstansi =	"m_instansi.kode='5.09.00.00.00' or m_instansi.kode='5.09.00.91.00'";
			}else{
				$whereInstansi =	"m_instansi.kode='".$this->session->userdata('kode_instansi')."' ";
			}
		}
		else{
			$whereInstansi =	"";
		}
		$this->dataInstansi = $this->instansi_model->showData($whereInstansi,"","nama");



		if($this->input->get('id_instansi')){
		 	/**$whereIjin		=	"
				(
					to_char(t_ijin_cuti_pegawai.tgl_mulai, 'MM/DD/YYYY') >= '".$this->input->get('tgl_mulai')."'
					AND
					to_char(t_ijin_cuti_pegawai.tgl_mulai, 'MM/DD/YYYY') <= '".$this->input->get('tgl_selesai')."'
				)

				and m_instansi.kode='".$this->input->get('id_instansi')."'";

				$this->dataIjin = 	$this->t_ijin_cuti_model->showData($whereIjin,"","t_ijin_cuti_pegawai.tgl_mulai");

				//echo $this->db->last_query();
			**/

			// $this->db->select("");
			// $this->db->select("");
			
			// $this->db->select("");
			// $this->db->select("");
			// $this->db->select("");
			// $this->db->select("");
			// $this->db->select("");
			// $this->db->select("");
			// $this->db->select("");

			$kodeAwalDinas	=	substr($this->input->get('id_instansi'),0,4);

			$tanggalMulai	=	explode('/',$this->input->get('tgl_mulai'));
			$tanggalAkhir	=	explode('/',$this->input->get('tgl_selesai'));

			$tanggalMulai	=	$tanggalMulai[2]."-".$tanggalMulai[1]."-".$tanggalMulai[0];
			$tanggalAkhir	=	$tanggalAkhir[2]."-".$tanggalAkhir[1]."-".$tanggalAkhir[0];

			$queryPegawai 	=	$this->db->query("
			select
				m.id as id_pegawai,m.nama as nama_pegawai, m.nip,
				pukh.nama_unor,
				pukh.nama_instansi,
				pukh.kode_instansi,
				m_jenis_ijin_cuti.kode as kode_ijin_cuti,
				m_jenis_ijin_cuti.nama as nama_ijin_cuti,
				t_ijin_cuti_pegawai.id as id_t_ijin,
				t_ijin_cuti_pegawai.file_lampiran,
				t_ijin_cuti_pegawai.no_surat,
				t_ijin_cuti_pegawai.esurat,
				t_ijin_cuti_pegawai.keterangan,
				t_ijin_cuti_pegawai.status,
				to_char(t_ijin_cuti_pegawai.tgl_mulai, 'DD-MM-YYYY') as tgl_mulai,
				to_char(t_ijin_cuti_pegawai.tgl_selesai, 'DD-MM-YYYY') as tgl_selesai,
				'' as kunci
			from
				m_pegawai m
				LEFT JOIN LATERAL (
					SELECT
						h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
					FROM
						m_pegawai_unit_kerja_histori h
						LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
						LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".$tanggalAkhir."' and m.id = h.id_pegawai
					ORDER BY h.tgl_mulai DESC LIMIT 1
				)
				pukh ON true

				LEFT JOIN t_ijin_cuti_pegawai ON  t_ijin_cuti_pegawai.id_pegawai =  m.id
				LEFT JOIN m_jenis_ijin_cuti ON m_jenis_ijin_cuti.id =  t_ijin_cuti_pegawai.id_jenis_ijin_cuti
			where
				pukh.kode_instansi = '".$this->input->get('id_instansi')."'
				and t_ijin_cuti_pegawai.tgl_mulai >= '".$tanggalMulai."'
				and t_ijin_cuti_pegawai.tgl_mulai <= '".$tanggalAkhir."'
				and t_ijin_cuti_pegawai.is_delete = 0
			order by
				t_ijin_cuti_pegawai.tgl_mulai
			");
			// echo $this->db->last_query();
			//var_dump($this->db->last_query());exit;
			$data 	= $queryPegawai->result();
			
			//batasan CUTOFF LAPORAN
			$tgl_batas 		= "2018-12-01";
			$time 			= strtotime($tanggalMulai);
			$batas_cutoff_time	= strtotime($tgl_batas);
			$tgl_mulai 		= date('Y-m-d',$time);
			$tgl_akhir 		= date('Y-m-t',$time);

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

			$this->dataIjin = $data;
		}

		$this->template_view->load_view('daftar_ijin_cuti_pegawai/daftar_ijin_cuti_pegawai_view');
	}

	public function ubah_status(){

		if($this->input->post('status')=='1'){
			$data = array(
				'status' 	=> '1'
			);
		}
		else{
			$data = array(
				'status' 	=> null
			);
		}

		$where = array(
			't_ijin_cuti_pegawai.id' => $this->input->post('id_t_ijin')
		);

		$query = $this->t_ijin_cuti_model->update($where,$data);
		$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));

		$dataIjin = $this->t_ijin_cuti_model->getData($where);

		$where ="tanggal  >= '".$dataIjin->tgl_mulai_insert."'  and tanggal  <= '".$dataIjin->tgl_selesai_insert."'  and id_pegawai='". $dataIjin->id_pegawai."'";
		$this->data_mentah_model->delete($where);


		$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));

		echo(json_encode($status));
	}

	public function delete($id){


		$data = array(
			'is_delete' 	=> '1'
		);

		$where = array(
			't_ijin_cuti_pegawai.id' => $id
		);

		$dataIjin	=	$this->t_ijin_cuti_model->getData($where);
		#LOG 
		$ijin		=	$this->global_model->get_by_id('t_ijin_cuti_pegawai',$where);
		
		$data_log = [
							'id_user'			=> $this->session->userdata()['id_karyawan'],
							'aksi'				=> 'REMOVE IJIN CUTI',
							'tanggal'			=> date('Y-m-d H:i:s'),
							'data'				=> json_encode($ijin),
							'file_lampiran'		=> ($ijin->file_lampiran)?$ijin->file_lampiran:null,
							'lampiran_blob'		=> ($ijin->lampiran)?$ijin->lampiran:null,
							'lampiran_blob_type'=> ($ijin->lampiran_type)?$ijin->lampiran_type:null
						];
		$this->global_model->save($data_log,'log_tekocak');

		$query = $this->t_ijin_cuti_model->update($where,$data);
		// $query = $this->absensi_log_model->delete($where);
		// if($query){
		// 	echo "SUKSES";
		// }
		// else{
		// 	echo "GAGAL";
		// }
		// ?tgl_mulai=01%2F09%2F2018&tgl_selesai=30%2F09%2F2018&id_instansi=5.16.00.00.00
		// redirect(base_url()."".$this->uri->segment(1)."?tgl_mulai=".$this->input->get('tgl_mulai')."&tgl_selesai=".$this->input->get('tgl_selesai')."&id_instansi=".$this->input->get('id_instansi')."");
		$url = "tgl_mulai=".$this->input->get('tgl_mulai')."&tgl_selesai=".$this->input->get('tgl_selesai')."&id_instansi=".$this->input->get('id_instansi')."";


		// Loop between timestamps, 24 hours at a time

		//$this->input->get('tgl_selesai')

		$startTime		=	strtotime($dataIjin->tgl_mulai_insert);
		$endTime			=	strtotime($dataIjin->tgl_selesai_insert);

		$this->load->library('migrasi_data');
		for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) {
			$tgl	=	$thisDate = date( 'Y-m-d', $i );
			$this->migrasi_data->cek_ulang_data_mentah($tgl, $dataIjin->id_pegawai, "update", false);
		}

		// echo "<h1>$cek</h1> <br> $url";
		redirect(base_url()."".$this->uri->segment(1)."?".$url);

	}

	public function cek_kunci_laporan()
	{
		$tanggal1	=	explode('/',$this->input->get('tgl1'));
		$tgl1	=	$tanggal1[2]."-".$tanggal1[1]."-".$tanggal1[0];

		$tanggal2	=	explode('/',$this->input->get('tgl2'));
		$tgl2	=	$tanggal2[2]."-".$tanggal2[1]."-".$tanggal2[0];

		//cari tgl terakhir
		$tgl_akhir1 = date('Y-m-t', strtotime($tgl1));
		$tgl_akhir2 = date('Y-m-t', strtotime($tgl2));
		
		$whereLog = "tgl_log BETWEEN '".$tgl_akhir1."' AND '".$tgl_akhir2."' AND kd_instansi='".$this->input->get('instansi')."' AND is_kunci = 'Y'";

		$tabel = 'log_laporan';
		$cek = $this->t_ijin_cuti_model->cek_data_kunci_laporan($whereLog, $tabel);
		
		if (!empty($cek)) {
			$output['status'] = true;
			$output['data'] = $cek;
		}else{
			$output['status'] = false;
		}
		
		echo json_encode($output);
	}

}
