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
			if($getOneUser[0]['role'] == 1){ //HR
				$count_pending_cuti = $this->countCutiRequest_pending();
				return view('request_cuti/request_cuti')->with('getOneUser', $getOneUser)->with('count_pending_cuti', $count_pending_cuti);
			}else{
				abort(404);
			}
		}
	}
	
	function showAll_cutiRequest_pending(){
		$all_cuti = DB::select('SELECT c.id_cuti, c.type_cuti, t.nama_type_cuti, c.desc_cuti, c.tgl_mulai_cuti, c.jumlah_hari_cuti, ue.nama_user, ue.nik_user, ue.department, c.requested_date, s.judul_status, c.hr_nik_approve FROM list_cuti c 
									INNER JOIN user ue ON c.user_cuti=ue.nik_user 
									INNER JOIN status_cuti s ON s.id_status_cuti=c.status
									INNER JOIN type_cuti t ON t.id_type_cuti=c.type_cuti WHERE c.status = 2 ORDER BY c.requested_date DESC');
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
		if(isset($_GET['action']) && $_GET['action'] == "approve"){
			$all_cuti = DB::update("UPDATE list_cuti SET status = '1' WHERE list_cuti.id_cuti = ".$_GET['id_cuti']);
			$result = json_decode(json_encode($all_cuti), true);
			return $result;
		}
		else if(isset($_GET['action']) && $_GET['action'] == "reject"){
			$all_cuti = DB::update("UPDATE list_cuti SET status = '3' WHERE list_cuti.id_cuti = ".$_GET['id_cuti']);
			$result = json_decode(json_encode($all_cuti), true);
			return $result;
		}
		else{
			return 0;
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
		//	$json = [];
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
}