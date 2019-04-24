<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Read_excel_report extends CI_Controller {

	protected $_ci;

  function __construct(){
      // $this->_ci = &get_instance();
      parent::__construct();
      $this->load->database();
      $this->load->model('golongan_model');
      // $this->_ci->load->model(['global_model', 'pegawai_model', 'instansi_model','data_mentah_model']);

  }


	function abc(){

		// include 'Classes/PHPExcel/IOFactory.php';

		// $file_data 	    = $this->upload->data();
		// $file_path 	    =  './uploads/productfile/'.$file_data['file_name'];
		
		// $inputFileName  = $file_path; 
		// $objPHPExcel    = PHPExcel_IOFactory::load($inputFileName);
		// $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		// $arrayCount     = count($allDataInSheet);  // Here get total count of row in that Excel sheet

		// for($i=2;$i<=$arrayCount;$i++){ 

		//     'product'	= $allDataInSheet[$i]["C"],
		//     'brand'		= $allDataInSheet[$i]["I"],
		//     'standard'	= $allDataInSheet[$i]["J"],

		// }

		$file = 'upload/latihan.xls';
 
		$this->load->library('excel');
		 
		$objPHPExcel 	 = PHPExcel_IOFactory::load($file);
		 
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
		// var_dump($cell_collection);die;
		foreach ($cell_collection as $cell) {
		    $column 	= $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
		    $row 		= $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
		    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
		 
		    if ($row == 1) {
		        $header[$row][$column] 	 = $data_value;
		    } else {
		        $arr_data[$row][$column] = $data_value;
		    }		    
		}

		for($i=1;$i<10;$i++) {
			
			// $tanggal 				 = $arr_data[$i]['B'];
			$nip 	 				 = $this->delete_spasi($arr_data[$i]['B']);
			$nama_pegawai 	 		 = $arr_data[$i]['C'];			
			$tempat_lahir 	 		 = $arr_data[$i]['D'];
			$tanggal_lahir 	 		 = $arr_data[$i]['E'];
			$golongan_tgl_mulai  	 = $arr_data[$i]['F'];
			$golongan_nama  	 	 = $arr_data[$i]['G'];
			$jabatan_tgl_mulai	 	 = $arr_data[$i]['H'];
			$jabatan_nama 		 	 = $arr_data[$i]['B'];
			$rumpun_tgl_mulai	 	 = $arr_data[$i]['J'];
			$rumpun_nama 		 	 = $arr_data[$i]['K'];
			$eselon_tanggal 	 	 = $arr_data[$i]['L'];
			$eselon_nama 		 	 = $arr_data[$i]['M'];
			$jenis_kelamin 		 	 = $arr_data[$i]['B'];
			$status_pegawai 	 	 = $arr_data[$i]['O'];
			$unor_tgl_mulai 	 	 = $arr_data[$i]['P'];
			$unor_nama 			 	 = $arr_data[$i]['Q'];
			$instansi 		    	 = $arr_data[$i]['R'];
			$kerja_mulai 		 	 = $arr_data[$i]['S'];
			$jadwal_kerja 		 	 = $arr_data[$i]['T'];
			$meninggal 			 	 = $arr_data[$i]['U'];

			// $jam_masuk 	 			 = $arr_data[$i]['B'];
			// $jam_pulang 	 		 = $arr_data[$i]['B'];
			// $finger_masuk 	 		 = $arr_data[$i]['B'];
			// $finger_pulang 	 		 = $arr_data[$i]['B'];
			// $ada_surat_lembur 	 	 = $arr_data[$i]['B'];
			// $masuk_hari_sebelumnya 	 = $arr_data[$i]['B'];
			// $pulang_hari_berikutnya  = $arr_data[$i]['B'];
			// $nip 	 = $arr_data[$i]['B'];
			// $nip 	 = $arr_data[$i]['B'];
			// if(){

			// }
			// $data = $this->cek_nip(str_replace(" ", "", $arr_data[$i]['B']));
			// if($data){
			// 	echo $data->nip."<br>";
			// }
			// else{
			// 	echo "Tidak Ada<br>";
			// }
		}
		 
		// $data['header'] = $header;
		
		// $data['values'] = $arr_data;
		
		
	}

	function read_data(){
		$file = 'upload/dkrth.xlsx';
 
		$this->load->library('excel');
		 
		$objPHPExcel 	 = PHPExcel_IOFactory::load($file);
		 
		
		// $cell_collection = $objPHPExcel->setActiveSheetIndex(1);
		// var_dump($cell_collection);
		// die;
		// $data = $objPHPExcel->getActiveSheet();
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
		// var_dump($cell_collection);die;
		foreach ($cell_collection as $cell) {
		    $column 	= $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
		    $row 		= $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
		    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
		    // $data_
		 
		    // if ($row == 1) {
		    //     $header[$row][$column] 	 = $data_value;
		    // } else {
		    //     $arr_data[$row][$column] = $data_value;
		    // }		    
		}
		// var_dump($cell_collection);die;
		// foreach ($cell_collection as $cell) {
		//     $column 	= $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
		//     $row 		= $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
		//     $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
		 
		//     if ($row == 1) {
		//         $header[$row][$column] 	 = $data_value;
		//     } else {
		//         $arr_data[$row][$column] = $data_value;
		//     }		    
		// }

	}

	function delete_spasi($var){
		return str_replace(" ", "", $var);
	}

	function rules_golongan($var_next, $var){
		if(empty($this->delete_spasi($var_next)) ){
			$data = array(
					'kode' => $this->input->post('KODE'),
					'nama' => $this->input->post('NAMA'),
					'deskripsi' => $this->input->post('DESKRIPSI'),
					'kode_pangkat' => $this->input->post('KODE_PANGKAT'),
					'kode_huruf' => $this->input->post('KODE_HURUF')
				);

			$query = $this->golongan_model->insert($data);

			// insert Temp

			return true;
		}
		return false;		
	}

	function rules_jabatan($var_next, $var){
		if(empty($this->delete_spasi($var_next)) ){
			// insert jabatan history

			return true;
		}
		return false;		
	}

	function rules_rumpun($var_next, $var){
		if(empty($this->delete_spasi($var_next)) ){
			// insert golongan

			return true;
		}
		return false;		
	}

	function rules_eselon($var_next, $var){
		if(empty($this->delete_spasi($var_next)) ){
			// insert golongan

			return true;
		}
		return false;		
	}

	function cek_nip($nip){
    	return  $this->_ci->db->query("select * from m_pegawai where nip = '".$nip."'")->row();
  	}

	function cek_izin($date, $id_pegawai){
     	return  $this->_ci->db->query("select
							        t_ijin_cuti_pegawai.id as id_ijin_cuti_pegawai,
							        m_jenis_ijin_cuti.kode,
							        m_jenis_ijin_cuti.nama
							       from
							        t_ijin_cuti_pegawai ,m_jenis_ijin_cuti
							       where
							        t_ijin_cuti_pegawai.is_delete   = 0 and
							        t_ijin_cuti_pegawai.tgl_mulai  <= '".$date."'  and
							        t_ijin_cuti_pegawai.tgl_selesai  >= '".$date."'  and
							        t_ijin_cuti_pegawai.id_jenis_ijin_cuti = m_jenis_ijin_cuti.id and
							        t_ijin_cuti_pegawai.id_pegawai = '".$id_pegawai."'")->row();
  }

  function cek_roster($date, $id_pegawai){
    $cek_roster =   $this->_ci->db->query("select
            t_roster.id as id_t_roster,
            m_jenis_roster.id as id_jenis_roster,
            m_jenis_roster.kode,
            m_jam_kerja.id as id_jam_kerja,

            to_char(m_jam_kerja.jam_mulai_scan_masuk,'HH24:MI') as jam_mulai_scan_masuk ,
            to_char(m_jam_kerja.jam_akhir_scan_masuk,'HH24:MI') as jam_akhir_scan_masuk ,
            to_char(m_jam_kerja.jam_masuk,'HH24:MI') as jam_masuk ,

            to_char(m_jam_kerja.jam_mulai_scan_pulang,'HH24:MI') as jam_mulai_scan_pulang ,
            to_char(m_jam_kerja.jam_akhir_scan_pulang,'HH24:MI') as jam_akhir_scan_pulang ,
            to_char(m_jam_kerja.jam_pulang,'HH24:MI') as jam_pulang ,

            m_jam_kerja.pulang_hari_berikutnya,
            m_jam_kerja.masuk_hari_sebelumnya
          from
            t_roster,  m_jenis_roster, m_jam_kerja
          where
            to_char( t_roster.tanggal,'yyyy-mm-dd') = '".$date."' and
            t_roster.id_pegawai = '".$id_pegawai."' and
            t_roster.id_jenis_roster = m_jenis_roster.id and
            m_jenis_roster.id_jam_kerja = m_jam_kerja.id ");

      return  $cek_roster->row();

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

  function cek_lembur($date, $id_pegawai){
    $cek_lembur =   $this->_ci->db->query("select
          id,
          to_char(t_lembur_pegawai.jam_lembur_akhir,'HH24:MI') as jam_lembur_akhir ,
          to_char(t_lembur_pegawai.jam_lembur_awal,'HH24:MI') as jam_lembur_awal
        from
          t_lembur_pegawai
        where
          t_lembur_pegawai.is_delete = 0 and
          to_char(t_lembur_pegawai.tgl_lembur  ,'yyyy-mm-dd')  = '".$date."'  and
          t_lembur_pegawai.id_pegawai = '".$id_pegawai."' ");

    return $cek_lembur->row();
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


  function ambil_finger_masuk($date_mulai, $date_akhir, $id_pegawai){

    $finger =  $this->_ci->db->query("select
                to_char(tanggal,'yyyy-mm-dd HH24:MI') as tanggal
              from
                absensi_log
              where
                tanggal >= '".$date_mulai."'
                AND tanggal <=  '".$date_akhir."' and
                concat(absensi_log.badgenumber,absensi_log.id_mesin) in (SELECT concat(user_id,id_mesin) from mesin_user where id_pegawai = '".$id_pegawai."')
              order by
                absensi_log.tanggal asc")->row();

      if ($finger == null)
      {
        $finger = new stdClass();
        $finger->tanggal = null;
      }

      return $finger;
  }

  function ambil_finger_pulang($date_mulai, $date_akhir, $id_pegawai){
      $finger =   $this->_ci->db->query("select
                to_char(tanggal,'yyyy-mm-dd HH24:MI') as tanggal
              from
                absensi_log
              where
                tanggal >= '".$date_mulai."'
                AND tanggal <=  '".$date_akhir."' and
                concat(absensi_log.badgenumber,absensi_log.id_mesin) in (SELECT concat(user_id,id_mesin) from mesin_user where id_pegawai = '".$id_pegawai."')
              order by
                absensi_log.tanggal desc LIMIT 1")->row();

      if ($finger == null)
      {
        $finger = new stdClass();
        $finger->tanggal = null;
      }

      return $finger;
  }

  function cek_jumlah_finger($date_mulai, $date_akhir, $id_pegawai){
      return  $this->_ci->db->query("select
                count(distinct(tanggal))  as jumlah
              from
                absensi_log
              where
                tanggal >= '".$date_mulai."'
                AND tanggal <=  '".$date_akhir."' and
                concat(absensi_log.badgenumber,absensi_log.id_mesin) in (SELECT concat(user_id,id_mesin) from mesin_user where id_pegawai = '".$id_pegawai."')
                ")->row();

  }
  //end cek dengan concat

  function get_nama($id_pegawai){
    return  $this->_ci->db->query("select nama from m_pegawai where id = '".$id_pegawai."' LIMIT 1")->row();
  }

  function ambil_selisih_menit($date_mulai, $date_akhir, $id_pegawai){
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

  function count_data_mentah_pegawai($date, $id_pegawai){
    return $check_jumlah_pegawai_data_mentah  = $this->_ci->db->query("select
              count(id_pegawai) as jumlah
            from
              data_mentah
            where
              tanggal   = '".$date."' and
              id_pegawai  = '".$id_pegawai."'")->row();
  }

  function dayOfWeek($date){
    return date("w", strtotime($date));
  }

  function cek_ulang_data_mentah($date, $id_pegawai, $fungsi, $tampil, $meninggal = null, $tgl_meninggal = null){
    $keterangan             = "";
    $cek_hari_libur         = $this->cek_hari_libur($date);
    $cek_izin               = $this->cek_izin($date, $id_pegawai);
    $data_lembur            = $this->cek_lembur($date, $id_pegawai);

    $nama_hari              = $this->generate_hari($this->dayOfWeek($date));

    $lembur = date("w", strtotime($date));
    $insert = true;

    if($cek_izin){ // if cek ijin
      if($cek_izin->kode == "DK" || $cek_izin->kode == "DL"){

        if($cek_hari_libur || $this->dayOfWeek($date) == 6 || $this->dayOfWeek($date) == 0){
            $menitPulangCepat       = "0";
            $fingerMasuk            = "";
            $fingerPulang           = "";
            $menitTelat             = "0";

            if(strtotime($date) < strtotime('2018-07-01')){
              $menitLembur          = "180";
              $menitLemburDiakui    = "180";
              $variable_menit     = "3";
            }
            else{
              $menitLembur          = "360";
              $menitLemburDiakui    = "360";
              $variable_menit     = "6";
            }

            $jadwalMasuk            = "";
            $jadwalPulang            = "";

            $kodeMasuk              = "*";
            $keteranganMasuk        = "";
            $kodeTidakMasuk         = $cek_izin->kode;
            $keteranganTidakMasuk   = "";
            $jamKerja               = $cek_izin->kode;
            $keterangan             = $cek_izin->kode." DI HARI LIBUR, DAPAT LEMBUR $variable_menit JAM (".$cek_izin->kode.")";
        }
        else{
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
          $kodeTidakMasuk         = $cek_izin->kode;
          $keteranganTidakMasuk   = "";
          $jamKerja               = $cek_izin->kode;
          $keterangan             = $cek_izin->kode." DI HARI LIBUR, DAPAT LEMBUR 3kkk JAM (".$cek_izin->kode.")";
        }
      }
      else{
        if($cek_hari_libur || $this->dayOfWeek($date) == 6 || $this->dayOfWeek($date) == 0){
          $jadwalMasuk            = "";
          $jadwalPulang           = "";
          $menitPulangCepat       = "0";
          $fingerMasuk            = "";
          $fingerPulang           = "";
          $menitTelat             = "0";
          $menitLembur            = "0";
          $menitLemburDiakui      = "0";
          $kodeMasuk              = "*";
          $keteranganMasuk        = "";
          $kodeTidakMasuk         = $cek_izin->kode;
          $keteranganTidakMasuk   = "";
          $jamKerja               = $cek_izin->kode;
          $keterangan             = "IJIN ".$cek_izin->kode." DI HARI LIBUR";
        }
        /////////////// jika hari biasa
        else{
          $jadwalMasuk            = "";
          $jadwalPulang            = "";
          $menitPulangCepat     = "0";
          $fingerMasuk          = "";
          $fingerPulang         = "";
          $menitTelat           = "0";
          $menitLembur          = "0";
          $menitLemburDiakui    = "0";
          $kodeMasuk            = "*";
          $keteranganMasuk      = "";
          $kodeTidakMasuk       = $cek_izin->kode;
          $keteranganTidakMasuk = $cek_izin->nama;
          $jamKerja             = $cek_izin->kode;
          $keterangan           = "IJIN ".$cek_izin->kode." DI HARI KERJA";
        }
      }
    } //end cek ijin
    else{ //else cek ijin
      $cek_roster = $this->cek_roster($date, $id_pegawai);
      if($cek_roster){ //cek roster
        if($cek_roster->masuk_hari_sebelumnya == 't'){
          $jadwalMasuk      = date("Y-m-d", strtotime("-1 days", strtotime($date)))." ".$cek_roster->jam_pulang;
          $scanMulaiMasuk   = date("Y-m-d", strtotime("-1 days", strtotime($date)))." ".$cek_roster->jam_mulai_scan_masuk;
          $scanAkhirMasuk   = date("Y-m-d", strtotime("-1 days", strtotime($date)))." ".$cek_roster->jam_akhir_scan_masuk;
          if(strtotime($scanMulaiMasuk) > strtotime($scanAkhirMasuk)) {
            $scanAkhirMasuk   = $date." ".$cek_roster->jam_akhir_scan_masuk;
          }
        }
        else{
          //echo "asdasd";
          $jadwalMasuk      = $date." ".$cek_roster->jam_masuk;
          $scanMulaiMasuk   = $date." ".$cek_roster->jam_mulai_scan_masuk;
          $scanAkhirMasuk   = $date." ".$cek_roster->jam_akhir_scan_masuk;
          if(strtotime($scanMulaiMasuk) > strtotime($scanAkhirMasuk)) {
            $scanAkhirMasuk   = date("Y-m-d", strtotime("+1 days", strtotime($date)))." ".$cek_roster->jam_akhir_scan_masuk;
          }
        }

        if($cek_roster->pulang_hari_berikutnya == 't'){
          $jadwalPulang     = date("Y-m-d", strtotime("+1 days", strtotime($date)))." ".$cek_roster->jam_pulang;
          $scanMulaiPulang  = date("Y-m-d", strtotime("+1 days", strtotime($date)))." ".$cek_roster->jam_mulai_scan_pulang;
          $scanAkhirPulang  = date("Y-m-d", strtotime("+1 days", strtotime($date)))." ".$cek_roster->jam_akhir_scan_pulang;
          if(strtotime($scanMulaiPulang) > strtotime($scanAkhirPulang)) {
            $scanAkhirPulang   = date("Y-m-d", strtotime("+2 days", strtotime($date)))." ".$cek_roster->jam_akhir_scan_pulang;
          }
        }
        else{
          $jadwalPulang     = $date." ".$cek_roster->jam_pulang;
          $scanMulaiPulang  = $date." ".$cek_roster->jam_mulai_scan_pulang;
          $scanAkhirPulang  = $date." ".$cek_roster->jam_akhir_scan_pulang;
          if(strtotime($scanMulaiPulang) > strtotime($scanAkhirPulang)) {
            $scanAkhirPulang   = date("Y-m-d", strtotime("+1 days", strtotime($date)))." ".$cek_roster->jam_akhir_scan_pulang;
          }
        }

        if($cek_roster->kode == "LB"){
          $tanggalBesok = date ("Y-m-d", strtotime("+1 days", strtotime($date)));
          if($data_lembur){
            $data_jumlah_finger   = $this->cek_jumlah_finger($date." 00:01", $date." 23:59", $id_pegawai);
            if($data_jumlah_finger->jumlah > 1){
            
              $finger_masuk           = $this->ambil_finger_masuk($date." 00:01", $date." 23:59", $id_pegawai)->tanggal;
              $finger_pulang          = $this->ambil_finger_pulang($date." 00:01", $date." 23:59", $id_pegawai)->tanggal;
              $selisih_finger         = $this->ambil_selisih_menit($finger_masuk, $finger_pulang, $id_pegawai);

              //start perwali desember 2018
              if(strtotime($date) >= strtotime('2018-12-01')) {
                if($selisih_finger > 360){
                  $menitLemburDiakui    = 360;
                }
                else{
                  $menitLemburDiakui    = $selisih_finger;
                }
              }else{
                $menitLemburDiakui    = $selisih_finger;
              }

              $menitPulangCepat       = "0";
              $fingerMasuk            = "";
              $jadwalMasuk            = "";
              $jadwalPulang            = "";
              $fingerPulang           = "";
              $menitTelat             = "0";
              $menitLembur            = $selisih_finger;
              $menitLemburDiakui      = $menitLemburDiakui;
              $kodeMasuk              = "*";
              $keteranganMasuk        = "";
              $kodeTidakMasuk         = "LB";
              $keteranganTidakMasuk   = "";
              $jamKerja               = "";
              $keterangan             = "LIBUR ROSTER DENGAN SURAT SESUAI FINGER";
            }
            else{
              $selisih = $this->ambil_selisih_menit($scanMulaiMasuk, $scanAkhirMasuk, $id_pegawai);
              $jadwalMasuk            = "";
              $jadwalPulang            = "";
              $menitPulangCepat       = "0";
              $fingerMasuk            = "";
              $fingerPulang           = "";
              $menitTelat             = "0";
              $menitLembur            = $this->ambil_selisih_menit($date." ".$data_lembur->jam_lembur_awal, $date." ".$data_lembur->jam_lembur_akhir, $id_pegawai);

              //start perwali desember 2018
              if(strtotime($date) >= strtotime('2018-12-01')) {
                if($menitLembur > 360){
                  $menitLemburDiakui    = 360;
                }
                else{
                  $menitLemburDiakui    = $menitLembur;
                }
              }else{
                $menitLemburDiakui    = $menitLembur;
              }

              //$menitLemburDiakui      = $this->ambil_selisih_menit($date." ".$data_lembur->jam_lembur_awal, $date." ".$data_lembur->jam_lembur_akhir, $id_pegawai);

              $kodeMasuk              = "*";
              $keteranganMasuk        = "";
              $kodeTidakMasuk         = "LB";
              $keteranganTidakMasuk   = "";
              $jamKerja               = "";
              $keterangan             = "LIBUR ROSTER SESUAI SURAT LEMBUR";
            }
          }
          else{
            $jadwalMasuk            = "";
            $jadwalPulang           = "";
            $menitPulangCepat       = "0";
            $fingerMasuk            = "";
            $fingerPulang           = "";
            $menitTelat             = "0";
            $menitLembur            = "0";
            $menitLemburDiakui      = "0";
            $kodeMasuk              = "*";
            $keteranganMasuk        = "";
            $kodeTidakMasuk         = "LB";
            $keteranganTidakMasuk   = "";
            $jamKerja               = "";
            $keterangan             = "LIBUR ROSTER";
          }
        }
        else{
     
          $finger_masuk           = $this->ambil_finger_masuk($scanMulaiMasuk, $scanAkhirMasuk, $id_pegawai)->tanggal;
          $finger_pulang          = $this->ambil_finger_pulang($scanMulaiPulang, $scanAkhirPulang, $id_pegawai)->tanggal;
        

          //////////// jika tidak ada finger masuk dan pulang sesuai data Master Roster
          if(!$finger_masuk && !$finger_pulang){

            $fingerMasuk              = "";
            $fingerPulang             = "";
            $menitPulangCepat         = "0";
            $menitTelat               = "0";
            $menitLembur              = "0";
            # start perwali desember 2018
            $seda = false;
    				if(strtotime($date) >= strtotime('2018-12-01')) {
              if($meninggal == 't'){
                if(strtotime($date) >= strtotime($tgl_meninggal)) {
                  $seda = true;
                }
              }
            }
            if($seda) {
              $kodeMasuk                = "MD";
              $kodeTidakMasuk           = "MD";
              $keteranganTidakMasuk     = "MENINGGAL DUNIA";
              $keterangan               = "MENINGGAL DUNIA";
            }
            else {
              $kodeMasuk                = "M";
              $kodeTidakMasuk           = "M";
              $keteranganTidakMasuk     = "MANGKIR";
              $keterangan               = "MANGKIR ROSTER, TIDAK ADA FINGER MASUK DAN PULANG";
            }
            # end perwali desember 2018
            $keteranganMasuk          = "";
            $menitLemburDiakui        = "0";
            $jamKerja                 = $cek_roster->kode;
          }
          //////////// jika ada finger masuk dan pulang sesuai data Master Roster
          else{
            
            // IF LEMBUR
            if($data_lembur){
              if(strtotime($finger_pulang) <  strtotime($date." ".$cek_roster->jam_akhir_scan_pulang) && strtotime($finger_pulang) > strtotime($date." ".$cek_roster->jam_pulang)){
                $menit_lembur          = $this->ambil_selisih_menit($date." ".$data_lembur->jam_lembur_awal, $date." ".$data_lembur->jam_lembur_akhir, $id_pegawai);
              
                if(strtotime($date) >= strtotime('2018-12-01')) {
                  if($menit_lembur > 360){
                    $menitLemburDiakui    = 360;
                  }
                  else{
                    $menitLemburDiakui    = $menit_lembur;
                  }
                }else{
                  $menitLemburDiakui    = $menit_lembur;
                }

                $menit_lembur_diakui    = $menitLemburDiakui;
                $keterangan            =  "HADIR ROSTER DI HARI $nama_hari LEMBUR DENGAN SURAT SESUAI FINGER";
              }
              else{
                $menit_lembur          = $this->ambil_selisih_menit($date." ".$data_lembur->jam_lembur_awal, $date." ".$data_lembur->jam_lembur_akhir, $id_pegawai);

                //start perwali desember 2018
                if(strtotime($date) >= strtotime('2018-12-01')) {
                  if($menit_lembur > 360){
                    $menitLemburDiakui    = 360;
                  }
                  else{
                    $menitLemburDiakui    = $menit_lembur;
                  }
                }else{
                  $menitLemburDiakui = $menit_lembur;
                }

                $menit_lembur_diakui  = $menitLemburDiakui;
                $keterangan           = "HADIR ROSTER DI HARI $nama_hari LEMBUR DENGAN SURAT SESUAI JAM INPUT";
              }
              // $jadwalMasuk            = "";
              // $jadwalPulang           = "";

              $fingerMasuk              = $finger_masuk;
              $fingerPulang           = $finger_pulang;
              $jamKerja                 = $cek_roster->kode;
              $menitPulangCepat         = "0";
              $menitTelat               = "0";
              $menitLembur            = $menit_lembur_diakui;
              $menitLemburDiakui      = $menit_lembur_diakui;
              $kodeMasuk              = "*";
              $keteranganMasuk        = "";
              $kodeTidakMasuk           = "LB";
              $keteranganTidakMasuk     = "";
             
            }
            else{
              $menit_telat           = $this->jumlah_menit_telat_hari_kerja($finger_masuk, $date." ".$cek_roster->jam_masuk);
              $menit_cepat_pulang    = $this->jumlah_menit_cepat_pulang_hari_kerja($finger_pulang, $date." ".$cek_roster->jam_pulang);
              $menit_lembur          = $this->ambil_selisih_menit($date." ".$cek_roster->jam_pulang, $finger_pulang, 0);
              $fingerMasuk            = $finger_masuk;
              $menitTelat           = $menit_telat;
              $fingerPulang         = $finger_pulang;
              $menitLembur            = $menit_lembur;
              $menitPulangCepat     = $menit_cepat_pulang;
              $jamKerja               = $cek_roster->kode;

              if($menitLembur > 180){
                $menitLemburDiakui = 180;
              }
              else{
                $menitLemburDiakui = $menitLembur;
              }

              $kodeMasuk              =   "H";
              $keteranganMasuk        =   "";
              $kodeTidakMasuk           =   "";
              $keteranganTidakMasuk     =   "";
              $keterangan         = "HADIR ROSTER";
            }
          }
          // $keterangan              = "MASUK ROSTER";
          // END SHOFI #####################################################

        }
      } //end if cek_roster
      else{
        if($cek_hari_libur){ //if hari libur
          if($data_lembur){ // if data lembur
            $data_jumlah_finger   = $this->cek_jumlah_finger($date." 00:01", $date." 23:59", $id_pegawai);
            if($data_jumlah_finger->jumlah > 1){ //if jumlah data finger > 1
              $finger_masuk           = $this->ambil_finger_masuk($date." 00:01", $date." 23:59", $id_pegawai)->tanggal;
              // var_dump($finger_masuk);exit;

              $finger_pulang          = $this->ambil_finger_pulang($date." 00:01", $date." 23:59", $id_pegawai)->tanggal;
              $selisih_finger         = $this->ambil_selisih_menit($finger_masuk, $finger_pulang, $id_pegawai);

              //start perwali desember 2018
              if(strtotime($date) >= strtotime('2018-12-01')) {
                if($selisih_finger > 360){
                  $menitLemburDiakui    = 360;
                }
                else{
                  $menitLemburDiakui    = $selisih_finger;
                }
              }

              $jadwalMasuk            = "";
              $jadwalPulang            = "";
              $menitPulangCepat       = "0";
              $fingerMasuk            =$finger_masuk;
              $fingerPulang           =$finger_pulang;
              $menitTelat             = "0";
              $menitLembur            = $selisih_finger;
              $menitLemburDiakui      = $menitLemburDiakui;
              $kodeMasuk              = "*";
              $keteranganMasuk        = "";
              $kodeTidakMasuk         = "LB";
              $keteranganTidakMasuk   = "";
              $jamKerja               = $cek_hari_libur->nama;
              $keterangan             = "LIBUR ".$cek_hari_libur->nama." DENGAN SURAT SESUAI FINGER";
            } //end if jumlah data finger > 1
            else{
              $menitLembur            = $this->ambil_selisih_menit($date." ".$data_lembur->jam_lembur_awal, $date." ".$data_lembur->jam_lembur_akhir, $id_pegawai);
            
              //start perwali desember 2018
              if(strtotime($date) >= strtotime('2018-12-01')) {
                if($menitLembur > 360){
                  $menitLemburDiakui    = 360;
                }
                else{
                  $menitLemburDiakui    = $menitLembur;
                }
              }

              $jadwalMasuk            = "";
              $jadwalPulang           = "";
              $menitPulangCepat       = "0";
              $fingerMasuk            = "";
              $fingerPulang           = "";
              $menitTelat             = "0";
              $kodeMasuk              = "*";
              $keteranganMasuk        = "";
              $kodeTidakMasuk         = "LB";
              $keteranganTidakMasuk   = "";
              $jamKerja               = $cek_hari_libur->nama;
              $keterangan             = "LIBUR ".$cek_hari_libur->nama." SESUAI SURAT LEMBUR";
            }
          } // end if data lembur
          else{
            $jadwalMasuk            = "";
            $jadwalPulang            = "";
            $menitPulangCepat       = "0";
            $fingerMasuk            = "";
            $fingerPulang           = "";
            $menitTelat             = "0";
            $menitLembur            = "0";
            $menitLemburDiakui      = "0";
            $kodeMasuk              = "*";
            $keteranganMasuk        = "";
            $kodeTidakMasuk         = "LB";
            $keteranganTidakMasuk       = "";
            $jamKerja               = $cek_hari_libur->nama;
            $keterangan             = "LIBUR ".$cek_hari_libur->nama." ";
          }
        } //end if hari libur
        else{
          if($this->dayOfWeek($date) == 6){
            $data_jumlah_finger   = $this->cek_jumlah_finger($date." 00:01", $date." 23:59", $id_pegawai);
            if($data_lembur){ //if data lembur
              if($data_jumlah_finger->jumlah > 1){ // if jumlah finger lebih dari 1
                $finger_masuk           = $this->ambil_finger_masuk($date." 00:01", $date." 23:59", $id_pegawai)->tanggal;
                $finger_pulang          = $this->ambil_finger_pulang($date." 00:01", $date." 23:59", $id_pegawai)->tanggal;
                $selisih_finger         = $this->ambil_selisih_menit($finger_masuk, $finger_pulang, $id_pegawai);

                //start perwali desember 2018
                if(strtotime($date) >= strtotime('2018-12-01')) {
                  if($selisih_finger > 360){
                    $menitLemburDiakui    = 360;
                  }
                  else{
                    $menitLemburDiakui    = $selisih_finger;
                  }
                }else{
                  $menitLemburDiakui = $selisih_finger;
                }

                $jadwalMasuk            = "";
                $jadwalPulang           = "";
                $menitPulangCepat       = "0";
                $fingerMasuk            = $finger_masuk;
                $fingerPulang           = $finger_pulang;
                $menitTelat             = "0";
                $menitLembur            = $selisih_finger;
                $menitLemburDiakui      = $menitLemburDiakui;
                $kodeMasuk              = "*";
                $keteranganMasuk        = "";
                $kodeTidakMasuk         = "LB";
                $keteranganTidakMasuk   = "";
                $jamKerja               = "";
                $keterangan             = "LEMBUR HARI SABTU DENGAN SURAT SESUAI FINGER";
              } // end jika jumlah finger lebih dari 1
              else{
                $jadwalMasuk            = "";
                $jadwalPulang           = "";
                $menitPulangCepat       = "0";
                $fingerMasuk            = "";
                $fingerPulang           = "";
                $menitTelat             = "0";
                $menitLembur            = $this->ambil_selisih_menit($date." ".$data_lembur->jam_lembur_awal, $date." ".$data_lembur->jam_lembur_akhir, $id_pegawai);

                //start perwali desember 2018
                if(strtotime($date) >= strtotime('2018-12-01')) {
                  if($menitLembur > 360){
                    $menitLemburDiakui    = 360;
                  }
                  else{
                    $menitLemburDiakui    = $menitLembur;
                  }
                }else{
                  $menitLemburDiakui = $menitLembur;
                }

            
                $kodeMasuk              = "*";
                $keteranganMasuk        = "";
                $kodeTidakMasuk         = "LB";
                $keteranganTidakMasuk   = "";
                $jamKerja               = "";
                $keterangan             = "LEMBUR HARI SABTU SESUAI SURAT";
              }
            } //end if data lembur
            else{
              if($data_jumlah_finger->jumlah > 1){ // if data jumlah finger lebih dari 1
                $finger_masuk           = $this->ambil_finger_masuk($date." 00:01", $date." 23:59", $id_pegawai)->tanggal;
                $finger_pulang          = $this->ambil_finger_pulang($date." 00:01", $date." 23:59", $id_pegawai)->tanggal;
                $selisih_finger         = $this->ambil_selisih_menit($finger_masuk, $finger_pulang, $id_pegawai);
                $jadwalMasuk            = "";
                $jadwalPulang           = "";

                if($selisih_finger > 360){
                  $menitLemburDiakui    = 360;
                }
                else{
                  $menitLemburDiakui    = $selisih_finger;
                }

                $menitPulangCepat       = "0";
                $fingerMasuk            = $finger_masuk;
                $fingerPulang           = $finger_pulang;
                $menitTelat             = "0";
                $menitLembur            = $selisih_finger;
                $kodeMasuk              = "*";
                $keteranganMasuk        = "";
                $kodeTidakMasuk         = "LB";
                $keteranganTidakMasuk   = "";
                $jamKerja               = "";
                $keterangan             = "LEMBUR HARI SABTU SESUAI FINGER";
              } // end if data jumlah finger lebih dari 1
              else{
                $jadwalMasuk            = "";
                $jadwalPulang           = "";
                $fingerMasuk            = "";
                $fingerPulang           = "";
                $menitPulangCepat       = "0";
                $menitTelat             = "0";
                $menitLembur            = "0";
                $menitLemburDiakui      = "0";
                $kodeMasuk              = "*";
                $keteranganMasuk        = "";
                $kodeTidakMasuk         = "LB";
                $keteranganTidakMasuk   = "";
                $jamKerja               = "";
                $keterangan             = "LIBUR DI HARI SABTU";
              }
            }
          }
          else if($this->dayOfWeek($date) == 0){
            $data_jumlah_finger   = $this->cek_jumlah_finger($date." 00:01", $date." 23:59", $id_pegawai);
            if($data_lembur){
              if($data_jumlah_finger->jumlah > 1){

                $finger_masuk           = $this->ambil_finger_masuk($date." 00:01", $date." 23:59", $id_pegawai)->tanggal;
                // var_dump($finger_masuk);exit;

                $finger_pulang          = $this->ambil_finger_pulang($date." 00:01", $date." 23:59", $id_pegawai)->tanggal;
                $selisih_finger         = $this->ambil_selisih_menit($finger_masuk, $finger_pulang, $id_pegawai);

                //start perwali desember 2018
                if(strtotime($date) >= strtotime('2018-12-01')) {
                  if($selisih_finger > 360){
                    $menitLemburDiakui    = 360;
                  }
                  else{
                    $menitLemburDiakui    = $selisih_finger;
                  }
                }else{
                  $menitLemburDiakui    = $selisih_finger;
                }

                $jadwalMasuk            = "";
                $jadwalPulang           = "";
                $menitPulangCepat       = "0";
                $fingerMasuk            = $finger_masuk;
                $fingerPulang           = $finger_pulang;
                $menitTelat             = "0";

                $menitLembur            = $selisih_finger;
                $menitLemburDiakui      = $menitLemburDiakui;
                $kodeMasuk              = "*";
                $keteranganMasuk        = "";
                $kodeTidakMasuk         = "LB";
                $keteranganTidakMasuk   = "";
                $jamKerja               = "";
                $keterangan             = "LEMBUR HARI MINGGU DENGAN SURAT SESUAI FINGER";
              }
              else{
                $menitLembur            = $this->ambil_selisih_menit($date." ".$data_lembur->jam_lembur_awal, $date." ".$data_lembur->jam_lembur_akhir, $id_pegawai);

                //start perwali desember 2018
                if(strtotime($date) >= strtotime('2018-12-01')) {
                  if($menitLembur > 360){
                    $menitLemburDiakui    = 360;
                  }
                  else{
                    $menitLemburDiakui    = $menitLembur;
                  }
                }
                else{
                  $menitLemburDiakui    = $menitLembur;
                }

                //$menitLemburDiakui      = $this->ambil_selisih_menit($date." ".$data_lembur->jam_lembur_awal, $date." ".$data_lembur->jam_lembur_akhir, $id_pegawai);
                $jadwalMasuk            = "";
                $jadwalPulang           = "";
                $menitPulangCepat       = "0";
                $fingerMasuk            = "";
                $fingerPulang           = "";

                $menitTelat             = "0";
                $kodeMasuk              = "*";
                $keteranganMasuk        = "";
                $kodeTidakMasuk         = "LB";
                $keteranganTidakMasuk   = "";
                $jamKerja               = "";
                $keterangan             = "LEMBUR HARI MINGGU SESUAI SURAT";
              }
            }
            else{
                $fingerMasuk            = "";
                $fingerPulang           = "";
                $jadwalMasuk            = "";
                $jadwalPulang           = "";
                $menitPulangCepat       = "0";
                $menitTelat             = "0";
                $menitLembur            = "0";
                $menitLemburDiakui      = "0";
                $kodeMasuk              = "*";
                $keteranganMasuk        = "";
                $kodeTidakMasuk         = "LB";
                $keteranganTidakMasuk   = "";
                $jamKerja               = "";
                $keterangan             = "LIBUR DI HARI MINGGU";
            }
          }
          else{ // Senin - Jumat

            $data_jumlah_finger   = $this->cek_jumlah_finger($date." 00:01", $date." 23:59", $id_pegawai);
            $role_finger            = $this->cek_jam_diperbolehkan_finger($date, $id_pegawai);

            if(!$role_finger){
              if($tampil){
                echo "<h1><font color='red'>Tidak ada Role Jam Kerja</font></h1><br>";
              }

              $jadwalMasuk          = "";
              $jadwalPulang         = "";
              $fingerMasuk          = "";
              $fingerPulang         =   "";
              $menitPulangCepat     =   "0";
              $menitTelat           =   "0";
              $menitLembur          =   "0";
              $kodeMasuk            =   "";
              $keteranganMasuk      =   "";
              $kodeTidakMasuk       =   "";
              $keteranganTidakMasuk =   "";
              $menitLemburDiakui    =   "";
              $keterangan       = "NO ROLE JAM KERJA DI HARI $nama_hari";
              $insert = false;

            }
            else{
              if($data_jumlah_finger->jumlah < 1){
                $jadwalMasuk          = $date." ".$role_finger->jam_masuk;
                $jadwalPulang         = $date." ".$role_finger->jam_pulang;

                $jamKerja             = $role_finger->jam_masuk." ".$role_finger->jam_pulang;
                $fingerMasuk          = "";
                $fingerPulang         =   "";
                $menitPulangCepat     =   "0";
                $menitTelat           =   "0";
                $menitLembur          =   "0";
                # start perwali desember 2018
                $seda = false;
        				if(strtotime($date) >= strtotime('2018-12-01')) {
                  if($meninggal == 't'){
                    if(strtotime($date) >= strtotime($tgl_meninggal)) {
                      $seda = true;
                    }
                  }
                }
                if($seda) {
                  $kodeMasuk                = "MD";
                  $kodeTidakMasuk           = "MD";
                  $keteranganTidakMasuk     = "MENINGGAL DUNIA";
                  $keterangan               = "MENINGGAL DUNIA";
                }
                else {
                  $kodeMasuk                = "M";
                  $kodeTidakMasuk           = "M";
                  $keteranganTidakMasuk     = "MANGKIR";
                  $keterangan               = "MANGKIR DI HARI $nama_hari";
                }
                # end perwali desember 2018
                $keteranganMasuk      =   "";
                $menitLemburDiakui    =   "0";
              }
              else{

                $finger_masuk        = $this->ambil_finger_masuk($date." ".$role_finger->jam_mulai_scan_masuk, $date." ".$role_finger->jam_akhir_scan_pulang, $id_pegawai)->tanggal;
                $finger_pulang       = $this->ambil_finger_pulang($date." ".$role_finger->jam_mulai_scan_masuk, $date." ".$role_finger->jam_akhir_scan_pulang, $id_pegawai)->tanggal;
                $selisih_finger      = $this->ambil_selisih_menit($finger_masuk, $finger_pulang, $id_pegawai);

                // jika finger masuk lebih besadari jadwal masuk / telat
                if(strtotime($finger_masuk) > strtotime($date." ".$role_finger->jam_masuk) ){
                  $menit_telat          = $this->jumlah_menit_telat_hari_kerja($finger_masuk, $date." ".$role_finger->jam_masuk);
                }
                else{
                  $menit_telat          = 0;
                }

            
                if(strtotime($finger_pulang) < strtotime($date." ".$role_finger->jam_pulang)){
                  $menit_cepat_pulang   = $this->jumlah_menit_cepat_pulang_hari_kerja($finger_pulang, $date." ".$role_finger->jam_pulang);
                }
                else{
                  $menit_cepat_pulang          = 0;
                }


                if($data_lembur){
                  // var_dump($role_finger->jam_akhir_scan_pulang);exit;
                  if(strtotime($finger_pulang) <  strtotime($date." ".$role_finger->jam_akhir_scan_pulang) && strtotime($finger_pulang) > strtotime($date." ".$role_finger->jam_pulang)){

                    $menit_lembur         = $this->ambil_selisih_menit($date." ".$role_finger->jam_pulang, $finger_pulang, 0);

                  //start perwali desember 2018
                  if(strtotime($date) >= strtotime('2018-12-01')) {
                    if($menit_lembur > 180)
                    {
                      $menit_lembur_diakui    = 180;
                    }
                    else
                    {
                      $menit_lembur_diakui    = $menit_lembur;
                    }
                  }
                  else
                  {
                    $menit_lembur_diakui    = $menit_lembur;
                  }


                    $keterangan           = "HADIR DI HARI $nama_hari LEMBUR DENGAN SURAT SESUAI FINGER";
                  }
                  else{

                    $menit_lembur             = $this->ambil_selisih_menit($date." ".$data_lembur->jam_lembur_awal, $date." ".$data_lembur->jam_lembur_akhir, $id_pegawai);

                    //start perwali desember 2018
                    if(strtotime($date) >= strtotime('2018-12-01')) {
                      if($menit_lembur > 180)
                      {
                        $menit_lembur_diakui    = 180;
                      }
                      else
                      {
                        $menit_lembur_diakui    = $menit_lembur;
                      }
                    }
                    else
                    {
                      $menit_lembur_diakui    = $menit_lembur;
                    }

                    $keterangan           = "HADIR DI HARI $nama_hari LEMBUR DENGAN SURAT SESUAI JAM INPUT";
                  }
                }
                else{
                  if(strtotime($finger_pulang) > strtotime($date." ".$role_finger->jam_pulang) ){
                    $menit_lembur         = $this->ambil_selisih_menit($date." ".$role_finger->jam_pulang, $finger_pulang, 0);

                      if($menit_lembur > 180){
                        $menit_lembur_diakui    = 180;
                      }
                      else{
                        $menit_lembur_diakui    = $menit_lembur;
                      }
                    $keterangan           = "HADIR DI HARI $nama_hari DENGAN LEMBUR";
                    }
                  else{
                    $menit_lembur_diakui    = 0;
                    $menit_lembur          = 0;
                    $keterangan           = "HADIR DI HARI $nama_hari TIDAK LEMBUR";
                  }

                  /// tidak ada e\lembur
                }


                $jamKerja         = $role_finger->jam_masuk." ".$role_finger->jam_pulang;
                $jadwalMasuk          = $date." ".$role_finger->jam_masuk;
                $jadwalPulang         = $date." ".$role_finger->jam_pulang;
                $fingerMasuk          = $finger_masuk;
                $fingerPulang         = $finger_pulang;

                $menitPulangCepat     = $menit_cepat_pulang;
                $menitTelat           = $menit_telat;

                $menitLembur          = $menit_lembur;
                $menitLemburDiakui    = $menit_lembur_diakui;

                // SHOFI TAMBAHAN ################################
                if($finger_masuk == $finger_pulang){
                  $menitLembur          = "0";
                  $menitLemburDiakui    = "0";
                }
                // END SHOFI

                $kodeMasuk            = "H";
                $keteranganMasuk      = "";
                $kodeTidakMasuk       = "";
                $keteranganTidakMasuk = "";

             
              }
            }
          }
        }
      }
    } // end else cek ijin

    if($fungsi == "insert"){
      if($this->count_data_mentah_pegawai($date, $id_pegawai)->jumlah > 0){
        if($tampil){
          echo "<h1>Sudah Ada</h1>";
        }
      }
      else{
        if($insert){
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
              keterangan
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

              '".$menitPulangCepat."',
              '".$menitTelat."',
              '".$menitLembur."',
              '".$menitLemburDiakui."',
              '".$kodeMasuk."',
              '".$keteranganMasuk."',
              '".$kodeTidakMasuk."',
              '".$keteranganTidakMasuk."',
              '".$keterangan."')";

          $this->_ci->db->query($insert);
          if($tampil){
            echo "<h1>INSERT</h1>";
          }
        }

      }
    }
    if($fungsi == "update"){
      if($tampil){
           echo "<h1>Update</h1>";
      }
      if($this->count_data_mentah_pegawai($date, $id_pegawai)->jumlah > 0){
          $insert = "
          insert into
            log_perubahan_data
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
            tanggal_update
            )
           SELECT
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
            now()
            from data_mentah
            where   tanggal   = '".$date."' and
            id_pegawai  = '".$id_pegawai."'";

            // echo $insert."<hr>";
            $this->_ci->db->query($insert);

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

            $update = "
            UPDATE data_mentah
            SET
            hari = '".$nama_hari."',
            jam_kerja = '".$jamKerja."',
            jadwal_masuk = $jadwalMasuk,
            jadwal_pulang = $jadwalPulang,
            finger_masuk = $fingerMasuk,
            finger_pulang = $fingerPulang,
            pulang_cepat = '".$menitPulangCepat."',
            datang_telat = '".$menitTelat."',
            lembur = '".$menitLembur."',
            lembur_diakui = '".$menitLemburDiakui."',
            kode_masuk = '".$kodeMasuk."',
            keterangan_masuk = '".$keteranganMasuk."',
            kode_tidak_masuk = '".$kodeTidakMasuk."',
            keterangan_tidak_masuk = '".$keteranganTidakMasuk."',
            keterangan = '".$keterangan."'
            WHERE tanggal   = '".$date."' and
              id_pegawai  = '".$id_pegawai."'";

                $this->_ci->db->query($update);

      }
    }

    if($tampil){
      echo $this->get_nama($id_pegawai)->nama."<br>";
      echo $id_pegawai."<br>";
      echo $nama_hari."<br>";
      echo $date."<br>";
      echo    "jadwal masuk = ".$jadwalMasuk  ."<br>";
      echo    "jadwal pulang = ".$jadwalPulang   ."<br>";
      echo    "menit pulang cepat = ".$menitPulangCepat   ."<br>";
      echo   "finger masuk = ". $fingerMasuk  ."<br>";
      echo    "finger pulang = ".$fingerPulang    ."<br>";
      echo    "menit telat = ".$menitTelat ."<br>";
      echo    "menit lembur = ".$menitLembur  ."<br>";
      echo      "menit lembur diakui = ".$menitLemburDiakui   ."<br>";
      echo      "kode masuk = ".$kodeMasuk  ."<br>";
      echo    "ket masuk = ".$keteranganMasuk   ."<br>";
      echo    "kode tidak masuk = ".$kodeTidakMasuk ."<br>";
      echo    "ket tidak masuk = ".$keteranganTidakMasuk ."<br>";
      echo    "keterangan = ".$keterangan ."<br><hr>";
    }
 }


	


}
