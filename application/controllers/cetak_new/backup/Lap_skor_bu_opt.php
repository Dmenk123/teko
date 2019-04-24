<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lap_skor extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model','log_laporan_model', 'lap_skor_kehadiran_model', 'lap_skor_kehadiran_detil_model']);
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

		/** JIKA DINAS PENDIDIKAN!!! */
		if ($this->input->get('id_instansi') == '5.09.00.93.00') {
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
							LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".$tglSelesai."' and m.id = h.id_pegawai and h.excel = 't'
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
		} else {
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
		}

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

	public function generate() {
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
			select * from lap_skor_kehadiran
			where bulan = '$bulan_get'
			and tahun = '$tahun_get'
			and id_instansi = '$id_instansi_get'
			and pns = '$pns_get'
			and deleted_at is null
		");

        if($this->input->get("pns_get") == 'y'){
            $wherePns 	= " and m.kode_status_pegawai !='5'";
        }
        else
        {
			$wherePns 	= " and m.kode_status_pegawai ='5'";
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

        $tanggal	=	$tahun_get."-".$bulan_get."-01";
        $tglSelesai 	= date('Y-m-t', strtotime($tanggal));

        $tanggal2	=	"01/".$bulan_get."/".$tahun_get;
        $tglSelesai2 	= date('t/m/Y', strtotime($tanggal));

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
							h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
						FROM
							m_pegawai_unit_kerja_histori h
							LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
							LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".$tglSelesai."' and m.id = h.id_pegawai and h.excel = 't'
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
		}		


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

				$this->lap_skor_kehadiran_model->update($where, ['deleted_at' => date('Y-m-d H:i:s')]);
				$this->lap_skor_kehadiran_detil_model->update($where, ['deleted_at' => date('Y-m-d H:i:s')]);
				#end

	            /** INSERT KE LAP_SKOR_KEHADIRAN */
	            $data_rekap_instansi = [
					'bulan'			=> $bulan_get,
					'tahun'			=> $tahun_get,
					'id_instansi'	=> $id_instansi_get,
					'pns'			=> $pns_get,
					'id_pegawai'	=> $this->session->userdata('id_karyawan'),
				];
				$this->lap_skor_kehadiran_model->insert($data_rekap_instansi);
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
		$this->load->model('lap_skor_kehadiran_model', 'lap_skor_kehadiran_detil_model');
		$this->load->library('konversi_menit');

		$id_pegawai 		= $this->input->post('id_pegawai');
		$tgl_mulai   		= $this->input->post('tgl_mulai_peg');
		$tgl_selesai 		= $this->input->post('tgl_akhir_peg');
		$instansiRaw 	  	= $this->input->post("id_instansi_peg");
		$nama_jabatan       = $this->input->post('jabatan');
		$rumpun_jabatan     = $this->input->post('rumpun_jabatan');
		$pns 				= $this->input->post('pns');
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

			////////////////////////////// jumlah lambat kurang lima /////////////////////////////////////////////
			# start perwali desember 2018
			if(($this->input->post("bulan") == 12 && $this->input->post("tahun") == 2018) || $this->input->post("tahun") == 2019) {
				$queryJumlahLambatKurangLima = $this->db->query("
					select
						count(*) as jumlah
					from
						data_mentah
					where
						tanggal >= '".$mulai."'
						AND tanggal <=  '".$akhir."' and
						id_pegawai = '".$id_pegawai."' and
						datang_telat > 0 and datang_telat <= 5
				");

				$dataJumlahLambatKurangLima		=	$queryJumlahLambatKurangLima->row();
				$skorLambatKurangLima			=	100 - ($dataJumlahLambatKurangLima->jumlah * 0.25);

				$skor_detil[] = [
					'frek'	=> $dataJumlahLambatKurangLima->jumlah,
					'skor' 	=> $skorLambatKurangLima
				];
				# end perwali database 2018
			}
			////////////////////////////// end jumlah lambat kurang lima /////////////////////////////////////////////

			////////////////////////////// jumlah lambat kurang lima belas ///////////////////////////////////////////
			$awallimabelas = 0;
			# start perwali desember 2018
			if(($this->input->post("bulan") == 12 && $this->input->post("tahun") == 2018) || $this->input->post("tahun") == 2019) {
				$awallimabelas = 5;
			}
			# end perwali database 2018

			$queryJumlahLambatKurangLimaBelas	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$mulai."'
					AND tanggal <=  '".$akhir."' and
					id_pegawai = '".$id_pegawai."' and
					datang_telat > $awallimabelas and datang_telat <= 15
			");

			$dataJumlahLambatKurangLimaBelas	=	$queryJumlahLambatKurangLimaBelas->row();
			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			////////////////////////////// end jumlah lambat kurang lima belas /////////////////////////////////////////

			////////////////////////////// jumlah lambat kurang satu jam ///////////////////////////////////////////////
			$queryJumlahLambatKurangSatuJam		=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$mulai."'
					AND tanggal <=  '".$akhir."' and
					id_pegawai = '".$id_pegawai."' and
					datang_telat > 15 and datang_telat <= 60
			");

			$dataJumlahLambatKurangSatuJam	=	$queryJumlahLambatKurangSatuJam->row();
			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			////////////////////////////// end jumlah lambat kurang satu jam ///////////////////////////////////////////////

			////////////////////////////// jumlah lambat kurang dua jam ///////////////////////////////////////////////
			$queryJumlahLambatKurangDuaJam		=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$mulai."'
					AND tanggal <=  '".$akhir."' and
					id_pegawai = '".$id_pegawai."' and
					datang_telat > 60 and datang_telat <= 120
			");

			$dataJumlahLambatKurangDuaJam	=	$queryJumlahLambatKurangDuaJam->row();
			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			////////////////////////////// end jumlah lambat kurang dua jam ///////////////////////////////////////////////

			////////////////////////////// jumlah lambat kurang tiga jam //////////////////////////////////////////////////
			$queryJumlahLambatKurangTigaJam		=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$mulai."'
					AND tanggal <=  '".$akhir."' and
					id_pegawai = '".$id_pegawai."' and
					datang_telat > 120 and datang_telat <= 180
			");

			$dataJumlahLambatKurangTigaJam	=	$queryJumlahLambatKurangTigaJam->row();
			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			////////////////////////////// end jumlah lambat kurang tiga jam //////////////////////////////////////////////////

			////////////////////////////// jumlah lambat kurang full /////////////////////////////////////////////////////////
			$queryJumlahLambatKurangFull	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$mulai."'
					AND tanggal <=  '".$akhir."' and
					id_pegawai = '".$id_pegawai."' and
					datang_telat > 180
			");

			$dataJumlahLambatKurangFull	=	$queryJumlahLambatKurangFull->row();
			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			////////////////////////////// end jumlah lambat kurang full //////////////////////////////////////////////////////

			//////////////////////////////// pulang cepat kurang lima belas ///////////////////////////////////////
			$queryJumlahCepatKurangLimaBelas	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$mulai."'
					AND tanggal <=  '".$akhir."' and
					id_pegawai = '".$id_pegawai."' and
					pulang_cepat > 0 and pulang_cepat <= 15
			");

			$dataJumlahCepatKurangLimaBelas	=	$queryJumlahCepatKurangLimaBelas->row();
			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			//////////////////////////////// end pulang cepat kurang lima belas ///////////////////////////////////////

			//////////////////////////////// pulang cepat kurang satu jam //////////////////////////////////////////
			$queryJumlahCepatKurangSatuJam		=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$mulai."'
					AND tanggal <=  '".$akhir."' and
					id_pegawai = '".$id_pegawai."' and
					pulang_cepat > 15 and pulang_cepat <=60
			");

			$dataJumlahCepatKurangSatuJam	=	$queryJumlahCepatKurangSatuJam->row();
			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			//////////////////////////////// end pulang cepat kurang satu jam ///////////////////////////////////////

			//////////////////////////////// pulang cepat kurang dua jam ///////////////////////////////////////
			$queryJumlahCepatKurangDuaJam		=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$mulai."'
					AND tanggal <=  '".$akhir."' and
					id_pegawai = '".$id_pegawai."' and
					pulang_cepat > 60 and pulang_cepat <= 120
			");

			$dataJumlahCepatKurangDuaJam	=	$queryJumlahCepatKurangDuaJam->row();
			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			//////////////////////////////// end pulang cepat kurang dua jam ///////////////////////////////////////

			//////////////////////////////// pulang cepat kurang tiga jam ///////////////////////////////////////
			$queryJumlahCepatKurangTigaJam		=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$mulai."'
					AND tanggal <=  '".$akhir."' and
					id_pegawai = '".$id_pegawai."' and
					pulang_cepat > 120 and pulang_cepat <=180
			");

			$dataJumlahCepatKurangTigaJam	=	$queryJumlahCepatKurangTigaJam->row();
			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			//////////////////////////////// end pulang cepat kurang tiga jam ///////////////////////////////////////

			//////////////////////////////// pulang cepat kurang full ///////////////////////////////////////
			$queryJumlahCepatKurangFull	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$mulai."'
					AND tanggal <=  '".$akhir."' and
					id_pegawai = '".$id_pegawai."' and
					pulang_cepat > 180
			");

			$dataJumlahCepatKurangFull	=	$queryJumlahCepatKurangFull->row();
			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			////////////////////////////end pulang cepat kurang full/////////////////////////////////////

			/////////////////////////////sakit ///////////////////////////////////////////////////
			$queryJumlahSakit	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$mulai."'
					AND tanggal <=  '".$akhir."' and
					id_pegawai = '".$id_pegawai."' and
					JAM_KERJA in ('SK','CS')  and
					tanggal not in (
						select
							tanggal
						from
							s_hari_libur
						where
							tanggal >= '".$mulai."'
							AND tanggal <=  '".$akhir."'
					) and
					EXTRACT(ISODOW FROM tanggal) not in (6, 7)
			");

			$dataJumlahSakit	=	$queryJumlahSakit->row();
			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			/////////////////////////////end sakit ///////////////////////////////////////////////////

			///////////////////////////// cuti besar ///////////////////////////////////////////////////
			$queryJumlahCutiBesar	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$mulai."'
					AND tanggal <=  '".$akhir."' and
					id_pegawai = '".$id_pegawai."' and
					JAM_KERJA in ('CAP','CM','CH')	and
					tanggal not in (
						select
							tanggal
						from
							s_hari_libur
						where
							tanggal >= '".$mulai."'
							AND tanggal <=  '".$akhir."'
					) and
					EXTRACT(ISODOW FROM tanggal) not in (6, 7)
			");
			//echo $dataJumlahCutiBesar->jumlah;
			$dataJumlahCutiBesar	=	$queryJumlahCutiBesar->row();
			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			///////////////////////////// end cuti besar ///////////////////////////////////////////////////

			///////////////////////////// hadir tidak sah  ///////////////////////////////////////////////////
			$queryJumlahTidakHadirSah	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$mulai."'
					AND tanggal <=  '".$akhir."' and
					id_pegawai = '".$id_pegawai."' and
					JAM_KERJA in ('I') and
					tanggal not in (
						select
							tanggal
						from
							s_hari_libur
						where
							tanggal >= '".$mulai."'
							AND tanggal <=  '".$akhir."'
					) and
					EXTRACT(ISODOW FROM tanggal) not in (6, 7)
			");

			$dataJumlahTidakHadirSah	=	$queryJumlahTidakHadirSah->row() ;
			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			/////////////////////////////end hadir tidak sah////////////////////////////////////////

			//////////////////////////// tidak hadir tidak sah////////////////////////////////////////
			$queryJumlahTidakHadirTidakSah	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$mulai."'
					AND tanggal <=  '".$akhir."' and
					id_pegawai = '".$id_pegawai."' and
					kode_masuk in ('M')
			");

			$dataJumlahTidakHadirTidakSah	=	$queryJumlahTidakHadirTidakSah->row();
			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			////////////////////////////end tidak hadir tidak sah////////////////////////////////////////

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
			if(($this->input->post("bulan") == 12 && $this->input->post("tahun") == 2018) || $this->input->post("tahun") == 2019) {
				$skorTotal = $skorTotal + $skorLambatKurangLima;
			}
			# end perwali desember 2018

			////////////////////////////// jumlah hadir total/////////////////////
			$queryJumlahHadirTotal	=	$this->db->query("
			select
				count(*) as jumlah
			from
				data_mentah
			where
				(tanggal >= '".$mulai."'
				AND tanggal <=  '".$akhir."' and
				id_pegawai = '".$id_pegawai."') and
				(
					(
						jam_kerja::text != ''
						AND jadwal_masuk::text != ''
						AND finger_masuk::text != ''
						AND finger_masuk < jadwal_pulang
					)
				or
					(kode_masuk ='H')
				)
			");
			$jumlahMasukTotal	=	$queryJumlahHadirTotal->row();
			//////////////////////////////end jumlah hadir total/////////////////////

			////////////////////////////////jumlah dinas luar/////////////////////////
			$queryJumlahDinasLuar	=	$this->db->query("
				select
					count(*) as jumlah
				from
					data_mentah
				where
					tanggal >= '".$mulai."'
					AND tanggal <=  '".$akhir."' and
					id_pegawai = '".$id_pegawai."' and
					JAM_KERJA in ('DL','DK') and
					tanggal not in (
						select
							tanggal
						from
							s_hari_libur
						where
							tanggal >= '".$mulai."'
							AND tanggal <=  '".$akhir."'
					) and
					EXTRACT(ISODOW FROM tanggal) not in (6, 7)
				");

			$dataJumlahDinasLuar	=	$queryJumlahDinasLuar->row();
			////////////////////////////////end jumlah dinas luar/////////////////////////

			////////////////////////////// cuti tahunan /////////////////////
			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
				$queryJumlahCutiTahunan	=	$this->db->query("
					select
						count(*) as jumlah
					from
						data_mentah
					where
						tanggal >= '".$mulai."'
						AND tanggal <=  '".$akhir."' and
						id_pegawai = '".$id_pegawai."' and
						JAM_KERJA in ('CT','TB') and
						tanggal not in (
							select
								tanggal
							from
								s_hari_libur
							where
								tanggal >= '".$mulai."'
								AND tanggal <=  '".$akhir."'
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
						tanggal >= '".$mulai."'
						AND tanggal <=  '".$akhir."' and
						id_pegawai = '".$id_pegawai."' and
						JAM_KERJA in ('CT') and
						tanggal not in (
							select
								tanggal
							from
								s_hari_libur
							where
								tanggal >= '".$mulai."'
								AND tanggal <=  '".$akhir."'
						) and
						EXTRACT(ISODOW FROM tanggal) not in (6, 7)
				");
			}

			$dataJumlahCutiTahunan	=	$queryJumlahCutiTahunan->row();

			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
				$skorJumlahCutiTahunan	=	100 - ($dataJumlahCutiTahunan->jumlah * 0);
			}
			# end perwali januari 2018
			////////////////////////////// end cuti tahunan ///////////////////////////

			////////////////////////////////skor total /////////////////////////////////
			$v_skor_total = 1400;
			# start perwali desember 2018
			if(($this->input->post("bulan") == 12 && $this->input->post("tahun") == 2018) || $this->input->post("tahun") == 2019) {
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
			if(($this->input->post("bulan") == 12 && $this->input->post("tahun") == 2018) || $this->input->post("tahun") == 2019) {
				if($meninggal == 't'){
					$skorTPP = 100;
					$seda = " (Meninggal Dunia)";
					$seda_input = TRUE;
				}
			}
			# end perwali desember 2018
			//////////////////////////////// end skor total /////////////////////////////////

			$urutan = $this->input->post('urut2');

            /** INSERT LAP SKOR KEHADIRAN DETIL */
			$data = [
				'nip'			=> $this->input->post('nip'),
				'nama'			=> $this->input->post('nama'),
				'golongan'		=> $this->input->post('golongan'),
				'jabatan'		=> $unor,
				'skor'			=> json_encode($skor_detil),
				'jml_hadir'		=> $jumlahMasukTotal->jumlah,
				'jml_dl'		=> $dataJumlahDinasLuar->jumlah,
				'jml_cuti'		=> $dataJumlahCutiTahunan->jumlah,
				'bulan'			=> $thingga[1],
                'tahun'			=> $thingga[2],
                'id_instansi'	=> $instansiRaw,
				'pns'			=> $pns,
				'meninggal' 	=> $seda_input,
				'urut' 			=> $urutan
			];

			$this->lap_skor_kehadiran_detil_model->insert($data);
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
			'pns'			=> $this->input->post('pns_update'),
			'deleted_at'	=> null,
		];

		$this->lap_skor_kehadiran_model->update($where, ['finished_at' => date('Y-m-d H:i:s')]);
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
				select * from lap_skor_kehadiran_detil
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
}
