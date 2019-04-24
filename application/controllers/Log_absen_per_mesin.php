<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_absen_per_mesin extends CI_Controller {

	public function __construct() {
        parent::__construct();
	}

	public function index() {
		$this->load->model(['mesin_model', 'mesin_model', 'mesin_user_model', 'global_model']);

        // 4 = ADMIN SKPD, 3 = ADMIN BKD
        if($this->session->userdata('id_kategori_karyawan')=='4' || $this->session->userdata('id_kategori_karyawan')=='3'){
			$whereInstansi =	"kode_instansi='".$this->session->userdata('kode_instansi')."' and aktif = 't' ";
		}
		else{
			$whereInstansi =	"aktif = 't'";
		}
        $data['mesin'] = $this->mesin_model->showData($whereInstansi, "","nama");

        // jika sudah ada pencarian form
        if($this->input->get('tgl_mulai') and $this->input->get('tgl_akhir') and $this->input->get('id_mesin')) {
            $tgl_mulai = date('Y-m-d', strtotime($this->input->get('tgl_mulai')));
            $tgl_akhir = date('Y-m-d', strtotime($this->input->get('tgl_akhir')));
            $id_mesin = $this->input->get('id_mesin');
            $id_pegawai = $this->input->get('id_pegawai');

            //cari 'user_id' nya terlebih dahulu
            $mesin_user = $this->mesin_user_model->getData("id = '$id_pegawai'");

            $user_id = isset($mesin_user->user_id) ? $mesin_user->user_id : '';

            $query = "select * from fn_absensi_log_history_mesin('$id_mesin', '$tgl_mulai', '$tgl_akhir', '$user_id') as (tanggal timestamp without time zone, otomatis boolean, mesin varchar, badgenumber varchar, nama_Pegawai varchar, nama_unor varchar, unor_header varchar)";

            $data['log_mesin'] = $this->global_model->getData($query);
        }

        $this->template_view->load_view('log_absen_per_mesin/show', $data);

        // $this->output->enable_profiler(TRUE);
    }

    public function get_pegawai_by_instansi($kode_instansi){
        $this->load->model('pegawai_model');

        $whereInstansi = "pukh.kode_instansi = '$kode_instansi' ";
				if($this->input->post('tgl_mulai') <> '') {
					$tgl_mulai = date('Y-m-d', strtotime($this->input->post('tgl_mulai')));
				}
				else {
					$tgl_mulai = null;
				}
				if($this->input->post('tgl_akhir') <> '') {
					$tgl_akhir = date('Y-m-d', strtotime($this->input->post('tgl_akhir')));
				}
				else {
					$tgl_akhir = null;
				}

        //$pegawai = $this->pegawai_model->showData($whereInstansi, "","nama");
				$pegawai = $this->pegawai_model->showData2Log($whereInstansi, $tgl_mulai, $tgl_akhir);

        // echo "<pre>";
        // print_r ($pegawai);
        // echo "</pre>";

        // $this->output->enable_profiler(TRUE);

        echo '<option value="">-- Silahkan Pilih --</option>';
        foreach ($pegawai as $key => $value) {
            echo '<option value="'.$value->id.'">'.$value->nama.'</option>';
        }
    }

    public function get_pegawai_by_mesin($kode_mesin){
        $this->load->model('pegawai_model');

        $select = "m_pegawai.id, m_pegawai.nama";
        $whereMesin = "mesin_user.id_mesin = '$kode_mesin' ";
        $join = array(
            array(
                "table" => "mesin_user",
                "on"    => "m_pegawai.id = mesin_user.id_pegawai"
            )
        );
        $order = "m_pegawai.nama asc";
        $pegawai = $this->pegawai_model->showData($whereMesin, null, $order, null, null, null, null, null, $join);        
                
        // echo "<pre>";
        // print_r ($pegawai);
        // echo "</pre>";        
        
        // $this->output->enable_profiler(TRUE);

        echo '<option value="">-- Silahkan Pilih --</option>';
        foreach ($pegawai as $key => $value) {
            echo '<option value="'.$value->id.'">'.$value->nama.'</option>';
        }
    }

}