<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_pegawai extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model(['pegawai_model', 'instansi_model', 'unor_kerja_model', 'eselon_model', 'jenis_jabatan_model', 'status_pegawai_model', 'jenis_kelamin_model', 'golongan_model', 'role_jam_kerja_model', 'pegawai_instansi_histori_model', 'pegawai_role_jam_kerja_histori_model', 'pegawai_rumpun_jabatan_histori_model', 'pegawai_unit_kerja_histori_model','pegawai_jabatan_histori_model','pegawai_golongan_histori_model','pegawai_eselon_histori_model','rumpun_jabatan_model', 'global_model']);
	}

	public function index(){
		$like = null;
		$urlSearch = null;
		$order_by ='nama';
		$where = "";
		//$lmt = 'LIMIT 50';
		$lmt = "";

		$this->instansiData = $this->instansi_model->showData("","",$order_by);
		if($this->input->get('id_instansi'))
		{
			if ($this->input->get('id_instansi') == 'all') {
				$where	=	"m.nama ilike '%".$this->input->get('keyword')."%'";
			}else{
				if(trim($this->input->get('keyword')) == "") {
					$where	=	"pukh.kode_instansi = '".$this->input->get('id_instansi')."'";
				}
				else {
					$where	=	"pukh.kode_instansi = '".$this->input->get('id_instansi')."' and m.nama ilike '%".$this->input->get('keyword')."%'";
				}
			}

			//$this->jumlahData 		= $this->pegawai_model->getCount2($where, $lmt);
			$this->showData 		= $this->pegawai_model->showData2($where, $lmt);
		}
		
		//$this->pagination->initialize($config);
		/*if ($this->session->userdata('id_kategori_karyawan') == '1') {
			$this->template_view->load_view('master/pegawai/pegawai_view');
		}else{
			$this->template_view->load_view('template/sedang-perbaikan');
		}*/
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
		$this->oldData = $this->pegawai_model->getData2($where);
		//var_dump($this->oldData);exit;

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

	private function _do_upload(){
        $config['upload_path']          = 'upload/meninggal';
        $config['allowed_types']        = 'gif|jpg|png|pdf|jpeg';
        //$config['max_size']             = 5500; //set max size allowed in Kilobyte
        // $config['max_width']            = 5000; // set max width image allowed
        // $config['max_height']           = 20000; // set max height allowed
        $config['file_name']            = 'meninggal_'.round(microtime(true) * 1000); //just milisec ond timestamp fot unique name

        $this->load->library('upload', $config);
        $this->load->library('image_lib');

        if(!$this->upload->do_upload('photo')){
            $data['inputerror'][] = 'photo';
            $data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
            $data['status'] = FALSE;
            echo json_encode($data);
            exit();
        }

        $nama_file = $this->upload->data('file_name');

        $config['image_library'] 	= 'gd2';
	    $config['source_image'] 	= 'upload'.$nama_file;
	    $config['create_thumb'] 	= FALSE;
	    $config['maintain_ratio'] 	= TRUE;
	    $config['width']     		= 1000;

	    $this->image_lib->clear();
	    $this->image_lib->initialize($config);
	    $this->image_lib->resize();


        return $this->upload->data('file_name');
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
		$this->form_validation->set_rules('TGL_MENINGGAL', '', 'trim');

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
			$meninggal = "FALSE";
			$tgl_meninggal = NULL;
			if($this->input->post('MENINGGAL') <> null) {
				$meninggal = 'TRUE';
				$tgl_meninggal = date('Y-m-d',strtotime($this->input->post('TGL_MENINGGAL')));
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
				'meninggal' => $meninggal,
				'tgl_meninggal' => $tgl_meninggal,
				'userupd' => $user['username']
			);
			if(!empty($_FILES['photo']['name'])){
				$upload = $this->_do_upload();
				$data['dokumen_kematian'] 	= $upload;
			}


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
			if ($this->input->post('cek-jabatan') == 't') {
				$pindah = true;
			}else{
				$pindah = null;
			}
			$tanggal 		= explode('/', $this->input->post('TGL_MULAI'));
			$tanggalInsert	= $tanggal[2]."-".$tanggal[1]."-".$tanggal[0];

			$data = array(
				'tgl_mulai' 				=> $tanggalInsert,
				'user_upd' 					=> $user['username'],
				'tgl_upd' 					=> date('Y-m-d H:i:s'),
				'id_pegawai' 				=> $this->input->get('id_pegawai'),
				'kode_jabatan' 				=> $this->input->post('KODE_JABATAN'),
				'langsung_pindah'			=> $this->input->post('cek-jabatan')
			);

			//db trans start
			$this->db->trans_begin();
			$query = $this->pegawai_jabatan_histori_model->insert($data);

			$where = "id = '".$this->input->get('id_pegawai')."'";
			$dataUpd = array(
				'kode_jenis_jabatan' => $this->input->post('KODE_JABATAN')
			);
			$queryUpd = $this->pegawai_jabatan_histori_model->update($where, $dataUpd, 'm_pegawai');

			#LOG START
			$data_log = [
				'id_user'			=> $this->session->userdata()['id_karyawan'],
				'aksi'				=> 'ADD JABATAN MASTER PEGAWAI',
				'tanggal'			=> date('Y-m-d H:i:s'),
				'data'				=> json_encode($data),
				'file_lampiran'		=> ($this->input->post('file_lampiran'))?$this->input->post('file_lampiran'):null
			];
			$this->global_model->save($data_log,'log_tekocak');
			#LOG FINISH

			$this->db->trans_status();
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$status = array('status' => false);
			}
			else{
				$this->db->trans_commit();
				$status = array('status' => true);
			}
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
		//db trans start
		$this->db->trans_begin();
		$id_histori = $this->input->get('id_histori');
		//get id_pegawai
		$q = "SELECT * FROM m_pegawai_jabatan_histori WHERE id='$id_histori'";
		$dataPeg = $this->global_model->getDataOne($q);
		
		//hapus histori
		$where ="id = '".$this->input->get('id_histori')."'";
		$this->pegawai_jabatan_histori_model->delete($where);

		//get last histori
		$idpeg = $dataPeg['id_pegawai'];
		$q2 = "SELECT kode_jabatan FROM m_pegawai_jabatan_histori WHERE id_pegawai='$idpeg' ORDER BY tgl_mulai DESC";
		$dataLast = $this->global_model->getDataOne($q2);
		$lastKodeJabatan = ($dataLast) ? $dataLast['kode_jabatan'] : null ;
		
		//update master pegawai
		$where2 = "id = '".$idpeg."'";
		$dataUpd = array(
			'kode_jenis_jabatan' => $lastKodeJabatan
		);
		$queryUpd = $this->pegawai_jabatan_histori_model->update($where2, $dataUpd, 'm_pegawai');

		#LOG START
		$data_log = [
			'id_user'			=> $this->session->userdata()['id_karyawan'],
			'aksi'				=> 'DELETE JABATAN MASTER PEGAWAI',
			'tanggal'			=> date('Y-m-d H:i:s'),
			'data'				=> json_encode($dataPeg),
			'file_lampiran'		=> ($this->input->post('file_lampiran'))?$this->input->post('file_lampiran'):null
		];
		$this->global_model->save($data_log,'log_tekocak');
		#LOG FINISH
		
		$this->db->trans_status();
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}
		else{
			$this->db->trans_commit();
		}
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
				'tgl_mulai' 			=> $tanggalInsert,
				'tgl_upd' 				=> date('Y-m-d H:i:s'),
				'user_upd' 				=> $user['username'],
				'id_pegawai' 			=> $this->input->get('id_pegawai'),
				'kode_golongan' 		=> $this->input->post('KODE_GOLONGAN')
			);
			//db trans start
			$this->db->trans_begin();
			$query = $this->pegawai_golongan_histori_model->insert($data);
			
			$where = "id = '".$this->input->get('id_pegawai')."'";
			$dataUpd = array(
				'kode_golongan_akhir' => $this->input->post('KODE_GOLONGAN')
			);
			$queryUpd = $this->pegawai_golongan_histori_model->update($where, $dataUpd, 'm_pegawai');

			#LOG START
			$data_log = [
				'id_user'			=> $this->session->userdata()['id_karyawan'],
				'aksi'				=> 'ADD GOLONGAN MASTER PEGAWAI',
				'tanggal'			=> date('Y-m-d H:i:s'),
				'data'				=> json_encode($data),
				'file_lampiran'		=> ($this->input->post('file_lampiran'))?$this->input->post('file_lampiran'):null
			];
			$this->global_model->save($data_log,'log_tekocak');
			#LOG FINISH

			$this->db->trans_status();
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$status = array('status' => false);
			}
			else{
				$this->db->trans_commit();
				$status = array('status' => true);
			}
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
		//db trans start
		$this->db->trans_begin();
		$id_histori = $this->input->get('id_histori');
		//get id_pegawai
		$q = "SELECT * FROM m_pegawai_golongan_histori WHERE id='$id_histori'";
		$dataPeg = $this->global_model->getDataOne($q);

		//hapus histori
		$where ="id = '".$this->input->get('id_histori')."'";
		$this->pegawai_golongan_histori_model->delete($where);

		//get last histori
		$idpeg = $dataPeg['id_pegawai'];
		$q2 = "SELECT kode_golongan FROM m_pegawai_golongan_histori WHERE id_pegawai='$idpeg' ORDER BY tgl_mulai DESC";
		$dataLast = $this->global_model->getDataOne($q2);
		$lastKodeGolongan = ($dataLast) ? $dataLast['kode_golongan'] : null;

		//update master pegawai
		$where2 = "id = '".$idpeg."'";
		$dataUpd = array(
			'kode_golongan_akhir' => $lastKodeGolongan
		);
		$queryUpd = $this->pegawai_golongan_histori_model->update($where2, $dataUpd, 'm_pegawai');

		#LOG START
		$data_log = [
			'id_user'			=> $this->session->userdata()['id_karyawan'],
			'aksi'				=> 'DELETE GOLONGAN MASTER PEGAWAI',
			'tanggal'			=> date('Y-m-d H:i:s'),
			'data'				=> json_encode($dataPeg),
			'file_lampiran'		=> ($this->input->post('file_lampiran'))?$this->input->post('file_lampiran'):null
		];
		$this->global_model->save($data_log,'log_tekocak');
		#LOG FINISH

		$this->db->trans_status();
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}
		else{
			$this->db->trans_commit();
		}
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
				'tgl_mulai' 			=> $tanggalInsert,
				'user_upd' 				=> $user['username'],
				'tgl_upd' 				=> date('Y-m-d H:i:s'),
				'id_pegawai' 			=> $this->input->get('id_pegawai'),
				'kode_eselon' 			=> $this->input->post('KODE_ESELON')
			);
			
			//db trans start
			$this->db->trans_begin();
			$query = $this->pegawai_eselon_histori_model->insert($data);

			$where = "id = '".$this->input->get('id_pegawai')."'";
			$dataUpd = array(
				'kode_eselon' => $this->input->post('KODE_ESELON')
			);
			$queryUpd = $this->pegawai_eselon_histori_model->update($where, $dataUpd, 'm_pegawai');
			
			#LOG START
			$data_log = [
				'id_user'			=> $this->session->userdata()['id_karyawan'],
				'aksi'				=> 'ADD ESELON MASTER PEGAWAI',
				'tanggal'			=> date('Y-m-d H:i:s'),
				'data'				=> json_encode($data),
				'file_lampiran'		=> ($this->input->post('file_lampiran'))?$this->input->post('file_lampiran'):null
			];
			$this->global_model->save($data_log,'log_tekocak');
			#LOG FINISH

			$this->db->trans_status();
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$status = array('status' => false);
			}
			else{
				$this->db->trans_commit();
				$status = array('status' => true);
			}
		}
		else{
			$status = array(
				'status' => false,
				'pesan'  => 'Gagal Melakukan Insert Data Eselon'
			);
		}
		echo(json_encode($status));
	}

	public function eselon_delete(){
		//db trans start
		$this->db->trans_begin();
		$id_histori = $this->input->get('id_histori');
		//get id_pegawai
		$q = "SELECT * FROM m_pegawai_eselon_histori WHERE id='$id_histori'";
		$dataPeg = $this->global_model->getDataOne($q);

		//hapus histori
		$where ="id = '".$this->input->get('id_histori')."'";
		$this->pegawai_eselon_histori_model->delete($where);

		//get last histori
		$idpeg = $dataPeg['id_pegawai'];
		$q2 = "SELECT kode_eselon FROM m_pegawai_eselon_histori WHERE id_pegawai='$idpeg' ORDER BY tgl_mulai DESC";
		$dataLast = $this->global_model->getDataOne($q2);
		$lastKodeEselon = ($dataLast) ? $dataLast['kode_eselon'] : null ;

		//update master pegawai
		$where2 = "id = '".$idpeg."'";
		$dataUpd = array(
			'kode_eselon' => $lastKodeEselon
		);
		$queryUpd = $this->pegawai_eselon_histori_model->update($where2, $dataUpd, 'm_pegawai');

		#LOG START
		$data_log = [
			'id_user'			=> $this->session->userdata()['id_karyawan'],
			'aksi'				=> 'DELETE ESELON MASTER PEGAWAI',
			'tanggal'			=> date('Y-m-d H:i:s'),
			'data'				=> json_encode($dataPeg),
			'file_lampiran'		=> ($this->input->post('file_lampiran'))?$this->input->post('file_lampiran'):null
		];
		$this->global_model->save($data_log,'log_tekocak');
		#LOG FINISH

		$this->db->trans_status();
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
		}
		else{
			$this->db->trans_commit();
		}
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
			$tanggal 		=  explode('/', $this->input->post('TGL_MULAI'));
			$tanggalInsert	=		$tanggal[2]."-".$tanggal[1]."-".$tanggal[0];
			if ($this->input->post('cek-unor') == 't') {
				$pindah = true;
			}else{
				$pindah = null;
			}

			if ($this->session->userdata('kode_instansi') == '5.09.00.00.00') {
				$data = array(
					'tgl_mulai' 	=> $tanggalInsert,
					'user_upd' 		=> $user['username'],
					'tgl_upd' 		=> date('Y-m-d H:i:s'),
					'id_pegawai' 	=> $this->input->get('id_pegawai'),
					'kode_unor' 	=> $this->input->post('KODE_UNOR'),
					'excel'			=> 't',
					'langsung_pindah'	=> $pindah
				);
			}else{
				$data = array(
					'tgl_mulai' 		=> $tanggalInsert,
					'user_upd' 			=> $user['username'],
					'tgl_upd' 			=> date('Y-m-d H:i:s'),
					'id_pegawai' 		=> $this->input->get('id_pegawai'),
					'kode_unor' 		=> $this->input->post('KODE_UNOR'),
					'excel'				=> null,
					'langsung_pindah'	=> $pindah
				);
			}

			//db trans start
			$this->db->trans_begin();
			$query = $this->pegawai_unit_kerja_histori_model->insert($data);

			$where = "id = '".$this->input->get('id_pegawai')."'";
			$dataUpd = array(
				'kode_unor' 		=> $this->input->post('KODE_UNOR'),
				'kode_instansi' 	=> $this->input->post('KODE_INSTANSI')				
			);
			$queryUpd = $this->pegawai_unit_kerja_histori_model->update($where, $dataUpd, 'm_pegawai');

			#LOG START
			$data_log = [
				'id_user'			=> $this->session->userdata()['id_karyawan'],
				'aksi'				=> 'ADD UNOR MASTER PEGAWAI',
				'tanggal'			=> date('Y-m-d H:i:s'),
				'data'				=> json_encode($data),
				'file_lampiran'		=> ($this->input->post('file_lampiran'))?$this->input->post('file_lampiran'):null
			];
			$this->global_model->save($data_log,'log_tekocak');
			#LOG FINISH

			$this->db->trans_status();
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$status = array('status' => false);
			}
			else{
				$this->db->trans_commit();
				$status = array('status' => true);
			}
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
		// $this->form_validation->set_rules('ID_ROLE_JAM_KERJA','Jam Kerja','required');
		// $this->form_validation->set_rules('TGL_MULAI','Unit Kerja','required');
		// if($this->form_validation->run() == true) {
		$tanggal 		=  explode('/', $this->input->post('TGL_MULAI'));
		$tanggalInsert	=		$tanggal[2]."-".$tanggal[1]."-".$tanggal[0];

		$data = array(
			'tgl_mulai' 				=> $tanggalInsert,
			'user_upd' 					=> $user['username'],
			'tgl_upd' 					=> date('Y-m-d H:i:s'),
			'id_pegawai' 				=> $this->input->get('id_pegawai'),
			'id_role_jam_kerja' 		=> $this->input->post('ID_ROLE_JAM_KERJA')
		);
		$query = $this->pegawai_role_jam_kerja_histori_model->insert($data);
		if ($query) {
			$status = array(
				'status' => true,
			);
		}else{
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

	public function getInstansi(){
		$unor = $this->db->query("
			SELECT
				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
			FROM
				m_pegawai_unit_kerja_histori h
				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode
			WHERE
				h.tgl_mulai <= '".$this->input->post('tanggal')."' and
				h.id_pegawai = '".$this->input->post('id_pegawai')."'
			ORDER BY h.tgl_mulai DESC LIMIT 1
		")->row_array();

		if($unor) {
			$status = array(
				'status' => true,
				'pesan'  => 'Ada Unor',
				'unor'   => $unor
			);
		}
		else {
			$status = array(
				'status' => false,
				'pesan'  => 'Pegawai Belum Terdaftar di Pemerintah Kota Surabaya Pada Bulan dan Tahun Tersebut'
			);
		}
		echo(json_encode($status));
	}
}
