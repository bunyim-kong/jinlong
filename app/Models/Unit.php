<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';

    protected $primaryKey = 'id';

    protected $fillable = [
    'property_id',
    'unit_number',
    'facility',
    'rent_price',
    'status',
];
  
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    public function leases()
    {
        return $this->hasMany(Lease::class, 'unit_id', 'id');
    }
    public function maintananceRquest()
    {
        return $this->belongsTo(MaintananceRquest::class, 'unit_id', 'id');
    }
}
