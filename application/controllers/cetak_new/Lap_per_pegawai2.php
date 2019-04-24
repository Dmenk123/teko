<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class lap_per_pegawai2 extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model','log_laporan_model']);
	}

	public function index() {

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
		//$this->dataInstansi = 	$queryInstansi->row();

		$id_pegawai = $this->input->get('id_pegawai');

		$queryPegawai 		=	$this->db->query("
			select mp.id, mp.nama, mp.nip, pih.nama_instansi, pjh.nama_jenis_jabatan
			from m_pegawai mp
			LEFT JOIN LATERAL (
				SELECT mi.nama as nama_instansi
					FROM m_pegawai_unit_kerja_histori mpukh
					LEFT JOIN m_unit_organisasi_kerja muok ON mpukh.kode_unor = muok.kode 
					LEFT JOIN m_instansi mi ON muok.kode_instansi = mi.kode
					WHERE mpukh.tgl_mulai <= '$tanggal' AND mpukh.id_pegawai = '$id_pegawai' or (mpukh.langsung_pindah = 't' and mpukh.id_pegawai = '$id_pegawai')
					ORDER BY mpukh.tgl_mulai DESC
					LIMIT 1
			) pih ON TRUE
			LEFT JOIN LATERAL (
				SELECT mjj.nama as nama_jenis_jabatan
					FROM m_pegawai_jabatan_histori mpjh
					LEFT JOIN m_jenis_jabatan mjj ON mpjh.kode_jabatan = mjj.kode
					WHERE mpjh.tgl_mulai <= '$tanggal' AND mpjh.id_pegawai = '$id_pegawai' or (mpjh.langsung_pindah = 't' and mpjh.id_pegawai = '$id_pegawai')
					ORDER BY mpjh.tgl_mulai DESC
					LIMIT 1
			) pjh ON TRUE
			where mp.id = '$id_pegawai'
		");

		$this->dataPegawai = $queryPegawai->row();

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
		//$this->dataPegawai = $this->pegawai_model->getDataJoin($where,$select,$join);
		//echo $this->db->last_query();

		$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
		// Tanggal pertama pada bulan ini
		$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
		// Tanggal terakhir pada bulan ini
		$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));

   	$this->sudahAda	=	$this->log_laporan_model->getData("kd_instansi = '".$this->input->get('id_instansi')."' and tgl_log = '".$this->tgl_terakhir."' ");

    	/** CEK APAKAH PERNAH PRINT LAPORAN PER PEGAWAI */
		$bulan_get = $this->input->get('bulan');
		$tahun_get = $this->input->get('tahun');
		$id_pegawai_get = $this->input->get('id_pegawai');
		$id_instansi_get = $this->input->get('id_instansi');

		$queryCekSudahPrintLaporan	=	$this->db->query("
			select * from lap_per_pegawai
			where bulan = '$bulan_get'
			and tahun = '$tahun_get'
			and id_pegawai = '$id_pegawai_get'
			and deleted_at is null
		");

		if($queryCekSudahPrintLaporan->row()) {
			$this->dataLaporan = $this->db->query("
				select * from lap_per_pegawai_detil
				where bulan = '$bulan_get'
				and tahun = '$tahun_get'
				and id_pegawai = '$id_pegawai_get'
				and deleted_at is null
					order by tanggal asc
			")->result();

			$tgl_mulai = date('d-m-Y', strtotime($this->tgl_pertama));
			$tgl_hingga = date('d-m-Y', strtotime($this->tgl_terakhir));

			$nama_dokumen = "Laporan_Per_Pegawai_".str_replace(" ","_",$this->dataPegawai->nama)."_Tanggal_".str_replace("-","_",$tgl_mulai)."_s/d_".str_replace("-","_",$tgl_hingga);
			$current_date = date('d/m/Y H:i:s');

			if($this->input->get("type") == 'pdf') {
				ini_set('memory_limit', '-1');
				//$html_header = $this->load->view('cetak/perpegawai_new/header', $data_arr, true); //render the view into HTML
				$html_body = $this->load->view('cetak/perpegawai_new/body', null, true); //render the view into HTML

				$this->load->library('pdf');
				$pdf=$this->pdf->load("en-GB-x","FOLIO","","",10,10,5,10,6,3,"P");
				$pdf->SetWatermarkImage(base_url('assets/img/logo_pemkot_watermark.png'), 0.7, '', array(90,38));

				$pdf->showWatermarkImage = true;
				//$pdf->showImageErrors = true;
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

				$this->load->view('cetak/perpegawai_new/excel');

				ob_end_clean();
			}
			else {
				$this->load->view('cetak/perpegawai_new/body');
			}
			return;

		} else {

			$this->load->model([
				'Lap_per_pegawai_model',
				'Lap_per_pegawai_detil_model'
			]);

			$data_lap_per_pegawai = [
				'bulan'					=> $bulan_get,
				'tahun'					=> $tahun_get,
				'id_pegawai'		=> $id_pegawai_get,
				'nip'           => $this->dataPegawai->nip,
				'nama'          => $this->dataPegawai->nama,
				'instansi'      => $this->dataPegawai->nama_instansi,
				'jabatan'       => $this->dataPegawai->nama_jenis_jabatan,
			];

			$this->Lap_per_pegawai_model->insert($data_lap_per_pegawai);
		}
		#end

		$whereDataLaporan  = "id_pegawai='".$this->input->get('id_pegawai')."' and tanggal  <= '".$this->tgl_terakhir ."'  and
						tanggal  >= '".$this->tgl_pertama ."' ";
		$this->dataLaporan = $this->data_mentah_model->showData($whereDataLaporan,"","tanggal");

		/** INSERT KE LAP_PER_PEGAWAI_DETIL */
		$this->createDetilBaru($data_lap_per_pegawai, $this->dataLaporan);
		#END
		redirect(base_url('cetak_new/lap_per_pegawai2/?bulan='.$bulan_get.'&tahun='.$tahun_get.'&id_instansi='.$id_instansi_get.'&id_pegawai='.$id_pegawai_get.'&type=html'));
	}

	public function createDetilBaru($data_pegawai, $laporan) {
	  $this->load->model([
	      'Lap_per_pegawai_detil_model'
	  ]);

	    $telatMasuk   	 = 0;
			$totPulangCepat  = 0;
			$totLembur 	  	 = 0;
			$totLemburDiakui = 0;
			$overTimeSemua	 = 0;

	    foreach ($laporan as $key => $data) {
		    if($data->datang_telat> 480){
	        $telatMasukBenar = 480;
		    }
		    else{
	        $telatMasukBenar = $data->datang_telat;
		    }

	      $datangTelat	=	$this->konversi_menit->hitung($telatMasukBenar);

	      if ($data->pulang_cepat > 480) {
	        $pulangCepat	  =	$this->konversi_menit->hitung(480);
	        $pulangCepatRaw = 480;
	      } else {
	        $pulangCepat	  =	$this->konversi_menit->hitung($data->pulang_cepat);
	        $pulangCepatRaw = $data->pulang_cepat;
	      }

				$arr_cek = array('DL','DK');
				if(in_array($data->jam_kerja,$arr_cek)) {
					$dataLembur					 = $this->konversi_menit->hitung($data->lembur);
					$dataLemburDiakui	 	 = $this->konversi_menit->hitung($data->lembur_diakui);
					$dataLemburRaw		   = $data->lembur;
					$dataLemburDiakuiRaw = $data->lembur_diakui;
				}
	      else if ($data->finger_pulang == NULL) {
	        $dataLembur	         = 0;
					$dataLemburDiakui    = 0;
	        $dataLemburRaw       = 0;
					$dataLemburDiakuiRaw = 0;
	      }
				else{
	        $dataLembur					 = $this->konversi_menit->hitung($data->lembur);
					$dataLemburDiakui		 = $this->konversi_menit->hitung($data->lembur_diakui);
	        $dataLemburRaw			 = $data->lembur;
					$dataLemburDiakuiRaw = $data->lembur_diakui;
	      }

				$overtime_json = json_encode($dataLembur);
				$overtime_diakui_json = json_encode($dataLemburDiakui);

	      	$telatMasuk		   += $telatMasukBenar;
	      	$totPulangCepat	 += $pulangCepatRaw;
			$totLembur		   += $dataLemburRaw;
			$totLemburDiakui += $dataLemburDiakuiRaw;

			if($data->hari == 'SABTU' or $data->hari == 'MINGGU'){
	          	if($data->hari == 'SABTU' and $dataLembur) {
	              	$jumlah_lembur = [
	                  	'sabtu'     => $dataLembur,
	                  	'minggu'    => ['-', '-']
	              	];
	      		} elseif($data->hari == 'MINGGU' and $dataLembur) {
		          	$jumlah_lembur = [
		              	'sabtu'     => ['-', '-'],
		              	'minggu'    => $dataLembur
		          	];
		      	} else {
		          	$jumlah_lembur = [
		              	'sabtu'     => ['-', '-'],
		              	'minggu'    => ['-', '-']
		          	];
		      	}
	      	} else {
	          	$jumlah_lembur = [
	              	'sabtu'     => ['-', '-'],
	              	'minggu'    => ['-', '-']
	          	];
	      	}

	      /** INSERT KE LAP_PER_PEGAWAI_DETIL */
	      $data_lap_per_pegawai_detil = [
					'bulan'			      => $data_pegawai['bulan'],
					'tahun'			      => $data_pegawai['tahun'],
	        'id_pegawai'      => $data_pegawai['id_pegawai'],
	        'tanggal'         => date('Y-m-d', strtotime($data->tanggal_indo)),
	        'jam_kerja'       => $data->jam_kerja,
	        'masuk'           => $data->finger_masuk_jam,
	        'telat_masuk'     => json_encode($datangTelat),
	        'pulang'          => $data->finger_pulang_jam,
	        'cepat_pulang'    => json_encode($pulangCepat),
	        'overtime'        => $overtime_json,
	        'overtime_diakui' => $overtime_diakui_json,
	        'jumlah_lembur'   => json_encode($jumlah_lembur),
	        'keterangan'      => $data->kode_masuk
				];

	      $this->Lap_per_pegawai_detil_model->insert($data_lap_per_pegawai_detil);
	      #END
	    }
	}

	public function createDetil($data_pegawai, $laporan) {
	  $this->load->model([
	      'Lap_per_pegawai_detil_model'
	  ]);

    $telatMasuk 	= 0;
		$totPulangCepat = 0;
		$totLembur 		= 0;
		$totLemburMINGGU = 0;
		$totLemburSABTU = 0;
		$overTimeSemua	= 0;
		$sabtu		 	= 0;
    $minggu		 	= 0;

    foreach ($laporan as $key => $data) {
	    if($data->datang_telat> 480){
	        $telatMasukBenar = 480;
	    }
	    else{
	        $telatMasukBenar = $data->datang_telat;
	    }

      $datangTelat	=	$this->konversi_menit->hitung($telatMasukBenar);

      if ($data->pulang_cepat > 480) {
          $pulangCepat	=	$this->konversi_menit->hitung(480);
          $pulangCepatRaw = 480;
      } else {
          $pulangCepat	=	$this->konversi_menit->hitung($data->pulang_cepat);
          $pulangCepatRaw = $data->pulang_cepat;
      }

			$arr_cek = array('DL','DK');
			if(in_array($data->jam_kerja,$arr_cek)) {
				$dataLembur		=	$this->konversi_menit->hitung($data->lembur);
				$dataLemburRaw	=	$data->lembur;
			}
      else if ($data->finger_pulang == NULL) {
          $dataLembur		=	0;
          $dataLemburRaw	=	0;
      }
			else{
          $dataLembur		=	$this->konversi_menit->hitung($data->lembur);
          $dataLemburRaw	=	$data->lembur;
      }

      if($data->hari != 'SABTU' && $data->hari != 'MINGGU'){
          $overtime_json = json_encode($dataLembur);
      } else {
          $overtime_json = json_encode(['-', '-']);
      }

      if($data->hari == 'SABTU' or $data->hari == 'MINGGU'){
          if($data->hari == 'SABTU' and $dataLembur) {
              $jumlah_lembur = [
                  'sabtu'     => $dataLembur,
                  'minggu'    => ['-', '-']
              ];
          } elseif($data->hari == 'MINGGU' and $dataLembur) {
              $jumlah_lembur = [
                  'sabtu'     => ['-', '-'],
                  'minggu'    => $dataLembur
              ];
          } else {
              $jumlah_lembur = [
                  'sabtu'     => ['-', '-'],
                  'minggu'    => ['-', '-']
              ];
          }
      } else {
          $jumlah_lembur = [
              'sabtu'     => ['-', '-'],
              'minggu'    => ['-', '-']
          ];
      }


      $telatMasuk		+= $telatMasukBenar;
      $totPulangCepat	+= $pulangCepatRaw;

      if($data->hari!='MINGGU' && $data->hari!='SABTU'){

          $totLembur		+= $dataLemburRaw;
      }

      if($data->hari=='MINGGU' ){

          $totLemburMINGGU		+= $dataLemburRaw;
      }

      if( $data->hari=='SABTU'){

          $totLemburSABTU		+= $dataLemburRaw;
      }

      /** INSERT KE LAP_PER_PEGAWAI_DETIL */
      $data_lap_per_pegawai_detil = [
				'bulan'			=> $data_pegawai['bulan'],
				'tahun'			=> $data_pegawai['tahun'],
        'id_pegawai'	=> $data_pegawai['id_pegawai'],
        'tanggal'       => date('Y-m-d', strtotime($data->tanggal_indo)),
        'jam_kerja'     => $data->jam_kerja,
        'masuk'         => $data->finger_masuk_jam,
        'telat_masuk'   => json_encode($datangTelat),
        'pulang'        => $data->finger_pulang_jam,
        'cepat_pulang'  => json_encode($pulangCepat),
        'overtime'      => $overtime_json,
        'jumlah_lembur' => json_encode($jumlah_lembur),
        'keterangan'    => $data->kode_masuk,
			];

      $this->Lap_per_pegawai_detil_model->insert($data_lap_per_pegawai_detil);
      #END
    }
	}

	public function generate() {
		$this->load->library('konversi_menit');
			
		/** CEK APAKAH PERNAH PRINT LAPORAN */
		$bulan_get = $this->input->get('bulan') ? $this->input->get('bulan') : 0;
		$tahun_get = $this->input->get('tahun') ? $this->input->get('tahun') : '';
		$id_pegawai_get = $this->input->get('id_pegawai') ? $this->input->get('id_pegawai') : '';
		$id_instansi_get = $this->input->get('id_instansi') ? $this->input->get('id_instansi') : '';
		//var_dump($bulan_get, $tahun_get, $id_pegawai_get, $id_instansi_get);exit;

		$tanggal_mulai_kunci = $tahun_get . '-' . $bulan_get . '-' . '01';
		$tanggal_akhir_kunci = date("Y-m-t", strtotime($tanggal_mulai_kunci));

		$queryCekSudahPrintLaporan	=	$this->db->query("
			select * from lap_per_pegawai
			where bulan = '$bulan_get'
			and tahun = '$tahun_get'
			and id_pegawai = '$id_pegawai_get'
			and deleted_at is null
		");

		if(!$queryCekSudahPrintLaporan->row()) {
			$this->session->set_flashdata('feedback_failed', 'Laporan Per Pegawai belum pernah dibuat!. Silahkan Klik Tampilkan');
			redirect('lap_per_pegawai','refresh');
		} else {
			/** CEK APAKAH ADA LAPORAN SUDAH DIKUNCI */
			//batasan CUTOFF LAPORAN
			$tgl_batas2 	= "2019-01-01";
			$hariBatas2		= strtotime($tgl_batas2);
			$hari_generate = strtotime($tanggal_akhir_kunci);
			$whereTahunBulan = $tahun_get . '-' . $bulan_get;

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

			$where = array('id' => $id_pegawai_get);
			$peg = $this->pegawai_model->getData($where);

			if($peg->kode_status_pegawai == 2) {
				$laporanTerkunci = false;
			}

			if($laporanTerkunci) {
				$this->session->set_flashdata('feedback_failed', 'Maaf, Laporan telah terkunci.');
				redirect('lap_per_pegawai', 'refresh');
			}
			#END

			/** UPDATE IS_DELETE JADI NULL */
			$this->load->model([
				'Lap_per_pegawai_model',
				'Lap_per_pegawai_detil_model'
			]);

			$where = [
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_pegawai'	=> $id_pegawai_get,
				'deleted_at'	=> null,
			];

			$this->Lap_per_pegawai_model->update($where, ['deleted_at' => date('Y-m-d H:i:s')]);

			$this->Lap_per_pegawai_detil_model->update($where, ['deleted_at' => date('Y-m-d H:i:s')]);
			#end

			/** INSERT LAPORAN BARU */
			$tanggal	=	$this->input->get('tahun')."-".$this->input->get('bulan')."-01";

			$id_pegawai = $this->input->get('id_pegawai');

			$queryPegawai 		=	$this->db->query("
					select mp.id, mp.nama, mp.nip, pih.nama_instansi, pjh.nama_jenis_jabatan
					from m_pegawai mp
					LEFT JOIN LATERAL (
							SELECT mi.nama as nama_instansi
									FROM m_pegawai_unit_kerja_histori mpukh
									LEFT JOIN m_unit_organisasi_kerja muok ON mpukh.kode_unor = muok.kode
									LEFT JOIN m_instansi mi ON muok.kode_instansi = mi.kode
									WHERE mpukh.tgl_mulai <= '$tanggal' AND mpukh.id_pegawai = '$id_pegawai' or (mpukh.langsung_pindah = 't' and mpukh.id_pegawai = '$id_pegawai')
									ORDER BY mpukh.tgl_mulai DESC
									LIMIT 1
					) pih ON TRUE
					LEFT JOIN LATERAL (
							SELECT mjj.nama as nama_jenis_jabatan
									FROM m_pegawai_jabatan_histori mpjh
									LEFT JOIN m_jenis_jabatan mjj ON mpjh.kode_jabatan = mjj.kode
									WHERE mpjh.tgl_mulai <= '$tanggal' AND mpjh.id_pegawai = '$id_pegawai' or (mpjh.langsung_pindah = 't' and mpjh.id_pegawai = '$id_pegawai')
									ORDER BY mpjh.tgl_mulai DESC
									LIMIT 1
					) pjh ON TRUE
					where mp.id = '$id_pegawai'
			");


			$dataPegawai = $queryPegawai->row();

			/** INSERT KE LAP_PER_PEGAWAI */
			$data_lap_per_pegawai = [
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_pegawai'	=> $id_pegawai_get,
				'nip'           => $dataPegawai->nip,
				'nama'          => $dataPegawai->nama,
				'instansi'      => $dataPegawai->nama_instansi,
				'jabatan'       => $dataPegawai->nama_jenis_jabatan,
			];

			$this->Lap_per_pegawai_model->insert($data_lap_per_pegawai);
			#end

			$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
			// Tanggal pertama pada bulan ini
			$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
			// Tanggal terakhir pada bulan ini
			$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));

			$whereDataLaporan  = "id_pegawai='".$id_pegawai_get."' and tanggal  <= '".$this->tgl_terakhir ."'  and
			tanggal  >= '".$this->tgl_pertama ."' ";
			$dataLaporan = $this->data_mentah_model->showData($whereDataLaporan,"","tanggal");


			/** INSERT KE LAP_PER_PEGAWAI_DETIL */
			$this->createDetilBaru($data_lap_per_pegawai, $dataLaporan);
			#END

			#end
			//var_dump('ganteng');exit;
			//redirect('lap_per_pegawai', 'refresh');
			redirect(base_url('cetak_new/lap_per_pegawai2/?bulan='.$bulan_get.'&tahun='.$tahun_get.'&id_instansi='.$id_instansi_get.'&id_pegawai='.$id_pegawai_get.'&type=html'));
		}
		#end
	}

}
