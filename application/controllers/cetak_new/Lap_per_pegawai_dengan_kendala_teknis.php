<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class lap_per_pegawai_dengan_kendala_teknis extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model','log_laporan_model']);
	}

	public function index(){
		
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
		
		$tanggal	=	$this->input->get('tahun')."-".$this->input->get('bulan')."-01";
		$queryInstansi 		=	$this->db->query("
		SELECT 
			h.kode_unor, (select m_instansi.nama from m_instansi where m_instansi.kode = (select kode_instansi from m_unit_organisasi_kerja c where c.kode = h.kode_unor) )  as nama_instansi 
		FROM 
			m_pegawai_unit_kerja_histori h 
		WHERE 
			h.tgl_mulai <=  '".$tanggal."'  
			and h.id_pegawai =  '".$this->input->get('id_pegawai')."'
		ORDER BY tgl_mulai desc 
			limit 1
	
		");	
		$this->dataInstansi = 	$queryInstansi->row();
		
		$select = "m_pegawai.*,  m_jenis_jabatan.nama as nama_jenis_jabatan, m_status_pegawai.nama as nama_status_pegawai";
		$where = array('m_pegawai.id' => $this->input->get('id_pegawai'));
		$join = array(
			
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
		$this->dataPegawai = $this->pegawai_model->getDataJoin($where,$select,$join);
		//echo $this->db->last_query();
		
		$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01"); 
		// Tanggal pertama pada bulan ini
		$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
		// Tanggal terakhir pada bulan ini
		$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));
		
		$this->sudahAda	=	$this->log_laporan_model->getData("kd_instansi = '".$this->input->get('id_instansi')."' and tgl_log = '".$this->tgl_terakhir."' ");
		
		$whereDataLaporan  = "id_pegawai='".$this->input->get('id_pegawai')."' and tanggal  <= '".$this->tgl_terakhir ."'  and 
						tanggal  >= '".$this->tgl_pertama ."' ";
		$this->dataLaporan = $this->data_mentah_model->showData($whereDataLaporan,"","tanggal");
		//echo $this->db->last_query();
		//var_dump($this->dataLaporan);
		
		$telatMasuk 	= 0;
		$totPulangCepat = 0;
		$totLembur 		= 0;
		$totLemburMINGGU 		= 0;
		$totLemburSABTU 		= 0;
		$overTimeSemua		= 0;
		$sabtu		 	= 0;
		$minggu		 	= 0;
	
	$this->laporanHtml = "";
	
	foreach($this->dataLaporan as $data){
		if($data->datang_telat> 480){
			$telatMasukBenar = 480;
		}
		else{
			$telatMasukBenar = $data->datang_telat;
		}
		
		$datangTelat	=	$this->konversi_menit->hitung($telatMasukBenar);
		$pulangCepat	=	$this->konversi_menit->hitung($data->pulang_cepat);
		$dataLembur		=	$this->konversi_menit->hitung($data->lembur);
		
		$tglMasuk	=	$data->tanggal." ".$data->finger_masuk_jam;
		
		$cekMasukkendalaTeknis	=	$this->db->query("
		select
			to_char(tanggal,'yyyy-mm-dd HH24:MI') as tanggal,absensi_log.lampiran
		from
			absensi_log
		where
			tanggal =  '".$tglMasuk."' and
			absensi_log.badgenumber in (SELECT user_id from mesin_user where id_pegawai = '".$this->input->get('id_pegawai')."')
			and absensi_log.id_mesin in (SELECT id_mesin from mesin_user where id_pegawai = '".$this->input->get('id_pegawai')."')
		and lampiran_type is not null 
		");
		$dataCekMasukkendalaTeknis 	= $cekMasukkendalaTeknis->row();
		if($dataCekMasukkendalaTeknis){
			$masukKendala = "";
		}
		else{
			$masukKendala = $data->finger_masuk_jam;			
		}
		
		$tglPulang	=	$data->tanggal." ".$data->finger_pulang_jam;
		$cekPulangkendalaTeknis	=	$this->db->query("
		select
			to_char(tanggal,'yyyy-mm-dd HH24:MI') as tanggal,absensi_log.lampiran
		from
			absensi_log
		where
			tanggal =  '".$tglPulang."' and
			absensi_log.badgenumber in (SELECT user_id from mesin_user where id_pegawai = '".$this->input->get('id_pegawai')."')
			and absensi_log.id_mesin in (SELECT id_mesin from mesin_user where id_pegawai = '".$this->input->get('id_pegawai')."')
		and lampiran_type is not null 
		");
		$dataCekPulangkendalaTeknis 	= $cekPulangkendalaTeknis->row();
	//	var_dump($dataCekPulangkendalaTeknis);
		if($dataCekPulangkendalaTeknis){
			$pulangKendala = "";
		}
		else{
			$pulangKendala = $data->finger_pulang_jam;			
		}
	
		$this->laporanHtml .= '<td align="center">'.$data->tanggal_indo.'</td>';
		$this->laporanHtml .= '<td align="center">'.$data->jam_kerja.'</td>';
		$this->laporanHtml .= '<td align="center">'.$masukKendala.'</td>';
		$this->laporanHtml .= '<td align="center">'.$datangTelat["jam"].'</td>';
		$this->laporanHtml .= '<td align="center">'.$datangTelat["menit"].'</td>';
		$this->laporanHtml .= '<td align="center">'.$pulangKendala.'</td>';
		$this->laporanHtml .= '<td align="center">'.$pulangCepat["jam"].'</td>';
		$this->laporanHtml .= '<td align="center">'.$pulangCepat["menit"].'</td>';
		
		$this->laporanHtml .= '<td align="center">';
			if($data->hari != 'SABTU' && $data->hari != 'MINGGU'){ $this->laporanHtml .=  $dataLembur['jam']; } else { $this->laporanHtml .=  "-";}  $this->laporanHtml .= '</td>';
		$this->laporanHtml .= '<td align="center">';
			if($data->hari != 'SABTU' && $data->hari != 'MINGGU'){ $this->laporanHtml .=  $dataLembur['menit']; } else {$this->laporanHtml .=  "-";} $this->laporanHtml .= '</td>';
		

		if($data->hari=='SABTU'){
		
			$this->laporanHtml .= '<td align="center">';  $this->laporanHtml .= $dataLembur["jam"]; $this->laporanHtml .= '</td>';
			$this->laporanHtml .= '<td align="center">'; $this->laporanHtml .= $dataLembur["menit"]; $this->laporanHtml .= '</td>';
		
		}
		else{
		
		$this->laporanHtml .= '<td align="center">-</td>';
		$this->laporanHtml .= '<td align="center">-</td>';
		
		}
		
		
		if($data->hari=='MINGGU'){
	
			$this->laporanHtml .= '<td align="center">'; $this->laporanHtml .= $dataLembur["jam"]; $this->laporanHtml .= '</td>';
			$this->laporanHtml .= '<td align="center">'; $this->laporanHtml .= $dataLembur["menit"]; $this->laporanHtml .= '</td>';
		
		}
		else{
			$this->laporanHtml .= '<td align="center">-</td>';
			$this->laporanHtml .= '<td align="center">-</td>';
		
		}
		
		$this->laporanHtml .= '<td align="center">'; $this->laporanHtml .= $data->kode_masuk; $this->laporanHtml .= '</td></tr>';
		
	
	
		$telatMasuk		+= $telatMasukBenar;
		$totPulangCepat	+= $data->pulang_cepat;
		
		if($data->hari!='MINGGU' && $data->hari!='SABTU'){
			
			$totLembur		+= $data->lembur;
		}
		
		if($data->hari=='MINGGU' ){
			
			$totLemburMINGGU		+= $data->lembur;
		}
		
		if( $data->hari=='SABTU'){
			
			$totLemburSABTU		+= $data->lembur;
		}
	} 
	
	//echo $telatMasuk;
	
		
		
		$totalTelatMasuk		=	$this->konversi_menit->hitung($telatMasuk);
		
		
		
		$totalPulangCepat		=	$this->konversi_menit->hitung($totPulangCepat);
		
		
		$totalLembur			=	$this->konversi_menit->hitung($totLembur);
		$totLemburSABTUArray			=	$this->konversi_menit->hitung($totLemburSABTU);
		$totLemburMINGGUArray			=	$this->konversi_menit->hitung($totLemburMINGGU);
	
	
	
	
		$this->laporanHtml .=' <tr class="bggrey" BORDER="0">
			<td><strong>TOTAL</strong></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>';
		$this->laporanHtml .=' <td align="center">'; $this->laporanHtml .=$totalTelatMasuk['jam']; $this->laporanHtml .=' </td>';
		$this->laporanHtml .='<td align="center">'; $this->laporanHtml .= $totalTelatMasuk['menit'];  $this->laporanHtml .=' </td>';
		$this->laporanHtml .=' 	<td>&nbsp;</td>';
		$this->laporanHtml .=' 	<td align="center">'; $this->laporanHtml .= $totalPulangCepat['jam'];  $this->laporanHtml .=' </td>';
		$this->laporanHtml .=' 	<td align="center">'; $this->laporanHtml .= $totalPulangCepat['menit']; $this->laporanHtml .=' </td>';
			
		$this->laporanHtml .=' 	<td align="center">'; $this->laporanHtml .= $totalLembur['jam']; $this->laporanHtml .=' </td>';
		$this->laporanHtml .=' 	<td align="center">'; $this->laporanHtml .= $totalLembur['menit']; $this->laporanHtml .=' </td>';
		$this->laporanHtml .=' 	<td align="center">'; $this->laporanHtml .= $totLemburSABTUArray['jam']; $this->laporanHtml .=' </td>';
		$this->laporanHtml .=' 	<td align="center">'; $this->laporanHtml .= $totLemburSABTUArray['menit']; $this->laporanHtml .=' </td>';
			
		$this->laporanHtml .=' 	<td align="center">'; $this->laporanHtml .= $totLemburMINGGUArray['jam']; $this->laporanHtml .= ' </td>';
		$this->laporanHtml .=' 	<td align="center">'; $this->laporanHtml .= $totLemburMINGGUArray["menit"];$this->laporanHtml .=' </td>';
		$this->laporanHtml .=' 	<td>&nbsp;</td>';
		$this->laporanHtml .=' </tr> ';
		
		$this->load->view('cetak/lap_per_pegawai_dengan_kendala_teknis_view');
	}
	
	
	
}
