<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class M_setting_hari_libur extends CI_Model {
    
    var $table = 's_hari_libur ';
    var $column_order = array(null, 'tanggal', 'nama', 's_hari_libur.keterangan', null); //set column field database for datatable orderable
    var $column_search = array('nama','s_hari_libur.keterangan'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('nama' => 'asc'); // default order 
 
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
 
    public function _get_datatables_query($tahun_awal, $tahun_akhir){
        $this->db->select('
            s_hari_libur.id,
            m_hari_libur.nama,
            s_hari_libur.tanggal,
            s_hari_libur.keterangan'
        );
        $this->db->from('s_hari_libur');
        $this->db->join('m_hari_libur','s_hari_libur.id_libur = m_hari_libur.id', 'left');
        $this->db->where('s_hari_libur.tanggal >=', $tahun_awal);
        $this->db->where('s_hari_libur.tanggal <=', $tahun_akhir);
        // $this->db->group_by('ip,nama');
        #$this->db->from($this->table);
        
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
 
    function get_datatables($tahun){
        $tahun_awal = $tahun.'-01-01';
        $tahun_akhir = $tahun.'-12-31';
        $this ->_get_datatables_query($tahun_awal, $tahun_akhir);
        if($this->input->post('length') != -1)
        $this->db->limit($this->input->post('length'),$this->input->post('start'));
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered($tahun){
        $tahun_awal = $tahun.'-01-01';
        $tahun_akhir = $tahun.'-12-31';
        $this->_get_datatables_query($tahun_awal, $tahun_akhir);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($tahun){
        $tahun_awal = $tahun.'-01-01';
        $tahun_akhir = $tahun.'-12-31';
        $this ->_get_datatables_query($tahun_awal, $tahun_akhir);
        return $this->db->count_all_results();
    }

    public function cek_data($id)
    {
        $this->db->select('*');
        $this->db->from('s_hari_libur');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_data_libur()
    {
        $query = $this->db->get('m_hari_libur');
        return $query->result();
    }

   /* public function get_last_id()
    {
       return $this->db->query("SELECT nextval('coba_sequence')")->row(); 
    }*/
    
    function get_by_id($id){
        $this->db->select('
            s_hari_libur.id,
            s_hari_libur.keterangan,
            s_hari_libur.tanggal,
            m_hari_libur.id as id_hari,
            m_hari_libur.nama');
        $this->db->from('s_hari_libur');
        $this->db->join('m_hari_libur', 's_hari_libur.id_libur = m_hari_libur.id', 'left');
        $this->db->where('s_hari_libur.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    function insert($data){
        $this->db->set('id', 'uuid_generate_v1()', FALSE);
        $this->db->insert('s_hari_libur', $data);
        return true;
    }

    function delete($where){
        $this->db->where($where);
        return $this->db->delete('s_hari_libur');
    }

     function update_data($where,$data){
        $this->db->where($where);
        return $this->db->update('s_hari_libur', $data);
    }
}