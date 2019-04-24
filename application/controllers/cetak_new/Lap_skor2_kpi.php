<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lap_skor2_kpi extends CI_Controller {
	private $dataTable = "";

	public function __construct() {
		parent::__construct();
		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model','log_laporan_model', 'lap_skor_kpi_model', 'lap_skor_kpi_detil_model']);
	}

	public function index(){

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


		$this->load->library('konversi_menit');

		$whereInstansi 		=	"kode = '".$this->input->get('id_instansi')."' ";
		$this->dataInstansi = 	$this->instansi_model->getData($whereInstansi,"","");


		// $dataTables = "";

		$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
		// Tanggal pertama pada bulan ini
		$tglMulai 	= date('Y-m-01', strtotime($hari_ini));
		// Tanggal terakhir pada bulan ini
		$tglSelesai 	= date('Y-m-t', strtotime($hari_ini));


		$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
		// Tanggal pertama pada bulan ini
		$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
		// Tanggal terakhir pada bulan ini
		$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));

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

		$kodeAwalDinas	=	substr($this->input->get('id_instansi'),0,4);
		$wherePns 	= " and m.kode_status_pegawai ='5'";
		
		$query_kode_sik = $this->db->query("select kode_sik, nama from m_instansi where kode = '".$this->input->get('id_instansi')."'");
		$data_kode_sik = $query_kode_sik->row();

		/*highlight_string("<?php\n\$data =\n" . var_export($data_kode_sik->kode_sik, true) . ";\n?>");exit;*/

		if ($data_kode_sik->kode_sik == null) {
			$kode_instansi_all = $this->input->get('id_instansi');
			$whereQuery = "pukh.kode_instansi = '".$kode_instansi_all."'".$wherePns;

		}else{
			$kode_instansi_all = substr($this->input->get('id_instansi'), 0, 5);
			$whereQuery = "pukh.kode_instansi LIKE '".$kode_instansi_all.'%'."'".$wherePns;
		}

		$kode_instansi_all = $this->input->get('id_instansi');
		$whereQuery = "pukh.kode_instansi = '".$kode_instansi_all."'".$wherePns;

		/** CEK APAKAH PERNAH PRINT LAPORAN */
		$bulan_get = $this->input->get('bulan');
		$tahun_get = $this->input->get('tahun');
		$id_instansi_get = $this->input->get('id_instansi');
		$id_bidang_get = $this->input->get('id_bidang');
		$pns_get = $this->input->get('pns');

		$queryCekSudahPrintLaporan	=	$this->db->query("
			select * from lap_skor_kpi
			where bulan = '$bulan_get'
			and tahun = '$tahun_get'
			and id_instansi = '$id_instansi_get'
			and pns = '$pns_get'
			and deleted_at is null
		");

		if($queryCekSudahPrintLaporan->row()) {
			$this->dataTable = "";

			$this->printed($jumlahMasuk, $bulan_get, $tahun_get, $id_instansi_get, $id_bidang_get, $pns_get);

			return;

		} else {
			$this->load->model([
				'lap_skor_kpi_model',
				'lap_skor_kpi_detil_model'
			]);

			$data = [
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'			=> $pns_get,
			];

			$this->lap_skor_kpi_model->insert($data);
		}
		#end

		# start nambah status meninggal
		$queryPegawai 	=	$this->db->query("
			select
				m.id as id_pegawai,m.nama, m.nip, m.meninggal, m.tgl_meninggal,
				pukh.nama_unor,
				pukh.kode_unor,
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
				pukh.nama_unor,
				m.nama
		");
		# end nambah status meninggal
		$this->dataPegawai	=	$queryPegawai->result();
		//echo $this->db->last_query();
		

		$this->dataTable = "";

		$i=1;
		foreach($this->dataPegawai as $dataPegawai){
			$skor_detil = [];

			//echo $jumlahLibur."<br>";
			$this->dataTable .= "<tr>";
			$this->dataTable .= "<td>".$i."</td>";
			$this->dataTable .= "<td>".$dataPegawai->nama."</td>";
			$this->dataTable .= "<td>".$dataPegawai->nip."</td>";
			$this->dataTable .= "<td>".$dataPegawai->nama_unor."</td>";

			/*if($dataPegawai->nama_jabatan =='Staf'){
				$unor = $dataPegawai->nama_jabatan." - ".$dataPegawai->nama_rumpun_jabatan;
			}
			else{
				$unor = $dataPegawai->nama_jabatan;
			}

			$this->dataTable .= "<td>".$unor." </td>";*/


			$awallimabelas = 0;
			//$kurang_lima_bos = '';
			/*# start perwali desember 2018
			if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
				$awallimabelas = 5;
				$kurang_lima_bos = 'count(CASE WHEN datang_telat > 0 AND datang_telat <= 5 THEN datang_telat END) as jumlahlambatkuranglima,';
			}
			# end perwali database 2018*/

			$queryJumlah	=	$this->db->query("
					select
						count(CASE WHEN datang_telat > $awallimabelas AND datang_telat <= 15 THEN datang_telat END) as jumlahlambatkuranglimabelas,
						count(CASE WHEN datang_telat > 15 AND datang_telat <= 60 THEN datang_telat END) as jumlahlambatkurangsatujam,
						count(CASE WHEN datang_telat > 60 AND datang_telat <= 120 THEN datang_telat END) as jumlahlambatkurangduajam,
						count(CASE WHEN datang_telat > 120 THEN datang_telat END) as jumlahlambatkurangfull,
						count(CASE WHEN (jam_kerja = 'SK' AND (keterangan = 'IJIN SK DI HARI KERJA' OR excel)) OR (jam_kerja = 'CS' AND (keterangan = 'IJIN CS DI HARI KERJA' OR excel)) THEN jam_kerja END) as jumlahsakit,
						count(CASE WHEN jam_kerja = 'I' AND (keterangan = 'IJIN I DI HARI KERJA' OR excel) THEN jam_kerja END) as jumlahtidakhadirsah,
						count(CASE WHEN kode_masuk = 'M' THEN kode_masuk END) as jumlahtidakhadirtidaksah,
						count(CASE WHEN (jam_kerja = 'IM' AND (keterangan = 'IJIN IM DI HARI KERJA' OR excel)) OR (jam_kerja = 'IB' AND (keterangan = 'IJIN IB DI HARI KERJA' OR excel)) OR (jam_kerja = 'IKM' AND (keterangan = 'IJIN IKM DI HARI KERJA' OR excel)) THEN jam_kerja END) as jumlahizinlain,
						count(CASE WHEN kode_masuk = 'H' OR (jam_kerja::text != '' AND jadwal_masuk::text != '' AND finger_masuk::text != '' AND finger_masuk < jadwal_pulang) THEN kode_masuk END) as jumlahhadirtotal,
						count(CASE WHEN jam_kerja in ('DL','DK') THEN jam_kerja END) as jumlahDinasLuar
					from
						data_mentah
					where
						tanggal >= '".$tglMulai."'
						AND tanggal <=  '".$tglSelesai."' and
						id_pegawai = '".$dataPegawai->id_pegawai."'"
				);


			$dataJumlahTelat		=	$queryJumlah->row();

			//aturan izin ke izin lain
			if ($dataJumlahTelat->jumlahtidakhadirsah > 1) {
				$jumlah_tidak_hadir_sah = $dataJumlahTelat->jumlahtidakhadirsah - 1;
				$jumlah_izin_lain = $dataJumlahTelat->jumlahizinlain + 1;
			}elseif($dataJumlahTelat->jumlahtidakhadirsah == 1){
				$jumlah_tidak_hadir_sah = $dataJumlahTelat->jumlahtidakhadirsah = 0;
				$jumlah_izin_lain = $dataJumlahTelat->jumlahizinlain + 1;
			}else{
				$jumlah_tidak_hadir_sah = $dataJumlahTelat->jumlahtidakhadirsah;
				$jumlah_izin_lain = $dataJumlahTelat->jumlahizinlain;
			}



			$skorLambatKurangLimaBelas = $dataJumlahTelat->jumlahlambatkuranglimabelas * 0.25;
						//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahlambatkuranglimabelas,
				'skor' 	=> $skorLambatKurangLimaBelas
			];
			
			$skorLambatKurangSatuJam = $dataJumlahTelat->jumlahlambatkurangsatujam * 0.5;

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] =  [
				'frek'	=> $dataJumlahTelat->jumlahlambatkurangsatujam,
				'skor' 	=> $skorLambatKurangSatuJam
			];

			$skorLambatKurangDuaJam	= $dataJumlahTelat->jumlahlambatkurangduajam * 1;
			
			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahlambatkurangduajam,
				'skor' 	=> $skorLambatKurangDuaJam
			];

			
			$skorLambatKurangFull =	$dataJumlahTelat->jumlahlambatkurangfull * 1.5;
			
			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahlambatkurangfull,
				'skor' 	=> $skorLambatKurangFull
			];

			# end perwali desember 2018
			$this->dataTable .= "<td>".$dataJumlahTelat->jumlahlambatkuranglimabelas."</td>";
			$this->dataTable .= "<td>".$skorLambatKurangLimaBelas."</td>";
			$this->dataTable .= "<td>".$dataJumlahTelat->jumlahlambatkurangsatujam."</td>";
			$this->dataTable .= "<td>".$skorLambatKurangSatuJam."</td>";
			$this->dataTable .= "<td>".$dataJumlahTelat->jumlahlambatkurangduajam."</td>";
			$this->dataTable .= "<td>".$skorLambatKurangDuaJam."</td>";
			$this->dataTable .= "<td>".$dataJumlahTelat->jumlahlambatkurangfull."</td>";
			$this->dataTable .= "<td>".$skorLambatKurangFull."</td>";

			/////////////////////////////sakit////////////////////////////////////
			// aturan sakit, mask 3 yg tidak dipotong
			if ($dataJumlahTelat->jumlahsakit > 3) {
				$jumlah_sakit_valid = $dataJumlahTelat->jumlahsakit - 3;
				$skorJumlahSakit = $jumlah_sakit_valid * 1;
			}else{
				$jumlah_sakit_valid = $dataJumlahTelat->jumlahsakit;
				$skorJumlahSakit = 0;
			}
			
			
			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $jumlah_sakit_valid,
				'skor' 	=> $skorJumlahSakit
			];

			$this->dataTable .= "<td>".$jumlah_sakit_valid."</td>";
			$this->dataTable .= "<td>".$skorJumlahSakit."</td>";
			/////////////////////////////end sakit////////////////////////////////////

			/////////////////////////////mangkir dengan suket/////////////////////////
			$skorJumlahTidakHadirSah = $jumlah_tidak_hadir_sah * 2;
			
			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $jumlah_tidak_hadir_sah,
				'skor' 	=> $skorJumlahTidakHadirSah
			];

			$this->dataTable .= "<td>".$jumlah_tidak_hadir_sah."</td>";
			$this->dataTable .= "<td>".$skorJumlahTidakHadirSah."</td>";

			/////////////////////////////mangkir dengan suket/////////////////////////

			////////////////////////////mangkir tanpa suket///////////////////////////

			$skorJumlahTidakHadirTidakSah	=	$dataJumlahTelat->jumlahtidakhadirtidaksah * 3;
			
			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahtidakhadirtidaksah,
				'skor' 	=> $skorJumlahTidakHadirTidakSah
			];


			$this->dataTable .= "<td>".$dataJumlahTelat->jumlahtidakhadirtidaksah."</td>";
			$this->dataTable .= "<td>".$skorJumlahTidakHadirTidakSah."</td>";

			////////////////////////////mangkir tanpa suket///////////////////////////

			$skorTotal =
						$skorLambatKurangLimaBelas +
						$skorLambatKurangSatuJam +
						$skorLambatKurangDuaJam  +
						$skorLambatKurangFull +

						$skorJumlahSakit +
						$skorJumlahTidakHadirSah +
						$skorJumlahTidakHadirTidakSah ;

			/*# start perwali januari 2018
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


			# start perwali desember 2018
			if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
				$skorTotal = $skorTotal + $skorLambatKurangLima;
			}
			# end perwali desember 2018*/
			$seda = "";
			$seda_input = NULL;
			if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
				if($dataPegawai->meninggal == 't'){
					$seda = " (Meninggal Dunia)";
					$seda_input = TRUE;
				}
			}


			$this->dataTable .= "<td>".number_format($skorTotal)."</td>";

			$this->dataTable .= "<td>".$dataJumlahTelat->jumlahdinasluar."</td>";

			$this->dataTable .= "<td>".$jumlah_izin_lain."</td>";

			$this->dataTable .= "<td>".$dataJumlahTelat->jumlahhadirtotal."</td>";

			$this->dataTable .= "<td>".$jumlahMasuk."</td>";

			$skorKPI = 100 - $skorTotal;
			# start tambah (if seda otomatis 100)
			if($skorKPI < 0) {
				$skorKPI = 0;
			}

			$this->dataTable .= "<td>".$skorKPI."</td>";
			$this->dataTable .= "</tr>";

			$urutan = $i;

			/** INSERT LAP SKOR KEHADIRAN DETIL */
			$data = [
				'id_pegawai'	=> $dataPegawai->id_pegawai,
				'nip'			=> $dataPegawai->nip,
				'nama'			=> $dataPegawai->nama,
				//golongan diganti unor
				'golongan'		=> $dataPegawai->nama_unor,
				'jabatan'		=> $dataPegawai->nama_jabatan,
				'skor'			=> json_encode($skor_detil),
				'jml_hadir'		=> $dataJumlahTelat->jumlahhadirtotal,
				'jml_dl'		=> $dataJumlahTelat->jumlahdinasluar,
				'jml_cuti'		=> '0',
				'jml_izin_lain' => $jumlah_izin_lain,
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'			=> $pns_get,
				'meninggal' 	=> $seda_input,
				'urut'			=> $urutan,
				'skor_total'	=> $skorKPI,
				'id_unor'		=> $dataPegawai->kode_unor
			];


			# start perwali januari 2018
			// if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				// $data['jml_cuti'] = $skorJumlahCutiTahunan;
			// }
			# end perwali januari 2018

			$this->lap_skor_kpi_detil_model->insert($data);
			#end

			$i++;
		}

		// $this->load->view('cetak/lap_skor_view',[
		// 	'dataTable' => $this->dataTable
		// ]);
		$this->load->library('dompdf_gen');

		// echo $this->dataTable;

		$this->load->view('cetak/lap_skor_kpi_view',[
			'dataTable' => $this->dataTable
		]);
		$paper_size  = 'folio'; //paper size
		$orientation = 'potrait'; //tipe format kertas
		$html = $this->output->get_output();
		$this->dompdf->set_paper($paper_size, $orientation);
		//Convert to PDF
		$this->dompdf->load_html($html);
		$this->dompdf->render();
		$this->dompdf->stream("kk.pdf", array('Attachment'=>0));
	}

	public function printed($hari_kerja, $bulan, $tahun, $id_instansi, $id_bidang=null, $pns) {
		$detil_laporan	=	$this->db->query("
			select * from lap_skor_kpi_detil
			where bulan = '$bulan'
			and tahun = '$tahun'
			and id_instansi = '$id_instansi'
			and id_unor = '$id_bidang'
			and pns = '$pns'
			and deleted_at is null
			order by golongan
		")->result();

		$tabel_arr = array();

		foreach ($detil_laporan as $key => $value) {
			$temp = array();
			$temp[] = $key+1;
			$temp[] = $value->nama;
			$temp[] = $value->nip;
			$temp[] = $value->golongan;

			$skors = json_decode($value->skor);

			$totalSkor = 0;

			foreach ($skors as $skor) {
				$temp[] = $skor->frek;
				$temp[] = $skor->skor;

				$totalSkor += $skor->skor;
			}

			$temp[] = number_format((float)$totalSkor, 1, ',', '');
			$temp[] = $value->jml_dl;
			$temp[] = $value->jml_izin_lain;
			$temp[] = $hari_kerja;
			$temp[] = $value->jml_hadir;

			$skorKPI = 100 - $totalSkor;

			# start tambah (if seda otomatis 100)
			if($skorKPI < 0) {
				$skorKPI = 0;
			}
			# end tambah
			
			if($value->meninggal == 't'){
				$skorKPI = 100;
			}

			$temp[] = $skorKPI;

			$tabel_arr[] = $temp;
		}

		// echo $this->dataTable;

		$data_arr = array(
			'isi' => $tabel_arr,
			'tgl_mulai' => date('d-m-Y', strtotime($this->tgl_pertama)),
			'tgl_hingga' => date('d-m-Y', strtotime($this->tgl_terakhir)),
			'instansi' => $this->dataInstansi
		);

		$nama_dokumen = "Laporan_Skor_OS_".str_replace(" ","_",$data_arr['instansi']->nama)."_Tanggal_".str_replace("-","_",$data_arr['tgl_mulai'])."_s/d_".str_replace("-","_",$data_arr['tgl_hingga']);
		$current_date = date('d/m/Y H:i:s');

		if($this->input->get("type") == 'pdf') {
			ini_set('memory_limit', '-1');
			//$html_header = $this->load->view('cetak/skor_new/header', $data_arr, true); //render the view into HTML
			$html_body = $this->load->view('cetak/skor_kpi_new/body', $data_arr, true); //render the view into HTML

			$this->load->library('pdf');
			$pdf=$this->pdf->load("en-GB-x","FOLIO-L","","",10,10,5,10,6,3,"L");
			$pdf->SetWatermarkImage(base_url().'2018/assets/img/logo_pemkot_watermark.png', 0.7, '',array(90,38));
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

			$this->load->view('cetak/skor_kpi_new/excel', $data_arr);

			ob_end_clean();
		}
		else {
			$this->load->view('cetak/skor_kpi_new/body', $data_arr);
		}
	}

	public function stop() {
		$this->load->model([
			'lap_skor_kpi_model'
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

		$this->lap_skor_kpi_model->update($where, ['finished_at' => date('Y-m-d H:i:s')]);

		$this->session->set_flashdata('feedback_success', 'Update Laporan Telah Dihentikan');

		redirect('lap_skor_kpi','refresh');
	}

	public function generate_update() {
		$this->load->library('konversi_menit');

		/** CEK APAKAH ADA LAPORAN SUDAH DIKUNCI */
		$whereTahunBulan = $this->input->get('tahun') . '-' . $this->input->get('bulan');
		$id_instansi_get = $this->input->get('id_instansi');

		$laporanTerkunci	= $this->db->query("
			select * from log_laporan
			where to_char(tgl_log, 'YYYY-MM') = '$whereTahunBulan'
			and kd_instansi = '$id_instansi_get'
			and is_kunci = 'Y'
		")->row_array();

		if($laporanTerkunci) {
			$ret = array(
				'status' => 'gagal',
				'pesan' => 'Maaf, Laporan telah terkunci.'
			);

			echo json_encode($ret);

			return;
		}
		#END

		/** CEK APAKAH PERNAH PRINT LAPORAN */
		$bulan_get = $this->input->get('bulan');
		$tahun_get = $this->input->get('tahun');
		$id_instansi_get = $this->input->get('id_instansi');
		$pns_get = $this->input->get('pns_get');

		$queryCekSudahPrintLaporan	=	$this->db->query("
			select * from lap_skor_kpi
			where bulan = '$bulan_get'
			and tahun = '$tahun_get'
			and id_instansi = '$id_instansi_get'
			and pns = '$pns_get'
			and deleted_at is null
		");

       
		$wherePns 	= " and m.kode_status_pegawai = '5'";

        $query_kode_sik = $this->db->query("select kode_sik, nama from m_instansi where kode = '".$this->input->get('id_instansi')."'");
        $data_kode_sik = $query_kode_sik->row();

        /*highlight_string("<?php\n\$data =\n" . var_export($data_kode_sik->kode_sik, true) . ";\n?>");exit;*/
        $kode_instansi_all = $this->input->get('id_instansi');
        $whereQuery = "pukh.kode_instansi = '".$kode_instansi_all."'".$wherePns;

        $tanggal	=	$tahun_get."-".$bulan_get."-01";
        $tglSelesai 	= date('Y-m-t', strtotime($tanggal));

        $tanggal2	=	"01/".$bulan_get."/".$tahun_get;
        $tglSelesai2 	= date('t/m/Y', strtotime($tanggal));

		
		# start nambah status meninggal
		$queryPegawai 	=	$this->db->query("
			select
				m.id as id_pegawai,m.nama, m.nip, m.meninggal, m.tgl_meninggal,
				pukh.nama_unor,
				pukh.kode_unor,
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
				pukh.nama_unor,
				m.nama
		");
		# end nambah status meninggal
		
		$dataPegawai	=	$queryPegawai->result();

		/** CEK APAKAH ADA PROSES GENERATE DI USER LAINNYA */
        $cek = $this->cek_proses_gen_user_lain($bulan_get, $tahun_get, $id_instansi_get, $pns_get, $dataPegawai);
        if($cek['status'])
        {
        	$ret = array(
				'status' => 'antri',
				'pesan'  => $cek['pesan'],
				'uri' => $cek['uri'],
				'data_generate' => $cek['data_generate'],
				'jml_pegawai' => $cek['jml_pegawai'],
				'jml_tergenerate' => $cek['jml_tergenerate']
			);
        }
        else
        {
        	if(! $queryCekSudahPrintLaporan->row())
			{
				$ret = array(
					'status' => 'gagal',
					'pesan' => 'Laporan Rekap Skor belum pernah dibuat. Silahkan Klik Tampilkan'
				);
			}
			else
			{
	            $dt_ins['id_user']       = $this->session->userdata('id_karyawan');
				$dt_ins['kode_instansi'] = $id_instansi_get;
				$dt_ins['start_at']      = date('Y-m-d H:i:s');

				/** UPDATE IS_DELETE JADI NULL */
				$where = [
					'bulan'			=> $bulan_get,
					'tahun'			=> $tahun_get,
					'id_instansi'	=> $id_instansi_get,
					'pns'			=> $pns_get,
					'deleted_at'	=> null,
				];

				$this->lap_skor_kpi_model->update($where, ['deleted_at' => date('Y-m-d H:i:s')]);
				$this->lap_skor_kpi_detil_model->update($where, ['deleted_at' => date('Y-m-d H:i:s')]);
				#end

	            /** INSERT KE lap_skor_kpi */
	            $data_rekap_instansi = [
					'bulan'			=> $bulan_get,
					'tahun'			=> $tahun_get,
					'id_instansi'	=> $id_instansi_get,
					'pns'			=> $pns_get,
					'id_pegawai'	=> $this->session->userdata('id_karyawan'),
				];
				$this->lap_skor_kpi_model->insert($data_rekap_instansi);
				#end

	            $ret = array(
					'status'      		=> 'sukses',
					'pesan'       		=> $dataPegawai,
					'pns'				=> $pns_get,
					'kd_instansi' 		=> $id_instansi_get,
					'bulan'				=> $bulan_get,
					'tahun'				=> $tahun_get,
					'tgl_mulai'   		=> $tanggal2,
					'tgl_selesai' 		=> $tglSelesai2,
					'id_user_upd' 		=> $dt_ins['id_user'],
					'kode_instansi_upd' => $dt_ins['kode_instansi'],
					'start_at_upd'		=> $dt_ins['start_at']
				);
	        }
        }
        #end cek apakah ada prises generate
        echo json_encode($ret);
	}

	public function proses_generate_perpegawai()
	{
		$this->load->model('lap_skor_kpi_model', 'lap_skor_kpi_detil_model');
		$this->load->library('konversi_menit');

		$id_pegawai 		= $this->input->post('id_pegawai');
		$tgl_mulai   		= $this->input->post('tgl_mulai_peg');
		$tgl_selesai 		= $this->input->post('tgl_akhir_peg');
		$instansiRaw 	  	= $this->input->post("id_instansi_peg");
		$nama_jabatan       = $this->input->post('jabatan');
		$rumpun_jabatan     = $this->input->post('rumpun_jabatan');
		$pns 				= $this->input->post('pns');
		$id_unor			= $this->input->post('id_unor');
		$meninggal          = $this->input->post('meninggal');

		$tmulai 		= explode('/', $tgl_mulai);
		$thingga 		= explode('/', $tgl_selesai);
		$akhir 			= $thingga[2]."-".$thingga[1]."-".$thingga[0];
		$mulai 			= $tmulai[2]."-".$tmulai[1]."-".$tmulai[0];

		/** INSERT LAPORAN BARU */

        if ($id_pegawai != '')
        {
        	$time_awal 	= strtotime($mulai);
			$time_akhir = strtotime($akhir);
			$begin 		= new DateTime($mulai);
			$end   		= new DateTime($akhir);

			$jumlahHari = $begin->diff($end) ;
			$jumlahHari = $jumlahHari->days + 1 ;
			$diff 		= abs($time_akhir - $time_awal);

			$skor_detil = [];
			if($nama_jabatan == 'Staf'){
				$unor = $nama_jabatan." - ".$rumpun_jabatan;
			}
			else{
				$unor = $nama_jabatan;
			}

			$awallimabelas = 0;

			$queryJumlah	=	$this->db->query("
					select
						count(CASE WHEN datang_telat > $awallimabelas AND datang_telat <= 15 THEN datang_telat END) as jumlahlambatkuranglimabelas,
						count(CASE WHEN datang_telat > 15 AND datang_telat <= 60 THEN datang_telat END) as jumlahlambatkurangsatujam,
						count(CASE WHEN datang_telat > 60 AND datang_telat <= 120 THEN datang_telat END) as jumlahlambatkurangduajam,
						count(CASE WHEN datang_telat > 120 THEN datang_telat END) as jumlahlambatkurangfull,
						count(CASE WHEN (jam_kerja = 'SK' AND (keterangan = 'IJIN SK DI HARI KERJA' OR excel)) OR (jam_kerja = 'CS' AND (keterangan = 'IJIN CS DI HARI KERJA' OR excel)) THEN jam_kerja END) as jumlahsakit,
						count(CASE WHEN jam_kerja = 'I' AND (keterangan = 'IJIN I DI HARI KERJA' OR excel) THEN jam_kerja END) as jumlahtidakhadirsah,
						count(CASE WHEN kode_masuk = 'M' THEN kode_masuk END) as jumlahtidakhadirtidaksah,
						count(CASE WHEN (jam_kerja = 'IM' AND (keterangan = 'IJIN IM DI HARI KERJA' OR excel)) OR (jam_kerja = 'IB' AND (keterangan = 'IJIN IB DI HARI KERJA' OR excel)) OR (jam_kerja = 'IKM' AND (keterangan = 'IJIN IKM DI HARI KERJA' OR excel)) THEN jam_kerja END) as jumlahizinlain,
						count(CASE WHEN kode_masuk = 'H' OR (jam_kerja::text != '' AND jadwal_masuk::text != '' AND finger_masuk::text != '' AND finger_masuk < jadwal_pulang) THEN kode_masuk END) as jumlahhadirtotal,
						count(CASE WHEN jam_kerja in ('DL','DK') THEN jam_kerja END) as jumlahDinasLuar
					from
						data_mentah
					where
						tanggal >= '".$mulai."'
						AND tanggal <=  '".$akhir."' and
						id_pegawai = '".$id_pegawai."'"
			);


			$dataJumlahTelat		=	$queryJumlah->row();

			if ($dataJumlahTelat->jumlahtidakhadirsah > 1) {
				$jumlah_tidak_hadir_sah = $dataJumlahTelat->jumlahtidakhadirsah - 1;
				$jumlah_izin_lain = $dataJumlahTelat->jumlahizinlain + 1;
			}elseif($dataJumlahTelat->jumlahtidakhadirsah == 1){
				$jumlah_tidak_hadir_sah = $dataJumlahTelat->jumlahtidakhadirsah = 0;
				$jumlah_izin_lain = $dataJumlahTelat->jumlahizinlain + 1;
			}else{
				$jumlah_tidak_hadir_sah = $dataJumlahTelat->jumlahtidakhadirsah;
				$jumlah_izin_lain = $dataJumlahTelat->jumlahizinlain;
			}
			
			$skorLambatKurangLimaBelas = $dataJumlahTelat->jumlahlambatkuranglimabelas * 0.25;
			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahlambatkuranglimabelas,
				'skor' 	=> $skorLambatKurangLimaBelas
			];				

			$skorLambatKurangSatuJam = $dataJumlahTelat->jumlahlambatkurangsatujam * 0.5;

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] =  [
				'frek'	=> $dataJumlahTelat->jumlahlambatkurangsatujam,
				'skor' 	=> $skorLambatKurangSatuJam
			];

			$skorLambatKurangDuaJam	= $dataJumlahTelat->jumlahlambatkurangduajam * 1;
			
			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahlambatkurangduajam,
				'skor' 	=> $skorLambatKurangDuaJam
			];

			
			$skorLambatKurangFull =	$dataJumlahTelat->jumlahlambatkurangfull * 1.5;
			
			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahlambatkurangfull,
				'skor' 	=> $skorLambatKurangFull
			];
			
			
			/////////////////////////////sakit////////////////////////////////////
			// aturan sakit, mask 3 yg tidak dipotong
			if ($dataJumlahTelat->jumlahsakit > 3) {
				$jumlah_sakit_valid = $dataJumlahTelat->jumlahsakit - 3;
				$skorJumlahSakit = $jumlah_sakit_valid * 1;
			}else{
				$jumlah_sakit_valid = $dataJumlahTelat->jumlahsakit;
				$skorJumlahSakit = 0;
			}
			
			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $jumlah_sakit_valid,
				'skor' 	=> $skorJumlahSakit
			];
			/////////////////////////////end sakit////////////////////////////////////

			/////////////////////////////mangkir dengan suket/////////////////////////
			$skorJumlahTidakHadirSah = $jumlah_tidak_hadir_sah * 2;
			
			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $jumlah_tidak_hadir_sah,
				'skor' 	=> $skorJumlahTidakHadirSah
			];
			/////////////////////////////mangkir dengan suket/////////////////////////

			////////////////////////////mangkir tanpa suket///////////////////////////

			$skorJumlahTidakHadirTidakSah	=	$dataJumlahTelat->jumlahtidakhadirtidaksah * 3;
			
			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTelat->jumlahtidakhadirtidaksah,
				'skor' 	=> $skorJumlahTidakHadirTidakSah
			];
			////////////////////////////end mangkir tanpa suket///////////////////////////

			////////////////////////////////skor total /////////////////////////////////
			$skorTotal =
						$skorLambatKurangLimaBelas +
						$skorLambatKurangSatuJam +
						$skorLambatKurangDuaJam  +
						$skorLambatKurangFull +

						$skorJumlahSakit +
						$skorJumlahTidakHadirSah +
						$skorJumlahTidakHadirTidakSah ;
			

			$skorKPI = 100 - $skorTotal;

			# start tambah (if seda otomatis 100)
			if($skorKPI < 0) {
				$skorKPI = 0;
			}
			# end tambah

			# start perwali desember 2018
			$seda = "";
			$seda_input = NULL;
			if(($this->input->post("bulan") == 12 && $this->input->post("tahun") == 2018) || $this->input->post("tahun") == 2019) {
				if($meninggal == 't'){
					$skorKPI = 100;
					$seda = " (Meninggal Dunia)";
					$seda_input = TRUE;
				}
			}
			# end perwali desember 2018
			//////////////////////////////// end skor total /////////////////////////////////

			$urutan = $this->input->post('urut2');

            /** INSERT LAP SKOR kpi DETIL */
			$data = [
				'id_pegawai'	=> $id_pegawai,
				'nip'			=> $this->input->post('nip'),
				'nama'			=> $this->input->post('nama'),
				//golongan diganti dengan nama unornya
				'golongan'		=> $this->input->post('nama_unor'),
				'jabatan'		=> $nama_jabatan,
				'skor'			=> json_encode($skor_detil),
				'jml_hadir'		=> $dataJumlahTelat->jumlahhadirtotal,
				'jml_dl'		=> $dataJumlahTelat->jumlahdinasluar,
				'jml_cuti'		=> '0',
				'jml_izin_lain' => $jumlah_izin_lain,
				'bulan'			=> $thingga[1],
                'tahun'			=> $thingga[2],
                'id_instansi'	=> $instansiRaw,
				'pns'			=> $pns,
				'meninggal' 	=> $seda_input,
				'urut' 			=> $urutan,
				'skor_total'	=> $skorKPI,
				'id_unor'		=> $id_unor
			];

			$this->lap_skor_kpi_detil_model->insert($data);
			#end
			echo json_encode(['status' => 'sukses', 'pesan' => 'Sukses Generate per pegawai']);
		}
		else
		{
			echo json_encode(['status' => 'gagal', 'pesan' => 'Gagal Generate per pegawai']);
		}
	}

	public function update_selesai_gen_laporan(){
		/** UPDATE FINISHED_AT JADI NOT NULL */
		$where = [
			'bulan'			=> $this->input->post('bulan_update'),
			'tahun'			=> $this->input->post('tahun_update'),
			'id_instansi'	=> $this->input->post('id_instansi_update'),
			'id_unor'		=> $this->input->post('id_unor_update'),
			'pns'			=> $this->input->post('pns_update'),
			'deleted_at'	=> null,
		];

		$this->lap_skor_kpi_model->update($where, ['finished_at' => date('Y-m-d H:i:s')]);
		#end

		echo json_encode(['status' => 'sukses', 'pesan' => 'Sukses Generate data']);
	}

	public function cek_proses_gen_user_lain($bulan_get, $tahun_get, $id_instansi_get, $pns_get, $data_pegawai)
	{
		/** CEK APAKAH ADA PROSES GENERATE DI USER LAINNYA */
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

		if($queryGeneratingLaporan) {
			$laporanTergenerate	= $this->db->query("
				select * from lap_skor_kpi_detil
				where bulan = '$bulan_get'
				and tahun = '$tahun_get'
				and id_instansi = '$id_instansi_get'
				and pns = '$pns_get'
				and deleted_at is null
			")->result();

			$antri = [
				'status' => TRUE,
				'pesan' => 'Terdapat antrian pada proses generate',
				'uri' => $data_uri,
				'data_generate' => $queryGeneratingLaporan,
				'jml_pegawai' => count($data_pegawai),
				'jml_tergenerate' => count($laporanTergenerate)
			];
		}
		else
		{
			$antri = [
				'status' => FALSE,
				'pesan' => 'loss',
				'uri' => $data_uri
			];
		}

		return $antri;
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
