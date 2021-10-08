<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use GuzzleHttp\Client;
use Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\UrlGenerator;

class CutiController extends Controller
{
    function kalender_cuti(){
		return view('kalender_cuti/kalender_cuti');
	}
}