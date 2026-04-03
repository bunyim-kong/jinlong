<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lease;

class LeaseController extends Controller
{
    public function index()
    {
        return Lease::all();
    }

    public function show(Lease $lease)
    {
        return $lease;
    }
}
