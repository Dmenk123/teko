<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lap_skor extends CI_Controller {

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


		$this->dataTables = "";

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
		
		if (substr($data_kode_sik->nama, 0, 9) != 'Kecamatan') {
			$kode_instansi_all = $this->input->get('id_instansi');
			$whereQuery = "pukh.kode_instansi = '".$kode_instansi_all."'".$wherePns;
			
		}else{
			$kode_instansi_all = substr($this->input->get('id_instansi'), 0, 5);
			$whereQuery = "pukh.kode_instansi LIKE '".$kode_instansi_all.'%'."'".$wherePns;
		}

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
			".$whereQuery."
		order by
			pjh.urut,
			peh.kode_eselon,
			pgh.kode_golongan desc,
			m.nip

		");
		$this->dataPegawai	=	$queryPegawai->result();
		//echo $this->db->last_query();
		$dataTables = "";

		$i=1;
		foreach($this->dataPegawai as $dataPegawai){



			//echo $jumlahLibur."<br>";
			$dataTables .= "<tr>";
			$dataTables .= "<td>".$i."</td>";
			$dataTables .= "<td>".$dataPegawai->nama."</td>";
			$dataTables .= "<td>".$dataPegawai->nip."</td>";
			$dataTables .= "<td>".$dataPegawai->nama_golongan."</td>";

			if($dataPegawai->nama_jabatan =='Staf'){
				$unor = $dataPegawai->nama_jabatan." - ".$dataPegawai->nama_rumpun_jabatan;
			}
			else{
				$unor = $dataPegawai->nama_jabatan;
			}

			$dataTables .= "<td>".$unor." </td>";



			$queryJumlahLambatKurangLimaBelas	=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				tanggal >= '".$tglMulai."'
				AND tanggal <=  '".$tglSelesai."' and
				id_pegawai = '".$dataPegawai->id_pegawai."' and
				datang_telat > 0 and datang_telat <= 15
			");

			$dataJumlahLambatKurangLimaBelas	=	$queryJumlahLambatKurangLimaBelas->row();
			$skorLambatKurangLimaBelas			=	100 - ($dataJumlahLambatKurangLimaBelas->jumlah * 1);

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
			$skorLambatKurangSatuJam		=	100 - ($dataJumlahLambatKurangSatuJam->jumlah * 2);

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
			$skorLambatKurangDuaJam		=	100 - ($dataJumlahLambatKurangDuaJam->jumlah * 3);

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
			$skorLambatKurangTigaJam		=	100 - ($dataJumlahLambatKurangTigaJam->jumlah * 4);

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
			$skorLambatKurangFull		=	100 - ($dataJumlahLambatKurangFull->jumlah * 5);





			$dataTables .= "<td>".$dataJumlahLambatKurangLimaBelas->jumlah."</td>";
			$dataTables .= "<td>".$skorLambatKurangLimaBelas."</td>";
			$dataTables .= "<td>".$dataJumlahLambatKurangSatuJam->jumlah."</td>";
			$dataTables .= "<td>".$skorLambatKurangSatuJam."</td>";
			$dataTables .= "<td>".$dataJumlahLambatKurangDuaJam->jumlah."</td>";
			$dataTables .= "<td>".$skorLambatKurangDuaJam."</td>";
			$dataTables .= "<td>".$dataJumlahLambatKurangTigaJam->jumlah."</td>";
			$dataTables .= "<td>".$skorLambatKurangTigaJam."</td>";
			$dataTables .= "<td>".$dataJumlahLambatKurangFull->jumlah."</td>";
			$dataTables .= "<td>".$skorLambatKurangFull."</td>";



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
			$skorCepatKurangLimaBelas			=	100 - ($dataJumlahCepatKurangLimaBelas->jumlah * 1);

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
			$skorCepatKurangSatuJam		=	100 - ($dataJumlahCepatKurangSatuJam->jumlah * 2);

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
			$skorCepatKurangDuaJam		=	100 - ($dataJumlahCepatKurangDuaJam->jumlah * 3);

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
			$skorCepatKurangTigaJam		=	100 - ($dataJumlahCepatKurangTigaJam->jumlah * 4);

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
			$skorCepatKurangFull		=	100 - ($dataJumlahCepatKurangFull->jumlah * 5);

			$dataTables .= "<td>".$dataJumlahCepatKurangLimaBelas->jumlah."</td>";
			$dataTables .= "<td>".$skorCepatKurangLimaBelas."</td>";
			$dataTables .= "<td>".$dataJumlahCepatKurangSatuJam->jumlah."</td>";
			$dataTables .= "<td>".$skorCepatKurangSatuJam."</td>";
			$dataTables .= "<td>".$dataJumlahCepatKurangDuaJam->jumlah."</td>";
			$dataTables .= "<td>".$skorCepatKurangDuaJam."</td>";
			$dataTables .= "<td>".$dataJumlahCepatKurangTigaJam->jumlah."</td>";
			$dataTables .= "<td>".$skorCepatKurangTigaJam."</td>";
			$dataTables .= "<td>".$dataJumlahCepatKurangFull->jumlah."</td>";
			$dataTables .= "<td>".$skorCepatKurangFull."</td>";



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
			$skorJumlahSakit		=	100 - ($dataJumlahSakit->jumlah * 2);

			$dataTables .= "<td>".$dataJumlahSakit->jumlah."</td>";
			$dataTables .= "<td>".$skorJumlahSakit."</td>";

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
			$skorJumlahCutiBesar	=	100 - ($dataJumlahCutiBesar->jumlah * 4);


			$dataTables .= "<td>".$dataJumlahCutiBesar->jumlah."</td>";
			$dataTables .= "<td>".$skorJumlahCutiBesar."</td>";


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
			$skorJumlahTidakHadirSah	=	100 - ($dataJumlahTidakHadirSah->jumlah * 5);





			$dataTables .= "<td>".$dataJumlahTidakHadirSah->jumlah."</td>";
			$dataTables .= "<td>".$skorJumlahTidakHadirSah."</td>";


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
			$skorJumlahTidakHadirTidakSah	=	100 - ($dataJumlahTidakHadirTidakSah->jumlah * 6);


			$dataTables .= "<td>".$dataJumlahTidakHadirTidakSah->jumlah."</td>";
			$dataTables .= "<td>".$skorJumlahTidakHadirTidakSah."</td>";

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




			$dataTables .= "<td>".number_format($skorTotal)."</td>";




			$dataTables .= "<td>".$jumlahMasuk."</td>";

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

			$dataTables .= "<td>".$jumlahMasukTotal->jumlah."</td>";

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

			$dataTables .= "<td>".$dataJumlahDinasLuar->jumlah."</td>";


			//////////////////////


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

			$dataJumlahCutiTahunan	=	$queryJumlahCutiTahunan->row();

			$dataTables .= "<td>".$dataJumlahCutiTahunan->jumlah."</td>";


			///////


			$skorTPP = 100 - (1400 - $skorTotal);

			$dataTables .= "<td>".$skorTPP." ($skorTotal)</td>";
			$dataTables .= "</tr>";

			$i++;
		}

		$this->load->view('cetak/lap_skor_view', ['dataTable' => $dataTables]);
	}
}
