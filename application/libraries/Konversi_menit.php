<?php
class Konversi_menit extends CI_Controller {
    protected $_ci;

    function __construct(){
        $this->_ci = &get_instance();

    }



    function hitung($jumlahMenit){
		if($jumlahMenit == '0'){
			$retr_arr["menit"] 	=  	"-";
			$retr_arr["menit_angka"] 	=  	"0";
			$retr_arr["jam"] 	=	"-";
			$retr_arr["jam_angka"] 	=	"0";


		}
		elseif($jumlahMenit < 60){
		//	$jumlahMenit =  sprintf('%02d', $jumlahMenit);
			$jumlahMenit = $jumlahMenit;

			$retr_arr["menit"] 	=  	$jumlahMenit;
			$retr_arr["menit_angka"] 	=  	$jumlahMenit;
			$retr_arr["jam"] 	=	"-";
			$retr_arr["jam_angka"] 	=	"0";

		}
		else{
			$jumlahJam 			=	$jumlahMenit / 60;
			$jumJam				= 	floor($jumlahJam);

			//echo "jumlahJam ".$jumJam." ";

			$jamDalamMenit 		= 	$jumJam * 60;
			$jumlahMenitAkhir   = 	$jumlahMenit - $jamDalamMenit;

			//$jumJam 			= sprintf('%02d', $jumJam);
			//$jumlahMenitAkhir 	= sprintf('%02d', $jumlahMenitAkhir);
			if($jumlahMenitAkhir == '0'){
				$retr_arr["menit"] 	=  	"-";
				$retr_arr["menit_angka"] 	=  	"0";
			}
			else{
				$retr_arr["menit"] 	=  	$jumlahMenitAkhir;
				$retr_arr["menit_angka"] 	=  $jumlahMenitAkhir;
			}
			$retr_arr["jam"] 	=	$jumJam;
			$retr_arr["jam_angka"] 	=	$jumJam;


		}
		return $retr_arr;

    }

}
