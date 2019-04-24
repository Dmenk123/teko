<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Lap_skor extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model','log_laporan_model', 'lap_skor_kehadiran_model', 'lap_skor_kehadiran_detil_model']);
	}

	public function generate() {
		$this->load->library('konversi_menit');

		/** CEK APAKAH ADA LAPORAN SUDAH DIKUNCI */
		$whereTahunBulan = $this->input->get('tahun') . '-' . $this->input->get('bulan');
		$tanggal_mulai_kunci = $this->input->get('tahun') . '-' . $this->input->get('bulan') . '-' . '01';
		$id_instansi_get = $this->input->get('id_instansi');
 		$tanggal_akhir_kunci = date("Y-m-t", strtotime($tanggal_mulai_kunci));

		//hardcode tidak bisa update jika tahun 2018
		//batasan CUTOFF LAPORAN
		$tgl_batas2 	= "2019-01-01";
		$hariBatas2		= strtotime($tgl_batas2);
		//$harisekarang	= strtotime(date("Y-m-d"));
		$hari_generate = strtotime($tanggal_akhir_kunci);

		if ($hari_generate < $hariBatas2 ){
			if ($this->session->userdata('id_kategori_karyawan') == '1') {
				$laporanTerkunci = false;
			}else{
				$laporanTerkunci = true;
			}
		}
		else
		{
			$laporanTerkunci	= $this->db->query("
				select * from log_laporan
				where to_char(tgl_log, 'YYYY-MM') = '$whereTahunBulan'
				and kd_instansi = '$id_instansi_get'
				and is_kunci = 'Y'
			")->row_array();
		}

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
            $wherePns 	= " and m.kode_status_pegawai < '5'";
        }
        else
        {
			//$wherePns 	= " and m.kode_status_pegawai >='5' and m.kode_status_pegawai > '1'";
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
							h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi, h.excel, h.langsung_pindah
						FROM
							m_pegawai_unit_kerja_histori h
							LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
							LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode 
							WHERE h.tgl_mulai <= '".$tanggal."' and m.id = h.id_pegawai or (h.langsung_pindah = 't' and h.id_pegawai = m.id)
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
							WHERE h.tgl_mulai <= '".$tanggal."' and m.id = h.id_pegawai or (h.langsung_pindah = 't' and h.id_pegawai = m.id)
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

			$awallimabelas = 0;
			$kurang_lima_bos = '';
			# start perwali desember 2018
			if(($this->input->post("bulan") == 12 && $this->input->post("tahun") == 2018) || $this->input->post("tahun") == 2019) {
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
					tanggal >= '".$mulai."'
					AND tanggal <=  '".$akhir."' and
					id_pegawai = '".$id_pegawai."'");

			$dataJumlahTelat		=	$queryJumlah->row();

			# start perwali desember 2018
			if(($this->input->post("bulan") == 12 && $this->input->post("tahun") == 2018) || $this->input->post("tahun") == 2019) {

				$skorLambatKurangLima =	100 - ($dataJumlahTelat->jumlahlambatkuranglima * 0.25);

				$skor_detil[] = [
					'frek'	=> $dataJumlahTelat->jumlahlambatkuranglima,
					'skor' 	=> $skorLambatKurangLima
				];
			}
			# end perwali database 2018

			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
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
			if(($this->input->post("bulan") == 12 && $this->input->post("tahun") == 2018) || $this->input->post("tahun") == 2019) {
				$skorTotal = $skorTotal + $skorLambatKurangLima;
			}
			# end perwali desember 2018

			//////////////////////

			# start perwali januari 2018
			if($this->input->post("bulan") == 01 && $this->input->post("tahun") == 2018) {
				$skorJumlahCutiTahunan = 100 - ($dataJumlahTelat->jumlahcutitahunan * 0);
			}
			# end perwali januari 2018
			///////

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
				'jml_hadir'		=> $dataJumlahTelat->jumlahhadirtotal,
				'jml_dl'		=> $dataJumlahTelat->jumlahdinasluar,
				'jml_cuti'		=> $dataJumlahTelat->jumlahcutitahunan,
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

		echo json_encode(['status' => 'sukses', 'pesan' => 'Sukses Update data Laporan Skor']);
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
