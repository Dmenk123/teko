<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_pegawai extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model(['pegawai_model', 'instansi_model', 'unor_kerja_model', 'eselon_model', 'jenis_jabatan_model', 'status_pegawai_model', 'jenis_kelamin_model', 'golongan_model', 'role_jam_kerja_model', 'pegawai_instansi_histori_model', 'pegawai_role_jam_kerja_histori_model', 'pegawai_rumpun_jabatan_histori_model', 'pegawai_unit_kerja_histori_model','pegawai_jabatan_histori_model','pegawai_golongan_histori_model','pegawai_eselon_histori_model','rumpun_jabatan_model']);
	}

	public function index(){
		$where 		 = null;
		$like 		 = null;
		$or_like 	 = null;
		$order_by  = 'kode';
		$urlSearch = null;
		$this->instansi_post = null;
		$this->keyword_post = null;

		$this->instansiData = $this->instansi_model->showData("","",$order_by);

		$select = "m_pegawai.*, m_instansi.nama as nama_instansi, m_jenis_jabatan.nama as nama_jenis_jabatan, m_status_pegawai.nama as nama_status_pegawai";
		$join = array(
			array(
				"table" => "m_instansi",
				"on"    => "m_pegawai.kode_instansi = m_instansi.kode"
			),
			array(
				"table" => "m_eselon",
				"on"    => "m_pegawai.kode_eselon = m_eselon.kode"
			),
			array(
				"table" => "m_jenis_jabatan",
				"on"    => "m_pegawai.kode_jenis_jabatan = m_jenis_jabatan.kode"
			),
			array(
				"table" => "m_status_pegawai",
				"on"    => "m_pegawai.kode_status_pegawai = m_status_pegawai.kode"
			),
			array(
				"table" => "m_golongan",
				"on"    => "m_pegawai.kode_golongan_akhir = m_golongan.kode"
			)
		);
		$order_by = "coalesce(m_jenis_jabatan.urut,1000), coalesce(m_eselon.kode,'z'), coalesce(m_golongan.kode,'') desc, coalesce(m_pegawai.nama,'') asc";

		$config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch;
		$this->load->library('pagination');

		$param_pegawai = $this->session->userdata('param_pegawai');

		if ($this->input->server('REQUEST_METHOD') == 'POST'){
			if($this->input->post('instansi')){
				if($this->input->post('instansi') <> 'all') {
					$where = array('kode_instansi' => $this->input->post('instansi'));
					if($this->input->post('keyword')) {
						$like = array('LOWER(m_pegawai.nama)' => strtolower($this->input->post('keyword')));
						$or_like = array(
							'LOWER(m_pegawai.nip)' => strtolower($this->input->post('keyword')),
							'LOWER(m_jenis_jabatan.nama)' => strtolower($this->input->post('keyword')),
							'LOWER(m_instansi.nama)' => strtolower($this->input->post('keyword')),
							'LOWER(m_status_pegawai.nama)' => strtolower($this->input->post('keyword'))
						);
					}
					$sess = array (
						'instansi_pegawai' => $this->input->post('instansi'),
						'keyword_pegawai' => $this->input->post('keyword')
					);
					$this->session->set_userdata('param_pegawai', $sess);
					$this->instansi_post = $this->input->post('instansi');
					$this->keyword_post = $this->input->post('keyword');
				}
				else {
					if($this->input->post('keyword')) {
						$like = array('LOWER(m_pegawai.nama)' => strtolower($this->input->post('keyword')));
						$or_like = array(
							'LOWER(m_pegawai.nip)' => strtolower($this->input->post('keyword')),
							'LOWER(m_jenis_jabatan.nama)' => strtolower($this->input->post('keyword')),
							'LOWER(m_instansi.nama)' => strtolower($this->input->post('keyword')),
							'LOWER(m_status_pegawai.nama)' => strtolower($this->input->post('keyword'))
						);
						$sess = array (
							'instansi_pegawai' => $this->input->post('instansi'),
							'keyword_pegawai' => $this->input->post('keyword')
						);
						$this->session->set_userdata('param_pegawai', $sess);
						$this->instansi_post = $this->input->post('instansi');
						$this->keyword_post = $this->input->post('keyword');
					}
					else {
						$this->session->unset_userdata('param_pegawai');
					}
					$this->instansi_post = 'all';
				}

				$this->jumlahData 		= $this->pegawai_model->getCount($where,$like,null,null,null,null,$or_like,$select,$join);
				$config['total_rows'] = $this->jumlahData;
				$config['per_page'] 	= 10;

				$this->showData = $this->pegawai_model->showData($where,$like,$order_by,$config['per_page'],$this->input->get('per_page'),null,$or_like,$select,$join);

				$this->pagination->initialize($config);
			}
			else if($this->input->post('keyword')) {
				$like = array('LOWER(m_pegawai.nama)' => strtolower($this->input->post('keyword')));
				$or_like = array('LOWER(m_pegawai.nip)' => strtolower($this->input->post('keyword')));
				$or_like = array('LOWER(m_jenis_jabatan.nama)' => strtolower($this->input->post('keyword')));
				$or_like = array('LOWER(m_instansi.nama)' => strtolower($this->input->post('keyword')));
				$or_like = array('LOWER(m_status_pegawai.nama)' => strtolower($this->input->post('keyword')));
				$sess = array (
					'instansi_pegawai' => $this->input->post('instansi'),
					'keyword_pegawai' => $this->input->post('keyword')
				);
				$this->session->set_userdata('param_pegawai', $sess);
				$this->instansi_post = $this->input->post('instansi');
				$this->keyword_post = $this->input->post('keyword');

				$this->jumlahData 		= $this->pegawai_model->getCount($where,$like,null,null,null,null,$or_like,$select,$join);
				$config['total_rows'] = $this->jumlahData;
				$config['per_page'] 	= 10;

				$this->showData = $this->pegawai_model->showData($where,$like,$order_by,$config['per_page'],$this->input->get('per_page'),null,$or_like,$select,$join);
				$this->pagination->initialize($config);
			}
			else if($this->input->post('instansi') == '' && $this->input->post('keyword') == '') {
				$this->session->unset_userdata('param_pegawai');
				$this->jumlahData 		= $this->pegawai_model->getCount("",$like,null,null,null,null,$or_like,null,$join);
				$config['total_rows'] = $this->jumlahData;
				$config['per_page'] 	= 10;

				$this->showData = $this->pegawai_model->showData("",$like,$order_by,$config['per_page'],$this->input->get('per_page'),null,$or_like,$select,$join);
				$this->pagination->initialize($config);
			}
		}
		else if($param_pegawai['instansi_pegawai']) {
			if($param_pegawai['instansi_pegawai'] <> 'all') {
				$where = array('kode_instansi' => $param_pegawai['instansi_pegawai']);
			}
			if($param_pegawai['keyword_pegawai']) {
				$like = array('LOWER(m_pegawai.nama)' => strtolower($param_pegawai['keyword_pegawai']));
				$or_like = array(
					'LOWER(m_pegawai.nip)' => strtolower($param_pegawai['keyword_pegawai']),
					'LOWER(m_jenis_jabatan.nama)' => strtolower($param_pegawai['keyword_pegawai']),
					'LOWER(m_instansi.nama)' => strtolower($param_pegawai['keyword_pegawai']),
					'LOWER(m_status_pegawai.nama)' => strtolower($param_pegawai['keyword_pegawai'])
				);
			}

			$this->instansi_post = $param_pegawai['instansi_pegawai'];
			$this->keyword_post = $param_pegawai['keyword_pegawai'];

			$this->jumlahData 		= $this->pegawai_model->getCount($where,$like,null,null,null,null,$or_like,$select,$join);
			$config['total_rows'] = $this->jumlahData;
			$config['per_page'] 	= 10;

			$this->showData = $this->pegawai_model->showData($where,$like,$order_by,$config['per_page'],$this->input->get('per_page'),null,$or_like,$select,$join);
			$this->pagination->initialize($config);
		}
		else if($param_pegawai['keyword_pegawai']) {
			$like = array('LOWER(m_pegawai.nama)' => strtolower($param_pegawai['keyword_pegawai']));
			$or_like = array(
				'LOWER(m_pegawai.nip)' => strtolower($param_pegawai['keyword_pegawai']),
				'LOWER(m_jenis_jabatan.nama)' => strtolower($param_pegawai['keyword_pegawai']),
				'LOWER(m_instansi.nama)' => strtolower($param_pegawai['keyword_pegawai']),
				'LOWER(m_status_pegawai.nama)' => strtolower($param_pegawai['keyword_pegawai'])
			);

			$this->instansi_post = $param_pegawai['instansi_pegawai'];
			$this->keyword_post = $param_pegawai['keyword_pegawai'];

			$this->jumlahData 		= $this->pegawai_model->getCount($where,$like,null,null,null,null,$or_like,$select,$join);
			$config['total_rows'] = $this->jumlahData;
			$config['per_page'] 	= 10;

			$this->showData = $this->pegawai_model->showData($where,$like,$order_by,$config['per_page'],$this->input->get('per_page'),null,$or_like,$select,$join);
			$this->pagination->initialize($config);
		}
		else {
			$this->jumlahData 		= $this->pegawai_model->getCount("",$like,null,null,null,null,$or_like,null,$join);
			$config['total_rows'] = $this->jumlahData;
			$config['per_page'] 	= 10;

			$this->showData = $this->pegawai_model->showData("",$like,$order_by,$config['per_page'],$this->input->get('per_page'),null,$or_like,$select,$join);
			$this->pagination->initialize($config);
		}

		$this->template_view->load_view('master/pegawai/pegawai_view');
	}
	public function add(){
		$order_by 	              = 'kode';
		$this->unorKerjaData      = $this->unor_kerja_model->showData("","",$order_by);
		$this->jenisKelaminData   = $this->jenis_kelamin_model->showData("","",$order_by);
		$this->statusPegawaiData  = $this->status_pegawai_model->showData("","",$order_by);

		$order_by               	= 'kode, nama';
		$this->instansiData       = $this->instansi_model->showData("","",$order_by);

		$order_by               	= 'kode, nama_eselon';
		$this->eselonData         = $this->eselon_model->showData("","",$order_by);

		$order_by               	= 'kode_pangkat, kode_huruf';
		$this->golonganData       = $this->golongan_model->showData("","",$order_by);

		$order_by                	= 'nama';
		$this->jenisJabatanData   = $this->jenis_jabatan_model->showData("","",$order_by);

		$this->roleJamKerjaData   = $this->role_jam_kerja_model->showData("","","");

		$this->template_view->load_view('master/pegawai/pegawai_add_view');
	}

	public function add_data(){
		$this->form_validation->set_rules('NIP', '', 'trim|required');
		$this->form_validation->set_rules('NAMA', '', 'trim|required');
		$this->form_validation->set_rules('TEMPAT_LAHIR', '', 'trim');
		$this->form_validation->set_rules('KODE_JENIS_KELAMIN', '', 'trim|required');
		$this->form_validation->set_rules('KODE_GOLONGAN_AKHIR', '', 'trim|required');
		$this->form_validation->set_rules('KODE_JENIS_JABATAN', '', 'trim|required');
		$this->form_validation->set_rules('KODE_STATUS_PEGAWAI', '', 'trim|required');
		$this->form_validation->set_rules('NO_REGISTRASI', '', 'trim');
		$this->form_validation->set_rules('GELAR_DEPAN', '', 'trim');
		$this->form_validation->set_rules('GELAR_BELAKANG', '', 'trim');
		$this->form_validation->set_rules('TGL_LAHIR', '', 'trim');
		$this->form_validation->set_rules('NO_HP', '', 'trim');
		$this->form_validation->set_rules('KODE_ESELON', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{
			$user = $this->session->userdata();
			$roster = 'NULL';
			if($this->input->post('ROSTER') <> null) {
				$roster = 'TRUE';
			}
			$aktif = 'NULL';
			if($this->input->post('AKTIF') <> null) {
				$aktif = 'TRUE';
			}

			$time = strtotime($this->input->post('TGL_LAHIR'));
			$tgl_lahir = date('Y-m-d',$time);

			$data = array(
				'nip' => $this->input->post('NIP'),
				'nama' => $this->input->post('NAMA'),
				'tempat_lahir' => $this->input->post('TEMPAT_LAHIR'),
				'kode_jenis_kelamin' => $this->input->post('KODE_JENIS_KELAMIN'),
				'kode_golongan_akhir' => $this->input->post('KODE_GOLONGAN_AKHIR'),
				'kode_jenis_jabatan' => $this->input->post('KODE_JENIS_JABATAN'),
				'kode_status_pegawai' => $this->input->post('KODE_STATUS_PEGAWAI'),
				'no_registrasi' => $this->input->post('NO_REGISTRASI'),
				'gelar_depan' => $this->input->post('GELAR_DEPAN'),
				'gelar_belakang' => $this->input->post('GELAR_BELAKANG'),
				'tgl_lahir' => $tgl_lahir,
				'no_hp' => $this->input->post('NO_HP'),
				'kode_eselon' => $this->input->post('KODE_ESELON'),
				'roster' => $roster,
				'aktif' => $aktif,
				'userupd' => $user['username']
			);

			$q = "INSERT INTO m_pegawai (id, nip, nama, tempat_lahir, kode_jenis_kelamin, kode_golongan_akhir, kode_jenis_jabatan, kode_status_pegawai, no_registrasi, gelar_depan, gelar_belakang, tgl_lahir, no_hp, kode_eselon, roster, aktif, userupd) VALUES (uuid_generate_v1(), '".$data['nip']."', '".$data['nama']."', '".$data['tempat_lahir']."', '".$data['kode_jenis_kelamin']."', '".$data['kode_golongan_akhir']."', '".$data['kode_jenis_jabatan']."', '".$data['kode_status_pegawai']."', '".$data['no_registrasi']."', '".$data['gelar_depan']."', '".$data['gelar_belakang']."', '".$data['tgl_lahir']."', '".$data['no_hp']."', '".$data['kode_eselon']."', ".$data['roster'].", ".$data['aktif'].", '".$data['userupd']."') RETURNING id";

			$res        = $this->pegawai_model->query($q);
			$id_pegawai = $res['id'];

			$id_role_jam_kerja = null;
			if($this->input->post('ROLE_TGL_MULAI') <> null) {
				$tgl_mulai = $this->input->post('ROLE_TGL_MULAI');
				$role_jam_kerja = $this->input->post('ROLE_ID_ROLE_JAM_KERJA');

				$urutan = 0;
				$tgl = '';

				for($i=0;$i<count($tgl_mulai);$i++){
					$time = strtotime($tgl_mulai[$i]);
					$t_mulai = date('Y-m-d',$time);

					if($i==0) {
						$urutan = $i;
						$tgl = $t_mulai;
					}
					else {
						if ($t_mulai > $tgl) {
							$urutan = $i;
							$tgl = $t_mulai;
						}
					}

					$data = array(
						'tgl_mulai' => $t_mulai,
						'user_upd' => $user['username'],
						'id_pegawai' => $id_pegawai,
						'id_role_jam_kerja' => $role_jam_kerja[$i]
					);
					$query = $this->pegawai_role_jam_kerja_histori_model->insert($data);
				}
				$id_role_jam_kerja = $role_jam_kerja[$urutan];
			}

			$kode_instansi = null;
			$kode_unor = null;
			if($this->input->post('UNOR_TGL_MULAI') <> null) {
				$tgl_mulai = $this->input->post('UNOR_TGL_MULAI');
				$instansi = $this->input->post('UNOR_KODE_INSTANSI');
				$unor = $this->input->post('UNOR_KODE_UNOR');

				$urutan = 0;
				$tgl = '';

				for($i=0;$i<count($tgl_mulai);$i++){
					$time = strtotime($tgl_mulai[$i]);
					$t_mulai = date('Y-m-d',$time);

					if($i==0) {
						$urutan = $i;
						$tgl = $t_mulai;
					}
					else {
						if ($t_mulai > $tgl) {
							$urutan = $i;
							$tgl = $t_mulai;
						}
					}

					$data = array(
						'tgl_mulai' => $t_mulai,
						'user_upd' => $user['username'],
						'id_pegawai' => $id_pegawai,
						'kode_instansi' => $instansi[$i]
					);
					$query = $this->pegawai_instansi_histori_model->insert($data);

					$data = array(
						'tgl_mulai' => $t_mulai,
						'user_upd' => $user['username'],
						'id_pegawai' => $id_pegawai,
						'kode_unor' => $unor[$i]
					);
					$query = $this->pegawai_unit_kerja_histori_model->insert($data);
				}
				$kode_instansi = $instansi[$urutan];
				$kode_unor = $unor[$urutan];
			}

			$data = array(
				'id_role_jam_kerja' => $id_role_jam_kerja,
				'kode_instansi' => $kode_instansi,
				'kode_unor' => $kode_unor
			);

			$where = array('id' => $id_pegawai);
			$query = $this->pegawai_model->update($where,$data);

			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));

			$sess = array (
				'instansi_pegawai' => $instansi[$urutan],
				'keyword_pegawai' => $this->input->post('NAMA')
			);
			$this->session->set_userdata('param_pegawai', $sess);
		}

		echo(json_encode($status));
	}
	public function edit($IdPrimaryKey){
		$order_by      = null;
		$where         = "id = '".$IdPrimaryKey."' ";
		$this->oldData = $this->pegawai_model->getData($where);


		$where                          = "id_pegawai = '".$IdPrimaryKey."' ";
		$order_by                       = 'tgl_mulai desc';
		$select = "m_pegawai_role_jam_kerja_histori.*, m_role_jam_kerja.nama as nama_role_jam_kerja";
		$join = array(
			array(
				"table" => "m_role_jam_kerja",
				"on"    => "m_pegawai_role_jam_kerja_histori.id_role_jam_kerja = m_role_jam_kerja.id"
			)
		);
		$this->jamKerjaHistoriData      = $this->pegawai_role_jam_kerja_histori_model->showData($where,null,$order_by,null,null,null,null,$select,$join);

		$select = "m_pegawai_instansi_histori.*, m_instansi.nama as nama_instansi";
		$join = array(
			array(
				"table" => "m_instansi",
				"on"    => "m_pegawai_instansi_histori.kode_instansi = m_instansi.kode"
			)
		);
		$this->instansiKerjaHistoriData = $this->pegawai_instansi_histori_model->showData($where,null,$order_by,null,null,null,null,$select,$join);

		$select = "m_pegawai_instansi_histori.*, m_instansi.nama as nama_instansi";
		$join = array(
			array(
				"table" => "m_instansi",
				"on"    => "m_pegawai_instansi_histori.kode_instansi = m_instansi.kode"
			)
		);
		$this->instansiKerjaHistoriData = $this->pegawai_instansi_histori_model->showData($where,null,$order_by,null,null,null,null,$select,$join);


		$select = "m_pegawai_jabatan_histori.*, m_jenis_jabatan.nama as nama_jenis_jabatan";
		$join = array(
			array(
				"table" => "m_jenis_jabatan",
				"on"    => "m_pegawai_jabatan_histori.kode_jabatan = m_jenis_jabatan.kode"
			)
		);
		$this->jabatanHistoriData = $this->pegawai_jabatan_histori_model->showData($where,null,$order_by,null,null,null,null,$select,$join);

		$select = "m_pegawai_golongan_histori.*, m_golongan.nama as nama_golongan";
		$join = array(
			array(
				"table" => "m_golongan",
				"on"    => "m_golongan.kode = m_pegawai_golongan_histori.kode_golongan"
			)
		);
		$this->golonganHistoriData = $this->pegawai_golongan_histori_model->showData($where,null,$order_by,null,null,null,null,$select,$join);

		$select = "m_pegawai_eselon_histori.*, m_eselon.nama_eselon";
		$join = array(
			array(
				"table" => "m_eselon",
				"on"    => "m_eselon.kode = m_pegawai_eselon_histori.kode_eselon"
			)
		);
		$this->eselonHistoriData = $this->pegawai_eselon_histori_model->showData($where,null,$order_by,null,null,null,null,$select,$join);

		$select = "m_pegawai_rumpun_jabatan_histori.*, m_rumpun_jabatan.nama as nama_rumpun_jabatan";
		$join = array(
			array(
				"table" => "m_rumpun_jabatan",
				"on"    => "m_rumpun_jabatan.id = m_pegawai_rumpun_jabatan_histori.id_rumpun_jabatan"
			)
		);
		$this->rumpunJabatanHistoriData = $this->pegawai_rumpun_jabatan_histori_model->showData($where,null,$order_by,null,null,null,null,$select,$join);


		/**$select = "m_pegawai_unit_kerja_histori.*, m_unit_organisasi_kerja.nama as nama_unor";
		$join = array(
			array(
				"table" => "m_unit_organisasi_kerja",
				"on"    => "m_pegawai_unit_kerja_histori.kode_unor = m_unit_organisasi_kerja.kode"
			)
		);
		$this->unitKerjaHistoriData     = $this->pegawai_unit_kerja_histori_model->showData($where,null,$order_by,null,null,null,null,$select,$join);

		select
				m_pegawai_unit_kerja_histori.id,
				m_pegawai_unit_kerja_histori.tgl_mulai,
				m_instansi.nama as nama_instansi ,
				m_unit_organisasi_kerja.nama as nama_unor
			from
				m_pegawai_unit_kerja_histori,m_instansi,m_unit_organisasi_kerja
			where
				m_pegawai_unit_kerja_histori.id_pegawai =  '".$IdPrimaryKey."' and
				m_instansi.kode like CONCAT(SUBSTRING(m_pegawai_unit_kerja_histori.kode_unor, 1, 5), '%') and
				m_unit_organisasi_kerja.kode = m_pegawai_unit_kerja_histori.kode_unor
			order by
				m_pegawai_unit_kerja_histori.tgl_mulai desc
		**/


		$selectUnitKerja = $this->db->query("
			select
				m_pegawai_unit_kerja_histori.id,
				m_pegawai_unit_kerja_histori.tgl_mulai,
				m_pegawai_unit_kerja_histori.id_pegawai,
				m_instansi.nama as nama_instansi ,

				m_unit_organisasi_kerja.nama as nama_unor
			from
				m_pegawai_unit_kerja_histori,m_instansi,m_unit_organisasi_kerja
			where
				m_pegawai_unit_kerja_histori.id_pegawai =  '".$IdPrimaryKey."' and

				m_instansi.kode = (select kode_instansi from m_unit_organisasi_kerja oop where oop.kode = m_pegawai_unit_kerja_histori.kode_unor )   and

				m_unit_organisasi_kerja.kode = m_pegawai_unit_kerja_histori.kode_unor
			order by
				m_pegawai_unit_kerja_histori.tgl_mulai desc
		");
		$this->unitKerjaHistoriData = $selectUnitKerja->result();

		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}
		$order_by 	              = 'kode';
		$this->unorKerjaData      = $this->unor_kerja_model->showData("","",$order_by);
		$this->jenisKelaminData   = $this->jenis_kelamin_model->showData("","",$order_by);
		$this->statusPegawaiData  = $this->status_pegawai_model->showData("","",$order_by);

		$order_by               	= 'kode, nama';
		$this->instansiData       = $this->instansi_model->showData("","",$order_by);

		$order_by               	= 'kode, nama';
		$this->jabatanData       = $this->jenis_jabatan_model->showData("","",$order_by);

		$order_by               	= 'kode, nama_eselon';
		$this->eselonData         = $this->eselon_model->showData("","",$order_by);

		$order_by               	= 'kode_pangkat, kode_huruf';
		$this->golonganData       = $this->golongan_model->showData("","",$order_by);

		$order_by                	= 'nama';
		$this->jenisJabatanData   = $this->jenis_jabatan_model->showData("","",$order_by);

		$order_by                	= 'nama_eselon';
		$this->eselonData   = $this->eselon_model->showData("","",$order_by);

	//	var_dump(	$this->eselonData);

		$order_by                	= 'nama';
		$this->rumpunJabatanData   = $this->rumpun_jabatan_model->showData("","",$order_by);


		$order_by                	= 'nama';
		$this->roleJamKerjaData   = $this->role_jam_kerja_model->showData("","",$order_by );

		$this->template_view->load_view('master/pegawai/pegawai_edit_view');
	}
	public function edit_data(){
		//echo "asdasd";
		$this->form_validation->set_rules('ID', '', 'trim|required');
		$this->form_validation->set_rules('NIP', '', 'trim|required');
		$this->form_validation->set_rules('NAMA', '', 'trim|required');
		$this->form_validation->set_rules('TEMPAT_LAHIR', '', 'trim');
		$this->form_validation->set_rules('KODE_JENIS_KELAMIN', '', 'trim|required');
		$this->form_validation->set_rules('KODE_STATUS_PEGAWAI', '', 'trim|required');
		$this->form_validation->set_rules('NO_REGISTRASI', '', 'trim');
		$this->form_validation->set_rules('GELAR_DEPAN', '', 'trim');
		$this->form_validation->set_rules('GELAR_BELAKANG', '', 'trim');
		$this->form_validation->set_rules('TGL_LAHIR', '', 'trim');
		$this->form_validation->set_rules('NO_HP', '', 'trim');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{
			$user = $this->session->userdata();

			$roster = NULL;
			if($this->input->post('ROSTER') <> null) {
				$roster = 'TRUE';
			}
			$aktif = NULL;
			if($this->input->post('AKTIF') <> null) {
				$aktif = 'TRUE';
			}
			$time = strtotime($this->input->post('TGL_LAHIR'));
			$tgl_lahir = date('Y-m-d',$time);

			$data = array(
				'nip' => $this->input->post('NIP'),
				'nama' => $this->input->post('NAMA'),
				'tempat_lahir' => $this->input->post('TEMPAT_LAHIR'),
				'kode_jenis_kelamin' => $this->input->post('KODE_JENIS_KELAMIN'),
				'kode_status_pegawai' => $this->input->post('KODE_STATUS_PEGAWAI'),
				'no_registrasi' => $this->input->post('NO_REGISTRASI'),
				'gelar_depan' => $this->input->post('GELAR_DEPAN'),
				'gelar_belakang' => $this->input->post('GELAR_BELAKANG'),
				'tgl_lahir' => $tgl_lahir,
				'no_hp' => $this->input->post('NO_HP'),
				'roster' => $roster,
				'aktif' => $aktif,
				'userupd' => $user['username']
			);

			$id_pegawai = $this->input->post('ID');

			$where = array(
				'id' => $id_pegawai
			);
			$query = $this->pegawai_model->update($where,$data);


			/**if($this->input->post('ROLE_TGL_MULAI') <> null) {
				$tgl_mulai = $this->input->post('ROLE_TGL_MULAI');
				$role_jam_kerja = $this->input->post('ROLE_ID_ROLE_JAM_KERJA');

				for($i=0;$i<count($tgl_mulai);$i++){
					$time = strtotime($tgl_mulai[$i]);
					$t_mulai = date('Y-m-d',$time);

					$data = array(
						'tgl_mulai' => $t_mulai,
						'user_upd' => $user['username'],
						'id_pegawai' => $id_pegawai,
						'id_role_jam_kerja' => $role_jam_kerja[$i]
					);
					$query = $this->pegawai_role_jam_kerja_histori_model->insert($data);
				}
			}

			if($this->input->post('UNOR_TGL_MULAI') <> null) {
				$tgl_mulai = $this->input->post('UNOR_TGL_MULAI');
				$instansi = $this->input->post('UNOR_KODE_INSTANSI');
				$unor = $this->input->post('UNOR_KODE_UNOR');

				for($i=0;$i<count($tgl_mulai);$i++){
					$time = strtotime($tgl_mulai[$i]);
					$t_mulai = date('Y-m-d',$time);

					$data = array(
						'tgl_mulai' => $t_mulai,
						'user_upd' => $user['username'],
						'id_pegawai' => $id_pegawai,
						'kode_instansi' => $instansi[$i]
					);
					$query = $this->pegawai_instansi_histori_model->insert($data);

					$data = array(
						'tgl_mulai' => $t_mulai,
						'user_upd' => $user['username'],
						'id_pegawai' => $id_pegawai,
						'kode_unor' => $unor[$i]
					);
					$query = $this->pegawai_unit_kerja_histori_model->insert($data);
				}
			}

			$where = "id_pegawai = '".$id_pegawai."' ";
			$order_by = 'tgl_mulai desc';

			$select = "m_pegawai_role_jam_kerja_histori.*, m_role_jam_kerja.nama as nama_role_jam_kerja";
			$join = array(
				array(
					"table" => "m_role_jam_kerja",
					"on"    => "m_pegawai_role_jam_kerja_histori.id_role_jam_kerja = m_role_jam_kerja.id"
				)
			);
			$jamKerjaHistoriData = $this->pegawai_role_jam_kerja_histori_model->showData($where,null,$order_by,null,null,null,null,$select,$join);

			$id_role_jam_kerja = null;
			if($jamKerjaHistoriData <> null) {
					$id_role_jam_kerja = $jamKerjaHistoriData[0]->id_role_jam_kerja;
			}

			$select = "m_pegawai_instansi_histori.*, m_instansi.nama as nama_instansi";
			$join = array(
				array(
					"table" => "m_instansi",
					"on"    => "m_pegawai_instansi_histori.kode_instansi = m_instansi.kode"
				)
			);
			$instansiHistoriData = $this->pegawai_instansi_histori_model->showData($where,null,$order_by,null,null,null,null,$select,$join);

			$kode_instansi = null;
			if($instansiHistoriData <> null) {
					$kode_instansi = $instansiHistoriData[0]->kode_instansi;
			}

			$select = "m_pegawai_unit_kerja_histori.*, m_unit_organisasi_kerja.nama as nama_unor";
			$join = array(
				array(
					"table" => "m_unit_organisasi_kerja",
					"on"    => "m_pegawai_unit_kerja_histori.kode_unor = m_unit_organisasi_kerja.kode"
				)
			);
			$unorHistoriData = $this->pegawai_unit_kerja_histori_model->showData($where,null,$order_by,null,null,null,null,$select,$join);

			$kode_unor = null;
			if($unorHistoriData <> null) {
					$kode_unor = $unorHistoriData[0]->kode_unor;
			}

			$data = array(
				'id_role_jam_kerja' => $id_role_jam_kerja,
				'kode_instansi' => $kode_instansi,
				'kode_unor' => $kode_unor
			);

			$where = array('id' => $id_pegawai);
			$query = $this->pegawai_model->update($where,$data);

			**/
		}
		$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));

		echo(json_encode($status));
	}

	public function delete($IdPrimaryKey){

		$where ="id_pegawai = '".$IdPrimaryKey."' ";
		$this->pegawai_role_jam_kerja_histori_model->delete($where);
		$this->pegawai_instansi_histori_model->delete($where);
		$this->pegawai_unit_kerja_histori_model->delete($where);

		$where ="id = '".$IdPrimaryKey."' ";
		$this->pegawai_model->delete($where);

		redirect(base_url()."".$this->uri->segment(1));
	}

	public function delete_role(){
		$this->form_validation->set_rules('id','Role','required');
		if($this->form_validation->run() == true) {
			$IdPrimaryKey = $this->input->post('id');
			$where ="id = '".$IdPrimaryKey."' ";
			$role = $this->pegawai_role_jam_kerja_histori_model->showData($where);
			$hapus = $this->pegawai_role_jam_kerja_histori_model->delete($where);

			if ($hapus) {
				$where = "id_pegawai = '".$role[0]->id_pegawai."' ";
				$order_by = 'tgl_mulai desc';
				$select = "m_pegawai_role_jam_kerja_histori.*, m_role_jam_kerja.nama as nama_role_jam_kerja";
				$join = array(
					array(
						"table" => "m_role_jam_kerja",
						"on"    => "m_pegawai_role_jam_kerja_histori.id_role_jam_kerja = m_role_jam_kerja.id"
					)
				);
				$jamKerjaHistoriData = $this->pegawai_role_jam_kerja_histori_model->showData($where,null,$order_by,null,null,null,null,$select,$join);

				$id_role_jam_kerja = null;
				if($jamKerjaHistoriData <> null) {
						$id_role_jam_kerja = $jamKerjaHistoriData[0]->id_role_jam_kerja;
				}

				$data = array(
					'id_role_jam_kerja' => $id_role_jam_kerja
				);

				$where = array('id' => $role[0]->id_pegawai);
				$query = $this->pegawai_model->update($where,$data);

				$status = array(
					'status'  => true,
					'pesan'   => 'Data History Role Jam Kerja Berhasil Dihapus'
				);
			}
			else {
				$status = array(
					'status' => false,
					'pesan'  => 'Gagal Melakukan Penghapusan Data History Role Jam Kerja, Kontak Administrator Atau Coba Kembali Beberapa Saat Lagi'
				);
			}
		}
		echo(json_encode($status));
	}

	public function delete_unor(){
		$this->form_validation->set_rules('id_instansi','Instansi','required');
		$this->form_validation->set_rules('id_unor','Unit Kerja','required');
		if($this->form_validation->run() == true) {
			$IdInstansiPrimaryKey = $this->input->post('id_instansi');
			$IdUnorPrimaryKey = $this->input->post('id_unor');

			$where ="id = '".$IdInstansiPrimaryKey."' ";
			$instansi = $this->pegawai_instansi_histori_model->showData($where);
			$hapus = $this->pegawai_instansi_histori_model->delete($where);

			$where ="id = '".$IdUnorPrimaryKey."' ";
			$unor = $this->pegawai_unit_kerja_histori_model->showData($where);
			$hapus = $this->pegawai_unit_kerja_histori_model->delete($where);

			if ($hapus) {
				$where = "id_pegawai = '".$instansi[0]->id_pegawai."' ";
				$order_by = 'tgl_mulai desc';
				$select = "m_pegawai_instansi_histori.*, m_instansi.nama as nama_instansi";
				$join = array(
					array(
						"table" => "m_instansi",
						"on"    => "m_pegawai_instansi_histori.kode_instansi = m_instansi.kode"
					)
				);
				$instansiHistoriData = $this->pegawai_instansi_histori_model->showData($where,null,$order_by,null,null,null,null,$select,$join);

				$kode_instansi = null;
				if($instansiHistoriData <> null) {
						$kode_instansi = $instansiHistoriData[0]->kode_instansi;
				}

				$select = "m_pegawai_unit_kerja_histori.*, m_unit_organisasi_kerja.nama as nama_unor";
				$join = array(
					array(
						"table" => "m_unit_organisasi_kerja",
						"on"    => "m_pegawai_unit_kerja_histori.kode_unor = m_unit_organisasi_kerja.kode"
					)
				);
				$unorHistoriData = $this->pegawai_unit_kerja_histori_model->showData($where,null,$order_by,null,null,null,null,$select,$join);

				$kode_unor = null;
				if($unorHistoriData <> null) {
						$kode_unor = $unorHistoriData[0]->kode_unor;
				}

				$data = array(
					'kode_instansi' => $kode_instansi,
					'kode_unor' => $kode_unor
				);

				$where = array('id' => $instansi[0]->id_pegawai);
				$query = $this->pegawai_model->update($where,$data);

				$status = array(
					'status'  => true,
					'pesan'   => 'Data History Unit Kerja Berhasil Dihapus'
				);
			}
			else {
				$status = array(
					'status' => false,
					'pesan'  => 'Gagal Melakukan Penghapusan Data History Unit Kerja, Kontak Administrator Atau Coba Kembali Beberapa Saat Lagi'
				);
			}
		}
		echo(json_encode($status));
	}

	public function exist_data($IdPrimaryKey) {
		$where ="kode = '".$IdPrimaryKey."' ";
		return $this->unor_kerja_model->getCount($where);
	}

	public function data_ajax_datatables() {
		$where 		 = null;
		$like 		 = null;
		$or_like 	 = null;
		$select = "m_pegawai.*, m_instansi.nama as nama_instansi, m_jenis_jabatan.nama as nama_jenis_jabatan, m_status_pegawai.nama as nama_status_pegawai";
		$join = array(
			array(
				"table" => "m_instansi",
				"on"    => "m_pegawai.kode_instansi = m_instansi.kode"
			),
			array(
				"table" => "m_eselon",
				"on"    => "m_pegawai.kode_eselon = m_eselon.kode"
			),
			array(
				"table" => "m_jenis_jabatan",
				"on"    => "m_pegawai.kode_jenis_jabatan = m_jenis_jabatan.kode"
			),
			array(
				"table" => "m_status_pegawai",
				"on"    => "m_pegawai.kode_status_pegawai = m_status_pegawai.kode"
			),
			array(
				"table" => "m_golongan",
				"on"    => "m_pegawai.kode_golongan_akhir = m_golongan.kode"
			)
		);
		$order_by = "coalesce(m_jenis_jabatan.urut,1000), coalesce(m_eselon.kode,'z'), coalesce(m_golongan.kode,'') desc, coalesce(m_pegawai.nama,'') asc";

		if($this->input->post('keyword')) {
			$like = array('LOWER(m_pegawai.nama)' => strtolower($this->input->post('keyword')));
			$or_like = array('LOWER(m_pegawai.nip)' => strtolower($this->input->post('keyword')));
			$or_like = array('LOWER(m_jenis_jabatan.nama)' => strtolower($this->input->post('keyword')));
			$or_like = array('LOWER(m_instansi.nama)' => strtolower($this->input->post('keyword')));
			$or_like = array('LOWER(m_status_pegawai.nama)' => strtolower($this->input->post('keyword')));
		}

		$pegawai = $this->pegawai_model->showData($where,$like,$order_by,null,null,null,$or_like,$select,$join);

		$datatable_pegawai = [];
		foreach ($pegawai as $key => $value) {
			$datatable_pegawai[$key][] = $value->id;
			$datatable_pegawai[$key][] = $value->nip;
			$datatable_pegawai[$key][] = $value->nama;
			$datatable_pegawai[$key][] = $value->nama_jenis_jabatan;
			$datatable_pegawai[$key][] = $value->nama_instansi;
			$datatable_pegawai[$key][] = $value->nama_status_pegawai;
		}

		$data = [
			"data" => $datatable_pegawai
		];

		echo json_encode($data);
	}


	public function jabatan_insert(){
		$user = $this->session->userdata();
		$this->form_validation->set_rules('KODE_JABATAN','Instansi','required');
		$this->form_validation->set_rules('TGL_MULAI','Unit Kerja','required');
		if($this->form_validation->run() == true) {

			$tanggal 				=  explode('/', $this->input->post('TGL_MULAI'));
			$tanggalInsert	=		$tanggal[2]."-".$tanggal[1]."-".$tanggal[0];

			$data = array(
				'tgl_mulai' 				=> $tanggalInsert,
				'user_upd' 					=> $user['username'],
				'tgl_upd' 					=> date('Y-m-d H:i:s'),
				'id_pegawai' 				=> $this->input->get('id_pegawai'),
				'kode_jabatan' 			=> $this->input->post('KODE_JABATAN')
			);
			$query = $this->pegawai_jabatan_histori_model->insert($data);

			$status = array(
				'status' => true,
			);

		}
		else{
			$status = array(
				'status' => false,
				'pesan'  => 'Gagal Melakukan Insert Data Jabatan'
			);
		}
		echo(json_encode($status));
	}

	public function jabatan_delete(){
		$where ="id = '".$this->input->get('id_histori')."'";
		$this->pegawai_jabatan_histori_model->delete($where);
		redirect(base_url()."master_pegawai/edit/".$this->input->get('id_pegawai'));
	}


	public function golongan_insert(){
		$user = $this->session->userdata();
		$this->form_validation->set_rules('KODE_GOLONGAN','Instansi','required');
		$this->form_validation->set_rules('TGL_MULAI','Unit Kerja','required');
		if($this->form_validation->run() == true) {

			$tanggal 				=  explode('/', $this->input->post('TGL_MULAI'));
			$tanggalInsert	=		$tanggal[2]."-".$tanggal[1]."-".$tanggal[0];

			$data = array(
				'tgl_mulai' 				=> $tanggalInsert,
				'tgl_upd' 					=> date('Y-m-d H:i:s'),
				'user_upd' 					=> $user['username'],
				'id_pegawai' 				=> $this->input->get('id_pegawai'),
				'kode_golongan' 		=> $this->input->post('KODE_GOLONGAN')
			);
			$query = $this->pegawai_golongan_histori_model->insert($data);

			$status = array(
				'status' => true,
			);

		}
		else{
			$status = array(
				'status' => false,
				'pesan'  => 'Gagal Melakukan Insert Data Golongan'
			);
		}
		echo(json_encode($status));
	}

	public function golongan_delete(){
		$where ="id = '".$this->input->get('id_histori')."'";
		$this->pegawai_golongan_histori_model->delete($where);
		redirect(base_url()."master_pegawai/edit/".$this->input->get('id_pegawai'));
	}


	public function eselon_insert(){
		$user = $this->session->userdata();
		$this->form_validation->set_rules('KODE_ESELON','Eselon','required');
		$this->form_validation->set_rules('TGL_MULAI','Unit Kerja','required');
		if($this->form_validation->run() == true) {

			$tanggal 				=  explode('/', $this->input->post('TGL_MULAI'));
			$tanggalInsert	=		$tanggal[2]."-".$tanggal[1]."-".$tanggal[0];

			$data = array(
				'tgl_mulai' 				=> $tanggalInsert,
				'user_upd' 					=> $user['username'],
				'tgl_upd' 					=> date('Y-m-d H:i:s'),
				'id_pegawai' 				=> $this->input->get('id_pegawai'),
				'kode_eselon' 			=> $this->input->post('KODE_ESELON')
			);
			$query = $this->pegawai_eselon_histori_model->insert($data);

			$status = array(
				'status' => true,
			);

		}
		else{
			$status = array(
				'status' => false,
				'pesan'  => 'Gagal Melakukan Insert Data Jabatan'
			);
		}
		echo(json_encode($status));
	}

	public function eselon_delete(){
		$where ="id = '".$this->input->get('id_histori')."'";
		$this->pegawai_eselon_histori_model->delete($where);
		redirect(base_url()."master_pegawai/edit/".$this->input->get('id_pegawai'));
	}

	public function rumpun_jabatan_insert(){
		$user = $this->session->userdata();
		$this->form_validation->set_rules('ID_RUMPUN_JABATAN','Eselon','required');
		$this->form_validation->set_rules('TGL_MULAI','Unit Kerja','required');
		if($this->form_validation->run() == true) {

			$tanggal 				=  explode('/', $this->input->post('TGL_MULAI'));
			$tanggalInsert	=		$tanggal[2]."-".$tanggal[1]."-".$tanggal[0];

			$data = array(
				'tgl_mulai' 				=> $tanggalInsert,
				'user_upd' 					=> $user['username'],
				'tgl_upd' 					=> date('Y-m-d H:i:s'),
				'id_pegawai' 				=> $this->input->get('id_pegawai'),
				'id_rumpun_jabatan' 			=> $this->input->post('ID_RUMPUN_JABATAN')
			);
			$query = $this->pegawai_rumpun_jabatan_histori_model->insert($data);

			$status = array(
				'status' => true,
			);

		}
		else{
			$status = array(
				'status' => false,
				'pesan'  => 'Gagal Melakukan Insert Data Rumpun Jabatan'
			);
		}
		echo(json_encode($status));
	}

	public function rumpun_jabatan_delete(){
		$where ="id = '".$this->input->get('id_histori')."'";
		$this->pegawai_rumpun_jabatan_histori_model->delete($where);
		redirect(base_url()."master_pegawai/edit/".$this->input->get('id_pegawai'));
	}


	public function unor_insert(){
		$user = $this->session->userdata();
		$this->form_validation->set_rules('KODE_UNOR','Eselon','required');
		$this->form_validation->set_rules('TGL_MULAI','Unit Kerja','required');
		if($this->form_validation->run() == true) {

			$tanggal 				=  explode('/', $this->input->post('TGL_MULAI'));
			$tanggalInsert	=		$tanggal[2]."-".$tanggal[1]."-".$tanggal[0];

			$data = array(
				'tgl_mulai' 				=> $tanggalInsert,
				'user_upd' 					=> $user['username'],
				'tgl_upd' 					=> date('Y-m-d H:i:s'),
				'id_pegawai' 				=> $this->input->get('id_pegawai'),
				'kode_unor' 			=> $this->input->post('KODE_UNOR')
			);
			$query = $this->pegawai_unit_kerja_histori_model->insert($data);

			$status = array(
				'status' => true,
			);

		}
		else{
			$status = array(
				'status' => false,
				'pesan'  => 'Gagal Melakukan Insert Data Unor'
			);
		}
		echo(json_encode($status));
	}

	public function unor_delete(){
		$where ="id = '".$this->input->get('id_histori')."'";
		$this->pegawai_unit_kerja_histori_model->delete($where);
		redirect(base_url()."master_pegawai/edit/".$this->input->get('id_pegawai'));
	}


	public function role_jam_kerja_insert(){
		$user = $this->session->userdata();
		$this->form_validation->set_rules('ID_ROLE_JAM_KERJA','Jam Kerja','required');
		$this->form_validation->set_rules('TGL_MULAI','Unit Kerja','required');
		if($this->form_validation->run() == true) {

			$tanggal 				=  explode('/', $this->input->post('TGL_MULAI'));
			$tanggalInsert	=		$tanggal[2]."-".$tanggal[1]."-".$tanggal[0];

			$data = array(
				'tgl_mulai' 				=> $tanggalInsert,
				'user_upd' 					=> $user['username'],
				'tgl_upd' 					=> date('Y-m-d H:i:s'),
				'id_pegawai' 				=> $this->input->get('id_pegawai'),
				'id_role_jam_kerja' => $this->input->post('ID_ROLE_JAM_KERJA')
			);
			$query = $this->pegawai_role_jam_kerja_histori_model->insert($data);
			//echo $this->db->last_query();
			$status = array(
				'status' => true,
			);

		}
		else{
			$status = array(
				'status' => false,
				'pesan'  => 'Gagal Melakukan Insert Data Unor'
			);
		}
		echo(json_encode($status));
	}

	public function role_jam_kerja_delete(){
		$where ="id = '".$this->input->get('id_histori')."'";
		$this->pegawai_role_jam_kerja_histori_model->delete($where);
		redirect(base_url()."master_pegawai/edit/".$this->input->get('id_pegawai'));
	}


}
