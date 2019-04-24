<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class M_kunci_upload extends CI_Model {
	
	var $table = 't_kunci_upload ';
    var $column_order = array('nama'); //set column field database for datatable orderable
    var $column_search = array('nama'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('nama' => 'asc'); // default order 
 
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query(){
        $this->db->select('
            t_kunci_upload.id,
            m_instansi.kode,
            m_instansi.nama,
            t_kunci_upload.tanggal,
            t_kunci_upload.is_kunci'
        );
        $this->db->from('t_kunci_upload');
        $this->db->join('m_instansi','t_kunci_upload.kode_instansi = m_instansi.kode', 'right');
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

    public function cek_data($idInstansi)
    {
        $this->db->select('*');
        $this->db->from('t_kunci_upload');
        $this->db->where('kode_instansi', $idInstansi);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_last_id()
    {
       return $this->db->query("SELECT nextval('coba_sequence')")->row(); 
    }
    

    function insert($data){
        $this->db->set('id', 'uuid_generate_v1()', TRUE);
        $this->db->insert('t_kunci_upload', $data);
    }

    function update($where,$data){
        $this->db->where($where);
        $this->db->update('t_kunci_upload', $data);
    }

    function delete($where){
        $this->db->where($where);
        return $this->db->delete('t_kunci_upload');
    }

    function deleteAll(){
        return $this->db->delete('t_kunci_upload');
    }
}