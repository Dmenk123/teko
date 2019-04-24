<?php defined('BASEPATH') OR exit('No direct script access allowed');

Class M_monitor_kondisi extends CI_Model {
    
    //var $table = 'm_mesin ';
    var $column_order = array('','ip_address','nama','tanggal_tarik','jml_download','jml_download_s','jml_download_g'); //set column field database for datatable orderable
    var $column_search = array('ip_address::text','tanggal_tarik::text','jml_download::text','nama'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('tanggal_tarik' => 'desc'); // default order 
     
    public function __construct(){
        parent::__construct(); 
        $this->load->database();
    }

    // public function get_mesin_data()
    // {
    //     $this->db->select('id, ip_address, nama');
    //     return $this->db->get('m_mesin')->result_array();
    // }
 
    private function _get_datatables_query($tanggal, $limit=""){
        $i = 0;
        foreach ($this->column_search as $item) // loop column 
        {
            if($this->input->post('search')) // if datatable send POST for search
            {
                if($i===0) // first loop
                {
                    $q_like = "AND (".$item." ilike '%".$_POST['search']['value']."%')";
                    //$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    //$this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $q_like = "OR (".$item." ilike '%".$_POST['search']['value']."%')";
                    //$this->db->or_like($item, $_POST['search']['value']);
                }
            }
            $i++;
        }

        if(isset($_POST['order'])) // here order processing
        {
            //$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            $o1 = $this->column_order[$_POST['order']['0']['column']];
            $o2 = $_POST['order']['0']['dir'];
            $q_order = "order by ".$o1." ".$o2."";
        } 
        else if(isset($this->order))
        {
            $o1 = $this->order;
            $q_order = "order by ".key($o1)." ".$o1[key($o1)]."";
            //$this->db->order_by(key($order), $order[key($order)]);
        }
        
        $q_data_tarik = "SELECT 
					m.id,
					m.ip_address,
					m.nama,
					t_tarik.tanggal_tarik,
					t_tarik.jml_download,
					t_tarik_s.jml_download_s,
					t_tarik_g.jml_download_g,
					t_load.jml_load
				from m_mesin m
				LEFT JOIN LATERAL (
					SELECT
						count(*) as jml_download, cast(l_tarik.start_download as date) as tanggal_tarik
					FROM
						t_log_penarikan l_tarik
						where cast(l_tarik.start_download as date) = '".$tanggal."' and l_tarik.id_mesin = m.id
						GROUP BY cast(l_tarik.start_download as date)
				) t_tarik ON true
				LEFT JOIN LATERAL (
					SELECT
						count(*) as jml_download_s
					FROM
						t_log_penarikan l_tarik_s
						where cast(l_tarik_s.start_download as date) = '".$tanggal."' and l_tarik_s.id_mesin = m.id and l_tarik_s.status = 'sukses'
				) t_tarik_s ON true
				LEFT JOIN LATERAL (
					SELECT
						count(*) as jml_download_g
					FROM
						t_log_penarikan l_tarik_g
						where cast(l_tarik_g.start_download as date) = '".$tanggal."' and l_tarik_g.id_mesin = m.id and l_tarik_g.status = 'gagal'
				) t_tarik_g ON true
				LEFT JOIN LATERAL (
					SELECT
						count(*) as jml_load
					FROM
						t_log_penarikan l_load
						where cast(l_load.tanggal_load_mulai as date) = '".$tanggal."' and l_load.id_mesin = m.id 
				) t_load ON true
				where m.aktif = true ".$q_like."
				GROUP BY 
				m.id, 
				t_tarik.tanggal_tarik, 
				t_tarik.jml_download, 
				t_tarik_s.jml_download_s, 
				t_tarik_g.jml_download_g,
				t_load.jml_load
                ".$q_order." ".$limit."
        ";
       
        return $q_data_tarik;
                        
        /* $i = 0;
     
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
        } */
    }

    function get_datatables($tanggal, $q_limit){
        $hasil = $this->_get_datatables_query($tanggal, $q_limit);
        // echo $hasil;die;
        $query = $this->db->query($hasil);
        return $query->result();
    }
 
    function count_filtered($tanggal, $q_limit){
        $hasil = $this->_get_datatables_query($tanggal, $q_limit);
        // $query = $this->db->get();
        $query = $this->db->query($hasil);
        return $query->num_rows();
        // $q_data_tarik = "
        //     SELECT a.ip, m_mesin.nama, a.status, COUNT( a.* ) jumlah_download 
        //     FROM t_log_penarikan a 
        //     left join m_mesin on m_mesin.ip_address = a.ip where cast(a.start_download as Date) = '".$tanggal."' 
        //     GROUP BY a.ip, a.status, m_mesin.nama";
        // return $this->db->query($q_data_tarik)->num_rows();
    }
 
    public function count_all($tanggal, $q_limit){
        // $this ->_get_datatables_query();
        /* $q_data_tarik = "
            SELECT a.ip, m_mesin.nama, a.status, COUNT( a.* ) jumlah_download 
            FROM t_log_penarikan a 
            left join m_mesin on m_mesin.ip_address = a.ip where cast(a.start_download as Date) = '".$tanggal."' 
            GROUP BY a.ip, a.status, m_mesin.nama";
        return $this->db->count_all_results(); */
        $hasil = $this->_get_datatables_query($tanggal, $q_limit);
        $query = $this->db->query($hasil);
        return $query->num_rows();
    }

    function getData($table, $where = null, $like = null, $order_by = null, $limit = null, $fromLimit=null, $or_where = null, $or_like = null, $select = null,$join = array())
    {
        if($select){
            $this->db->select($select);
        }
        else {
            $this->db->select("*");
        }
        if($where){
            $this->db->where($where);
        }
        if($or_where){
            $this->db->or_where($or_where);
        }
        if($like){
            $this->db->like($like);
        }
        if($or_like){
            $this->db->or_like($or_like);
        }
        foreach($join as $j) :
            $this->db->join($j["table"], $j["on"],'left');
        endforeach;
        if($order_by){
            $this->db->order_by($order_by);
        }
        return $this->db->get($table,$limit,$fromLimit)->result_array();
    }
    
}