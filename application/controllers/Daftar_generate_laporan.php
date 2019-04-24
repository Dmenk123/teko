<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daftar_generate_laporan extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('monitoring_tarik_model');
		$this->load->model('t_ijin_cuti_model');
		$this->load->model('instansi_model');
		$this->load->model('data_mentah_model');

	}

	public function index(){

		if($this->session->userdata('id_kategori_karyawan') !='4' || $this->session->userdata('id_kategori_karyawan') !='3')
		{
			$like = null;
			$like2 = null;
			$urlSearch = null;
			$order_by ='sa';
			$where = "";

			if($this->input->get('field')){
				$like = "WHERE ".$_GET['field']." ilike '%".$_GET['keyword']."%'";
				$like2 = array($_GET['field'] => strtoupper($_GET['keyword']));
				$urlSearch = "?field=".$_GET['field']."&keyword=".$_GET['keyword'];
			}

			/*var_dump($like);exit;*/
			$config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch;
			$this->jumlahData 		= $this->monitoring_tarik_model->getCount($where,$like2);
			$config['total_rows'] 	= $this->jumlahData;
			$config['per_page'] 	= 25;

			$kode_instansi_sess = $this->session->userdata('kode_instansi');
			if ($this->session->userdata('id_kategori_karyawan') == '1' || $this->session->userdata('id_kategori_karyawan') == '2') {
				$query = "SELECT mulai_gen.sa, akhir_gen.fa, m_instansi.nama, rb_gen.rb, rb_gen.lu, m_instansi.kode 
							from m_instansi
							LEFT JOIN LATERAL (
								SELECT max(tsa.start_at) as sa
								FROM t_cron_scheduler tsa
								WHERE tsa.id_upd = m_instansi.kode
								ORDER BY sa DESC LIMIT 1
							)mulai_gen on true
							LEFT JOIN LATERAL (
								SELECT max(tsf.finish_at) as fa
								FROM t_cron_scheduler tsf
								WHERE tsf.id_upd = m_instansi.kode
								ORDER BY fa DESC LIMIT 1
							)akhir_gen on true
							LEFT JOIN LATERAL (
								SELECT trb.running_by as rb, trb.date as lu
								FROM t_cron_scheduler trb
								WHERE trb.id_upd = m_instansi.kode
								ORDER BY trb.finish_at DESC LIMIT 1
							)rb_gen on true
							".$like."
							limit ".$config['per_page'];
				$this->dataSch = $this->monitoring_tarik_model->showData2($query);
				//$this->dataSch = $this->monitoring_tarik_model->showData($where,$like2,$order_by,$config['per_page'],$this->input->get('per_page'));
				$this->pagination->initialize($config);
			}else{
				if ($this->session->userdata('kode_instansi') == '5.09.00.00.00') {
					$where =	"kode='5.09.00.00.00' or kode='5.09.00.93.00'";
				}elseif ($this->session->userdata('kode_instansi') == '5.06.00.00.00') {
					$substr_dinkes = substr($this->session->userdata('kode_instansi'),0,5);
					$where =	"kode ilike '".$substr_dinkes."%'";
				}
				else{
					$where = "kode = '".$kode_instansi_sess."'";
				}

				$query = "SELECT mulai_gen.sa, akhir_gen.fa, m_instansi.nama, rb_gen.rb, rb_gen.lu, m_instansi.kode 
							from m_instansi
							LEFT JOIN LATERAL (
								SELECT max(tsa.start_at) as sa
								FROM t_cron_scheduler tsa
								WHERE tsa.id_upd = m_instansi.kode
								ORDER BY sa DESC LIMIT 1
							)mulai_gen on true
							LEFT JOIN LATERAL (
								SELECT max(tsf.finish_at) as fa
								FROM t_cron_scheduler tsf
								WHERE tsf.id_upd = m_instansi.kode
								ORDER BY fa DESC LIMIT 1
							)akhir_gen on true
							LEFT JOIN LATERAL (
								SELECT trb.running_by as rb, trb.date as lu
								FROM t_cron_scheduler trb
								WHERE trb.id_upd = m_instansi.kode
								ORDER BY trb.finish_at DESC LIMIT 1
							)rb_gen on true
							where ".$where." limit ".$config['per_page'];
				$this->dataSch = $this->monitoring_tarik_model->showData2($query);
				//$this->dataSch = $this->monitoring_tarik_model->showData($where,$like2,$order_by,$config['per_page'],$this->input->get('per_page'));
				$this->pagination->initialize($config);
			}
		}

		/*if ($this->session->userdata('id_kategori_karyawan') == '1') {
			$this->template_view->load_view('daftar_generate_laporan/d_generate_laporan_view_opt');
		}else{
			$this->template_view->load_view('template/sedang-perbaikan');
		}*/
		$this->template_view->load_view('daftar_generate_laporan/d_generate_laporan_view_opt');
	}

	public function autocomplete_pegawai()
	{	
		//jika instansi sekretariat dewan
		if ($this->input->post('kode_instansi') == '2.00.00.00.00') {
			$kodeAwalDinas	=	substr($this->input->post('kode_instansi'),0,3);
		}else{
			$kodeAwalDinas	=	substr($this->input->post('kode_instansi'),0,4);
		}

		$tmulai = explode('/', $this->input->post('tgl_mulai'));
		$thingga = explode('/', $this->input->post('tgl_akhir'));
		$akhir =	$thingga[2]."-".$thingga[1]."-".$thingga[0];
		$mulai =	$tmulai[2]."-".$tmulai[1]."-".$tmulai[0];

		/*$queryPegawai 	=	$this->db->query("
			select 
				m.id as id_pegawai,
				m.nip,m.nama, 
				pukh.kode_unor as unor ,
				mjb.nama as nama_unor,
				m.aktif,
				mjb.nama as nama_jabatan
				
			from 
				m_pegawai m,m_jenis_jabatan mjb
				LEFT JOIN LATERAL (
					SELECT kode_unor
					FROM m_pegawai_unit_kerja_histori h
					WHERE  tgl_mulai <= '".$akhir."' and m.id = h.id_pegawai or (h.langsung_pindah = 't' and h.id_pegawai = m.id)
					ORDER BY tgl_mulai DESC
					LIMIT 1
				) pukh ON true
			where 
				pukh.kode_unor like '".$kodeAwalDinas."%'
				and mjb.kode=m.kode_jenis_jabatan 
				and ( m.nama ilike '%".$this->input->post('term')."%' or m.nip ilike '%".$this->input->post('term')."%' )
			order by 
				mjb.urut
		");*/

		$queryPegawai 	=	$this->db->query("
			select 
				m.id as id_pegawai,
				m.nip,m.nama, 
				pukh.kode_unor as unor ,
				mjb.nama as nama_unor,
				m.aktif,
				mjb.nama as nama_jabatan
				
			from 
				m_pegawai m
				LEFT JOIN m_jenis_jabatan mjb ON mjb.kode=m.kode_jenis_jabatan 
				LEFT JOIN LATERAL (
					SELECT kode_unor, langsung_pindah
					FROM m_pegawai_unit_kerja_histori h
					WHERE  tgl_mulai <= '".$akhir."' and m.id = h.id_pegawai or (h.langsung_pindah = 't' and h.id_pegawai = m.id)
					ORDER BY tgl_mulai DESC
					LIMIT 1
				) pukh ON true
			where 
				pukh.kode_unor like '".$kodeAwalDinas."%'
				and ( m.nama ilike '%".$this->input->post('term')."%' or m.nip ilike '%".$this->input->post('term')."%' )
				
			order by 
				mjb.urut
		");

		$dataPegawai	=	$queryPegawai->result();
		echo '[';		
		$i=1;
		foreach($dataPegawai as $data){			
			
			if($i > 1){echo ",";}
			echo '{ "label":"'.$data->nip.' - '.$data->nama.'", "id_pegawai":"'.$data->id_pegawai.'", "nip":"'.$data->nip.'", "nama":"'.$data->nama.'" } ';
			$i++;
		}
		echo ']';
	}


}
