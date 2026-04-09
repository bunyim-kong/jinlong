<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $table = 'tenants';

    protected $primaryKey = 'id';

    protected $fillable = [
    'user_id',
    'sex',
    'dob',
    'address',
    'phone_number',
    'email',
];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function maintananceRquest()
    {
        return $this->hasMany(MaintenanceRequest::class, 'tenant_id', 'id');
    }
    public function leases()
    {
        return $this->hasMany(Lease::class, 'tenant_id', 'id');
    }
}
