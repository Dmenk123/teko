<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Mod_api extends CI_Model {

	public function unit_kerja($kode_sik){
		$this->db->select('*');
		$this->db->from('m_instansi');
		$this->db->where('kode_sik',$kode_sik);
		$query = $this->db->get();
		if($query){
			return $query->result();
		}else{
			return false;
		}
	}

	public function skor_lembur($tabel,$where,$where_in){
		$this->db->select('*');
		$this->db->from($tabel);
		if($where_in){
			if ($where) {
				$this->db->where($where);
			}
			$this->db->where_in("kd_instansi",$where_in);
		}else{
			$this->db->where($where);
		}
		$query = $this->db->get();
		if($query){
			return $query->result();
		}else{
			return false;
		}
	}

	public function skor_lembur_detail($tabel,$where,$where_in){
		$this->db->select('*');
		$this->db->from($tabel);
		if($where_in){
			if ($where) {
				$this->db->where($where);
			}
			$this->db->where_in("id_instansi",$where_in);
		}else{
			$this->db->where($where);
		}
		$this->db->order_by('urut', 'asc');
		$query = $this->db->get();
		if($query){
			return $query->result();
		}else{
			return false;
		}
	}


	public function skor_lembur2($tabel,$where){
		$this->db->select('*');
		$this->db->from($tabel);
		$this->db->where($where);
		$query = $this->db->get();
		if($query){
			return $query->result();
		}else{
			return false;
		}
	}


  
    public function pegawai($kode){
		$this->db->select('
						m_pegawai.nama,
						m_pegawai.nip,
						m_pegawai.kode_instansi,
						m_pegawai.kode_unor,
						m_golongan.nama as golongan,
						m_instansi.nama as nama_instansi,
						m_jenis_jabatan.nama as nama_jabatan
						');
        $this->db->from('m_pegawai');
        $this->db->join('m_golongan','m_pegawai.kode_golongan_akhir = m_golongan.kode');
        $this->db->join('m_instansi','m_pegawai.kode_instansi = m_instansi.kode');
        $this->db->join('m_jenis_jabatan','m_pegawai.kode_jenis_jabatan = m_jenis_jabatan.kode');
        $this->db->where('m_pegawai.kode_instansi',$kode);
		$query = $this->db->get();
		if($query){
			return $query->result();
		}else{
			return false;
		}
	}


    public function pegawai2($tahun,$bulan,$kode,$tanggal){
		$query = $this->db->query("select
			m.id as id_pegawai,m.nama, m.nip,
			pukh.nama_unor,
			pukh.nama_instansi,
			pukh.kode_sik,
			pjh.nama_jabatan, 
			pjh.urut,
			pgh.nama_golongan,
			peh.nama_eselon,
			prjh.nama_rumpun_jabatan
		from
			m_pegawai m
			LEFT JOIN LATERAL (
				SELECT
					h.kode_unor, h.tgl_mulai, muok.nama as nama_unor, mi.kode as kode_instansi, mi.nama as nama_instansi, mi.kode_sik AS kode_sik
				FROM
					m_pegawai_unit_kerja_histori h
					LEFT JOIN m_unit_organisasi_kerja muok ON  h.kode_unor =  muok.kode
					LEFT JOIN m_instansi mi ON  muok.kode_instansi = mi.kode WHERE h.tgl_mulai <= '".$tanggal."' and m.id = h.id_pegawai
				ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			pukh ON true
			LEFT JOIN LATERAL (
				SELECT h.kode_jabatan, h.tgl_mulai, mjj.nama as nama_jabatan, mjj.urut FROM m_pegawai_jabatan_histori h LEFT JOIN m_jenis_jabatan mjj ON  h.kode_jabatan =  mjj.kode WHERE h.tgl_mulai <=  '".$tanggal."' and m.id = h.id_pegawai ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			pjh ON true
			LEFT JOIN LATERAL (
				SELECT h.kode_golongan, h.tgl_mulai, mg.nama as nama_golongan FROM m_pegawai_golongan_histori h LEFT JOIN m_golongan mg ON  h.kode_golongan =  mg.kode WHERE h.tgl_mulai <=  '".$tanggal."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			pgh ON true
			LEFT JOIN LATERAL (
				SELECT h.kode_eselon, h.tgl_mulai, me.nama_eselon FROM m_pegawai_eselon_histori h LEFT JOIN m_eselon me ON  h.kode_eselon =  me.kode WHERE h.tgl_mulai <=  '".$tanggal."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			peh ON true
			LEFT JOIN LATERAL (
				SELECT h.id_rumpun_jabatan, h.tgl_mulai, mrj.nama as nama_rumpun_jabatan FROM m_pegawai_rumpun_jabatan_histori h LEFT JOIN m_rumpun_jabatan mrj ON  h.id_rumpun_jabatan =  mrj.id WHERE h.tgl_mulai <=  '".$tanggal."' and m.id = h.id_pegawai  ORDER BY h.tgl_mulai DESC LIMIT 1
			)
			prjh ON true
		where
			pukh.kode_instansi = '".$kode."'
		order by
			pjh.urut,
			peh.kode_eselon,
			pgh.kode_golongan desc,
			m.nip");
		// $query = $this->db->get();
		if($query){
			return $query->result();
		}else{
			return false;
		}
	}
	
}