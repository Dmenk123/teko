<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class lap_uang_makan2 extends CI_Controller {

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

		$whereInstansi 		=	"kode = '".$this->input->get('id_instansi')."' ";
		$this->dataInstansi = 	$this->instansi_model->getData($whereInstansi,"","");

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


		$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
		// Tanggal pertama pada bulan ini
		$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
		// Tanggal terakhir pada bulan ini
		$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));

		$this->sudahAda	=	$this->log_laporan_model->getData("kd_instansi = '".$this->input->get('id_instansi')."' and tgl_log = '".$this->tgl_terakhir."' ");

		if ($this->input->get('id_instansi') == '5.09.00.93.00') {
			$dataLembur = "";
			$dataLembur .= '
			<table width="100%" class="cloth" cellspacing="0" cellpadding="0">
			<thead>
			<tr>
			<th>NO</th>
			<th>NAMA</th>
			<th>NIP</th>';
		}else{
			$dataLembur = "";
			$dataLembur .= '
			<table width="100%" class="cloth" cellspacing="0" cellpadding="0">
			<thead>
			<tr>
			<th>NO</th>
			<th>NAMA</th>';
		}

		while (strtotime($this->tgl_pertama) <= strtotime($this->tgl_terakhir )) {

			$dataLembur .= '<th>'.date ("d", strtotime($this->tgl_pertama)).'</th>';
			$this->tgl_pertama = date ("Y-m-d", strtotime("+1 days", strtotime($this->tgl_pertama)));
		}

		$dataLembur .= '<th>Jumlah Hari</th></tr></thead>';

			/**$select = "m_pegawai.nama,m_pegawai.id as id_pegawai,m_jenis_jabatan.nama as nama_jenis_jabatan";
		if($this->input->get("pns") == 'y'){
			$where 	= "m_pegawai.kode_instansi = '".$this->input->get('id_instansi')."' and m_pegawai.kode_status_pegawai='1'";
		}
		else{

			$where 	= "m_pegawai.kode_instansi = '".$this->input->get('id_instansi')."' and m_pegawai.kode_status_pegawai!='1'";
		}

		$join 	= array(
			array(
				"table" => "m_jenis_jabatan",
				"on"    => "m_pegawai.kode_jenis_jabatan = m_jenis_jabatan.kode"
			)
		);
		$orderBy 			= "m_jenis_jabatan.urut,m_pegawai.nama";
		$this->dataPegawai 	= $this->pegawai_model->showData($where,'',$orderBy,'','','','',$select,$join);
		**/

		/** CEK APAKAH PERNAH PRINT LAPORAN */
		$bulan_get = $this->input->get('bulan');
		$tahun_get = $this->input->get('tahun');
		$id_instansi_get = $this->input->get('id_instansi');
		$pns_get = $this->input->get('pns');

		$queryCekSudahPrintLaporan	=	$this->db->query("
			select * from lap_uang_makan
			where bulan = '$bulan_get'
			and tahun = '$tahun_get'
			and id_instansi = '$id_instansi_get'
			and pns = '$pns_get'
			and deleted_at is null
		");

		if($queryCekSudahPrintLaporan->row())
		{

			/** untuk cek apakah generate laporan benar2 selesai */
			$queryCekSudahFinishGenerateLaporan	= $this->db->query("
				select * from lap_uang_makan
				where bulan = '$bulan_get'
				and tahun = '$tahun_get'
				and id_instansi = '$id_instansi_get'
				and pns = '$pns_get'
				and deleted_at is null
				and finished_at is not null
			");
			#end

			if($queryCekSudahFinishGenerateLaporan->row() or $this->input->get('lanjut_cetak'))
			{
				$this->printed($bulan_get, $tahun_get, $id_instansi_get, $pns_get);
				return;
			}
			else
			{
				$data_uri = [
					'bulan' => $bulan_get,
					'tahun' => $tahun_get,
					'id_instansi' => $id_instansi_get,
					'pns' => $pns_get,
				];

				$queryGeneratingLaporan	= $this->db->query("
					select m.*, u.fullname from lap_uang_makan m
					join c_security_user_new u on m.id_pegawai = u.id
					where bulan = '$bulan_get'
					and tahun = '$tahun_get'
					and id_instansi = '$id_instansi_get'
					and pns = '$pns_get'
					and deleted_at is null
					and finished_at is null
				")->row_array();

				$laporanTergenerate	= $this->db->query("
					select * from lap_uang_makan_detil
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

				redirect('lap_absensi_uang_makan','refresh');
			}
		}
		else
		{
			// $this->session->set_flashdata('feedback_failed', 'Maaf, Laporan belum pernah terbuat!.');

			// redirect('lap_absensi_uang_makan', 'refresh');

			$this->load->model(['Lap_uang_makan_model','Lap_uang_makan_detil_model']);

			$data = [
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'			=> $pns_get,
				'id_pegawai'	=> $this->session->userdata('id_karyawan'),
			];

			$this->Lap_uang_makan_model->insert($data);
		}
		#end

		$kodeAwalDinas	=	substr($this->input->get('id_instansi'),0,4);

		if($this->input->get("pns") == 'y'){
			$wherePns 	= " and m.kode_status_pegawai='1'";
		}
		else{

			$wherePns 	= " and m.kode_status_pegawai <='6' and m.kode_status_pegawai > '1'";
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

		$tanggal	=	$this->input->get('tahun')."-".$this->input->get('bulan')."-01";
		$tglSelesai 	= date('Y-m-t', strtotime($tanggal));

		//jika dinas pendidikan
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
					peh.kode_eselon,
					pgh.kode_golongan desc,
					m.nip
			");
			# end nambah status meninggal
		}
		$this->dataPegawai	=	$queryPegawai->result();
		//echo $this->db->last_query();

		$i=1;
		foreach($this->dataPegawai as $dataPegawai){


			$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
			// Tanggal pertama pada bulan ini
			$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
			// Tanggal terakhir pada bulan ini
			$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));

			if ($this->input->get('id_instansi') == '5.09.00.93.00' || $this->input->get('id_instansi') == '5.02.00.00.00') {
				$dataLembur .= "<tr><td align='center'>".$i."</td>";
				$dataLembur .= "<td>".$dataPegawai->nama."</td>";
				$dataLembur .= "<td>".$dataPegawai->nip."</td>";
			}else{
				$dataLembur .= "<tr><td align='center'>".$i."</td>";
				$dataLembur .= "<td>".$dataPegawai->nama."</td>";
			}


			$hitungMasukTotal 	= 0;

			// untuk insert ke lap_uang_makan_detil
			$skor = [];
			if ($instansiRaw == '5.09.00.93.00' || $instansiRaw == '5.02.00.00.00') {
				$skor[0] = $nip;
			}

			$q_makan = $this->db->query("
			SELECT tgl::date as tanggal, extract(dow from tgl) as hari_tgl, dm.jadwal_masuk::text, dm.jadwal_pulang::text, finger_masuk::text, dm.kode_masuk, dm.kode_tidak_masuk
			FROM generate_series('".$this->tgl_pertama."', '".$this->tgl_terakhir."', '1 day'::interval) tgl
			LEFT JOIN data_mentah as dm ON tgl = dm.tanggal AND id_pegawai = '".$dataPegawai->id_pegawai."'
			order by tgl
			");

			$makan = $q_makan->result_array();

			for($i=0;$i<count($makan);$i++) {
				if($makan[$i]["kode_masuk"] == 'H') {
					$hitungMasuk = 1;
					$text 		 = '1';
				}
				else {
					if($makan[$i]["jadwal_masuk"] <> '') {
						if($makan[$i]["finger_masuk"] <> '') {
							if(strtotime($makan[$i]["finger_masuk"]) < strtotime($makan[$i]["jadwal_pulang"])) {
								$hitungMasuk = 1;
								$text 		 = '1';
							}
							else {
								$hitungMasuk = 0;
								$text 		 = '0';
							}
						}
						else {
							if($makan[$i]["kode_tidak_masuk"] <> '') {
								$hitungMasuk = 0;
								$text 		 = $makan[$i]["kode_tidak_masuk"];
							}
							else {
								$hitungMasuk = 0;
								$text 		 = '0';
							}
						}
					}
					else {
						if($makan[$i]["kode_tidak_masuk"] <> '') {
							$hitungMasuk = 0;
							$text 		 = $makan[$i]["kode_tidak_masuk"];
						}
						else {
							$hitungMasuk = 0;
							$text 		 = '0';
						}
					}
				}
				$dataLembur .= '<td>'.$text.'</td>';
				$skor[] = $text;
				$hitungMasukTotal += $hitungMasuk;
			}

			$dataLembur .= '<td align=center>'.$hitungMasukTotal.' Hari</td>';

			$urutan = $i;

			/** INSERT LAP REKAP INSTANSI DETIL */
			$data = [
				'nama'			=> $dataPegawai->nama,
				'skor'			=> json_encode($skor),
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'			=> $pns_get,
				'jml_hari'		=> $hitungMasukTotal,
				'urut'   		=> $urutan
			];

			$this->Lap_uang_makan_detil_model->insert($data);
			#end


			$i++;
		}

		$dataLembur .= '</table>';


		/** UPDATE FINISHED_AT JADI NOT NULL */
		$where = [
			'bulan'			=> $bulan_get,
			'tahun'			=> $tahun_get,
			'id_instansi'	=> $id_instansi_get,
			'pns'			=> $pns_get,
			'deleted_at'	=> null,
		];

		$this->Lap_uang_makan_model->update($where, ['finished_at' => date('Y-m-d H:i:s')]);
		#end


		$this->bulan 	=	$namaBulan[$this->input->get('bulan')];

		if($this->session->userdata('username') <> 'damkar') {
			$this->load->library('dompdf_gen');
			$this->load->view('cetak/lap_uang_makan_view',[
				'dataLembur' => $dataLembur
			]);
			$paper_size  = 'folio'; //paper size
			$orientation = 'landscape'; //tipe format kertas
			$html = $this->output->get_output();
			$this->dompdf->set_paper($paper_size, $orientation);
			//Convert to PDF
			$this->dompdf->load_html($html);
			$this->dompdf->render();
			$this->dompdf->stream("laporan_uang_makan.pdf", array('Attachment'=>0));
		} else {
			$this->load->view('cetak/lap_uang_makan_view',[
				'dataLembur' => $dataLembur
			]);
		}

	}

	public function printed($bulan, $tahun, $id_instansi, $pns) {
		$detil_laporan	=	$this->db->query("
			select * from lap_uang_makan_detil
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

        $datanya = array();

		foreach ($detil_laporan as $key => $value) {
			$temp = array();
			$temp[] = $key+1;
			$temp[] = $value->nama;

			foreach (json_decode($value->skor) as $skor) {
                $temp[] = $skor;
            }

			$temp[] = $value->jml_hari;
			$datanya[] = $temp;
		}

		$bulan = $this->input->get("bulan");
		$tahun = $this->input->get("tahun");

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

		$data_arr = array(
			'datanya' => $datanya,
			'tgl_mulai' => date('d-m-Y', strtotime($this->tgl_pertama)),
			'tgl_hingga' => date('d-m-Y', strtotime($this->tgl_terakhir)),
			'instansi' => $this->dataInstansi,
			'hari' => $hari,
			'bulan' => $bulan_array[($bulan - 1)],
			'tahun' => $tahun
		);

		$nama_dokumen = "Laporan_Absensi_Makan_".str_replace(" ","_",$data_arr['instansi']->nama)."_Tanggal_".str_replace("-","_",$data_arr['tgl_mulai'])."_s/d_".str_replace("-","_",$data_arr['tgl_hingga']);
		$current_date = date('d/m/Y H:i:s');

		if($this->input->get("type") == 'pdf') {
			ini_set('memory_limit', '-1');
			//$html_header = $this->load->view('cetak/absensi_uang_makan_new/header', $data_arr, true); //render the view into HTML
			$html_body = $this->load->view('cetak/absensi_uang_makan_new/body', $data_arr, true); //render the view into HTML

			$this->load->library('pdf');
			$pdf=$this->pdf->load("en-GB-x","FOLIO-L","","",10,10,5,10,6,3,"L");
			$pdf->SetWatermarkImage('http://teko-cak.surabaya.go.id/assets/img/logo_pemkot_watermark.png', 0.7, '',array(90,38));
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

			$this->load->view('cetak/absensi_uang_makan_new/excel', $data_arr);

			ob_end_clean();
		}
		else {
			$this->load->view('cetak/absensi_uang_makan_new/body', $data_arr);
			//$this->output->enable_profiler(TRUE);
		}
	}

  public function printed_lama($bulan, $tahun, $id_instansi, $pns) {
		$detil_laporan	=	$this->db->query("
			select * from lap_uang_makan_detil
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


		$this->sudahAda	=	$this->log_laporan_model->getData("kd_instansi = '".$this->input->get('id_instansi')."' and tgl_log = '".$this->tgl_terakhir."' ");


		if ($this->input->get('id_instansi') == '5.09.00.93.00' || $this->input->get('id_instansi') == '5.02.00.00.00') {
			$dataLembur = "";
			$dataLembur .= '
			<table width="100%" class="cloth" cellspacing="0" cellpadding="0">
			<thead>
			<tr>
			<th>NO</th>
			<th>NAMA</th>
			<th>NIP</th>';
		}else{
			$dataLembur = "";
			$dataLembur .= '
			<table width="100%" class="cloth" cellspacing="0" cellpadding="0">
			<thead>
			<tr>
			<th>NO</th>
			<th>NAMA</th>';
		}

		while (strtotime($this->tgl_pertama) <= strtotime($this->tgl_terakhir )) {

			$dataLembur .= '<th>'.date ("d", strtotime($this->tgl_pertama)).'</th>';
			$this->tgl_pertama = date ("Y-m-d", strtotime("+1 days", strtotime($this->tgl_pertama)));
		}

        $dataLembur .= '<th>Jumlah Hari</th></tr></thead>';

		foreach ($detil_laporan as $key => $value) {
			$dataLembur .= "<tr align='center'>";

			$dataLembur .= "<td>".($key+1)."</td>";
            $dataLembur .= "<td align='left'>".$value->nama."</td>";


			foreach (json_decode($value->skor) as $skor) {
                $dataLembur .= "<td>".$skor."</td>";
            }

            $dataLembur .= "<th>".$value->jml_hari." Hari</th>";

			$dataLembur .= "</tr>";
		}


		if($this->session->userdata('username') <> 'damkar') {
			$this->load->library('dompdf_gen');
			$this->load->view('cetak/lap_uang_makan_view',[
				'dataLembur' => $dataLembur
			]);
			$paper_size  = 'folio'; //paper size
			$orientation = 'landscape'; //tipe format kertas
			$html = $this->output->get_output();
			$this->dompdf->set_paper($paper_size, $orientation);
			//Convert to PDF
			$this->dompdf->load_html($html);
			$this->dompdf->render();
			$this->dompdf->stream("laporan_uang_makan.pdf", array('Attachment'=>0));
		} else {
			$this->load->view('cetak/lap_uang_makan_view',[
				'dataLembur' => $dataLembur
			]);
		}
	}

	public function generate() {
        $this->load->library('konversi_menit');

		/** CEK APAKAH PERNAH PRINT LAPORAN */
		$bulan_get = $this->input->get('bulan') ? $this->input->get('bulan') : 0;
		$tahun_get = $this->input->get('tahun') ? $this->input->get('tahun') : '';
		$id_instansi_get = $this->input->get('id_instansi') ? $this->input->get('id_instansi') : '';
		$pns_get = $this->input->get('pns') ? $this->input->get('pns') : '';
		$queryCekSudahPrintLaporan	=	$this->db->query("
            select * from lap_uang_makan
            where bulan = '$bulan_get'
            and tahun = '$tahun_get'
			and id_instansi = '$id_instansi_get'
			and pns = '$pns_get'
            and deleted_at is null
		");

		if(! $queryCekSudahPrintLaporan->row()) {
            $this->session->set_flashdata('feedback_failed', 'Laporan Uang Makan belum pernah dibuat!. Silahkan Klik Tampilkan');

			redirect('lap_absensi_uang_makan','refresh');
		} else {
			/** CEK APAKAH ADA LAPORAN SUDAH DIKUNCI */
			$whereTahunBulan = $tahun_get . '-' . $bulan_get;

			$laporanTerkunci	= $this->db->query("
				select * from log_laporan
				where to_char(tgl_log, 'YYYY-MM') = '$whereTahunBulan'
				and is_kunci = 'Y'
			")->row_array();

			if($laporanTerkunci) {
				$this->session->set_flashdata('feedback_failed', 'Maaf, Laporan telah terkunci.');

				redirect('lap_skor', 'refresh');
			}
			#END

            if($this->input->get("pns") == 'y'){
                $wherePns 	= " and m.kode_status_pegawai='1'";
            }
            else{

                $wherePns 	= " and m.kode_status_pegawai <='6' and m.kode_status_pegawai > '1'";
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

			$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
			// Tanggal pertama pada bulan ini
			$tglMulai 	= date('Y-m-01', strtotime($hari_ini));
			// Tanggal terakhir pada bulan ini
			$tglSelesai 	= date('Y-m-t', strtotime($hari_ini));

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
						".$whereQuery."
					order by
						peh.kode_eselon,
						pgh.kode_golongan desc,
						m.nip
				");
				# end nambah status meninggal
			}

            $this->dataPegawai	=	$queryPegawai->result();
			//echo $this->db->last_query();


			/** CEK APAKAH ADA PROSES GENERATE DI USER LAINNYA */
			$data_uri = [
				'bulan' => $bulan_get,
				'tahun' => $tahun_get,
				'id_instansi' => $id_instansi_get,
				'pns' => $pns_get,
			];

			$queryGeneratingLaporan	= $this->db->query("
				select m.*, u.fullname from lap_uang_makan m
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
					select * from lap_uang_makan_detil
					where bulan = '$bulan_get'
					and tahun = '$tahun_get'
					and id_instansi = '$id_instansi_get'
					and pns = '$pns_get'
					and deleted_at is null
				")->result();

				$this->session->set_flashdata('feedback_warning_update', [
					'uri' => $data_uri,
					'data_generate' => $queryGeneratingLaporan,
					'jml_pegawai' => count($this->dataPegawai),
					'jml_tergenerate' => count($laporanTergenerate),
				]);

				redirect('lap_absensi_uang_makan','refresh');
			}
			#END


			/** UPDATE IS_DELETE JADI NULL */
			$this->load->model([
				'Lap_uang_makan_model',
				'Lap_uang_makan_detil_model'
			]);

			$where = [
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'			=> $pns_get,
				'deleted_at'	=> null,
			];

			$this->Lap_uang_makan_model->update($where, ['deleted_at' => date('Y-m-d H:i:s')]);

			$this->Lap_uang_makan_detil_model->update($where, ['deleted_at' => date('Y-m-d H:i:s')]);
			#end

            /** INSERT KE LAP_UANG_MAKAN */
            $data_uang_makan = [
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'			=> $pns_get,
				'id_pegawai'	=> $this->session->userdata('id_karyawan'),
			];

            $this->Lap_uang_makan_model->insert($data_uang_makan);
			#end


            $dataLembur = '';
            $i=1;
            foreach($this->dataPegawai as $dataPegawai){


                $hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
                // Tanggal pertama pada bulan ini
                $this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
                // Tanggal terakhir pada bulan ini
                $this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));

                if ($this->input->get('id_instansi') == '5.09.00.93.00') {
                	$dataLembur .= "<tr><td align='center'>".$i."</td>";
	                $dataLembur .= "<td>".$dataPegawai->nama."</td>";
	                $dataLembur .= "<td>".$dataPegawai->nip."</td>";
                }else{
                	$dataLembur .= "<tr><td align='center'>".$i."</td>";
	                $dataLembur .= "<td>".$dataPegawai->nama."</td>";
                }


                $hitungMasukTotal 	= 0;

                // untuk insert ke lap_uang_makan_detil
                $skor = [];

                if ($instansiRaw == '5.09.00.93.00' || $instansiRaw == '5.02.00.00.00') {
					$skor[0] = $nip;
				}

                $q_makan = $this->db->query("
				SELECT tgl::date as tanggal, extract(dow from tgl) as hari_tgl, dm.jadwal_masuk::text, dm.jadwal_pulang::text, finger_masuk::text, dm.kode_masuk, dm.kode_tidak_masuk
				FROM generate_series('".$this->tgl_pertama."', '".$this->tgl_terakhir."', '1 day'::interval) tgl
				LEFT JOIN data_mentah as dm ON tgl = dm.tanggal AND id_pegawai = '".$dataPegawai->id_pegawai."'
				order by tgl
				");

				$makan = $q_makan->result_array();

				for($i=0;$i<count($makan);$i++) {
					if($makan[$i]["kode_masuk"] == 'H') {
						$hitungMasuk = 1;
						$text 		 = '1';
					}
					else {
						if($makan[$i]["jadwal_masuk"] <> '') {
							if($makan[$i]["finger_masuk"] <> '') {
								if(strtotime($makan[$i]["finger_masuk"]) < strtotime($makan[$i]["jadwal_pulang"])) {
									$hitungMasuk = 1;
									$text 		 = '1';
								}
								else {
									$hitungMasuk = 0;
									$text 		 = '0';
								}
							}
							else {
								if($makan[$i]["kode_tidak_masuk"] <> '') {
									$hitungMasuk = 0;
									$text 		 = $makan[$i]["kode_tidak_masuk"];
								}
								else {
									$hitungMasuk = 0;
									$text 		 = '0';
								}
							}
						}
						else {
							if($makan[$i]["kode_tidak_masuk"] <> '') {
								$hitungMasuk = 0;
								$text 		 = $makan[$i]["kode_tidak_masuk"];
							}
							else {
								$hitungMasuk = 0;
								$text 		 = '0';
							}
						}
					}
					$dataLembur .= '<td>'.$text.'</td>';
					$skor[] = $text;
					$hitungMasukTotal += $hitungMasuk;
				}

				$dataLembur .= '<td align=center>'.$hitungMasukTotal.' Hari</td>';

                $urutan = $i;

                /** INSERT LAP REKAP INSTANSI DETIL */
                $data = [
                    'nama'			=> $dataPegawai->nama,
                    'skor'			=> json_encode($skor),
                    'bulan'			=> $bulan_get,
                    'tahun'			=> $tahun_get,
                    'id_instansi'	=> $id_instansi_get,
                    'pns'			=> $pns_get,
                    'jml_hari'		=> $hitungMasukTotal,
                    'urut'			=> $urutan
                ];

                $this->Lap_uang_makan_detil_model->insert($data);
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

			$this->Lap_uang_makan_model->update($where, ['finished_at' => date('Y-m-d H:i:s')]);
			#end


            $this->session->set_flashdata('feedback_success', 'Laporan Uang Makan berhasil terupdate!. Silahkan Klik Tampilkan');

            redirect('lap_absensi_uang_makan','refresh');
        }
		#end
	}

	public function stop() {
		$this->load->model(['Lap_uang_makan_model']);

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

		$this->Lap_uang_makan_model->update($where, ['finished_at' => date('Y-m-d H:i:s')]);

		$this->session->set_flashdata('feedback_success', 'Update Laporan Telah Dihentikan');

		redirect('lap_absensi_uang_makan','refresh');
	}

	public function update_via_url($bulan_get, $tahun_get, $id_instansi_get, $pns_get) {
        $this->load->library('konversi_menit');

		/** CEK APAKAH PERNAH PRINT LAPORAN */
		$queryCekSudahPrintLaporan	=	$this->db->query("
            select * from lap_uang_makan
            where bulan = '$bulan_get'
            and tahun = '$tahun_get'
			and id_instansi = '$id_instansi_get'
			and pns = '$pns_get'
            and deleted_at is null
		");

		if(! $queryCekSudahPrintLaporan->row()) {
            echo "<h1>SUDAH PERNAH BUAT LAPORAN</h1>";
		} else {
            /** CEK APAKAH ADA LAPORAN SUDAH DIKUNCI */
			$whereTahunBulan = $tahun_get . '-' . $bulan_get;

			$laporanTerkunci	= $this->db->query("
				select * from log_laporan
				where to_char(tgl_log, 'YYYY-MM') = '$whereTahunBulan'
				and is_kunci = 'Y'
			")->row_array();

			if($laporanTerkunci) {
				echo "<h1>feedback_failed', 'Maaf, Laporan telah terkunci.</h1>";
			}
			#END

            if($pns_get == 'y'){
                $wherePns 	= " and m.kode_status_pegawai='1'";
            }
            else{

                $wherePns 	= " and m.kode_status_pegawai <='6' and m.kode_status_pegawai > '1'";
            }

            $query_kode_sik = $this->db->query("select kode_sik, nama from m_instansi where kode = '".$id_instansi_get."'");
            $data_kode_sik = $query_kode_sik->row();

            /*highlight_string("<?php\n\$data =\n" . var_export($data_kode_sik->kode_sik, true) . ";\n?>");exit;*/

            if (substr($data_kode_sik->nama, 0, 9) != 'Kecamatan') {
                $kode_instansi_all = $id_instansi_get;
                $whereQuery = "pukh.kode_instansi = '".$kode_instansi_all."'".$wherePns;

            }else{
                $kode_instansi_all = substr($id_instansi_get, 0, 5);
                $whereQuery = "pukh.kode_instansi LIKE '".$kode_instansi_all.'%'."'".$wherePns;
            }

			$hari_ini 		= date($tahun_get."-".$bulan_get."-01");
			// Tanggal pertama pada bulan ini
			$tglMulai 	= date('Y-m-01', strtotime($hari_ini));
			// Tanggal terakhir pada bulan ini
			$tglSelesai 	= date('Y-m-t', strtotime($hari_ini));


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
                            h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi, h.langsung_pindah
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
                    ".$whereQuery."
                order by
                    peh.kode_eselon,
                    pgh.kode_golongan desc,
                    pjh.urut,
                    m.nip
            ");
            $dataPegawai	=	$queryPegawai->result();
			//echo $this->db->last_query();


			/** CEK APAKAH ADA PROSES GENERATE DI USER LAINNYA */
			$data_uri = [
				'bulan' => $bulan_get,
				'tahun' => $tahun_get,
				'id_instansi' => $id_instansi_get,
				'pns' => $pns_get,
			];

			$queryGeneratingLaporan	= $this->db->query("
				select m.*, u.fullname from lap_uang_makan m
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
					select * from lap_uang_makan_detil
					where bulan = '$bulan_get'
					and tahun = '$tahun_get'
					and id_instansi = '$id_instansi_get'
					and pns = '$pns_get'
					and deleted_at is null
				")->result();
			}
			#END


			/** UPDATE IS_DELETE JADI NULL */
			$this->load->model([
				'Lap_uang_makan_model',
				'Lap_uang_makan_detil_model'
			]);

			$where = [
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'			=> $pns_get,
				'deleted_at'	=> null,
			];

			$this->Lap_uang_makan_model->update($where, ['deleted_at' => date('Y-m-d H:i:s')]);

			$this->Lap_uang_makan_detil_model->update($where, ['deleted_at' => date('Y-m-d H:i:s')]);
			#end

            /** INSERT KE LAP_UANG_MAKAN */
            $data_uang_makan = [
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'			=> $pns_get,
				'id_pegawai'	=> 'dc7ae9c2-200c-11e7-aa72-000c29766abb',
			];

            $this->Lap_uang_makan_model->insert($data_uang_makan);
			#end


            $dataLembur = '';
            $i=1;
            foreach($dataPegawai as $dataPegawai){


                $hari_ini 		= date($tahun_get."-".$bulan_get."-01");
                // Tanggal pertama pada bulan ini
                $tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
                // Tanggal terakhir pada bulan ini
                $tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));

                $hitungMasukTotal 	= 0;

                // untuk insert ke lap_uang_makan_detil
                $skor = [];

                while (strtotime($tgl_pertama) <= strtotime($tgl_terakhir )) {


                    $queryHadir 	=	$this->db->query("
                    select
                        *
                    from
                        data_mentah

                    where
                        tanggal = '".$tgl_pertama."' and
                        id_pegawai = '".$dataPegawai->id_pegawai."'
                    ");
                    $dataHadir	=	$queryHadir->row();

                    $namaHari 	= date('D', strtotime($tgl_pertama));



                    $start_date = date ("Y-m-d", strtotime($tgl_pertama));
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
                    $dataLibur	=	$queryLibur->row();


                    $namaHari 	= date('D', strtotime($tgl_pertama));



                    if($namaHari == 'Sat' || $namaHari == 'Sun' || $dataLibur){
                        $queryRoster	=	$this->db->query("
                        select
                            *
                        from
                            t_roster

                        where
                            tanggal = '".$tgl_pertama."' and
                            id_pegawai = '".$dataPegawai->id_pegawai."' and
                            id_jenis_roster != '12a9b2c8-fcd3-4540-b5cb-28edfa15fbb4'
                        ");
                        $dataRoster	=	$queryRoster->row();


                        if($dataRoster){
                            $hitungMasuk 	= 1;
                            // untuk insert ke lap_uang_makan_detil
                            $skor[] = 1;
                        }
                        else{
							if(!$this->dataLibur && ($namaHari == 'Sat' || $namaHari == 'Sun')) {
								$role_finger = $this->cek_jam_diperbolehkan_finger($this->tgl_pertama, $dataPegawai->id_pegawai);
								if($role_finger){
									if($this->dataHadir){
										if($this->dataHadir->finger_masuk != ''){
											$hitungMasuk 	= 1;
											$text 			=	'1';
										}
										else{
											$hitungMasuk = 0;
											$text 		=	 $this->dataHadir->kode_tidak_masuk ;
										}
									}
									else{
										$hitungMasuk 	= 	0;
										$text 			=	0;
									}
								}
								else {
									$hitungMasuk = 0;
									$text= '0';
								}
							}
							else {
								$hitungMasuk = 0;
								$text = '0';
							}
							$dataLembur .= '<td>'.$text.'</td>';
							$skor[] = $text;
                        }
                    }
                    else{
                        if($dataHadir){
                            if($dataHadir->finger_masuk != ''){
                                $hitungMasuk 	= 1;
                                $text 			=	'1';
                            }
                            else{
								# start potensi perwali
                                $hitungMasuk = 0;
                                $text 		=	 $dataHadir->kode_tidak_masuk ;
								# end potensi perwali
                            }
                        }
                        else{
                            $hitungMasuk 	= 	0;
                            $text 			=	0;
                        }

                        // untuk insert ke lap_uang_makan_detil
                        $skor[] = $text;
                    }


                    $hitungMasukTotal += $hitungMasuk;

                    $tgl_pertama = date ("Y-m-d", strtotime("+1 days", strtotime($tgl_pertama)));
                }

                /** INSERT LAP REKAP INSTANSI DETIL */
                $data = [
                    'nama'			=> $dataPegawai->nama,
                    'skor'			=> json_encode($skor),
                    'bulan'			=> $bulan_get,
                    'tahun'			=> $tahun_get,
                    'id_instansi'	=> $id_instansi_get,
                    'pns'			=> $pns_get,
                    'jml_hari'		=> $hitungMasukTotal
                ];

                $this->Lap_uang_makan_detil_model->insert($data);
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

			$this->Lap_uang_makan_model->update($where, ['finished_at' => date('Y-m-d H:i:s')]);
			#end


            echo "SUKSESSSSSSSSSSSSSSSSSSSSSSS";
        }
		#end
	}

	function cek_jam_diperbolehkan_finger($date, $id_pegawai){
      return $this->_ci->db->query("SELECT
                              d.nama,
                              A.id_role_jam_kerja,
                              A.tgl_mulai,
                              A.id_pegawai,
                              b.id_jam_kerja,
                              b.id_hari,
                              C.jam_akhir_scan_masuk,
                              C.jam_akhir_scan_pulang,
                              C.jam_mulai_scan_masuk,
                              C.jam_mulai_scan_pulang,
                              C.jam_masuk,
                              C.jam_pulang
                            FROM
                              m_pegawai_role_jam_kerja_histori AS A,
                              m_role_jam_kerja_detail AS B,
                              m_jam_kerja AS C,
                            m_hari as d
                            WHERE
                              A.id_pegawai = '$id_pegawai'
                            AND A.id_role_jam_kerja = b.id_role
                            AND b.id_jam_kerja = c.id
                            AND d.id = b.id_hari
                            and a.tgl_mulai <= '$date'
                            and b.id_hari = (select extract(isodow from date '$date'))
                            order by a.tgl_mulai desc
                            limit 1")->row();
  	}

}
