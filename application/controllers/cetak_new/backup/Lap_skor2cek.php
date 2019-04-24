<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lap_skor2cek extends CI_Controller {
	private $dataTable = "";

	public function __construct() {
		parent::__construct();
		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model','log_laporan_model']);
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

		if($this->input->get("pns") == 'y'){
			$wherePns 	= " and m.kode_status_pegawai='1'";
		}
		else{

			$wherePns 	= " and m.kode_status_pegawai!='1'";
		}

		$query_kode_sik = $this->db->query("select kode_sik, nama from m_instansi where kode = '".$this->input->get('id_instansi')."'");
		$data_kode_sik = $query_kode_sik->row();

		/*highlight_string("<?php\n\$data =\n" . var_export($data_kode_sik->kode_sik, true) . ";\n?>");exit;*/

		/*if ($data_kode_sik->kode_sik == null) {
			$kode_instansi_all = $this->input->get('id_instansi');
			$whereQuery = "pukh.kode_instansi = '".$kode_instansi_all."'".$wherePns;

		}else{
			$kode_instansi_all = substr($this->input->get('id_instansi'), 0, 5);
			$whereQuery = "pukh.kode_instansi LIKE '".$kode_instansi_all.'%'."'".$wherePns;
		}*/

		if (substr($data_kode_sik->nama, 0, 9) != 'Kecamatan') {
		$kode_instansi_all = $this->input->get('id_instansi');
		$whereQuery = "pukh.kode_instansi = '".$kode_instansi_all."'".$wherePns;

		}else{
			$kode_instansi_all = substr($this->input->get('id_instansi'), 0, 5);
			$whereQuery = "pukh.kode_instansi LIKE '".$kode_instansi_all.'%'."'".$wherePns;
		}



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

		if($queryCekSudahPrintLaporan->row()) {
			$this->dataTable = "";

			$this->printed($jumlahMasuk, $bulan_get, $tahun_get, $id_instansi_get, $pns_get);

			return;

		} else {
			$this->load->model([
				'Lap_skor_kehadiran_model',
				'Lap_skor_kehadiran_detil_model'
			]);

			$data = [
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'			=> $pns_get,
			];

			$this->Lap_skor_kehadiran_model->insert($data);
		}
		#end

		echo "select
			m.id as id_pegawai,m.nama, m.nip, , m.meninggal, m.tgl_meninggal,
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
			pjh.urut,
			peh.kode_eselon,
			pgh.kode_golongan desc,
			m.nip";
			die;
		# start nambah status meninggal
		$queryPegawai 	=	$this->db->query("
		select
			m.id as id_pegawai,m.nama, m.nip, , m.meninggal, m.tgl_meninggal,
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
			pjh.urut,
			peh.kode_eselon,
			pgh.kode_golongan desc,
			m.nip

		");
		# end nambah status meninggal
		$this->dataPegawai	=	$queryPegawai->result();
		/*highlight_string("<?php\n\$data =\n" . var_export($this->dataPegawai, true) . ";\n?>");exit;*/
		//echo $this->db->last_query();
		$this->dataTable = "";

		$i=1;
		foreach($this->dataPegawai as $dataPegawai){
			$skor_detil = [];

			//echo $jumlahLibur."<br>";
			$this->dataTable .= "<tr>";
			$this->dataTable .= "<td>XXXXXXX".$i."</td>";
			$this->dataTable .= "<td>".$dataPegawai->nama."</td>";
			$this->dataTable .= "<td>".$dataPegawai->nip."</td>";
			$this->dataTable .= "<td>".$dataPegawai->nama_golongan."</td>";

			if($dataPegawai->nama_jabatan =='Staf'){
				$unor = $dataPegawai->nama_jabatan." - ".$dataPegawai->nama_rumpun_jabatan;
			}
			else{
				$unor = $dataPegawai->nama_jabatan;
			}

			$this->dataTable .= "<td>".$unor." </td>";

			# start perwali desember 2018
			if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
				$queryJumlahLambatKurangLima	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					datang_telat > 0 and datang_telat <= 5
				");

				$dataJumlahLambatKurangLima		=	$queryJumlahLambatKurangLima->row();
				$skorLambatKurangLima			=	100 - ($dataJumlahLambatKurangLima->jumlah * 0.25);

				$skor_detil[] = [
					'frek'	=> $dataJumlahLambatKurangLima->jumlah,
					'skor' 	=> $skorLambatKurangLima
				];
			}
			# end perwali database 2018


			$awallimabelas = 0;
			# start perwali desember 2018
			if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
				$awallimabelas = 5;
			}
			# end perwali database 2018

			$queryJumlahLambatKurangLimaBelas	=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				tanggal >= '".$tglMulai."'
				AND tanggal <=  '".$tglSelesai."' and
				id_pegawai = '".$dataPegawai->id_pegawai."' and
				datang_telat > $awallimabelas and datang_telat <= 15
			");

			$dataJumlahLambatKurangLimaBelas	=	$queryJumlahLambatKurangLimaBelas->row();
			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorLambatKurangLimaBelas			=	100 - ($dataJumlahLambatKurangLimaBelas->jumlah * 0.25);
			}
			# end perwali januari 2018
			else {
				$skorLambatKurangLimaBelas			=	100 - ($dataJumlahLambatKurangLimaBelas->jumlah * 1);
			}


			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahLambatKurangLimaBelas->jumlah,
				'skor' 	=> $skorLambatKurangLimaBelas
			];


			$queryJumlahLambatKurangSatuJam		=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				tanggal >= '".$tglMulai."'
				AND tanggal <=  '".$tglSelesai."' and
				id_pegawai = '".$dataPegawai->id_pegawai."' and
				datang_telat > 15 and datang_telat <= 60
			");

			$dataJumlahLambatKurangSatuJam	=	$queryJumlahLambatKurangSatuJam->row();
			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorLambatKurangSatuJam			=	100 - ($dataJumlahLambatKurangSatuJam->jumlah * 1);
			}
			# end perwali januari 2018
			else {
				$skorLambatKurangSatuJam			=	100 - ($dataJumlahLambatKurangSatuJam->jumlah * 2);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] =  [
				'frek'	=> $dataJumlahLambatKurangSatuJam->jumlah,
				'skor' 	=> $skorLambatKurangSatuJam
			];

			$queryJumlahLambatKurangDuaJam		=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				tanggal >= '".$tglMulai."'
				AND tanggal <=  '".$tglSelesai."' and
				id_pegawai = '".$dataPegawai->id_pegawai."' and
				datang_telat > 60 and datang_telat <= 120
			");

			$dataJumlahLambatKurangDuaJam	=	$queryJumlahLambatKurangDuaJam->row();
			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorLambatKurangDuaJam			=	100 - ($dataJumlahLambatKurangDuaJam->jumlah * 2);
			}
			# end perwali januari 2018
			else {
				$skorLambatKurangDuaJam			=	100 - ($dataJumlahLambatKurangDuaJam->jumlah * 3);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahLambatKurangDuaJam->jumlah,
				'skor' 	=> $skorLambatKurangDuaJam
			];

			$queryJumlahLambatKurangTigaJam		=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				tanggal >= '".$tglMulai."'
				AND tanggal <=  '".$tglSelesai."' and
				id_pegawai = '".$dataPegawai->id_pegawai."' and
				datang_telat > 120 and datang_telat <= 180
			");

			$dataJumlahLambatKurangTigaJam	=	$queryJumlahLambatKurangTigaJam->row();
			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorLambatKurangTigaJam			=	100 - ($dataJumlahLambatKurangTigaJam->jumlah * 3);
			}
			# end perwali januari 2018
			else {
				$skorLambatKurangTigaJam			=	100 - ($dataJumlahLambatKurangTigaJam->jumlah * 4);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahLambatKurangTigaJam->jumlah,
				'skor' 	=> $skorLambatKurangTigaJam
			];

			$queryJumlahLambatKurangFull	=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				tanggal >= '".$tglMulai."'
				AND tanggal <=  '".$tglSelesai."' and
				id_pegawai = '".$dataPegawai->id_pegawai."' and
				datang_telat > 180
			");

			$dataJumlahLambatKurangFull	=	$queryJumlahLambatKurangFull->row();
			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorLambatKurangFull			=	100 - ($dataJumlahLambatKurangFull->jumlah * 4);
			}
			# end perwali januari 2018
			else {
				$skorLambatKurangFull			=	100 - ($dataJumlahLambatKurangFull->jumlah * 5);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahLambatKurangFull->jumlah,
				'skor' 	=> $skorLambatKurangFull
			];


			$this->dataTable .= "<td>".$dataJumlahLambatKurangLimaBelas->jumlah."</td>";
			$this->dataTable .= "<td>".$skorLambatKurangLimaBelas."</td>";
			$this->dataTable .= "<td>".$dataJumlahLambatKurangSatuJam->jumlah."</td>";
			$this->dataTable .= "<td>".$skorLambatKurangSatuJam."</td>";
			$this->dataTable .= "<td>".$dataJumlahLambatKurangDuaJam->jumlah."</td>";
			$this->dataTable .= "<td>".$skorLambatKurangDuaJam."</td>";
			$this->dataTable .= "<td>".$dataJumlahLambatKurangTigaJam->jumlah."</td>";
			$this->dataTable .= "<td>".$skorLambatKurangTigaJam."</td>";
			$this->dataTable .= "<td>".$dataJumlahLambatKurangFull->jumlah."</td>";
			$this->dataTable .= "<td>".$skorLambatKurangFull."</td>";



			//// pulang cepat

			$queryJumlahCepatKurangLimaBelas	=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				tanggal >= '".$tglMulai."'
				AND tanggal <=  '".$tglSelesai."' and
				id_pegawai = '".$dataPegawai->id_pegawai."' and
				pulang_cepat > 0 and pulang_cepat <= 15
			");

			$dataJumlahCepatKurangLimaBelas	=	$queryJumlahCepatKurangLimaBelas->row();
			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorCepatKurangLimaBelas		=	100 - ($dataJumlahCepatKurangLimaBelas->jumlah * 0.25);
			}
			# end perwali januari 2018
			else {
				$skorCepatKurangLimaBelas		=	100 - ($dataJumlahCepatKurangLimaBelas->jumlah * 1);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahCepatKurangLimaBelas->jumlah,
				'skor' 	=> $skorCepatKurangLimaBelas
			];

			$queryJumlahCepatKurangSatuJam		=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				tanggal >= '".$tglMulai."'
				AND tanggal <=  '".$tglSelesai."' and
				id_pegawai = '".$dataPegawai->id_pegawai."' and
				pulang_cepat > 15 and pulang_cepat <=60
			");

			$dataJumlahCepatKurangSatuJam	=	$queryJumlahCepatKurangSatuJam->row();
			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorCepatKurangSatuJam		=	100 - ($dataJumlahCepatKurangSatuJam->jumlah * 1);
			}
			# end perwali januari 2018
			else {
				$skorCepatKurangSatuJam		=	100 - ($dataJumlahCepatKurangSatuJam->jumlah * 2);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahCepatKurangSatuJam->jumlah,
				'skor' 	=> $skorCepatKurangSatuJam
			];

			$queryJumlahCepatKurangDuaJam		=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				tanggal >= '".$tglMulai."'
				AND tanggal <=  '".$tglSelesai."' and
				id_pegawai = '".$dataPegawai->id_pegawai."' and
				pulang_cepat > 60 and pulang_cepat <= 120
			");

			$dataJumlahCepatKurangDuaJam	=	$queryJumlahCepatKurangDuaJam->row();
			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorCepatKurangDuaJam		=	100 - ($dataJumlahCepatKurangDuaJam->jumlah * 2);
			}
			# end perwali januari 2018
			else {
				$skorCepatKurangDuaJam		=	100 - ($dataJumlahCepatKurangDuaJam->jumlah * 3);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahCepatKurangDuaJam->jumlah,
				'skor' 	=> $skorCepatKurangDuaJam
			];

			$queryJumlahCepatKurangTigaJam		=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				tanggal >= '".$tglMulai."'
				AND tanggal <=  '".$tglSelesai."' and
				id_pegawai = '".$dataPegawai->id_pegawai."' and
				pulang_cepat > 120 and pulang_cepat <=180
			");

			$dataJumlahCepatKurangTigaJam	=	$queryJumlahCepatKurangTigaJam->row();
			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorCepatKurangTigaJam		=	100 - ($dataJumlahCepatKurangTigaJam->jumlah * 3);
			}
			# end perwali januari 2018
			else {
				$skorCepatKurangTigaJam		=	100 - ($dataJumlahCepatKurangTigaJam->jumlah * 4);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahCepatKurangTigaJam->jumlah,
				'skor' 	=> $skorCepatKurangTigaJam
			];

			$queryJumlahCepatKurangFull	=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				tanggal >= '".$tglMulai."'
				AND tanggal <=  '".$tglSelesai."' and
				id_pegawai = '".$dataPegawai->id_pegawai."' and
				pulang_cepat > 180
			");

			$dataJumlahCepatKurangFull	=	$queryJumlahCepatKurangFull->row();
			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorCepatKurangFull		=	100 - ($dataJumlahCepatKurangFull->jumlah * 4);
			}
			# end perwali januari 2018
			else {
				$skorCepatKurangFull		=	100 - ($dataJumlahCepatKurangFull->jumlah * 5);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahCepatKurangFull->jumlah,
				'skor' 	=> $skorCepatKurangFull
			];

			$this->dataTable .= "<td>".$dataJumlahCepatKurangLimaBelas->jumlah."</td>";
			$this->dataTable .= "<td>".$skorCepatKurangLimaBelas."</td>";
			$this->dataTable .= "<td>".$dataJumlahCepatKurangSatuJam->jumlah."</td>";
			$this->dataTable .= "<td>".$skorCepatKurangSatuJam."</td>";
			$this->dataTable .= "<td>".$dataJumlahCepatKurangDuaJam->jumlah."</td>";
			$this->dataTable .= "<td>".$skorCepatKurangDuaJam."</td>";
			$this->dataTable .= "<td>".$dataJumlahCepatKurangTigaJam->jumlah."</td>";
			$this->dataTable .= "<td>".$skorCepatKurangTigaJam."</td>";
			$this->dataTable .= "<td>".$dataJumlahCepatKurangFull->jumlah."</td>";
			$this->dataTable .= "<td>".$skorCepatKurangFull."</td>";


			$queryJumlahSakit	=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				tanggal >= '".$tglMulai."'
				AND tanggal <=  '".$tglSelesai."' and
				id_pegawai = '".$dataPegawai->id_pegawai."' and
				JAM_KERJA in ('SK','CS')  and
				tanggal not in (
					select
						tanggal
					from
						s_hari_libur
					where
						tanggal >= '".$tglMulai."'
						AND tanggal <=  '".$tglSelesai."'
				) and
				EXTRACT(ISODOW FROM tanggal) not in (6, 7)
			");

			$dataJumlahSakit	=	$queryJumlahSakit->row();
			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorJumlahSakit		=	100 - ($dataJumlahSakit->jumlah * 1);
			}
			# end perwali januari 2018
			else {
				$skorJumlahSakit		=	100 - ($dataJumlahSakit->jumlah * 2);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahSakit->jumlah,
				'skor' 	=> $skorJumlahSakit
			];

			$this->dataTable .= "<td>".$dataJumlahSakit->jumlah."</td>";
			$this->dataTable .= "<td>".$skorJumlahSakit."</td>";

			//////////////////////////

			$queryJumlahCutiBesar	=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				tanggal >= '".$tglMulai."'
				AND tanggal <=  '".$tglSelesai."' and
				id_pegawai = '".$dataPegawai->id_pegawai."' and
				JAM_KERJA in ('CAP','CM','CH')	and
				tanggal not in (
					select
						tanggal
					from
						s_hari_libur
					where
						tanggal >= '".$tglMulai."'
						AND tanggal <=  '".$tglSelesai."'
				) and
				EXTRACT(ISODOW FROM tanggal) not in (6, 7)
			");
			//echo $dataJumlahCutiBesar->jumlah;
			$dataJumlahCutiBesar	=	$queryJumlahCutiBesar->row();
			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorJumlahCutiBesar	=	100 - ($dataJumlahCutiBesar->jumlah * 3);
			}
			# end perwali januari 2018
			else {
				$skorJumlahCutiBesar	=	100 - ($dataJumlahCutiBesar->jumlah * 4);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahCutiBesar->jumlah,
				'skor' 	=> $skorJumlahCutiBesar
			];

			$this->dataTable .= "<td>".$dataJumlahCutiBesar->jumlah."</td>";
			$this->dataTable .= "<td>".$skorJumlahCutiBesar."</td>";


			////////////////////////

			$queryJumlahTidakHadirSah	=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				tanggal >= '".$tglMulai."'
				AND tanggal <=  '".$tglSelesai."' and
				id_pegawai = '".$dataPegawai->id_pegawai."' and
				JAM_KERJA in ('I') and
				tanggal not in (
					select
						tanggal
					from
						s_hari_libur
					where
						tanggal >= '".$tglMulai."'
						AND tanggal <=  '".$tglSelesai."'
				) and
				EXTRACT(ISODOW FROM tanggal) not in (6, 7)
			");

			$dataJumlahTidakHadirSah	=	$queryJumlahTidakHadirSah->row() ;
			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorJumlahTidakHadirSah	=	100 - ($dataJumlahTidakHadirSah->jumlah * 5);
			}
			# end perwali januari 2018
			else {
				$skorJumlahTidakHadirSah		=	100 - ($dataJumlahTidakHadirSah->jumlah * 5);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTidakHadirSah->jumlah,
				'skor' 	=> $skorJumlahTidakHadirSah
			];



			$this->dataTable .= "<td>".$dataJumlahTidakHadirSah->jumlah."</td>";
			$this->dataTable .= "<td>".$skorJumlahTidakHadirSah."</td>";


			////////////////////////

			$queryJumlahTidakHadirTidakSah	=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				tanggal >= '".$tglMulai."'
				AND tanggal <=  '".$tglSelesai."' and
				id_pegawai = '".$dataPegawai->id_pegawai."' and
				kode_masuk in ('M') and
				tanggal not in (
					select
						tanggal
					from
						s_hari_libur
					where
						tanggal >= '".$tglMulai."'
						AND tanggal <=  '".$tglSelesai."'
				) and
				EXTRACT(ISODOW FROM tanggal) not in (6, 7)
			");

			$dataJumlahTidakHadirTidakSah	=	$queryJumlahTidakHadirTidakSah->row();
			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorJumlahTidakHadirTidakSah	=	100 - ($dataJumlahTidakHadirTidakSah->jumlah * 6);
			}
			# end perwali januari 2018
			else {
				$skorJumlahTidakHadirTidakSah	=	100 - ($dataJumlahTidakHadirTidakSah->jumlah * 6);
			}

			//untuk insert ke lap_skor_kehadiran_detail
			$skor_detil[] = [
				'frek'	=> $dataJumlahTidakHadirTidakSah->jumlah,
				'skor' 	=> $skorJumlahTidakHadirTidakSah
			];


			$this->dataTable .= "<td>".$dataJumlahTidakHadirTidakSah->jumlah."</td>";
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

			/****ini Lawas

			$queryJumlahHadirTotal	=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				tanggal >= '".$tglMulai."'
				AND tanggal <=  '".$tglSelesai."' and
				id_pegawai = '".$dataPegawai->id_pegawai."' and
				finger_masuk is not null  and kode_masuk ='H' and
				tanggal not in (
					select
						tanggal
					from
						s_hari_libur
					where
						tanggal >= '".$tglMulai."'
						AND tanggal <=  '".$tglSelesai."'
				) and
				EXTRACT(ISODOW FROM tanggal) not in (6, 7)

			");
			**/

			$queryJumlahHadirTotal	=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				tanggal >= '".$tglMulai."'
				AND tanggal <=  '".$tglSelesai."' and
				id_pegawai = '".$dataPegawai->id_pegawai."' and
				kode_masuk ='H'

			");
			$jumlahMasukTotal	=	$queryJumlahHadirTotal->row();

			$this->dataTable .= "<td>".$jumlahMasukTotal->jumlah."</td>";

			//////////////////////


			$queryJumlahDinasLuar	=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				tanggal >= '".$tglMulai."'
				AND tanggal <=  '".$tglSelesai."' and
				id_pegawai = '".$dataPegawai->id_pegawai."' and
				JAM_KERJA in ('DL','DK') and
				tanggal not in (
					select
						tanggal
					from
						s_hari_libur
					where
						tanggal >= '".$tglMulai."'
						AND tanggal <=  '".$tglSelesai."'
				) and
				EXTRACT(ISODOW FROM tanggal) not in (6, 7)
			");

			$dataJumlahDinasLuar	=	$queryJumlahDinasLuar->row();

			$this->dataTable .= "<td>".$dataJumlahDinasLuar->jumlah."</td>";

			//////////////////////

			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$queryJumlahCutiTahunan	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					JAM_KERJA in ('CT','TB') and
					tanggal not in (
						select
							tanggal
						from
							s_hari_libur
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."'
					) and
					EXTRACT(ISODOW FROM tanggal) not in (6, 7)
				");
			}
			# end perwali januari 2018
			else {
				$queryJumlahCutiTahunan	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					JAM_KERJA in ('CT') and
					tanggal not in (
						select
							tanggal
						from
							s_hari_libur
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."'
					) and
					EXTRACT(ISODOW FROM tanggal) not in (6, 7)
				");
			}

			$dataJumlahCutiTahunan	=	$queryJumlahCutiTahunan->row();

			# start perwali januari 2018
			if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
				$skorJumlahCutiTahunan	=	100 - ($dataJumlahCutiTahunan->jumlah * 0);
			}
			# end perwali januari 2018

			$this->dataTable .= "<td>".$dataJumlahCutiTahunan->jumlah."</td>";

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

			$this->dataTable .= "<td>".$skorTPP."</td>";
			$this->dataTable .= "</tr>";


			/** INSERT LAP SKOR KEHADIRAN DETIL */
			$data = [
				'nip'			=> $dataPegawai->nip,
				'nama'			=> $dataPegawai->nama,
				'golongan'		=> $dataPegawai->nama_golongan,
				'jabatan'		=> $unor,
				'skor'			=> json_encode($skor_detil),
				'jml_hadir'		=> $jumlahMasukTotal->jumlah,
				'jml_dl'		=> $dataJumlahDinasLuar->jumlah,
				'jml_cuti'		=> $dataJumlahCutiTahunan->jumlah,
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'			=> $pns_get,
				'meninggal' => $seda_input
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

		$this->load->view('cetak/lap_skor_view',[
			'dataTable' => $this->dataTable
		]);
	}

	public function printed($hari_kerja, $bulan, $tahun, $id_instansi, $pns) {
		echo "select * from lap_skor_kehadiran_detil
			where bulan = '$bulan'
			and tahun = '$tahun'
			and id_instansi = '$id_instansi'
			and pns = '$pns'
			and deleted_at is null";
			die;
		$detil_laporan	=	$this->db->query("
			select * from lap_skor_kehadiran_detil
			where bulan = '$bulan'
			and tahun = '$tahun'
			and id_instansi = '$id_instansi'
			and pns = '$pns'
			and deleted_at is null
		")->result();

		foreach ($detil_laporan as $key => $value) {
			$this->dataTable .= "<tr>";

			$this->dataTable .= "<td>XXX".($key+1)."</td>";
			$this->dataTable .= "<td>".$value->nama."</td>";
			$this->dataTable .= "<td>".$value->nip."</td>";
			$this->dataTable .= "<td>".$value->golongan."</td>";
			$this->dataTable .= "<td>".$value->jabatan."</td>";

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

		$this->load->view('cetak/lap_skor_view',[
			'dataTable' => $this->dataTable
		]);
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
					pjh.urut,
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
				$dataTable .= "<td>".$dataPegawai->nama_golongan."</td>";

				if($dataPegawai->nama_jabatan =='Staf'){
					$unor = $dataPegawai->nama_jabatan." - ".$dataPegawai->nama_rumpun_jabatan;
				}
				else{
					$unor = $dataPegawai->nama_jabatan;
				}

				$dataTable .= "<td>".$unor." </td>";

				# start perwali desember 2018
				if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
					$queryJumlahLambatKurangLima	=	$this->db->query("
					select
						count(*) as jumlah
					from
						data_mentah
					where
						tanggal >= '".$tglMulai."'
						AND tanggal <=  '".$tglSelesai."' and
						id_pegawai = '".$dataPegawai->id_pegawai."' and
						datang_telat > 0 and datang_telat <= 5
					");

					$dataJumlahLambatKurangLima		=	$queryJumlahLambatKurangLima->row();
					$skorLambatKurangLima			=	100 - ($dataJumlahLambatKurangLima->jumlah * 0.25);

					$skor_detil[] = [
						'frek'	=> $dataJumlahLambatKurangLima->jumlah,
						'skor' 	=> $skorLambatKurangLima
					];
				}
				# end perwali database 2018


				$awallimabelas = 0;
				# start perwali desember 2018
				if(($this->input->get("bulan") == 12 && $this->input->get("tahun") == 2018) || $this->input->get("tahun") == 2019) {
					$awallimabelas = 5;
				}
				# end perwali database 2018

				$queryJumlahLambatKurangLimaBelas	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					datang_telat > $awallimabelas and datang_telat <= 15
				");

				$dataJumlahLambatKurangLimaBelas	=	$queryJumlahLambatKurangLimaBelas->row();
				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorLambatKurangLimaBelas			=	100 - ($dataJumlahLambatKurangLimaBelas->jumlah * 0.25);
				}
				# end perwali januari 2018
				else {
					$skorLambatKurangLimaBelas			=	100 - ($dataJumlahLambatKurangLimaBelas->jumlah * 1);
				}


				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahLambatKurangLimaBelas->jumlah,
					'skor' 	=> $skorLambatKurangLimaBelas
				];


				$queryJumlahLambatKurangSatuJam		=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					datang_telat > 15 and datang_telat <= 60
				");

				$dataJumlahLambatKurangSatuJam	=	$queryJumlahLambatKurangSatuJam->row();
				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorLambatKurangSatuJam			=	100 - ($dataJumlahLambatKurangSatuJam->jumlah * 1);
				}
				# end perwali januari 2018
				else {
					$skorLambatKurangSatuJam			=	100 - ($dataJumlahLambatKurangSatuJam->jumlah * 2);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] =  [
					'frek'	=> $dataJumlahLambatKurangSatuJam->jumlah,
					'skor' 	=> $skorLambatKurangSatuJam
				];

				$queryJumlahLambatKurangDuaJam		=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					datang_telat > 60 and datang_telat <= 120
				");

				$dataJumlahLambatKurangDuaJam	=	$queryJumlahLambatKurangDuaJam->row();
				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorLambatKurangDuaJam			=	100 - ($dataJumlahLambatKurangDuaJam->jumlah * 2);
				}
				# end perwali januari 2018
				else {
					$skorLambatKurangDuaJam			=	100 - ($dataJumlahLambatKurangDuaJam->jumlah * 3);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahLambatKurangDuaJam->jumlah,
					'skor' 	=> $skorLambatKurangDuaJam
				];

				$queryJumlahLambatKurangTigaJam		=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					datang_telat > 120 and datang_telat <= 180
				");

				$dataJumlahLambatKurangTigaJam	=	$queryJumlahLambatKurangTigaJam->row();
				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorLambatKurangTigaJam			=	100 - ($dataJumlahLambatKurangTigaJam->jumlah * 3);
				}
				# end perwali januari 2018
				else {
					$skorLambatKurangTigaJam			=	100 - ($dataJumlahLambatKurangTigaJam->jumlah * 4);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahLambatKurangTigaJam->jumlah,
					'skor' 	=> $skorLambatKurangTigaJam
				];

				$queryJumlahLambatKurangFull	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					datang_telat > 180
				");

				$dataJumlahLambatKurangFull	=	$queryJumlahLambatKurangFull->row();
				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorLambatKurangFull			=	100 - ($dataJumlahLambatKurangFull->jumlah * 4);
				}
				# end perwali januari 2018
				else {
					$skorLambatKurangFull			=	100 - ($dataJumlahLambatKurangFull->jumlah * 5);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahLambatKurangFull->jumlah,
					'skor' 	=> $skorLambatKurangFull
				];


				$dataTable .= "<td>".$dataJumlahLambatKurangLimaBelas->jumlah."</td>";
				$dataTable .= "<td>".$skorLambatKurangLimaBelas."</td>";
				$dataTable .= "<td>".$dataJumlahLambatKurangSatuJam->jumlah."</td>";
				$dataTable .= "<td>".$skorLambatKurangSatuJam."</td>";
				$dataTable .= "<td>".$dataJumlahLambatKurangDuaJam->jumlah."</td>";
				$dataTable .= "<td>".$skorLambatKurangDuaJam."</td>";
				$dataTable .= "<td>".$dataJumlahLambatKurangTigaJam->jumlah."</td>";
				$dataTable .= "<td>".$skorLambatKurangTigaJam."</td>";
				$dataTable .= "<td>".$dataJumlahLambatKurangFull->jumlah."</td>";
				$dataTable .= "<td>".$skorLambatKurangFull."</td>";



				//// pulang cepat

				$queryJumlahCepatKurangLimaBelas	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					pulang_cepat > 0 and pulang_cepat <= 15
				");

				$dataJumlahCepatKurangLimaBelas	=	$queryJumlahCepatKurangLimaBelas->row();
				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorCepatKurangLimaBelas		=	100 - ($dataJumlahCepatKurangLimaBelas->jumlah * 0.25);
				}
				# end perwali januari 2018
				else {
					$skorCepatKurangLimaBelas		=	100 - ($dataJumlahCepatKurangLimaBelas->jumlah * 1);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahCepatKurangLimaBelas->jumlah,
					'skor' 	=> $skorCepatKurangLimaBelas
				];

				$queryJumlahCepatKurangSatuJam		=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					pulang_cepat > 15 and pulang_cepat <=60
				");

				$dataJumlahCepatKurangSatuJam	=	$queryJumlahCepatKurangSatuJam->row();
				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorCepatKurangSatuJam		=	100 - ($dataJumlahCepatKurangSatuJam->jumlah * 1);
				}
				# end perwali januari 2018
				else {
					$skorCepatKurangSatuJam		=	100 - ($dataJumlahCepatKurangSatuJam->jumlah * 2);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahCepatKurangSatuJam->jumlah,
					'skor' 	=> $skorCepatKurangSatuJam
				];

				$queryJumlahCepatKurangDuaJam		=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					pulang_cepat > 60 and pulang_cepat <= 120
				");

				$dataJumlahCepatKurangDuaJam	=	$queryJumlahCepatKurangDuaJam->row();
				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorCepatKurangDuaJam		=	100 - ($dataJumlahCepatKurangDuaJam->jumlah * 2);
				}
				# end perwali januari 2018
				else {
					$skorCepatKurangDuaJam		=	100 - ($dataJumlahCepatKurangDuaJam->jumlah * 3);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahCepatKurangDuaJam->jumlah,
					'skor' 	=> $skorCepatKurangDuaJam
				];

				$queryJumlahCepatKurangTigaJam		=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					pulang_cepat > 120 and pulang_cepat <=180
				");

				$dataJumlahCepatKurangTigaJam	=	$queryJumlahCepatKurangTigaJam->row();
				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorCepatKurangTigaJam		=	100 - ($dataJumlahCepatKurangTigaJam->jumlah * 3);
				}
				# end perwali januari 2018
				else {
					$skorCepatKurangTigaJam		=	100 - ($dataJumlahCepatKurangTigaJam->jumlah * 4);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahCepatKurangTigaJam->jumlah,
					'skor' 	=> $skorCepatKurangTigaJam
				];

				$queryJumlahCepatKurangFull	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					pulang_cepat > 180
				");

				$dataJumlahCepatKurangFull	=	$queryJumlahCepatKurangFull->row();
				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorCepatKurangFull		=	100 - ($dataJumlahCepatKurangFull->jumlah * 4);
				}
				# end perwali januari 2018
				else {
					$skorCepatKurangFull		=	100 - ($dataJumlahCepatKurangFull->jumlah * 5);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahCepatKurangFull->jumlah,
					'skor' 	=> $skorCepatKurangFull
				];

				$dataTable .= "<td>".$dataJumlahCepatKurangLimaBelas->jumlah."</td>";
				$dataTable .= "<td>".$skorCepatKurangLimaBelas."</td>";
				$dataTable .= "<td>".$dataJumlahCepatKurangSatuJam->jumlah."</td>";
				$dataTable .= "<td>".$skorCepatKurangSatuJam."</td>";
				$dataTable .= "<td>".$dataJumlahCepatKurangDuaJam->jumlah."</td>";
				$dataTable .= "<td>".$skorCepatKurangDuaJam."</td>";
				$dataTable .= "<td>".$dataJumlahCepatKurangTigaJam->jumlah."</td>";
				$dataTable .= "<td>".$skorCepatKurangTigaJam."</td>";
				$dataTable .= "<td>".$dataJumlahCepatKurangFull->jumlah."</td>";
				$dataTable .= "<td>".$skorCepatKurangFull."</td>";



				$queryJumlahSakit	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					JAM_KERJA in ('SK','CS')  and
					tanggal not in (
						select
							tanggal
						from
							s_hari_libur
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."'
					) and
					EXTRACT(ISODOW FROM tanggal) not in (6, 7)
				");

				$dataJumlahSakit	=	$queryJumlahSakit->row();
				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorJumlahSakit		=	100 - ($dataJumlahSakit->jumlah * 1);
				}
				# end perwali januari 2018
				else {
					$skorJumlahSakit		=	100 - ($dataJumlahSakit->jumlah * 2);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahSakit->jumlah,
					'skor' 	=> $skorJumlahSakit
				];

				$dataTable .= "<td>".$dataJumlahSakit->jumlah."</td>";
				$dataTable .= "<td>".$skorJumlahSakit."</td>";

				//////////////////////////

				$queryJumlahCutiBesar	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					JAM_KERJA in ('CAP','CM','CH')	and
					tanggal not in (
						select
							tanggal
						from
							s_hari_libur
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."'
					) and
					EXTRACT(ISODOW FROM tanggal) not in (6, 7)
				");
				//echo $dataJumlahCutiBesar->jumlah;
				$dataJumlahCutiBesar	=	$queryJumlahCutiBesar->row();
				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorJumlahCutiBesar	=	100 - ($dataJumlahCutiBesar->jumlah * 3);
				}
				# end perwali januari 2018
				else {
					$skorJumlahCutiBesar	=	100 - ($dataJumlahCutiBesar->jumlah * 4);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahCutiBesar->jumlah,
					'skor' 	=> $skorJumlahCutiBesar
				];

				$dataTable .= "<td>".$dataJumlahCutiBesar->jumlah."</td>";
				$dataTable .= "<td>".$skorJumlahCutiBesar."</td>";


				////////////////////////

				$queryJumlahTidakHadirSah	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					JAM_KERJA in ('I') and
					tanggal not in (
						select
							tanggal
						from
							s_hari_libur
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."'
					) and
					EXTRACT(ISODOW FROM tanggal) not in (6, 7)
				");

				$dataJumlahTidakHadirSah	=	$queryJumlahTidakHadirSah->row() ;
				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorJumlahTidakHadirSah	=	100 - ($dataJumlahTidakHadirSah->jumlah * 5);
				}
				# end perwali januari 2018
				else {
					$skorJumlahTidakHadirSah		=	100 - ($dataJumlahTidakHadirSah->jumlah * 5);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahTidakHadirSah->jumlah,
					'skor' 	=> $skorJumlahTidakHadirSah
				];



				$dataTable .= "<td>".$dataJumlahTidakHadirSah->jumlah."</td>";
				$dataTable .= "<td>".$skorJumlahTidakHadirSah."</td>";


				////////////////////////

				$queryJumlahTidakHadirTidakSah	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					kode_masuk in ('M') and
					tanggal not in (
						select
							tanggal
						from
							s_hari_libur
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."'
					) and
					EXTRACT(ISODOW FROM tanggal) not in (6, 7)
				");

				$dataJumlahTidakHadirTidakSah	=	$queryJumlahTidakHadirTidakSah->row();
				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorJumlahTidakHadirTidakSah	=	100 - ($dataJumlahTidakHadirTidakSah->jumlah * 6);
				}
				# end perwali januari 2018
				else {
					$skorJumlahTidakHadirTidakSah	=	100 - ($dataJumlahTidakHadirTidakSah->jumlah * 6);
				}

				//untuk insert ke lap_skor_kehadiran_detail
				$skor_detil[] = [
					'frek'	=> $dataJumlahTidakHadirTidakSah->jumlah,
					'skor' 	=> $skorJumlahTidakHadirTidakSah
				];


				$dataTable .= "<td>".$dataJumlahTidakHadirTidakSah->jumlah."</td>";
				$dataTable .= "<td>".$skorJumlahTidakHadirTidakSah."</td>";

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

				$dataTable .= "<td>".number_format($skorTotal)."</td>";

				$dataTable .= "<td>".$jumlahMasuk."</td>";

				/////////////////////////

				/****ini Lawas

				$queryJumlahHadirTotal	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					finger_masuk is not null  and kode_masuk ='H' and
					tanggal not in (
						select
							tanggal
						from
							s_hari_libur
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."'
					) and
					EXTRACT(ISODOW FROM tanggal) not in (6, 7)

				");
				**/

				$queryJumlahHadirTotal	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					kode_masuk ='H'

				");
				$jumlahMasukTotal	=	$queryJumlahHadirTotal->row();

				$dataTable .= "<td>".$jumlahMasukTotal->jumlah."</td>";

				//////////////////////


				$queryJumlahDinasLuar	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$tglMulai."'
					AND tanggal <=  '".$tglSelesai."' and
					id_pegawai = '".$dataPegawai->id_pegawai."' and
					JAM_KERJA in ('DL','DK') and
					tanggal not in (
						select
							tanggal
						from
							s_hari_libur
						where
							tanggal >= '".$tglMulai."'
							AND tanggal <=  '".$tglSelesai."'
					) and
					EXTRACT(ISODOW FROM tanggal) not in (6, 7)
				");

				$dataJumlahDinasLuar	=	$queryJumlahDinasLuar->row();

				$dataTable .= "<td>".$dataJumlahDinasLuar->jumlah."</td>";

				//////////////////////

				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$queryJumlahCutiTahunan	=	$this->db->query("
					select
						count(*) as jumlah
					from
						data_mentah
					where
						tanggal >= '".$tglMulai."'
						AND tanggal <=  '".$tglSelesai."' and
						id_pegawai = '".$dataPegawai->id_pegawai."' and
						JAM_KERJA in ('CT','TB') and
						tanggal not in (
							select
								tanggal
							from
								s_hari_libur
							where
								tanggal >= '".$tglMulai."'
								AND tanggal <=  '".$tglSelesai."'
						) and
						EXTRACT(ISODOW FROM tanggal) not in (6, 7)
					");
				}
				# end perwali januari 2018
				else {
					$queryJumlahCutiTahunan	=	$this->db->query("
					select
						count(*) as jumlah
					from
						data_mentah
					where
						tanggal >= '".$tglMulai."'
						AND tanggal <=  '".$tglSelesai."' and
						id_pegawai = '".$dataPegawai->id_pegawai."' and
						JAM_KERJA in ('CT') and
						tanggal not in (
							select
								tanggal
							from
								s_hari_libur
							where
								tanggal >= '".$tglMulai."'
								AND tanggal <=  '".$tglSelesai."'
						) and
						EXTRACT(ISODOW FROM tanggal) not in (6, 7)
					");
				}

				$dataJumlahCutiTahunan	=	$queryJumlahCutiTahunan->row();

				# start perwali januari 2018
				if($this->input->get("bulan") == 01 && $this->input->get("tahun") == 2018) {
					$skorJumlahCutiTahunan	=	100 - ($dataJumlahCutiTahunan->jumlah * 0);
				}
				# end perwali januari 2018

				$dataTable .= "<td>".$dataJumlahCutiTahunan->jumlah."</td>";

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


				/** INSERT LAP SKOR KEHADIRAN DETIL */
				$data = [
					'nip'			=> $dataPegawai->nip,
					'nama'			=> $dataPegawai->nama,
					'golongan'		=> $dataPegawai->nama_golongan,
					'jabatan'		=> $unor,
					'skor'			=> json_encode($skor_detil),
					'jml_hadir'		=> $jumlahMasukTotal->jumlah,
					'jml_dl'		=> $dataJumlahDinasLuar->jumlah,
					'jml_cuti'		=> $dataJumlahCutiTahunan->jumlah,
					'bulan'			=> $bulan_get,
					'tahun'			=> $tahun_get,
					'id_instansi'	=> $id_instansi_get,
					'pns'			=> $pns_get,
					'meninggal'	=> $seda_input
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
}
