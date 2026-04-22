<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;

class PropertyController extends Controller
{
    public function index()
    {
        return Property::all();
    }

    public function show(Property $property)
    {
        return $property;
    }

    public function store(Request $request)
    {
        // make a variable to store the data
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        // take the variable that have data to the database
        Property::create([
            'name'=>$name,
            'email'=>$email,
            'password'=>$password,
        ]);
    }
}