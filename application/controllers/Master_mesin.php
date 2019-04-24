<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_mesin extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();

		$this->load->model(['mesin_model', 'jenis_mesin_model', 'instansi_model', 'area_mesin_model']);
	}

	public function index()
	{
		$like = null;
		$urlSearch = null;
		$order_by ='nama';
		$where = "";

		if($this->input->get('field'))
		{
			switch ($this->input->get('field')) 
			{
				case 'nama':
					$like = array("m_mesin.nama" => strtoupper($_GET['keyword']));
					break;

				case 'instansi':
					$like = array("m_instansi.nama" => strtoupper($_GET['keyword']));
					break;
			}
			$urlSearch = "?field=".$_GET['field']."&keyword=".$_GET['keyword'];
		}

		$select = "m_mesin.*, m_jenis_mesin.nama as jenis_mesin, m_area_mesin.nama as area_mesin, m_instansi.nama as instansi";
		$join = array(
			array(
				"table" => "m_instansi",
				"on"    => "m_mesin.kode_instansi = m_instansi.kode"
			),
			array(
				"table" => "m_jenis_mesin",
				"on"    => "m_mesin.id_jenis = m_jenis_mesin.id"
			),
			array(
				"table" => "m_area_mesin",
				"on"    => "m_mesin.id_area_mesin = m_area_mesin.kode"
			)
		);
		$order_by = "coalesce(m_mesin.nama,'') asc";

		$config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch;

		$this->jumlahData 		= $this->mesin_model->getCount($where,$like,null,null,null,null,null,$select,$join);
		$config['total_rows'] = $this->jumlahData;
		$config['per_page'] 	= 10;

		$this->showData = $this->mesin_model->showData($where,$like,$order_by,$config['per_page'],$this->input->get('per_page'),null,null,$select,$join);
		$this->pagination->initialize($config);
		$this->template_view->load_view('master/mesin/mesin_view');
	}

	public function add()
	{
		$order_by  = 'nama';
		$this->jenis_mesin_data   	= $this->jenis_mesin_model->showData("","",$order_by);
		$this->instansi_data   		= $this->instansi_model->showData("","",$order_by);
		$this->area_mesin_data   	= $this->area_mesin_model->showData("","",$order_by);

		$this->template_view->load_view('master/mesin/mesin_add_view');
	}

	public function add_data()
	{
		$this->form_validation->set_rules('nama', '', 'trim|required');
		$this->form_validation->set_rules('id_jenis_mesin', '', 'trim|required');
		$this->form_validation->set_rules('kode_instansi', '', 'trim|required');
		$this->form_validation->set_rules('kode_area_mesin', '', 'trim|required');
		$this->form_validation->set_rules('ip_address', '', 'trim|required');
		$this->form_validation->set_rules('password', '', 'trim|required');
		$this->form_validation->set_rules('port', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	
		{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}

		else
		{
			$where ="nama = '".$this->input->post('nama')."' ";
			$this->oldData = $this->mesin_model->getData($where);

			if($this->oldData)
			{
				$status = array('status' => false , 'pesan' => 'Silahkan ganti Nama.. karena nama sudah terpakai.');
			}
			else
			{
				$where ="ip_address = '".$this->input->post('ip_address')."' ";
				$this->oldData = $this->mesin_model->getData($where);

				if($this->oldData)
				{
					$status = array('status' => false , 'pesan' => 'Silahkan ganti IP Address.. karena IP Address sudah terpakai.');
				}
				else
				{
					$aktif = "FALSE";
					if($this->input->post('aktif'))
					{
						$aktif = "TRUE";
					}

					$hapus_log = "FALSE";
					if($this->input->post('hapus_log'))
					{
						$hapus_log = "TRUE";
					}

					$this->load->library('encrypt_decrypt');

					$data = array
					(
						'id' 	=> $this->encrypt_decrypt->new_id(),
						'aktif' 	=> $aktif,
						'hapus_log' 	=> $hapus_log,
						'ip_address' 	=> $this->input->post('ip_address'),
						'keterangan' 	=> null,
						'mac_address' 	=> null,
						'nama' 	=> $this->input->post('nama'),
						'password' 	=> $this->input->post('password'),
						'port' 	=> $this->input->post('port'),
						'user_name' 	=> null,
						'id_jenis' 	=> $this->input->post('id_jenis_mesin'),
						'id_area_mesin' 	=> $this->input->post('kode_area_mesin'),
						'status' 	=> 'Connect',
						'waktu_status' 	=> date('Y-M-d h:i:s.u'),
						'status_ping' 	=> 'Connect',
						'waktu_status_ping' 	=> date('Y-M-d h:i:s.u'),
						'kode_instansi' 	=> $this->input->post('kode_instansi'),
						'kode_unor' 	=> '6.31.00.00.00'
					);

					$query = $this->mesin_model->insert($data);
					$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
				}
			}
		}

		echo(json_encode($status));
	}

	public function edit($id)
	{
		$order_by  = 'nama';
		$where ="id = '".$id."' ";
		$this->data = $this->mesin_model->getData($where);
		
		if(!$this->data)
		{
			redirect($this->uri->segment(1));
		}

		$this->jenis_mesin_data   = $this->jenis_mesin_model->showData("","",$order_by);
		$this->instansi_data   = $this->instansi_model->showData("","",$order_by);
		$this->area_mesin_data   = $this->area_mesin_model->showData("","",$order_by);
		$this->load->library('encrypt_decrypt');
		$this->password = $this->encrypt_decrypt->dec_enc('encrypt',$this->data->password);
		$this->template_view->load_view('master/mesin/mesin_edit_view');
	}

	public function edit_data()
	{
		$this->form_validation->set_rules('nama', '', 'trim|required');
		$this->form_validation->set_rules('id_jenis_mesin', '', 'trim|required');
		$this->form_validation->set_rules('kode_instansi', '', 'trim|required');
		$this->form_validation->set_rules('kode_area_mesin', '', 'trim|required');
		$this->form_validation->set_rules('ip_address', '', 'trim|required');
		$this->form_validation->set_rules('password', '', 'trim|required');
		$this->form_validation->set_rules('port', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	
		{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}

		else
		{
			$aktif = "FALSE";
			if($this->input->post('aktif'))
			{
				$aktif = "TRUE";
			}

			$hapus_log = "FALSE";
			if($this->input->post('hapus_log'))
			{
				$hapus_log = "TRUE";
			}

			$data = array
			(
				'aktif' 	=> $aktif,
				'hapus_log' 	=> $hapus_log,
				'ip_address' 	=> $this->input->post('ip_address'),
				'keterangan' 	=> null,
				'mac_address' 	=> null,
				'nama' 	=> $this->input->post('nama'),
				'password' 	=> $this->input->post('password'),
				'port' 	=> $this->input->post('port'),
				'user_name' 	=> null,
				'id_jenis' 	=> $this->input->post('id_jenis_mesin'),
				'id_area_mesin' 	=> $this->input->post('kode_area_mesin'),
				'status' 	=> 'Connect',
				'waktu_status' 	=> date('Y-M-d h:i:s.u'),
				'status_ping' 	=> 'Connect',
				'waktu_status_ping' 	=> date('Y-M-d h:i:s.u'),
				'kode_instansi' 	=> $this->input->post('kode_instansi'),
				'kode_unor' 	=> '6.31.00.00.00'
			);

			$where = array
			(
				'id' => $this->input->post('id')
			);

			$query = $this->mesin_model->update($where,$data);

			//echo $this->db->last_query();
			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}

		echo(json_encode($status));
	}

	public function delete($id)
	{
		$where ="id = '".$id."' ";
		$this->mesin_model->delete($where);

		redirect(base_url()."".$this->uri->segment(1));
	}
}