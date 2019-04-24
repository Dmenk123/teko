<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class lap_absensi_lembur extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_sebelum_update_model']);
	}


	public function index(){


		$this->load->library('ciqrcode');



		$this->load->library('konversi_menit');

		$whereInstansi 		=	"kode = '".$this->input->get('id_instansi')."' ";
		$this->dataInstansi = 	$this->instansi_model->getData($whereInstansi,"","");

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


		$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
		// Tanggal pertama pada bulan ini
		$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
		// Tanggal terakhir pada bulan ini
		$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));


		$this->dataLembur = "";
		$this->dataLembur .= '
		<table width="100%" class="cloth" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<th>NO</th>
		<th>NAMA</th>';

		while (strtotime($this->tgl_pertama) <= strtotime($this->tgl_terakhir )) {

			$this->dataLembur .= '<th>'.date ("d", strtotime($this->tgl_pertama)).'</th>';
			$this->tgl_pertama = date ("Y-m-d", strtotime("+1 days", strtotime($this->tgl_pertama)));
		}



		$this->dataLembur .= '<th>Total</th><th>Skor Lembur (%)</th></tr>';



		/**$select = "m_pegawai.nama,m_pegawai.id as id_pegawai,m_jenis_jabatan.nama as nama_jenis_jabatan";
		if($this->input->get("pns") == 'y'){
			$where 	= "m_pegawai.kode_instansi = '".$this->input->get('id_instansi')."' and m_pegawai.kode_status_pegawai='1'";
		}
		else{

			$where 	= "m_pegawai.kode_instansi = '".$this->input->get('id_instansi')."' and m_pegawai.kode_status_pegawai!='1'";
		}

		$join 	= array(
			array(
				"table" => "m_jenis_jabatan",
				"on"    => "m_pegawai.kode_jenis_jabatan = m_jenis_jabatan.kode"
			)
		);
		$orderBy 			= "m_jenis_jabatan.urut,m_pegawai.nama";
		$this->dataPegawai 	= $this->pegawai_model->showData($where,'',$orderBy,'','','','',$select,$join);
		**/

		$kodeAwalDinas	=	substr($this->input->get('id_instansi'),0,4);

		if($this->input->get("pns") == 'y'){
			$wherePns 	= " and m.kode_status_pegawai='1'";
		}
		else{

			$wherePns 	= " and m.kode_status_pegawai!='1'";
		}

		$tanggal	=	$this->input->get('tahun')."-".$this->input->get('bulan')."-01";


		$tglSelesai 	= date('Y-m-t', strtotime($tanggal));

		$queryPegawai 	=	$this->db->query("
		select
			m.id as id_pegawai,m.nama, m.nip,
			pukh.nama_unor,
			pukh.nama_instansi,
			pjh.nama_jabatan, pjh.urut,
			pgh.nama_golongan,
			peh.nama_eselon,
			prjh.nama_rumpun_jabatan
		from
			m_pegawai m
			LEFT JOIN LATERAL (
				SELECT
					h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
				FROM
					m_pegawai_unit_kerja_histori h
					LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
					LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".$tglSelesai."' and m.id = h.id_pegawai
				ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			pukh ON true
			LEFT JOIN LATERAL (
				SELECT h.kode_jabatan, h.tgl_mulai, mjj.nama as nama_jabatan, mjj.urut FROM m_pegawai_jabatan_histori h LEFT JOIN m_jenis_jabatan mjj ON  h.kode_jabatan =  mjj.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			pjh ON true
			LEFT JOIN LATERAL (
				SELECT h.kode_golongan, h.tgl_mulai, mg.nama as nama_golongan FROM m_pegawai_golongan_histori h LEFT JOIN m_golongan mg ON  h.kode_golongan =  mg.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			pgh ON true
			LEFT JOIN LATERAL (
				SELECT h.kode_eselon, h.tgl_mulai, me.nama_eselon FROM m_pegawai_eselon_histori h LEFT JOIN m_eselon me ON  h.kode_eselon =  me.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			peh ON true
			LEFT JOIN LATERAL (
				SELECT h.id_rumpun_jabatan, h.tgl_mulai, mrj.nama as nama_rumpun_jabatan FROM m_pegawai_rumpun_jabatan_histori h LEFT JOIN m_rumpun_jabatan mrj ON  h.id_rumpun_jabatan =  mrj.id WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			prjh ON true
		where
			pukh.kode_instansi = '".$this->input->get('id_instansi')."' $wherePns
		order by
			pjh.urut,
			peh.kode_eselon,
			pgh.kode_golongan desc,
			m.nip
			");
		$this->dataPegawai	=	$queryPegawai->result();
		//echo $this->db->last_query();
		$i=1;
		foreach($this->dataPegawai as $dataPegawai){


			$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
			// Tanggal pertama pada bulan ini
			$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
			// Tanggal terakhir pada bulan ini
			$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));

			$this->dataLembur .= "<tr><td align='center'>".$i."</td>";
			$this->dataLembur .= "<td>".$dataPegawai->nama."</td>";


			$totalLemburJumlah 			= 0;
			$totalLemburJumlahDiakui 	= 0;

			while (strtotime($this->tgl_pertama) <= strtotime($this->tgl_terakhir )) {


				$queryJumlahLembur	=	$this->db->query("select lembur,lembur_diakui from data_mentah_sebelum_update where id_pegawai='".$dataPegawai->id_pegawai."' and tanggal='".$this->tgl_pertama."'");
				$dataHasilLembur	=	$queryJumlahLembur->row();

				if($dataHasilLembur){
					$lemburJumlah = $dataHasilLembur->lembur;
					$lemburJumlahDiakui = $dataHasilLembur->lembur_diakui;
				}
				else{
					$lemburJumlah = "0";
					$lemburJumlahDiakui = "0";
				}
				$lembur = $this->konversi_menit->hitung($lemburJumlah);

				if($lemburJumlah == 0){
					$color="red";
				}
				elseif($lemburJumlah != $lemburJumlahDiakui ){
					$color="red";
				}
				else{
					$color="";
				}

				//$this->dataLembur .= "<td align='center' >".$lemburJumlahDiakui." -- ".$lemburJumlah." -- <span style='color:".$color."'>".sprintf("%02d", $lembur['jam_angka'])." : ".sprintf("%02d",$lembur['menit_angka'])."</span></td>";
				$this->dataLembur .= "<td align='center' ><span style='color:".$color."'>".sprintf("%02d", $lembur['jam_angka'])." : ".sprintf("%02d",$lembur['menit_angka'])."</span></td>";


				$totalLemburJumlah += $lemburJumlah;
				$totalLemburJumlahDiakui += $lemburJumlahDiakui;

				$this->tgl_pertama = date ("Y-m-d", strtotime("+1 days", strtotime($this->tgl_pertama)));
			}


				$jumlahPersen 	= round(($totalLemburJumlah / 1800) * 100);
				if($jumlahPersen > 99){
					$jumlahPersen = 100;
				}
				else{
					$jumlahPersen = "<span style='color:orange;'>".$jumlahPersen."</span>";
				}

			$jumlahLembur			=	$this->konversi_menit->hitung($totalLemburJumlahDiakui);

			$bulan 		=	date('Y-m', strtotime($hari_ini));
			if($bulan =='2018-05' || $bulan =='2018-06'){				
				$where	=	"and jenis = 'RAMADHAN'";
			}
			else{
				$where	=	"and jenis = 'BIASA'";
			}

			$queryPersen 	=	$this->db->query("select skor from m_skor_lembur where menit_mulai <='".$totalLemburJumlahDiakui."' and menit_akhir >= '".$totalLemburJumlahDiakui."' $where");
			$dataPersen		=	$queryPersen->row();


			$this->dataLembur .= "<td align='center' ><b>".sprintf("%02d", $jumlahLembur['jam_angka'])." : ".sprintf("%02d", $jumlahLembur['menit_angka'])."</b></td>";
			$this->dataLembur .= "<td align='center'>".$dataPersen->skor."</td>";
			$this->dataLembur .= "</tr>";
			$i++;
		}

		$this->dataLembur .= '</table>';

		$this->bulan 	=	$namaBulan[$this->input->get('bulan')];

		$this->load->view('cetak/lap_absensi_lembur_view');
	}



}
