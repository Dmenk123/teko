<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Excel_import_shofi extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->_ci = &get_instance();
		$this->_ci->load->database();
		$this->_ci->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model']);
	}

	public function excel($param){
		error_reporting(-1);
		ini_set('display_errors', 1);
		#$file = './files/fatich_new/soewandhie_februari_new.xlsx';
		$file = './files/param_new/shofi/Siwalankerto/'.$param.'';

		//load the excel library
		$this->load->library('excel');

		//read file from path
		$objPHPExcel = PHPExcel_IOFactory::load($file);

		//get only the Cell Collection
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

		//extract to a PHP readable array format
		foreach ($cell_collection as $cell) {
			$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
			$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
			$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getFormattedValue();

			//The header will/should be in row 1 only. of course, this can be modified to suit your need.
			if ($row == 1) {
				$header[$row][$column] = $data_value;
			} else {
				if($column <> "D") {
					$arr_data[$row][$column] = str_replace(' ', '', $data_value);
				}
				else {
					$arr_data[$row][$column] = $data_value;
				}
			}
			// if ($row == 12) {
				// break;
			// }
		}

		// var_dump($arr_data[15]);
		// die;

		//send the data in an array format
		$data['header'] = $header;
		$data['values'] = $arr_data;

		$array_cell = array ("A","B","C","D","E","F","G","H","I","J","K","L");
		$no = 2;
		foreach ($arr_data as $data) {
			for($y=0;$y<count($array_cell);$y++) {
				if(!isset($data[$array_cell[$y]])) {
					$data[$array_cell[$y]] = "";
				}
			}
			$pegawai = $this->_ci->db->query("
				select
					*
				from
					m_pegawai
				where
					nip = '".$data["C"]."'
			")->result_array();

			if ($pegawai == null) {
				echo "data $no tidak ada di master pegawai ".$data["C"]."<br/>";
			}
			else if(count($pegawai) > 1) {
				echo "NIP data $no terdaftar lebih dari 1 di master pegawai <br/>";
			}
			else {
				$date       = $data["B"];
				$id_pegawai = $pegawai[0]['id'];
				$nama_hari  = $this->generate_hari($this->dayOfWeek($date));
				if($data["L"] <> 'H' && $data["L"] <> '') {
					if($data["L"] == 'M') {
						$jamKerja     	      = $data["E"] . " " . $data["F"];
						if($data["J"] == 'Ya') {
							$jadwalMasuk = date("Y-m-d", strtotime("-1 days", strtotime($date)))." ".$data["E"];
						}
						else {
							$jadwalMasuk = $date." ".$data["E"];
						}
						if($data["K"] == 'Ya') {
							$jadwalPulang = date("Y-m-d", strtotime("+1 days", strtotime($date)))." ".$data["F"];
						}
						else {
							$jadwalPulang = $date." ".$data["F"];
						}
						$fingerMasuk          = null;
						$fingerPulang         = null;
						$menitPulangCepat     = 0;
						$menitTelat           = 0;
						$menitLembur          = 0;
						$menitLemburDiakui    = 0;
						$kodeMasuk            = "M";
						$keteranganMasuk      = null;
						$kodeTidakMasuk       = "M";
						$keteranganTidakMasuk = "Mangkir";
						$keterangan           = "Mangkir di hari $nama_hari";
					}
					else {
						$jamKerja             = $data["L"];
						$jadwalMasuk          = null;
						$jadwalPulang         = null;
						$fingerMasuk          = null;
						$fingerPulang         = null;
						$menitPulangCepat     = 0;
						$menitTelat           = 0;
						$menitLembur          = 0;
						$menitLemburDiakui    = 0;
						$kodeMasuk            = "*";
						$keteranganMasuk      = null;
						$kodeTidakMasuk       = $data["L"];

						$ijin = $this->_ci->db->query("
							select
								*
							from
								m_jenis_ijin_cuti
							where
								kode = '".$data["L"]."'
						")->row_array();
						$ket = "?";
						if($ijin <> null) {
							$ket = $ijin['nama'];
						}

						$keteranganTidakMasuk = "$ket";
						$keterangan           = "Ijin ".$data["L"]." di hari $nama_hari";

						if($data["L"] == "DK" || $data["L"] == "DL") {
							$jamKerja = $data["E"] . " " . $data["F"];
							if (trim($jamKerja) == "") {
								$menitPulangCepat       = "0";
		            $fingerMasuk            = "";
		            $fingerPulang           = "";
		            $menitTelat             = "0";

		            if(strtotime($date) < strtotime('2018-07-01')){
		              $menitLembur          = "180";
		              $menitLemburDiakui    = "180";
		              $variable_menit       = "3";
		            }
		            else{
		              $menitLembur          = "360";
		              $menitLemburDiakui    = "360";
		              $variable_menit       = "6";
		            }

		            $jadwalMasuk             = "";
		            $jadwalPulang            = "";

		            $kodeMasuk              = "*";
		            $keteranganMasuk        = "";
		            $kodeTidakMasuk         = $data["L"];
		            $keteranganTidakMasuk   = "";
		            $jamKerja               = $data["L"];
		            $keterangan             = $data["L"]." DI HARI LIBUR, DAPAT LEMBUR $variable_menit JAM (".$data["L"].")";
							}
							else {
								$menitPulangCepat       = "0";
			          $fingerMasuk            = "";
			          $fingerPulang           = "";
			          $menitTelat             = "0";
			          $menitLembur            = "180";
			          $menitLemburDiakui      = "180";
			          $jadwalMasuk            = "";
			          $jadwalPulang           = "";
			          $kodeMasuk              = "*";
			          $keteranganMasuk        = "";
			          $kodeTidakMasuk         = $data["L"];
			          $keteranganTidakMasuk   = "";
			          $jamKerja               = $data["L"];
			          $keterangan             = $data["L"]." DI HARI LIBUR, DAPAT LEMBUR 3 JAM (".$data["L"].")";
							}
						}
					}
				}
				else {
					$jamKerja = $data["E"] . " " . $data["F"];
					if (trim($jamKerja) == "") {
						$cek_hari_libur = $this->cek_hari_libur($date);
						if($cek_hari_libur) {
							$jamKerja     = $cek_hari_libur->nama;
							$jadwalMasuk  = null;
							$jadwalPulang = null;
							if($data["I"] == 'Ya') {
								$fingerMasuk  = $date." ".$data["G"];
								$fingerPulang = $date." ".$data["H"];
								if(trim($data["G"]) <> "" || trim($data["H"]) <> "") {
									if(trim($data["G"]) == "") {
										$fingerMasuk = $fingerPulang;
									}
									if(trim($data["H"]) == "") {
										$fingerPulang = $fingerMasuk;
									}
									$selisih_finger       = $this->ambil_selisih_menit($fingerMasuk, $fingerPulang);
									$menitPulangCepat     = 0;
									$menitTelat           = 0;
									$menitLembur          = $selisih_finger;
									if(strtotime($date) >= strtotime('2018-12-01')) {
										if($selisih_finger > 360){
											$menitLemburDiakui = 360;
										}
										else{
											$menitLemburDiakui = $selisih_finger;
										}
									} else{
										$menitLemburDiakui = $selisih_finger;
									}
									$kodeMasuk            = "*";
									$keteranganMasuk      = null;
									$kodeTidakMasuk       = "LB";
									$keteranganTidakMasuk = null;
									$keterangan           = "LIBUR ".$cek_hari_libur->nama." DENGAN SURAT SESUAI FINGER";
								}
								else {
									$fingerMasuk          = null;
									$fingerPulang         = null;
									$menitPulangCepat     = 0;
									$menitTelat           = 0;
									$menitLembur          = 0;
									$menitLemburDiakui    = 0;
									$kodeMasuk            = "*";
									$keteranganMasuk      = null;
									$kodeTidakMasuk       = "LB";
									$keteranganTidakMasuk = null;
									$keterangan           = "LIBUR ".$cek_hari_libur->nama." ";
								}
							}
							else {
								$fingerMasuk          = null;
								$fingerPulang         = null;
								$menitPulangCepat     = 0;
								$menitTelat           = 0;
								$menitLembur          = 0;
								$menitLemburDiakui    = 0;
								$kodeMasuk            = "*";
								$keteranganMasuk      = null;
								$kodeTidakMasuk       = "LB";
								$keteranganTidakMasuk = null;
								$keterangan           = "LIBUR ".$cek_hari_libur->nama." ";
							}
						}
						else {
							$jadwalMasuk  = null;
							$jadwalPulang = null;
							if($this->dayOfWeek($date) == 6 || $this->dayOfWeek($date) == 0) {
								if($data["I"] == 'Ya') {
									$fingerMasuk  = $date." ".$data["G"];
									$fingerPulang = $date." ".$data["H"];
									if(trim($data["G"]) <> "" || trim($data["H"]) <> "") {
										if(trim($data["G"]) == "") {
											$fingerMasuk = $fingerPulang;
										}
										if(trim($data["H"]) == "") {
											$fingerPulang = $fingerMasuk;
										}
										$selisih_finger       = $this->ambil_selisih_menit($fingerMasuk, $fingerPulang);
										$menitPulangCepat     = 0;
										$menitTelat           = 0;
										$menitLembur          = $selisih_finger;
										if(strtotime($date) >= strtotime('2018-12-01')) {
											if($selisih_finger > 360){
												$menitLemburDiakui = 360;
											}
											else{
												$menitLemburDiakui = $selisih_finger;
											}
										} else{
											$menitLemburDiakui = $selisih_finger;
										}
										$kodeMasuk            = "*";
										$keteranganMasuk      = null;
										$kodeTidakMasuk       = "LB";
										$keteranganTidakMasuk = null;
										$keterangan           = "LIBUR $nama_hari DENGAN SURAT SESUAI FINGER";
									}
									else {
										$fingerMasuk          = null;
										$fingerPulang         = null;
										$menitPulangCepat     = 0;
										$menitTelat           = 0;
										$menitLembur          = 0;
										$menitLemburDiakui    = 0;
										$kodeMasuk            = "*";
										$keteranganMasuk      = null;
										$kodeTidakMasuk       = "LB";
										$keteranganTidakMasuk = null;
										$keterangan           = "LIBUR $nama_hari ";
									}
								}
								else {
									$fingerMasuk  = $date." ".$data["G"];
									$fingerPulang = $date." ".$data["H"];
									if((trim($data["G"]) <> "" || trim($data["H"]) <> "") && $this->dayOfWeek($date) == 6) {
										if(trim($data["G"]) == "") {
											$fingerMasuk = $fingerPulang;
										}
										if(trim($data["H"]) == "") {
											$fingerPulang = $fingerMasuk;
										}
										$selisih_finger       = $this->ambil_selisih_menit($fingerMasuk, $fingerPulang);
										$menitPulangCepat     = 0;
										$menitTelat           = 0;
										$menitLembur          = $selisih_finger;
										if($selisih_finger > 360){
											$menitLemburDiakui = 360;
										}
										else{
											$menitLemburDiakui = $selisih_finger;
										}
										$kodeMasuk            = "*";
										$keteranganMasuk      = null;
										$kodeTidakMasuk       = "LB";
										$keteranganTidakMasuk = null;
										$keterangan           = "LIBUR $nama_hari TANPA SURAT SESUAI FINGER";
									}
									else {
										$fingerMasuk          = null;
										$fingerPulang         = null;
										$menitPulangCepat     = 0;
										$menitTelat           = 0;
										$menitLembur          = 0;
										$menitLemburDiakui    = 0;
										$kodeMasuk            = "*";
										$keteranganMasuk      = null;
										$kodeTidakMasuk       = "LB";
										$keteranganTidakMasuk = null;
										$keterangan           = "LIBUR $nama_hari ";
									}
								}
							}
							else {
								$fingerMasuk          = null;
								$fingerPulang         = null;
								$menitPulangCepat     = 0;
								$menitTelat           = 0;
								$menitLembur          = 0;
								$menitLemburDiakui    = 0;
								$kodeMasuk            = "*";
								$keteranganMasuk      = null;
								$kodeTidakMasuk       = "LB";
								$keteranganTidakMasuk = null;
								$keterangan           = "LIBUR ROSTER $nama_hari ";
							}
						}
					}
					else {
						if($data["J"] == 'Ya') {
							$jadwalMasuk = date("Y-m-d", strtotime("-1 days", strtotime($date)))." ".$data["E"];
						}
						else {
							$jadwalMasuk = $date." ".$data["E"];
						}
						if($data["K"] == 'Ya') {
							$jadwalPulang = date("Y-m-d", strtotime("+1 days", strtotime($date)))." ".$data["F"];
						}
						else {
							$jadwalPulang = $date." ".$data["F"];
						}
						if(trim($data["G"]) <> "") {
							if($data["J"] == 'Ya') {
								$fingerMasuk = $date." ".$data["G"];
								if($fingerMasuk > $jadwalPulang) {
									$fingerMasuk = date("Y-m-d", strtotime("-1 days", strtotime($date)))." ".$data["G"];
								}
							}
							else {
								$fingerMasuk = $date." ".$data["G"];
							}
						}
						else {
							$fingerMasuk = "";
						}
						if(trim($data["H"]) <> "") {
							if($data["K"] == 'Ya') {
								$fingerPulang = $date." ".$data["H"];
								if($fingerPulang < $jadwalPulang) {
									$fingerPulang = date("Y-m-d", strtotime("+1 days", strtotime($date)))." ".$data["H"];
								}
							}
							else {
								$fingerPulang = $date." ".$data["H"];
							}
						}
						else {
							$fingerPulang = "";
						}

						$telat_cepat = false;

						if(trim($data["G"]) <> "" || trim($data["H"]) <> "") {
							if(trim($data["G"]) == "") {
								$fingerMasuk = $fingerPulang;
								$telat_cepat = true;
							}
							if(trim($data["H"]) == "") {
								$fingerPulang = $fingerMasuk;
								$telat_cepat = true;
							}
							if($fingerMasuk == $fingerPulang) {
								$telat_cepat = true;
							}

							if(strtotime($fingerMasuk) > strtotime($jadwalMasuk)){
								$menit_telat = $this->jumlah_menit_telat_hari_kerja($fingerMasuk, $jadwalMasuk);
								if(!$telat_cepat) {
									if($menit_telat > 480) {
										$fingerMasuk = date("Y-m-d", strtotime("-1 days", strtotime($date)))." ".$data["G"];
										$menit_telat = $this->jumlah_menit_telat_hari_kerja($fingerMasuk, $jadwalMasuk);
									}
								}
							}
							else{
								$selisih_finger = 0;
								$menit_telat = 0;
							}
							$menitTelat           = $menit_telat;
							if(strtotime($fingerPulang) < strtotime($jadwalPulang)){
								$menit_cepat_pulang = $this->jumlah_menit_cepat_pulang_hari_kerja($fingerPulang, $jadwalPulang);
								if(!$telat_cepat) {
									if($menit_cepat_pulang > 480) {
										$fingerPulang = date("Y-m-d", strtotime("+1 days", strtotime($date)))." ".$data["H"];
										$menit_cepat_pulang = $this->jumlah_menit_cepat_pulang_hari_kerja($fingerPulang, $jadwalPulang);
									}
								}
							}
							else{
								$menit_cepat_pulang = 0;
							}

							$menitPulangCepat     = $menit_cepat_pulang;
							if(strtotime($fingerPulang) > strtotime($jadwalPulang)) {
								$selisih_finger       = $this->ambil_selisih_menit($jadwalPulang, $fingerPulang);
								$menitLembur          = $selisih_finger;
							}
							else {
								$menitLembur = 0;
							}
							if(strtotime($date) >= strtotime('2018-12-01')) {
								if($selisih_finger > 180){
									$menitLemburDiakui = 180;
								}
								else{
									$menitLemburDiakui = $selisih_finger;
								}
							} else{
								if($data["I"] == 'Ya') {
									$menitLemburDiakui = $selisih_finger;
								}
								else {
									if($selisih_finger > 180){
										$menitLemburDiakui = 180;
									}
									else{
										$menitLemburDiakui = $selisih_finger;
									}
								}
							}
							$kodeMasuk            = "H";
							$keteranganMasuk      = null;
							$kodeTidakMasuk       = null;
							$keteranganTidakMasuk = null;
							if($data["I"] == 'Ya') {
								$keterangan = "MASUK $nama_hari TANPA SURAT SESUAI FINGER";
							}
							else {
								$keterangan = "MASUK $nama_hari DENGAN SURAT SESUAI FINGER";
							}
						}
						else {
							$fingerMasuk          = null;
							$fingerPulang         = null;
							$menitPulangCepat     = 0;
							$menitTelat           = 0;
							$menitLembur          = 0;
							$menitLemburDiakui    = 0;
							$kodeMasuk            = "M";
							$keteranganMasuk      = null;
							$kodeTidakMasuk       = "M";
							$keteranganTidakMasuk = "Mangkir";
							$keterangan           = "Mangkir di hari $nama_hari";
						}
					}
				}

				if($jadwalMasuk==''){
					$jadwalMasuk = 'null';
				}
				else{
					$jadwalMasuk = "'".$jadwalMasuk."'";
				}

				if($jadwalPulang==''){
					$jadwalPulang = 'null';
				}
				else{
					$jadwalPulang = "'".$jadwalPulang."'";
				}

				if($fingerMasuk==''){
					$fingerMasuk = 'null';
				}
				else{
					$fingerMasuk = "'".$fingerMasuk."'";
				}

				if($fingerPulang==''){
					$fingerPulang = 'null';
				}
				else{
					$fingerPulang = "'".$fingerPulang."'";
				}

				$data_mentah = $this->_ci->db->query("
					select
						*
					from
						data_mentah
					where
						tanggal = '".$data["B"]."' and
						id_pegawai = '".$pegawai[0]['id']."'
				")->row_array();

				if ($data_mentah == null) {
					$insert = "
						insert into
							data_mentah
							(
								tanggal,
								id_pegawai,
								hari,
								jam_kerja,
								jadwal_masuk,
								jadwal_pulang,
								finger_masuk,
								finger_pulang,
								pulang_cepat,
								datang_telat,
								lembur,
								lembur_diakui,
								kode_masuk,
								keterangan_masuk,
								kode_tidak_masuk,
								keterangan_tidak_masuk,
								keterangan,
								excel
							)
							values
							(
								'".$date."',
								'".$id_pegawai."',
								'".$nama_hari."',
								'".$jamKerja."',

								$jadwalMasuk,
								$jadwalPulang,
								$fingerMasuk,
								$fingerPulang,

								'".floor($menitPulangCepat)."',
								'".floor($menitTelat)."',
								'".floor($menitLembur)."',
								'".floor($menitLemburDiakui)."',
								'".$kodeMasuk."',
								'".$keteranganMasuk."',
								'".$kodeTidakMasuk."',
								'".$keteranganTidakMasuk."',
								'".$keterangan."',
								TRUE)";

					$cek_insert = $this->_ci->db->query($insert);
				}
				else {
					$update = "
			            UPDATE data_mentah
			            SET
			            	hari = '".$nama_hari."',
				            jam_kerja = '".$jamKerja."',
				            jadwal_masuk = $jadwalMasuk,
				            jadwal_pulang = $jadwalPulang,
				            finger_masuk = $fingerMasuk,
				            finger_pulang = $fingerPulang,
				            pulang_cepat = '".floor($menitPulangCepat)."',
				            datang_telat = '".floor($menitTelat)."',
				            lembur = '".floor($menitLembur)."',
				            lembur_diakui = '".floor($menitLemburDiakui)."',
				            kode_masuk = '".$kodeMasuk."',
				            keterangan_masuk = '".$keteranganMasuk."',
				            kode_tidak_masuk = '".$kodeTidakMasuk."',
				            keterangan_tidak_masuk = '".$keteranganTidakMasuk."',
				            keterangan = '".$keterangan."',
				            excel = TRUE
			            WHERE tanggal   = '".$data["B"]."' and
			            	id_pegawai  = '".$pegawai[0]['id']."'";

               		$cek_insert = $this->_ci->db->query($update);
				}


				if($cek_insert){
					$data_mentah2 = $this->_ci->db->query("
						select
							*
						from
							data_mentah
						where
							tanggal = '".$data["B"]."' and
							id_pegawai = '".$pegawai[0]['id']."'
					")->row_array();

					if ($data_mentah2 == null) {
						$insert2 = "
							insert into
								data_mentah2
								(
									tanggal,
									id_pegawai,
									hari,
									jam_kerja,
									jadwal_masuk,
									jadwal_pulang,
									finger_masuk,
									finger_pulang,
									pulang_cepat,
									datang_telat,
									lembur,
									lembur_diakui,
									kode_masuk,
									keterangan_masuk,
									kode_tidak_masuk,
									keterangan_tidak_masuk,
									keterangan,
									excel
								)
								values
								(
									'".$date."',
									'".$id_pegawai."',
									'".$nama_hari."',
									'".$jamKerja."',

									$jadwalMasuk,
									$jadwalPulang,
									$fingerMasuk,
									$fingerPulang,

									'".floor($menitPulangCepat)."',
									'".floor($menitTelat)."',
									'".floor($menitLembur)."',
									'".floor($menitLemburDiakui)."',
									'".$kodeMasuk."',
									'".$keteranganMasuk."',
									'".$kodeTidakMasuk."',
									'".$keteranganTidakMasuk."',
									'".$keterangan."',
									TRUE)";

						$cek_insert2 = $this->_ci->db->query($insert2);
					}
					else {
						$update2 = "
				            UPDATE data_mentah2
				            SET
				            	hari = '".$nama_hari."',
					            jam_kerja = '".$jamKerja."',
					            jadwal_masuk = $jadwalMasuk,
					            jadwal_pulang = $jadwalPulang,
					            finger_masuk = $fingerMasuk,
					            finger_pulang = $fingerPulang,
					            pulang_cepat = '".floor($menitPulangCepat)."',
					            datang_telat = '".floor($menitTelat)."',
					            lembur = '".floor($menitLembur)."',
					            lembur_diakui = '".floor($menitLemburDiakui)."',
					            kode_masuk = '".$kodeMasuk."',
					            keterangan_masuk = '".$keteranganMasuk."',
					            kode_tidak_masuk = '".$kodeTidakMasuk."',
					            keterangan_tidak_masuk = '".$keteranganTidakMasuk."',
					            keterangan = '".$keterangan."',
					            excel = TRUE
				            WHERE tanggal   = '".$data["B"]."' and
				            	id_pegawai  = '".$pegawai[0]['id']."'";

	               		$cek_insert2 = $this->_ci->db->query($update2);
					}
					if($cek_insert2){
						echo "data $no berhasil insert data mentah dan data mentah 2 $jamKerja $fingerMasuk $kodeMasuk $id_pegawai<br/>";
					}
					else {
						echo "data $no gagal insert data mentah 2 <br/>";
					}
				}
				else {
					echo "data $no gagal insert data mentah <br/>";
				}
			}
			$no++;
			// if($no == 11) {
				// break;
			// }
		}
	}

	function dayOfWeek($date){
		return date("w", strtotime($date));
	}

	function generate_hari($day){
		if($day == 1){
		  return "SENIN";
		}
		else if($day == 2){
		  return "SELASA";
		}
		else if($day == 3){
			return "RABU";
		}
		else if($day == 4){
			return "KAMIS";
		}
		else if($day == 5){
			return "JUMAT";
		}
		else if($day == 6){
			return "SABTU";
		}
		else{
			return "MINGGU";
		}
	}

	function cek_hari_libur($date){
		$cek_hari_libur =   $this->_ci->db->query("select
			s_hari_libur.id,
			m_hari_libur.id as id_hari_libur,
			m_hari_libur.nama
		  from
			s_hari_libur ,m_hari_libur
		  where
			s_hari_libur.tanggal = '".$date."'  and
			s_hari_libur.id_libur = m_hari_libur.id");

		return $cek_hari_libur->row();
	}

	function ambil_selisih_menit($date_mulai, $date_akhir){
        $masuk         = strtotime($date_mulai);
        $pulang        = strtotime($date_akhir);
        $menitLembur   = round(abs($pulang - $masuk) / 60,2);
        return $menitLembur;
	}

	function jumlah_menit_telat_hari_kerja($date_finger_datang, $date_jadwal_datang){
		if(strtotime($date_finger_datang) > strtotime($date_jadwal_datang)){
			return $this->ambil_selisih_menit($date_finger_datang, $date_jadwal_datang, 0);
		}
		return 0;
	}

	function jumlah_menit_cepat_pulang_hari_kerja($date_finger_pulang, $date_jadwal_pulang){
		if(strtotime($date_finger_pulang) < strtotime($date_jadwal_pulang)){
			return $this->ambil_selisih_menit($date_finger_pulang, $date_jadwal_pulang, 0);
		}
		return 0;
	}
}
?>
