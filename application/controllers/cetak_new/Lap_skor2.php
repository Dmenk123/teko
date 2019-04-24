
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lap_skor2 extends CI_Controller {
	private $dataTable = "";

	public function __construct() {
		parent::__construct();
		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model','log_laporan_model']);
	}

	public function index(){

		$whereInstansi 		=	"kode = '".$this->input->get('id_instansi')."' ";
		$this->dataInstansi = 	$this->instansi_model->getData($whereInstansi,"","");

		//start generate QR-CODE
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
		//end generate QR-CODE

		$this->load->library('konversi_menit');

		$whereInstansi 		=	"kode = '".$this->input->get('id_instansi')."' ";
		$this->dataInstansi = 	$this->instansi_model->getData($whereInstansi,"","");
		
		$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
		// Tanggal pertama pada bulan ini
		$tglMulai 	= date('Y-m-01', strtotime($hari_ini));
		// Tanggal terakhir pada bulan ini
		$tglSelesai 	= date('Y-m-t', strtotime($hari_ini));

		//$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
		//this->sudahAda	=	$this->log_laporan_model->getData("kd_instansi = '".$this->input->get('id_instansi')."' and tgl_log = '".$this->tgl_terakhir."' ");
		//Tanggal pertama pada bulan ini
		$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
		// Tanggal terakhir pada bulan ini
		$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));

		$awal 			= strtotime($tglMulai);
		$akhir 			= strtotime($tglSelesai);

		$dt1 				= new DateTime($tglMulai);
		$dt2 				= new DateTime($tglSelesai);
		$jumlahHari = $dt1->diff($dt2) ;
		$jumlahHari = $jumlahHari->days + 1;
		$diff 			= abs($akhir-$awal);
		//$jumlahHari 	= $diff/86400;
		//var_dump($telat);
		$selainMinggu 	= array();
		$sabtuminggu 	= array();

		$tanggalsabtu 	= "";
		$tanggalminggu 	= "";
		$tanggalSeninJumat 	= "";

		$sabtuMinggu2 	= "";
		$iMinggu=0;
		$iMinggu2=1;
		$iSabtu=0;
		$iSeninJumat=0;
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
				$start_date 	= date("Y-m-d", $i);
				$queryLibur 	=	$this->db->query("
					select
						s_hari_libur.id,
						m_hari_libur.id as id_hari_libur,
						m_hari_libur.nama
					from
						s_hari_libur ,m_hari_libur
					where
						s_hari_libur.tanggal = '".$start_date."'  and
						s_hari_libur.id_libur = m_hari_libur.id
				");
				$this->dataLibur	=	$queryLibur->row();
				//jika hari libur
				if($this->dataLibur){
					if($iMinggu2 == 1){
						$sabtuMinggu2 .=  "'".date('Y-m-d',$i)."'";
					}
					else{
						$sabtuMinggu2 .=  ",'".date('Y-m-d',$i)."'";
					}

					$iMinggu2++;
				}

			} else {
				//$sabtuminggu2[] 	= date('Y-m-d',strtotime($i));
				//echo $iMinggu2;
				$sabtuminggu[] 	= $i;
				if($iMinggu2 == 1){
					 $sabtuMinggu2 .=  "'".date('Y-m-d',$i)."'";
				}
				else{
					$sabtuMinggu2 .=  ",'".date('Y-m-d',$i)."'";
				}

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
				$iMinggu2++;
			}
		}
		//var_dump([$sabtuMinggu2, $iMinggu2]);exit;
		//var_dump($sabtuMinggu2);

		$jumlahSabtuMinggu = $iSabtu + $iMinggu;

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
		$jumlahLibur		=	$jumlahSabtuMinggu + $dataJumlahHariLibur->jumlah;

		/** CEK APAKAH PERNAH PRINT LAPORAN */
		$bulan_get = $this->input->get('bulan');
		$tahun_get = $this->input->get('tahun');
		$id_instansi_get = $this->input->get('id_instansi');
		$pns_get = $this->input->get('pns');

		$queryCekSudahPrintLaporan	=	$this->db->query("
			select * from lap_skor_kehadiran
			where bulan = '$bulan_get'

			and tahun = '$tahun_get'
			and id_instansi = '$id_instansi_get'
			and pns = '$pns_get'
			and deleted_at is null
		");
		//cek jika sudah pernah cetak laporan
		if($queryCekSudahPrintLaporan->row()) {
			/** untuk cek apakah generate laporan benar2 selesai */
			$queryCekSudahFinishGenerateLaporan	= $this->db->query("
				select * from lap_skor_kehadiran
				where bulan = '$bulan_get'
				and tahun = '$tahun_get'
				and id_instansi = '$id_instansi_get'
				and pns = '$pns_get'
				and deleted_at is null
				and finished_at is not null
			");
			//cek apakah sudah finish generate laporan
			if($queryCekSudahFinishGenerateLaporan->row() or $this->input->get('lanjut_cetak')) {
				$this->printed($jumlahMasuk, $bulan_get, $tahun_get, $id_instansi_get, $pns_get);
				//$this->dataTable = "";
				return;
			} else {
				$data_uri = [
					'bulan' => $bulan_get,
					'tahun' => $tahun_get,
					'id_instansi' => $id_instansi_get,
					'pns' => $pns_get,
				];

				$queryGeneratingLaporan	= $this->db->query("
					select m.*, u.fullname from lap_skor_kehadiran m
					join c_security_user_new u on m.id_pegawai = u.id
					where bulan = '$bulan_get'
					and tahun = '$tahun_get'
					and id_instansi = '$id_instansi_get'
					and pns = '$pns_get'
					and deleted_at is null
					and finished_at is null
				")->row_array();

				$laporanTergenerate	= $this->db->query("
					select * from lap_skor_kehadiran_detil
					where bulan = '$bulan_get'
					and tahun = '$tahun_get'
					and id_instansi = '$id_instansi_get'
					and pns = '$pns_get'
					and deleted_at is null
				")->result();
				
				$this->session->set_flashdata('feedback_warning_tampilkan', [
					'uri' => $data_uri,
					'data_generate' => $queryGeneratingLaporan,
					'jml_pegawai' => count($this->dataPegawai),
					'jml_tergenerate' => count($laporanTergenerate),
				]);

				redirect('lap_skor','refresh');
			}
			#end
		}else{
			//jika belum pernah cetak header tabel laporan
			$this->load->model([
				'Lap_skor_kehadiran_model',
				'Lap_skor_kehadiran_detil_model'
			]);

			$data = [
				'bulan'				=> $bulan_get,
				'tahun'				=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'					=> $pns_get,
				'id_pegawai'	=> $this->session->userdata('id_karyawan'),
			];

			$this->Lap_skor_kehadiran_model->insert($data);
		}
		#end
		
		$kodeAwalDinas	=	substr($this->input->get('id_instansi'),0,4);

		if($this->input->get("pns") == 'y'){
				$wherePns 	= " and m.kode_status_pegawai < '5'";
		}
		else
		{
	//$wherePns 	= " and m.kode_status_pegawai >='5' and m.kode_status_pegawai > '1'";
			$wherePns 	= " and m.kode_status_pegawai >='5'";
		}

		$query_kode_sik = $this->db->query("select kode_sik, nama from m_instansi where kode = '".$this->input->get('id_instansi')."'");
		$data_kode_sik = $query_kode_sik->row();

		if (substr($data_kode_sik->nama, 0, 9) != 'Kecamatan') {
			$kode_instansi_all = $this->input->get('id_instansi');
			$whereQuery = "pukh.kode_instansi = '".$kode_instansi_all."'".$wherePns;
		} else {
			$kode_instansi_all = substr($this->input->get('id_instansi'), 0, 5);
			$whereQuery = "pukh.kode_instansi LIKE '".$kode_instansi_all.'%'."'".$wherePns;
		}

		/** JIKA DINAS PENDIDIKAN!!! */
		if ($this->input->get('id_instansi') == '5.09.00.93.00') {
			# start nambah status meninggal
			$queryPegawai 	=	$this->db->query("
				select
					m.id as id_pegawai,m.nama, m.nip, m.meninggal, m.tgl_meninggal,
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
							h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi, h.excel, h.langsung_pindah
						FROM
							m_pegawai_unit_kerja_histori h
							LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
							LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode 
							WHERE h.tgl_mulai <= '".$tglMulai."' and m.id = h.id_pegawai or (h.langsung_pindah = 't' and h.id_pegawai = m.id)
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
					".$whereQuery." and pukh.excel = 't'
				order by
					pjh.urut,
					peh.kode_eselon,
					pgh.kode_golongan desc,
					m.nip
			");
			# end nambah status meninggal
			$this->dataPegawai	=	$queryPegawai->result();
			//echo $this->db->last_query();
		} else {
			# start nambah status meninggal
			$queryPegawai 	=	$this->db->query("
				select
					m.id as id_pegawai,m.nama, m.nip, m.meninggal, m.tgl_meninggal,
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
							h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi, h.langsung_pindah
						FROM
							m_pegawai_unit_kerja_histori h
							LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
							LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode 
							WHERE h.tgl_mulai <= '".$tglSelesai."' and m.id = h.id_pegawai or (h.langsung_pindah = 't' and h.id_pegawai = m.id)
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
					".$whereQuery."
				order by
					pjh.urut,
					peh.kode_eselon,
					pgh.kode_golongan desc,
					m.nip
			");
			# end nambah status meninggal
			$this->dataPegawai	=	$queryPegawai->result();
			//echo $this->db->last_query();
		}

		$i=1;
		foreach($this->dataPegawai as $dataPegawai){
			$skor_detil = [];

			if($dataPegawai->nama_jabatan =='Staf'){
				$unor = $dataPegawai->nama_jabatan." - ".$dataPegawai->nama_rumpun_jabatan;
			}
			else{
				$unor = $dataPegawai->nama_jabatan;
			}

			$awallimabelas 		= 0;
			$kurang_lima_bos 	= '';
			# start perwali desember 2018
			if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
				$awallimabelas = 5;
				$kurang_lima_bos = 'count(CASE WHEN datang_telat > 0 AND datang_telat <= 5 THEN datang_telat END) as jumlahlambatkuranglima,';
			}
			# end perwali database 2018

			$cuti_tahunan_bos = "jam_kerja = 'CT' AND (keterangan = 'IJIN CT DI HARI KERJA' OR excel)";
			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
				$cuti_tahunan_bos = "(jam_kerja = 'CT' AND (keterangan = 'IJIN CT DI HARI KERJA' OR excel)) OR (jam_kerja = 'TB' AND (keterangan = 'IJIN TB DI HARI KERJA' OR excel))";
			}
			# end perwali januari 2018
			$queryJumlah	=	$this->db->query("
				select
					$kurang_lima_bos
					count(CASE WHEN datang_telat > $awallimabelas AND datang_telat <= 15 THEN datang_telat END) as jumlahlambatkuranglimabelas,
					count(CASE WHEN datang_telat > 15 AND datang_telat <= 60 THEN datang_telat END) as jumlahlambatkurangsatujam,
					count(CASE WHEN datang_telat > 60 AND datang_telat <= 120 THEN datang_telat END) as jumlahlambatkurangduajam,
					count(CASE WHEN datang_telat > 120 AND datang_telat <= 180 THEN datang_telat END) as jumlahlambatkurangtigajam,
					count(CASE WHEN datang_telat > 180 THEN datang_telat END) as jumlahlambatkurangfull,
					count(CASE WHEN pulang_cepat > 0 AND pulang_cepat <= 15 THEN pulang_cepat END) as jumlahcepatkuranglimabelas,
					count(CASE WHEN pulang_cepat > 15 AND pulang_cepat <= 60 THEN pulang_cepat END) as jumlahcepatkurangsatujam,
					count(CASE WHEN pulang_cepat > 60 AND pulang_cepat <= 120 THEN pulang_cepat END) as jumlahcepatkurangduajam,
					count(CASE WHEN pulang_cepat > 120 AND pulang_cepat <= 180 THEN pulang_cepat END) as jumlahcepatkurangtigajam,
					count(CASE WHEN pulang_cepat > 180 THEN pulang_cepat END) as jumlahcepatkurangfull,
					count(CASE WHEN (jam_kerja = 'SK' AND (keterangan = 'IJIN SK DI HARI KERJA' OR excel)) OR (jam_kerja = 'CS' AND (keterangan = 'IJIN CS DI HARI KERJA' OR excel)) THEN jam_kerja END) as jumlahsakit,
					count(CASE WHEN (jam_kerja = 'CAP' AND (keterangan = 'IJIN CAP DI HARI KERJA' OR excel)) OR (jam_kerja = 'CM' AND (keterangan = 'IJIN CM DI HARI KERJA' OR excel)) OR (jam_kerja = 'CB' AND (keterangan = 'IJIN CB DI HARI KERJA' OR excel)) THEN jam_kerja END) as jumlahcuti,
					count(CASE WHEN jam_kerja = 'I' AND (keterangan = 'IJIN I DI HARI KERJA' OR excel) THEN jam_kerja END) as jumlahtidakhadirsah,
					count(CASE WHEN kode_masuk = 'M' THEN kode_masuk END) as jumlahtidakhadirtidaksah,
					count(CASE WHEN kode_masuk = 'H' OR (jam_kerja::text != '' AND jadwal_masuk::text != '' AND finger_masuk::text != '' AND finger_masuk < jadwal_pulang) THEN kode_masuk END) as jumlahhadirtotal,
					count(CASE WHEN jam_kerja in ('DL','DK') THEN jam_kerja END) as jumlahDinasLuar,
					count(CASE WHEN $cuti_tahunan_bos THEN jam_kerja END) as jumlahcutitahunan
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."'");

			$dataJumlahTelat		=	$queryJumlah->row();

			# start perwali desember 2018
			if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {

				$skorLambatKurangLima =	100 - ($dataJumlahTelat->jumlahlambatkuranglima * 0.25);

				$skor_detil[] = [
					'frek'	=> $dataJumlahTelat->jumlahlambatkuranglima,
					'skor' 	=> $skorLambatKurangLima
				];
			}
			# end perwali database 2018

			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorLambatKurangLimaBelas = 100 - ($dataJumlahTelat->jumlahlambatkuranglimabelas * 0.25);
			}
			# end perwali januari 2018
			else {
				$skorLambatKurangLimaBelas = 100 - ($dataJumlahTelat->jumlahlambatkuranglimabelas * 1);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahlambatkuranglimabelas,
				'skor' 	=> $skorLambatKurangLimaBelas
			];

			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorLambatKurangSatuJam = 100 - ($dataJumlahTelat->jumlahlambatkurangsatujam * 1);
			}
			# end perwali januari 2018
			else {
				$skorLambatKurangSatuJam = 100 - ($dataJumlahTelat->jumlahlambatkurangsatujam * 2);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] =  [
				'frek'	=> $dataJumlahTelat->jumlahlambatkurangsatujam,
				'skor' 	=> $skorLambatKurangSatuJam
			];

			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorLambatKurangDuaJam = 100 - ($dataJumlahTelat->jumlahlambatkurangduajam * 2);
			}
			# end perwali januari 2018
			else {
				$skorLambatKurangDuaJam	= 100 - ($dataJumlahTelat->jumlahlambatkurangduajam * 3);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahlambatkurangduajam,
				'skor' 	=> $skorLambatKurangDuaJam
			];

			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorLambatKurangTigaJam = 100 - ($dataJumlahTelat->jumlahlambatkurangtigajam * 3);
			}
			# end perwali januari 2018
			else {
				$skorLambatKurangTigaJam = 100 - ($dataJumlahTelat->jumlahlambatkurangtigajam * 4);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahlambatkurangtigajam,
				'skor' 	=> $skorLambatKurangTigaJam
			];


			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorLambatKurangFull =	100 - ($dataJumlahTelat->jumlahlambatkurangfull * 4);
			}
			# end perwali januari 2018
			else {
				$skorLambatKurangFull =	100 - ($dataJumlahTelat->jumlahlambatkurangfull * 5);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahlambatkurangfull,
				'skor' 	=> $skorLambatKurangFull
			];

			//// pulang cepat

			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorCepatKurangLimaBelas =	100 - ($dataJumlahTelat->jumlahcepatkuranglimabelas * 0.25);
			}
			# end perwali januari 2018
			else {
				$skorCepatKurangLimaBelas =	100 - ($dataJumlahTelat->jumlahcepatkuranglimabelas * 1);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahcepatkuranglimabelas,
				'skor' 	=> $skorCepatKurangLimaBelas
			];


			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorCepatKurangSatuJam	= 100 - ($dataJumlahTelat->jumlahcepatkurangsatujam * 1);
			}
			# end perwali januari 2018
			else {
				$skorCepatKurangSatuJam	= 100 - ($dataJumlahTelat->jumlahcepatkurangsatujam * 2);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahcepatkurangsatujam,
				'skor' 	=> $skorCepatKurangSatuJam
			];

			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorCepatKurangDuaJam = 100 - ($dataJumlahTelat->jumlahcepatkurangduajam * 2);
			}
			# end perwali januari 2018
			else {
				$skorCepatKurangDuaJam = 100 - ($dataJumlahTelat->jumlahcepatkurangduajam * 3);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahcepatkurangduajam,
				'skor' 	=> $skorCepatKurangDuaJam
			];

			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorCepatKurangTigaJam	= 100 - ($dataJumlahTelat->jumlahcepatkurangtigajam * 3);
			}
			# end perwali januari 2018
			else {
				$skorCepatKurangTigaJam	= 100 - ($dataJumlahTelat->jumlahcepatkurangtigajam * 4);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahcepatkurangtigajam,
				'skor' 	=> $skorCepatKurangTigaJam
			];

			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorCepatKurangFull = 100 - ($dataJumlahTelat->jumlahcepatkurangfull * 4);
			}
			# end perwali januari 2018
			else {
				$skorCepatKurangFull = 100 - ($dataJumlahTelat->jumlahcepatkurangfull * 5);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahcepatkurangfull,
				'skor' 	=> $skorCepatKurangFull
			];

			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorJumlahSakit = 100 - ($dataJumlahTelat->jumlahsakit * 1);
			}
			# end perwali januari 2018
			else {
				$skorJumlahSakit = 100 - ($dataJumlahTelat->jumlahsakit * 2);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahsakit,
				'skor' 	=> $skorJumlahSakit
			];
			//////////////////////////

			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorJumlahCutiBesar = 100 - ($dataJumlahTelat->jumlahcuti * 3);
			}
			# end perwali januari 2018
			else {
				$skorJumlahCutiBesar = 100 - ($dataJumlahTelat->jumlahcuti * 4);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahcuti,
				'skor' 	=> $skorJumlahCutiBesar
			];
			////////////////////////

			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorJumlahTidakHadirSah = 100 - ($dataJumlahTelat->jumlahtidakhadirsah * 5);
			}
			# end perwali januari 2018
			else {
				$skorJumlahTidakHadirSah = 100 - ($dataJumlahTelat->jumlahtidakhadirsah * 5);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahtidakhadirsah,
				'skor' 	=> $skorJumlahTidakHadirSah
			];
			////////////////////////

			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorJumlahTidakHadirTidakSah	=	100 - ($dataJumlahTelat->jumlahtidakhadirtidaksah * 6);
			}
			# end perwali januari 2018
			else {
				$skorJumlahTidakHadirTidakSah	=	100 - ($dataJumlahTelat->jumlahtidakhadirtidaksah * 6);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahtidakhadirtidaksah,
				'skor' 	=> $skorJumlahTidakHadirTidakSah
			];
			/////////////////////////

			$skorTotal =
						$skorLambatKurangLimaBelas +
						$skorLambatKurangSatuJam +
						$skorLambatKurangDuaJam  +
						$skorLambatKurangTigaJam +
						$skorLambatKurangFull +

						$skorCepatKurangLimaBelas +
						$skorCepatKurangSatuJam +
						$skorCepatKurangDuaJam +
						$skorCepatKurangTigaJam +
						$skorCepatKurangFull +

						$skorJumlahSakit +
						$skorJumlahCutiBesar +
						$skorJumlahTidakHadirSah +
						$skorJumlahTidakHadirTidakSah ;

			# start perwali desember 2018
			if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
				$skorTotal = $skorTotal + $skorLambatKurangLima;
			}
			# end perwali desember 2018

			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorJumlahCutiTahunan = 100 - ($dataJumlahTelat->jumlahcutitahunan * 0);
			}
			# end perwali januari 2018
			///////

			$v_skor_total = 1400;
			# start perwali desember 2018
			if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
				$v_skor_total = 1500;
			}

			$skorTPP = 100 - ($v_skor_total - $skorTotal);

			# start tambah (if seda otomatis 100)
			if($skorTPP < 0) {
				$skorTPP = 0;
			}
			# end tambah

			# start perwali desember 2018
			$seda = "";
			$seda_input = NULL;
			if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
				if($dataPegawai->meninggal == 't'){
					$skorTPP = 100;
					$seda = " (Meninggal Dunia)";
					$seda_input = TRUE;
				}
			}
			# end perwali desember 2018

			$urutan = $i;
			/** INSERT LAP SKOR KEHADIRAN DETIL */
			$data = [
				'nip'					=> $dataPegawai->nip,
				'nama'				=> $dataPegawai->nama,
				'golongan'		=> $dataPegawai->nama_golongan,
				'jabatan'			=> $unor,
				'skor'				=> json_encode($skor_detil),
				'jml_hadir'		=> $dataJumlahTelat->jumlahhadirtotal,
				'jml_dl'			=> $dataJumlahTelat->jumlahdinasluar,
				'jml_cuti'		=> $dataJumlahTelat->jumlahcutitahunan,
				'bulan'				=> $bulan_get,
				'tahun'				=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'					=> $pns_get,
				'meninggal' 	=> $seda_input,
				'urut'				=> $urutan
			];

			//insert lap_skor_detil
			$this->Lap_skor_kehadiran_detil_model->insert($data);

			//isi data report
			$temp[] = $i;
			$temp['no'] = $i;
			$temp['nama'] = $dataPegawai->nama;
			$temp['nip'] = $dataPegawai->nip;
			$temp['nama_golongan'] = $dataPegawai->nama_golongan;
			$temp['unor'] = $unor;
						
			//terlambat datang
			# start perwali desember 2018
			if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
				$temp['jumlahlambatkuranglima'] = $dataJumlahTelat->jumlahlambatkuranglima;
				$temp['skorLambatKurangLima'] = $skorLambatKurangLima;
			}
			# end perwali desember 2018
			$temp['jumlahlambatkuranglimabelas'] = $dataJumlahTelat->jumlahlambatkuranglimabelas;
			$temp['skorLambatKurangLimaBelas'] = $skorLambatKurangLimaBelas;
			$temp['jumlahlambatkurangsatujam'] = $dataJumlahTelat->jumlahlambatkurangsatujam;
			$temp['skorLambatKurangSatuJam'] = $skorLambatKurangSatuJam;
			$temp['jumlahlambatkurangduajam'] = $dataJumlahTelat->jumlahlambatkurangduajam;
			$temp['skorLambatKurangDuaJam'] = $skorLambatKurangDuaJam;
			$temp['jumlahlambatkurangtigajam'] = $dataJumlahTelat->jumlahlambatkurangtigajam;
			$temp['skorLambatKurangTigaJam'] = $skorLambatKurangTigaJam;
			$temp['jumlahlambatkurangfull'] = $dataJumlahTelat->jumlahlambatkurangfull;
			$temp['skorLambatKurangFull'] = $skorLambatKurangFull;
			//pulang cepat
			$temp['jumlahcepatkuranglimabelas'] = $dataJumlahTelat->jumlahcepatkuranglimabelas;
			$temp['skorCepatKurangLimaBelas'] = $skorCepatKurangLimaBelas;
			$temp['jumlahcepatkurangsatujam'] = $dataJumlahTelat->jumlahcepatkurangsatujam;
			$temp['skorCepatKurangSatuJam'] = $skorCepatKurangSatuJam;
			$temp['jumlahcepatkurangduajam'] = $dataJumlahTelat->jumlahcepatkurangduajam;
			$temp['skorCepatKurangDuaJam'] = $skorCepatKurangDuaJam;
			$temp['jumlahcepatkurangtigajam'] = $dataJumlahTelat->jumlahcepatkurangtigajam;
			$temp['skorCepatKurangTigaJam'] = $skorCepatKurangTigaJam;
			$temp['jumlahcepatkurangfull'] = $dataJumlahTelat->jumlahcepatkurangfull;
			$temp['skorCepatKurangFull'] = $skorCepatKurangFull;
			//sakit
			$temp['jumlahsakit'] = $dataJumlahTelat->jumlahsakit;
			$temp['skorJumlahSakit'] = $skorJumlahSakit;
			//cuti
			$temp['jumlahcuti'] = $dataJumlahTelat->jumlahcuti;
			$temp['skorJumlahCutiBesar'] = $skorJumlahCutiBesar;
			//tidak hadir sah (menggunakan surat).
			$temp['jumlahtidakhadirsah'] = $dataJumlahTelat->jumlahtidakhadirsah;
			$temp['skorJumlahTidakHadirSah'] = $skorJumlahTidakHadirSah;
			//tidak hadir tidak sah (mangkir).
			$temp['jumlahtidakhadirtidaksah'] = $dataJumlahTelat->jumlahtidakhadirtidaksah;
			$temp['skorJumlahTidakHadirTidakSah'] = $skorJumlahTidakHadirTidakSah;
			//skor total
			$temp['skorTotal'] = number_format($skorTotal);
			//jumlah masuk
			$temp['jumlahMasuk'] = $jumlahMasuk;
			//jumlah hadir total
			$temp['jumlahhadirtotal'] = $dataJumlahTelat->jumlahhadirtotal;
			//jumlah dinas luar
			$temp['jumlahdinasluar'] = $dataJumlahTelat->jumlahdinasluar;
			//jumlah cuti tahunan
			$temp['jumlahcutitahunan'] = $dataJumlahTelat->jumlahcutitahunan;
			//skor tpp
			$temp['skorTPP'] = $skorTPP;
			//var_dump($temp);exit;
			$datanya[] = $temp;
			#end
			$i++;
		}

		/** UPDATE FINISHED_AT JADI NOT NULL */
		$where = [
			'bulan'				=> $bulan_get,
			'tahun'				=> $tahun_get,
			'id_instansi'	=> $id_instansi_get,
			'pns'					=> $pns_get,
			'deleted_at'	=> null,
		];

		$this->Lap_skor_kehadiran_model->update($where, ['finished_at' => date('Y-m-d H:i:s')]);
		#end

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

		$this->bulan 	=	$namaBulan[$this->input->get('bulan')];

		$data_arr = array(
			'datanya' => $datanya,
			'tgl_mulai' => date('d-m-Y', strtotime($tglMulai)),
			'tgl_hingga' => date('d-m-Y', strtotime($tglSelesai)),
			'instansi' => $this->dataInstansi,
			//'hari' => $hari,
			'bulan' => $this->bulan,
			'tahun' => $tahun_get
		);

		$nama_dokumen = "Laporan_Skor_Kehadiran_".str_replace(" ","_",$data_arr['instansi']->nama)."_Tanggal_".str_replace("-","_",$data_arr['tgl_mulai'])."_s/d_".str_replace("-","_",$data_arr['tgl_hingga']);
		$current_date = date('d/m/Y H:i:s');

		if($this->input->get("type") == 'pdf') {
			ini_set('memory_limit', '-1');
			//$html_header = $this->load->view('cetak/skor_new/header', $data_arr, true); //render the view into HTML
			$html_body = $this->load->view('cetak/skor/body', $data_arr, true); //render the view into HTML

			$this->load->library('pdf');
			$pdf=$this->pdf->load("en-GB-x","FOLIO-L","","",10,10,5,10,6,3,"L");
			$pdf->SetWatermarkImage(base_url('assets/img/logo_pemkot_watermark.png'), 0.7, '',array(90,38));
			$pdf->showWatermarkImage = true;
			//$pdf->SetHTMLHeader($html_header);
			$pdf->SetFooter(''.'Halaman {PAGENO} dari {nb}||'.$current_date.''); //Add a footer for good measure
			$pdf->WriteHTML($html_body); //write the HTML into PDF
			$pdf->Output($nama_dokumen.".pdf" ,'I');
		}
		else if($this->input->get("type") =='xls') {
			// Fungsi header dengan mengirimkan raw data excel
			header("Cache-Control: no-cache, no-store, must-revalidate");
			header("Content-Type: application/vnd.ms-excel");
			// Mendefinisikan nama file ekspor "hasil-export.xls"
			header("Content-Disposition: attachment; filename=".$nama_dokumen.".xls");

			$this->load->view('cetak/skor/excel', $data_arr);

			ob_end_clean();
		}
		else {
			$this->load->view('cetak/skor/body', $data_arr);
		}
	}

	public function printed($hari_kerja, $bulan, $tahun, $id_instansi, $pns) {
		$detil_laporan	=	$this->db->query("
			select * from lap_skor_kehadiran_detil
			where bulan = '$bulan'
			and tahun = '$tahun'
			and id_instansi = '$id_instansi'
			and pns = '$pns'
			and deleted_at is null
			order by urut
		")->result();

		$tabel_arr = array();

		foreach ($detil_laporan as $key => $value) {
			$temp = array();
			$temp[] = $key+1;
			$temp['nama'] = $value->nama;
			$temp['nip'] = $value->nip;
			$temp['nama_golongan'] = $value->golongan;
			$temp['unor'] = $value->jabatan;

			$skors = json_decode($value->skor);
		/* 	echo '<pre>';
			print_r($skors);
			echo "</pre>";exit; */
			//terlambat datang
			# start perwali desember 2018
			if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
				$temp['jumlahlambatkuranglima'] = $skors[0]->frek;
				$temp['skorLambatKurangLima'] = $skors[0]->skor;
				$temp['jumlahlambatkuranglimabelas'] = $skors[1]->frek;
				$temp['skorLambatKurangLimaBelas'] = $skors[1]->skor;
				$temp['jumlahlambatkurangsatujam'] = $skors[2]->frek;
				$temp['skorLambatKurangSatuJam'] = $skors[2]->skor;
				$temp['jumlahlambatkurangduajam'] = $skors[3]->frek;
				$temp['skorLambatKurangDuaJam'] = $skors[3]->skor;
				$temp['jumlahlambatkurangtigajam'] = $skors[4]->frek;
				$temp['skorLambatKurangTigaJam'] = $skors[4]->skor;
				$temp['jumlahlambatkurangfull'] = $skors[5]->frek;
				$temp['skorLambatKurangFull'] = $skors[5]->skor;;
				//pulang cepat
				$temp['jumlahcepatkuranglimabelas'] = $skors[6]->frek;
				$temp['skorCepatKurangLimaBelas'] = $skors[6]->skor;
				$temp['jumlahcepatkurangsatujam'] = $skors[7]->frek;
				$temp['skorCepatKurangSatuJam'] = $skors[7]->skor;
				$temp['jumlahcepatkurangduajam'] = $skors[8]->frek;
				$temp['skorCepatKurangDuaJam'] = $skors[8]->skor;
				$temp['jumlahcepatkurangtigajam'] = $skors[9]->frek;
				$temp['skorCepatKurangTigaJam'] = $skors[9]->skor;
				$temp['jumlahcepatkurangfull'] = $skors[10]->frek;
				$temp['skorCepatKurangFull'] = $skors[10]->skor;
				//sakit
				$temp['jumlahsakit'] = $skors[11]->frek;
				$temp['skorJumlahSakit'] = $skors[11]->skor;
				//cuti
				$temp['jumlahcuti'] = $skors[12]->frek;
				$temp['skorJumlahCutiBesar'] = $skors[12]->skor;
				//tidak hadir sah (menggunakan surat).
				$temp['jumlahtidakhadirsah'] = $skors[13]->frek;
				$temp['skorJumlahTidakHadirSah'] = $skors[13]->skor;
				//tidak hadir tidak sah (mangkir).
				$temp['jumlahtidakhadirtidaksah'] = $skors[14]->frek;
				$temp['skorJumlahTidakHadirTidakSah'] = $skors[14]->skor;
				# end perwali desember 2018
			}else{
				$temp['jumlahlambatkuranglimabelas'] = $skors[0]->frek;
				$temp['skorLambatKurangLimaBelas'] = $skors[0]->skor;
				$temp['jumlahlambatkurangsatujam'] = $skors[1]->frek;
				$temp['skorLambatKurangSatuJam'] = $skors[1]->skor;
				$temp['jumlahlambatkurangduajam'] = $skors[2]->frek;
				$temp['skorLambatKurangDuaJam'] = $skors[2]->skor;
				$temp['jumlahlambatkurangtigajam'] = $skors[3]->frek;
				$temp['skorLambatKurangTigaJam'] = $skors[3]->skor;
				$temp['jumlahlambatkurangfull'] = $skors[4]->frek;
				$temp['skorLambatKurangFull'] = $skors[4]->skor;;
				//pulang cepat
				$temp['jumlahcepatkuranglimabelas'] = $skors[5]->frek;
				$temp['skorCepatKurangLimaBelas'] = $skors[5]->skor;
				$temp['jumlahcepatkurangsatujam'] = $skors[6]->frek;
				$temp['skorCepatKurangSatuJam'] = $skors[6]->skor;
				$temp['jumlahcepatkurangduajam'] = $skors[7]->frek;
				$temp['skorCepatKurangDuaJam'] = $skors[7]->skor;
				$temp['jumlahcepatkurangtigajam'] = $skors[8]->frek;
				$temp['skorCepatKurangTigaJam'] = $skors[8]->skor;
				$temp['jumlahcepatkurangfull'] = $skors[9]->frek;
				$temp['skorCepatKurangFull'] = $skors[9]->skor;
				//sakit
				$temp['jumlahsakit'] = $skors[10]->frek;
				$temp['skorJumlahSakit'] = $skors[10]->skor;
				//cuti
				$temp['jumlahcuti'] = $skors[11]->frek;
				$temp['skorJumlahCutiBesar'] = $skors[11]->skor;
				//tidak hadir sah (menggunakan surat).
				$temp['jumlahtidakhadirsah'] = $skors[12]->frek;
				$temp['skorJumlahTidakHadirSah'] = $skors[12]->skor;
				//tidak hadir tidak sah (mangkir).
				$temp['jumlahtidakhadirtidaksah'] = $skors[13]->frek;
				$temp['skorJumlahTidakHadirTidakSah'] = $skors[13]->skor;
			}
			
			$totalSkor = 0;
			foreach ($skors as $skor) {
				$totalSkor += $skor->skor;
			}

			$temp['skorTotal'] = number_format($totalSkor);
			$temp['jumlahMasuk'] = $hari_kerja;
			$temp['jumlahhadirtotal'] = $value->jml_hadir;
			$temp['jumlahdinasluar'] = $value->jml_dl;
			$temp['jumlahcutitahunan'] = $value->jml_cuti;

			$v_skor_total = 1400;
			# start perwali desember 2018
			if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
				$v_skor_total = 1500;
			}

			$skorTPP = 100 - ($v_skor_total - $totalSkor);

			# start tambah (if seda otomatis 100)
			if($skorTPP < 0) {
				$skorTPP = 0;
			}
			# end tambah

			# start perwali desember 2018
			if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
				if($value->meninggal == 't'){
					$skorTPP = 100;
				}
			}
			# end perwali desember 2018

			$temp['skorTPP'] = $skorTPP;

			$tabel_arr[] = $temp;
		}

		// echo $this->dataTable;

		$data_arr = array(
			'datanya' => $tabel_arr,
			'tgl_mulai' => date('d-m-Y', strtotime($this->tgl_pertama)),
			'tgl_hingga' => date('d-m-Y', strtotime($this->tgl_terakhir)),
			'instansi' => $this->dataInstansi
		);

		$nama_dokumen = "Laporan_Skor_".str_replace(" ","_",$data_arr['instansi']->nama)."_Tanggal_".str_replace("-","_",$data_arr['tgl_mulai'])."_s/d_".str_replace("-","_",$data_arr['tgl_hingga']);
		$current_date = date('d/m/Y H:i:s');

		if($this->input->get("type") == 'pdf') {
			ini_set('memory_limit', '-1');
			//$html_header = $this->load->view('cetak/skor_new/header', $data_arr, true); //render the view into HTML
			$html_body = $this->load->view('cetak/skor/body', $data_arr, true); //render the view into HTML

			$this->load->library('pdf');
			$pdf=$this->pdf->load("en-GB-x","FOLIO-L","","",10,10,5,10,6,3,"L");
			$pdf->SetWatermarkImage(base_url('assets/img/logo_pemkot_watermark.png'), 0.7, '',array(90,38));
			$pdf->showWatermarkImage = true;
			//$pdf->SetHTMLHeader($html_header);
			$pdf->SetFooter(''.'Halaman {PAGENO} dari {nb}||'.$current_date.''); //Add a footer for good measure
			$pdf->WriteHTML($html_body); //write the HTML into PDF
			$pdf->Output($nama_dokumen.".pdf" ,'I');
		}
		else if($this->input->get("type") =='xls') {
			// Fungsi header dengan mengirimkan raw data excel
			header("Cache-Control: no-cache, no-store, must-revalidate");
			header("Content-Type: application/vnd.ms-excel");
			// Mendefinisikan nama file ekspor "hasil-export.xls"
			header("Content-Disposition: attachment; filename=".$nama_dokumen.".xls");

			$this->load->view('cetak/skor/excel', $data_arr);

			ob_end_clean();
		}
		else {
			$this->load->view('cetak/skor/body', $data_arr);
		}
	}

	public function printed_lama($hari_kerja, $bulan, $tahun, $id_instansi, $pns) {
		$detil_laporan	=	$this->db->query("
			select * from lap_skor_kehadiran_detil
			where bulan = '$bulan'
			and tahun = '$tahun'
			and id_instansi = '$id_instansi'
			and pns = '$pns'
			and deleted_at is null
			order by golongan desc
		")->result();

		foreach ($detil_laporan as $key => $value) {
			$this->dataTable .= "<tr class='margin'>";

			$this->dataTable .= "<td>".($key+1)."</td>";
			$this->dataTable .= "<td style='text-align: left; padding-left: 25px;'>".$value->nama."</td>";
			$this->dataTable .= "<td>".$value->nip."</td>";
			$this->dataTable .= "<td style='text-align: left; padding-left: 25px;'>".$value->golongan."</td>";
			$this->dataTable .= "<td style='text-align: left; padding-left: 25px;'>".$value->jabatan."</td>";

			$skors = json_decode($value->skor);

			$totalSkor = 0;

			foreach ($skors as $skor) {
				$this->dataTable .= "<td>".$skor->frek."</td>";
				$this->dataTable .= "<td>".$skor->skor."</td>";

				$totalSkor += $skor->skor;
			}

			$this->dataTable .= "<td>".number_format($totalSkor)."</td>";
			$this->dataTable .= "<td>".$hari_kerja."</td>";
			$this->dataTable .= "<td>".$value->jml_hadir."</td>";
			$this->dataTable .= "<td>".$value->jml_dl."</td>";
			$this->dataTable .= "<td>".$value->jml_cuti."</td>";

			$v_skor_total = 1400;
			# start perwali desember 2018
			if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
				$v_skor_total = 1500;
			}

			$skorTPP = 100 - ($v_skor_total - $totalSkor);

			# start tambah (if seda otomatis 100)
			if($skorTPP < 0) {
				$skorTPP = 0;
			}
			# end tambah

			# start perwali desember 2018
			if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
				if($value->meninggal == 't'){
					$skorTPP = 100;
				}
			}
			# end perwali desember 2018

			$this->dataTable .= "<td>".$skorTPP."</td>";

			$this->dataTable .= "</tr>";
		}

		// echo $this->dataTable;

		if($this->session->userdata('username') <> 'damkar') {
			$this->load->library('dompdf_gen');

			$this->load->view('cetak/lap_skor_view',[
				'dataTable' => $this->dataTable
			]);

			$paper_size  = 'folio'; //paper size
			$orientation = 'landscape'; //tipe format kertas
			$html = $this->output->get_output();
			$this->dompdf->set_paper($paper_size, $orientation);
			//Convert to PDF
			$this->dompdf->load_html($html);
			$this->dompdf->render();
			$this->dompdf->stream("Laporan Skor.pdf", array('Attachment'=>0));
		}
		else {
			$this->load->view('cetak/lap_skor_view',[
				'dataTable' => $this->dataTable
			]);
		}
	}

	public function generate() {
		/** CEK APAKAH PERNAH PRINT LAPORAN */
		$bulan_get = $this->input->get('bulan') ? $this->input->get('bulan') : 0;
		$tahun_get = $this->input->get('tahun') ? $this->input->get('tahun') : '';
		$id_instansi_get = $this->input->get('id_instansi') ? $this->input->get('id_instansi') : '';
		$pns_get = $this->input->get('pns') ? $this->input->get('pns') : '';

		$queryCekSudahPrintLaporan	=	$this->db->query("
			select * from lap_skor_kehadiran
			where bulan = '$bulan_get'
			and tahun = '$tahun_get'
			and id_instansi = '$id_instansi_get'
			and pns = '$pns_get'
			and deleted_at is null
		");

		if(! $queryCekSudahPrintLaporan->row()) {
			redirect('lap_skor','refresh');
		} else {
			/** CEK APAKAH ADA LAPORAN SUDAH DIKUNCI */
			$whereTahunBulan = $tahun_get . '-' . $bulan_get;

			$laporanTerkunci	= $this->db->query("
				select * from log_laporan
				where to_char(tgl_log, 'YYYY-MM') = '$whereTahunBulan'
				and kd_instansi = '$id_instansi_get'
				and is_kunci = 'Y'
			")->row_array();

			if($laporanTerkunci) {
				$this->session->set_flashdata('feedback_failed', 'Maaf, Laporan telah terkunci.');

				redirect('lap_skor', 'refresh');
			}
			#END

			/** UPDATE IS_DELETE JADI NULL */
			$this->load->model([
				'Lap_skor_kehadiran_model',
				'Lap_skor_kehadiran_detil_model'
			]);

			$where = [
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'			=> $pns_get,
				'deleted_at'	=> null,
			];

			$this->Lap_skor_kehadiran_model->update($where, ['deleted_at' => date('Y-m-d H:i:s')]);

			$this->Lap_skor_kehadiran_detil_model->update($where, ['deleted_at' => date('Y-m-d H:i:s')]);
			#end


			/** INSERT LAPORAN BARU */
			$data = [
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'			=> $pns_get,
			];
			$this->Lap_skor_kehadiran_model->insert($data);

			$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
			// Tanggal pertama pada bulan ini
			$tglMulai 	= date('Y-m-01', strtotime($hari_ini));
			// Tanggal terakhir pada bulan ini
			$tglSelesai 	= date('Y-m-t', strtotime($hari_ini));


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


			$sabtuMinggu2 	= "";

			$iMinggu=0;
			$iMinggu2=1;
			$iSabtu=0;
			$iSeninJumat=0;
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


					$start_date = date ("Y-m-d", $i);
					$queryLibur 	=	$this->db->query("
					select
						s_hari_libur.id,
						m_hari_libur.id as id_hari_libur,
						m_hari_libur.nama
					from
						s_hari_libur ,m_hari_libur
					where
						s_hari_libur.tanggal = '".$start_date."'  and
						s_hari_libur.id_libur = m_hari_libur.id
					");
					$this->dataLibur	=	$queryLibur->row();
					if($this->dataLibur){
						if($iMinggu2 == 1){
							$sabtuMinggu2 .=  "'".date('Y-m-d',$i)."'";
						}
						else{
							$sabtuMinggu2 .=  ",'".date('Y-m-d',$i)."'";
						}

					$iMinggu2++;
					}

				} else {
					//$sabtuminggu2[] 	= date('Y-m-d',strtotime($i));
					//echo $iMinggu2;

					$sabtuminggu[] 	= $i;


					if($iMinggu2 == 1){
						 $sabtuMinggu2 .=  "'".date('Y-m-d',$i)."'";
					}
					else{
						$sabtuMinggu2 .=  ",'".date('Y-m-d',$i)."'";
					}

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
					$iMinggu2++;
				}
			}

			//	var_dump($sabtuMinggu2);

			$jumlahSabtuMinggu = $iSabtu + $iMinggu;

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

			$jumlahLibur		=	$jumlahSabtuMinggu + $dataJumlahHariLibur->jumlah;


			if($this->input->get("pns") == 'y'){
				$wherePns 	= " and m.kode_status_pegawai='1'";
			}
			else{

				$wherePns 	= " and m.kode_status_pegawai!='1'";
			}

			$query_kode_sik = $this->db->query("select kode_sik, nama from m_instansi where kode = '".$this->input->get('id_instansi')."'");
			$data_kode_sik = $query_kode_sik->row();


			if (substr($data_kode_sik->nama, 0, 9) != 'Kecamatan') {
			$kode_instansi_all = $this->input->get('id_instansi');
			$whereQuery = "pukh.kode_instansi = '".$kode_instansi_all."'".$wherePns;

			}else{
				$kode_instansi_all = substr($this->input->get('id_instansi'), 0, 5);
				$whereQuery = "pukh.kode_instansi LIKE '".$kode_instansi_all.'%'."'".$wherePns;
			}


			# start nambah status meninggal
			$queryPegawai 	=	$this->db->query("
				select
					m.id as id_pegawai,m.nama, m.nip, m.meninggal, m.tgl_meninggal,
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
					".$whereQuery."
				order by
					peh.kode_eselon,
					pgh.kode_golongan desc,
					m.nip

			");
			# end nambah status meninggal
			$this->dataPegawai	=	$queryPegawai->result();
			/*highlight_string("<?php\n\$data =\n" . var_export($this->dataPegawai, true) . ";\n?>");exit;*/
			//echo $this->db->last_query();
			$dataTable = "";

			$i=1;
			foreach($this->dataPegawai as $dataPegawai){
				$skor_detil = [];

				//echo $jumlahLibur."<br>";
				$dataTable .= "<tr>";
				$dataTable .= "<td>".$i."</td>";
				$dataTable .= "<td>".$dataPegawai->nama."</td>";
				$dataTable .= "<td>".$dataPegawai->nip."</td>";
				$dataTable .= "<td valign='center'>".$dataPegawai->nama_golongan."</td>";

				if($dataPegawai->nama_jabatan =='Staf'){
					$unor = $dataPegawai->nama_jabatan." - ".$dataPegawai->nama_rumpun_jabatan;
				}
				else{
					$unor = $dataPegawai->nama_jabatan;
				}

				$dataTable .= "<td>".$unor." </td>";

				$awallimabelas = 0;
				$kurang_lima_bos = '';
				# start perwali desember 2018
				if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
					$awallimabelas = 5;
					$kurang_lima_bos = 'count(CASE WHEN datang_telat > 0 AND datang_telat <= 5 THEN datang_telat END) as jumlahlambatkuranglima,';
				}
				# end perwali database 2018

				$cuti_tahunan_bos = "'CT'";
				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$cuti_tahunan_bos = "'CT','TB'";
				}
				# end perwali januari 2018

				$queryJumlah	=	$this->db->query("
					select
						$kurang_lima_bos
						count(CASE WHEN datang_telat > $awallimabelas AND datang_telat <= 15 THEN datang_telat END) as jumlahlambatkuranglimabelas,
						count(CASE WHEN datang_telat > 15 AND datang_telat <= 60 THEN datang_telat END) as jumlahlambatkurangsatujam,
						count(CASE WHEN datang_telat > 60 AND datang_telat <= 120 THEN datang_telat END) as jumlahlambatkurangduajam,
						count(CASE WHEN datang_telat > 120 AND datang_telat <= 180 THEN datang_telat END) as jumlahlambatkurangtigajam,
						count(CASE WHEN datang_telat > 180 THEN datang_telat END) as jumlahlambatkurangfull,
						count(CASE WHEN pulang_cepat > 5 AND pulang_cepat <= 15 THEN pulang_cepat END) as jumlahcepatkuranglimabelas,
						count(CASE WHEN pulang_cepat > 15 AND pulang_cepat <= 60 THEN pulang_cepat END) as jumlahcepatkurangsatujam,
						count(CASE WHEN pulang_cepat > 60 AND pulang_cepat <= 120 THEN pulang_cepat END) as jumlahcepatkurangduajam,
						count(CASE WHEN pulang_cepat > 120 AND pulang_cepat <= 180 THEN pulang_cepat END) as jumlahcepatkurangtigajam,
						count(CASE WHEN pulang_cepat > 180 THEN pulang_cepat END) as jumlahcepatkurangfull,
						count(CASE WHEN jam_kerja in ('SK','CS') AND jadwal_masuk::text <> '' THEN jam_kerja END) as jumlahsakit,
						count(CASE WHEN jam_kerja in ('CAP','CM','CH') AND jadwal_masuk::text <> '' THEN jam_kerja END) as jumlahcuti,
						count(CASE WHEN jam_kerja in ('I') AND jadwal_masuk::text <> '' THEN jam_kerja END) as jumlahtidakhadirsah,
						count(CASE WHEN kode_masuk in ('M') AND jadwal_masuk::text <> '' THEN kode_masuk END) as jumlahtidakhadirtidaksah,
						count(CASE WHEN kode_masuk = 'H' OR (jam_kerja::text != '' AND jadwal_masuk::text != '' AND finger_masuk::text != '' AND finger_masuk < jadwal_pulang) THEN kode_masuk END) as jumlahhadirtotal,
						count(CASE WHEN jam_kerja in ('DL','DK') AND jadwal_masuk::text <> '' THEN jam_kerja END) as jumlahdinasluar,
						count(CASE WHEN jam_kerja in ($cuti_tahunan_bos) AND jadwal_masuk::text <> '' THEN jam_kerja END) as jumlahcutitahunan
					from
						data_mentah
					where
						tanggal >= '".$tglMulai."'
						AND tanggal <=  '".$tglSelesai."' and
						id_pegawai = '".$dataPegawai->id_pegawai."'");

				$dataJumlahTelat		=	$queryJumlah->row();

				# start perwali desember 2018
				if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {

					$skorLambatKurangLima =	100 - ($dataJumlahTelat->jumlahlambatkuranglima * 0.25);

					$skor_detil[] = [
						'frek'	=> $dataJumlahTelat->jumlahlambatkuranglima,
						'skor' 	=> $skorLambatKurangLima
					];
				}
				# end perwali database 2018

				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorLambatKurangLimaBelas = 100 - ($dataJumlahTelat->jumlahlambatkuranglimabelas * 0.25);
				}
				# end perwali januari 2018
				else {
					$skorLambatKurangLimaBelas = 100 - ($dataJumlahTelat->jumlahlambatkuranglimabelas * 1);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahTelat->jumlahlambatkuranglimabelas,
					'skor' 	=> $skorLambatKurangLimaBelas
				];


				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorLambatKurangSatuJam = 100 - ($dataJumlahTelat->jumlahlambatkurangsatujam * 1);
				}
				# end perwali januari 2018
				else {
					$skorLambatKurangSatuJam = 100 - ($dataJumlahTelat->jumlahlambatkurangsatujam * 2);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] =  [
					'frek'	=> $dataJumlahTelat->jumlahlambatkurangsatujam,
					'skor' 	=> $skorLambatKurangSatuJam
				];


				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorLambatKurangDuaJam = 100 - ($dataJumlahTelat->jumlahlambatkurangduajam * 2);
				}
				# end perwali januari 2018
				else {
					$skorLambatKurangDuaJam	= 100 - ($dataJumlahTelat->jumlahlambatkurangduajam * 3);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahTelat->jumlahlambatkurangduajam,
					'skor' 	=> $skorLambatKurangDuaJam
				];


				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorLambatKurangTigaJam = 100 - ($dataJumlahTelat->jumlahlambatkurangtigajam * 3);
				}
				# end perwali januari 2018
				else {
					$skorLambatKurangTigaJam = 100 - ($dataJumlahTelat->jumlahlambatkurangtigajam * 4);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahTelat->jumlahlambatkurangtigajam,
					'skor' 	=> $skorLambatKurangTigaJam
				];


				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorLambatKurangFull =	100 - ($dataJumlahTelat->jumlahlambatkurangfull * 4);
				}
				# end perwali januari 2018
				else {
					$skorLambatKurangFull =	100 - ($dataJumlahTelat->jumlahlambatkurangfull * 5);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahTelat->jumlahlambatkurangfull,
					'skor' 	=> $skorLambatKurangFull
				];

				# start perwali desember 2018
				if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
					$this->dataTable .= "<td>".$dataJumlahTelat->jumlahlambatkuranglima."</td>";
					$this->dataTable .= "<td>".$skorLambatKurangLima."</td>";
				}
				# end perwali desember 2018
				$this->dataTable .= "<td>".$dataJumlahTelat->jumlahlambatkuranglimabelas."</td>";
				$this->dataTable .= "<td>".$skorLambatKurangLimaBelas."</td>";
				$this->dataTable .= "<td>".$dataJumlahTelat->jumlahlambatkurangsatujam."</td>";
				$this->dataTable .= "<td>".$skorLambatKurangSatuJam."</td>";
				$this->dataTable .= "<td>".$dataJumlahTelat->jumlahlambatkurangduajam."</td>";
				$this->dataTable .= "<td>".$skorLambatKurangDuaJam."</td>";
				$this->dataTable .= "<td>".$dataJumlahTelat->jumlahlambatkurangtigajam."</td>";
				$this->dataTable .= "<td>".$skorLambatKurangTigaJam."</td>";
				$this->dataTable .= "<td>".$dataJumlahTelat->jumlahlambatkurangfull."</td>";
				$this->dataTable .= "<td>".$skorLambatKurangFull."</td>";

				//// pulang cepat

				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorCepatKurangLimaBelas =	100 - ($dataJumlahTelat->jumlahcepatkuranglimabelas * 0.25);
				}
				# end perwali januari 2018
				else {
					$skorCepatKurangLimaBelas =	100 - ($dataJumlahTelat->jumlahcepatkuranglimabelas * 1);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahTelat->jumlahcepatkuranglimabelas,
					'skor' 	=> $skorCepatKurangLimaBelas
				];


				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorCepatKurangSatuJam	= 100 - ($dataJumlahTelat->jumlahcepatkurangsatujam * 1);
				}
				# end perwali januari 2018
				else {
					$skorCepatKurangSatuJam	= 100 - ($dataJumlahTelat->jumlahcepatkurangsatujam * 2);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahTelat->jumlahcepatkurangsatujam,
					'skor' 	=> $skorCepatKurangSatuJam
				];


				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorCepatKurangDuaJam = 100 - ($dataJumlahTelat->jumlahcepatkurangduajam * 2);
				}
				# end perwali januari 2018
				else {
					$skorCepatKurangDuaJam = 100 - ($dataJumlahTelat->jumlahcepatkurangduajam * 3);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahTelat->jumlahcepatkurangduajam,
					'skor' 	=> $skorCepatKurangDuaJam
				];


				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorCepatKurangTigaJam	= 100 - ($dataJumlahTelat->jumlahcepatkurangtigajam * 3);
				}
				# end perwali januari 2018
				else {
					$skorCepatKurangTigaJam	= 100 - ($dataJumlahTelat->jumlahcepatkurangtigajam * 4);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahTelat->jumlahcepatkurangtigajam,
					'skor' 	=> $skorCepatKurangTigaJam
				];


				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorCepatKurangFull = 100 - ($dataJumlahTelat->jumlahcepatkurangfull * 4);
				}
				# end perwali januari 2018
				else {
					$skorCepatKurangFull = 100 - ($dataJumlahTelat->jumlahcepatkurangfull * 5);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahTelat->jumlahcepatkurangfull,
					'skor' 	=> $skorCepatKurangFull
				];

				$this->dataTable .= "<td>".$dataJumlahTelat->jumlahcepatkuranglimabelas."</td>";
				$this->dataTable .= "<td>".$skorCepatKurangLimaBelas."</td>";
				$this->dataTable .= "<td>".$dataJumlahTelat->jumlahcepatkurangsatujam."</td>";
				$this->dataTable .= "<td>".$skorCepatKurangSatuJam."</td>";
				$this->dataTable .= "<td>".$dataJumlahTelat->jumlahcepatkurangduajam."</td>";
				$this->dataTable .= "<td>".$skorCepatKurangDuaJam."</td>";
				$this->dataTable .= "<td>".$dataJumlahTelat->jumlahcepatkurangtigajam."</td>";
				$this->dataTable .= "<td>".$skorCepatKurangTigaJam."</td>";
				$this->dataTable .= "<td>".$dataJumlahTelat->jumlahcepatkurangfull."</td>";
				$this->dataTable .= "<td>".$skorCepatKurangFull."</td>";


				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorJumlahSakit = 100 - ($dataJumlahTelat->jumlahsakit * 1);
				}
				# end perwali januari 2018
				else {
					$skorJumlahSakit = 100 - ($dataJumlahTelat->jumlahsakit * 2);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahTelat->jumlahsakit,
					'skor' 	=> $skorJumlahSakit
				];

				$this->dataTable .= "<td>".$dataJumlahTelat->jumlahsakit."</td>";
				$this->dataTable .= "<td>".$skorJumlahSakit."</td>";

				//////////////////////////

				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorJumlahCutiBesar = 100 - ($dataJumlahTelat->jumlahcuti * 3);
				}
				# end perwali januari 2018
				else {
					$skorJumlahCutiBesar = 100 - ($dataJumlahTelat->jumlahcuti * 4);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahTelat->jumlahcuti,
					'skor' 	=> $skorJumlahCutiBesar
				];

				$this->dataTable .= "<td>".$dataJumlahTelat->jumlahcuti."</td>";
				$this->dataTable .= "<td>".$skorJumlahCutiBesar."</td>";


				////////////////////////

				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorJumlahTidakHadirSah = 100 - ($dataJumlahTelat->jumlahtidakhadirsah * 5);
				}
				# end perwali januari 2018
				else {
					$skorJumlahTidakHadirSah = 100 - ($dataJumlahTelat->jumlahtidakhadirsah * 5);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahTelat->jumlahtidakhadirsah,
					'skor' 	=> $skorJumlahTidakHadirSah
				];



				$this->dataTable .= "<td>".$dataJumlahTelat->jumlahtidakhadirsah."</td>";
				$this->dataTable .= "<td>".$skorJumlahTidakHadirSah."</td>";


				////////////////////////

				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorJumlahTidakHadirTidakSah	=	100 - ($dataJumlahTelat->jumlahtidakhadirtidaksah * 6);
				}
				# end perwali januari 2018
				else {
					$skorJumlahTidakHadirTidakSah	=	100 - ($dataJumlahTelat->jumlahtidakhadirtidaksah * 6);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahTelat->jumlahtidakhadirtidaksah,
					'skor' 	=> $skorJumlahTidakHadirTidakSah
				];


				$this->dataTable .= "<td>".$dataJumlahTelat->jumlahtidakhadirtidaksah."</td>";
				$this->dataTable .= "<td>".$skorJumlahTidakHadirTidakSah."</td>";

				/////


				$skorTotal =
							$skorLambatKurangLimaBelas +
							$skorLambatKurangSatuJam +
							$skorLambatKurangDuaJam  +
							$skorLambatKurangTigaJam +
							$skorLambatKurangFull +

							$skorCepatKurangLimaBelas +
							$skorCepatKurangSatuJam +
							$skorCepatKurangDuaJam +
							$skorCepatKurangTigaJam +
							$skorCepatKurangFull +

							$skorJumlahSakit +
							$skorJumlahCutiBesar +
							$skorJumlahTidakHadirSah +
							$skorJumlahTidakHadirTidakSah ;


				# start perwali desember 2018
				if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
					$skorTotal = $skorTotal + $skorLambatKurangLima;
				}
				# end perwali desember 2018



				$this->dataTable .= "<td>".number_format($skorTotal)."</td>";




				$this->dataTable .= "<td>".$jumlahMasuk."</td>";

				/////////////////////////

				$this->dataTable .= "<td>".$dataJumlahTelat->jumlahhadirtotal."</td>";

				//////////////////////

				$this->dataTable .= "<td>".$dataJumlahTelat->jumlahdinasluar."</td>";

				//////////////////////

				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorJumlahCutiTahunan = 100 - ($dataJumlahTelat->jumlahcutitahunan * 0);
				}
				# end perwali januari 2018

				$this->dataTable .= "<td>".$dataJumlahTelat->jumlahcutitahunan."</td>";

				///////

				$v_skor_total = 1400;
				# start perwali desember 2018
				if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
					$v_skor_total = 1500;
				}

				$skorTPP = 100 - ($v_skor_total - $skorTotal);

				# start tambah (if seda otomatis 100)
				if($skorTPP < 0) {
					$skorTPP = 0;
				}
				# end tambah

				# start perwali desember 2018
				$seda = "";
				$seda_input = NULL;
				if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
					if($dataPegawai->meninggal == 't'){
						$skorTPP = 100;
						$seda = " (Meninggal Dunia)";
						$seda_input = TRUE;
					}
				}
				#end perwali desember 2018

				$dataTable .= "<td>".$skorTPP."</td>";
				$dataTable .= "</tr>";

				$urutan = $i;

				/** INSERT LAP SKOR KEHADIRAN DETIL */
				$data = [
					'nip'			=> $dataPegawai->nip,
					'nama'			=> $dataPegawai->nama,
					'golongan'		=> $dataPegawai->nama_golongan,
					'jabatan'		=> $unor,
					'skor'			=> json_encode($skor_detil),
					'jml_hadir'		=> $dataJumlahTelat->jumlahhadirtotal,
					'jml_dl'		=> $dataJumlahTelat->jumlahdinasluar,
					'jml_cuti'		=> $dataJumlahTelat->jumlahcutitahunan,
					'bulan'			=> $bulan_get,
					'tahun'			=> $tahun_get,
					'id_instansi'	=> $id_instansi_get,
					'pns'			=> $pns_get,
					'meninggal'		=> $seda_input,
					'urut' 			=> $urutan
				];

				# start perwali januari 2018
				// if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					// $data['jml_cuti'] = $skorJumlahCutiTahunan;
				// }
				# end perwali januari 2018

				$this->Lap_skor_kehadiran_detil_model->insert($data);
				#end

				$i++;
			}
			#END


			redirect('lap_skor?bulan=' . $bulan_get . "&tahun=" . $tahun_get . "&id_instansi=" . $id_instansi_get . "&pns=" . $pns_get, 'refresh');
		}
		#end
	}

	public function stop() {
		$this->load->model([
			'Lap_skor_kehadiran_model'
		]);

		$bulan_get = $this->input->get('bulan') ? $this->input->get('bulan') : 0;
		$tahun_get = $this->input->get('tahun') ? $this->input->get('tahun') : '';
		$id_instansi_get = $this->input->get('id_instansi') ? $this->input->get('id_instansi') : '';
		$pns_get = $this->input->get('pns') ? $this->input->get('pns') : '';

		$where = [
			'bulan'			=> $bulan_get,
			'tahun'			=> $tahun_get,
			'id_instansi'	=> $id_instansi_get,
			'pns'			=> $pns_get,
			'deleted_at'	=> null,
			'finished_at'	=> null,
		];

		$this->Lap_skor_kehadiran_model->update($where, ['finished_at' => date('Y-m-d H:i:s')]);

		$this->session->set_flashdata('feedback_success', 'Update Laporan Telah Dihentikan');

		redirect('lap_skor','refresh');
	}

	function cek_roster($date, $id_pegawai){
    $cek_roster =   $this->_ci->db->query("select
            t_roster.id as id_t_roster,
            m_jenis_roster.id as id_jenis_roster,
            m_jenis_roster.kode,
            m_jam_kerja.id as id_jam_kerja,

            to_char(m_jam_kerja.jam_mulai_scan_masuk,'HH24:MI') as jam_mulai_scan_masuk ,
            to_char(m_jam_kerja.jam_akhir_scan_masuk,'HH24:MI') as jam_akhir_scan_masuk ,
            to_char(m_jam_kerja.jam_masuk,'HH24:MI') as jam_masuk ,

            to_char(m_jam_kerja.jam_mulai_scan_pulang,'HH24:MI') as jam_mulai_scan_pulang ,
            to_char(m_jam_kerja.jam_akhir_scan_pulang,'HH24:MI') as jam_akhir_scan_pulang ,
            to_char(m_jam_kerja.jam_pulang,'HH24:MI') as jam_pulang ,

            m_jam_kerja.pulang_hari_berikutnya,
            m_jam_kerja.masuk_hari_sebelumnya
          from
            t_roster,  m_jenis_roster, m_jam_kerja
          where
            to_char( t_roster.tanggal,'yyyy-mm-dd') = '".$date."' and
            t_roster.id_pegawai = '".$id_pegawai."' and
            t_roster.id_jenis_roster = m_jenis_roster.id and
            m_jenis_roster.id_jam_kerja = m_jam_kerja.id ");

      return  $cek_roster->row();
  	}
}
