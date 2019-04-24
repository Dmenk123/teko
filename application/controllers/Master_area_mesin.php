<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_area_mesin extends CI_Controller 
{
	public function __construct() {
		parent::__construct();

		$this->load->model('area_mesin_model');
	}

	public function index()
	{
		$like = null;
		$urlSearch = null;
		$order_by ='nama';
		$where = "";

		if($this->input->get('field'))
		{
			$like = array($_GET['field'] => strtoupper($_GET['keyword']));
			$urlSearch = "?field=".$_GET['field']."&keyword=".$_GET['keyword'];
		}

		$config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch;
		$this->jumlahData 		= $this->area_mesin_model->getCount($where,$like);
		$config['total_rows'] 	= $this->jumlahData;
		$config['per_page'] 	= 10;
		$this->showData = $this->area_mesin_model->showData($where,$like,$order_by,$config['per_page'],$this->input->get('per_page'));
		//echo $this->db->last_query();
		$this->pagination->initialize($config);
		$this->template_view->load_view('master/area_mesin/area_mesin_view');
	}

	public function add()
	{
		$this->template_view->load_view('master/area_mesin/area_mesin_add_view');
	}

	public function add_data()
	{
		$this->form_validation->set_rules('NAMA', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	
		{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}

		else
		{

			$where ="kode = '".$this->input->post('KODE')."' ";
			$this->oldData = $this->area_mesin_model->getData($where);

			if($this->oldData)
			{
				$status = array('status' => false , 'pesan' => 'Silahkan ganti Kode.. karena kode sudah terpakai.');
			}
			else
			{
				$data = array
				(
					'kode' 	=> $this->input->post('KODE'),
					'nama' 	=> $this->input->post('NAMA')
				);

				$query = $this->area_mesin_model->insert($data);
				$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
			}
		}

		echo(json_encode($status));
	}

	public function edit($kode)
	{

		$where ="kode = '".$kode."' ";
		$this->oldData = $this->area_mesin_model->getData($where);
		
		if(!$this->oldData)
		{
			redirect($this->uri->segment(1));
		}

		$this->template_view->load_view('master/area_mesin/area_mesin_edit_view');
	}

	public function edit_data()
	{
		$this->form_validation->set_rules('NAMA', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	
		{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}

		else
		{
			$data = array
			(
				'nama' 	=> $this->input->post('NAMA')
			);


			$where = array
			(
				'kode' => $this->input->post('KODE')
			);

			$query = $this->area_mesin_model->update($where,$data);

			//echo $this->db->last_query();
			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}

		echo(json_encode($status));
	}

	public function delete($kode)
	{
		$where ="kode = '".$kode."' ";
		$this->area_mesin_model->delete($where);

		redirect(base_url()."".$this->uri->segment(1));
	}
}