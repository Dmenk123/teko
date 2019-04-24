<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Update_rumpun_jabatan extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(['import_bkd_excel_model']);
	}
	
	// ################################################################################################################
	// FUNGSI IMPORT
	
	function update_data_staff(){
		
		$query 		= "SELECT * 
					   FROM lap_skor_kehadiran_detil 
					   WHERE jabatan = 'Staf - ' 
					   	 AND deleted_at IS NULL 
					   ORDER BY id_instansi ASC LIMIT 100";
		
		$hasilTemp  = $this->db->query($query)->result();
		
		$i = 0; 
		$min = 0;

		foreach ($hasilTemp as $val) {
			// $i++;
			$tanggal 				= $val->tahun."-".sprintf("%02s", $val->bulan)."-02";
			$query_histori_jabatan  = "SELECT
										rum.nama 
									   FROM
										m_pegawai_rumpun_jabatan_histori AS his
									   JOIN m_pegawai AS peg ON peg.ID = his.id_pegawai
									   JOIN m_rumpun_jabatan AS rum ON rum.id = his.id_rumpun_jabatan
									   WHERE peg.nip = '".$val->nip."' AND his.tgl_mulai <= '".$tanggal."'  
									   ORDER BY his.tgl_mulai DESC LIMIT 1";

			// echo $query_histori_jabatan."<br>$i<br>";
		
			$hasil_staff = $this->db->query($query_histori_jabatan)->row();

			if(!empty($hasil_staff)){
				// echo $val->nip." &nbsp;".$hasil_staff->nama." <br>";
				// $update 	= "UPDATE lap_skor_kehadiran_detil
				// 	            SET
				// 	            jabatan = 'Staf - ".$hasil_staff->nama."'
				// 	            WHERE nip   = '".$val->nip."' and
				// 	              	  bulan  = '".$val->bulan."' and
				// 	              	  tahun  = '".$val->tahun."' and 
				// 	              	  jabatan = 'Staf - ' and
				// 	              	  deleted_at is null";

    //             $this->db->query($update);

				$i++;
			}
			else{
				$min++;
				echo $val->nip." &nbsp;".$val->id_pegawai." - ".$tanggal." - INSTANSI ".$val->id_instansi." <br>";
			}

		}
		echo "<h1>$i OFFSETT = $min</h1>";
	}
	
}

