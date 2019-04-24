<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roster_pegawai extends CI_Controller {



	public function __construct() {
		parent::__construct();

		$this->load->model('instansi_model');
		$this->load->model('pegawai_model');
		$this->load->model('roster_model');
		$this->load->model('jenis_roster_model');

	}

	public function index(){

		$this->dataRosterTable 	= $this->jenis_roster_model->showData("","","m_jenis_roster.nama");
		//	var_dump($this->dataRosterTable);
	
		if($this->session->userdata('id_kategori_karyawan')=='4'){
			if ($this->session->userdata('kode_instansi') == '5.09.00.00.00') {
				$whereInstansi =	"m_instansi.kode='5.09.00.00.00' or m_instansi.kode='5.09.00.91.00'";
			}else{
				$whereInstansi =	"m_instansi.kode='".$this->session->userdata('kode_instansi')."' ";
			}
		}
		else{
			$whereInstansi =	"";
		}
	
		$this->dataInstansi = $this->instansi_model->showData($whereInstansi,"","nama");
		$this->dataRoster = '';

		if($this->input->get('id_instansi')=='' ){
			$where ="kode_instansi = '' ";

		}
		else{
			if($this->input->get('id_pegawai')==''){
				$this->dataRoster.='<table class="table table-bordered">
						<thead><tr><td>Silahkan Pilih Pegawai dari Dropdown dahulu </td></tr></thead></table>';
			}
			else{
				$where ="kode_instansi = '".$this->input->get('id_instansi')."' ";
			
				$this->dataRoster.='<table class="table table-bordered">
					<thead>
						<tr>';
						$month = $this->input->get('bulan');
						$year = $this->input->get('tahun');
						$date = mktime(0,0,0,$month,1,$year);

						for($n=1;$n <= date('t',$date);$n++){
							$this->dataRoster.=	"<th>".$n."</th>";
						}
						$this->dataRoster.='</tr></thead><tbody><tr>';

						for($p=1;$p <= date('t',$date);$p++){
							if($p < 10){
								$tanggal = $year."-".$month."-0".$p;
							}
							else{
								$tanggal = $year."-".$month."-".$p;
							}

							$whereRoster ="id_pegawai = '".$this->input->get('id_pegawai')."' and tanggal='".$tanggal."'";
							$this->hasilRoster= $this->roster_model->getData($whereRoster);

							if($this->hasilRoster){
								$this->dataRoster.=	"<td align='center'><a style='cursor:pointer' onclick='show_modal_form_roster(\"$tanggal\")'>".$this->hasilRoster->kode."</a><br><span class='glyphicon glyphicon-remove btn btn-danger btn-xs' onclick='tampil_pesan_hapus(\"Roster  tanggal ".$this->hasilRoster->tanggal."\",\"".base_url()."".$this->uri->segment('1')."/delete/?id_roster=".$this->hasilRoster->id."&bulan=".$this->input->get('bulan')."&tahun=".$this->input->get('tahun')."&id_instansi=".$this->input->get('id_instansi')."&id_pegawai=".$this->input->get('id_pegawai')."\")'></span></td>";
							}
							else{
								$this->dataRoster.=	"<td align='center'><i class='glyphicon glyphicon-edit' style='cursor:pointer;' onclick='show_modal_form_roster(\"$tanggal\")'></i></td>";
							}
						}

					$this->dataRoster.='</tr></tbody>
					</table>';
					
				
				$wherePegawai ="id = '".$this->input->get('id_pegawai')."' ";	
				$this->dataPegawai = $this->pegawai_model->getData($wherePegawai,"","nama");
			}			
		}
		$this->template_view->load_view('roster_pegawai/roster_pegawai_view');
	}

	public function add_data(){
		$this->form_validation->set_rules('id_pegawai', '', 'trim|required');
		$this->form_validation->set_rules('id_roster', '', 'trim|required');
		$this->form_validation->set_rules('tanggal', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{

			$where ="tanggal = '".$this->input->post('tanggal')."' and id_pegawai = '".$this->input->post('id_pegawai')."'";
			$this->roster_model->delete($where);


				$this->load->library('encrypt_decrypt');

				$data = array(
					'id' 	=> $this->encrypt_decrypt->new_id(),
					'id_pegawai' 	=> $this->input->post('id_pegawai'),
					'id_jenis_roster' 	=> $this->input->post('id_roster'),
					'tanggal' 	=> $this->input->post('tanggal'),
					'user_ins' 	=> $_SESSION['nama_karyawan']
				);

				$this->db->set('time_ins', 'NOW()', FALSE);	
				$query = $this->roster_model->insert($data);
				if($query){
					$status = array('status' => true );
				}
				else{
					$status = array('status' => FALSE, 'pesan' => 'ss');
				}

		}

		echo(json_encode($status));
	}

	public function delete(){

		$where ="id = '".$this->input->get('id_roster')."' ";
		$this->roster_model->delete($where);
		//echo $this->db->last_query();
		redirect(base_url()."".$this->uri->segment(1)."?bulan=".$this->input->get('bulan')."&tahun=".$this->input->get('tahun')."&id_instansi=".$this->input->get('id_instansi')."&id_pegawai=".$this->input->get('id_pegawai'));
	}

	public function show_data_option_pegawai(){

			$where ="kode_instansi = '".$this->input->post('kode_instansi')."' ";
			$this->dataPegawai = $this->pegawai_model->showData($where,"","nama");

			$tanggal = $this->input->post('tahun')."-".$this->input->post('bulan');
			echo '<option value="">Silahkan pilih Pegawai</option>';
			foreach($this->dataPegawai as $data){
				$queryJumlah = $this->db->query("select count(*) as jumlah from t_roster where to_char(tanggal, 'YYYY-MM') = '".$tanggal."' and id_pegawai='".$data->id."'");
				$dataJumlah = $queryJumlah->row();

				if(	$dataJumlah->jumlah == '0'){
					$nama = $data->nama;
				}
				else{
					$nama = $data->nama." - ".$dataJumlah->jumlah;
				}

				echo "<option value='".$data->id."'>".$data->nama."</option>";
		}
	}


}
