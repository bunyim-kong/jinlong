<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;

class PropertyController extends Controller
{
    // Return all properties
    public function index()
    {
        return Property::all();
    }

    // Return a single property (Route Model Binding)
    public function show(Property $property)
    {
        return $property;
    }
}