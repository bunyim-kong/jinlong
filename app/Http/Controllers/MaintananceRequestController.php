<?php

namespace App\Http\Controllers;

use App\Models\MaintananceRquest; 
use Illuminate\Http\Request;

class MaintananceRequestController extends Controller
{
    public function index()
    {
        return MaintananceRquest::all(); 
    }   

      
    public function show (MaintananceRquest $maintananceRequest)
    {
        return $maintananceRequest; 
    }
}