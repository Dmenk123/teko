<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class lap_rekap_instansi_optimize extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model','log_laporan_model']);
	}



	public function index(){

		$this->load->library('konversi_menit');

		$whereInstansi 		=	"kode = '".$this->input->get('id_instansi')."' ";
		$this->dataInstansi = 	$this->instansi_model->getData($whereInstansi,"","");
		
		$this->load->library('ciqrcode');
		$this->load->library('encrypt_decrypt');
		
		$config['cacheable']    = true; //boolean, the default is true
		$config['cachedir']     = '/upload/'; //string, the default is application/cache/
		$config['errorlog']     = '/upload/'; //string, the default is application/logs/
		$config['imagedir']     = '/upload/qrcode/'; //direktori penyimpanan qr code
		$config['quality']      = true; //boolean, the default is true
		$config['size']         = '1024'; //interger, the default is 1024
		$config['black']        = array(224,255,255); // array, default is array(255,255,255)
		$config['white']        = array(70,130,180); // array, default is array(0,0,0)
		$this->ciqrcode->initialize($config);

		$url 			=	"asdasd";
		$image_name		=	time().'.png'; //buat name dari qr code sesuai dengan nim

		$currentURL = current_url(); //for simple URL
	//var_dump( $this->input->server('QUERY_STRING')); //for parameters
		$fullURL = $currentURL.'?'.$this->input->server('QUERY_STRING'); 

		$params['data'] 	= $fullURL; //data yang akan di jadikan QR CODE
		$params['level'] 	= 'H'; //H=High
		$params['size'] 	= 10;
		$params['savename'] = FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/images/
		$this->ciqrcode->generate($params); // fungsi untuk generate QR CODE
		
		$this->imageQrCode =	$image_name;


		$this->dataTables = "";

		$tglMulai		=	explode('/',$this->input->get('tgl_mulai'));
		$tglMulai		=	$tglMulai[2]."-".$tglMulai[1]."-".$tglMulai[0];

		$tglSelesai		=	explode('/',$this->input->get('tgl_selesai'));
		$tglSelesai		=	$tglSelesai[2]."-".$tglSelesai[1]."-".$tglSelesai[0];

		$this->tgl_terakhir 	= date('Y-m-t', strtotime($tglMulai));
		
		$this->sudahAda	=	$this->log_laporan_model->getData("kd_instansi = '".$this->input->get('id_instansi')."' and tgl_log = '".$this->tgl_terakhir."' ");
		
		$awal 		= strtotime($tglMulai);
		$akhir 		= strtotime($tglSelesai);

		$dt1 		= new DateTime($tglMulai);
		$dt2 		= new DateTime($tglSelesai);
		$jumlahHari = $dt1->diff($dt2) ;
		$jumlahHari = $jumlahHari->days + 1 ;


		$diff 			= abs($akhir-$awal);
		//$jumlahHari 	= $diff/86400;

		//var_dump($telat);

		$selainMinggu 	= array();
		$sabtuminggu 	= array();

		$tanggalsabtu 	= "";
		$tanggalminggu 	= "";
		$tanggalSeninJumat 	= "";

		$iSabtu=1;
		$iMinggu=1;
		$iSeninJumat=1;
		for ($i=$awal; $i <= $akhir; $i += (60 * 60 * 24)) {
			if (date('w', $i) !== '0' && date('w', $i) !== '6') {

				$selainMinggu[] = $i;

				if($iSeninJumat == '1'){
					$tanggalSeninJumat .= "'".date('Y-m-d',$i)."'";
				}
				else{
					$tanggalSeninJumat .= ",'".date('Y-m-d',$i)."'";
				}
				$iSeninJumat++;


			} else {
				$sabtuminggu[] 	= $i;
				//echo $ii;
					if(date('w', $i) == '6'){
						if($iSabtu == '1'){
							$tanggalsabtu .= "'".date('Y-m-d',$i)."'";
						}
						else{
							$tanggalsabtu .= ",'".date('Y-m-d',$i)."'";
						}
						$iSabtu++;
					}

					if(date('w', $i) == '0'){
						if($iMinggu == '1'){
							$tanggalminggu .= "'".date('Y-m-d',$i)."'";
						}
						else{
							$tanggalminggu .= ",'".date('Y-m-d',$i)."'";
						}
						$iMinggu++;
					}
			}
		}

		//echo $tanggalSeninJumat;

		$queryJumlahHariLibur 	=	$this->db->query("
		select
			count(*) as jumlah
		from
			s_hari_libur
		where
			tanggal >= '".$tglMulai."'
			AND tanggal <=  '".$tglSelesai."' and
			id not in (SELECT id FROM s_hari_libur WHERE EXTRACT(ISODOW FROM tanggal) IN (6, 7))
		");
		$dataJumlahHariLibur	=	$queryJumlahHariLibur->row();
		//var_dump($this->db->last_query());

	 	$jumlahSeninJumat	=	$jumlahHari - count($sabtuminggu);
	 	$jumlahMasuk		=	$jumlahSeninJumat - $dataJumlahHariLibur->jumlah;

		$kodeAwalDinas	=	substr($this->input->get('id_instansi'),0,4);

		if($this->input->get("pns") == 'y'){
			$wherePns 	= " and m.kode_status_pegawai='1'";
		}
		else{
			$wherePns 	= " and m.kode_status_pegawai!='1'";
		}


		/**$queryPegawai 	=	$this->db->query("
		select
			m.id as id_pegawai,m.nip,m.nama, pukh.kode_unor as unor ,mjb.nama as nama_unor,m.aktif
		from m_pegawai m,m_jenis_jabatan mjb
			LEFT JOIN LATERAL (
				SELECT kode_unor
				FROM m_pegawai_unit_kerja_histori h
				WHERE tgl_mulai <= '".$tglMulai."' and m.id = h.id_pegawai
				ORDER BY tgl_mulai DESC
				LIMIT 1
			) pukh ON true
		where pukh.kode_unor like '".$kodeAwalDinas."%'
		and mjb.kode=m.kode_jenis_jabatan $wherePns
		order by mjb.urut,mjb.nama
		");**/

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
					LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".$tglMulai."' and m.id = h.id_pegawai
				ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			pukh ON true
			LEFT JOIN LATERAL (
				SELECT h.kode_jabatan, h.tgl_mulai, mjj.nama as nama_jabatan, mjj.urut FROM m_pegawai_jabatan_histori h LEFT JOIN m_jenis_jabatan mjj ON  h.kode_jabatan =  mjj.kode WHERE h.tgl_mulai <=  '".$tglMulai."' and m.id = h.id_pegawai ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			pjh ON true
			LEFT JOIN LATERAL (
				SELECT h.kode_golongan, h.tgl_mulai, mg.nama as nama_golongan FROM m_pegawai_golongan_histori h LEFT JOIN m_golongan mg ON  h.kode_golongan =  mg.kode WHERE h.tgl_mulai <=  '".$tglMulai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			pgh ON true
			LEFT JOIN LATERAL (
				SELECT h.kode_eselon, h.tgl_mulai, me.nama_eselon FROM m_pegawai_eselon_histori h LEFT JOIN m_eselon me ON  h.kode_eselon =  me.kode WHERE h.tgl_mulai <=  '".$tglMulai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			peh ON true
			LEFT JOIN LATERAL (
				SELECT h.id_rumpun_jabatan, h.tgl_mulai, mrj.nama as nama_rumpun_jabatan FROM m_pegawai_rumpun_jabatan_histori h LEFT JOIN m_rumpun_jabatan mrj ON  h.id_rumpun_jabatan =  mrj.id WHERE h.tgl_mulai <=  '".$tglMulai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
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

//echo $this->db->last_query();

		$this->dataPegawai	=	$queryPegawai->result();



		$i=1;
		foreach($this->dataPegawai as $dataPegawai){

		$queryJumlahKerja 	=	$this->db->query("
		select
			count(kode_masuk != '*' OR NULL) as jumlah_kerja,
			count(finger_masuk is not null OR NULL) as jumlah_hadir,
			count(datang_telat > 0 OR NULL) as datang_telat,
			count(pulang_cepat > 0 OR NULL) as pulang_cepat,
			count(kode_tidak_masuk = 'M' OR NULL) as jumlah_m,
			count(kode_tidak_masuk = 'CH' OR NULL) as jumlah_ch,
			count(kode_tidak_masuk = 'CM' OR NULL) as jumlah_cm,
			count(kode_tidak_masuk = 'CT' OR NULL) as jumlah_ct,
			count(kode_tidak_masuk = 'CAP' OR NULL) as jumlah_cap,
			count(kode_tidak_masuk = 'DK' OR NULL) as jumlah_dk,
			count(kode_tidak_masuk = 'DL' OR NULL) as jumlah_dl,
			count(kode_tidak_masuk = 'I' OR NULL) as jumlah_i,
			count(kode_tidak_masuk = 'LP' OR NULL) as jumlah_lp,
			count(kode_tidak_masuk = 'MPP' OR NULL) as jumlah_mpp,			
			count((JAM_KERJA = 'SK' OR JAM_KERJA = 'CS') OR NULL) as jumlah_sk,
			count(kode_tidak_masuk = 'TB' OR NULL) as jumlah_tb,
			count(kode_tidak_masuk = 'UFT' OR NULL) as jumlah_uft,
			sum(lembur_diakui) as jumlah_diakui,
			sum(lembur) as jumlah_lembur
		from
			data_mentah
		where
			tanggal BETWEEN '".$tglMulai."' AND '".$tglSelesai."' and
			id_pegawai = '".$dataPegawai->id_pegawai."'
			
		");

		$dataProcessing	=	$queryJumlahKerja->row();
		//echo ;

		// $queryJumlahHadir	=	$this->db->query("
		// select
		// 	count(*) as jumlah
		// from
		// 	data_mentah
		// where
		// 	tanggal >= '".$tglMulai."'
		// 	AND tanggal <=  '".$tglSelesai."' and
		// 	id_pegawai = '".$dataPegawai->id_pegawai."' and
		// 	finger_masuk is not null 
		// ");
		// $dataJumlahHadir	=	$queryJumlahHadir->row();

		// $queryJumlahDatangTelat	=	$this->db->query("
		// select
		// 	count(*) as jumlah
		// from
		// 	data_mentah
		// where
		// 	tanggal >= '".$tglMulai."'
		// 	AND tanggal <=  '".$tglSelesai."' and
		// 	id_pegawai = '".$dataPegawai->id_pegawai."' and
		// 	datang_telat > 0
		// ");

		// $dataJumlahDatangTelat	=	$queryJumlahDatangTelat->row();

		// $queryJumlahPulangCepat	=	$this->db->query("
		// select
		// 	count(*) as jumlah
		// from
		// 	data_mentah
		// where
		// 	tanggal >= '".$tglMulai."'
		// 	AND tanggal <=  '".$tglSelesai."' and
		// 	id_pegawai = '".$dataPegawai->id_pegawai."' and
		// 	pulang_cepat > 0
		// ");
		// $dataJumlahPulangCepat	=	$queryJumlahPulangCepat->row();

		$queryLEmburSeninJumat	=	$this->db->query("
		select
			sum(lembur_diakui) as jumlah_diakui,
			sum(lembur) as jumlah
		from
			data_mentah
		where
			id_pegawai = '".$dataPegawai->id_pegawai."'
			AND tanggal in ($tanggalSeninJumat)
		");
		$dataLEmburSeninJumat	=	$queryLEmburSeninJumat->row();
		$dataLEmburSeninJumatArray	=	$this->konversi_menit->hitung($dataLEmburSeninJumat->jumlah);

		$queryLemburSabtu	=	$this->db->query("
		select
			sum(lembur_diakui) as jumlah_diakui,
			sum(lembur) as jumlah
		from
			data_mentah
		where
			id_pegawai = '".$dataPegawai->id_pegawai."'
			AND tanggal in ($tanggalsabtu)
		");
		$dataLemburSabtu	=	$queryLemburSabtu->row();
		$dataLemburSabtuArray	=	$this->konversi_menit->hitung($dataLemburSabtu->jumlah);

		$queryLemburMinggu	=	$this->db->query("
		select
			sum(lembur_diakui) as jumlah_diakui,
			sum(lembur) as jumlah
		from
			data_mentah
		where
			id_pegawai = '".$dataPegawai->id_pegawai."'
			AND tanggal in ($tanggalminggu)
		");
		$dataLemburMinggu	=	$queryLemburMinggu->row();
		$dataLemburMingguArray	=	$this->konversi_menit->hitung($dataLemburMinggu->jumlah);

		// $oke = "select
		// 	sum(lembur_diakui) as jumlah_diakui,
		// 	sum(lembur) as jumlah
		// from
		// 	data_mentah
		// where
		// 	id_pegawai = '".$dataPegawai->id_pegawai."'
		// 	AND tanggal in ($tanggalminggu)";

		// $queryJumlah_M 	=	$this->db->query("
		// select
		// 	count(*) as jumlah
		// from
		// 	data_mentah
		// where
		// 	tanggal >= '".$tglMulai."'
		// 	AND tanggal <=  '".$tglSelesai."' and
		// 	id_pegawai = '".$dataPegawai->id_pegawai."' and
		// 	kode_tidak_masuk = 'M'
		// ");
		// $dataJumlah_M	=	$queryJumlah_M->row();

		// $queryJumlah_CH 	=	$this->db->query("
		// select
		// 	count(*) as jumlah
		// from
		// 	data_mentah
		// where
		// 	tanggal >= '".$tglMulai."'
		// 	AND tanggal <=  '".$tglSelesai."' and
		// 	id_pegawai = '".$dataPegawai->id_pegawai."' and
		// 	kode_tidak_masuk = 'CH'
		// ");
		// $dataJumlah_CH	=	$queryJumlah_CH->row();

		// $queryJumlah_CM 	=	$this->db->query("
		// select
		// 	count(*) as jumlah
		// from
		// 	data_mentah
		// where
		// 	tanggal >= '".$tglMulai."'
		// 	AND tanggal <=  '".$tglSelesai."' and
		// 	id_pegawai = '".$dataPegawai->id_pegawai."' and
		// 	kode_tidak_masuk = 'CM'
		// ");
		// $dataJumlah_CM	=	$queryJumlah_CM->row();

		// $queryJumlah_CT 	=	$this->db->query("
		// select
		// 	count(*) as jumlah
		// from
		// 	data_mentah
		// where
		// 	tanggal >= '".$tglMulai."'
		// 	AND tanggal <=  '".$tglSelesai."' and
		// 	id_pegawai = '".$dataPegawai->id_pegawai."' and
		// 	kode_tidak_masuk = 'CT'
		// ");
		// $dataJumlah_CT	=	$queryJumlah_CT->row();

		// $queryJumlah_CAP 	=	$this->db->query("
		// select
		// 	count(*) as jumlah
		// from
		// 	data_mentah
		// where
		// 	tanggal >= '".$tglMulai."'
		// 	AND tanggal <=  '".$tglSelesai."' and
		// 	id_pegawai = '".$dataPegawai->id_pegawai."' and
		// 	kode_tidak_masuk = 'CAP'
		// ");
		// $dataJumlah_CAP	=	$queryJumlah_CAP->row();

		// $queryJumlah_DK 	=	$this->db->query("
		// select
		// 	count(*) as jumlah
		// from
		// 	data_mentah
		// where
		// 	tanggal >= '".$tglMulai."'
		// 	AND tanggal <=  '".$tglSelesai."' and
		// 	id_pegawai = '".$dataPegawai->id_pegawai."' and
		// 	kode_tidak_masuk = 'DK'
		// ");
		// $dataJumlah_DK	=	$queryJumlah_DK->row();

		// $queryJumlah_DL 	=	$this->db->query("
		// select
		// 	count(*) as jumlah
		// from
		// 	data_mentah
		// where
		// 	tanggal >= '".$tglMulai."'
		// 	AND tanggal <=  '".$tglSelesai."' and
		// 	id_pegawai = '".$dataPegawai->id_pegawai."' and
		// 	kode_tidak_masuk = 'DL'
		// ");
		// $dataJumlah_DL	=	$queryJumlah_DL->row();

		// $queryJumlah_I 	=	$this->db->query("
		// select
		// 	count(*) as jumlah
		// from
		// 	data_mentah
		// where
		// 	tanggal >= '".$tglMulai."'
		// 	AND tanggal <=  '".$tglSelesai."' and
		// 	id_pegawai = '".$dataPegawai->id_pegawai."' and
		// 	kode_tidak_masuk = 'I'
		// ");
		// $dataJumlah_I	=	$queryJumlah_I->row();

		// $queryJumlah_LP 	=	$this->db->query("
		// select
		// 	count(*) as jumlah
		// from
		// 	data_mentah
		// where
		// 	tanggal >= '".$tglMulai."'
		// 	AND tanggal <=  '".$tglSelesai."' and
		// 	id_pegawai = '".$dataPegawai->id_pegawai."' and
		// 	kode_tidak_masuk = 'LP'
		// ");
		// $dataJumlah_LP	=	$queryJumlah_LP->row();

		// $queryJumlah_MPP 	=	$this->db->query("
		// select
		// 	count(*) as jumlah
		// from
		// 	data_mentah
		// where
		// 	tanggal >= '".$tglMulai."'
		// 	AND tanggal <=  '".$tglSelesai."' and
		// 	id_pegawai = '".$dataPegawai->id_pegawai."' and
		// 	kode_tidak_masuk = 'MPP'
		// ");
		// $dataJumlah_MPP	=	$queryJumlah_MPP->row();

		// $queryJumlah_SK 	=	$this->db->query("
		// select
		// 	count(*) as jumlah
		// from
		// 	data_mentah
		// where
		// 	tanggal >= '".$tglMulai."'
		// 	AND tanggal <=  '".$tglSelesai."' and
		// 	id_pegawai = '".$dataPegawai->id_pegawai."' and
		// 	JAM_KERJA in ('SK','CS')
		// ");
		// $dataJumlah_SK	=	$queryJumlah_SK->row();

		// $queryJumlah_TB 	=	$this->db->query("
		// select
		// 	count(*) as jumlah
		// from
		// 	data_mentah
		// where
		// 	tanggal >= '".$tglMulai."'
		// 	AND tanggal <=  '".$tglSelesai."' and
		// 	id_pegawai = '".$dataPegawai->id_pegawai."' and
		// 	kode_tidak_masuk = 'TB'
		// ");
		// $dataJumlah_TB	=	$queryJumlah_TB->row();

		// $queryJumlah_UFT 	=	$this->db->query("
		// select
		// 	count(*) as jumlah
		// from
		// 	data_mentah
		// where
		// 	tanggal >= '".$tglMulai."'
		// 	AND tanggal <=  '".$tglSelesai."' and
		// 	id_pegawai = '".$dataPegawai->id_pegawai."' and
		// 	kode_tidak_masuk = 'UFT'
		// ");
		// $dataJumlah_UFT	=	$queryJumlah_UFT->row();

		$this->dataTables .= "
			<tr>
			<td align='center'>".$i."</td>
			<td>".$dataPegawai->nama."</td>
			<td align='center'>".$dataPegawai->nip."</td>
			<td>".$dataPegawai->nama_unor." </td>
			<td align='center'>".$jumlahMasuk."</td>
			<td align='center'>".$dataProcessing->jumlah_hadir."</td>
			<td align='center'>".$dataProcessing->datang_telat."</td>
			<td align='center'>".$dataProcessing->pulang_cepat."</td>
			<td align='center'>".$dataLEmburSeninJumatArray['jam']."</td>
			<td align='center'>".$dataLEmburSeninJumatArray['menit']."</td>
			<td align='center'>".$dataLemburSabtuArray['jam']."</td>
			<td align='center'>".$dataLemburSabtuArray['menit']."</td>
			<td align='center'>".$dataLemburMingguArray['jam']."</td>
			<td align='center'>".$dataLemburMingguArray['menit']."</td>
			<td align='center'>".$dataProcessing->jumlah_m."</td>
			<td align='center'>".$dataProcessing->jumlah_ch."</td>
			<td align='center'>".$dataProcessing->jumlah_cm."</td>
			<td align='center'>".$dataProcessing->jumlah_ct."</td>
			<td align='center'>".$dataProcessing->jumlah_cap."</td>
			<td align='center'>".$dataProcessing->jumlah_dk."</td>
			<td align='center'>".$dataProcessing->jumlah_dl."</td>
			<td align='center'>".$dataProcessing->jumlah_i."</td>
			<td align='center'>".$dataProcessing->jumlah_lp."</td>
			<td align='center'>".$dataProcessing->jumlah_mpp."</td>
			<td align='center'>".$dataProcessing->jumlah_sk."</td>
			<td align='center'>".$dataProcessing->jumlah_tb."</td>
			<td align='center'>".$dataProcessing->jumlah_uft."</td>
			</tr>
		";
		$i++;
		}

		$this->load->view('cetak/lap_rekap_instansi_view');
	}
}
