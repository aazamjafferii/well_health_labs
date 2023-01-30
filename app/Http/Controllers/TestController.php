<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TestController extends Controller
{
    public function index(){
        return view('welcome');
    }
}
