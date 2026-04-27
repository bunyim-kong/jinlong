<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Lease;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with('user', 'leases')->paginate(10);
        $totalTenants = Tenant::count();
        $activeLeases = Lease::where('status', 'active')->count();
        $maleCount = Tenant::where('sex', 'male')->count();
        $femaleCount = Tenant::where('sex', 'female')->count();
        
        return view('pages.tenants', compact('tenants', 'totalTenants', 'activeLeases', 'maleCount', 'femaleCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'sex' => 'required|in:male,female',
            'dob' => 'required|date',
            'phone_number' => 'required|string',
            'address' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'tenant',
        ]);

        Tenant::create([
            'user_id' => $user->id,
            'sex' => $request->sex,
            'dob' => $request->dob,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'email' => $request->email,
        ]);

        return redirect()->route('tenants.index')->with('success', 'Tenant added successfully');
    }

    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);
        if ($tenant->user) {
            $tenant->user->delete();
        }
        $tenant->delete();
        return response()->json(['success' => true]);
    }
}