<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class M_log_load extends CI_Model {
    
    //var $table = 'm_mesin ';
    var $column_order = array('','finish_download','tanggal_load_mulai','tanggal_load_selesai','ip','nama','jumlah_data'); //set column field database for datatable orderable
    var $column_search = array('finish_download::text','tanggal_load_mulai::text','tanggal_load_selesai::text','nama'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('finish_download' => 'desc'); // default order 
 
 
    public function __construct(){
        parent::__construct(); 
        $this->load->database();
    }
 
    private function _get_datatables_query(){
        
        // $this->db->query('select t.finish_download, t.ip, m.nama, t.jumlah_data from t_log_penarikan t join m_mesin m on t.id_mesin = m.id where finish_download is not null and tanggal_load_selesai is null order by finish_download')-result();
        $this->db->select('
            t_log_penarikan.finish_download,
            t_log_penarikan.tanggal_load_mulai,
            t_log_penarikan.tanggal_load_selesai,
            t_log_penarikan.ip,
            m_mesin.nama,
            t_log_penarikan.jumlah_data,
        ');
        $this->db->from('t_log_penarikan');
        $this->db->join('m_mesin', 't_log_penarikan.id_mesin = m_mesin.id', 'left');
        $where = "t_log_penarikan.finish_download is NOT NULL and tanggal_load_selesai is NULL";
        $this->db->where($where);        
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
        $this->_get_datatables_query();
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
    
}