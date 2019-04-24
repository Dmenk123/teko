<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// use \LINE\LINEBot;
// use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
// use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;
// use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
// use \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
// use \LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
// use \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;

// include APPPATH . 'third_party/line_bot_sdk/src/LINEBot.php';
// include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/Constant/Meta.php';
// include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/Constant/MessageType.php';
// include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/HTTPClient.php';
// include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/HTTPClient/Curl.php';
// include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/HTTPClient/CurlHTTPClient.php';
// include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/MessageBuilder.php';
// include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/MessageBuilder/TextMessageBuilder.php';
// include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/Response.php';

class Disposisi extends CI_Controller {

	function __construct()
	{
    	parent::__construct();
    	$this->load->model('model_app');
    	$this->load->library('custom_function');
	    $this->load->library('alur');

	    if($this->session->userdata('logged_in') != TRUE ){
			/*$newdata = array('url'=> current_url() );
			$this->session->set_userdata($newdata);*/
				if (!$this->input->is_ajax_request()) {
		        redirect('login');
				}
				else {
					$status['STATUS']   = "session";
					echo json_encode($status);
					die;
				}
			}
	}

	function add_disposisi(){
		//BUG FIX DARI ALI
		$dari             = $this->input->post('dari');
		//END BUG FIX DARI ALI

		$kepada           = $this->input->post('kepada');
		$unit             = $this->input->post('unit');
		$disposisi        = htmlspecialchars($this->input->post('disposisi'));
		$id_detail        = $this->input->post('id_detail');
		$id_disposisi     = $this->input->post('id_disposisi');
		$id_log_disposisi = $this->input->post('id_log_disposisi');
		$sk_sm            = $this->input->post('sk_sm');
		$laporan          = $this->input->post('laporan');
		$deadline         = ($this->input->post('deadline'))?$this->input->post('deadline'):null;
		$kordinator       = $this->input->post('unit_kordinator');
		$id_level 		  = $this->session->userdata('id_level');
		$id_unit  		  = $this->session->userdata('id_unit');
        if($laporan==null){
        	$laporan=3;
        }
        if($deadline!=null){
        	$tgldeadline      = date('d-M-Y', strtotime('+'.$deadline.' days'));
        }
		$kategori_serdesk = $this->input->post('kategori');
		$sub_kat_serdesk  = $this->input->post('sub_kategori');
		$aplikasi_id	  = $this->input->post('nama_aplikasi');

		if($id_level==19 && $id_unit == 105010100){
			$data         = array('id_detail_sm' => $id_detail);
			$query = "
				SELECT log_dispos.id_log_disposisi, log_dispos.flag, log_dispos.id_level, log_dispos.id_unit, log_dispos.status, log_dispos.id_detail_sm, log_dispos.status_dari, dsm.skpd, dsm.id_detail, dsm.id_surat_masuk, dsm.agenda, dsm.tgl_surat, muk_skpd.id_struktur, log_dispos.kategori AS kategori_serdesk, log_dispos.sub_kategori AS sub_kategori_serdesk
				FROM t_log_disposisi log_dispos
				LEFT JOIN detail_surat_masuk dsm ON log_dispos.id_detail_sm = dsm.id_detail
				LEFT JOIN m_user mu ON log_dispos.tujuan_user = mu.id_user
				JOIN m_unit_kerja muk ON mu.id_unit = muk.id_unit
				JOIN m_unit_kerja muk_skpd ON ((concat(substr((muk.id_unit)::text, 0, 4), '000000')) = (muk_skpd.id_unit)::text)
				WHERE log_dispos.id_detail_sm = '$id_detail' AND log_dispos.id_unit = '$id_unit' AND log_dispos.status <> 20
	    ";
			$surat_masuk = $this->model_app->eksekusi($query)->row();
		}else{
			$data         = array('id_detail_sm' => $id_detail);
			$query = "
				SELECT log_dispos.id_log_disposisi, log_dispos.flag, log_dispos.id_level, log_dispos.id_unit, log_dispos.status, log_dispos.id_detail_sm, log_dispos.status_dari, dsm.skpd, dsm.id_detail, dsm.id_surat_masuk, dsm.agenda, dsm.tgl_surat, muk_skpd.id_struktur, log_dispos.kategori AS kategori_serdesk, log_dispos.sub_kategori AS sub_kategori_serdesk
				FROM t_log_disposisi log_dispos
				LEFT JOIN detail_surat_masuk dsm ON log_dispos.id_detail_sm = dsm.id_detail
				LEFT JOIN m_user mu ON log_dispos.tujuan_user = mu.id_user
				JOIN m_unit_kerja muk ON mu.id_unit = muk.id_unit
				JOIN m_unit_kerja muk_skpd ON ((concat(substr((muk.id_unit)::text, 0, 4), '000000')) = (muk_skpd.id_unit)::text)
				WHERE log_dispos.id_detail_sm = '$id_detail' AND log_dispos.id_unit = '$id_unit' AND log_dispos.status <> 20
	    ";
			$surat_masuk = $this->model_app->eksekusi($query)->row();
		}
		$cek_null = $this->custom_function->isNull($surat_masuk, 'surat tidak ditemukan', 'add_disposisi 49');
		if ($cek_null['BOOLEAN']) {
			echo json_encode($cek_null);
			return;
		}

		if($kategori_serdesk==NULL && $sub_kat_serdesk==NULL){
			$kategori_serdesk = $surat_masuk->kategori_serdesk;
			$sub_kat_serdesk = $surat_masuk->sub_kategori_serdesk;
		}

		$data_where   = array('id_struktur'=>$surat_masuk->id_struktur, 'id_level'=>$kepada, 'status_sk_sm'=> 2);
		$jenis_dispos = $this->model_app->getSelectedData('m_jenis_disposisi', $data_where)->row();
		if($jenis_dispos!=NULL){
			if($jenis_dispos->flag==1){
				$data_where   = array('id_struktur'=>$surat_masuk->id_struktur, 'id_level'=>$kepada, 'status_sk_sm'=> 2,'next_id_jenis_disposisi'=>NULL);
				$jenis_dispos = $this->model_app->getSelectedData('m_jenis_disposisi', $data_where)->row();
			}
		}
		$this->db->trans_start();
		$size = 0;
		if ($kepada == 0) { // JIKA DILAKSANAKAN SENDIRI MAKA DISPOSISI SUDAH BERAKHIR
			$data['kategori']     = $kategori_serdesk;
			$data['sub_kategori'] = $sub_kat_serdesk;
			$data['aplikasi_id']  = $aplikasi_id;

			$data['isi_disposisi'] = $disposisi;
			$data['id_detail_sm']  = $id_detail;
			// $data['ID_DISPOSISI']  = $id_disposisi;
			$data['tujuan_user']   = $this->session->userdata('id');
			$data['id_unit']       = $this->session->userdata('id_unit');
			$data['dari_user']     = $this->session->userdata('id');
			$data['flag']          = $surat_masuk->flag;
			$data['status']        = 20;
			$data['status_dari']   = $surat_masuk->id_log_disposisi;
			$data['id_level']      = $this->session->userdata('id_level');
			$data['pelaksana']     = $this->session->userdata('id');
            $data['deadline']      = $deadline;
            $data['id_status']     = 4;
            if($data['deadline']!=null){
            	$data['tgl_deadline']  = $tgldeadline;
        	}

			$data2 = array('tgl_dispos' => "'".date('Y-m-d H:i:s')."'");

		// 	$query = "
	    //   SELECT id_log_disposisi, flag, id_level, id_unit, status, id_detail_sm, status_dari
	    //   FROM t_log_disposisi
	    //   WHERE id_detail_sm = '$id_detail' AND dari_user = '".$this->session->userdata('id')."' AND tujuan_user = '".$this->session->userdata('id')."' AND status <> 20
	    // ";
			// $get_dispos = $this->model_app->eksekusi($query)->row();
			$get_dispos = true;
			if ($get_dispos) {
				$sendiri = true;
				if ($surat_masuk->flag == 1) {
					$data['peminta_laporan'] = $this->insert_laporan($id_detail, $surat_masuk->id_log_disposisi);
				}

				$data_where = array('id_detail_sm' => $id_detail,'id_status'=>2);
				$jumlah_surat_baru = $this->model_app->count_data('t_log_disposisi', $data_where);

				if($jumlah_surat_baru==0){
					$cekdin	    = substr($this->session->userdata('id_dinas'), 0, 3);
					$din 		= $cekdin."%";
					$data_where = array('id_detail_sm' => $id_detail, 'cast(id_unit as text) LIKE' => $din );
					$status_baru = array('id_status' => 4);
					$this->model_app->updateData('t_log_disposisi', $data_where, $status_baru);
				}

				$this->model_app->insertData('t_disposisi', $data, $data2);

				$this->delete_disposisi_before($surat_masuk->status_dari, $sendiri);

				if($this->input->post('disposisi_lagi') <> null) {
					$data_where = array('id_log_disposisi' => $this->input->post('disposisi_lagi'));
				}
				else {
					$data_where = array('id_log_disposisi' => $this->input->post('id_log_disposisi'));
				}
				$status_baru = array('id_status' => 3);
				$this->model_app->updateData('t_log_disposisi', $data_where, $status_baru);

				$status['STATUS']   = "berhasil";
				$status['MESSAGE']  = "Berhasil Menambah Disposisi";
				$status['ID_LEVEL'] = $kepada;

				if ($surat_masuk->id_kategori == 22 && $this->session->userdata('id_dinas') == 539000000) {
					$status_json   = $this->send_serdesk($data, $dari);
					// print_r($status_json); exit;
					$id_permintaan = $status_json['id_permintaan'];
					$data          = array('id_permintaan' => $id_permintaan);
					$datawhere     = array('id_detail' => $id_detail);
					$this->model_app->updateData('detail_surat_masuk', $datawhere, $data);
					$status['MESSAGE']  = $status['MESSAGE']." ".$status_json['MESSAGE'];
					$status['REDIRECT'] = "surat/inbox";
				}

			} else{
				$status['STATUS']   = "warning";
				$status['MESSAGE']  = "Anda Tidak Dapat Mendisposisikan pada Orang yang sama 2 kali !";
				$status['ID_LEVEL'] = $kepada;
			}

		} else{
			if(!empty($unit) && $unit != "null"){ // JIKA UNIT TIDAK KOSONG
				if (!is_array($unit)) {
					$unit = explode(',', $unit);
				}
				$arr_seksi = array(3,17);
				if($surat_masuk->id_struktur==50 || $surat_masuk->id_struktur==100 || $surat_masuk->id_struktur==300){
					if(in_array($this->session->userdata('id_level'), $arr_seksi)){
						$cekdin	    = substr($this->session->userdata('id_unit'), 0, 7);
					}else{
						$cekdin	    = substr($this->session->userdata('id_unit'), 0, 6);
					}
				}else if($surat_masuk->id_struktur==60 || $surat_masuk->id_struktur==41  || $surat_masuk->id_struktur==70){
					$cekdin	    = substr($this->session->userdata('id_unit'), 0, 7);
				}
				$din 		= $cekdin."%";

				$is_acc = $this->input->post('is_acc');

				if($is_acc == 1) {
					$id_level_acc  = $this->session->userdata('id_level');
					$id_unit_acc   = $this->session->userdata('id_unit');
					$level_opd_acc = 1;
					if($id_level_acc == 8) {
						$level_opd_acc = 7;
					}
					else if($id_level_acc == 11) {
						$level_opd_acc = 10;
					}
					else if($id_level_acc == 27) {
						$level_opd_acc = 23;
					}
					$idDinas = $this->custom_function->next_disposisi($level_opd_acc, $id_unit_acc, $id_level_acc);

					$query = "
						SELECT
							mu.*,
							CASE
			            WHEN (mu.id_level = ANY (ARRAY[1, 2, 3, 9, 19, 17, 23, 10, 7, 25])) THEN (concat('KEPALA ', muk.nama_unit))::character varying
			            WHEN (mu.id_level = ANY (ARRAY[5, 13, 14])) THEN l.nama_level
			            WHEN (mu.id_level = ANY (ARRAY[15, 16, 24, 27])) THEN muk.nama_unit
			            WHEN (mu.id_level = ANY (ARRAY[4, 6])) THEN (concat(mu.nama, '(Staff)'))::character varying
			            ELSE mu.nama
			        END AS nama_next
						FROM
							m_user mu
							JOIN m_unit_kerja muk ON mu.id_unit = muk.id_unit
							JOIN m_level l ON mu.id_level = l.id_level
						WHERE
							mu.id_level = '$level_opd_acc'
							AND mu.status = 1
							AND cast(mu.id_unit as text) LIKE '$idDinas%' ESCAPE '!'
					";
					$id_user_opd_acc = $this->model_app->eksekusi($query)->row_array();

					if (!in_array($id_user_opd_acc['id_user'], $unit)) {
						$query = "
							SELECT log_dispos.id_log_disposisi
							FROM t_log_disposisi log_dispos
				      WHERE log_dispos.id_detail_sm = '$id_detail' AND log_dispos.tujuan_user = '".$id_user_opd_acc['id_user']."'
				    ";
						$cek_mengetahui_acc = $this->model_app->eksekusi($query)->row();

						if (empty($cek_mengetahui_acc)) {
							$data_acc['kategori']     = $kategori_serdesk;
							$data_acc['sub_kategori'] = $sub_kat_serdesk;
							$data_acc['aplikasi_id']  = $aplikasi_id;

							$data_acc['isi_disposisi'] = ucwords($id_user_opd_acc['nama_next'])." Mengetahui";
							// $data_acc['ID_DISPOSISI']  = $id_disposisi;
							$data_acc['id_detail_sm']  = $id_detail;
							$data_acc['flag']          = 0;
							$data_acc['tujuan_user']   = $id_user_opd_acc['id_user'];
							$data_acc['status']        = $jenis_dispos->level_disposisi;
							$data_acc['status_dari']   = $surat_masuk->id_log_disposisi;
							$data_acc['id_unit']       = $id_user_opd_acc['id_unit'];
							$data_acc['dari_user']     = $this->session->userdata('id');
							$data_acc['id_level']      = $id_user_opd_acc['id_level'];
	            //$data_acc['deadline']      = ($deadline)?$deadline:null;
	            $data_acc['id_status']     = 1;
	            // if($data_acc['deadline']!=null){
	    				// 	$data_acc['tgl_deadline']  = $tgldeadline;
							// }
							// $data_acc['peminta_laporan'] = $this->insert_laporan($id_detail, $surat_masuk->id_log_disposisi);

							$data2_acc = array('tgl_dispos' => "'".date('Y-m-d H:i:s')."'");

							$this->model_app->insertData('t_disposisi', $data_acc, $data2_acc);
						}
					}
				}

				for ($i=0; $i < count($unit); $i++) {
					$query = "
						SELECT mu.id_user, mu.id_level, mu.id_unit, muk.nama_unit, muk.id_struktur as struktur_dinas
						FROM m_user mu
						LEFT JOIN m_unit_kerja muk ON mu.id_unit = muk.id_unit
						WHERE mu.id_user = '".$unit[$i]."' AND mu.status = 1
					";
					$user_data = $this->model_app->eksekusi($query)->row();
					$kepada 	= $user_data->id_level;

					$arr_status = array(13,16);
					if(in_array($surat_masuk->status, $arr_status)){
						$this->cek_unit_user($this->session->userdata('id'), $user_data->id_user, $this->session->userdata('id_level'), $surat_masuk,$deadline);
					}
					$data['kategori']     = $kategori_serdesk;
					$data['sub_kategori'] = $sub_kat_serdesk;
					$data['aplikasi_id']  = $aplikasi_id;

					$data['isi_disposisi'] = $disposisi;
					// $data['ID_DISPOSISI']  = $id_disposisi;
					$data['id_detail_sm']  = $id_detail;
					$data['flag']          = $laporan;
					$data['tujuan_user']   = $user_data->id_user;
					$data['status']        = $jenis_dispos->level_disposisi;
					$data['status_dari']   = $surat_masuk->id_log_disposisi;
					$data['id_unit']       = $user_data->id_unit;
					$data['dari_user']     = $this->session->userdata('id');
					$data['id_level']      = $user_data->id_level;
                    $data['deadline']      = ($deadline)?$deadline:null;
                    $data['id_status']     = 2;
                    if($data['deadline']!=null){
            				$data['tgl_deadline']  = $tgldeadline;
        			}
        			$level_umum = array(13,14,15,16);
					if (in_array($id_level, $level_umum)) {
						$data['kordinator']      = $kordinator;
					}

					$data2 = array('tgl_dispos' => "'".date('Y-m-d H:i:s')."'");
					if($is_acc <> 1) {
						$size = $this->alur->jump_disposisi($this->session->userdata('id_level'), $jenis_dispos->level_disposisi, $user_data->id_level, $user_data->id_user, $surat_masuk);
					}
					if ($laporan == 1) {
						$data['peminta_laporan'] = $this->insert_laporan($id_detail, $surat_masuk->id_log_disposisi);
						// $this->insert_laporan($id_detail);
					}
					$ceksekre	    = substr($surat_masuk->skpd, 0, 3)."000000";
					$arr_sekda 		= array(100000000,101000000,102000000,103000000,104000000,105000000);

					if($this->input->post('disposisi_lagi') <> null) {
						$data_where = array('id_log_disposisi' => $this->input->post('disposisi_lagi'));
					}
					else {
						$data_where = array('id_log_disposisi' => $this->input->post('id_log_disposisi'));
					}
					$status_baru = array('id_status' => 3);
					$this->model_app->updateData('t_log_disposisi', $data_where, $status_baru);

					if(in_array($ceksekre, $arr_sekda) && $surat_masuk->id_unit == 105010100){
							$this->delete_disposisi_bagian_before($surat_masuk->id_detail,$surat_masuk->id_level);
						}
					if ($surat_masuk->status > 3) $this->delete_disposisi_before($surat_masuk->status_dari);

					$this->model_app->insertData('t_disposisi', $data, $data2);
					//$this->alur->send_notif_disposisi($user_data->id_user, $id_detail,"baru");

					if (stripos(htmlspecialchars($this->input->post('disposisi')), "outreach") !== false) {
						$query = "
				      SELECT log_dispos.isi_disposisi, log_dispos.tgl_log_dispos, m_tujuan.nama_unit AS dinas_tujuan, m_tujuan.id_unit AS id_dinas_tujuan
				      FROM t_log_disposisi log_dispos
							JOIN m_user mu ON log_dispos.dari_user = mu.id_user::varchar(250)
							LEFT JOIN LATERAL (
								SELECT
									CASE
										WHEN c_tujuan.id_struktur = ANY (ARRAY[50, 100, 60, 300]) THEN concat(substr(log_dispos.id_unit::text, 0, 4), '000000')::bpchar
										WHEN c_tujuan.id_struktur = 40 THEN concat(substr(log_dispos.id_unit::text, 0, 7), '000')::bpchar
										ELSE log_dispos.id_unit::character(10)
									END AS skpd1
								FROM
									m_unit_kerja c_tujuan
								WHERE
									c_tujuan.id_unit::text = concat(substr(log_dispos.id_unit::text, 0, 4), '000000')
							) n_tujuan ON TRUE
							LEFT JOIN m_unit_kerja m_tujuan ON m_tujuan.id_unit::text = n_tujuan.skpd1::text
				      WHERE log_dispos.id_detail_sm = '".$surat_masuk->id_detail."'
							AND mu.id_unit = '101000000'
							ORDER BY log_dispos.tgl_log_dispos asc
				    ";
						$disposisi_wali = $this->model_app->eksekusi($query)->result();

						if($disposisi_wali) {
							$unit_tujuan_kesra = array();
							for ($i=0; $i < count($disposisi_wali); $i++) {
								if (!in_array($disposisi_wali[$i]->dinas_tujuan, $unit_tujuan_kesra)) {
									$unit_tujuan_kesra[] = $disposisi_wali[$i]->dinas_tujuan;
								}
							}

							$query = "
					      SELECT *
					      FROM esurat_kesra
					      WHERE id_surat_masuk = '".$surat_masuk->id_surat_masuk."'
					    ";
							$surat_kesra = $this->model_app->eksekusi($query)->row();
							$data_kesra = array();
							if($surat_kesra == null) {
								$data_kesra['id_surat_masuk']  			   = $surat_masuk->id_surat_masuk;
								$data_kesra['id_detail_sm']    			   = $surat_masuk->id_detail;
								$data_kesra['no_surat_masuk']  			   = $this->input->post('no_surat_kesra');;
								$data_kesra['no_agenda']       			   = $this->input->post('no_agenda_kesra');
								$data_kesra['agenda']          			   = $surat_masuk->agenda;
								$data_kesra['tgl_surat']        			 = $surat_masuk->tgl_surat;
								$data_kesra['dari']             			 = $this->input->post('dari_kesra');
								$data_kesra['perihal']          			 = $this->input->post('perihal_kesra');
								$data_kesra['sifat']            			 = $this->input->post('sifat_kesra');;
								$data_kesra['tgl_disposisi']    			 = $disposisi_wali[0]->tgl_log_dispos;
								$data_kesra['isi_disposisi']    			 = $disposisi_wali[0]->isi_disposisi;
								$data_kesra['tujuan_disposisi'] 			 = json_encode($unit_tujuan_kesra);
	              $data_kesra['file_surat']       			 = $this->input->post('file_surat_kesra');
	              $data_kesra['file_lampiran']   				 = $this->input->post('file_lampiran_surat_kesra');
								$data_kesra['file_lampiran_pendukung'] = $this->input->post('file_lampiran_pendukung_kesra');
	              $data_kesra['flag']            				 = 0;

								$this->model_app->insertData('esurat_kesra', $data_kesra);
							}
						}
					}

					$status['STATUS']   = "berhasil";
					$status['MESSAGE']  = "Berhasil Menambah Disposisi";
					$status['ID_LEVEL'] = $kepada;
				}
			} else{ // JIKA UNIT KOSONG
				$status['STATUS']   = "gagal";
				$status['MESSAGE']  = "Tujuan Unit Tidak Boleh Kosong !";
				$status['ID_LEVEL'] = $kepada;
			}
		}
		echo json_encode($status);

		$this->db->trans_complete();
	}

	function add_disposisi_sekretariat(){
		// ini_set('display_errors', 1);
		// if($this->input->post('id_detail') == 23820) {
		// 	die;
		// }
		//BUG FIX DARI ALI
		$dari             = $this->input->post('dari');
		//END BUG FIX DARI ALI

		$kepada           = $this->input->post('kepada');
		$unit             = $this->input->post('unit');

		$disposisi        = htmlspecialchars($this->input->post('disposisi'));
		$id_detail        = $this->input->post('id_detail');
		$id_disposisi     = $this->input->post('id_disposisi');
		$id_log_disposisi = $this->input->post('id_log_disposisi');
		$sk_sm            = $this->input->post('sk_sm');
		$laporan          = $this->input->post('laporan');
        $deadline         = ($this->input->post('deadline'))?$this->input->post('deadline'):null;
        $kordinator       = ($this->input->post('unit_kordinator'))?$this->input->post('unit_kordinator'):null;
        $id_level 		  = $this->session->userdata('id_level');
		$id_unit  		  = $this->session->userdata('id_unit');

        if($laporan==0){
        	$laporan=3;
        }
        if($deadline!='null'){
        	$tgldeadline      = date('d-M-Y', strtotime('+'.$deadline.' days'));
        }else{
        	$deadline    = null;
        	$tgldeadline =null;
        }
		$kategori_serdesk = $this->input->post('kategori');
		$sub_kat_serdesk  = $this->input->post('sub_kategori');
		$aplikasi_id	  = $this->input->post('nama_aplikasi');

		//cek apakah surat memang ditujukan pada user yang melakukan disposisi
		if($id_level==19 && $id_unit == 105010100){
			$data         = array('id_detail_sm' => $id_detail);
			$query = "
				SELECT log_dispos.id_log_disposisi, log_dispos.flag, log_dispos.id_level, log_dispos.id_unit, log_dispos.status, log_dispos.id_detail_sm, log_dispos.status_dari, dsm.skpd, dsm.id_detail, dsm.id_surat_masuk, dsm.agenda, dsm.tgl_surat, sm.perihal,
				CASE
						WHEN muk.nama_unit IS NULL THEN dsm.dari
						ELSE muk.nama_unit
				END AS dinas_pengirim
				FROM t_log_disposisi log_dispos
				LEFT JOIN detail_surat_masuk dsm ON log_dispos.id_detail_sm = dsm.id_detail
				LEFT JOIN m_unit_kerja muk ON dsm.dari::text = muk.id_unit::text
				LEFT JOIN t_surat_masuk sm ON dsm.id_surat_masuk = sm.id_surat_masuk
				WHERE log_dispos.id_detail_sm = '$id_detail' AND log_dispos.id_unit = '$id_unit' AND log_dispos.status <> 20
	    ";
			$surat_masuk = $this->model_app->eksekusi($query)->row();
			// $data_where   = array('id_detail' => $id_detail, 'id_unit_tujuan'=>$id_unit);
			// $surat_masuk  = $this->model_app->getSelectedData('v_user_dan_disposisi',$data_where)->row();
		}else{
			$data         = array('id_detail_sm' => $id_detail);
			$query = "
				SELECT log_dispos.id_log_disposisi, log_dispos.flag, log_dispos.id_level, log_dispos.id_unit, log_dispos.status, log_dispos.id_detail_sm, log_dispos.status_dari, dsm.skpd, dsm.id_detail, dsm.id_surat_masuk, dsm.agenda, dsm.tgl_surat, sm.perihal,
				CASE
						WHEN muk.nama_unit IS NULL THEN dsm.dari
						ELSE muk.nama_unit
				END AS dinas_pengirim
				FROM t_log_disposisi log_dispos
				LEFT JOIN detail_surat_masuk dsm ON log_dispos.id_detail_sm = dsm.id_detail
				LEFT JOIN m_unit_kerja muk ON dsm.dari::text = muk.id_unit::text
				LEFT JOIN t_surat_masuk sm ON dsm.id_surat_masuk = sm.id_surat_masuk
				WHERE log_dispos.id_detail_sm = '$id_detail' AND log_dispos.id_unit = '$id_unit' AND log_dispos.status <> 20
	    ";
			$surat_masuk = $this->model_app->eksekusi($query)->row();
			// $data_where   = array('id_detail' => $id_detail, 'id_unit_tujuan'=>$id_unit );
			// $surat_masuk  = $this->model_app->getSelectedData('v_user_dan_disposisi',$data_where)->row();
		}
		$cek_null = $this->custom_function->isNull($surat_masuk, 'surat tidak ditemukan', 'add_disposisi 49');
		if ($cek_null['BOOLEAN']) {
			echo json_encode($cek_null);
			return;
		}
		//end check

		$this->db->trans_start();
		$size = 0;

		//disposisi pada diri sendiri
		if($kepada==0){
			$data['kategori']     = $kategori_serdesk;
			$data['sub_kategori'] = $sub_kat_serdesk;
			$data['aplikasi_id']  = $aplikasi_id;

			$data['isi_disposisi'] = $disposisi;
			$data['id_detail_sm']  = $id_detail;
			// $data['ID_DISPOSISI']  = $id_disposisi;
			$data['tujuan_user']   = $this->session->userdata('id');
			$data['id_unit']       = $this->session->userdata('id_unit');
			$data['dari_user']     = $this->session->userdata('id');
			$data['flag']          = $surat_masuk->flag;
			$data['status']        = 20;
			$data['status_dari']   = $surat_masuk->id_log_disposisi;
			$data['id_level']      = $this->session->userdata('id_level');
			$data['pelaksana']     = $this->session->userdata('id');
            $data['deadline']      = $deadline;
            $data['id_status']     = 4;
            if($data['deadline']!=null){
            	$data['tgl_deadline']  = $tgldeadline;
        	}

			$data2 = array('tgl_dispos' => "'".date('Y-m-d H:i:s')."'");

			// $data_where = array('id_detail' => $id_detail, 'dari_user' => $this->session->userdata('id'), 'id_user_tujuan' => $this->session->userdata('id'));
			// $get_dispos = $this->model_app->getSelectedData('v_user_dan_disposisi', $data_where)->row();
			$query = "
	      SELECT id_log_disposisi, flag, id_level, id_unit, status, id_detail_sm, status_dari
	      FROM t_log_disposisi
	      WHERE id_detail_sm = '$id_detail' AND dari_user = '".$this->session->userdata('id')."' AND tujuan_user = '".$this->session->userdata('id')."' AND status <> 20
	    ";
			$get_dispos = $this->model_app->eksekusi($query)->row();
			// cek apakah sudah pernah disposisi pada diri sendiri , pengecekan tidak muspro !!
			if (empty($get_dispos)) {
				$sendiri = true;
				if ($surat_masuk->flag == 1) {
					$data['peminta_laporan'] = $this->insert_laporan($id_detail, $surat_masuk->id_log_disposisi);
					// $this->insert_laporan($id_detail);
				}
				// print_r($data);exit;
				$data_where = array('id_detail_sm' => $id_detail,'id_status'=>2);
				$jumlah_surat_baru = $this->model_app->count_data('t_log_disposisi', $data_where);
				// print_r($data);exit;
				//if($surat_masuk->ID_STRUKTUR==50 || $surat_masuk->ID_STRUKTUR==100 || $surat_masuk->ID_STRUKTUR==300){
				//	$cekdin	    = substr($this->session->userdata('id_unit'), 0, 6);
				//}else if($surat_masuk->ID_STRUKTUR==60 || $surat_masuk->ID_STRUKTUR==41){
				//	$cekdin	    = substr($this->session->userdata('id_unit'), 0, 7);
				//}else
				if($jumlah_surat_baru==0){
					$cekdin	    = substr($this->session->userdata('id_dinas'), 0, 3);
					$din 		= $cekdin."%";
					// ini untuk apa ?? muspro
					$data_where = array('id_detail_sm' => $id_detail, 'cast(id_unit as text) LIKE' => $din );
					// end ini untuk apa ?? muspro
					$status_baru = array('id_status' => 4);
					$this->model_app->updateData('t_log_disposisi', $data_where, $status_baru);
				}
				// $data_where = array('id_detail_sm' => $id_detail);
				// $status_baru = array('id_status' => 4);
				// $this->model_app->updateData('t_log_disposisi', $data_where, $status_baru);
				$this->model_app->insertData('t_disposisi', $data, $data2);
				$this->delete_disposisi_before($surat_masuk->status_dari, $sendiri);

				$status['STATUS']   = "berhasil";
				$status['MESSAGE']  = "Berhasil Menambah Disposisi";
				$status['ID_LEVEL'] = $kepada;

				if ($surat_masuk->id_kategori == 22 && $this->session->userdata('id_dinas') == 539000000) {
					$status_json   = $this->send_serdesk($data, $dari);
					// print_r($status_json); exit;
					$id_permintaan = $status_json['id_permintaan'];
					$data          = array('id_permintaan' => $id_permintaan);
					$datawhere     = array('id_detail' => $id_detail);
					$this->model_app->updateData('detail_surat_masuk', $datawhere, $data);
					$status['MESSAGE']  = $status['MESSAGE']." ".$status_json['MESSAGE'];
					$status['REDIRECT'] = "surat/inbox";
				}

			} // end cek apakah sudah pernah disposisi pada diri sendiri , pengecekan tidak muspro !!
			else{
				$status['STATUS']   = "warning";
				// $status['MESSAGE']  = "Anda Tidak Dapat Mendisposisikan pada Orang yang sama 2 kali ! 11";
				$status['ID_LEVEL'] = $kepada;
			}
		} //end disposisi pada diri sendiri
		else{
			if(!empty($unit) && $unit != "null"){ // JIKA UNIT TIDAK KOSONG
				if (!is_array($unit)) {
					$unit = explode(',', $unit);
				}

				// untuk koordinator
				// $tetew1 = 0;
				// $tetew2 = 0;
				// $tetew3 = 0;
				// $tetew4 = 0;
				//
				// $user_tetew1 = 0;
				// $user_tetew2 = 0;
				// $user_tetew3 = 0;
				// $user_tetew4 = 0;
				//
				// $cekuser_tetew = 1;
				//
				// $unit_tetew3 = 0;
				//
				// $level_tetew = $this->session->userdata('id_level');
				// end untuk koordinator

				for ($i=0; $i < count($unit); $i++) {
					// $data_where = array('id_user'=>$unit[$i]);
					// $user_data  = $this->model_app->getSelectedData('v_user',$data_where)->row();
					$query = "
						SELECT mu.id_user, mu.id_level, mu.id_unit, mu.id_line, muk.nama_unit, muk.id_struktur as struktur_dinas
						FROM m_user mu
						LEFT JOIN m_unit_kerja muk ON mu.id_unit = muk.id_unit
						WHERE mu.id_user = '".$unit[$i]."' AND mu.status = 1
					";
					$user_data = $this->model_app->eksekusi($query)->row();
					$kepada 	= $user_data->id_level;

					/** JIKA DISPOSISI KE KELURAHAN */
					if(strtolower(substr($user_data->nama_unit, 0, 9)) == 'kelurahan') {
						$data_where = ['id_unit' => $user_data->id_unit];
						$unit_kerja = $this->model_app->getSelectedData('m_unit_kerja', $data_where)->row();

						$user_data->struktur_dinas = $unit_kerja->id_struktur;
					}
					/** END */

					$data_where   = array('id_struktur'=>$user_data->struktur_dinas, 'id_level'=>$kepada, 'status_sk_sm'=> 2);
					$jenis_dispos = $this->model_app->getSelectedData('m_jenis_disposisi', $data_where)->row();
					if($jenis_dispos!=NULL){
						if($jenis_dispos->flag==1){
							$data_where   = array('id_struktur'=>$user_data->struktur_dinas, 'id_level'=>$kepada, 'status_sk_sm'=> 2,'next_id_jenis_disposisi'=>NULL);
							// $data_where   = array('ID_STRUKTUR'=>$surat_masuk->ID_STRUKTUR, 'ID_LEVEL'=>$kepada, 'STATUS_SK_SM'=> 2,'NEXT_ID_JENIS_DISPOSISI'=>NULL);
							$jenis_dispos = $this->model_app->getSelectedData('m_jenis_disposisi', $data_where)->row();
						}
					}

					$arr_status = array(13,16);
					if(in_array($surat_masuk->status, $arr_status)){
						$this->cek_unit_user($this->session->userdata('id'), $user_data->id_user, $this->session->userdata('id_level'), $surat_masuk,$deadline);
					}
					$data['kategori']     = $kategori_serdesk;
					$data['sub_kategori'] = $sub_kat_serdesk;
					$data['aplikasi_id']  = $aplikasi_id;

					$data['isi_disposisi'] = $disposisi;
					// $data['ID_DISPOSISI']  = $id_disposisi;
					$data['id_detail_sm']  = $id_detail;
					$data['flag']          = $laporan;
					$data['tujuan_user']   = $user_data->id_user;
					$data['status']        = $jenis_dispos->level_disposisi;
					$data['status_dari']   = $surat_masuk->id_log_disposisi;
					$data['id_unit']       = $user_data->id_unit;
					$data['dari_user']     = $this->session->userdata('id');
					$data['id_level']      = $user_data->id_level;
                    $data['deadline']      = $deadline;
                    $data['id_status']     = 2;
                    $data['flag_dari_surat'] = 1;
                    if($data['deadline']!=null){
            				$data['tgl_deadline']  = $tgldeadline;
        			}

        			$level_umum = array(13,14,15,16);
					if (in_array($id_level, $level_umum)) {
						$data['kordinator']      = $kordinator;
					}
					$tgl_log_disposisi_line = date('Y-m-d H:i:s');
					$data2 = array('tgl_dispos' => "'".$tgl_log_disposisi_line."'");

					//Ini save disposisi
					$size = $this->alur->jump_disposisi($this->session->userdata('id_level'), $jenis_dispos->level_disposisi, $user_data->id_level, $user_data->id_user, $surat_masuk);
					if ($laporan == 1) {
						$data['peminta_laporan'] = $this->insert_laporan($id_detail, $surat_masuk->id_log_disposisi);
						// $this->insert_laporan($id_detail);
					}

					$ceksekre	    = substr($surat_masuk->skpd, 0, 3)."000000";
					$arr_sekda 		= array(100000000,101000000,102000000,103000000,104000000,105000000);
					if(in_array($ceksekre, $arr_sekda) && $surat_masuk->id_unit == 105010100){
							$this->delete_disposisi_bagian_before($surat_masuk->id_detail,$surat_masuk->id_level);
						}
					if ($surat_masuk->status > 3) $this->delete_disposisi_before($surat_masuk->status_dari);

					// $data_where = array('id_user' => $unit[$i]);
					// $get_unit  = $this->model_app->getSelectedData('v_user', $data_where)->row();
					$get_unit = $user_data;
					if($this->input->post('disposisi_lagi') <> null) {
						$data_where = array('id_log_disposisi' => $this->input->post('disposisi_lagi'));
					}
					else {
						$data_where = array('id_detail_sm' => $id_detail,"id_unit"=>$this->session->userdata('id_unit'));
					}
					$status_baru = array('id_status' => 3);
					$this->model_app->updateData('t_log_disposisi', $data_where, $status_baru);
					$data_temp_tujuan = array('id_unit'=> $user_data->id_unit,'id_detail'=>$id_detail,'dari_unit'=>$this->session->userdata('id_dinas'));
					$this->model_app->insertData('temp_tujuan_dispos', $data_temp_tujuan);
					// var_dump($this->db->last_query());
					// exit;
					if(in_array($get_unit->id_unit, $arr_sekda)){
						$this->model_app->insertData('t_disposisi', $data, $data2);
					// 	var_dump($this->db->last_query());
					// exit;
					}else{
						$hasil = $this->alur->disposisi_sekretariat($id_detail,$user_data->id_unit,$user_data->struktur_dinas,$this->session->userdata('id'),$jenis_dispos->level_disposisi,$surat_masuk->id_log_disposisi,$surat_masuk,$data);
					}

					// untuk koordinator
					// $arr_kepala_opd = array(1,7,10,17,20,23,25);
					//
					// $get_id_level = $user_data->id_level;
					// if ($get_id_level == '14') {
					// 	if($level_tetew == 13) {
					// 		$tetew1 += 1;
					// 		$user_tetew1 = $user_data->id_user;
					// 	}
					// 	else {
					// 		$cekuser_tetew = 0;
					// 	}
					// }elseif ($get_id_level == '15') {
					// 	if($level_tetew == 13 || $level_tetew == 14) {
					// 		$tetew2 += 1;
					// 		$user_tetew2 = $user_data->id_user;
					// 	}
					// 	else {
					// 		$cekuser_tetew = 0;
					// 	}
					// }elseif ($get_id_level == '16') {
					// 	if($level_tetew == 13 || $level_tetew == 14 || $level_tetew == 15) {
					// 		$tetew3 += 1;
					// 		$user_tetew3 = $user_data->id_user;
					// 		$unit_tetew3 = $user_data->id_unit;
					// 	}
					// 	else {
					// 		$cekuser_tetew = 0;
					// 	}
					// }elseif (in_array($get_id_level, $arr_kepala_opd)) {
					// 	// echo "update kadin";
					// 	$tetew4 += 1;
					// 	$user_tetew4 = $user_data->id_user;
					// }
					// end untuk koordinator

					if($user_data->id_line <> null) {
						// $q_notifikasi_line = "INSERT INTO t_notifikasi_line (id_user, id_line, pesan, status) VALUES ('".$user_data->id_user."', '".$user_data->id_line."', '$pesan_notifikasi_line', 0) RETURNING id";
						//
						// $res_notifikasi_line = $this->model_app->queryReturn($q_notifikasi_line);
						//
						// $id_notifikasi_line = $res_notifikasi_line['id'];

						// $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient("sg/cQy0aVenkPYoz7h+zyIrkmEH8ir7TGRDtv4mry2hPy48B++zrIUCHtc59A36I8ck5BGKFHKpppThvCokWWCdklQQCWZk8YYGiuWUblh6LtE8bj5paexpn98dKHhDyrtgof39RQqrg82nZtg50SwdB04t89/1O/w1cDnyilFU=");
						// $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => "03677c4551ec5d57e47f8633cfa411e0"]);

						// include APPPATH . 'third_party/line_bot_sdk/src/LINEBot.php';
						// include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/HTTPClient.php';
						// include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/HTTPClient/CurlHTTPClient.php';
						// include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/MessageBuilder.php';
						// include APPPATH . 'third_party/line_bot_sdk/src/LINEBot/MessageBuilder/TextMessageBuilder.php';

						// $this->load->library('line_bot_sdk/src/LINEBot.php');
						// $this->load->library('line_bot_sdk/src/LINEBot/HTTPClient.php');
						// $this->load->library('line_bot_sdk/src/LINEBot/HTTPClient/CurlHTTPClient.php');
						// $this->load->library('line_bot_sdk/src/LINEBot/MessageBuilder.php');
						// $this->load->library('line_bot_sdk/src/LINEBot/MessageBuilder/TextMessageBuilder.php');


						$this->load->library('linebot');

						$query = "
							SELECT
          			mu.id_user, mu.id_level, mu.nama, mu.status,
			          CASE
									WHEN mu.id_level = ANY (ARRAY[1, 2, 3, 9, 19, 17, 23, 10, 7, 25]) THEN concat('KEPALA ', muk.nama_unit)::character varying
									WHEN mu.id_level = ANY (ARRAY[5, 8, 11]) THEN ml.nama_level||' '||muk_skpd.nama_unit
									WHEN mu.id_level = ANY (ARRAY[13, 14]) THEN muk_skpd.nama_unit
									WHEN mu.id_level = ANY (ARRAY[15, 16, 24, 27]) THEN muk.nama_unit
									WHEN mu.id_level = ANY (ARRAY[4, 6]) THEN concat('STAFF ', muk.nama_unit)::character varying
			            ELSE mu.nama
			          END AS jabatan
          		FROM
            		m_user mu
		            JOIN m_unit_kerja muk ON mu.id_unit = muk.id_unit
		            JOIN m_level ml ON mu.id_level = ml.id_level
		            LEFT JOIN LATERAL (
									SELECT CASE
										WHEN (muk_skpd.id_struktur = ANY (ARRAY[50, 100, 300])) THEN concat(substr(muk.id_unit::text, 0, 4), '000000')::bpchar
										WHEN (muk_skpd.id_struktur = ANY (ARRAY[40, 60, 70])) THEN concat(substr(muk.id_unit::text, 0, 7), '000')::bpchar
										ELSE muk.id_unit::character(10)
									END AS skpd1
									FROM m_unit_kerja muk_skpd
									WHERE concat(substr(muk.id_unit::text, 0, 4), '000000') = muk_skpd.id_unit::text
								) skpd ON TRUE
								JOIN m_unit_kerja muk_skpd ON skpd.skpd1::text = muk_skpd.id_unit::text
          		WHERE
								mu.id_user = '".$this->session->userdata('id')."'
						";
						$dari_user_notifikasi_line = $this->model_app->eksekusi($query)->row();

						$pesan_notifikasi_line = "Pemberitahuan, ada surat masuk ke Bapak / Ibu \n\nPengirim : ".$surat_masuk->dinas_pengirim."\nPerihal Surat : ".$surat_masuk->perihal."\nDisposisi Dari : ".$dari_user_notifikasi_line->nama." (".$dari_user_notifikasi_line->jabatan.")\nIsi Disposisi : ".$disposisi."\nTgl Disposisi : ".$tgl_log_disposisi_line."\nUntuk info detail surat, agar dibuka di https://esurat.surabaya.go.id";

						//================================================ LINE ==================================================
						$data_notifikasi_line = array(
							'id_user' => $user_data->id_user,
							'id_line' => $user_data->id_line,
							'pesan'   => $pesan_notifikasi_line
						);
						$data_notifikasi_line2 = array('tgl_kirim' => "'".date('Y-m-d H:i:s')."'");

						$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(LINE_CHANNEL_ACCESS_TOKEN);
						$bot  = new \LINE\LINEBot($httpClient, ['channelSecret' => LINE_CHANNEL_SECRET]);

						$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($pesan_notifikasi_line);
						$response_notifikasi_line = $bot->pushMessage($user_data->id_line, $textMessageBuilder);
						// ================================================ END LINE ==================================================
						
						if($response_notifikasi_line->isSucceeded()) {
							$data_notifikasi_line['status'] = 1;
						}
						else {
							$data_notifikasi_line['status'] = 0;
						}

						$this->model_app->insertData('t_notifikasi_line', $data_notifikasi_line, $data_notifikasi_line2);
					}

					if (stripos(htmlspecialchars($this->input->post('disposisi')), "outreach") !== false) {
						$query = "
				      SELECT log_dispos.isi_disposisi, log_dispos.tgl_log_dispos, m_tujuan.nama_unit AS dinas_tujuan, m_tujuan.id_unit AS id_dinas_tujuan
				      FROM t_log_disposisi log_dispos
							JOIN m_user mu ON log_dispos.dari_user = mu.id_user::varchar(250)
							LEFT JOIN LATERAL (
								SELECT
									CASE
										WHEN c_tujuan.id_struktur = ANY (ARRAY[50, 100, 60, 300]) THEN concat(substr(log_dispos.id_unit::text, 0, 4), '000000')::bpchar
										WHEN c_tujuan.id_struktur = 40 THEN concat(substr(log_dispos.id_unit::text, 0, 7), '000')::bpchar
										ELSE log_dispos.id_unit::character(10)
									END AS skpd1
								FROM
									m_unit_kerja c_tujuan
								WHERE
									c_tujuan.id_unit::text = concat(substr(log_dispos.id_unit::text, 0, 4), '000000')
							) n_tujuan ON TRUE
							LEFT JOIN m_unit_kerja m_tujuan ON m_tujuan.id_unit::text = n_tujuan.skpd1::text
				      WHERE log_dispos.id_detail_sm = '".$surat_masuk->id_detail."'
							AND mu.id_unit = '101000000'
							ORDER BY log_dispos.tgl_log_dispos asc
				    ";
						$disposisi_wali = $this->model_app->eksekusi($query)->result();

						if($disposisi_wali) {
							$unit_tujuan_kesra = array();
							for ($i=0; $i < count($disposisi_wali); $i++) {
								if (!in_array($disposisi_wali[$i]->dinas_tujuan, $unit_tujuan_kesra)) {
									$unit_tujuan_kesra[] = $disposisi_wali[$i]->dinas_tujuan;
								}
							}

							$query = "
					      SELECT *
					      FROM esurat_kesra
					      WHERE id_surat_masuk = '".$surat_masuk->id_surat_masuk."'
					    ";
							$surat_kesra = $this->model_app->eksekusi($query)->row();
							$data_kesra = array();
							if($surat_kesra == null) {
								$data_kesra['id_surat_masuk']  			   = $surat_masuk->id_surat_masuk;
								$data_kesra['id_detail_sm']    			   = $surat_masuk->id_detail;
								$data_kesra['no_surat_masuk']  			   = $this->input->post('no_surat_kesra');;
								$data_kesra['no_agenda']       			   = $this->input->post('no_agenda_kesra');
								$data_kesra['agenda']          			   = $surat_masuk->agenda;
								$data_kesra['tgl_surat']        			 = $surat_masuk->tgl_surat;
								$data_kesra['dari']             			 = $this->input->post('dari_kesra');
								$data_kesra['perihal']          			 = $this->input->post('perihal_kesra');
								$data_kesra['sifat']            			 = $this->input->post('sifat_kesra');;
								$data_kesra['tgl_disposisi']    			 = $disposisi_wali[0]->tgl_log_dispos;
								$data_kesra['isi_disposisi']    			 = $disposisi_wali[0]->isi_disposisi;
								$data_kesra['tujuan_disposisi'] 			 = json_encode($unit_tujuan_kesra);
	              $data_kesra['file_surat']       			 = $this->input->post('file_surat_kesra');
	              $data_kesra['file_lampiran']   				 = $this->input->post('file_lampiran_surat_kesra');
								$data_kesra['file_lampiran_pendukung'] = $this->input->post('file_lampiran_pendukung_kesra');
	              $data_kesra['flag']            				 = 0;

								$this->model_app->insertData('esurat_kesra', $data_kesra);
							}
						}
					}



					$status['STATUS']   = "berhasil";
					$status['MESSAGE']  = "Berhasil Menambah Disposisi";
					$status['ID_LEVEL'] = $kepada;

					//$data_where = array('id_detail' => $id_detail, 'dari_user' => $this->session->userdata('id'), 'id_dinas' => $user_data->id_dinas);
					// $get_dispos = $this->model_app->getSelectedData('v_user_dan_disposisi', $data_where)->row();
					// if (empty($get_dispos)) {
					//
					// } else{
					// 	$status['STATUS']   = "warning";
					// 	$status['MESSAGE']  = "Anda Pernah Mendisposisikan Kepada ".$get_dispos->nama_dinas.". Dengan Nomor Surat ".$get_dispos->no_surat_masuk." Pada Tanggal ".$get_dispos->tgl_dispos.".";
					// 	$status['ID_LEVEL'] = $user_data->id_level;
					// }
				}

				//UNTUK UPDATE KOORDINATOR
				// $update_koor = 0;
				//
				// if($cekuser_tetew == 1) {
				// 	if ($tetew1 == 1) {
				// 		$data_where   = array('id_detail_sm'=>$id_detail, 'tujuan_user'=>$user_tetew1, 'id_status' => 2);
				// 		$is_koor = array('is_koordinator' => 1);
				// 		$this->model_app->updateData('t_log_disposisi', $data_where, $is_koor);
				//
				// 		$data_where   = array('id_detail'=>$id_detail);
				// 		$is_asis = array('is_asisten' => 0);
				// 		$this->model_app->updateData('detail_surat_masuk', $data_where, $is_asis);
				// 		$update_koor = 1;
				// 	}elseif ($tetew2 == 1) {
				// 		$data_where   = array('id_detail_sm'=>$id_detail, 'tujuan_user'=>$user_tetew2, 'id_status' => 2);
				// 		$is_koor = array('is_koordinator' => 1);
				// 		$this->model_app->updateData('t_log_disposisi', $data_where, $is_koor);
				//
				// 		$data_where   = array('id_detail'=>$id_detail);
				// 		$is_asis = array('is_asisten' => 4);
				// 		$this->model_app->updateData('detail_surat_masuk', $data_where, $is_asis);
				// 		$update_koor = 1;
				// 	}elseif ($tetew3 == 1) {
				// 			$data_where   = array('id_detail_sm'=>$id_detail, 'tujuan_user'=>$user_tetew3, 'id_status' => 2);
				// 			$is_koor = array('is_koordinator' => 1);
				// 			$this->model_app->updateData('t_log_disposisi', $data_where, $is_koor);
				//
				// 			$data_where   = array('id_detail'=>$id_detail);
				// 			$hmm = -1;
				// 			if ($unit_tetew3 == '103000000') {
				// 				$hmm = 1;
				// 			}elseif ($unit_tetew3 == '104000000') {
				// 				$hmm = 2;
				// 			}elseif ($unit_tetew3 == '105000000') {
				// 				$hmm = 3;
				// 			}
				// 			$is_asis = array('is_asisten' => $hmm);
				// 			$this->model_app->updateData('detail_surat_masuk', $data_where, $is_asis);
				// 			$update_koor = 1;
				// 	}elseif ($tetew4 == 1 && $tetew3 == 0) {
				// 			$data_where   = array('id_detail_sm'=>$id_detail, 'tujuan_user'=>$user_tetew4, 'id_status' => 2);
				// 			$is_koor = array('is_koordinator' => 1);
				// 			$this->model_app->updateData('t_log_disposisi', $data_where, $is_koor);
				// 			$update_koor = 1;
				// 	}
				// }
				// else {
				// 	$update_koor = 1;
				// }
				//
				// if ($get_id_level == '14') {
				// 	$update_koor = 1;
				// }
				// end untuk koordinator

				///.UNTUK UPDATE KOORDINATOR
				// $status['KOORDINATOR'] = $update_koor;
				// $status['ID_DETAIL']	 = $id_detail;
				// if($update_koor == 0) {
				// 	$status['STATUS']   = "berhasil_koor";
				// }
				// end untuk koordinator

			} else{ // JIKA UNIT KOSONG
				$status['STATUS']   = "gagal";
				$status['MESSAGE']  = "Tujuan Unit Tidak Boleh Kosong !";
				$status['ID_LEVEL'] = $kepada;
			}
		}
		echo json_encode($status);

		$this->db->trans_complete();
		//$this->output->enable_profiler(TRUE);
	}

	function add_disposisi_sekretariat_bekup(){
		// ini_set('display_errors', 1);
		// if($this->input->post('id_detail') == 23820) {
		// 	die;
		// }
		//BUG FIX DARI ALI
		$dari             = $this->input->post('dari');
		//END BUG FIX DARI ALI

		$kepada           = $this->input->post('kepada');
		$unit             = $this->input->post('unit');

		$disposisi        = htmlspecialchars($this->input->post('disposisi'));
		$id_detail        = $this->input->post('id_detail');
		$id_disposisi     = $this->input->post('id_disposisi');
		$id_log_disposisi = $this->input->post('id_log_disposisi');
		$sk_sm            = $this->input->post('sk_sm');
		$laporan          = $this->input->post('laporan');
        $deadline         = ($this->input->post('deadline'))?$this->input->post('deadline'):null;
        $kordinator       = ($this->input->post('unit_kordinator'))?$this->input->post('unit_kordinator'):null;
        $id_level 		  = $this->session->userdata('id_level');
		$id_unit  		  = $this->session->userdata('id_unit');

        if($laporan==0){
        	$laporan=3;
        }
        if($deadline!='null'){
        	$tgldeadline      = date('d-M-Y', strtotime('+'.$deadline.' days'));
        }else{
        	$deadline    = null;
        	$tgldeadline =null;
        }
		$kategori_serdesk = $this->input->post('kategori');
		$sub_kat_serdesk  = $this->input->post('sub_kategori');
		$aplikasi_id	  = $this->input->post('nama_aplikasi');

		if($id_level==19 && $id_unit == 105010100){
			$data         = array('id_detail_sm' => $id_detail);
			$data_where   = array('id_detail' => $id_detail, 'id_unit_tujuan'=>$id_unit);
			$surat_masuk  = $this->model_app->getSelectedData('v_user_dan_disposisi',$data_where)->row();
		}else{
			$data         = array('id_detail_sm' => $id_detail);
			$data_where   = array('id_detail' => $id_detail, 'id_unit_tujuan'=>$id_unit );
			$surat_masuk  = $this->model_app->getSelectedData('v_user_dan_disposisi',$data_where)->row();
		}
		$cek_null = $this->custom_function->isNull($surat_masuk, 'surat tidak ditemukan', 'add_disposisi 49');
		if ($cek_null['BOOLEAN']) {
			echo json_encode($cek_null);
			return;
		}
		$this->db->trans_start();
		$size = 0;
		if($kepada==0){
			$data['kategori']     = $kategori_serdesk;
			$data['sub_kategori'] = $sub_kat_serdesk;
			$data['aplikasi_id']  = $aplikasi_id;

			$data['isi_disposisi'] = $disposisi;
			$data['id_detail_sm']  = $id_detail;
			// $data['ID_DISPOSISI']  = $id_disposisi;
			$data['tujuan_user']   = $this->session->userdata('id');
			$data['id_unit']       = $this->session->userdata('id_unit');
			$data['dari_user']     = $this->session->userdata('id');
			$data['flag']          = $surat_masuk->flag;
			$data['status']        = 20;
			$data['status_dari']   = $surat_masuk->id_log_disposisi;
			$data['id_level']      = $this->session->userdata('id_level');
			$data['pelaksana']     = $this->session->userdata('id');
            $data['deadline']      = $deadline;
            $data['id_status']     = 4;
            if($data['deadline']!=null){
            	$data['tgl_deadline']  = $tgldeadline;
        	}

			$data2 = array('tgl_dispos' => "'".date('Y-m-d H:i:s')."'");

			$data_where = array('id_detail' => $id_detail, 'dari_user' => $this->session->userdata('id'), 'id_user_tujuan' => $this->session->userdata('id'));
			$get_dispos = $this->model_app->getSelectedData('v_user_dan_disposisi', $data_where)->row();
			if (empty($get_dispos)) {
				$sendiri = true;
				if ($surat_masuk->flag == 1) {
					$data['peminta_laporan'] = $this->insert_laporan($id_detail, $surat_masuk->id_log_disposisi);
					// $this->insert_laporan($id_detail);
				}
				// print_r($data);exit;
				$data_where = array('id_detail_sm' => $id_detail,'id_status'=>2);
				$jumlah_surat_baru = $this->model_app->count_data('t_log_disposisi', $data_where);
				// print_r($data);exit;
				//if($surat_masuk->ID_STRUKTUR==50 || $surat_masuk->ID_STRUKTUR==100 || $surat_masuk->ID_STRUKTUR==300){
				//	$cekdin	    = substr($this->session->userdata('id_unit'), 0, 6);
				//}else if($surat_masuk->ID_STRUKTUR==60 || $surat_masuk->ID_STRUKTUR==41){
				//	$cekdin	    = substr($this->session->userdata('id_unit'), 0, 7);
				//}else
				if($jumlah_surat_baru==0){
					$cekdin	    = substr($this->session->userdata('id_dinas'), 0, 3);
					$din 		= $cekdin."%";
					$data_where = array('id_detail_sm' => $id_detail, 'cast(id_unit as text) LIKE' => $din );
					$status_baru = array('id_status' => 4);
					$this->model_app->updateData('t_log_disposisi', $data_where, $status_baru);
				}
				// $data_where = array('id_detail_sm' => $id_detail);
				// $status_baru = array('id_status' => 4);
				// $this->model_app->updateData('t_log_disposisi', $data_where, $status_baru);
				$this->model_app->insertData('t_disposisi', $data, $data2);
				$this->delete_disposisi_before($surat_masuk->status_dari, $sendiri);

				$status['STATUS']   = "berhasil";
				$status['MESSAGE']  = "Berhasil Menambah Disposisi";
				$status['ID_LEVEL'] = $kepada;

				if ($surat_masuk->id_kategori == 22 && $this->session->userdata('id_dinas') == 539000000) {
					$status_json   = $this->send_serdesk($data, $dari);
					// print_r($status_json); exit;
					$id_permintaan = $status_json['id_permintaan'];
					$data          = array('id_permintaan' => $id_permintaan);
					$datawhere     = array('id_detail' => $id_detail);
					$this->model_app->updateData('detail_surat_masuk', $datawhere, $data);
					$status['MESSAGE']  = $status['MESSAGE']." ".$status_json['MESSAGE'];
					$status['REDIRECT'] = "surat/inbox";
				}

			} else{
				$status['STATUS']   = "warning";
				// $status['MESSAGE']  = "Anda Tidak Dapat Mendisposisikan pada Orang yang sama 2 kali ! 11";
				$status['ID_LEVEL'] = $kepada;
			}
		}else{
			if(!empty($unit) && $unit != "null"){ // JIKA UNIT TIDAK KOSONG
				if (!is_array($unit)) {
					$unit = explode(',', $unit);
				}
				for ($i=0; $i < count($unit); $i++) {
					$data_where = array('id_user'=>$unit[$i], );
					$user_data  = $this->model_app->getSelectedData('v_user',$data_where)->row();
					$kepada 	= $user_data->id_level;

					/** JIKA DISPOSISI KE KELURAHAN */
					if(strtolower(substr($user_data->nama_unit, 0, 9)) == 'kelurahan') {
						$data_where = ['id_unit' => $user_data->id_unit];
						$unit_kerja = $this->model_app->getSelectedData('m_unit_kerja', $data_where)->row();

						$user_data->struktur_dinas = $unit_kerja->id_struktur;
					}
					/** END */

					$data_where   = array('id_struktur'=>$user_data->struktur_dinas, 'id_level'=>$kepada, 'status_sk_sm'=> 2);
					$jenis_dispos = $this->model_app->getSelectedData('m_jenis_disposisi', $data_where)->row();
					if($jenis_dispos!=NULL){
						if($jenis_dispos->flag==1){
							$data_where   = array('id_struktur'=>$user_data->struktur_dinas, 'id_level'=>$kepada, 'status_sk_sm'=> 2,'next_id_jenis_disposisi'=>NULL);
							// $data_where   = array('ID_STRUKTUR'=>$surat_masuk->ID_STRUKTUR, 'ID_LEVEL'=>$kepada, 'STATUS_SK_SM'=> 2,'NEXT_ID_JENIS_DISPOSISI'=>NULL);
							$jenis_dispos = $this->model_app->getSelectedData('m_jenis_disposisi', $data_where)->row();
						}
					}

					$arr_status = array(13,16);
					if(in_array($surat_masuk->status, $arr_status)){
						$this->cek_unit_user($this->session->userdata('id'), $user_data->id_user, $this->session->userdata('id_level'), $surat_masuk,$deadline);
					}
					$data['kategori']     = $kategori_serdesk;
					$data['sub_kategori'] = $sub_kat_serdesk;
					$data['aplikasi_id']  = $aplikasi_id;

					$data['isi_disposisi'] = $disposisi;
					// $data['ID_DISPOSISI']  = $id_disposisi;
					$data['id_detail_sm']  = $id_detail;
					$data['flag']          = $laporan;
					$data['tujuan_user']   = $user_data->id_user;
					$data['status']        = $jenis_dispos->level_disposisi;
					$data['status_dari']   = $surat_masuk->id_log_disposisi;
					$data['id_unit']       = $user_data->id_unit;
					$data['dari_user']     = $this->session->userdata('id');
					$data['id_level']      = $user_data->id_level;
                    $data['deadline']      = $deadline;
                    $data['id_status']     = 2;
                    $data['flag_dari_surat'] = 1;
                    if($data['deadline']!=null){
            				$data['tgl_deadline']  = $tgldeadline;
        			}

        			$level_umum = array(13,14,15,16);
					if (in_array($id_level, $level_umum)) {
						$data['kordinator']      = $kordinator;
					}
					$data2 = array('tgl_dispos' => "'".date('Y-m-d H:i:s')."'");

					//Ini save disposisi
					$size = $this->alur->jump_disposisi($this->session->userdata('id_level'), $jenis_dispos->level_disposisi, $user_data->id_level, $user_data->id_user, $surat_masuk);
					if ($laporan == 1) {
						$data['peminta_laporan'] = $this->insert_laporan($id_detail, $surat_masuk->id_log_disposisi);
						// $this->insert_laporan($id_detail);
					}

					$ceksekre	    = substr($surat_masuk->skpd, 0, 3)."000000";
					$arr_sekda 		= array(100000000,101000000,102000000,103000000,104000000,105000000);
					if(in_array($ceksekre, $arr_sekda) && $surat_masuk->id_unit == 105010100){
							$this->delete_disposisi_bagian_before($surat_masuk->id_detail,$surat_masuk->id_level);
						}
					if ($surat_masuk->status > 3) $this->delete_disposisi_before($surat_masuk->status_dari);

					$data_where = array('id_user' => $unit[$i]);
					$get_unit  = $this->model_app->getSelectedData('v_user', $data_where)->row();
					if($this->input->post('disposisi_lagi') <> null) {
						$data_where = array('id_log_disposisi' => $this->input->post('disposisi_lagi'));
					}
					else {
						$data_where = array('id_detail_sm' => $id_detail,"id_unit"=>$this->session->userdata('id_unit'));
					}
					$status_baru = array('id_status' => 3);
					$this->model_app->updateData('t_log_disposisi', $data_where, $status_baru);
					$data_temp_tujuan = array('id_unit'=> $user_data->id_unit,'id_detail'=>$id_detail,'dari_unit'=>$this->session->userdata('id_dinas'));
					$this->model_app->insertData('temp_tujuan_dispos', $data_temp_tujuan);
					// var_dump($this->db->last_query());
					// exit;
					if(in_array($get_unit->id_unit, $arr_sekda)){
						$this->model_app->insertData('t_disposisi', $data, $data2);
					// 	var_dump($this->db->last_query());
					// exit;
					}else{
						$hasil = $this->alur->disposisi_sekretariat($id_detail,$user_data->id_unit,$user_data->struktur_dinas,$this->session->userdata('id'),$jenis_dispos->level_disposisi,$surat_masuk->id_log_disposisi,$surat_masuk,$data);
					}

					$status['STATUS']   = "berhasil";
					$status['MESSAGE']  = "Berhasil Menambah Disposisi";
					$status['ID_LEVEL'] = $kepada;

					//$data_where = array('id_detail' => $id_detail, 'dari_user' => $this->session->userdata('id'), 'id_dinas' => $user_data->id_dinas);
					// $get_dispos = $this->model_app->getSelectedData('v_user_dan_disposisi', $data_where)->row();
					// if (empty($get_dispos)) {
					//
					// } else{
					// 	$status['STATUS']   = "warning";
					// 	$status['MESSAGE']  = "Anda Pernah Mendisposisikan Kepada ".$get_dispos->nama_dinas.". Dengan Nomor Surat ".$get_dispos->no_surat_masuk." Pada Tanggal ".$get_dispos->tgl_dispos.".";
					// 	$status['ID_LEVEL'] = $user_data->id_level;
					// }
				}
			} else{ // JIKA UNIT KOSONG
				$status['STATUS']   = "gagal";
				$status['MESSAGE']  = "Tujuan Unit Tidak Boleh Kosong !";
				$status['ID_LEVEL'] = $kepada;
			}
		}
		echo json_encode($status);

		$this->db->trans_complete();
	}

	function cek_disposisi_sekretariat() {
		$id_detail = $this->input->post('id_detail');
		$tgl_dispos = $this->input->post('tgl_dispos');
		$data_where = array('id_detail' => $id_detail, 'dari_user' => $this->session->userdata('id'));
		$where = "b.id_detail_sm = '$id_detail' AND b.dari_user = '".$this->session->userdata('id')."'";
		//$get_dispos = $this->model_app->getSelectedData('t_log_disposisi', $data_where)->result_array();
		$get_dispos = $this->model_app->tampil_disposisi_list($where);
		if (empty($get_dispos)) {
			$status['STATUS']   = "belum";
			$status['MESSAGE']  = "Surat Belum Pernah Terdisposisi Sebelumnya";
		} else{
			$data_where = array('id_detail' => $id_detail, 'id_user_tujuan' => $this->session->userdata('id'), 'tgl_dispos' => $tgl_dispos);
			$get_dispos2 = $this->model_app->getSelectedData('v_user_dan_disposisi', $data_where)->row_array();
			$status['STATUS']   = "warning";
			$pernah_disposisi = "";
			$tabel = "<style> table {border-collapse:collapse; table-layout:fixed;} table td {border:solid 1px; width:100px; word-wrap:break-word;} </style><table border='1' width='100%' style='font-size:12px;'><thead><tr><td width='30%'>Tujuan</td><td width='10%'>Nomor Surat</td><td width='10%'>Tanggal Disposisi</td><td width='50%'>Isi Disposisi</td></tr></thead><tbody>";
			$ketemu = false;
			$tgl_disposisi_lama = "";
			for($i=0;$i<count($get_dispos);$i++) {
				$pernah_disposisi = $pernah_disposisi .'<br>'.$get_dispos[$i]['nama_dinas'].". Dengan Nomor Surat ".$get_dispos[$i]['no_surat_masuk']." Pada Tanggal ".$get_dispos[$i]['tgl_dispos']." Dengan isi".' "'.nl2br($get_dispos[$i]['isi_disposisi']).'"';
				$tabel = $tabel . "<tr><td>".$get_dispos[$i]['nama_dinas']."</td><td>".$get_dispos[$i]['no_surat_masuk']."</td><td>".$get_dispos[$i]['tgl_dispos']."</td><td>".nl2br($get_dispos[$i]['isi_disposisi'])."</td></tr>";
				if(!$ketemu) {
					if($get_dispos[$i]['tujuan_user'] == $get_dispos2['dari_user']) {
						$tgl_disposisi_lama = $get_dispos[$i]['tgl_dispos'];
						$ketemu = true;
					}
				}
			}
			$tabel = $tabel . "</tbody></table>";
			$tabel2 = "<table border='1' width='100%' style='font-size:12px;'><thead><tr><td width='30%'>Dari</td><td width='10%'>Nomor Surat</td><td width='10%'>Tanggal Disposisi</td><td width='50%'>Isi Disposisi</td></tr></thead><tbody><tr><td>".$get_dispos2['unit_dari']."</td><td>".$get_dispos2['no_surat_masuk']."</td><td>".$get_dispos2['tgl_dispos']."</td><td>".nl2br($get_dispos2['isi_disposisi'])."</td></tr></tbody></table>";
			$tidak = 'Apabila Tidak, maka isi Disposisi yang lama telah dianggap mewakili dan Surat akan hilang dari menu Surat Masuk';
			$tidak = 'Apabila Tidak, maka tidak akan mengubah isi Disposisi sebelumnya dan Surat ini akan diarsipkan';
			if($this->session->userdata('id_level') == 13) {
				$tidak = 'Apabila Tidak, maka akan dilakukan Disposisi kembali secara otomatis dengan isi yang sama dengan disposisi sebelumnya';
			}
			$status['MESSAGE']  = "<b>Karena Bapak / Ibu Sudah Pernah Mendisposisikan Surat ini Kepada : </b><br><br>".$tabel."<br><br><b>Apabila Ya, maka Disposisi baru akan melengkapi Disposisi sebelumnya</b><br><br><b>".$tidak."</b><br><br><b>Surat ini merupakan Disposisi ke Bapak / Ibu yang berasal  : </b><br><br>".$tabel2;
			$status['DARI_USER']   = $get_dispos2['dari_user'];
			$status['ID_LOG_DISPOSISI'] = $get_dispos2['id_log_disposisi'];
			$status['TGL_LOG_DISPOSISI'] = $tgl_disposisi_lama;
		}
		echo json_encode($status);
	}

	function cek_disposisi_opd() {
		$id_detail = $this->input->post('id_detail');
		$tgl_dispos = $this->input->post('tgl_dispos');
		$id_log_disposisi_asal = $this->input->post('id_log_disposisi');
		$arr_level = array(1,7,10,17,20);
		$operator_sm = array(14,22,9,26,100,173,194,197,200,212,213,3803);
		if(in_array($this->session->userdata('id_level'), $arr_level)) {
			$data_where = array('id_detail' => $id_detail, 'dari_user' => $this->session->userdata('id'));
			$where = "b.id_detail_sm = '$id_detail' AND b.dari_user = '".$this->session->userdata('id')."'";
		}
		else {
			$data_where = array('id_detail' => $id_detail, 'dari_user' => $this->session->userdata('id'), 'flag <>' => 8);
			$where = "b.id_detail_sm = '$id_detail' AND b.dari_user = '".$this->session->userdata('id')."'";
		}
		$data_where = array('id_detail' => $id_detail, 'dari_user' => $this->session->userdata('id'));
		$where = "b.id_detail_sm = '$id_detail' AND b.dari_user = '".$this->session->userdata('id')."'";
		//$get_dispos = $this->model_app->getSelectedData('t_log_disposisi', $data_where)->result_array();
		$putar = true;
		$get_dispos = $this->model_app->tampil_disposisi_list($where);
		if (empty($get_dispos)) {
			$status['STATUS']   = "belum";
			$status['MESSAGE']  = "Surat Belum Pernah Terdisposisi Sebelumnya";
		} else{
			$data_where = array('id_detail' => $id_detail, 'id_user_tujuan' => $this->session->userdata('id'), 'tgl_dispos' => $tgl_dispos);
			$get_dispos2 = $this->model_app->getSelectedData('v_user_dan_disposisi', $data_where)->row_array();
			$id_log_disposisi_update = $get_dispos2['id_log_disposisi'];
			if(in_array($this->session->userdata('id_level'), $arr_level)) {
				$dari = $get_dispos2['dari_user'];
				$id_log_sebelum = $get_dispos2['status_dari'];
				$id_log_sekarang = $get_dispos2['id_log_disposisi'];
				$unit_dari = $get_dispos2['unit_dari'];
				//while(substr($get_dispos2['id_unit_tujuan'], 0,3) == substr($tujuan, 0,3) || substr($get_dispos2['id_unit_tujuan'], 0,6) == substr($tujuan, 0,6)) {
				while (!in_array($dari, $operator_sm) && $putar) {
					$d_where = array('id_log_disposisi' => $id_log_sebelum);
					$log_dis = $this->model_app->getSelectedData('t_log_disposisi', $d_where)->row_array();
					if($log_dis['status_dari'] == null) {
						$putar = false;
						$id_log_sekarang = $id_log_disposisi_asal;
					}
					else {
						$id_log_sebelum = $log_dis['status_dari'];
						$id_log_sekarang = $log_dis['id_log_disposisi'];
						$dari = $log_dis['dari_user'];
					}
				}
				$data_where = array('id_log_disposisi' => $id_log_sekarang);
				$get_dispos2 = $this->model_app->getSelectedData('v_user_dan_disposisi', $data_where)->row_array();
			}
			$status['STATUS']   = "warning";
			$pernah_disposisi = "";
			$tabel = "<style> table {border-collapse:collapse; table-layout:fixed;} table td {border:solid 1px; width:100px; word-wrap:break-word;} </style><table border='1' width='100%' style='font-size:12px;'><thead><tr><td width='30%'>Tujuan</td><td width='10%'>Nomor Surat</td><td width='10%'>Tanggal Disposisi</td><td width='50%'>Isi Disposisi</td></tr></thead><tbody>";
			for($i=0;$i<count($get_dispos);$i++) {
				$pernah_disposisi = $pernah_disposisi .'<br>'.$get_dispos[$i]['nama_tujuan'].". Dengan Nomor Surat ".$get_dispos[$i]['no_surat_masuk']." Pada Tanggal ".$get_dispos[$i]['tgl_dispos']." Dengan isi".' "'.nl2br($get_dispos[$i]['isi_disposisi']).'"';
				$tabel = $tabel . "<tr><td>".$get_dispos[$i]['nama_tujuan']."</td><td>".$get_dispos[$i]['no_surat_masuk']."</td><td>".$get_dispos[$i]['tgl_dispos']."</td><td>".nl2br($get_dispos[$i]['isi_disposisi'])."</td></tr>";
			}
			$tabel = $tabel . "</tbody></table>";
			$tabel2 = "<table border='1' width='100%' style='font-size:12px;'><thead><tr><td width='30%'>Dari</td><td width='10%'>Nomor Surat</td><td width='10%'>Tanggal Disposisi</td><td width='50%'>Isi Disposisi</td></tr></thead><tbody><tr><td>".$get_dispos2['unit_dari']."</td><td>".$get_dispos2['no_surat_masuk']."</td><td>".$get_dispos2['tgl_dispos']."</td><td>".nl2br($get_dispos2['isi_disposisi'])."</td></tr></tbody></table>";
			//Apabila Tidak, maka isi Disposisi yang lama telah dianggap mewakili dan Surat akan hilang dari menu Surat Masuk
			$status['MESSAGE']  = "<b>Karena Bapak / Ibu Sudah Pernah Mendisposisikan Surat ini Kepada : </b><br><br>".$tabel."<br><br><b>Apabila Ya, maka Disposisi baru akan melengkapi Disposisi sebelumnya</b><br><br><b>Apabila Tidak, maka tidak akan mengubah isi Disposisi sebelumnya dan Surat ini akan diarsipkan</b><br><br><b>Surat ini merupakan Disposisi ke Bapak / Ibu yang berasal  : </b><br><br>".$tabel2;
			$status['DARI_USER']   = $get_dispos2['dari_user'];
			$status['ID_LOG_DISPOSISI'] = $id_log_disposisi_asal;
		}
		echo json_encode($status);
	}

	function hilangkan_disposisi($p){
		$this->db->trans_start();
		$param = base64_decode(urldecode($p));
    $dtparam = explode('||', $param);
		$id_log_disposisi = $dtparam[0];
		$data_where  = array('id_log_disposisi' => $id_log_disposisi);
		$status_baru = array('id_status' => 3);
		$update = $this->model_app->updateData('t_log_disposisi', $data_where, $status_baru);

		if($update) {
			$status['STATUS']   = "berhasil";
			$status['MESSAGE']  = "Status Disposisi Sudah Diteruskan";
		}
		else {
			$status['STATUS']   = "gagal";
			$status['MESSAGE']  = "Gagal Merubah Status Disposisi";
		}
		$this->db->trans_complete();
		echo json_encode($status);
	}

	function add_disposisi_sekretariat_lagi($idsurat){
		$param = base64_decode(urldecode($idsurat));
    $dtparam = explode('||', $param);
		$idsurat = $dtparam[0];
		$tgl_dispos = $dtparam[1];
		$dari_user = $dtparam[2];
		$id_log_disposisi = $dtparam[3];
		$data_where = array('id_detail_sm' => $idsurat, 'dari_user' => $this->session->userdata('id'), 'tujuan_user' => $dari_user, 'tgl_log_dispos' => $tgl_dispos);
		$get_dispos = $this->model_app->getSelectedData('t_log_disposisi', $data_where)->row_array();
		//BUG FIX DARI ALI
		//$dari             = $get_dispos['dari_user'];
		//END BUG FIX DARI ALI

		//$kepada           = $this->input->post('kepada');
		$unit             = $get_dispos['tujuan_user'];

		$disposisi        = htmlspecialchars($get_dispos['isi_disposisi']);
		$id_detail        = $get_dispos['id_detail_sm'];
		//$id_disposisi     = $this->input->post('id_disposisi');
		//$id_log_disposisi = $this->input->post('id_log_disposisi');
		//$sk_sm            = $this->input->post('sk_sm');
		$laporan          = $get_dispos['flag'];
    $deadline         = $get_dispos['deadline'];
    $tgldeadline      = $get_dispos['tgl_deadline'];
    $kordinator       = $get_dispos['kordinator'];
    $id_level 		    = $get_dispos['id_level'];
		$id_unit  		    = $get_dispos['id_unit'];

		// $kategori_serdesk = $this->input->post('kategori');
		// $sub_kat_serdesk  = $this->input->post('sub_kategori');
		// $aplikasi_id	  = $this->input->post('nama_aplikasi');

		if($id_level==19 && $id_unit == 105010100){
			$data         = array('id_detail_sm' => $id_detail);
			$data_where   = array('id_detail' => $id_detail, 'id_unit_tujuan'=>$id_unit);
			$surat_masuk  = $this->model_app->getSelectedData('v_user_dan_disposisi',$data_where)->row();
		}else{
			$data         = array('id_detail_sm' => $id_detail);
			$data_where   = array('id_detail' => $id_detail, 'id_unit_tujuan'=>$id_unit );
			$surat_masuk  = $this->model_app->getSelectedData('v_user_dan_disposisi',$data_where)->row();
		}
		$cek_null = $this->custom_function->isNull($surat_masuk, 'surat tidak ditemukan', 'add_disposisi 49');
		if ($cek_null['BOOLEAN']) {
			echo json_encode($cek_null);
			return;
		}
		$this->db->trans_start();
		$size = 0;

		$data_where = array('id_user'=>$unit);
		$user_data  = $this->model_app->getSelectedData('v_user',$data_where)->row();
		$kepada 	= $user_data->id_level;

		$data_where   = array('id_struktur'=>$user_data->struktur_dinas, 'id_level'=>$kepada, 'status_sk_sm'=> 2);
		$jenis_dispos = $this->model_app->getSelectedData('m_jenis_disposisi', $data_where)->row();
		if($jenis_dispos!=NULL){
			if($jenis_dispos->flag==1){
				$data_where   = array('id_struktur'=>$user_data->struktur_dinas, 'id_level'=>$kepada, 'status_sk_sm'=> 2,'next_id_jenis_disposisi'=>NULL);
				// $data_where   = array('ID_STRUKTUR'=>$surat_masuk->ID_STRUKTUR, 'ID_LEVEL'=>$kepada, 'STATUS_SK_SM'=> 2,'NEXT_ID_JENIS_DISPOSISI'=>NULL);
				$jenis_dispos = $this->model_app->getSelectedData('m_jenis_disposisi', $data_where)->row();
			}
		}

		$arr_status = array(13,16);
		if(in_array($surat_masuk->status, $arr_status)){
			$this->cek_unit_user($this->session->userdata('id'), $user_data->id_user, $this->session->userdata('id_level'), $surat_masuk,$deadline);
		}
		// $data['kategori']     = $kategori_serdesk;
		// $data['sub_kategori'] = $sub_kat_serdesk;
		// $data['aplikasi_id']  = $aplikasi_id;

		$data['isi_disposisi'] = $disposisi;
		// $data['ID_DISPOSISI']  = $id_disposisi;
		$data['id_detail_sm']  = $id_detail;
		$data['flag']          = $laporan;
		$data['tujuan_user']   = $user_data->id_user;
		$data['status']        = $jenis_dispos->level_disposisi;
		$data['status_dari']   = $surat_masuk->id_log_disposisi;
		$data['id_unit']       = $user_data->id_unit;
		$data['dari_user']     = $this->session->userdata('id');
		$data['id_level']      = $user_data->id_level;
              $data['deadline']      = $deadline;
              $data['id_status']     = 2;
              $data['flag_dari_surat'] = 1;
              if($data['deadline']!=null){
      				$data['tgl_deadline']  = $tgldeadline;
  			}

  			$level_umum = array(13,14,15,16);
		if (in_array($id_level, $level_umum)) {
			$data['kordinator']      = $kordinator;
		}
		$data2 = array('tgl_dispos' => "'".date('Y-m-d H:i:s')."'");

		//Ini save disposisi
		$size = $this->alur->jump_disposisi($this->session->userdata('id_level'), $jenis_dispos->level_disposisi, $user_data->id_level, $user_data->id_user, $surat_masuk);
		if ($laporan == 1) {
			$data['peminta_laporan'] = $this->insert_laporan($id_detail, $surat_masuk->id_log_disposisi);
			// $this->insert_laporan($id_detail);
		}

		$ceksekre	    = substr($surat_masuk->skpd, 0, 3)."000000";
		$arr_sekda 		= array(100000000,101000000,102000000,103000000,104000000,105000000);
		if(in_array($ceksekre, $arr_sekda) && $surat_masuk->id_unit == 105010100){
				$this->delete_disposisi_bagian_before($surat_masuk->id_detail,$surat_masuk->id_level);
			}
		if ($surat_masuk->status > 3) $this->delete_disposisi_before($surat_masuk->status_dari);

		$data_where = array('id_user' => $unit);
		$get_unit  = $this->model_app->getSelectedData('v_user', $data_where)->row();
		$data_where = array('id_log_disposisi' => $id_log_disposisi);
		$status_baru = array('id_status' => 3);
		$this->model_app->updateData('t_log_disposisi', $data_where, $status_baru);
		$data_temp_tujuan = array('id_unit'=> $user_data->id_unit,'id_detail'=>$id_detail,'dari_unit'=>$this->session->userdata('id_dinas'));
		$this->model_app->insertData('temp_tujuan_dispos', $data_temp_tujuan);
		// var_dump($this->db->last_query());
		// exit;
		if(in_array($get_unit->id_unit, $arr_sekda)){
			$insert = $this->model_app->insertData('t_disposisi', $data, $data2);
		// 	var_dump($this->db->last_query());
		// exit;
		}else{
			$hasil = $this->alur->disposisi_sekretariat($id_detail,$user_data->id_unit,$user_data->struktur_dinas,$this->session->userdata('id'),$jenis_dispos->level_disposisi,$surat_masuk->id_log_disposisi,$surat_masuk,$data);
		}
		if($insert) {
			$status['STATUS']   = "berhasil";
			$status['MESSAGE']  = "Berhasil Menambah Disposisi";
			$status['ID_LEVEL'] = $kepada;
		}
		else {
			$status['STATUS']   = "gagal";
			$status['MESSAGE']  = "Gagal Menambah Disposisi";
			$status['ID_LEVEL'] = $kepada;
		}
		echo json_encode($status);

		$this->db->trans_complete();
	}

	function add_disposisi_umum(){
		$dari             = $this->input->post('dari');
		//END BUG FIX DARI ALI

		$kepada           = $this->input->post('kepada');
		$unit             = $this->input->post('arahan');
		$disposisi        = "Surat Di Teruskan";
		$id_detail        = $this->input->post('id_detail');
		$id_disposisi     = $this->input->post('id_disposisi');
		$id_log_disposisi = $this->input->post('id_log_disposisi');
		$sk_sm            = $this->input->post('sk_sm');
		$id_unit  		  = $this->session->userdata('id_unit');

		if(!empty($unit) && $unit != "null"){

				$datawhere = array('id_user' => $unit);
				$user_data = $this->model_app->getSelectedData('v_user', $datawhere)->row();
				$cek_null  = $this->custom_function->isNull($user_data, 'user tidak dapat ditemukan', 'add_disposisi_umum 158');
				if ($cek_null['BOOLEAN']) {
					$status = $cek_null;
				}

				$data_where   = array('id_detail' => $id_detail, 'id_unit_tujuan'=>$id_unit);
				$surat_masuk  = $this->model_app->getSelectedData('v_user_dan_disposisi',$data_where)->row();

				if($surat_masuk->ID_STRUKTUR==41){
					$data_where   = array('id_struktur'=>40, 'id_level'=>$user_data->id_level, 'status_sk_sm'=> 2);
					$jenis_dispos = $this->model_app->getSelectedData('m_jenis_disposisi', $data_where)->row();
				}else{
					$data_where   = array('id_struktur'=>$surat_masuk->id_struktur, 'id_level'=>$user_data->id_level, 'status_sk_sm'=> 2);
					$jenis_dispos = $this->model_app->getSelectedData('m_jenis_disposisi', $data_where)->row();
				}
				$data = array('id_detail_sm' => $id_detail);

				$data['isi_disposisi'] = $disposisi;
				// $data['ID_DISPOSISI']  = $id_disposisi;
				$data['id_detail_sm']  = $id_detail;
				$data['tujuan_user']   = $user_data->id_user;
				$data['status']        = $jenis_dispos->level_disposisi;
				$data['status_dari']   = $surat_masuk->id_log_disposisi;
				$data['id_unit']       = $user_data->id_unit;
				$data['dari_user']     = $this->session->userdata('id');
				$data['id_level']      = $user_data->id_level;
				$data['flag']      	   = 8;
				$data['id_status']     = 2;

				$data2 = array('tgl_dispos' => "'".date('Y-m-d H:i:s')."'");
				$data_where = array('id_detail' => $id_detail, 'dari_user' => $this->session->userdata('id'), 'id_user_tujuan' => $user_data->id_user);
				$get_dispos = $this->model_app->getSelectedData('v_user_dan_disposisi', $data_where)->row();
				//if (empty($get_dispos)) {
					// switch ($kepada) {
					//switch ($user_data->ID_LEVEL) {
						//case 1: // DISPOSISI KE DINAS
							//$data['TUJUAN_USER'] = $user_data->ID_DINAS;
							//$status = $this->disposisi_dinas($surat_masuk, $user_data, $disposisi);
							//break;
						//case 50: // DISPOSISI KE BADAN
							//$data['TUJUAN_USER'] = $user_data->ID_DINAS;
							//$status = $this->disposisi_dinas($surat_masuk, $user_data, $disposisi);
							//break;
						//case 17: // DISPOSISI KE BAGIAN
							//$data['TUJUAN_USER'] = $user_data->ID_DINAS;
							//$status = $this->disposisi_bagian($surat_masuk, $user_data, $disposisi);
							//break;
					//}
					//$status = array('STATUS' => "berhasil", 'MESSAGE' => "berhasil Disposisi Surat !");
					//if ($status['STATUS'] == "gagal") {
					//	break;
					//}
					if ($surat_masuk->STATUS > 3) $this->delete_disposisi_before($surat_masuk->status_dari);
					$data_where = array('id_detail_sm' => $id_detail);
					$status_baru = array('id_status' => 3);
					$this->model_app->updateData('t_log_disposisi', $data_where, $status_baru);
					$this->model_app->insertData('t_disposisi', $data, $data2);
					$status['STATUS']   = "berhasil";
					$status['MESSAGE']  = "Berhasil Menambah Disposisi";
					// $status['ID_LEVEL'] = $kepada;
					$status['ID_LEVEL'] = $user_data->id_level;
				//} else{
					//$status['STATUS']   = "warning";
					//$status['MESSAGE']  = "Anda Tidak Dapat Mendisposisikan pada Orang yang sama 2 kali !";
					//$status['ID_LEVEL'] = $user_data->ID_LEVEL;
				//}

		} else{
			$status['STATUS']   = "gagal";
			$status['MESSAGE']  = "Tujuan Unit Tidak Boleh Kosong !";
			// $status['ID_LEVEL'] = $kepada;
		}

		echo json_encode($status);
	}

	function update_disposisi(){
		$id_log_disposisi = $this->input->post('id_log_disposisi');
		$id_disposisi     = $this->input->post('id_disposisi');
		$isi_disposisi    = $this->input->post('disposisi');
		$laporan          = $this->input->post('laporan');
		$data_where      = array('id_disposisi' => $id_disposisi);
		$disposisi       = $this->model_app->getSelectedData('t_disposisi', $data_where)->row();
		$this->db->trans_start();

		if (empty($disposisi)) { // JIKA TIDAK ADA LOG_DISPOSISI
			$status['STATUS']  = "gagal";
			$status['MESSAGE'] = "Disposisi, Tidak Ditemukan !";
			$this->db->trans_complete();
			echo json_encode($status);
			return;
		}

		$data_where2 = array('id_log_disposisi' => $id_log_disposisi);
		$this->model_app->deleteData('t_log_disposisi', $data_where2);

		$data['isi_disposisi'] = $isi_disposisi;
		if ($laporan == 1) {
			$data['flag'] = 1;
			$data['peminta_laporan'] = $this->insert_laporan($disposisi->id_detail_sm, $disposisi->status_dari);
		} else{
			$data['flag'] = null;
			$data['peminta_laporan'] = null;
		}
		$data2 = array('tgl_dispos' => "'".date('Y-m-d H:i:s')."'");
		$this->model_app->updateData('t_disposisi', $data_where, $data, $data2);
		$status['STATUS']  = "berhasil";
		$status['MESSAGE'] = "Berhasil, Update Disposisi !";
		$this->db->trans_complete();
		echo json_encode($status);
	}

	function hapus_disposisi(){
		$this->db->trans_start();
		$level            = $this->input->post('id_level');
		$id_disposisi     = $this->input->post('id_disposisi');
		$id_log_disposisi = $this->input->post('id_log_disposisi');
		$datawhere = array('id_disposisi' => $id_disposisi);
		$disposisi = $this->model_app->getSelectedData('t_disposisi', $datawhere)->row();

		$datawhere = array('id_detail_sm' => $disposisi->id_detail_sm, 'id_disposisi' => $disposisi->id_disposisi );
		$log_dispos = $this->model_app->getSelectedData('t_log_disposisi', $datawhere)->result();

		foreach ($log_dispos as $row) {
			$datawhere = array('id_disposisi' => $row->id_disposisi);
			$this->model_app->deleteData('t_disposisi', $datawhere);
			$this->model_app->deleteData('t_log_disposisi', $datawhere);
		}
		/*$data_where = array('ID_DISPOSISI'=> $id_disposisi );
		$this->model_app->deleteData('T_DISPOSISI', $data_where);
		$data_where = array('ID_LOG_DISPOSISI'=> $id_log_disposisi );
		$this->model_app->deleteData('T_LOG_DISPOSISI', $data_where);*/
		$status['STATUS']   = "berhasil";
		$status['ID_LEVEL'] = $level;
		$status['MESSAGE']  = "Berhasil, menghapus disposisi !";
		$this->db->trans_complete();
		echo json_encode($status);
	}

	function hapus_disposisi_sekre(){
		$this->db->trans_start();
		$level            = $this->input->post('id_level');
		$id_disposisi     = $this->input->post('id_disposisi');
		$id_log_disposisi = $this->input->post('id_log_disposisi');
		$id_unit          = $this->input->post('id_unit');
		$struktur         = $this->input->post('id_struktur');

		$struktur_dinas  = array(50,100,300);
		$struktur_bagian = array(41,60,70);
		$struktur_sekda = array(10,20,30,40);

		if (in_array($struktur, $struktur_dinas)) {
			$idDinas = substr($id_unit, 0,3);
		} elseif (in_array($struktur, $struktur_bagian)) {
			$idDinas = substr($id_unit, 0,6);
		} elseif (in_array($struktur, $struktur_sekda)) {
			$idDinas = $id_unit;
		}

		$datawhere = array('id_disposisi' => $id_disposisi);
		$disposisi = $this->model_app->getSelectedData('t_disposisi', $datawhere)->row();

		$datawhere = array('id_detail_sm' => $disposisi->id_detail_sm);
		$data_where = array('id_detail' => $disposisi->id_detail_sm);
		$data_like  = array('cast(id_unit as text)' => $idDinas);
		$this->model_app->deleteData('temp_tujuan_dispos', $data_where,$data_like);
		$log_dispos = $this->model_app->getSelectedData('t_log_disposisi', $datawhere,$data_like)->result();

		foreach ($log_dispos as $row) {
			$datawhere = array('id_disposisi' => $row->id_disposisi);
			$this->model_app->deleteData('t_disposisi', $datawhere);
			$this->model_app->deleteData('t_log_disposisi', $datawhere);
		}
		/*$data_where = array('ID_DISPOSISI'=> $id_disposisi );
		$this->model_app->deleteData('T_DISPOSISI', $data_where);
		$data_where = array('ID_LOG_DISPOSISI'=> $id_log_disposisi );
		$this->model_app->deleteData('T_LOG_DISPOSISI', $data_where);*/
		$status['STATUS']   = "berhasil";
		$status['ID_LEVEL'] = $level;
		$status['MESSAGE']  = "Berhasil, menghapus disposisi !";
		$this->db->trans_complete();
		echo json_encode($status);
	}

	function updatekordinator(){
		$this->db->trans_start();
		$id_surat            = $this->input->post('idsurat');
		$kordinator 		 = $this->input->post('kordinator');

		$data_where = array('id_detail' => $id_surat,'dari_unit'=>$this->session->userdata('id_dinas') );
		$log_dispos = $this->model_app->getSelectedData('temp_tujuan_dispos', $data_where)->result();
		foreach ($log_dispos as $row) {
			$cekdin	    = substr($row->id_unit, 0, 3);
			$din 		= $cekdin."%";
			$data_where = array('id_detail_sm' => $id_surat, 'cast(id_unit as text) LIKE' => $din );
			$data 		= array('kordinator'=>$kordinator);
			$this->model_app->updateData('t_log_disposisi', $data_where,$data);
		}
		/*$data_where = array('ID_DISPOSISI'=> $id_disposisi );
		$this->model_app->deleteData('T_DISPOSISI', $data_where);
		$data_where = array('ID_LOG_DISPOSISI'=> $id_log_disposisi );
		$this->model_app->deleteData('T_LOG_DISPOSISI', $data_where);*/
		$status['STATUS']   = "berhasil";
		$status['MESSAGE']  = "Berhasil, menambah kordinator !";
		$this->db->trans_complete();
		echo json_encode($status);
	}

	function hapus_laporan(){
		$this->db->trans_start();
		$id_laporan     = $this->input->post('id_laporan');
		$datawhere = array('id_laporan' => $id_laporan);
		$this->model_app->deleteData('t_sm_laporan', $datawhere);
		$this->model_app->deleteData('t_log_disposisi_laporan',$datawhere);
		$status['STATUS']   = "berhasil";
		$status['MESSAGE']  = "Berhasil, menghapus laporan !";
		$this->db->trans_complete();
		echo json_encode($status);

	}

	private function delete_disposisi_before($id_log_disposisi, $sendiri = null){
		$data_where = array('id_log_disposisi' => $id_log_disposisi);
		$log_dispos = $this->model_app->getSelectedData('t_log_disposisi', $data_where)->row();
		if (!empty($log_dispos)) {
			$data_where = array('id_disposisi' => $log_dispos->id_disposisi);
			$this->model_app->deleteData('t_disposisi', $data_where);
			if ($sendiri) {
				$data_where = array('dari_user' => $log_dispos->tujuan_user, 'tujuan_user' => $this->session->userdata('id'), 'id_detail_sm' => $log_dispos->id_detail_sm );
				$log_dispos2 = $this->model_app->getSelectedData('t_disposisi', $data_where)->row();
				if (!empty($log_dispos2)) {
					$data_where = array('id_disposisi' => $log_dispos2->id_disposisi);
					$this->model_app->deleteData('t_disposisi', $data_where);
				}
			}
		}
	}

	private function delete_disposisi_bagian_before($id_detail, $id_tujuan){
		$data_where = array('id_detail_sm' => $id_detail,'id_level'=> $id_tujuan,'tujuan_user !='=>$this->session->userdata('id'));
		$this->model_app->deleteData('t_disposisi', $data_where);
	}

	function cek_unit_user($kepala_dari, $staff_tujuan, $id_level_kepala_dari, $surat_masuk,$deadline)
	{
		if ($id_level_kepala_dari == 3 || $id_level_kepala_dari == 19) {
			$datawhere = array('id_user' => $kepala_dari);
			$user_dari = $this->model_app->getSelectedData('v_user', $datawhere)->row();

			$datawhere   = array('id_user' => $staff_tujuan);
			$user_tujuan = $this->model_app->getSelectedData('v_user', $datawhere)->row();
			// print_r($user_tujuan);exit;
			if ($user_dari->id_unit != $user_tujuan->id_unit) {
				$datawhere    = array('id_struktur'=> $user_dari->struktur_dinas, 'id_level'=> $id_level_kepala_dari, 'status_sk_sm'=> 2);
				$jenis_dispos = $this->model_app->getSelectedData('m_jenis_disposisi', $datawhere)->row();

				$datawhere   = array('id_level' => $jenis_dispos->id_level);
				$data_like   = array('cast(id_unit as text)' => $user_tujuan->id_unit);
				$user_kepala = $this->model_app->getSelectedData('v_user', $datawhere, $data_like)->row();

				$data_where = array('dari_user' => $kepala_dari, 'tujuan_user' => $user_kepala->id_user, 'id_detail_sm' => $surat_masuk->id_detail, );
				$cek_dispos = $this->model_app->getSelectedData('t_log_disposisi', $data_where)->row();

				if (!empty($user_kepala) && empty($cek_dispos)) {
					$data['flag']          = 3;
					$data['isi_disposisi'] = ucwords($user_kepala->nama_next)." Mengetahui";
					$data['dari_user']     = $kepala_dari;
					$data['tujuan_user']   = $user_kepala->id_user;
					$data['id_unit']       = $user_kepala->id_unit;
					$data['status']        = $jenis_dispos->level_disposisi;
					$data['status_dari']   = $surat_masuk->id_log_disposisi;
					$data['id_detail_sm']  = $surat_masuk->id_detail;
					$data['id_level']	   = $user_kepala->id_level;
                    $data['deadline']      = ($this->input->post('deadline'))?$this->input->post('deadline'):null;
                    if( $data['deadline'] !=null){
			        	$data['tgl_deadline']  = date('d-M-Y', strtotime('+'.$data['deadline'].' days'));
			        }

					$data2 				   = array('tgl_dispos' => "'".date('Y-m-d H:i:s')."'");
					$this->model_app->insertData('t_disposisi', $data, $data2);

					$data['flag']          = 3;
					$data['isi_disposisi'] = $jenis_dispos->nama_jenis;
					$data['dari_user']     = $user_kepala->id_user;
					$data['tujuan_user']   = null;
					$data['id_unit']       = $user_kepala->id_unit;
					$data['status']        = $jenis_dispos->level_disposisi;
					$data['status_dari']   = $surat_masuk->id_log_disposisi;
					$data['id_detail_sm']  = $surat_masuk->id_detail;
					$data['id_level']	   = $user_kepala->id_level;

					$data2 = array('tgl_dispos' => "'".date('Y-m-d H:i:s')."'");

					$datawhere = array(
						'tujuan_user' => $user_kepala->id_user, 'dari_user' => $kepala_dari,
						'status' => $jenis_dispos->level_disposisi, 'id_detail_sm' => $surat_masuk->id_detail);
					$this->model_app->updateData('t_disposisi', $datawhere, $data, $data2);
				}
			}
		}

	}

	function batal_disposisi(){
		$this->db->trans_start();

		$id_detail        = $this->input->post('id_surat_detail');
		$id_log_disposisi = $this->input->post('log');
		/*$id_user       = $this->input->post('id_user');
		$id_unit       = $this->input->post('id_unit');
		$sk_sm         = $this->input->post('sk_sm');
		$dari          = $this->input->post('dari');
		$status        = $this->input->post('status_level');*/
		$isi_disposisi = $this->input->post('isi_disposisi');

		$data_where     = array('id_log_disposisi' => $id_log_disposisi );
		$disposisi      = $this->model_app->getSelectedData('v_history_sm', $data_where)->row();
		$data_where     = array('id_log_disposisi' => $disposisi->status_dari );
		$next_disposisi = $this->model_app->getSelectedData('v_history_sm', $data_where)->row();
		$size           = 0;
		// print_r($disposisi); exit;
		if (!empty($disposisi)) {
			$data['flag'] 		   = 4;
			$data['isi_disposisi'] = $isi_disposisi;
			$data['dari_user']     = $this->session->userdata('id');
			$data['tujuan_user']   = $next_disposisi->tujuan_user;
			$data['id_unit']	   = $next_disposisi->id_unit_tujuan;
			$data['id_detail_sm']  = $id_detail;
			$data['id_level']      = $next_disposisi->id_level_tujuan;
			$data['status']        = $next_disposisi->status;
			$data['status_dari']   = $next_disposisi->status_dari;
			$data2 = array('tgl_dispos' => "'".date('Y-m-d H:i:s')."'");
			$data_where = array('id_disposisi' => $disposisi->id_disposisi);
			$this->model_app->updateData('T_DISPOSISI', $data_where, $data, $data2);
			$size++;
		}
		if ($size > 0) {
			$result['STATUS'] = "berhasil";
			$result['MESSAGE'] = "Berhasil Batal Disposisi Surat !";
		} else{
			$result['STATUS'] = "gagal";
			$result['MESSAGE'] = "Terjadi Kesalahan !";
		}
		$this->db->trans_complete();

		echo json_encode($result);
	}

	private function insert_laporan($id_detail_sm, $id_log_disposisi){
		$data_where = array('id_log_disposisi' => $id_log_disposisi, 'id_detail_sm' => $id_detail_sm);
		$disposisi  = $this->model_app->getSelectedData('t_log_disposisi', $data_where)->row();
		$peminta    = null;
		// print_r($disposisi);exit;
		if ($disposisi->flag == 1) {
			$peminta = $disposisi->peminta_laporan;
		} else{
			$peminta = $this->session->userdata('id');
		}
		return $peminta;
	}

	function delete_disposisi(){
		/*$id_log_disposisi = $this->input->post('id');
		$status_sm        = $this->input->post('status_sm');
		// $data = array('FLAG' => 5);
		$this->model_app->updateData('T_LOG_DISPOSISI');*/
	}

	/**
	 * [function mendisposisikan surat ke dinas jika dari walikota, wakil walikota, asisten, sekda]
	 * @param  [object] $surat_masuk 	[object dari tabel V_USER_DAN_DISPOSISI]
	 * @param  [object] $user        	[object dari tabel V_USER]
	 * @param  [string] $isi_disposisi 	[isi disposisi untuk dinas yang akan dikirimkan]
	 * @return [array]                [status hasil dari function insert_detail atau function disposisi_ke_dinas]
	 */
	function disposisi_dinas($surat_masuk, $user, $isi_disposisi=null){
		$status = $this->insert_detail($surat_masuk, $user);

		$status = $this->alur->disposisi_ke_dinas_bagian($status['id_detail'],1, $surat_masuk, $isi_disposisi);

		return $status;
	}

	function disposisi_bagian($param_sm, $user, $isi_disposisi=null){
		$status = $this->insert_detail($surat_masuk, $user);

		$status = $this->alur->disposisi_ke_dinas_bagian($status['id_detail'], 1, $surat_masuk, $isi_disposisi);

		return $status;
	}

	/**
	 * [insert data ke TABEL T_SURAT_MASUK DAN DETAIL_SURAT_MASUK]
	 * @param  [object] $surat_masuk [object dari tabel V_USER_DAN_DISPOSISI]
	 * @param  [object] $user        [object dari tabel V_USER]
	 * @return [array]               [array status berhasil / gagal]
	 */
	private function insert_detail($surat_masuk, $user){
		$data_sm = array(
			'id_sk_fk'          => $surat_masuk->id_sk_fk,
			'id_detail_fk'      => $surat_masuk->id_detail,
			'no_surat_masuk'    => $surat_masuk->no_surat_masuk,
			'id_kategori'       => $surat_masuk->id_kategori,
			'id_sifat'          => $surat_masuk->id_sifat,
			'id_jenis_lampiran' => $surat_masuk->id_jenis_lampiran,
			'perihal'           => $surat_masuk->perihal,
			);
		$data_where = array('id_detail_fk' => $surat_masuk->id_detail);
		$surat      = $this->model_app->getSelectedData('t_surat_masuk', $data_where)->row();
		if (empty($surat)) {
			$id_sm = $this->model_app->getMax('t_surat_masuk', 'id_surat_masuk')->row()->NO_MAX;
			$id_sm += 1;
			$data_sm['id_surat_masuk'] = $id_sm;
			$this->model_app->insertData('t_surat_masuk', $data_sm);
		} else{
			$data_where = array('id_surat_masuk' => $surat_masuk->id_surat_masuk);
			$id_sm      = $surat->id_surat_masuk;
			$this->model_app->updateData('t_surat_masuk', $data_where, $data_sm);
		}

		$data_detail = array(
			'id_surat_masuk' => $id_sm,
			'dari'           => $surat_masuk->id_unit,
			'skpd'           => $user->id_dinas,
		);
		$tgl          = date('Y-m-d H:i:s');
		$data_detail2 = array(
			'"tgl_surat"' => "'".$surat_masuk->tgl_surat."'",
			'"tgl_terima"' => "'".$tgl."'"
		);
		$data_where = array('id_surat_masuk'=>$id_sm,'dari'=>$surat_masuk->id_unit,'skpd'=>$user->id_dinas);
		$detail_sm  = $this->model_app->getSelectedData('detail_surat_masuk', $data_where)->row();
		if (empty($detail_sm)) {
			$id_detail = $this->model_app->getMax('detail_surat_masuk', 'id_detail')->row()->NO_MAX;
			$id_detail += 1;
			$data_detail['id_detail'] = $id_detail;
			$this->model_app->insertData('detail_surat_masuk', $data_detail, $data_detail2);
		} else{
			$id_detail = $detail_sm->id_detail;
			$this->model_app->updateData('detail_surat_masuk', $data_where, $data_detail, $data_detail2);
		}
		$status = array('STATUS' => 'berhasil', 'MESSAGE' => 'Berhasil kirim disposisi ke tujuan !', 'ID_DETAIL' => $id_detail );
		return $status;
	}

	/**
	 * kirim data ke service desk untuk integrasi sistem
	 * @param  [array] $data_disposisi 	[data array dari disposisi yang akan dimasukkan ke TABEL T_DISPOSISI]
	 * @return [type]              [description]
	 */
	public function send_serdesk($data_disposisi, $dari = null){
		$datawhere   = array('id_detail' => $data_disposisi['id_detail_sm']);
		$surat_masuk = $this->model_app->getSelectedData('V_MASTER_MASUK', $datawhere)->row();

		$datawhere = array('ID_UNIT' => $surat_masuk->SKPD);
		$unit = $this->model_app->getSelectedData('M_UNIT_KERJA',$datawhere)->row();

		$datawhere = array('ID_LOG_DISPOSISI' => $data_disposisi['STATUS_DARI']);
		$history   = $this->model_app->getSelectedData('V_HISTORY_SM', $datawhere)->row();
		$tgl       = $history->TGL_LOG_DISPOSISI;

		$api_key              = "2F3L0LHDBKV6O72FLYD3KJ1QRRBYDWWEP22V6JHJYESLVT8F6V1YPTHT";
		$user_name            = "punky123";
		$data = array(
			'DESKRIPSI'       => $data_disposisi['ISI_DISPOSISI'],
			// 'NAMA_PELAPOR'    => $unit->NAMA_UNIT,
			// 'NAMA_PELAPOR'    => $dari, //BUG FIX DARI ALI
			'ALAMAT'          => $unit->ALAMAT,
			'EMAIL'           => $unit->EMAIL,
			'TELEPON'         => $unit->TELP,
			'JALUR'           => 2,
			'KOORDINATOR_ID'  => $data_disposisi['DARI_USER'],
			'KATEGORI_ID'     => $data_disposisi['KATEGORI'],
			'SUB_KATEGORI_ID' => $data_disposisi['SUB_KATEGORI'],
			'TANGGAL'         => $tgl,
			'SURAT'           => base_url('pdf_serdesk/serdesk/'.$surat_masuk->SURAT),
			// 'OPD'             => $unit->NAMA_UNIT,
			'OPD'             => $dari,
			'ID_USER'         => $data_disposisi['DARI_USER'],
			'STATUS'          => 1,
		);
		if (!empty($data_disposisi['APLIKASI_ID'])) {
			$data['APLIKASI'] = $data_disposisi['APLIKASI_ID'];
		}
		if ($data_disposisi['SUB_KATEGORI'] == 181) {
			$data['NAMA_PERMINTAAN'] = $data_disposisi['ISI_DISPOSISI'];
		}
		if (!empty($surat_masuk->LAMPIRAN)) {
			$data['LAMPIRAN'] = base_url($surat_masuk->LAMPIRAN);
		}
		if (!empty($surat_masuk->LAMPIRAN_PENDUKUNG)) {
			$data['LAMPIRAN_PENDUKUNG'] = base_url($surat_masuk->LAMPIRAN_PENDUKUNG);
		}
		$data['nonce']        = time();
		$data['action']       = "insert_tabel_permintaan";

		// var_dump($data);
		$data           = json_encode($data);
		$encrypted_data = mcrypt_encrypt(MCRYPT_BLOWFISH, $api_key, $data, MCRYPT_MODE_ECB);
		$plain_text     = base64_encode($encrypted_data);

		$crl = curl_init();
		$headr   = array();
		$headr[] = 'Content-length: 0';
		$headr[] = 'Content-type: application/json';
		$headr[] = 'CLIENT-ID: ' . $user_name;
		$headr[] = 'API-REQUEST: ' . $plain_text;
		curl_setopt($crl, CURLOPT_URL, 'https://api.surabaya.go.id');
		curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);
		curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($crl, CURLOPT_POST, true);
		curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($crl, CURLOPT_TIMEOUT, 5);
		$rest = curl_exec($crl);
		curl_close($crl);

		$status_json = json_decode($rest, true);

		return $status_json;
	}
}
