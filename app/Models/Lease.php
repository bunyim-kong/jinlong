<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    protected $table = 'leases';

    protected $primaryKey = 'id';

    protected $fillable = [
        
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'lease_id', 'id');
    }
}
