<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_lembur_pegawai extends CI_Controller {



	public function __construct() {
		parent::__construct();

		// $this->load->model('jenis_ijin_cuti_model');
		$this->load->model('lembur/t_lembur_model', 't_lembur');
		$this->load->model('instansi_model');
		$this->load->model('data_mentah_model');
		$this->load->model('global_model');
	}

	public function index(){

		if($this->session->userdata('id_kategori_karyawan')=='4' || $this->session->userdata('id_kategori_karyawan')=='11')
		{
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

			$kodeAwalDinas	=	substr($this->input->get('id_instansi'),0,4);

			$tanggalMulai		=	explode('/',$this->input->get('tgl_mulai'));
			$tanggalAkhir		=	explode('/',$this->input->get('tgl_selesai'));

			$tanggalMulai		=	$tanggalMulai[2]."-".$tanggalMulai[1]."-".$tanggalMulai[0];
			$tanggalAkhir		=	$tanggalAkhir[2]."-".$tanggalAkhir[1]."-".$tanggalAkhir[0];


			$queryPegawai 	=	$this->db->query("

			select
				m.id as id_pegawai,m.nama as nama_pegawai, m.nip,
				pukh.nama_unor,
				pukh.nama_instansi,
				pukh.kode_instansi,
				t_lembur_pegawai.id as id_t_ijin,
				t_lembur_pegawai.no_surat as no_surat,
				t_lembur_pegawai.keterangan as keterangan,
				t_lembur_pegawai.file_lampiran as file_lampiran,
				t_lembur_pegawai.status as status,
				to_char(t_lembur_pegawai.tgl_lembur, 'DD-MM-YYYY') as tgl_lembur,
				to_char(t_lembur_pegawai.jam_lembur_awal, 'DD-MM-YYYY HH24:MI') as jam_awal,
				to_char(t_lembur_pegawai.jam_lembur_akhir, 'DD-MM-YYYY HH24:MI') as jam_akhir,
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

				LEFT JOIN t_lembur_pegawai  ON  t_lembur_pegawai.id_pegawai =  m.id
			where
				pukh.kode_instansi = '".$this->input->get('id_instansi')."'
				and t_lembur_pegawai.tgl_lembur  between '".$tanggalMulai."' and '".$tanggalAkhir."'
				and t_lembur_pegawai.is_delete = 0
			order by
				t_lembur_pegawai.tgl_lembur

			");
			
			$data = $queryPegawai->result();

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
			//echo $this->db->last_query();
		}

		/*if ($this->session->userdata('id_kategori_karyawan') == '1') {
			$this->template_view->load_view('lembur_pegawai/daftar_lembur_pegawai_view');
		}else{
			$this->template_view->load_view('template/sedang-perbaikan');
		}*/
		$this->template_view->load_view('lembur_pegawai/daftar_lembur_pegawai_view');
	}

	public function ubah_status(){

		if($this->input->post('status')=='1'){
			$data = array(
				'status' 	=> '1',
				'time_upd_status' => date('Y-m-d h:i:s')
			);
		}
		else{
			$data = array(
				'status' 	=> null,
				'time_upd_status' => date('Y-m-d h:i:s')
			);
		}

		$where = array(
			't_lembur_pegawai.id' => $this->input->post('id_t_ijin')
		);

		$query = $this->t_lembur->update($where,$data);
		$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));

		$dataLembur	=	$this->t_lembur->getData($where);

		// $date_lembur 			= new DateTime( $tgl_lembur );
		// $this->load->library('migrasi_data');
		// $this->migrasi_data->cek_ulang_data_mentah($date_lembur->format("Y-m-d"), $this->input->post('id_pegawai'), "update");

		$where ="tanggal ='".$dataLembur->tgl_lembur_insert."' and id_pegawai='". $dataLembur->id_pegawai."'";
		$this->data_mentah_model->delete($where);

		echo(json_encode($status));
	}

	public function delete($id){


		$data = array(
			'is_delete' 	=> '1'
		);

		$where = array(
			't_lembur_pegawai.id' => $id
		);

		$ijin		=	$this->global_model->get_by_id('t_lembur_pegawai',$where);
		#LOG START
		$data_log = [
				'id_user'			=> $this->session->userdata()['id_karyawan'],
				'aksi'				=> 'REMOVE LEMBUR PEGAWAI',
				'tanggal'			=> date('Y-m-d H:i:s'),
				'data'				=> json_encode($ijin),
				'file_lampiran'		=> ($ijin->file_lampiran)?$ijin->file_lampiran:null,
				'lampiran_blob'		=> ($ijin->lampiran)?$ijin->lampiran:null,
				'lampiran_blob_type'=> ($ijin->lampiran_type)?$ijin->lampiran_type:null
			];
		$this->global_model->save($data_log,'log_tekocak');
		#LOG FINISH

		$dataLembur	=	$this->t_lembur->getData($where);

		$query = $this->t_lembur->update($where,$data);


		//$query = $this->absensi_log_model->delete($where);
		// if($query){
		// 	echo "SUKSES";
		// }
		// else{
		// 	echo "GAGAL";
		// }
		// ?tgl_mulai=01%2F09%2F2018&tgl_selesai=30%2F09%2F2018&id_instansi=5.16.00.00.00
		// redirect(base_url()."".$this->uri->segment(1)."?tgl_mulai=".$this->input->get('tgl_mulai')."&tgl_selesai=".$this->input->get('tgl_selesai')."&id_instansi=".$this->input->get('id_instansi')."");

		$url = "tgl_mulai=".$this->input->get('tgl_mulai')."&tgl_selesai=".$this->input->get('tgl_selesai')."&id_instansi=".$this->input->get('id_instansi')."";

		$this->load->library('migrasi_data');
		$this->migrasi_data->cek_ulang_data_mentah($dataLembur->tgl_lembur_insert, $dataLembur->id_pegawai, "update", false);
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

		$cek = $this->t_lembur->cek_data_kunci_laporan($whereLog, $tabel);
		if (!empty($cek)) {
			$output['status'] = true;
			$output['data'] = $cek;
		}else{
			$output['status'] = false;
		}
		
		echo json_encode($output);
	}


}
