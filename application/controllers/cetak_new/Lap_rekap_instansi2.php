<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class lap_rekap_instansi2 extends CI_Controller {

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

		/** CEK APAKAH PERNAH PRINT LAPORAN */
		$bulan_get = $this->input->get('bulan');
		$tahun_get = $this->input->get('tahun');
		$id_instansi_get = $this->input->get('id_instansi');
		$pns_get = $this->input->get('pns');

		$queryCekSudahPrintLaporan	=	$this->db->query("
			select * from lap_rekap_instansi
			where bulan = '$bulan_get'
			and tahun = '$tahun_get'
			and id_instansi = '$id_instansi_get'
			and pns = '$pns_get'
			and deleted_at is null
		");

		if($queryCekSudahPrintLaporan->row()) {

			/** untuk cek apakah generate laporan benar2 selesai */
			$queryCekSudahFinishGenerateLaporan	= $this->db->query("
				select * from lap_rekap_instansi
				where bulan = '$bulan_get'
				and tahun = '$tahun_get'
				and id_instansi = '$id_instansi_get'
				and pns = '$pns_get'
				and deleted_at is null
				and finished_at is not null
			");
			#end

			if($queryCekSudahFinishGenerateLaporan->row() or $this->input->get('lanjut_cetak')) {
				$this->printed($bulan_get, $tahun_get, $id_instansi_get, $pns_get);
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
					select m.*, u.fullname from lap_rekap_instansi m
					join c_security_user_new u on m.id_pegawai = u.id
					where bulan = '$bulan_get'
					and tahun = '$tahun_get'
					and id_instansi = '$id_instansi_get'
					and pns = '$pns_get'
					and deleted_at is null
					and finished_at is null
				")->row_array();

				$laporanTergenerate	= $this->db->query("
					select * from lap_rekap_instansi_detil
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

				redirect('lap_rekap_instansi','refresh');
			}
			
		} else {
			$this->load->model([
				'Lap_rekap_instansi_model',
				'Lap_rekap_instansi_detil_model'
			]);

			$data = [
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'			=> $pns_get,
				'id_pegawai'	=> $this->session->userdata('id_karyawan'),
			];

			$this->Lap_rekap_instansi_model->insert($data);
		}
		#end

		$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
		// Tanggal pertama pada bulan ini
		$tglMulai 	= date('Y-m-01', strtotime($hari_ini));
		// Tanggal terakhir pada bulan ini
		$tglSelesai 	= date('Y-m-t', strtotime($hari_ini));

		// Tanggal pertama pada bulan ini
		$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
		// Tanggal terakhir pada bulan ini
		$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));
		
		$awal 		= strtotime($tglMulai);
		$akhir 		= strtotime($tglSelesai);

		$dt1 		= new DateTime($tglMulai);
		$dt2 		= new DateTime($tglSelesai);
		$jumlahHari = $dt1->diff($dt2) ;
		$jumlahHari = $jumlahHari->days + 1 ;

		$diff 		= abs($akhir-$awal);
		

		$kodeAwalDinas	=	substr($this->input->get('id_instansi'),0,4);

		if($this->input->get("pns") == 'y'){
			$wherePns 	= " and m.kode_status_pegawai < '5'";
		}
		else{
			$wherePns 	= " and m.kode_status_pegawai >='5'";
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
							h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi, h.excel
						FROM
							m_pegawai_unit_kerja_histori h
							LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
							LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".$tglMulai."' and m.id = h.id_pegawai and h.excel = 't'
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
					".$whereQuery." and pukh.excel = 't' and pukh.excel = 't'
				order by
				pjh.urut,
				peh.kode_eselon,
				pgh.kode_golongan desc,
				m.nip
			");
		}else{
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
					".$whereQuery."
				order by
				pjh.urut,
				peh.kode_eselon,
				pgh.kode_golongan desc,
				m.nip
			");
		}
		

		//echo $this->db->last_query();
		$this->dataPegawai	=	$queryPegawai->result();
        
       

		$i=1;
		$temp = array(); //array u/ data report
		foreach($this->dataPegawai as $dataPegawai)
		{
			$skor_detil = [];
			if($dataPegawai->nama_jabatan =='Staf'){
				$unor = $dataPegawai->nama_jabatan." - ".$dataPegawai->nama_rumpun_jabatan;
			}
			else{
				$unor = $dataPegawai->nama_jabatan;
			}
			
			$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
			// Tanggal pertama pada bulan ini
			$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
			// Tanggal terakhir pada bulan ini
			$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));

			$tanggalsabtu 		= "";
			$tanggalminggu 		= "";
			$tanggalSeninJumat 	= "";

			$hitungMTotal = 0;
			$hitungCHTotal = 0;
			$hitungCMTotal = 0;
			$hitungCTTotal = 0;
			$hitungCAPTotal = 0;
			$hitungDKTotal = 0;
			$hitungDLTotal = 0;
			$hitungITotal = 0;
			$hitungLPTotal = 0;
			$hitungMPPTotal = 0;
			$hitungSKTotal = 0;
			$hitungTBTotal = 0;
			$hitungUFTTotal = 0;

			$hitungMasukTotal 	= 0;
			$hitungJumlahKerjaTotal = 0;
			$hitungTelatTotal = 0;
			$hitungPulangCepatTotal = 0;

			$lemburSeninJumatTotal = 0;
			$lemburSeninJumatTotalOke = 0;
			$lemburSabtuTotal = 0;
			$lemburSabtuTotalOke = 0;
			$lemburMingguTotal = 0;
			$lemburMingguTotalOke = 0;

			$q_rekap_instansi = $this->db->query("
				SELECT 
					tgl::date as tanggal,
					extract(dow from tgl) as hari_tgl,
					dm.jadwal_masuk::text,
					dm.jadwal_pulang::text,
					finger_masuk::text,
					dm.kode_masuk,
					dm.kode_tidak_masuk,
					dm.jam_kerja, dm.keterangan,
					dm.lembur_diakui,
					dm.lembur,
					dm.datang_telat,
					dm.pulang_cepat
				FROM 
					generate_series('".$this->tgl_pertama."', '".$this->tgl_terakhir."', '1 day'::interval) tgl
				LEFT JOIN 
					data_mentah as dm ON tgl = dm.tanggal AND id_pegawai = '".$dataPegawai->id_pegawai."'
				order by tgl
			");
			$rekap_ins = $q_rekap_instansi->result_array();

			for($i=0; $i < count($rekap_ins); $i++) 
			{
				if($rekap_ins[$i]["kode_masuk"] == 'H') 
				{
					$hitungMasuk = 1;
					$text 		 = '1';
				}
				else
				{
					if($rekap_ins[$i]["jadwal_masuk"] <> '') 
					{
						if($rekap_ins[$i]["finger_masuk"] <> '') 
						{
							if(strtotime($rekap_ins[$i]["finger_masuk"]) < strtotime($rekap_ins[$i]["jadwal_pulang"])) {
								$hitungMasuk = 1;
								$text 		 = '1';
							}
							else {
								$hitungMasuk = 0;
								$text 		 = '0';
							}
						}
						else 
						{
							if($rekap_ins[$i]["jam_kerja"] <> '') {
								$hitungMasuk = 0;
								$text 		 = $rekap_ins[$i]["kode_tidak_masuk"];
							}
							else {
								$hitungMasuk = 0;
								$text 		 = '0';
							}
						}
					}
					else
					{
						if($rekap_ins[$i]["kode_tidak_masuk"] <> 'LB') {
							$hitungMasuk = 0;
							$text 		 = $rekap_ins[$i]["kode_tidak_masuk"];
						}
						else 
						{
							$arr_lb = array("LIBUR ROSTER DENGAN SURAT SESUAI FINGER","LIBUR ROSTER SESUAI SURAT LEMBUR","LIBUR ROSTER");
							if(in_array($rekap_ins[$i]["keterangan"], $arr_lb)) {
								$hitungMasuk = 0;
								$text 		 = $rekap_ins[$i]["kode_tidak_masuk"];
							}
							else 
							{
								$hitungMasuk = 0;
								$text 		 = '0';
							}
						}
					}
				}

				if($rekap_ins[$i]["jadwal_masuk"] <> '') {
					$hitungJumlahKerjaTotal += 1;
				}

				if ($rekap_ins[$i]['kode_tidak_masuk'] == 'M') {
					$hitungMTotal += 1;
				}elseif($rekap_ins[$i]['kode_tidak_masuk'] == 'CH'){
					$hitungCHTotal += 1;
				}elseif($rekap_ins[$i]['kode_tidak_masuk'] == 'CM'){
					$hitungCMTotal += 1;
				}elseif($rekap_ins[$i]['kode_tidak_masuk'] == 'CT'){
					$hitungCTTotal += 1;
				}elseif($rekap_ins[$i]['kode_tidak_masuk'] == 'CAP'){
					$hitungCAPTotal += 1;
				}elseif($rekap_ins[$i]['kode_tidak_masuk'] == 'DK'){
					$hitungDKTotal += 1;
				}elseif($rekap_ins[$i]['kode_tidak_masuk'] == 'DL'){
					$hitungDLTotal += 1;
				}elseif($rekap_ins[$i]['kode_tidak_masuk'] == 'I'){
					$hitungITotal += 1;
				}elseif($rekap_ins[$i]['kode_tidak_masuk'] == 'LP'){
					$hitungLPTotal += 1;
				}elseif($rekap_ins[$i]['kode_tidak_masuk'] == 'MPP'){
					$hitungMPPTotal += 1;
				}elseif($rekap_ins[$i]['kode_tidak_masuk'] == 'SK' || $rekap_ins[$i]['kode_tidak_masuk'] == 'CS'){
					$hitungSKTotal += 1;
				}elseif($rekap_ins[$i]['kode_tidak_masuk'] == 'TB'){
					$hitungTBTotal += 1;
				}elseif($rekap_ins[$i]['kode_tidak_masuk'] == 'UFT'){
					$hitungUFTTotal += 1;
				}

				if ($rekap_ins[$i]['datang_telat'] > 0) {
					$hitungTelatTotal += 1;
				}

				if ($rekap_ins[$i]['pulang_cepat'] > 0) {
					$hitungPulangCepatTotal += 1;
				}

				if ($rekap_ins[$i]['hari_tgl'] !== '6' && $rekap_ins[$i]['hari_tgl'] !== '0') {
					$lemburSeninJumatTotal += $rekap_ins[$i]['lembur'];
					$lemburSeninJumatTotalOke += $rekap_ins[$i]['lembur_diakui'];
				}

				if ($rekap_ins[$i]['hari_tgl'] === '6'){
					$lemburSabtuTotal += $rekap_ins[$i]['lembur'];
					$lemburSabtuTotalOke += $rekap_ins[$i]['lembur_diakui'];
				}

				if($rekap_ins[$i]['hari_tgl'] === '0'){
					$lemburMingguTotal += $rekap_ins[$i]['lembur'];
					$lemburMingguTotalOke += $rekap_ins[$i]['lembur_diakui'];
				}	


				$hitungMasukTotal += $hitungMasuk;
				$skor[] = $text;
			}//end loop data mentah

			$dataLEmburSeninJumatArray = $this->konversi_menit->hitung($lemburSeninJumatTotal);
			$dataLemburSabtuArray = $this->konversi_menit->hitung($lemburSabtuTotal);
			$dataLemburMingguArray = $this->konversi_menit->hitung($lemburMingguTotal);

			/** INSERT LAP REKAP INSTANSI DETIL */
			$data_jumlah_hari = [
				$hitungMasukTotal,
				$hitungTelatTotal,
				$hitungPulangCepatTotal
			];

			$data_overtime = [
				$dataLEmburSeninJumatArray['jam'],
				$dataLEmburSeninJumatArray['menit']
			];

			$data_jumlah_lembur = [
				'sabtu' => [
					$dataLemburSabtuArray['jam'],
					$dataLemburSabtuArray['menit']
				],
				'minggu' => [
					$dataLemburMingguArray['jam'],
					$dataLemburMingguArray['menit']
				]
			];

			$data_keterangan = [
				$hitungMTotal,
				$hitungCHTotal,
				$hitungCMTotal,
				$hitungCTTotal,
				$hitungCAPTotal,
				$hitungDKTotal,
				$hitungDLTotal,
				$hitungITotal,				
				$hitungLPTotal,
				$hitungMPPTotal,
				$hitungSKTotal,
				$hitungTBTotal,
				$hitungUFTTotal,
			];

			$data = [
				'nip'			=> $dataPegawai->nip,
				'nama'			=> $dataPegawai->nama,
				'jabatan'		=> $unor,
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'			=> $pns_get,
				'kerja'         => $hitungJumlahKerjaTotal,
				'jumlah_hari'   => json_encode($data_jumlah_hari),
				'overtime'      => json_encode($data_overtime),
				'jumlah_lembur' => json_encode($data_jumlah_lembur),
				'keterangan'    => json_encode($data_keterangan),
				'urut'			=> $i
			];

			$this->Lap_rekap_instansi_detil_model->insert($data);

			//isi data report
			$temp[] = $i;
			$temp['nama'] = $dataPegawai->nama;
			$temp['nip'] = $dataPegawai->nip;
			$temp['jabatan'] = $unor;
			$temp['kerja'] = $hitungJumlahKerjaTotal;
			
			$temp['jml_hari_hadir'] = $data_jumlah_hari[0];
			$temp['jml_hari_telat'] = $data_jumlah_hari[1];
			$temp['jml_hari_pulang_cepat'] = $data_jumlah_hari[2];

			$temp['overtime_jam'] = $data_overtime[0];
			$temp['overtime_menit'] = $data_overtime[1];

			$temp['sabtu_jam'] = $data_jumlah_lembur['sabtu'][0];
			$temp['sabtu_menit'] = $data_jumlah_lembur['sabtu'][1];

			$temp['minggu_jam'] = $data_jumlah_lembur['minggu'][0];
			$temp['minggu_menit'] = $data_jumlah_lembur['minggu'][1];

			$temp['ket_m'] = $data_keterangan[0];
			$temp['ket_ch'] = $data_keterangan[1];
			$temp['ket_cm'] = $data_keterangan[2];
			$temp['ket_ct'] = $data_keterangan[3];
			$temp['ket_cap'] = $data_keterangan[4];
			$temp['ket_dk'] = $data_keterangan[5];
			$temp['ket_dl'] = $data_keterangan[6];
			$temp['ket_i'] = $data_keterangan[7];
			$temp['ket_lp'] = $data_keterangan[8];
			$temp['ket_mpp'] = $data_keterangan[9];
			$temp['ket_sk'] = $data_keterangan[10];
			$temp['ket_tb'] = $data_keterangan[11];
			$temp['ket_upt'] = $data_keterangan[12];

			//var_dump($temp);exit;
			$datanya[] = $temp;
			#end
			$i++;
		}

		/** UPDATE FINISHED_AT JADI NOT NULL */
		$where = [
			'bulan'			=> $bulan_get,
			'tahun'			=> $tahun_get,
			'id_instansi'	=> $id_instansi_get,
			'pns'			=> $pns_get,
			'deleted_at'	=> null,
		];

		$this->Lap_rekap_instansi_model->update($where, ['finished_at' => date('Y-m-d H:i:s')]);
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
			'tgl_mulai' => date('d-m-Y', strtotime($this->tgl_pertama)),
			'tgl_hingga' => date('d-m-Y', strtotime($this->tgl_terakhir)),
			'instansi' => $this->dataInstansi,
			//'hari' => $hari,
			'bulan' => $this->bulan,
			'tahun' => $tahun_get
		);

		$nama_dokumen = "Laporan_Rekap_Instansi_".str_replace(" ","_",$data_arr['instansi']->nama)."_Tanggal_".str_replace("-","_",$data_arr['tgl_mulai'])."_s/d_".str_replace("-","_",$data_arr['tgl_hingga']);
		$current_date = date('d/m/Y H:i:s');

		if($this->input->get("type") == 'pdf') {
			ini_set('memory_limit', '-1');
			//$html_header = $this->load->view('cetak/skor_new/header', $data_arr, true); //render the view into HTML
			$html_body = $this->load->view('cetak/rekap_instansi_new/body', $data_arr, true); //render the view into HTML

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

			$this->load->view('cetak/rekap_instansi_new/excel', $data_arr);

			ob_end_clean();
		}
		else {
			$this->load->view('cetak/rekap_instansi_new/body', $data_arr);
		}
    }
    
    public function printed($bulan, $tahun, $id_instansi, $pns) {
		$detil_laporan	=	$this->db->query("
			select * from lap_rekap_instansi_detil
			where bulan = '$bulan'
			and tahun = '$tahun'
			and id_instansi = '$id_instansi'
			and pns = '$pns'
			and deleted_at is null
			order by urut
		")->result();

		$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
		// Tanggal pertama pada bulan ini
		$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
		// Tanggal terakhir pada bulan ini
		$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));
		$bulan = $this->input->get("bulan");
		$tahun = $this->input->get("tahun");

		$datanya = array();

		foreach ($detil_laporan as $key => $value) {
			$jumlah_hari = json_decode($value->jumlah_hari);
            $overtime = json_decode($value->overtime);
            $jumlah_lembur = json_decode($value->jumlah_lembur);
            $keterangan = json_decode($value->keterangan);      

			$temp = array();
			$temp[] = $key+1;
			$temp['nama'] = $value->nama;
			$temp['nip'] = $value->nip;
			$temp['jabatan'] = $value->jabatan;
			$temp['kerja'] = $value->kerja;
			
			$temp['jml_hari_hadir'] = $jumlah_hari[0];
			$temp['jml_hari_telat'] = $jumlah_hari[1];
			$temp['jml_hari_pulang_cepat'] = $jumlah_hari[2];

			$temp['overtime_jam'] = $overtime[0];
			$temp['overtime_menit'] = $overtime[1];

			$temp['sabtu_jam'] = $jumlah_lembur->sabtu[0];
			$temp['sabtu_menit'] = $jumlah_lembur->sabtu[1];

			$temp['minggu_jam'] = $jumlah_lembur->minggu[0];
			$temp['minggu_menit'] = $jumlah_lembur->minggu[1];

			$temp['ket_m'] = $keterangan[0];
			$temp['ket_ch'] = $keterangan[1];
			$temp['ket_cm'] = $keterangan[2];
			$temp['ket_ct'] = $keterangan[3];
			$temp['ket_cap'] = $keterangan[4];
			$temp['ket_dk'] = $keterangan[5];
			$temp['ket_dl'] = $keterangan[6];
			$temp['ket_i'] = $keterangan[7];
			$temp['ket_lp'] = $keterangan[8];
			$temp['ket_mpp'] = $keterangan[9];
			$temp['ket_sk'] = $keterangan[10];
			$temp['ket_tb'] = $keterangan[11];
			$temp['ket_upt'] = $keterangan[12];

			//var_dump($temp);exit;
			$datanya[] = $temp;
		}

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
			'tgl_mulai' => date('d-m-Y', strtotime($this->tgl_pertama)),
			'tgl_hingga' => date('d-m-Y', strtotime($this->tgl_terakhir)),
			'instansi' => $this->dataInstansi,
			//'hari' => $hari,
			'bulan' => $this->bulan,
			'tahun' => $tahun
		);

		$nama_dokumen = "Laporan_Rekap_Instansi_".str_replace(" ","_",$data_arr['instansi']->nama)."_Tanggal_".str_replace("-","_",$data_arr['tgl_mulai'])."_s/d_".str_replace("-","_",$data_arr['tgl_hingga']);
		$current_date = date('d/m/Y H:i:s');

		if($this->input->get("type") == 'pdf') {
			ini_set('memory_limit', '-1');
			//$html_header = $this->load->view('cetak/skor_new/header', $data_arr, true); //render the view into HTML
			$html_body = $this->load->view('cetak/rekap_instansi_new/body', $data_arr, true); //render the view into HTML

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

			$this->load->view('cetak/rekap_instansi_new/excel', $data_arr);

			ob_end_clean();
		}
		else {
			$this->load->view('cetak/rekap_instansi_new/body', $data_arr);
		}		
	}

	public function stop() {
		$this->load->model(['lap_rekap_instansi_model', 'lap_rekap_instansi_detil_model']);

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

		$this->lap_rekap_instansi_model->update($where, ['finished_at' => date('Y-m-d H:i:s')]);

		$this->session->set_flashdata('feedback_success', 'Update Laporan Instansi Telah Dihentikan');

		redirect('lap_rekap_instansi','refresh');
	}
}
