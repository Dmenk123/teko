<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_absen_per_unor extends CI_Controller {

	public function __construct() {
        parent::__construct();
	}

	public function index() {

		$this->load->model(['instansi_model', 'mesin_user_model', 'global_model']);

        // 4 = ADMIN SKPD, 3 = ADMIN BKD
        if($this->session->userdata('id_kategori_karyawan')=='4' || $this->session->userdata('id_kategori_karyawan')=='3'){
			$whereInstansi =	"m_instansi.kode='".$this->session->userdata('kode_instansi')."' ";
		}
		else{
			$whereInstansi =	null;
		}
        $data['instansi'] = $this->instansi_model->showData($whereInstansi, "","nama");

        // jika sudah ada pencarian form
        if($this->input->get('tgl_mulai') and $this->input->get('tgl_akhir') and $this->input->get('id_instansi')) {
            $tgl_mulai = date('Y-m-d', strtotime($this->input->get('tgl_mulai')));
            $tgl_akhir = date('Y-m-d', strtotime($this->input->get('tgl_akhir')));
            $id_instansi = $this->input->get('id_instansi');
            $id_pegawai = $this->input->get('id_pegawai');

            //cari 'user_id' nya terlebih dahulu
            //$mesin_user = $this->mesin_user_model->getData("id_pegawai = '$id_pegawai' ");

            //$user_id = isset($mesin_user->user_id) ? $mesin_user->user_id : '';

            // $query = "select * from fn_absensi_log_history('$id_instansi', '$tgl_mulai', '$tgl_akhir', '$user_id') as (tanggal timestamp without time zone, otomatis boolean, mesin varchar, badgenumber varchar, nama_Pegawai varchar, nama_unor varchar, unor_header varchar)";
            $query = "select 
                        l.tanggal,
                        l.otomatis,
                        l.mesin,
                        l.badgenumber,
                        coalesce(p.nama,'') as nama_pegawai, 
                        coalesce(u.nama, '') as nama_unor,
                        coalesce(uh.nama,'') as unor_header 
                    from(
                            select l.tanggal, l.otomatis, coalesce(m.nama, '') as mesin, l.badgenumber, mu.id_pegawai
                            from absensi_log l
                            inner join mesin_user mu on mu.id_mesin = l.id_mesin and mu .user_id=l.badgenumber
                            left join m_mesin m on m.id = l.id_mesin
                            where l.tanggal::date >= '".$tgl_mulai."'::date
                            and l.tanggal::date <= '".$tgl_akhir."'::date
                            and m.kode_instansi = '".$id_instansi."'
                            and l.badgenumber in (
                                select user_id from mesin_user where id_pegawai ilike '%".$id_pegawai."%'
                            )
                    )l
                    left join m_pegawai p on p.id = l.id_pegawai
                    left join m_unit_organisasi_kerja u on u.kode = p.kode_unor
                    left join m_unit_organisasi_kerja uh on uh.kode=u.parent_id
                    order by l.tanggal desc";
            
            $data['log_unor'] = $this->global_model->getData($query);
        }

        $this->template_view->load_view('log_absen_per_unor/show', $data);
        // if ($this->session->userdata('id_kategori_karyawan') == '1') {
        //     $this->template_view->load_view('log_absen_per_unor/show', $data);
        // }else{
        //     $this->template_view->load_view('template/sedang-perbaikan');
        // }
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

        echo '<option value="">-- Silahkan Pilih --</option>';
        foreach ($pegawai as $key => $value) {
            echo '<option value="'.$value->id.'">'.$value->nama.'</option>';
        }
    }

}
