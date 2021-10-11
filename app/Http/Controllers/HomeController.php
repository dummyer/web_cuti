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

class HomeController extends Controller
{
    function index(){
		if(!Session::get('nik_user')){
            return redirect('login');
        }else{
			$getOneUser = $this->getOneUser();
			$count_pending_cuti = $this->countCutiRequest_pending();
			return view('home/home')->with('getOneUser', $getOneUser)->with('count_pending_cuti', $count_pending_cuti);
		}
		
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
	
	function countCutiRequest_pending(){
		return (int)count($this->showAll_cutiRequest_pending());
	}
	
	function showAll_cutiRequest_pending(){
		$all_cuti = DB::select('SELECT c.id_cuti, c.type_cuti, t.nama_type_cuti, c.desc_cuti, c.tgl_mulai_cuti, c.jumlah_hari_cuti, ue.nama_user, ue.nik_user, ue.department, c.requested_date, s.judul_status, c.hr_nik_approve FROM list_cuti c 
									INNER JOIN user ue ON c.user_cuti=ue.nik_user 
									INNER JOIN status_cuti s ON s.id_status_cuti=c.status
									INNER JOIN type_cuti t ON t.id_type_cuti=c.type_cuti WHERE c.status = 2 ORDER BY c.requested_date DESC');
		$result = json_decode(json_encode($all_cuti), true);
		return $result;
	}
}