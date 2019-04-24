<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class M_pegawai_permesin extends CI_Model {
	
	var $table = 'mesin_user';
    var $column_order = array('','m_pegawai.nama','ip_address','m_instansi.nama'); //set column field database for datatable orderable
    var $column_search = array('m_pegawai.nama','m_instansi.nama'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('m_pegawai.nama' => 'desc'); // default order 
 
 
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
	
	private function rules(){
		$session_check = $this->session->userdata();
		if((int)$session_check['id_kategori_karyawan'] > 2){
			$this->db->where('kode_instansi',$session_check['kode_instansi']);
		}
	}
 
    private function _get_datatables_query(){
        if($this->input->post('id_instansi')){
            $this->db->where('m_pegawai.kode_instansi', $this->input->post('id_instansi'));
        }

		$this->db->select(
            'mesin_user.user_id,
             mesin_user.id_mesin,
             mesin_user.id as id_usermesin,
             m_mesin.ip_address,
             m_mesin.nama as nama_instansi_mesin,
             m_pegawai.nama,
             m_pegawai.kode_instansi,
             m_instansi.nama as nama_instansi
            ');
        $this->db->from('mesin_user');
        $this->db->join('m_pegawai', 'm_pegawai.id = mesin_user.id_pegawai', 'left');
        $this->db->join('m_mesin', 'm_mesin.id = mesin_user.id_mesin', 'left');
        $this->db->join('m_instansi', 'm_instansi.kode =  m_pegawai.kode_instansi', 'left');
        // $this->db->from($this->table);
        // $this->rules();
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($this->input->post('search')) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables(){
        $this ->_get_datatables_query();
        if($this->input->post('length') != -1)
        $this->db->limit($this->input->post('length'),$this->input->post('start'));
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered(){
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all(){
        $this ->_get_datatables_query();
        return $this->db->count_all_results();
    }

    public function select_data($tabel,$where){
        $this->db->select('mesin_user.user_id,
             mesin_user.id_mesin,
             mesin_user.id as id_usermesin,
             m_mesin.ip_address,
             m_mesin.nama as nama_instansi_mesin,
             m_pegawai.nama,
             m_pegawai.kode_instansi,
             m_instansi.nama as nama_instansi
            ');
        $this->db->from($tabel);
        $this->db->join('m_mesin', 'm_mesin.id = mesin_user.id_mesin', 'left');
        $this->db->join('m_pegawai', 'm_pegawai.id = mesin_user.id_pegawai', 'left');
        $this->db->join('m_instansi', 'm_instansi.kode =  m_pegawai.kode_instansi', 'left');
        if($where){
            $this->db->where($where);
        }
        $query  = $this->db->get();
        $result = $query->result();
        if($result){
            return $query->result();
        }
    }

    public function select_mesin($kode){
        $this->db->select('ip_address,nama');
        $this->db->from('m_mesin');
        if($kode){
            $this->db->where('kode_instansi',$kode);
        }
        $query  = $this->db->get();
        $result = $query->result();
        
        if($result){
            return $query->result();
        }
    }
    
}