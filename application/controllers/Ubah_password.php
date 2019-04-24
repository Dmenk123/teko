<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ubah_password extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('global_model');

	}

	public function index(){
		$this->template_view->load_view('ubah_password/ubah_password_pegawai');
	}

  public function ubah_password_pegawai(){
    $password_lama = $this->input->post('password_lama');
    $password_baru = $this->input->post('password_baru');
    $konfirmasi_password_baru = $this->input->post('konfirmasi_password_baru');
    $user = $this->session->userdata();

    $peg = $this->db->query("
      select
        *
      from
        m_pegawai
      where
        id = '".$user['id_karyawan']."' and
        kode_status_pegawai = '1' and
        aktif
    ")->row_array();

    if($peg) {
      if(sha1(md5($password_lama)) == $peg['password']) {
        if($password_baru == $konfirmasi_password_baru) {
          $update = "
            UPDATE m_pegawai
            SET
              password = '".sha1(md5($password_baru))."'
            WHERE
              id  = '".$peg['id']."'";

          $update_password = $this->db->query($update);

          if($update_password) {
            $status = array(
      				'status' => true,
      				'pesan'  => 'Password Baru Berhasil dirubah'
      			);
          }
          else {
            $status = array(
      				'status' => false,
      				'pesan'  => 'Terjadi Kesalahan, coba ulangi beberapa saat lagi atau hubungi Administrator Sistem'
      			);
          }
        }
        else {
          $status = array(
    				'status' => false,
    				'pesan'  => 'Password Baru dan Konfirmasi Password Baru Tidak Sama'
    			);
        }
      }
      else {
        $status = array(
  				'status' => false,
  				'pesan'  => 'Password Saat ini tidak sesuai'
  			);
      }
    }
    else {
      $status = array(
				'status' => false,
				'pesan'  => 'Anda Melakukan Akses Terlarang Pada Sistem'
			);
    }

		echo(json_encode($status));
	}
}
