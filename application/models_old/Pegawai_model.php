<?php

class Pegawai_model extends CI_Model {
	public function __construct() {
		parent::__construct();

		//$this->load->model('log_model');
	}

	// function showData($where){

	// 	$tglSelesai		=	date('Y-m-d');

	// 	$queryPegawai 	=	$this->db->query("
	// 	select
	// 		m.id as id_pegawai,m.nama, m.nip,

	// 		m_status_pegawai.nama as nama_status_pegawai,

	// 		pukh.nama_unor,

	// 		pukh.nama_instansi,
	// 		pjh.nama_jabatan, pjh.urut,
	// 		pgh.nama_golongan,
	// 		peh.nama_eselon,
	// 		prjh.nama_rumpun_jabatan
	// 	from
	// 		m_pegawai m
	// 		left join m_status_pegawai on m_status_pegawai.kode = m.kode_status_pegawai

	// 		LEFT JOIN LATERAL (
	// 			SELECT
	// 				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 			FROM
	// 				m_pegawai_unit_kerja_histori h
	// 				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".$tglSelesai."' and m.id = h.id_pegawai
	// 			ORDER BY h.tgl_mulai DESC LIMIT 1
	// 		)
	// 		pukh ON true
	// 		LEFT JOIN LATERAL (
	// 			SELECT h.kode_jabatan, h.tgl_mulai, mjj.nama as nama_jabatan, mjj.urut FROM m_pegawai_jabatan_histori h LEFT JOIN m_jenis_jabatan mjj ON  h.kode_jabatan =  mjj.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai ORDER BY h.tgl_mulai DESC LIMIT 1
	// 		)
	// 		pjh ON true
	// 		LEFT JOIN LATERAL (
	// 			SELECT h.kode_golongan, h.tgl_mulai, mg.nama as nama_golongan FROM m_pegawai_golongan_histori h LEFT JOIN m_golongan mg ON  h.kode_golongan =  mg.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
	// 		)
	// 		pgh ON true
	// 		LEFT JOIN LATERAL (
	// 			SELECT h.kode_eselon, h.tgl_mulai, me.nama_eselon FROM m_pegawai_eselon_histori h LEFT JOIN m_eselon me ON  h.kode_eselon =  me.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
	// 		)
	// 		peh ON true
	// 		LEFT JOIN LATERAL (
	// 			SELECT h.id_rumpun_jabatan, h.tgl_mulai, mrj.nama as nama_rumpun_jabatan FROM m_pegawai_rumpun_jabatan_histori h LEFT JOIN m_rumpun_jabatan mrj ON  h.id_rumpun_jabatan =  mrj.id WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
	// 		)
	// 		prjh ON true


	// 	where
	// 		".$where."
	// 	order by
	// 		pjh.urut,
	// 		peh.kode_eselon,
	// 		pgh.kode_golongan desc,
	// 		m.nip

	// 	");
	// 	return	$queryPegawai->result();
	// }

	// function getCount($where){
	// 	$tglSelesai		=	date('Y-m-d');

	// 	$queryPegawai 	=	$this->db->query("
	// 	select
	// 		m.id as id_pegawai,m.nama, m.nip,

	// 		m_status_pegawai.nama as nama_status_pegawai,

	// 		pukh.nama_unor,

	// 		pukh.nama_instansi,
	// 		pjh.nama_jabatan, pjh.urut,
	// 		pgh.nama_golongan,
	// 		peh.nama_eselon,
	// 		prjh.nama_rumpun_jabatan
	// 	from
	// 		m_pegawai m

	// 		left join m_status_pegawai on m_status_pegawai.kode = m.kode_status_pegawai

	// 		LEFT JOIN LATERAL (
	// 			SELECT
	// 				h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
	// 			FROM
	// 				m_pegawai_unit_kerja_histori h
	// 				LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
	// 				LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".$tglSelesai."' and m.id = h.id_pegawai
	// 			ORDER BY h.tgl_mulai DESC LIMIT 1
	// 		)
	// 		pukh ON true
	// 		LEFT JOIN LATERAL (
	// 			SELECT h.kode_jabatan, h.tgl_mulai, mjj.nama as nama_jabatan, mjj.urut FROM m_pegawai_jabatan_histori h LEFT JOIN m_jenis_jabatan mjj ON  h.kode_jabatan =  mjj.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai ORDER BY h.tgl_mulai DESC LIMIT 1
	// 		)
	// 		pjh ON true
	// 		LEFT JOIN LATERAL (
	// 			SELECT h.kode_golongan, h.tgl_mulai, mg.nama as nama_golongan FROM m_pegawai_golongan_histori h LEFT JOIN m_golongan mg ON  h.kode_golongan =  mg.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
	// 		)
	// 		pgh ON true
	// 		LEFT JOIN LATERAL (
	// 			SELECT h.kode_eselon, h.tgl_mulai, me.nama_eselon FROM m_pegawai_eselon_histori h LEFT JOIN m_eselon me ON  h.kode_eselon =  me.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
	// 		)
	// 		peh ON true
	// 		LEFT JOIN LATERAL (
	// 			SELECT h.id_rumpun_jabatan, h.tgl_mulai, mrj.nama as nama_rumpun_jabatan FROM m_pegawai_rumpun_jabatan_histori h LEFT JOIN m_rumpun_jabatan mrj ON  h.id_rumpun_jabatan =  mrj.id WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
	// 		)
	// 		prjh ON true
	// 	where
	// 		".$where."

	// 	");
	// 	return	$queryPegawai->num_rows();
	// }


function showData($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null,$or_where = null,$or_like = null,$select = null,$join = array()){
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
			if($or_like){
				$this->db->group_start();
			}
			$this->db->like($like);
		}
		if($or_like){
			$this->db->or_like($or_like);
			$this->db->group_end();
		}
		foreach($join as $j) :
			$this->db->join($j["table"], $j["on"],'left');
		endforeach;
		if($order_by){
			$this->db->order_by($order_by);
		}
		return $this->db->get("m_pegawai",$limit,$fromLimit)->result();
	}

	function getCount($where = null,$like = null,$order_by = null,$limit = null, $fromLimit=null,$or_where = null,$or_like = null,$select = null,$join = array()){
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
			if($or_like){
				$this->db->group_start();
			}
			$this->db->like($like);
		}
		if($or_like){
			$this->db->or_like($or_like);
			$this->db->group_end();
		}
		foreach($join as $j) :
			$this->db->join($j["table"], $j["on"],'left');
		endforeach;
		return $this->db->get("m_pegawai",$limit,$fromLimit)->num_rows();
	}
	function getData($where){
		$tglSelesai		=	date('Y-m-d');

		$queryPegawai 	=	$this->db->query("
		select
			m.id as id_pegawai,m.nama, m.nip,m.tempat_lahir,
			m.gelar_depan,
			m.gelar_belakang,
			m.tgl_lahir,
			m.no_hp,
			m.no_registrasi,
			m.roster,
			m.aktif,
			m.kode_jenis_kelamin,
			m.kode_status_pegawai,
			m.tgl_lahir,
			m.tmt_golongan_akhir,

			m_status_pegawai.nama as nama_status_pegawai,

			pukh.nama_unor,

			pukh.nama_instansi,
			pjh.nama_jabatan, pjh.urut,
			pgh.nama_golongan,
			peh.nama_eselon,
			prjh.nama_rumpun_jabatan
		from
			m_pegawai m
			left join m_status_pegawai on m_status_pegawai.kode = m.kode_status_pegawai

			LEFT JOIN LATERAL (
				SELECT
					h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi
				FROM
					m_pegawai_unit_kerja_histori h
					LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
					LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".$tglSelesai."' and m.id = h.id_pegawai
				ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			pukh ON true
			LEFT JOIN LATERAL (
				SELECT h.kode_jabatan, h.tgl_mulai, mjj.nama as nama_jabatan, mjj.urut FROM m_pegawai_jabatan_histori h LEFT JOIN m_jenis_jabatan mjj ON  h.kode_jabatan =  mjj.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			pjh ON true
			LEFT JOIN LATERAL (
				SELECT h.kode_golongan, h.tgl_mulai, mg.nama as nama_golongan FROM m_pegawai_golongan_histori h LEFT JOIN m_golongan mg ON  h.kode_golongan =  mg.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			pgh ON true
			LEFT JOIN LATERAL (
				SELECT h.kode_eselon, h.tgl_mulai, me.nama_eselon FROM m_pegawai_eselon_histori h LEFT JOIN m_eselon me ON  h.kode_eselon =  me.kode WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			peh ON true
			LEFT JOIN LATERAL (
				SELECT h.id_rumpun_jabatan, h.tgl_mulai, mrj.nama as nama_rumpun_jabatan FROM m_pegawai_rumpun_jabatan_histori h LEFT JOIN m_rumpun_jabatan mrj ON  h.id_rumpun_jabatan =  mrj.id WHERE h.tgl_mulai <=  '".$tglSelesai."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			prjh ON true


		where
			".$where."
		order by
			pjh.urut,
			peh.kode_eselon,
			pgh.kode_golongan desc,
			m.nip

		");
		return	$queryPegawai->row();
	}

	function getDataJoin($where = null, $select = null, $join = array()){
		if($select){
			$this->db->select($select);
		}
		else {
			$this->db->select("*");
		}
		$this->db->where($where);
		foreach($join as $j) :
			$this->db->join($j["table"], $j["on"],'left');
		endforeach;
		return $this->db->get("m_pegawai")->row();
	}

	function getPrimaryKeyMax(){
		$query = $this->db->query('select max(id) as MAX from m_pegawai') ;
		return $query->row();
	}

	function insert($data){
		$this->db->set('id', 'uuid_generate_v1()', FALSE);
		$this->db->insert('m_pegawai', $data);
		return $this->db->insert_id('m_pegawai_id_seq');
	}
	function update($where,$data){
		$this->db->where($where);
		$this->db->update('m_pegawai', $data);
	}
	function delete($where){
		$this->db->where($where);
		$this->db->delete('m_pegawai');
	}

	function query($query){
		return $this->db->query($query)->row_array();
	}
}

?>
