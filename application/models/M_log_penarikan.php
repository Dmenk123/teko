<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class M_log_penarikan extends CI_Model {
    
    var $table = 'm_mesin ';
    var $column_order = array('','nama','ip_address','jam_selesai_download','cast(array_terakhir_mesin as integer)','jam_selesai_load','jam_download','status_mesin'); //set column field database for datatable orderable
    var $column_search = array('nama','ip_address','status'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('jam_selesai_download' => 'desc'); // default order 
 
 
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
    
    /*private function rules(){
        $session_check = $this->session->userdata();
        if((int)$session_check['id_kategori_karyawan'] > 2){
            $this->db->where('kode_instansi',$session_check['kode_instansi']);
        }
    }*/

     private function rules()
     {
        $session_check = $this->session->userdata();
        if ((int)$session_check['id_kategori_karyawan'] == 15) {
            return false;
        }elseif ((int)$session_check['id_kategori_karyawan'] > 2){
            if ($session_check['kode_instansi'] == '5.06.00.00.00') 
            {
                $kodeAwalDinas =  substr($session_check['kode_instansi'],0,5);
                $this->db->like("kode_instansi", $kodeAwalDinas);
                //$this->db->where('kode_instansi',$session_check['kode_instansi']);
            }
            else
            {
                $this->db->where('kode_instansi',$session_check['kode_instansi']);
            }
        }
    }
 
    private function _get_datatables_query(){
        #(select manual from t_log_penarikan a where a.ip = ip and a.tanggal_input = (select max(tanggal_input) from t_log_penarikan) limit 1) as manual
        // $this->db->query("SELECT distinct t1.ip,
                // m_mesin.nama,
                // t1.tanggal_input,
                // t1.jumlah_data,
                // t1.status,
                // t1.manual
        // FROM t_log_penarikan t1
        // left join m_mesin on t1.ip = m_mesin.ip_address  
        // WHERE t1.tanggal_input = (SELECT MAX(t2.tanggal_input)
                 // FROM t_log_penarikan t2
                 // WHERE t2.ip = t1.ip)
                                 // order by t1.ip");
                                 
        // $this->db->distinct();
        // $this->db->select('
                           // t1.ip,
                 // m_mesin.nama,
                 // t1.tanggal_input,
                 // t1.jumlah_data,
                 // t1.status,
                 // t1.manual
                            // ');
        // $this->db->from('t_log_penarikan t1');
        // $this->db->join('m_mesin','t_log_penarikan.ip = (SELECT MAX(t2.tanggal_input) FROM t_log_penarikan t2 WHERE t2.ip = t1.ip) ');
        // $this->db->where('t1.tanggal_input = (SELECT MAX(t2.tanggal_input) FROM t_log_penarikan t2 WHERE t2.ip = t1.ip)');
        // $this->db->group_by('t1.ip');
        
        
        // $this->db->select('
        //                     nama,
        //                     ip,
        //                     max( tanggal_input ) AS tanggal_input,
        //                  max(manual) as manual,
        //                     max( jumlah_data ) AS jumlah_data,
        //                     max( t_log_penarikan.status ) AS status
        //                     ');
        // $this->db->from('t_log_penarikan');
        // $this->db->join('m_mesin','t_log_penarikan.ip = m_mesin.ip_address ');
        // $this->db->group_by('ip,nama');
        // $this->db->order_by('tanggal_input','desc');
        
        // $this->db->limit('1');
        $this->db->from($this->table);
        $this->db->where('kode_instansi is not null');
        $this->db->where('aktif','t');
        if ($this->rules() !== FALSE) {
             $this->rules();
        }
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

    public function get_nama_instansi($id)
    {
       $this->db->select('nama');
       $this->db->from('m_instansi');
       $this->db->where('kode',$id);
       $query = $this->db->get();
       return $query->row();
    }

    public function get_last_gen($kd_instansi)
    {
        $this->db->select('start_at, finish_at');
        $this->db->from('t_cron_scheduler');
        $this->db->where('id_upd', $kd_instansi);
        $this->db->order_by('start_at', 'desc');
        $query = $this->db->get();
        return $query->row();
    }
    
}