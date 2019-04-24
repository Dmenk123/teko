<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function surat(){
		$config['upload_path']   = 'upload/'.$this->input->post('folder'); 
		$config['allowed_types'] = 'pdf|jpg|png|jpeg'; 
		//perhatikan pengaturan server ukuran upload
		//default upload 2 mb
		$config['max_size'] = '5000';

		$this->load->library('upload', $config);
		 
		if ( ! $this->upload->do_upload('userfile')) {
			$status = array('status' => false , 'pesan' => $this->upload->display_errors() );
        }			
         else { 
		 
			$fileUpload = $this->upload->data();
			
			$final_file_name = time()."_".$fileUpload['raw_name'].''.$fileUpload['file_ext'];
			rename($fileUpload['full_path'],$fileUpload['file_path'].$final_file_name);
			
			$status = array('status' => true , 'nama_file' => $final_file_name );		
        } 		
		
		echo(json_encode($status));
	}

	
}
