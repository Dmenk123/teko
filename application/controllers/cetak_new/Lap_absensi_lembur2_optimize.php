<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class lap_absensi_lembur2 extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model','log_laporan_model']);
	}

	function cektanggal($date){
		$dayofweek = date('w', strtotime($date));
		echo date("w", strtotime("2018-02-25"));
	}

	function UpdatePerPegawai(){

		$this->load->library('migrasi_data');
		$id_pegawai = $this->input->post('id_pegawai');

		if ($id_pegawai != '') {
			$begin = new DateTime( date("Y-m-d", strtotime("-7 day", strtotime(date("Y-m-d")))) );
			$end   = new DateTime( date("Y-m-d") );

			for($i = $begin; $i <= $end; $i->modify('+1 day')){		

				if($this->migrasi_data->count_data_mentah_pegawai( $i->format("Y-m-d"), $id_pegawai)->jumlah == 0){
					$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $id_pegawai, "insert", false);
				}
				else{
					$this->migrasi_data->cek_ulang_data_mentah($i->format("Y-m-d"), $id_pegawai, "update", false);
				}
			}
			//return true;
			echo json_encode(['status' => true]);
		}else{
			echo json_encode(['status' => false]);
		}
		
	}

	function update_selesai(){
		$this->load->model('antrian_generate_model');
		$id_user   	   = $this->input->post('id_user');
		$kode_instansi = $this->input->post('kode_instansi');
		$start_at	   = $this->input->post("start_at");
		
		$dt_update = array(
			'finish_at'  => date('Y-m-d H:i:s')
		);

		$dt_where = "id_user = '".$id_user."' and kode_instansi = '".$kode_instansi."' and start_at = '".$start_at."'";

		$this->antrian_generate_model->update($dt_where, $dt_update);
		
		$ret = array(
			'status' => 'sukses',
			'pesan'  => 'Sukses'
		);
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

		$this->bulan 	=	$namaBulan[$this->input->get('bulan')];
		$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
		// Tanggal pertama pada bulan ini
		$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
		// Tanggal terakhir pada bulan ini
		$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));
		
		$this->sudahAda	=	$this->log_laporan_model->getData("kd_instansi = '".$this->input->get('id_instansi')."' and tgl_log = '".$this->tgl_terakhir."' ");

		$dataLembur = "";
		$dataLembur .= '
		<table width="100%" class="cloth" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<th>NO</th>
		<th>NAMA</th>
		<th>NIP</th>';

		while (strtotime($this->tgl_pertama) <= strtotime($this->tgl_terakhir )) {

			$dataLembur .= '<th>'.date ("d", strtotime($this->tgl_pertama)).'</th>';
			$this->tgl_pertama = date ("Y-m-d", strtotime("+1 days", strtotime($this->tgl_pertama)));
		}

		$dataLembur .= '<th>Total</th><th>Skor Lembur (%)</th></tr>';

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

		$kodeAwalDinas	=	substr($this->input->get('id_instansi'),0,4);

		if($this->input->get("pns_get") == 'y'){
            $wherePns 	= " and m.kode_status_pegawai !='5'";
        }
        else{

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

		$tanggal	=	$this->input->get('tahun')."-".$this->input->get('bulan')."-01";

        $tglSelesai 	= date('Y-m-t', strtotime($tanggal));

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
        
        /** CEK APAKAH PERNAH PRINT LAPORAN */
		$bulan_get = $this->input->get('bulan');
		$tahun_get = $this->input->get('tahun');
		$id_instansi_get = $this->input->get('id_instansi');
		$pns_get = $this->input->get('pns');

		$queryCekSudahPrintLaporan	=	$this->db->query("
			select * from lap_absensi_lembur
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
				select * from lap_absensi_lembur
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
					select m.*, u.fullname from lap_absensi_lembur m
					join c_security_user_new u on m.id_pegawai = u.id
					where bulan = '$bulan_get'
					and tahun = '$tahun_get'
					and id_instansi = '$id_instansi_get'
					and pns = '$pns_get'
					and deleted_at is null
					and finished_at is null
				")->row_array();

				$laporanTergenerate	= $this->db->query("
					select * from lap_absensi_lembur_detil
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

				redirect('lap_absensi_lembur','refresh');
			}
		} 
		else 
		{
			$this->load->model(['Lap_absensi_lembur_model','Lap_absensi_lembur_detil_model']);

			$data = [
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'			=> $pns_get,
				'id_pegawai'	=> $this->session->userdata('id_karyawan'),
			];

			$this->Lap_absensi_lembur_model->insert($data);
		}
        #end
        		
		$i=1;
		foreach($this->dataPegawai as $dataPegawai){


			$hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
			// Tanggal pertama pada bulan ini
			$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
			// Tanggal terakhir pada bulan ini
			$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));

			$dataLembur .= "<tr><td align='center'>".$i."</td>";
			$dataLembur .= "<td>".$dataPegawai->nama."</td>";
			$dataLembur .= "<td>".$dataPegawai->nip."</td>";

			$totalLemburJumlah 			= 0;
			$totalLemburJumlahDiakui 	= 0;

            // untuk insert ke lap_absensi_lembur_detil
            $skor = [];
            
			while (strtotime($this->tgl_pertama) <= strtotime($this->tgl_terakhir )) {


				$queryJumlahLembur	=	$this->db->query("select lembur,lembur_diakui from data_mentah where id_pegawai='".$dataPegawai->id_pegawai."' and tanggal='".$this->tgl_pertama."'");
				$dataHasilLembur	=	$queryJumlahLembur->row();

				if($dataHasilLembur){
					$lemburJumlah = $dataHasilLembur->lembur;
					$lemburJumlahDiakui = $dataHasilLembur->lembur_diakui;
				}
				else{
					$lemburJumlah = "0";
					$lemburJumlahDiakui = "0";
				}
				$lembur = $this->konversi_menit->hitung($lemburJumlah);

				if($lemburJumlah == 0){
					$color="red";
				}
				elseif($lemburJumlah != $lemburJumlahDiakui ){
					$color="red";
				}
				else{
					$color="";
				}

				//$dataLembur .= "<td align='center' >".$lemburJumlahDiakui." -- ".$lemburJumlah." -- <span style='color:".$color."'>".sprintf("%02d", $lembur['jam_angka'])." : ".sprintf("%02d",$lembur['menit_angka'])."</span></td>";
                $dataLembur .= "<td align='center' ><span style='color:".$color."'>".sprintf("%02d", $lembur['jam_angka'])." : ".sprintf("%02d",$lembur['menit_angka'])."</span></td>";
                
                // untuk insert ke lap_absensi_lembur_detil
                $skor[] = [
                    'color' => $color,
                    'value' => sprintf("%02d", $lembur['jam_angka'])." : ".sprintf("%02d",$lembur['menit_angka'])
                ];


				$totalLemburJumlah += $lemburJumlah;
				$totalLemburJumlahDiakui += $lemburJumlahDiakui;

				$this->tgl_pertama = date ("Y-m-d", strtotime("+1 days", strtotime($this->tgl_pertama)));
			}

			$jumlahPersen 	= round(($totalLemburJumlah / 1800) * 100);
			if($jumlahPersen > 99){
				$jumlahPersen = 100;
			}
			else{
				$jumlahPersen = "<span style='color:orange;'>".$jumlahPersen."</span>";
			}

			$jumlahLembur			=	$this->konversi_menit->hitung($totalLemburJumlahDiakui);

			$bulan 		=	date('Y-m', strtotime($hari_ini));
			if($bulan =='2018-05' || $bulan =='2018-06'){
				$where	=	"and jenis = 'RAMADHAN'";
			}
			else{
				$where	=	"and jenis = 'BIASA'";
			}

			$queryPersen 	=	$this->db->query("select skor from m_skor_lembur where menit_mulai <='".$totalLemburJumlahDiakui."' and menit_akhir >= '".$totalLemburJumlahDiakui."' $where");
			$dataPersen		=	$queryPersen->row();


			$dataLembur .= "<td align='center' ><b>".sprintf("%02d", $jumlahLembur['jam_angka'])." : ".sprintf("%02d", $jumlahLembur['menit_angka'])."</b></td>";
			$dataLembur .= "<td align='center'>".$dataPersen->skor."</td>";
            $dataLembur .= "</tr>";
            
           
            /** INSERT LAP REKAP LEMBUR DETIL */	
			$data = [
                'nip'			=> $dataPegawai->nip,
                'nama'			=> $dataPegawai->nama,
				'skor'			=> json_encode($skor),
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'			=> $pns_get,
                'total'		    => sprintf("%02d", $jumlahLembur['jam_angka'])." : ".sprintf("%02d", $jumlahLembur['menit_angka']),
                'skor_persen'	=> $dataPersen->skor,
			];
	
			$this->Lap_absensi_lembur_detil_model->insert($data);
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

		$this->Lap_absensi_lembur_model->update($where, ['finished_at' => date('Y-m-d H:i:s')]);
		#end

		$this->load->view('cetak/lap_absensi_lembur_view',[
			'dataLembur' => $dataLembur,
			'bulan'		=> $this->bulan
		]);
    }
    
    public function printed($bulan, $tahun, $id_instansi, $pns) {

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

		$detil_laporan	=	$this->db->query("
			select * from lap_absensi_lembur_detil
			where bulan = '$bulan'
			and tahun = '$tahun'
			and id_instansi = '$id_instansi'
			and pns = '$pns'
			and deleted_at is null
		")->result();

        $hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
		// Tanggal pertama pada bulan ini
		$this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
		// Tanggal terakhir pada bulan ini
		$this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));

				
		$this->sudahAda	=	$this->log_laporan_model->getData("kd_instansi = '".$this->input->get('id_instansi')."' and tgl_log = '".$this->tgl_terakhir."' ");


		$dataLembur = "";
		$dataLembur .= '
		<table width="100%" class="cloth" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<th>NO</th>
        <th>NAMA</th>
        <th>NIP</th>';

		while (strtotime($this->tgl_pertama) <= strtotime($this->tgl_terakhir )) {

			$dataLembur .= '<th>'.date ("d", strtotime($this->tgl_pertama)).'</th>';
			$this->tgl_pertama = date ("Y-m-d", strtotime("+1 days", strtotime($this->tgl_pertama)));
		}

        $dataLembur .= '<th>Total</th><th>Skor Lembur (%)</th></tr>';
        
		foreach ($detil_laporan as $key => $value) {
			$dataLembur .= "<tr>";

			$dataLembur .= "<td>".($key+1)."</td>";
            $dataLembur .= "<td>".$value->nama."</td>";
            $dataLembur .= "<td>".$value->nip."</td>";
            
            $skors = json_decode($value->skor);
            
			foreach ($skors as $skor) {
                $dataLembur .= "<td><span style='color:".$skor->color."'>".$skor->value."</span></td>";
            }

            $dataLembur .= "<th>".$value->total."</th>";
            $dataLembur .= "<th>".$value->skor_persen."</th>";

			$dataLembur .= "</tr>";
		}
        
		$this->load->view('cetak/lap_absensi_lembur_view',[
			'dataLembur' => $dataLembur,
			'bulan'		 => $namaBulan[$bulan]
		]);
	}

	public function generate() {
        $this->load->library('konversi_menit');

		/** CEK APAKAH PERNAH PRINT LAPORAN */
		$bulan_get = $this->input->get('bulan') ? $this->input->get('bulan') : 0;
		$tahun_get = $this->input->get('tahun') ? $this->input->get('tahun') : '';
		$id_instansi_get = $this->input->get('id_instansi') ? $this->input->get('id_instansi') : '';
		$pns_get = $this->input->get('pns') ? $this->input->get('pns') : '';
		$queryCekSudahPrintLaporan	=	$this->db->query("
            select * from lap_absensi_lembur
            where bulan = '$bulan_get'
            and tahun = '$tahun_get'
			and id_instansi = '$id_instansi_get'
			and pns = '$pns_get'
            and deleted_at is null
		");

		if(! $queryCekSudahPrintLaporan->row()) {
            $this->session->set_flashdata('feedback_failed', 'Laporan Absensi Lembur belum pernah dibuat!. Silahkan Klik Tampilkan');

			redirect('lap_absensi_lembur','refresh');
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
    
            $tanggal	=	$this->input->get('tahun')."-".$this->input->get('bulan')."-01";
    
    
            $tglSelesai 	= date('Y-m-t', strtotime($tanggal));		

			
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

			/** CEK APAKAH ADA PROSES GENERATE DI USER LAINNYA */
			$data_uri = [
				'bulan' => $bulan_get,
				'tahun' => $tahun_get,
				'id_instansi' => $id_instansi_get,
				'pns' => $pns_get,
			];

			$queryGeneratingLaporan	= $this->db->query("
				select m.*, u.fullname from lap_absensi_lembur m
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
					select * from lap_absensi_lembur_detil
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

				redirect('lap_absensi_lembur','refresh');
			}
			#END

			
			/** UPDATE IS_DELETE JADI NULL */
			$this->load->model([
				'Lap_absensi_lembur_model',
				'Lap_absensi_lembur_detil_model'
			]);

			$where = [
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'			=> $pns_get,
				'deleted_at'	=> null,
			];

			$this->Lap_absensi_lembur_model->update($where, ['deleted_at' => date('Y-m-d H:i:s')]);

			$this->Lap_absensi_lembur_detil_model->update($where, ['deleted_at' => date('Y-m-d H:i:s')]);
			#end


            /** INSERT KE LAP_ABSENSI_LEMBUR */
            $data_absensi_lembur = [
				'bulan'			=> $bulan_get,
				'tahun'			=> $tahun_get,
				'id_instansi'	=> $id_instansi_get,
				'pns'			=> $pns_get,
				'id_pegawai'	=> $this->session->userdata('id_karyawan'),
			];

            $this->Lap_absensi_lembur_model->insert($data_absensi_lembur);
			#end
			
            $dataLembur = '';
            $i=1;
            foreach($this->dataPegawai as $dataPegawai){

                $hari_ini 		= date($this->input->get("tahun")."-".$this->input->get("bulan")."-01");
                // Tanggal pertama pada bulan ini
                $this->tgl_pertama 	= date('Y-m-01', strtotime($hari_ini));
                // Tanggal terakhir pada bulan ini
                $this->tgl_terakhir 	= date('Y-m-t', strtotime($hari_ini));

                $dataLembur .= "<tr><td align='center'>".$i."</td>";
                $dataLembur .= "<td>".$dataPegawai->nama."</td>";
                $dataLembur .= "<td>".$dataPegawai->nip."</td>";

                $totalLemburJumlah 			= 0;
                $totalLemburJumlahDiakui 	= 0;

                // untuk insert ke lap_absensi_lembur_detil
                $skor = [];
                
                while (strtotime($this->tgl_pertama) <= strtotime($this->tgl_terakhir )) {


                    $queryJumlahLembur	=	$this->db->query("select lembur,lembur_diakui from data_mentah where id_pegawai='".$dataPegawai->id_pegawai."' and tanggal='".$this->tgl_pertama."'");
                    $dataHasilLembur	=	$queryJumlahLembur->row();

                    if($dataHasilLembur){
                        $lemburJumlah = $dataHasilLembur->lembur;
                        $lemburJumlahDiakui = $dataHasilLembur->lembur_diakui;
                    }
                    else{
                        $lemburJumlah = "0";
                        $lemburJumlahDiakui = "0";
                    }
                    $lembur = $this->konversi_menit->hitung($lemburJumlah);

                    if($lemburJumlah == 0){
                        $color="red";
                    }
                    elseif($lemburJumlah != $lemburJumlahDiakui ){
                        $color="red";
                    }
                    else{
                        $color="";
                    }

                    //$dataLembur .= "<td align='center' >".$lemburJumlahDiakui." -- ".$lemburJumlah." -- <span style='color:".$color."'>".sprintf("%02d", $lembur['jam_angka'])." : ".sprintf("%02d",$lembur['menit_angka'])."</span></td>";
                    $dataLembur .= "<td align='center' ><span style='color:".$color."'>".sprintf("%02d", $lembur['jam_angka'])." : ".sprintf("%02d",$lembur['menit_angka'])."</span></td>";
                    
                    // untuk insert ke lap_absensi_lembur_detil
                    $skor[] = [
                        'color' => $color,
                        'value' => sprintf("%02d", $lembur['jam_angka'])." : ".sprintf("%02d",$lembur['menit_angka'])
                    ];


                    $totalLemburJumlah += $lemburJumlah;
                    $totalLemburJumlahDiakui += $lemburJumlahDiakui;

                    $this->tgl_pertama = date ("Y-m-d", strtotime("+1 days", strtotime($this->tgl_pertama)));
                }


                $jumlahPersen 	= round(($totalLemburJumlah / 1800) * 100);
                if($jumlahPersen > 99){
                    $jumlahPersen = 100;
                }
                else{
                    $jumlahPersen = "<span style='color:orange;'>".$jumlahPersen."</span>";
                }

                $jumlahLembur			=	$this->konversi_menit->hitung($totalLemburJumlahDiakui);

                $bulan 		=	date('Y-m', strtotime($hari_ini));
                if($bulan =='2018-05' || $bulan =='2018-06'){
                    $where	=	"and jenis = 'RAMADHAN'";
                }
                else{
                    $where	=	"and jenis = 'BIASA'";
                }

                $queryPersen 	=	$this->db->query("select skor from m_skor_lembur where menit_mulai <='".$totalLemburJumlahDiakui."' and menit_akhir >= '".$totalLemburJumlahDiakui."' $where");
                $dataPersen		=	$queryPersen->row();


                $dataLembur .= "<td align='center' ><b>".sprintf("%02d", $jumlahLembur['jam_angka'])." : ".sprintf("%02d", $jumlahLembur['menit_angka'])."</b></td>";
                $dataLembur .= "<td align='center'>".$dataPersen->skor."</td>";
                $dataLembur .= "</tr>";
                
            
                /** INSERT LAP REKAP INSTANSI DETIL */	
                $data = [
                    'nip'			=> $dataPegawai->nip,
                    'nama'			=> $dataPegawai->nama,
                    'skor'			=> json_encode($skor),
                    'bulan'			=> $bulan_get,
                    'tahun'			=> $tahun_get,
                    'id_instansi'	=> $id_instansi_get,
                    'pns'			=> $pns_get,
                    'total'		    => sprintf("%02d", $jumlahLembur['jam_angka'])." : ".sprintf("%02d", $jumlahLembur['menit_angka']),
                    'skor_persen'	=> $dataPersen->skor,
                ];
        
                $this->Lap_absensi_lembur_detil_model->insert($data);
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

			$this->Lap_absensi_lembur_model->update($where, ['finished_at' => date('Y-m-d H:i:s')]);
			#end


            $this->session->set_flashdata('feedback_success', 'Laporan Absensi Lembur berhasil terupdate!. Silahkan Klik Tampilkan');

            redirect('lap_absensi_lembur','refresh');
        }
		#end
	}

	public function stop() {
		$this->load->model(['Lap_absensi_lembur_model']);

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

		$this->Lap_absensi_lembur_model->update($where, ['finished_at' => date('Y-m-d H:i:s')]);

		$this->session->set_flashdata('feedback_success', 'Update Laporan Telah Dihentikan');

		redirect('lap_absensi_lembur','refresh');
	}
}
