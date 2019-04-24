<?php

class Monitoring_tarik_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		//$this->load->model('log_model');
	}

	/*function showData($limit = null, $fromLimit=null)
	{
		$query = $this->db->query(
			"SELECT * FROM (
    			SELECT DISTINCT ON (id_upd) * FROM  t_cron_scheduler
    		) p
			ORDER BY start_at DESC"
		);
		
		return $query->result();
	}*/

	function showData($where = null,$like = null,$order_by = null,$limit = null, $fromLimit = null)
	{
		$this->db->select('
			max(start_at) sa,
			nama_upd,
			id_upd,
			max(finish_at) fa,
			max(running_by) rb,
			max(date) tgl,
			max(status) status
		');

		$this->db->from('t_cron_scheduler');

		if($where) {
			$this->db->where($where);
		}
		if ($like) {
			$this->db->like($like);
		}
		
		$this->db->group_by('nama_upd, id_upd');

		if ($order_by) {
			$this->db->order_by($order_by, 'DESC');
		}

		if ($limit) {
			$this->db->limit($limit, $fromLimit);
		}
		
		$query = $this->db->get();
		if ($query) {
			return $query->result();
		}else{
			return false;
		}
	}

	public function showData2($data)
	{
		return $this->db->query($data)->result();
	}

	function getCount($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){
		$this->db->select("DISTINCT(kode)");
		if($where){
			$this->db->where($where);
		}
		if($like){
			$this->db->like($like);
		}
		return $this->db->get("m_instansi",$limit,$fromLimit)->num_rows();
	}

	function getCount2($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null){
		if($fromLimit) {
			$fromLimit;
		}else{
			$fromLimit = 0;
		}

		$query = $this->db->query(
			"SELECT * FROM (
    			SELECT DISTINCT ON (id_upd) * 
    			FROM  t_cron_scheduler
    		) p
			".$like." ORDER BY p.".$order_by." DESC LIMIT ".$limit." OFFSET ".$fromLimit.""
		);

		return $query->num_rows();
	}

	function getData($where){
		$this->db->select("*");
		$this->db->where($where);
		return $this->db->get("t_cron_scheduler")->row();
	}

	function getPrimaryKeyMax(){
		$query = $this->db->query('select max(id) as MAX from t_cron_scheduler') ;
		return $query->row();
	}

	function insert($data){
		$this->db->insert('t_cron_scheduler', $data);
	}

	function update($where,$data){
		$this->db->where($where);
		$this->db->update('t_cron_scheduler', $data);
	}

	function delete($where){
		$this->db->where($where);
		$this->db->delete('t_cron_scheduler');
	}
}

?>
