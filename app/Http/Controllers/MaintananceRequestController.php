<?php

namespace App\Http\Controllers;

use App\Models\MaintananceRquest; 
use Illuminate\Http\Request;

class MaintananceRequestController extends Controller
{
    public function index()
    {
        $requests = MaintananceRquest::all();
        return view('maintenance.index', compact('requests'));
    }   
}