<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;

class PropertyController extends Controller
{
    public function index (){
        $nonCollection = Property::paginate(5);
        return view('pages.properties', compact('nonCollection'));
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
        
        return redirect()->back()->with('success');
    }
}