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
			return view('home/home')->with('getOneUser', $getOneUser);
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
}