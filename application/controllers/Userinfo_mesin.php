<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Userinfo_mesin extends CI_Controller 
{
	public function __construct() {
		parent::__construct();

		$this->load->model(['mesin_model', 'userinfo_mesin_model', 'pegawai_model']);
	}

	public function index()
	{
		$like = null;
		$urlSearch = null;
		$order_by ='nama';
		$where = "";
		$select = "*";

		$this->data_mesin = $this->mesin_model->showData($where, null, $order_by);

		if($this->input->get('id_mesin') || $this->input->get('user'))
		{
			$like = array('user_name' => $_GET['user']);
			if(is_numeric($_GET['user']))
			{ $like = array('user_id' => $_GET['user']); }

			if($this->input->get('id_mesin'))
			{ $where = array('id_mesin' => $_GET['id_mesin']); }

			$urlSearch = "?id_mesin=".$_GET['id_mesin']."&user=".$_GET['user'];

			$select = "mesin_user.*, m_pegawai.nama as nama_pegawai, m_pegawai.nip as nip_pegawai, m_unit_organisasi_kerja.nama as nama_unor";
			$join = array(
				array(
					"table" => "m_pegawai",
					"on"    => "mesin_user.id_pegawai = m_pegawai.id"
				),
				array(
					"table" => "m_unit_organisasi_kerja",
					"on"    => "m_pegawai.kode_unor = m_unit_organisasi_kerja.kode"
				)
			);
			$order_by = "coalesce(user_name,'') asc";

			$config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch;
			$this->jumlahData 		= $this->userinfo_mesin_model->getCount($where,$like,null,null,null,null,null,$select,$join);
			$config['total_rows'] 	= $this->jumlahData;
			$config['per_page'] 	= 10;
			$this->showData = $this->userinfo_mesin_model->showData($where,$like,$order_by,$config['per_page'],$this->input->get('per_page'),null,null,$select,$join);

			$this->pagination->initialize($config);
		}

		$this->template_view->load_view('master/userinfo_mesin/userinfo_mesin_view');
	}

	public function map($id)
	{
		$like = null;
		$urlSearch = null;
		$order_by ='';
		$select = "*";
		$where = array('mesin_user.id' => $id);

		$select = "mesin_user.*, m_pegawai.nip as nip_pegawai";
		$join = array(
			array(
				"table" => "m_pegawai",
				"on"    => "mesin_user.id_pegawai = m_pegawai.id"
			)
		);

		$this->data = $this->userinfo_mesin_model->getData($where, $select, $join);		
		if(!$this->data)
		{
			redirect($this->uri->segment(1));
		}

		$select = "m_pegawai.*, m_instansi.nama as nama_instansi, m_jenis_jabatan.nama as nama_jabatan, m_status_pegawai.nama as nama_status";
		$join = array
		(
			array
			(
				"table" => "m_instansi",
				"on"    => "m_pegawai.kode_instansi = m_instansi.kode"
			),
			array
			(
				"table" => "m_jenis_jabatan",
				"on"    => "m_jenis_jabatan.kode = m_pegawai.kode_jenis_jabatan"
			),
			array
			(
				"table" => "m_status_pegawai",
				"on"    => "m_status_pegawai.kode = m_pegawai.kode_status_pegawai"
			)
		);
		$order_by = "coalesce(m_pegawai.nama,'') asc";


		if($this->input->get('keyword'))
		{
			$like = array('m_pegawai.nama' => $_GET['keyword']);
			if(is_numeric($_GET['keyword']))
			{ $like = array('m_pegawai.nip' => $_GET['keyword']); }
			$urlSearch = "?keyword=".$_GET['keyword'];
		}

		if($this->input->get('id_mesin'))
		{ $config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch; }
		$this->jumlahData 		= $this->pegawai_model->getCount(null,$like,null,null,null,null,null,$select,$join);
		$config['total_rows'] 	= $this->jumlahData;
		$config['per_page'] 	= 10;

		$this->data_pegawai = $this->pegawai_model->showData(null, $like, $order_by, $config['per_page'], $this->input->get('per_page'), null, null, $select, $join);

		$this->pagination->initialize($config);

		$this->template_view->load_view('master/userinfo_mesin/userinfo_mesin_edit_view');
	}

	public function map_data()
	{	
		$data = array
		(
			'id_pegawai' => $this->input->post('id_pegawai')			
		);

		$where = array
		(
			'id' => $this->input->post('id_mesin_user')
		);

		$query = $this->userinfo_mesin_model->update($where,$data);
		redirect(base_url()."".$this->uri->segment(1)."?id_mesin=".$this->input->post('id_mesin')."&user=".$this->input->post('user_id')."");
	}

	public function unmap()
	{
		$data = array
		(
			'id_pegawai' 	=> null
			
		);

		$where = array
		(
			'id' => $this->input->post('id_mesin_user')
		);

		$query = $this->userinfo_mesin_model->update($where,$data);

		redirect(base_url()."".$this->uri->segment(1)."?id_mesin=".$this->input->post('id_mesin')."&user=".$this->input->post('user_id')."");
	}
}