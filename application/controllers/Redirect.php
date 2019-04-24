<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class redirect extends CI_Controller {

	public function __construct() {
		parent::__construct();
    	
	}

	function index(){
		redirect('login');
		
	}
}
