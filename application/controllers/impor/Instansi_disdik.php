<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Instansi_disdik extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model(['pegawai_unit_kerja_histori_model', 'pegawai_model']);
	}

	public function index(){

        $this->load->library(array('PHPExcel', 'PHPExcel/IOFactory'));
        
        // if (!file_exists('./upload/impor/')) {
        //     mkdir('./upload/impor/', 0777, true);
        // }

        // /** UPLOAD FILE */
        // $file_name = explode('.', $_FILES['file']['name']);
        // $type = $file_name[count($file_name) - 1];

        // $fileName = time();
        
        // $config['upload_path'] = './upload/impor/';
        // $config['file_name'] = $fileName . '.' . $type;
        // $config['allowed_types'] = '*';
        // $config['max_size'] = 10000;
        
            
        // $this->load->library('upload');
        // $this->upload->initialize($config);
            
        // if(! $this->upload->do_upload('file') ) {
        //     echo $this->upload->display_errors();

        //     return;
        // }
        // #end



        // $media = $this->upload->data('file');
        // $inputFileName = './upload/impor/'.$config['file_name'];

        $file_disdik = './upload/dispendik/data.xlsx';
        
        try {
            $objPHPExcel = IOFactory::load($file_disdik);

            $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($file_disdik, PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        $objPHPExcel->setActiveSheetIndex(0);

        $row_active = 6;

        $jml_terimpor = 0;

        //extract to a PHP readable array format
        foreach ($cell_collection as $key => $cell) {
            $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
            $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
            $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
            
            //The header will/should be in row 1 only. of course, this can be modified to suit your need.
            if(! in_array($row, [1,2,3,4,5])) {
                if($row > $row_active) { //flag column terakir
                    if(isset($arr_data['C']) and $arr_data['C']){

                        /** cek nip di m_pegawai */
                        $nip = $arr_data['C'];
                        $m_pegawai = $this->pegawai_model->getData("nip = '$nip'");
                        #end

                        if($m_pegawai) {

                            /** cek id_pegawai di m_pegawai_instansi_histori */
                            $id_pegawai = $m_pegawai->id;
                            $tgl_mulai = "2018-01-01";

                            $m_pegawai_history = $this->pegawai_unit_kerja_histori_model->getData("id_pegawai = '$id_pegawai' and tgl_mulai = '$tgl_mulai'");
                            #end

                            $data = array(
                                'tgl_mulai' 				=> $tgl_mulai,
                                'user_upd' 					=> $m_pegawai->userupd,
                                'tgl_upd' 					=> date('Y-m-d H:i:s'),
                                'id_pegawai' 				=> $m_pegawai->id,
                                'kode_unor' 			    => $arr_data['D'],
                                'excel'                     => true
                            );
                            $query = $this->pegawai_unit_kerja_histori_model->insert($data);

                            // $this->output->enable_profiler(TRUE);

                            echo 'insert history nip: ' . $nip . '<br/>'; 

                            $jml_terimpor++;
                            
                            // return;
                        } else {
                            echo 'TIDAK insert history nip: ' . $nip . '(di m_pegawai tdk ada)<br/>';
                        }
                        #end
                        
                    }

                    $arr_data = [];

                    $row_active++;

                    $arr_data[$column] = $data_value;
                } else {
                    $arr_data[$column] = $data_value;
                }
            }
        }
        #end

        // unlink('./upload/impor/'.$config['file_name']); //hapus file excel

        echo 'jml history unor terimpor: ' . $jml_terimpor . '<br/>'; 
    }
}
