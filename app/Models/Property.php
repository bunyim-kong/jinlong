<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $table = 'properties';

    protected $primaryKey = 'id';

    protected $fillable = [
    'name',
    'address',
    'type',
    'total_units',
];
    public function units()
    {
        return $this->hasMany(Unit::class, 'property_id', 'id');
    }
}
