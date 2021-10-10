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

class LoginController extends Controller
{
    function login(){
		return view('login');
	}
	function logout(){
		Session::flush();
		return view('login');
	}
	
	function authLogin(){
		//return $_REQUEST;
		$all_cuti = DB::select("SELECT * FROM user where nik_user='".$_GET['nik']."' AND password='".MD5($_GET['password'])."'");
		$result = json_decode(json_encode($all_cuti), true);
		//return $result;
		if(count($result) > 0){
			Session::put('nik_user', trim($result[0]['nik_user']));
			return redirect('home');
		}else{
			return redirect('login');
		}
		
	}
	
}