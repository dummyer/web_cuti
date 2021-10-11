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
	
    function create_cuti_hr(){
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
	
	function showAll_cutiRequest_pending(){
		$all_cuti = DB::select('SELECT c.id_cuti, c.type_cuti, t.nama_type_cuti, c.desc_cuti, c.tgl_mulai_cuti, c.jumlah_hari_cuti, ue.nama_user, ue.nik_user, ue.department, c.requested_date, s.judul_status, c.hr_nik_approve FROM list_cuti c 
									INNER JOIN user ue ON c.user_cuti=ue.nik_user 
									INNER JOIN status_cuti s ON s.id_status_cuti=c.status
									INNER JOIN type_cuti t ON t.id_type_cuti=c.type_cuti 
									
									WHERE c.status = 2 ORDER BY c.requested_date DESC');
		$result = json_decode(json_encode($all_cuti), true);
		return $result;
	}
	
	function showAll_cutiRequest_approved(){
		$all_cuti = DB::select('SELECT c.id_cuti, c.type_cuti, t.nama_type_cuti, c.desc_cuti, c.tgl_mulai_cuti, c.jumlah_hari_cuti, ue.nama_user, ue.nik_user, ue.department, c.requested_date, s.judul_status, c.hr_nik_approve FROM list_cuti c 
									INNER JOIN user ue ON c.user_cuti=ue.nik_user 
									INNER JOIN status_cuti s ON s.id_status_cuti=c.status
									INNER JOIN type_cuti t ON t.id_type_cuti=c.type_cuti WHERE c.status = 1 ORDER BY c.requested_date DESC');
		$result = json_decode(json_encode($all_cuti), true);
		return $result;
	}
	
	function showAll_cutiRequest_rejected(){
		$all_cuti = DB::select('SELECT c.id_cuti, c.type_cuti, t.nama_type_cuti, c.desc_cuti, c.tgl_mulai_cuti, c.jumlah_hari_cuti, ue.nama_user, ue.nik_user, ue.department, c.requested_date, s.judul_status, c.hr_nik_approve FROM list_cuti c 
									INNER JOIN user ue ON c.user_cuti=ue.nik_user 
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
		if(count($getOneCuti) > 0 && $getOneCuti[0]['user_cuti'] != Session::get('nik_user')){
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
	
	function statusCutiColor($status){
		$arr = [
				"",
				"#ffe699", //tahunan
				"#ffabea", //sakit
				"#a6ffb2", //melahirkan
				"#b8baff", //bersama
				"#ababab", //alasan khusus
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
							(`type_cuti`, `desc_cuti`, `attachment`, `tgl_mulai_cuti`, `jumlah_hari_cuti`, `user_cuti`, `requested_date`, `status`, `hr_nik_approve`) VALUES 
								('".$_POST['type_cuti']."', '".$_POST['desc_cuti']."', '".$lokasi.$newfilename."', '".$_POST['first_date']."', '".$totalHari."', '".Session::get('nik_user')."', '".Date('Y-m-d H:i:s')."', '2', NULL)");
					$result = json_decode(json_encode($one_user), true);
					return $result;
					
				}
			}else{
				return 3;
			}
		}else{
			$one_user = DB::insert("INSERT INTO `list_cuti` 
							(`type_cuti`, `desc_cuti`, `attachment`, `tgl_mulai_cuti`, `jumlah_hari_cuti`, `user_cuti`, `requested_date`, `status`, `hr_nik_approve`) VALUES 
								('".$_POST['type_cuti']."', '".$_POST['desc_cuti']."', NULL, '".$_POST['first_date']."', '".$totalHari."', '".Session::get('nik_user')."', '".Date('Y-m-d H:i:s')."', '2', NULL)");
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