<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    protected $table = 'leases';

    protected $primaryKey = 'id';

    protected $fillable = [
    'tenants_id',
    'unit_id',
    'start_date',
    'end_date',
    'monthly_rent',
    'status',
];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'lease_id', 'id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenants_id', 'id');
    }
}
