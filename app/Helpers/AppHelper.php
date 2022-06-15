<?php

namespace App\Helpers;
use DateTime;

class AppHelper{
	public static function instance(){
        return new AppHelper();
    }

	public function mfRp($value){
		if($value == 0){
			return "-";
		}
		else{
			return 'Rp '.number_format($value,0,",",".").',-';
		}
	}

	public function mf($value){
		if($value == 0){
			return "-";
		}
		else if($value < 0){
			return '('.number_format($value * -1,0,",",".").')';
		}
		else{
			return number_format($value,0,",",".");
		}
	}

	public function quantityf($value){
		if($value == 0){
			return "-";
		}
		else{
			return $value;
		}
	}

	public function npwpf($value){
		if(strlen($value) == 14){
			$value = '0'.$value;
		}
		return preg_replace('~(\d{2})(\d{3})(\d{3})(\d{1})(\d{3})(\d{3})~', '$1.$2.$3.$4-$5.$6', $value);
	}


	public function getMonthID($month){
		$month = (int) $month;
		$monthFullID = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
		return $monthFullID[$month - 1];
	}

	public function getDayID($day){
		$day = (int) $day;
		$dayFullID = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
		return $dayFullID[$day];
	}

	public function get25thPrevMonth($date){
		return (new DateTime($date->format('Y-m-25')))->modify('-1 month');
	}
}

?>