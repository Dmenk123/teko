<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Excel_roster extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->_ci = &get_instance();
		$this->_ci->load->database();
		$this->_ci->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model']);
	}

	public function index(){
		$file = './files/fatich_new/pmk_roster.xlsx';

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
				$arr_data[$row][$column] = str_replace(' ', '', $data_value);
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

		$array_cell = array ("A","B","C","D");
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
					nip = '".$data["B"]."'
			")->result_array();

			if ($pegawai == null) {
				echo "data $no tidak ada di master pegawai <br/>";
			}
			else if(count($pegawai) > 1) {
				echo "NIP data $no terdaftar lebih dari 1 di master pegawai <br/>";
			}
			else {
        $user = $this->session->userdata();

        $roster = $this->_ci->db->query("
  				select
  					*
  				from
  					t_roster
  				where
  					id_pegawai = '".$pegawai[0]["id"]."'
            and tanggal = '".$data["A"]."'
  			")->row_array();

        if($data["C"] == "07:00:00") {
          $id_roster = '02e23264-0986-11e9-a4b6-000c29766abb';
        }
        else if($data["C"] == "15:00:00") {
          $id_roster = '4ae1ca48-0986-11e9-9420-000c29766abb';
        }
        else {
          $id_roster = '620229b6-0986-11e9-b16d-000c29766abb';
        }

        if ($roster == null) {
          $q = "INSERT INTO t_roster (id, tanggal, id_jenis_roster, id_pegawai, userupd, timeupd, user_ins, time_ins) VALUES (uuid_generate_v1(), '".$data['A']."', '".$id_roster."', '".$pegawai[0]['id']."', '".$user['username']."', '".date('Y-m-d H:i:s')."', '".$user['username']."', '".date('Y-m-d H:i:s')."') RETURNING id";

					$res        = $this->pegawai_model->query($q);
					if($res) {
            echo "data $no berhasil input pada roster <br/>";
          }
          else {
            echo "data $no gagal input pada roster <br/>";
          }
        }
        else {
          $update = "
			            UPDATE t_roster
			            SET
			            	id_jenis_roster = '".$id_roster."',
				            userupd = '".$user['username']."',
				            timeupd = '".date('Y-m-d H:i:s')."'
			            WHERE id   = '".$roster["id"]."'";

       		$cek_insert = $this->_ci->db->query($update);
          if($cek_insert) {
            echo "data $no berhasil update pada roster <br/>";
          }
          else {
            echo "data $no gagal update pada roster <br/>";
          }
        }
      }
      $no++;
    }
  }
}
