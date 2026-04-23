<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    protected $table = 'maintenance_requests';

    protected $primaryKey = 'id'; 

    protected $fillable = [
    'tenant_id',
    'unit_id',
    'title',
    'description',
    'priority',
    'request_date',
    'completion_date',
    'staus',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
}
