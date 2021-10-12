<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use GuzzleHttp\Client;
use Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\DB;
date_default_timezone_set("Asia/Jakarta");

class CutiController extends Controller
{
    function kalender_cuti(){
		if(!Session::get('nik_user')){
            return redirect('login');
        }else{
			$getOneUser = $this->getOneUser();
			if($getOneUser[0]['role'] == 1){ //HR
				$showCalender = $this->showCalender();
				//
				$count_pending_cuti = $this->countCutiRequest_pending();
				return view('kalender_cuti/kalender_cuti')->with('showCalender', $showCalender)->with('getOneUser', $getOneUser)->with('count_pending_cuti', $count_pending_cuti);
		
			}else{
				abort(404);
			}
			
		}
	}
	
    function permintaan_cuti(){
		if(!Session::get('nik_user')){
            return redirect('login');
        }
		else{
			$getOneUser = $this->getOneUser();
			if($getOneUser[0]['role'] == 1){ //HR
				$all_cuti_pending = $this->showAll_cutiRequest_pending();
				$count_pending_cuti = $this->countCutiRequest_pending();
				
				return view('permintaan_cuti/permintaan_cuti')->with('all_cuti_pending', $all_cuti_pending)->with('getOneUser', $getOneUser)->with('count_pending_cuti', $count_pending_cuti);
			}else{
				abort(404);
			}
		}
	}
	
    function request_cuti(){
		if(!Session::get('nik_user')){
            return redirect('login');
        }
		else{
			$getOneUser = $this->getOneUser();
				$getKategoriCuti = $this->getKategoriCuti();
				$count_pending_cuti = $this->countCutiRequest_pending();
				$getReqCuti_OneUser = $this->getReqCuti_OneUser();
				return view('request_cuti/request_cuti')->with('getOneUser', $getOneUser)->with('getKategoriCuti', $getKategoriCuti)->with('count_pending_cuti', $count_pending_cuti)->with('getReqCuti_OneUser', $getReqCuti_OneUser);
			
		}
	}
	
    function info_cuti(){
		if(!Session::get('nik_user')){
            return redirect('login');
        }
		else{
			$getOneUser = $this->getOneUser();
				$getKategoriCuti = $this->getKategoriCuti();
				$count_pending_cuti = $this->countCutiRequest_pending();
				$getReqCuti_OneUser = $this->getReqCuti_OneUser();
				$getOneUser_cuti6BulanTerakhir = $this->getOneUser_cuti6BulanTerakhir();
				$getDetailCuti6BulanTerakhir = $this->getDetailCuti6BulanTerakhir();
				return view('info_cuti/info_cuti')->with('getOneUser', $getOneUser)->with('getDetailCuti6BulanTerakhir', $getDetailCuti6BulanTerakhir)->with('getKategoriCuti', $getKategoriCuti)->with('count_pending_cuti', $count_pending_cuti)->with('getReqCuti_OneUser', $getReqCuti_OneUser)->with('getOneUser_cuti6BulanTerakhir', $getOneUser_cuti6BulanTerakhir);
			
		}
	}
	
	function getOneUser_cutiPeriode(){
		$getcountCuti = DB::select("SELECT YEAR(tgl_mulai_cuti) as tahun, MONTH(tgl_mulai_cuti) as bulan, sum(jumlah_hari_cuti) as total_cuti, status as status_cuti FROM `list_cuti` where user_cuti='".Session::get('nik_user')."' OR user_cuti='99999' GROUP BY YEAR(tgl_mulai_cuti), MONTH(tgl_mulai_cuti), status;");
		$result = json_decode(json_encode($getcountCuti), true);
		return $result;
	}
	
	function getOneUser_cuti6BulanTerakhir(){
		$totalCuti = $this->getOneUser_cutiPeriode();
		$json = [];
		//return $totalCuti;
		for($i=0;$i<6;$i++){
			$json[$i]['id'] = $i;
			$json[$i]['date'] = Date('F Y', strtotime(Date('Y-m-d').' -'.$i.' months'));
			$json[$i]['status']['approved'] = 0;
			$json[$i]['status']['pending'] = 0;
			$json[$i]['status']['rejected'] = 0;
			for($j=0;$j<count($totalCuti);$j++){
				if(($totalCuti[$j]['tahun'].'-'.$totalCuti[$j]['bulan']) == Date('Y-n', strtotime(Date('Y-m-d').' -'.$i.' months'))){
					if($totalCuti[$j]['status_cuti'] == 1){
						$json[$i]['status']['approved']+=(int)$totalCuti[$j]['total_cuti'];
					}
					else if($totalCuti[$j]['status_cuti'] == 2){
						$json[$i]['status']['pending']+=(int)$totalCuti[$j]['total_cuti'];
					}
					else if($totalCuti[$j]['status_cuti'] == 3){
						$json[$i]['status']['rejected']+=(int)$totalCuti[$j]['total_cuti'];
					}
				}
			}
		}
		return $json;
	}
	
	function getDetailCuti6BulanTerakhir(){
		$getCuti = DB::select("SELECT list_cuti.*, type_cuti.nama_type_cuti, status_cuti.judul_status, user.nama_user FROM `list_cuti` 
								LEFT JOIN type_cuti on list_cuti.type_cuti = type_cuti.id_type_cuti
								LEFT JOIN status_cuti on list_cuti.status = status_cuti.id_status_cuti
								LEFT JOIN user on list_cuti.hr_nik_approve = user.nik_user
								where (user_cuti='1901' OR user_cuti='99999')
								AND YEAR(tgl_mulai_cuti) = '".Date('Y')."' ORDER BY list_cuti.tgl_mulai_cuti DESC");
		$result = json_decode(json_encode($getCuti), true);
		return $result;
	}
	
    function create_cuti_hr(){
		if(!Session::get('nik_user')){
            return redirect('login');
        }
		else{
			$getOneUser = $this->getOneUser();
			if($getOneUser[0]['role'] == 1){ //HR
				$all_cuti_pending = $this->showAll_cutiRequest_pending();
				$getKategoriCuti = $this->getKategoriCuti();
				$count_pending_cuti = $this->countCutiRequest_pending();
				$getAllUser = $this->getAllUser();
				return view('create_cuti/create_cuti')->with('getOneUser', $getOneUser)->with('getKategoriCuti', $getKategoriCuti)->with('getAllUser', $getAllUser);
			}else{
				abort(404);
			}
		}
	}
	
	function showAll_cutiRequest_pending(){
		$all_cuti = DB::select('SELECT c.id_cuti, c.attachment, c.type_cuti, c.requested_user, t.nama_type_cuti, c.desc_cuti, c.tgl_mulai_cuti, c.jumlah_hari_cuti, IFNULL(ue.nama_user, 0) as nama_user, IFNULL(ue.nik_user, 0) as nik_user, IFNULL(ue.department, 0) as department, c.requested_date, s.judul_status, c.hr_nik_approve FROM list_cuti c 
									LEFT JOIN user ue ON c.user_cuti=ue.nik_user
									INNER JOIN status_cuti s ON s.id_status_cuti=c.status
									INNER JOIN type_cuti t ON t.id_type_cuti=c.type_cuti 
									
									WHERE c.status = 2 ORDER BY c.requested_date DESC');
		$result = json_decode(json_encode($all_cuti), true);
		return $result;
	}
	
	function showAll_cutiRequest_approved(){
		$all_cuti = DB::select('SELECT c.id_cuti, c.type_cuti, t.nama_type_cuti, c.desc_cuti, c.tgl_mulai_cuti, c.jumlah_hari_cuti, IFNULL(ue.nama_user, 0) as nama_user, IFNULL(ue.nik_user, 0) as nik_user, IFNULL(ue.department, 0) as department, c.requested_date, s.judul_status, c.hr_nik_approve FROM list_cuti c 
									LEFT JOIN user ue ON c.user_cuti=ue.nik_user 
									INNER JOIN status_cuti s ON s.id_status_cuti=c.status
									INNER JOIN type_cuti t ON t.id_type_cuti=c.type_cuti WHERE c.status = 1 ORDER BY c.requested_date DESC');
		$result = json_decode(json_encode($all_cuti), true);
		return $result;
	}
	
	function showAll_cutiRequest_rejected(){
		$all_cuti = DB::select('SELECT c.id_cuti, c.type_cuti, t.nama_type_cuti, c.desc_cuti, c.tgl_mulai_cuti, c.jumlah_hari_cuti, IFNULL(ue.nama_user, 0) as nama_user, IFNULL(ue.nik_user, 0) as nik_user, IFNULL(ue.department, 0) as department, c.requested_date, s.judul_status, c.hr_nik_approve FROM list_cuti c 
									LEFT JOIN user ue ON c.user_cuti=ue.nik_user 
									INNER JOIN status_cuti s ON s.id_status_cuti=c.status
									INNER JOIN type_cuti t ON t.id_type_cuti=c.type_cuti WHERE c.status = 3 ORDER BY c.requested_date DESC');
		$result = json_decode(json_encode($all_cuti), true);
		return $result;
	}
	
	function countCutiRequest_pending(){
		return (int)count($this->showAll_cutiRequest_pending());
	}
	
	function updateCuti(){
		//return $_REQUEST;
		$getOneUser = $this->getOneUser();
		$all_cuti = DB::select('SELECT * from list_cuti where id_cuti ='.$_GET['id_cuti']);
		$getOneCuti = json_decode(json_encode($all_cuti), true);
		if(count($getOneCuti) > 0 && $getOneCuti[0]['requested_user'] != Session::get('nik_user')){
			if(isset($_GET['action']) && $_GET['action'] == "approve"){
				$get_cuti_this_month = DB::select("SELECT user_cuti, sum(jumlah_hari_cuti) as total_cuti from list_cuti where MONTH(tgl_mulai_cuti) = MONTH(CURRENT_DATE())
													AND YEAR(tgl_mulai_cuti) = YEAR(CURRENT_DATE()) AND status = 1 AND user_cuti='".$getOneCuti[0]['user_cuti']."' GROUP BY user_cuti");
				$cutiThisMonth = json_decode(json_encode($get_cuti_this_month), true);
					//return $getOneCuti[0]['user_cuti'];
				if(count($cutiThisMonth) > 0 &&((int)$cutiThisMonth[0]['total_cuti']+(int)$getOneCuti[0]['jumlah_hari_cuti']) <= 12){
					$all_cuti = DB::update("UPDATE list_cuti SET status = '1', hr_nik_approve = '".Session::get('nik_user')."' WHERE list_cuti.id_cuti = ".$_GET['id_cuti']);
					$result = json_decode(json_encode($all_cuti), true);
					return $result;
				}else{
					return 3;
				}
			}
			else if(isset($_GET['action']) && $_GET['action'] == "reject"){
				$all_cuti = DB::update("UPDATE list_cuti SET status = '3', hr_nik_approve = '".Session::get('nik_user')."' WHERE list_cuti.id_cuti = ".$_GET['id_cuti']);
				$result = json_decode(json_encode($all_cuti), true);
				return $result;
			}
			else{
				return 0;
			}
		}else{
			return 4;
		}
		
	}
	
	function getAllUser(){
		$all_cuti = DB::select("SELECT * FROM user");
		$result = json_decode(json_encode($all_cuti), true);
		return $result;
	}
	
	function statusCutiColor($status){
		$arr = [
				"",
				"#FF6C38", //tahunan
				"#ffabea", //sakit
				"#a6ffb2", //melahirkan
				"#b8baff", //bersama
				"#ababab", //alasan khusus
				"#8E8E8E", //terlambat
		];
		
		return $arr[$status];
	}
	
	function showCalender(){
		$all_cuti_approved = $this->showAll_cutiRequest_approved();
		//return $all_cuti_approved;
		
		/*
		title          : 'All Day Event',
          start          : new Date(y, m, 1),
          backgroundColor: '#f56954', //red
          borderColor    : '#f56954', //red
          allDay         : true
		*/
		$json = [];
		for($i=0;$i<count($all_cuti_approved);$i++){
			
			$json[$i]['title'] = '[CUTI '.$all_cuti_approved[$i]['nama_type_cuti'].'] '.$all_cuti_approved[$i]['nik_user'];
			$json[$i]['start'] = date('Y-m-d', strtotime($all_cuti_approved[$i]['tgl_mulai_cuti']));
			$json[$i]['end'] = date('Y-m-d', strtotime($all_cuti_approved[$i]['tgl_mulai_cuti'].('+'.($all_cuti_approved[$i]['jumlah_hari_cuti']-1).' days')));
			$json[$i]['backgroundColor'] = $this->statusCutiColor($all_cuti_approved[$i]['type_cuti']);
			$json[$i]['borderColor'] = $this->statusCutiColor($all_cuti_approved[$i]['type_cuti']);
			//$json[$i]['allDay'] = true;
		}
		
		return $json;
	}
	
	function getOneUser(){
		$one_user = DB::select("SELECT * FROM user where nik_user='".Session::get('nik_user')."'");
		$result = json_decode(json_encode($one_user), true);
		if(count($result) > 0){
			return $result;
		}else{
			return redirect('login');
		}
	}
	
	function getKategoriCuti(){
		$one_user = DB::select("SELECT * FROM type_cuti");
		$result = json_decode(json_encode($one_user), true);
		if(count($result) > 0){
			return $result;
		}else{
			return [];
		}
	}
	
	function requestCuti(){
		//return $_REQUEST;
		$allImg = $_FILES['uploadedFile'];
		$limit = 1 * 1024 * 1024; //1MB.
		$ext = pathinfo($allImg['name'], PATHINFO_EXTENSION);
		
		$lokasi = 'assets/img/pict_chat/'.Session::get('nik_user').'/';
		//$resultImg = [];
		$totalHaritmp = strtotime($_POST['last_date']) - strtotime($_POST['first_date'].' -1 days');
		$totalHari = round($totalHaritmp / (60 * 60 * 24));
		if($allImg['tmp_name'] != ""){
			if (!file_exists($lokasi)) {
				mkdir($lokasi, 0777, true);
			}
			if($allImg['size']<= $limit){
				$newfilename= Session::get('nik_user').'_'.$this->getDatetimeNowName().'.'.$ext;

				if(move_uploaded_file( $allImg['tmp_name'], $lokasi.$newfilename )){
					$one_user = DB::insert("INSERT INTO `list_cuti` 
							(`type_cuti`, `desc_cuti`, `attachment`, `tgl_mulai_cuti`, `jumlah_hari_cuti`, `user_cuti`, `requested_date`, `requested_user`, `status`, `hr_nik_approve`) VALUES 
								('".$_POST['type_cuti']."', '".$_POST['desc_cuti']."', '".$lokasi.$newfilename."', '".$_POST['first_date']."', '".$totalHari."', '".Session::get('nik_user')."', '".Date('Y-m-d H:i:s')."', '".Session::get('nik_user')."', '2', NULL)");
					$result = json_decode(json_encode($one_user), true);
					return $result;
					
				}
			}else{
				return 3;
			}
		}else{
			$one_user = DB::insert("INSERT INTO `list_cuti` 
							(`type_cuti`, `desc_cuti`, `attachment`, `tgl_mulai_cuti`, `jumlah_hari_cuti`, `user_cuti`, `requested_date`, `requested_user`, `status`, `hr_nik_approve`) VALUES 
								('".$_POST['type_cuti']."', '".$_POST['desc_cuti']."', NULL, '".$_POST['first_date']."', '".$totalHari."', '".Session::get('nik_user')."', '".Date('Y-m-d H:i:s')."', '".Session::get('nik_user')."', '2', NULL)");
					$result = json_decode(json_encode($one_user), true);
					return $result;
		}
		
		
	}
	
	function createCuti(){
		//return $_REQUEST;
		$allImg = $_FILES['uploadedFile'];
		$limit = 1 * 1024 * 1024; //1MB.
		$ext = pathinfo($allImg['name'], PATHINFO_EXTENSION);
		
		$lokasi = 'assets/img/pict_chat/'.Session::get('nik_user').'/';
		//$resultImg = [];
		$totalHaritmp = strtotime($_POST['last_date']) - strtotime($_POST['first_date'].' -1 days');
		$totalHari = round($totalHaritmp / (60 * 60 * 24));
		if($allImg['tmp_name'] != ""){
			if (!file_exists($lokasi)) {
				mkdir($lokasi, 0777, true);
			}
			if($allImg['size']<= $limit){
				$newfilename= Session::get('nik_user').'_'.$this->getDatetimeNowName().'.'.$ext;

				if(move_uploaded_file( $allImg['tmp_name'], $lokasi.$newfilename )){
					$one_user = DB::insert("INSERT INTO `list_cuti` 
							(`type_cuti`, `desc_cuti`, `attachment`, `tgl_mulai_cuti`, `jumlah_hari_cuti`, `user_cuti`, `requested_date`, `requested_user`, `status`, `hr_nik_approve`) VALUES 
								('".$_POST['type_cuti']."', '".$_POST['desc_cuti']."', '".$lokasi.$newfilename."', '".$_POST['first_date']."', '".$totalHari."', '".$_POST['user_cuti']."', '".Date('Y-m-d H:i:s')."', '".Session::get('nik_user')."', '2', NULL)");
					$result = json_decode(json_encode($one_user), true);
					return $result;
					
				}
			}else{
				return 3;
			}
		}else{
			$one_user = DB::insert("INSERT INTO `list_cuti` 
							(`type_cuti`, `desc_cuti`, `attachment`, `tgl_mulai_cuti`, `jumlah_hari_cuti`, `user_cuti`, `requested_date`, `requested_user`, `status`, `hr_nik_approve`) VALUES 
								('".$_POST['type_cuti']."', '".$_POST['desc_cuti']."', NULL, '".$_POST['first_date']."', '".$totalHari."', '".$_POST['user_cuti']."', '".Date('Y-m-d H:i:s')."', '".Session::get('nik_user')."', '2', NULL)");
					$result = json_decode(json_encode($one_user), true);
					return $result;
		}
		
		
	}
	
	function getDatetimeNowName() {
		$tz_object = new \DateTimeZone('Asia/Jakarta');
		//date_default_timezone_set('Brazil/East');

		$datetime = new \DateTime();
		$datetime->setTimezone($tz_object);
		return $datetime->format('YmdHis');
	}
	
	function getReqCuti_OneUser(){
		$one_user = DB::select("SELECT c.*, t.nama_type_cuti FROM list_cuti c 
								INNER JOIN type_cuti t ON t.id_type_cuti = c.type_cuti 
								where MONTH(c.tgl_mulai_cuti) = MONTH(CURRENT_DATE())
								AND YEAR(c.tgl_mulai_cuti) = YEAR(CURRENT_DATE())
								AND c.user_cuti='".Session::get('nik_user')."' order by c.requested_date ASC");
		$result = json_decode(json_encode($one_user), true);
		return $result;
	}
}