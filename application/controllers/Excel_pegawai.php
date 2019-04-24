<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Excel_pegawai extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->_ci = &get_instance();
		$this->_ci->load->database();
		$this->_ci->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model','pegawai_instansi_histori_model', 'pegawai_role_jam_kerja_histori_model', 'pegawai_rumpun_jabatan_histori_model', 'pegawai_unit_kerja_histori_model','pegawai_jabatan_histori_model','pegawai_golongan_histori_model','pegawai_eselon_histori_model']);
	}

	public function index(){
		$file = './files/param_new/curi/pegawai/pegawai_kebonsari.xlsx';
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
			if ($row == 1 || $row == 2) {
				$header[$row][$column] = $data_value;
			} else {
				$arr_trim = array("B","E","G","H","J","L","P","S");
				if(in_array($column,$arr_trim)) {
					$arr_data[$row][$column] = str_replace(' ', '', $data_value);
				}
				else {
					$arr_data[$row][$column] = $data_value;
				}
			}
			// if ($row == 12) {
			// 	break;
			// }
		}

		//send the data in an array format
		$data['header'] = $header;
		$data['values'] = $arr_data;

		// var_dump($arr_data);
		// die;


		$array_cell = array ("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V");
		$no = 3;
		$eksekusi = true;
		foreach ($arr_data as $key => $data) {
			if($eksekusi) {
				for($y=0;$y<count($array_cell);$y++) {
					if(!isset($arr_data[$key][$array_cell[$y]])) {
						$arr_data[$key][$array_cell[$y]] = "";
					}
				}
				$pegawai = $this->_ci->db->query("
					select
						*
					from
						m_pegawai
					where
						nip = '".$data["B"]."'
				")->result_array();

				if ($pegawai == null) {
					$user = $this->session->userdata();
					$roster = 'NULL';
					$aktif = 'TRUE';

					$time = strtotime($data["E"]);
					$tgl_lahir = date('Y-m-d',$time);

					$kode_status_pegawai = "";
					if($data["O"] == "PNS") {
						$kode_status_pegawai = "1";
					}
					else if($data["O"] == "CPNS") {
						$kode_status_pegawai = "2";
					}
					else if($data["O"] == "PNS DPK") {
						$kode_status_pegawai = "3";
					}
					else if($data["O"] == "PNS/F") {
						$kode_status_pegawai = "4";
					}
					else if($data["O"] == "TENAGA KONTRAK") {
						$kode_status_pegawai = "5";
					}
					else if($data["O"] == "TENAGA TITIPAN") {
						$kode_status_pegawai = "6";
					}

					$meninggal = "FALSE";
					$tgl_meninggal = 'NULL';
					if($data["U"] == "Ya") {
						$meninggal = 'TRUE';
						$tgl_meninggal = "'".date('Y-m-d',strtotime($data["V"]))."'";
					}

					$data = array(
						'nip' => $data["B"],
						'nama' => $data["C"],
						'tempat_lahir' => $data["D"],
						'kode_jenis_kelamin' => $data["N"],
						'kode_golongan_akhir' => "",
						'kode_jenis_jabatan' => "",
						'kode_status_pegawai' => $kode_status_pegawai,
						'no_registrasi' => "",
						'gelar_depan' => "",
						'gelar_belakang' => "",
						'tgl_lahir' => $tgl_lahir,
						'no_hp' => "",
						'kode_eselon' => "",
						'roster' => $roster,
						'aktif' => $aktif,
						'meninggal' => $meninggal,
						'tgl_meninggal' => $tgl_meninggal,
						'userupd' => $user['username']
					);

					$q = "INSERT INTO m_pegawai (id, nip, nama, tempat_lahir, kode_jenis_kelamin, kode_golongan_akhir, kode_jenis_jabatan, kode_status_pegawai, no_registrasi, gelar_depan, gelar_belakang, tgl_lahir, no_hp, kode_eselon, roster, aktif, meninggal, tgl_meninggal, userupd) VALUES (uuid_generate_v1(), '".$data['nip']."', '".$data['nama']."', '".$data['tempat_lahir']."', '".$data['kode_jenis_kelamin']."', '".$data['kode_golongan_akhir']."', '".$data['kode_jenis_jabatan']."', '".$data['kode_status_pegawai']."', '".$data['no_registrasi']."', '".$data['gelar_depan']."', '".$data['gelar_belakang']."', '".$data['tgl_lahir']."', '".$data['no_hp']."', '".$data['kode_eselon']."', ".$data['roster'].", ".$data['aktif'].", ".$data['meninggal'].", ".$data['tgl_meninggal'].", '".$data['userupd']."') RETURNING id";

					$res        = $this->pegawai_model->query($q);
					$id_pegawai = $res['id'];

					$tgl_mulai_golongan = array();
					$golongan = array();
					$tgl_mulai_jabatan = array();
					$jabatan = array();
					$tgl_mulai_rumpun_jabatan = array();
					$rumpun_jabatan = array();
					$tgl_mulai_eselon = array();
					$eselon = array();
					$tgl_mulai_instansi = array();
					$instansi = array();
					$unor = array();
					$tgl_mulai_jadwal = array();
					$jadwal = array();

					// $tgl_mulai_golongan[] = $data["F"];
					// $golongan[] = $data["G"];
					// $tgl_mulai_jabatan[] = $data["H"];
					// $jabatan[] = $data["I"];
					// $tgl_mulai_rumpun_jabatan[] = $data["J"];
					// $rumpun_jabatan[] = $data["K"];
					// $tgl_mulai_eselon[] = $data["L"];
					// $eselon[] = $data["M"];
					// $tgl_mulai_instansi[] = $data["P"];
					// $unor[] = $data["Q"];
					// $instansi[] = $data["R"];
					// $tgl_mulai_jadwal[] = $data["S"];
					// $jadwal[] = $data["T"];

					$index = $key;

					$putar = true;

					while($putar) {
						if($index <> $key) {
							for($y=0;$y<count($array_cell);$y++) {
								if(!isset($arr_data[$index][$array_cell[$y]])) {
									$arr_data[$index][$array_cell[$y]] = "";
								}
							}
						}
						if(trim($arr_data[$index]["B"]) == "" || $index == $key) {
							if($this->validateDate(trim($arr_data[$index]["F"]))) {
								$q_gol = $this->_ci->db->query("
									select
										kode
									from
										m_golongan
									where
										LOWER(nama) = '".strtolower($arr_data[$index]["G"])."'
								")->row_array();
								if ($q_gol <> null) {
									$tgl_mulai_golongan[] = $arr_data[$index]["F"];
									$golongan[] = $q_gol["kode"];
								}
							}
							if($this->validateDate(trim($arr_data[$index]["H"]))) {
								$q_jab = $this->_ci->db->query("
									select
										kode
									from
										m_jenis_jabatan
									where
										LOWER(nama) = '".strtolower($arr_data[$index]["I"])."'
								")->row_array();
								if ($q_jab <> null) {
									$tgl_mulai_jabatan[] = $arr_data[$index]["H"];
									$jabatan[] = $q_jab["kode"];
								}
							}
							if($this->validateDate(trim($arr_data[$index]["J"]))) {
								$q_rum_jab = $this->_ci->db->query("
									select
										id
									from
										m_rumpun_jabatan
									where
										LOWER(nama) = '".strtolower($arr_data[$index]["K"])."'
								")->row_array();
								if ($q_rum_jab <> null) {
									$tgl_mulai_rumpun_jabatan[] = $arr_data[$index]["J"];
									$rumpun_jabatan[] = $q_rum_jab["id"];
								}
							}
							if($this->validateDate(trim($arr_data[$index]["L"]))) {
								$q_eselon = $this->_ci->db->query("
									select
										kode_eselon
									from
										m_eselon
									where
										LOWER(nama_eselon) = '".strtolower($arr_data[$index]["M"])."'
								")->row_array();
								if ($q_eselon <> null) {
									$tgl_mulai_eselon[] = $arr_data[$index]["L"];
									$eselon[] = $q_gol["kode_eselon"];
								}
							}
							if($this->validateDate(trim($arr_data[$index]["P"]))) {
								$q_unor = $this->_ci->db->query("
									select
										kode
									from
										m_instansi
									where
										LOWER(nama) = '".strtolower($arr_data[$index]["Q"])."'
								")->row_array();

								$q_instansi = $this->_ci->db->query("
									select
										kode
									from
										m_instansi
									where
										LOWER(nama) = '".strtolower($arr_data[$index]["R"])."'
								")->row_array();
								if ($q_unor <> null && $q_instansi <> null) {
									$tgl_mulai_instansi[] = $arr_data[$index]["P"];
									$unor[] = $q_unor["kode"];
									$instansi[] = $q_instansi["kode"];
								}
							}
							if($this->validateDate(trim($arr_data[$index]["S"]))) {
								$q_jadwal = $this->_ci->db->query("
									select
										id
									from
										m_role_jam_kerja
									where
										LOWER(nama) = '".strtolower($arr_data[$index]["T"])."'
								")->row_array();
								if ($q_jadwal <> null) {
									$tgl_mulai_jadwal[] = $arr_data[$index]["S"];
									$jadwal[] = $q_jadwal["kode"];
								}
							}
						}
						else {
							$putar = false;
						}
						$index++;
					}

					for($a=0;$a<count($jabatan);$a++) {
						$data_jabatan = array(
							'tgl_mulai' 				=> $tgl_mulai_jabatan[$a],
							'user_upd' 					=> $user['username'],
							'tgl_upd' 					=> date('Y-m-d H:i:s'),
							'id_pegawai' 				=> $id_pegawai,
							'kode_jabatan' 			=> $jabatan[$a]
						);
						$query_data_jabatan = $this->pegawai_jabatan_histori_model->insert($data_jabatan);
					}

					for($a=0;$a<count($golongan);$a++) {
						$data_golongan = array(
							'tgl_mulai' 				=> $tgl_mulai_golongan[$a],
							'user_upd' 					=> $user['username'],
							'tgl_upd' 					=> date('Y-m-d H:i:s'),
							'id_pegawai' 				=> $id_pegawai,
							'kode_golongan' 		=> $golongan[$a]
						);
						$query_data_golongan = $this->pegawai_golongan_histori_model->insert($data_golongan);
					}

					for($a=0;$a<count($eselon);$a++) {
						$data_eselon = array(
							'tgl_mulai' 				=> $tgl_mulai_eselon[$a],
							'user_upd' 					=> $user['username'],
							'tgl_upd' 					=> date('Y-m-d H:i:s'),
							'id_pegawai' 				=> $id_pegawai,
							'kode_eselon' 			=> $eselon[$a]
						);
						$query_data_eselon = $this->pegawai_eselon_histori_model->insert($data_eselon);
					}

					for($a=0;$a<count($rumpun_jabatan);$a++) {
						$data_rumpun_jabatan = array(
							'tgl_mulai' 				=> $tgl_mulai_rumpun_jabatan[$a],
							'user_upd' 					=> $user['username'],
							'tgl_upd' 					=> date('Y-m-d H:i:s'),
							'id_pegawai' 				=> $id_pegawai,
							'id_rumpun_jabatan' => $rumpun_jabatan[$a]
						);
						$query_rumpun_jabatan = $this->pegawai_rumpun_jabatan_histori_model->insert($data_rumpun_jabatan);
					}

					for($a=0;$a<count($unor);$a++) {
						$data_unor = array(
							'tgl_mulai' 			=> $tgl_mulai_instansi[$a],
							'user_upd' 				=> $user['username'],
							'tgl_upd' 				=> date('Y-m-d H:i:s'),
							'id_pegawai' 			=> $id_pegawai,
							'kode_unor' 			=> $unor[$a]
						);
						$query_data_unor = $this->pegawai_unit_kerja_histori_model->insert($data_unor);
					}

					for($a=0;$a<count($jadwal);$a++) {
						$data_jadwal = array(
							'tgl_mulai' 				=> $tgl_mulai_jadwal[$a],
							'user_upd' 					=> $user['username'],
							'tgl_upd' 					=> date('Y-m-d H:i:s'),
							'id_pegawai' 				=> $id_pegawai,
							'id_role_jam_kerja' => $jadwal[$a]
						);
						$query_data_jadwal = $this->pegawai_role_jam_kerja_histori_model->insert($data_jadwal);
					}
					echo "data baris $no berhasil insert di master pegawai <br/>";
				}
				else {
					echo "data baris $no sudah ada di master pegawai <br/>";
				}
			}
			else {
				echo "data baris $no merupakan data pelengkap pegawai <br/>";
			}
			$no++;
		}
	}

	function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
	}
}
?>
