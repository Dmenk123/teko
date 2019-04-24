<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_role extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$this->load->model('kategori_user_model');
	}

	public function index(){
		$like = null;
		$order_by = 'nama_kategori_user';
		$urlSearch = null;

		if($this->input->get('field')){
			$like = array($_GET['field'] => $_GET['keyword']);
			$urlSearch = "?field=".$_GET['field']."&keyword=".$_GET['keyword'];
		}

		$config['base_url'] 	= base_url().''.$this->uri->segment(1).'/index'.$urlSearch;
		$this->jumlahData = $this->kategori_user_model->getCount("",$like);
		$config['total_rows'] 	= $this->jumlahData;
		$config['per_page'] 	= 10;

		$this->showData = $this->kategori_user_model->showData("",$like,$order_by,$config['per_page'],$this->input->get('per_page'));
		$this->pagination->initialize($config);

		$this->template_view->load_view('setting_role/setting_role_view');
	}
	public function add(){
		$this->template_view->load_view('setting_role/setting_role_add_view');
	}
	public function add_data(){
		$this->form_validation->set_rules('NAMA_KATEGORI_USER', '', 'trim|required');

		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{
			$maxId = $this->kategori_user_model->getPrimaryKeyMax();
			$new_id_kategori_user = $maxId->max + 1;

			$data = array(
				'id_kategori_user' => $new_id_kategori_user,
				'nama_kategori_user' => $this->input->post('NAMA_KATEGORI_USER'),
				'keterangan' => $this->input->post('KETERANGAN')
			);
			$query = $this->kategori_user_model->insert($data);
			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1)."/edit/".$new_id_kategori_user);
		}

		echo(json_encode($status));
	}
	public function edit($IdPrimaryKey){
		$where = array('id_kategori_user' => $IdPrimaryKey);
		$orderBy = 'urutan_menu';
		$this->oldData = $this->kategori_user_model->getData($where);
		if(!$this->oldData){
			redirect($this->uri->segment(1));
		}
		$this->load->model('menu_model');
		$this->load->model('t_hak_akses_model');
		$this->checkboxMenu = "<div class='col-sm-9 col-sm-offset-3'><table class='table table-bordered' width='100%'><thead><tr><td align='center'><div class='checkbox'><label><input type='checkbox' id='checkAllDelete' onclick='checkAllDeleteButton()'> &nbsp;Nama Menu</label></div> </td><td>Tambah</td><td>Ubah</td><td>Hapus</td></tr></thead><tbody>";
		$whereMenuSatu = array('id_parent' => '0');
		foreach( $this->menu_model->showData($whereMenuSatu,'',$orderBy) as $menuSatu){
			$whereDataSatu = array('id_kategori_user' => $IdPrimaryKey, 'id_menu' => $menuSatu->id_menu);
			$dataSatu = $this->t_hak_akses_model->getData($whereDataSatu);

			if($dataSatu){
				$aktifMenuSatu = "checked";
				if($dataSatu->add_button == 'Y'){
					$addAktifSatu = "checked";
				}
				else{
					$addAktifSatu = "";
				}
				if($dataSatu->edit_button == 'Y'){
					$editAktifSatu = "checked";
				}
				else{
					$editAktifSatu = "";
				}
				if($dataSatu->delete_button == 'Y'){
					$deleteAktifSatu = "checked";
				}
				else{
					$deleteAktifSatu = "";
				}
			}
			else{
				$aktifMenuSatu = "";
				$addAktifSatu = "";
				$editAktifSatu = "";
				$deleteAktifSatu = "";
			}


			$this->checkboxMenu.= "<tr><td><div class='checkbox'><label><input ".$aktifMenuSatu." type='checkbox' value='".$menuSatu->id_menu."' name='id_menu[]'>".$menuSatu->nama_menu."</label></div></td>";
			if($menuSatu->link_menu!=""){
				if($menuSatu->add_button == "Y"){
					$this->checkboxMenu.= "<td align='center'><input ".$addAktifSatu." type='checkbox' name='add_".$menuSatu->id_menu."' value='Y'></td>";
				}
				else{
					$this->checkboxMenu.= "<td></td>";
				}
				if($menuSatu->edit_button == "Y"){
					$this->checkboxMenu.= "<td align='center'><input ".$editAktifSatu." type='checkbox' name='edit_".$menuSatu->id_menu."' value='Y'></td>";
				}
				else{
					$this->checkboxMenu.= "<td></td>";
				}
				if($menuSatu->delete_button == "Y"){
					$this->checkboxMenu.= "<td align='center'><input ".$deleteAktifSatu." type='checkbox' name='delete_".$menuSatu->id_menu."' value='Y'></td>";
				}
				else{
					$this->checkboxMenu.= "<td></td>";
				}

			}
			else{
				$this->checkboxMenu.="<td colspan='3'></td>";
			}

			$this->checkboxMenu.= "</tr>";

			////////////////////// ---> Menu Dua <---------- ////////////////
			$whereMenuDua = array('id_parent' => $menuSatu->id_menu , 'aktif_menu' => 'Y');
			foreach( $this->menu_model->showData($whereMenuDua,'',$orderBy) as $menuDua){
				$whereDataDua = array('id_kategori_user' => $IdPrimaryKey, 'id_menu' => $menuDua->id_menu);
				$dataDua = $this->t_hak_akses_model->getData($whereDataDua);

				if($dataDua){
					$aktifMenuDua = "checked";
					if($dataDua->add_button == 'Y'){
						$addAktifDua = "checked";
					}
					else{
						$addAktifDua = "";
					}
					if($dataDua->edit_button == 'Y'){
						$editAktifDua = "checked";
					}
					else{
						$editAktifDua = "";
					}
					if($dataDua->delete_button == 'Y'){
						$deleteAktifDua = "checked";
					}
					else{
						$deleteAktifDua = "";
					}
				}
				else{
					$aktifMenuDua = "";
					$addAktifDua = "";
					$editAktifDua = "";
					$deleteAktifDua = "";
				}

				$this->checkboxMenu.= "<tr><td><div class='col-sm-6 col-sm-offset-1'><div class='checkbox'><label><input type='checkbox' ".$aktifMenuDua." value='".$menuDua->id_menu."' name='id_menu[]'>".$menuDua->nama_menu."</label></div></div></td>";
				if($menuDua->link_menu!=""){
					if($menuDua->add_button == "Y"){
						$this->checkboxMenu.= "<td align='center'><input ".$addAktifDua." type='checkbox' name='add_".$menuDua->id_menu."' value='Y'></td>";
					}
					else{
						$this->checkboxMenu.= "<td></td>";
					}
					if($menuDua->edit_button == "Y"){
						$this->checkboxMenu.= "<td align='center'><input ".$editAktifDua." type='checkbox' name='edit_".$menuDua->id_menu."' value='Y'></td>";
					}
					else{
						$this->checkboxMenu.= "<td></td>";
					}
					if($menuDua->delete_button == "Y"){
						$this->checkboxMenu.= "<td align='center'><input type='checkbox' ".$deleteAktifDua." name='delete_".$menuDua->id_menu."' value='Y'></td>";
					}
					else{
						$this->checkboxMenu.= "<td></td>";
					}

				}
				else{
					$this->checkboxMenu.="<td colspan='3'></td>";
				}

				$this->checkboxMenu.= "</tr>";

				//////////////////--> Menu Tiga <--- ///////////////////
				$whereMenuTiga = array('id_parent' => $menuDua->id_menu);
				foreach( $this->menu_model->showData($whereMenuTiga) as $menuTiga){

					$whereDataTiga = array('id_kategori_user' => $IdPrimaryKey, 'id_menu' => $menuTiga->id_menu);
					$dataTiga = $this->t_hak_akses_model->getData($whereDataTiga);



					if($dataTiga){
						$aktifMenuTiga = "checked";
						if($dataTiga->add_button == 'Y'){
							$addAktifTiga = "checked";
						}
						else{
							$addAktifTiga = "";
						}
						if($dataTiga->edit_button == 'Y'){
							$editAktifTiga = "checked";
						}
						else{
							$editAktifTiga = "";
						}
						if($dataTiga->delete_button == 'Y'){
							$deleteAktifTiga = "checked";
						}
						else{
							$deleteAktifTiga = "";
						}
					}
					else{
						$aktifMenuTiga = "";
						$addAktifTiga = "";
						$editAktifTiga = "";
						$deleteAktifTiga = "";
					}

					$this->checkboxMenu.= "<tr><td><div class='col-sm-6 col-sm-offset-2'><div class='checkbox'><label><input type='checkbox' ".$aktifMenuTiga." value='".$menuTiga->id_menu."' name='id_menu[]'>".$menuTiga->nama_menu."</label></div></div></td>";
					if($menuTiga->link_menu!=""){
						if($menuTiga->add_button == "Y"){
							$this->checkboxMenu.= "<td align='center'><input type='checkbox' name='add_".$menuTiga->id_menu."' ".$addAktifTiga." value='Y'></td>";
						}
						else{
							$this->checkboxMenu.= "<td></td>";
						}
						if($menuTiga->edit_button == "Y"){
							$this->checkboxMenu.= "<td align='center'><input type='checkbox' name='edit_".$menuTiga->id_menu."' ".$editAktifTiga." value='Y'></td>";
						}
						else{
							$this->checkboxMenu.= "<td></td>";
						}
						if($menuTiga->delete_button == "Y"){
							$this->checkboxMenu.= "<td align='center'><input type='checkbox' name='delete_".$menuTiga->id_menu."' ".$deleteAktifTiga." value='Y'></td>";
						}
						else{
							$this->checkboxMenu.= "<td></td>";
						}

					}
					else{
						$this->checkboxMenu.="<td colspan='3'></td>";
					}

					$this->checkboxMenu.= "</tr>";


					/**
					$whereMenuEmpat = array('id_parent' => $menuTiga->ID_MENU);
					foreach( $this->menu_model->showData($whereMenuEmpat) as $menuEmpat){
						$whereDataEmpat = array('id_kategori_user' => $IdPrimaryKey, 'id_menu' => $menuEmpat->ID_MENU);
						$dataEmpat = $this->t_hak_akses_model->getData($whereDataEmpat);

						if($dataEmpat){
							$aktifMenuEmpat = "checked";
							if($dataEmpat->ADD_BUTTON == 'Y'){
								$addAktifEmpat = "checked";
							}
							else{
								$addAktifEmpat = "";
							}
							if($dataEmpat->EDIT_BUTTON == 'Y'){
								$editAktifEmpat = "checked";
							}
							else{
								$editAktifEmpat = "";
							}
							if($dataEmpat->DELETE_BUTTON == 'Y'){
								$deleteAktifEmpat = "checked";
							}
							else{
								$deleteAktifEmpat = "";
							}
						}
						else{
							$aktifMenuEmpat = "";
							$addAktifEmpat = "";
							$editAktifEmpat = "";
							$deleteAktifEmpat = "";
						}

						$this->checkboxMenu.= "<tr><td><div class='col-sm-6 col-sm-offset-3'><div class='checkbox'><label><input type='checkbox' ".$aktifMenuEmpat." value='".$menuEmpat->ID_MENU."' name='id_menu[]'>".$menuEmpat->NAMA_MENU."</label></div></div></td>";
						if($menuEmpat->LINK_MENU!=""){
							if($menuEmpat->ADD_BUTTON == "Y"){
								$this->checkboxMenu.= "<td align='center'><input type='checkbox' name='add_".$menuEmpat->ID_MENU."' ".$addAktifEmpat." value='Y'></td>";
							}
							else{
								$this->checkboxMenu.= "<td></td>";
							}
							if($menuEmpat->EDIT_BUTTON == "Y"){
								$this->checkboxMenu.= "<td align='center'><input type='checkbox' name='edit_".$menuEmpat->ID_MENU."'  ".$editAktifEmpat." value='Y'></td>";
							}
							else{
								$this->checkboxMenu.= "<td></td>";
							}
							if($menuEmpat->DELETE_BUTTON == "Y"){
								$this->checkboxMenu.= "<td align='center'><input type='checkbox' name='delete_".$menuEmpat->ID_MENU."' ".$deleteAktifEmpat." value='Y'></td>";
							}
							else{
								$this->checkboxMenu.= "<td></td>";
							}

						}
						else{
							$this->checkboxMenu.="<td colspan='3'></td>";
						}

						$this->checkboxMenu.= "</tr>";
					}**/
				}
			}
		}
		$this->checkboxMenu .= "</tbody></table></div>";

		$this->template_view->load_view('setting_role/setting_role_edit_view');
	}
	public function edit_data(){
		$this->form_validation->set_rules('ID_KATEGORI_USER', '', 'trim|required');
		$this->form_validation->set_rules('NAMA_KATEGORI_USER', '', 'trim|required');
		//echo $this->input->post('ID_KATEGORI_USER');
		if ($this->form_validation->run() == FALSE)	{
			$status = array('status' => FALSE, 'pesan' => 'Gagal menyimpan Data, pastikan telah mengisi semua inputan.');
		}
		else{
			$data = array(
				'nama_kategori_user' => $this->input->post('NAMA_KATEGORI_USER'),
				'keterangan' => $this->input->post('KETERANGAN')
			);
			$where = array('id_kategori_user' => $this->input->post('ID_KATEGORI_USER'));
			$query = $this->kategori_user_model->update($where,$data);

			//echo $this->db->last_query();

			$this->db->query("delete from t_hak_akses where id_kategori_user='".$this->input->post('ID_KATEGORI_USER')."'");
			foreach($this->input->post('id_menu') as $id_menu){
				$insert = "
				insert into
					t_hak_akses
					( 	id_kategori_user,id_menu,add_button,edit_button,delete_button  )
					values
					('".$this->input->post('ID_KATEGORI_USER')."','".$id_menu."','".$this->input->post('add_'.$id_menu)."','".$this->input->post('edit_'.$id_menu)."','".$this->input->post('delete_'.$id_menu)."')
				";
				$this->db->query($insert);
			}

			$status = array('status' => true , 'redirect_link' => base_url()."".$this->uri->segment(1));
		}

		echo(json_encode($status));
	}
	public function delete($IdPrimaryKey){
		$where = array('id_kategori_user' => $IdPrimaryKey);
		$this->oldData = $this->kategori_user_model->delete($where);

		redirect(base_url()."".$this->uri->segment(1));
	}

}
