<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class lap_uang_makan extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model','log_laporan_model']);
	}

	public function generate() {
    	$this->load->library('konversi_menit');
    	/** CEK APAKAH ADA LAPORAN SUDAH DIKUNCI */
		$whereTahunBulan = $this->input->get('tahun') . '-' . $this->input->get('bulan');
		$id_instansi_get = $this->input->get('id_instansi');
		$tanggal_mulai_kunci = $this->input->get('tahun') . '-' . $this->input->get('bulan') . '-' . '01';
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
		$bulan_get = $this->input->get('bulan') ? $this->input->get('bulan') : 0;
		$tahun_get = $this->input->get('tahun') ? $this->input->get('tahun') : '';
		$id_instansi_get = $this->input->get('id_instansi') ? $this->input->get('id_instansi') : '';
		$pns_get = $this->input->get('pns_get') ? $this->input->get('pns_get') : '';
		$queryCekSudahPrintLaporan	=	$this->db->query("
            select * from lap_uang_makan
            where bulan = '$bulan_get'
            and tahun = '$tahun_get'
			and id_instansi = '$id_instansi_get'
			and pns = '$pns_get'
            and deleted_at is null
		");

		$this->load->model(['Lap_uang_makan_model', 'Lap_uang_makan_detil_model']);

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

      	$data_pegawai	=	$queryPegawai->result();

		/** CEK APAKAH ADA PROSES GENERATE DI USER LAINNYA */
        $cek = $this->cek_proses_gen_user_lain($bulan_get, $tahun_get, $id_instansi_get, $pns_get, $data_pegawai);
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
					'pesan' => 'Laporan Uang Makan belum pernah dibuat. Silahkan Klik Tampilkan'
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

	            $ret = array(
					'status'      		=> 'sukses',
					'pesan'       		=> $data_pegawai,
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
        #end cek apakah ada proses generate
        echo json_encode($ret);
	}

	public function proses_generate_perpegawai()
	{
		$this->load->model(['Lap_uang_makan_model', 'Lap_uang_makan_detil_model']);

		$this->load->library('konversi_menit');
		$id_pegawai = $this->input->post('id_pegawai');
		$tgl_mulai   = $this->input->post('tgl_mulai_peg');
		$tgl_selesai = $this->input->post('tgl_akhir_peg');
		$instansiRaw 	  = $this->input->post("id_instansi_peg");
		$pns = $this->input->post('pns');
		$nip 	  = $this->input->post("nip");

		$tmulai = explode('/', $tgl_mulai);
		$thingga = explode('/', $tgl_selesai);
		$akhir =	$thingga[2]."-".$thingga[1]."-".$thingga[0];
		$mulai =	$tmulai[2]."-".$tmulai[1]."-".$tmulai[0];

		/** INSERT LAPORAN BARU */

        if ($id_pegawai != '')
        {
			$begin = new DateTime($mulai);
			$end   = new DateTime($akhir);

			$skor = [];
			$hitungMasukTotal 	= 0;
			$q_makan = $this->db->query("
				SELECT tgl::date as tanggal, extract(dow from tgl) as hari_tgl, dm.jadwal_masuk::text, dm.jadwal_pulang::text, finger_masuk::text, dm.kode_masuk, dm.kode_tidak_masuk, dm.jam_kerja, dm.keterangan
				FROM generate_series('".$mulai."', '".$akhir."', '1 day'::interval) tgl
				LEFT JOIN data_mentah as dm ON tgl = dm.tanggal AND id_pegawai = '".$id_pegawai."'
				order by tgl
			");

			$makan = $q_makan->result_array();
			$skor[0] = $nip;
			
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
							if($makan[$i]["jam_kerja"] <> '') {
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
						if($makan[$i]["kode_tidak_masuk"] <> 'LB') {
							$hitungMasuk = 0;
							$text 		 = $makan[$i]["kode_tidak_masuk"];
						}
						else {
							$arr_lb = array("LIBUR ROSTER DENGAN SURAT SESUAI FINGER","LIBUR ROSTER SESUAI SURAT LEMBUR","LIBUR ROSTER");
							if(in_array($makan[$i]["keterangan"], $arr_lb)) {
								$hitungMasuk = 0;
								$text 		 = $makan[$i]["kode_tidak_masuk"];
							}
							else {
								$hitungMasuk = 0;
								$text 		 = '0';
							}
						}
					}
				}
				$skor[] = $text;
				$hitungMasukTotal += $hitungMasuk;
			}

			if ($this->input->post('urut2') == null) {
				$urutan = 99;
			}else{
				$urutan = $this->input->post('urut2');
			}

            /** INSERT LAP REKAP UANG MAKAN DETIL */
			$data = [
				'nama'			=> $this->input->post('nama'),
				'skor'			=> json_encode($skor),
				'bulan'			=> $thingga[1],
				'tahun'			=> $thingga[2],
				'id_instansi'	=> $instansiRaw,
				'pns'			=> $pns,
				'jml_hari'		=> $hitungMasukTotal,
				'urut'			=> $urutan
			];

            $this->Lap_uang_makan_detil_model->insert($data);
			#end
			echo json_encode(['status' => 'sukses', 'pesan' => 'Sukses Generate per pegawai']);
		}
		else
		{
			echo json_encode(['status' => 'gagal', 'pesan' => 'Gagal Generate per pegawai']);
		}
	}

	public function update_selesai_gen_laporan(){
		$this->load->model(['Lap_uang_makan_model', 'Lap_uang_makan_detil_model']);
		/** UPDATE FINISHED_AT JADI NOT NULL */
		$where = [
			'bulan'			=> $this->input->post('bulan_update'),
			'tahun'			=> $this->input->post('tahun_update'),
			'id_instansi'	=> $this->input->post('id_instansi_update'),
			'pns'			=> $this->input->post('pns_update'),
			'deleted_at'	=> null,
		];

		$this->Lap_uang_makan_model->update($where, ['finished_at' => date('Y-m-d H:i:s')]);
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
			select m.*, u.fullname from lap_uang_makan m
			join c_security_user_new u on m.id_pegawai = u.id
			where bulan = '$bulan_get'
			and tahun = '$tahun_get'
			and id_instansi = '$id_instansi_get'
			and pns = '$pns_get'
			and deleted_at is null
			and finished_at is null
		")->row_array();

		if($queryGeneratingLaporan)
		{
			$laporanTergenerate	= $this->db->query("
				select * from lap_uang_makan_detil
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

	function cek_jam_diperbolehkan_finger($date, $id_pegawai){
      return $this->db->query("SELECT
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
