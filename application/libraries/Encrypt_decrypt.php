<?php
class Encrypt_decrypt extends CI_Controller {
    protected $_ci;
    
    function __construct(){
        $this->_ci = &get_instance();
    }
    
	function dec_enc($action, $string) {
		$output = false;	 
		
		$encrypt_method = "AES-256-CBC";
		$secret_key 	= 'K3yIn1UntukG4rb15';
		$secret_iv 		= 'bismillah';
	 
		$key = hash('sha256', $secret_key);
		
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
	 
		if( $action == 'encrypt' ) {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		}
		else if( $action == 'decrypt' ){
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}
		
		return $output;
	}
	
	function new_id(){
		$queryNewId	= $this->_ci->db->query("select * from uuid_generate_v1() as newid");
		$dataNewId = $queryNewId->row();
		
		return $dataNewId->newid;
	}
}
