<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Excel_pegawai_update_nip extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->_ci = &get_instance();
		$this->_ci->load->database();
		$this->_ci->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model','pegawai_instansi_histori_model', 'pegawai_role_jam_kerja_histori_model', 'pegawai_rumpun_jabatan_histori_model', 'pegawai_unit_kerja_histori_model','pegawai_jabatan_histori_model','pegawai_golongan_histori_model','pegawai_eselon_histori_model']);
	}

	public function index(){
		$file = './files/fatich_new/soewandhie_pegawai_new.xlsx';

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
				$arr_data[$row][$column] = str_replace(' ', '', $data_value);
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
						and userupd  = 'adi'
				")->result_array();

				if ($pegawai == null) {
					echo "data baris $no belum ada di master pegawai <br/>";
				}
				else if(count($pegawai) > 1) {
					echo "data $no terdaftar lebih dari 1 di master pegawai <br/>";
				}
				else {
					$update = "
			            UPDATE m_pegawai
			            SET
			            	nip = '".$pegawai[0]["nip"]."00'
			            WHERE id   = '".$pegawai[0]["id"]."'";

       		$cek_insert = $this->_ci->db->query($update);
          if($cek_insert) {
            echo "data $no berhasil update pada m_pegawai <br/>";
          }
          else {
            echo "data $no gagal update pada m_pegawai <br/>";
          }
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
