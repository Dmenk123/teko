<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Login extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    function cektanggal($date){
      $dayofweek = date('w', strtotime($date));
      return date('Y-m-d', strtotime(($date - $dayofweek).' day', strtotime($date)));
    }

    function index_get() {
        $id = $this->get('id');
        if ($id == '') {
            $kontak = $this->db->get('m_hari')->result();
        } else {
            $this->db->where('id', $id);
            $kontak = $this->db->get('m_hari')->result();
        }
        return json_encode($this->response($kontak, 200));
    }
    //Menampilkan data kontak
    function login_post() {
        $nip      = $this->post('nip');
        $password = $this->post('password');

        $data     = array();
        $result   = array();

        if($nip == "1122334455" AND $password == "123123"){
          $data_user = array(
            "nip"       => "081212323233",
            "nama"      => "Muhammad Adi Santoso S.Kom M.Kon",
            "jabatan"   => "Kepala Seksi Sekali",
            "opd"       => "Dinas Komunikasi Dan Informatika"
          );

          $data = array(
            "message" => "Login Berhasil",
            "error"   => true,
            "data"    => $data_user
          );
          return json_encode($this->response($data, 200));
        }
        else{
          $data = array(
            "message" => "Login Salah",
            "error"   => true
          );
          return json_encode($this->response($data, 200));
        }



    }

    //Menampilkan data kontak
    function getData() {
        $id = $this->get('id');
        if ($id == '') {
            $kontak = $this->db->get('m_hari')->result();
        } else {
            $this->db->where('id', $id);
            $kontak = $this->db->get('m_hari')->result();
        }
        return json_encode($this->response($kontak, 200));
    }






    //Masukan function selanjutnya disini
}
?>
