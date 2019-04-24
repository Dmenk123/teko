<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require 'vendor/autoload.php';

class cetak extends CI_Controller {

	public function __construct() {
		parent::__construct();
    $this->load->model(['global_model', 'pegawai_model', 'instansi_model', 'data_mentah_model']);
	}

	// $q_absen = "
	// 	SELECT tanggal::date, tanggal::time as waktu, extract(dow from tanggal) as hari FROM absensi_log where badgenumber In (".$q_id_finger['user_id'].") AND tanggal BETWEEN '".$tmulai[2]."-".$tmulai[1]."-".$tmulai[0]."' AND '".$thingga[2]."-".$thingga[1]."-".$thingga[0]."' order by tanggal, waktu
	// ";
	// $absen = $this->global_model->getData($q_absen);

	// $q_jadwal = "
	// SELECT rjkh.tgl_mulai, rjkd.id_hari, jk.jam_mulai_scan_masuk, jk.jam_akhir_scan_masuk, jk.jam_mulai_scan_pulang, jk.jam_akhir_scan_pulang, jk.jam_masuk, jk.jam_pulang,  jk.toleransi_terlambat, jk.toleransi_pulang_cepat, jk.masuk_hari_sebelumnya, jk.pulang_hari_berikutnya FROM m_pegawai_role_jam_kerja_histori as rjkh LEFT JOIN m_role_jam_kerja_detail as rjkd ON rjkh.id_role_jam_kerja = rjkd.id_role LEFT JOIN m_jam_kerja as jk ON rjkd.id_jam_kerja = jk.id where rjkh.id_pegawai = '".$id_pegawai."'  AND (rjkh.tgl_mulai <= ''".$tmulai[2]."-".$tmulai[1]."-".$tmulai[0]."'' OR rjkh.tgl_mulai <= '".$thingga[2]."-".$thingga[1]."-".$thingga[0]."') order by rjkh.tgl_mulai, rjkd.id_hari
	// ";

  public function cetakperpegawai() {
    $p = $this->input->get('p');
    $param = base64_decode(urldecode($p));
    $dtparam = explode('||', $param);

    $id_pegawai = $dtparam[0];
    $tgl_mulai = $dtparam[1];
    $tmulai = explode('/', $tgl_mulai);
    $tgl_hingga = $dtparam[2];
    $thingga = explode('/', $tgl_hingga);
    $mode = $dtparam[3];
		$url = base_url()."cetak/cetakperpegawai?p=".urlencode($p);

		$select = "m_pegawai.*, m_unit_organisasi_kerja.nama as nama_unor, m_instansi.nama as nama_instansi, m_jenis_jabatan.nama as nama_jenis_jabatan, m_status_pegawai.nama as nama_status_pegawai";
		$join = array(
			array(
				"table" => "m_unit_organisasi_kerja",
				"on"    => "m_pegawai.kode_unor = m_unit_organisasi_kerja.kode"
			),
			array(
				"table" => "m_instansi",
				"on"    => "m_unit_organisasi_kerja.kode_instansi = m_instansi.kode"
			),
			array(
				"table" => "m_eselon",
				"on"    => "m_pegawai.kode_eselon = m_eselon.kode"
			),
			array(
				"table" => "m_jenis_jabatan",
				"on"    => "m_pegawai.kode_jenis_jabatan = m_jenis_jabatan.kode"
			),
			array(
				"table" => "m_status_pegawai",
				"on"    => "m_pegawai.kode_status_pegawai = m_status_pegawai.kode"
			),
			array(
				"table" => "m_golongan",
				"on"    => "m_pegawai.kode_golongan_akhir = m_golongan.kode"
			)
		);
		$where         = "id = '".$id_pegawai."' ";
		$pegawai = $this->pegawai_model->getDataJoin($where, $select, $join);

    $query = "
      select * from fn_rpt_rekap_per_pegawai('', '".$id_pegawai."', '".$tmulai[2]."-".$tmulai[1]."-".$tmulai[0]."', '".$thingga[2]."-".$thingga[1]."-".$thingga[0]."') as (
      id_pegawai varchar, nip varchar, nama varchar, kode_unor varchar, unor varchar, tanggal date, hari varchar,hari_status varchar, jam_Kerja varchar,ada_roster boolean,
      jam_masuk timestamp without time zone, absen_masuk timestamp without time zone, telat_jam double precision, telat_menit double precision,
      jam_pulang timestamp without time zone, absen_pulang timestamp without time zone, pulang_cepat_jam double precision, pulang_cepat_menit double precision,
      overtime_jam double precision, overtime_menit double precision,
      sabtu_jam double precision, sabtu_menit double precision, minggu_jam double precision, minggu_menit double precision,
      keterangan varchar, kode_instansi varchar,instansi varchar,jabatan varchar)";

    $isi = $this->global_model->getData($query);

		//$q_id_finger = "SELECT * FROM mesin_user where id_pegawai = '".$id_pegawai."'";
		// $q_id_finger = "
		// 	SELECT string_agg(user_id, ',') as user_id FROM public.mesin_user where id_pegawai = '".$id_pegawai."'
		// ";
		// $finger = $this->global_model->getDataOne($q_id_finger);
		//
		// $q_absen = "
		// 	SELECT tanggal::date, tanggal::time as waktu, extract(dow from tanggal) as hari FROM absensi_log where badgenumber In (".$finger['user_id'].") AND tanggal BETWEEN '".$tmulai[2]."-".$tmulai[1]."-".$tmulai[0]."' AND '".$thingga[2]."-".$thingga[1]."-".$thingga[0]."' order by tanggal, waktu
		// ";
		// $absen = $this->global_model->getData($q_absen);

    $data["isi"] = $isi;
    $data["tgl_mulai"] = $tgl_mulai;
    $data["tgl_hingga"] = $tgl_hingga;
    $data["pegawai"] = $pegawai;
    $data["url"] = $url;
		$current_date = date('d/m/Y H:i:s');

		//$this->load->view('cetak/perpegawai/header', $data);
    //$this->load->view('cetak/perpegawai/body', $data);

		$nama_dokumen = "Laporan_Per_Pegawai_".str_replace(" ","_",$data["pegawai"]->nama)."_Tanggal_".$tgl_mulai."_s/d_".$tgl_hingga;

		if($mode == 'pdf') {
			ini_set('memory_limit', '-1');
			// $this->load->view('cetak/perpegawai/header', $data);
      // $this->load->view('cetak/perpegawai/body', $data);

			$html_header = $this->load->view('cetak/perpegawai/header', $data, true); //render the view into HTML
			$html_body = $this->load->view('cetak/perpegawai/body', $data, true); //render the view into HTML

			$this->load->library('pdf');
			$pdf=$this->pdf->load("en-GB-x","A4","","",10,10,45,10,6,3,"P");
			$pdf->SetWatermarkImage('http://garbis.surabaya.go.id/v3beta/assets/images/logo_pemkot_watermark.jpg', 0.3, 'F');
			$pdf->showWatermarkImage = true;
			$pdf->SetHTMLHeader($html_header);
			$pdf->SetFooter(''.'Halaman {PAGENO} dari {nb}||'.$current_date.''); //Add a footer for good measure
			$pdf->WriteHTML($html_body); //write the HTML into PDF
			$pdf->Output($nama_dokumen.".pdf" ,'I');
		}

		else if($mode == 'xls') {
			//$this->load->library('ciqrcode');

			//$config['cacheable']    = true; //boolean, the default is true
      //$config['cachedir']     = './assets/'; //string, the default is application/cache/
      //$config['errorlog']     = './assets/'; //string, the default is application/logs/
      //$config['imagedir']     = './assets/images/'; //direktori penyimpanan qr code
      //$config['quality']      = true; //boolean, the default is true
      //$config['size']         = '1024'; //interger, the default is 1024
      //$config['black']        = array(224,255,255); // array, default is array(255,255,255)
      //$config['white']        = array(70,130,180); // array, default is array(0,0,0)
      //$this->ciqrcode->initialize($config);

      //$image_name=$p.'.png'; //buat name dari qr code sesuai dengan nim

      //$params['data'] = $url; //data yang akan di jadikan QR CODE
      //$params['level'] = 'H'; //H=High
      //$params['size'] = 10;
      //$params['savename'] = FCPATH.$config['imagedir'].$image_name; //simpan image QR CODE ke folder assets/images/
      //$this->ciqrcode->generate($params); // fungsi untuk generate QR CODE

			// Fungsi header dengan mengirimkan raw data excel
			header("Cache-Control: no-cache, no-store, must-revalidate");
			header("Content-Type: application/vnd.ms-excel");
			// Mendefinisikan nama file ekspor "hasil-export.xls"
			header("Content-Disposition: attachment; filename=".$nama_dokumen.".xls");

			$this->load->view('cetak/perpegawai/excel', $data);

			ob_end_clean();

			//$html_header = $this->load->view('cetak/perpegawai/header', $data, true); //render the view into HTML
			//$html_body = $html_header.$this->load->view('cetak/perpegawai/body', $data, true); //render the view into HTML

			//$this->load->helper("file");
			//$fileName = "temp_file_name.html";
			//$path = APPPATH."views/excel/";
			//$path_file = $path . $fileName;

			//if (write_file($path_file, $html_body)) {
				//$reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
		    //$spreadsheet = $reader->load($path_file);

		    //write out to html file
		    //$writer = new \PhpOffice\PhpSpreadsheet\Writer\Html($spreadsheet);
		    //$writer->save($path. "doc.xlsx");

				// Fungsi header dengan mengirimkan raw data excel
				//header("Content-type: application/vnd-ms-excel");
				// Mendefinisikan nama file ekspor "hasil-export.xls"
				//header("Content-Disposition: attachment; filename=".$nama_dokumen.".xls");
				//$this->load->view($path_file);

		    //delete the temporary file
		    //unlink($path_file);
			//}
			//else{
				//echo "
				//	<script>
				//		alert('Terjadi Kesalahan, Silahkan Kontak Administrator');
				//		window.close();
				//	</script>
				//";
			//}
		}
		else if($mode == 'html') {
			$this->load->view('cetak/perpegawai/header', $data);
      $this->load->view('cetak/perpegawai/body', $data);
		}
		else{
			echo "
				<script>
					alert('Terjadi Kesalahan, Silahkan Kontak Administrator');
					window.close();
				</script>
			";
		}
  }

	public function cetakrekapinstansi() {
		$p = $this->input->get('p');
    $param = base64_decode(urldecode($p));
    $dtparam = explode('||', $param);

    $kode_instansi = $dtparam[0];
		$status_pegawai = $dtparam[1];
    $tgl_mulai = $dtparam[2];
    $tmulai = explode('/', $tgl_mulai);
    $tgl_hingga = $dtparam[3];
    $thingga = explode('/', $tgl_hingga);
    $mode = $dtparam[4];
		$url = base_url()."cetak/cetakrekapinstansi?p=".urlencode($p);

		$where ="kode = '".$kode_instansi."' ";
		$instansi = $this->instansi_model->getData($where);

		$query = "
		select x.*,j.nama as jabatan from(
			select * from fn_rpt_rekap_per_instansi('".$kode_instansi."', '".$tmulai[2]."-".$tmulai[1]."-".$tmulai[0]."', '".$thingga[2]."-".$thingga[1]."-".$thingga[0]."') as (id_pegawai varchar, nama varchar, nip varchar, kerja bigint, jml_hari_hadir bigint,
				jml_hari_telat bigint, jml_hari_pulang_cepat bigint, overtime_jam double precision, overtime_menit double precision, sabtu_jam double precision, sabtu_menit double precision,
				minggu_jam double precision, minggu_menit double precision, ket_m bigint, ket_ch bigint, ket_cm bigint, ket_ct bigint,ket_cap bigint,
				ket_dk bigint, ket_dl bigint, ket_i bigint, ket_lp bigint, ket_mpp bigint, ket_sk bigint, ket_tb bigint, ket_upt bigint))x
				left join m_pegawai p on x.id_pegawai=p.id
				left join m_jenis_jabatan j on p.kode_jenis_jabatan=j.kode
		";
		$where = "where p.kode_status_pegawai <> '5'";
		if ($status_pegawai == 0) {
			$where = "where p.kode_status_pegawai = '5'";
		}

		$order = "order by coalesce(j.urut,1000),coalesce(p.kode_eselon,'z'),coalesce(p.kode_golongan_akhir,'z') desc";

		$isi = $this->global_model->getData($query." ".$where." ".$order);
		$data["isi"] = $isi;
		$data["tgl_mulai"] = $tgl_mulai;
		$data["tgl_hingga"] = $tgl_hingga;
		$data["instansi"] = $instansi;
		$data["url"] = $url;
		$current_date = date('d/m/Y H:i:s');

		$nama_dokumen = "Laporan_Rekapitulasi_Pegawai_".str_replace(" ","_",$instansi->nama)."_Tanggal_".$tgl_mulai."_s/d_".$tgl_hingga;

		if($mode == 'pdf') {
			ini_set('memory_limit', '-1');
			// $this->load->view('cetak/rekap_instansi/header', $data);
			// $this->load->view('cetak/rekap_instansi/body', $data);

			$html_header = $this->load->view('cetak/rekap_instansi/header', $data, true); //render the view into HTML
			$html_body = $this->load->view('cetak/rekap_instansi/body', $data, true); //render the view into HTML

			$this->load->library('pdf');
			$pdf=$this->pdf->load("en-GB-x","A4-L","","",10,10,45,10,6,3,"L");
			$pdf->SetWatermarkImage('http://garbis.surabaya.go.id/v3beta/assets/images/logo_pemkot_watermark.jpg', 0.3, 'F');
			$pdf->showWatermarkImage = true;
			$pdf->SetHTMLHeader($html_header);
			$pdf->SetFooter(''.'Halaman {PAGENO} dari {nb}||'.$current_date.''); //Add a footer for good measure
			$pdf->WriteHTML($html_body); //write the HTML into PDF
			$pdf->Output($nama_dokumen.".pdf" ,'I');
		}
		else if($mode == 'xls') {
			// Fungsi header dengan mengirimkan raw data excel
			header("Cache-Control: no-cache, no-store, must-revalidate");
			header("Content-Type: application/vnd.ms-excel");
			// Mendefinisikan nama file ekspor "hasil-export.xls"
			header("Content-Disposition: attachment; filename=".$nama_dokumen.".xls");

			$this->load->view('cetak/rekap_instansi/excel', $data);

			ob_end_clean();
		}
		else if($mode == 'html') {
			$this->load->view('cetak/rekap_instansi/header', $data);
			$this->load->view('cetak/rekap_instansi/body', $data);
		}
		else{
			echo "
				<script>
					alert('Terjadi Kesalahan, Silahkan Kontak Administrator');
					window.close();
				</script>
			";
		}
	}

	public function cetakskor() {
		$p = $this->input->get('p');
    $param = base64_decode(urldecode($p));
    $dtparam = explode('||', $param);

    $kode_instansi = $dtparam[0];
		$status_pegawai = $dtparam[1];
    $tgl_mulai = $dtparam[2];
    $tmulai = explode('/', $tgl_mulai);
    $tgl_hingga = $dtparam[3];
    $thingga = explode('/', $tgl_hingga);
    $mode = $dtparam[4];
		$url = base_url()."cetak/cetakrekapinstansi?p=".urlencode($p);
		$status = 'pns';
		if ($status_pegawai == 0) {
			$status = 'non_pns';
		}

		$where ="kode = '".$kode_instansi."' ";
		$instansi = $this->instansi_model->getData($where);

		$query = "
				select * from fn_rpt_hitung_rekap_per_instansi_skor('".$kode_instansi."', '".$tmulai[2]."-".$tmulai[1]."-".$tmulai[0]."', '".$thingga[2]."-".$thingga[1]."-".$thingga[0]."','".$status."') as (
								nama varchar, nip varchar, golongan varchar, jabatan varchar,k_freq1 bigint, k_skor1 double precision,
								k_freq2 bigint, k_skor2 double precision, k_freq3 bigint, k_skor3 double precision, k_freq4 bigint, k_skor4 double precision, k_freq5 bigint, k_skor5 double precision,
								p_freq1 bigint, p_skor1 double precision, p_freq2 bigint, p_skor2 double precision, p_freq3 bigint, p_skor3 double precision, p_freq4 bigint, p_skor4 double precision, p_freq5 bigint, p_skor5 double precision,
								c_s_freq bigint, c_s_skor double precision, c_hms_freq bigint, c_hms_skor double precision, th_s_freq bigint, th_s_skor double precision, th_ts_freq bigint, th_ts_skor double precision,dl_freq double precision,ct_freq double precision,jml_hari double precision,jml_hadir double precision)
		";

		//$order = "order by coalesce(j.urut,1000),coalesce(p.kode_eselon,'z'),coalesce(p.kode_golongan_akhir,'z') desc";

		$isi = $this->global_model->getData($query);
		$data["isi"] = $isi;
		$data["tgl_mulai"] = $tgl_mulai;
		$data["tgl_hingga"] = $tgl_hingga;
		$data["instansi"] = $instansi;
		$data["url"] = $url;
		$current_date = date('d/m/Y H:i:s');

		$nama_dokumen = "Laporan_Skor_Kehadiran_".str_replace(" ","_",$instansi->nama)."_Tanggal_".$tgl_mulai."_s/d_".$tgl_hingga;

		if($mode == 'pdf') {
			ini_set('memory_limit', '-1');
			// $this->load->view('cetak/rekap_instansi/header', $data);
			// $this->load->view('cetak/rekap_instansi/body', $data);

			$html_header = $this->load->view('cetak/skor/header', $data, true); //render the view into HTML
			$html_body = $this->load->view('cetak/skor/body', $data, true); //render the view into HTML

			$this->load->library('pdf');
			$pdf=$this->pdf->load("en-GB-x","A4-L","","",2,2,45,10,6,3,"L");
			$pdf->SetWatermarkImage('http://garbis.surabaya.go.id/v3beta/assets/images/logo_pemkot_watermark.jpg', 0.3, 'F');
			$pdf->showWatermarkImage = true;
			$pdf->SetHTMLHeader($html_header);
			$pdf->SetFooter(''.'Halaman {PAGENO} dari {nb}||'.$current_date.''); //Add a footer for good measure
			$pdf->WriteHTML($html_body); //write the HTML into PDF
			$pdf->Output($nama_dokumen.".pdf" ,'I');
		}
		else if($mode == 'xls') {
			// Fungsi header dengan mengirimkan raw data excel
			header("Cache-Control: no-cache, no-store, must-revalidate");
			header("Content-Type: application/vnd.ms-excel");
			// Mendefinisikan nama file ekspor "hasil-export.xls"
			header("Content-Disposition: attachment; filename=".$nama_dokumen.".xls");

			$this->load->view('cetak/skor/excel', $data);

			ob_end_clean();
		}
		else if($mode == 'html') {
			$this->load->view('cetak/skor/header', $data);
			$this->load->view('cetak/skor/body', $data);
		}
		else{
			echo "
				<script>
					alert('Terjadi Kesalahan, Silahkan Kontak Administrator');
					window.close();
				</script>
			";
		}
	}

	public function cetakskorlembur() {
		$p = $this->input->get('p');
    $param = base64_decode(urldecode($p));
    $dtparam = explode('||', $param);

		$bulan = $dtparam[0];
		$tahun = $dtparam[1];
		$kode_instansi = $dtparam[2];
		$status_pegawai = $dtparam[3];
    $mode = $dtparam[4];

		$bulan_array = array("JANUARI","FEBRUARI","MARET","APRIL","MEI","JUNI","JULI","AGUSTUS","SEPTEMBER","OKTOBER","NOVEMBER","DESEMBER");
		$hari_array = array("31","28","31","30","31","30","31","31","30","31","30","31","29");
		$hari = 0;
		if($bulan == 2) {
			if($tahun % 4 == 0) {
				$hari = $hari_array[12];
			}
			else {
				$hari = $hari_array[($bulan - 1)];
			}
		}
		else {
			$hari = $hari_array[($bulan - 1)];
		}

		$status = 'pns';
		if ($status_pegawai == 0) {
			$status = 'non_pns';
		}

		$url = base_url()."cetak/cetakskorlembur?p=".urlencode($p);

		if($hari == 28) {
			$query = "
				select * from fn_rpt_rekap_lembur28('".$tahun."', '".$bulan."', '".$kode_instansi. "','".$status."') as (nama varchar, nip varchar, lembur_01 text, approve_01 text, lembur_02 text, approve_02 text,
				lembur_03 text, approve_03 text, lembur_04 text, approve_04 text,
				lembur_05 text, approve_05 text, lembur_06 text, approve_06 text,
				lembur_07 text, approve_07 text, lembur_08 text, approve_08 text,
				lembur_09 text, approve_09 text, lembur_10 text, approve_10 text,
				lembur_11 text, approve_11 text, lembur_12 text, approve_12 text,
				lembur_13 text, approve_13 text, lembur_14 text, approve_14 text,
				lembur_15 text, approve_15 text, lembur_16 text, approve_16 text,
				lembur_17 text, approve_17 text, lembur_18 text, approve_18 text,
				lembur_19 text, approve_19 text, lembur_20 text, approve_20 text,
				lembur_21 text, approve_21 text, lembur_22 text, approve_22 text,
				lembur_23 text, approve_23 text, lembur_24 text, approve_24 text,
				lembur_25 text, approve_25 text, lembur_26 text, approve_26 text,
				lembur_27 text, approve_27 text, lembur_28 text, approve_28 text,
				total text,skor integer)
			";
		}
		else if($hari == 29) {
			$query = "
				select * from fn_rpt_rekap_lembur29('".$tahun."', '".$bulan."', '".$kode_instansi. "','".$status."') as (nama varchar, nip varchar, lembur_01 text, approve_01 text, lembur_02 text, approve_02 text,
				lembur_03 text, approve_03 text, lembur_04 text, approve_04 text,
				lembur_05 text, approve_05 text, lembur_06 text, approve_06 text,
				lembur_07 text, approve_07 text, lembur_08 text, approve_08 text,
				lembur_09 text, approve_09 text, lembur_10 text, approve_10 text,
				lembur_11 text, approve_11 text, lembur_12 text, approve_12 text,
				lembur_13 text, approve_13 text, lembur_14 text, approve_14 text,
				lembur_15 text, approve_15 text, lembur_16 text, approve_16 text,
				lembur_17 text, approve_17 text, lembur_18 text, approve_18 text,
				lembur_19 text, approve_19 text, lembur_20 text, approve_20 text,
				lembur_21 text, approve_21 text, lembur_22 text, approve_22 text,
				lembur_23 text, approve_23 text, lembur_24 text, approve_24 text,
				lembur_25 text, approve_25 text, lembur_26 text, approve_26 text,
				lembur_27 text, approve_27 text, lembur_28 text, approve_28 text,
				lembur_29 text, approve_29 text, total text,skor integer)
			";
		}
		else if($hari == 30) {
			$query = "
				select * from fn_rpt_rekap_lembur30('".$tahun."', '".$bulan."', '".$kode_instansi. "','".$status."') as (nama varchar, nip varchar, lembur_01 text, approve_01 text, lembur_02 text, approve_02 text,
				lembur_03 text, approve_03 text, lembur_04 text, approve_04 text,
				lembur_05 text, approve_05 text, lembur_06 text, approve_06 text,
				lembur_07 text, approve_07 text, lembur_08 text, approve_08 text,
				lembur_09 text, approve_09 text, lembur_10 text, approve_10 text,
				lembur_11 text, approve_11 text, lembur_12 text, approve_12 text,
				lembur_13 text, approve_13 text, lembur_14 text, approve_14 text,
				lembur_15 text, approve_15 text, lembur_16 text, approve_16 text,
				lembur_17 text, approve_17 text, lembur_18 text, approve_18 text,
				lembur_19 text, approve_19 text, lembur_20 text, approve_20 text,
				lembur_21 text, approve_21 text, lembur_22 text, approve_22 text,
				lembur_23 text, approve_23 text, lembur_24 text, approve_24 text,
				lembur_25 text, approve_25 text, lembur_26 text, approve_26 text,
				lembur_27 text, approve_27 text, lembur_28 text, approve_28 text,
				lembur_29 text, approve_29 text, lembur_30 text, approve_30 text,
				total text,skor integer)
			";
		}
		else if($hari == 31) {
			$query = "
				select * from fn_rpt_rekap_lembur31('".$tahun."', '".$bulan."', '".$kode_instansi. "','".$status."') as (nama varchar, nip varchar, lembur_01 text, approve_01 text, lembur_02 text, approve_02 text,
				lembur_03 text, approve_03 text, lembur_04 text, approve_04 text,
				lembur_05 text, approve_05 text, lembur_06 text, approve_06 text,
				lembur_07 text, approve_07 text, lembur_08 text, approve_08 text,
				lembur_09 text, approve_09 text, lembur_10 text, approve_10 text,
				lembur_11 text, approve_11 text, lembur_12 text, approve_12 text,
				lembur_13 text, approve_13 text, lembur_14 text, approve_14 text,
				lembur_15 text, approve_15 text, lembur_16 text, approve_16 text,
				lembur_17 text, approve_17 text, lembur_18 text, approve_18 text,
				lembur_19 text, approve_19 text, lembur_20 text, approve_20 text,
				lembur_21 text, approve_21 text, lembur_22 text, approve_22 text,
				lembur_23 text, approve_23 text, lembur_24 text, approve_24 text,
				lembur_25 text, approve_25 text, lembur_26 text, approve_26 text,
				lembur_27 text, approve_27 text, lembur_28 text, approve_28 text,
				lembur_29 text, approve_29 text, lembur_30 text, approve_30 text,
				lembur_31 text, approve_31 text, total text,skor integer)
			";
		}

		$where ="kode = '".$kode_instansi."' ";
		$instansi = $this->instansi_model->getData($where);

		$isi = $this->global_model->getData($query);
		$data["isi"] = $isi;
		$data["bulan"] = $bulan_array[($bulan - 1)];
		$data["tahun"] = $tahun;
		$data["instansi"] = $instansi;
		$data["hari"] = $hari;
		$data["url"] = $url;
		$current_date = date('d/m/Y H:i:s');

		$nama_dokumen = "Laporan_Skor_Lembur_".str_replace(" ","_",$instansi->nama)."_Bulan_".$bulan_array[($bulan - 1)]."_Tahun_".$tahun;

		if($mode == 'pdf') {
			ini_set('memory_limit', '-1');
			// $this->load->view('cetak/skor_lembur/header', $data);
			// $this->load->view('cetak/skor_lembur/body', $data);

			$html_header = $this->load->view('cetak/skor_lembur/header', $data, true); //render the view into HTML
			$html_body = $this->load->view('cetak/skor_lembur/body', $data, true); //render the view into HTML

			$this->load->library('pdf');
			$pdf=$this->pdf->load("en-GB-x","A4-L","","",10,10,45,10,6,3,"L");
			$pdf->SetWatermarkImage('http://garbis.surabaya.go.id/v3beta/assets/images/logo_pemkot_watermark.jpg', 0.3, 'F');
			$pdf->showWatermarkImage = true;
			$pdf->SetHTMLHeader($html_header);
			$pdf->SetFooter(''.'Halaman {PAGENO} dari {nb}||'.$current_date.''); //Add a footer for good measure
			$pdf->WriteHTML($html_body); //write the HTML into PDF
			$pdf->Output($nama_dokumen.".pdf" ,'I');
		}
		else if($mode == 'xls') {
			// Fungsi header dengan mengirimkan raw data excel
			header("Cache-Control: no-cache, no-store, must-revalidate");
			header("Content-Type: application/vnd.ms-excel");
			// Mendefinisikan nama file ekspor "hasil-export.xls"
			header("Content-Disposition: attachment; filename=".$nama_dokumen.".xls");

			$this->load->view('cetak/skor_lembur/excel', $data);

			ob_end_clean();
		}
		else if($mode == 'html') {
			$this->load->view('cetak/skor_lembur/header', $data);
			$this->load->view('cetak/skor_lembur/body', $data);
		}
		else{
			echo "
				<script>
					alert('Terjadi Kesalahan, Silahkan Kontak Administrator');
					window.close();
				</script>
			";
		}
	}

	public function lap_absensi_per_pegawai()
	{
		$bulan_array = array("JANUARI","FEBRUARI","MARET","APRIL","MEI","JUNI","JULI","AGUSTUS","SEPTEMBER","OKTOBER","NOVEMBER","DESEMBER");

		$data["id_pegawai"] = $_GET['id'];
		$data["bulan"] 		= $_GET['bulan'];
		$data["nama_bulan"] = $bulan_array[($_GET['bulan'] - 1)];
		$data["tahun"] 		= $_GET['tahun'];

		$select  	= 'm_pegawai.nama, m_pegawai.nip, m_jenis_jabatan.nama as nama_jabatan, m_instansi.nama as nama_instansi';
		$where   	= "m_pegawai.id = '".$_GET['id']."' ";
		$join = array
		(
			array(
				"table" => "m_instansi",
				"on"    => "m_instansi.kode = m_pegawai.kode_instansi"
			),
			array(
				"table" => "m_jenis_jabatan",
				"on"    => "m_jenis_jabatan.kode = m_pegawai.kode_jenis_jabatan"
			)
		);
		$data["pegawai"] 	= $this->pegawai_model->getDataJoin($where, $select, $join);

		//var_dump($data["pegawai"]);

		//$where = "id_pegawai = '".$_GET['id']."'";
		$where = array
		(
			'id_pegawai' 						=> $_GET['id'],
			'extract(month from tanggal) = ' 	=> $_GET['bulan'],
			'extract(year from tanggal) = ' 	=> $_GET['tahun']
		);
		//$where = array();
		$data['absensi'] = $this->data_mentah_model->showData($where);
		//echo $this->db->last_query();

		//var_dump($data['absensi']);

		$nama_dokumen = "Laporan_Absensi_Per_Pegawai_".$data["pegawai"]->nama."_Bulan_".$bulan_array[($_GET['bulan'] - 1)]."_Tahun_".$_GET['tahun'];

		ini_set('memory_limit', '-1');

		$html_header = $this->load->view('cetak/absensi_per_pegawai/header', $data, true); //render the view into HTML
		$html_body = $this->load->view('cetak/absensi_per_pegawai/body', $data, true); //render the view into HTML

		$this->load->library('pdf');
		$pdf=$this->pdf->load("en-GB-x","A4-L","","",10,10,45,10,6,3,"L");
		$pdf->SetWatermarkImage('http://garbis.surabaya.go.id/v3beta/assets/images/logo_pemkot_watermark.jpg', 0.3, 'F');
		$pdf->showWatermarkImage = true;
		$pdf->SetHTMLHeader($html_header);
		$pdf->SetFooter(''.'Halaman {PAGENO} dari {nb}||'.$current_date.''); //Add a footer for good measure
		$pdf->WriteHTML($html_body); //write the HTML into PDF
		$pdf->Output($nama_dokumen.".pdf" ,'I');
	}
}
