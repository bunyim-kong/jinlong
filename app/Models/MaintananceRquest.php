<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintananceRquest extends Model
{
    protected $table = 'maintananceRequests';

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
}
