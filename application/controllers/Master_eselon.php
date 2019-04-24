<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_eselon extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('eselon_model');
	}

	public function index(){
		$like 		  = null;
		$or_like 		= null;
		$order_by 	= 'kode, nama_eselon';
		$urlSearch 	= null;

		if($this->input->get('keyword')){
			$like = array('LOWER(kode)' => strtolower($_GET['keyword']));
			$or_like = array('LOWER(nama_eselon)' => strtolower($_GET['keyword']), 'LOWER(to_char(tunjangan, \'999\'))' => strtolower($_GET['keyword']));
			$urlSearch = "?keyword=".$_GET['keyword'];
		}

		$this->load->library('pagination');

		$config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch;
		$this->jumlahData 		= $this->eselon_model->getCount("",$like,null,null,null,null,$or_like);
		$config['total_rows'] 	= $this->jumlahData;
		$config['per_page'] 	= 10;

		$this->showData = $this->eselon_model->showData("",$like,$order_by,$config['per_page'],$this->input->get('per_page'),null,$or_like);
		$this->pagination->initialize($config);

		$this->template_view->load_view('master/eselon/eselon_view');
	}
	public function add(){
		$this->template_view->load_view('master/eselon/eselon_add_view');
	}

	public function add_data(){
		$this->form_validation->set_rules('KODE', '', 'trim|required');
		$this->form_validation->set_rules('NAMA_ESELON', '', 'trim|required');
		$this->form_validation->set_rules('TUNJANGAN', '', 'trim|callback_numeric_wcomma');
		$this->form_validation->set_rules('KODE_GOL_MAX', '', 'trim');
		$this->form_validation->set_rules('KODE_GOL_MIN', '', 'trim');
		$this->form_validation->set_rules('URUT', '', 'trim');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kode dan Nama Eselon Wajib diisi.');
		}
		else{
			if ($this->exist_data($this->input->post('KODE')) > 0)	{
				$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kode Eselon telah dipakai sebelumnya.');
			}
			else {
				$tunjangan = $this->input->post('TUNJANGAN');
				if($tunjangan == '') {
					$tunjangan = null;
				}
				$data = array(
					'kode' => $this->input->post('KODE'),
					'nama_eselon' => $this->input->post('NAMA_ESELON'),
					'tunjangan' => $tunjangan,
					'kode_gol_max' => $this->input->post('KODE_GOL_MAX')	,
					'kode_gol_min' => $this->input->post('KODE_GOL_MIN')	,
					'urut' => $this->input->post('URUT')
				);

				$query = $this->eselon_model->insert($data);
				$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
			}
		}

		echo(json_encode($status));
	}
	public function edit($IdPrimaryKey){
		$where ="kode = '".$IdPrimaryKey."' ";
		$this->oldData = $this->eselon_model->getData($where);
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}
		$order_by = null;

		$this->template_view->load_view('master/eselon/eselon_edit_view');
	}
	public function edit_data(){
		$this->form_validation->set_rules('KODE_LAMA', '', 'trim|required');
		$this->form_validation->set_rules('KODE', '', 'trim|required');
		$this->form_validation->set_rules('NAMA_ESELON', '', 'trim|required');
		$this->form_validation->set_rules('TUNJANGAN', '', 'trim|callback_numeric_wcomma');
		$this->form_validation->set_rules('KODE_GOL_MAX', '', 'trim');
		$this->form_validation->set_rules('KODE_GOL_MIN', '', 'trim');
		$this->form_validation->set_rules('URUT', '', 'trim');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kode dan Nama Eselon Wajib diisi.');
		}
		else{
			if ($this->input->post('KODE') <> $this->input->post('KODE_LAMA') && $this->exist_data($this->input->post('KODE')) > 0)	{
				$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, Kode Eselon telah dipakai sebelumnya.');
			}
			else {
				$tunjangan = $this->input->post('TUNJANGAN');
				if($tunjangan == '') {
					$tunjangan = null;
				}
				$data = array(
					'kode' => $this->input->post('KODE'),
					'nama_eselon' => $this->input->post('NAMA_ESELON'),
					'tunjangan' => $tunjangan,
					'kode_gol_max' => $this->input->post('KODE_GOL_MAX')	,
					'kode_gol_min' => $this->input->post('KODE_GOL_MIN')	,
					'urut' => $this->input->post('URUT')
				);


				$where = array('kode' => $this->input->post('KODE_LAMA'));
				$query = $this->eselon_model->update($where,$data);
				$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
			}
		}

		echo(json_encode($status));
	}
	public function delete($IdPrimaryKey){

		$where ="kode = '".$IdPrimaryKey."' ";
		$this->eselon_model->delete($where);

		redirect(base_url()."".$this->uri->segment(1));
	}

	public function exist_data($IdPrimaryKey) {
		$where ="kode = '".$IdPrimaryKey."' ";
		return $this->eselon_model->getCount($where);
	}

	function numeric_wcomma ($str){
    return preg_match('/^[0-9,]+$/', $str);
	}
}
