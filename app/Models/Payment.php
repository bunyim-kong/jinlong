<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $primaryKey = 'id';

    protected $fillable = [
    'lease_id',
    'amount',
    'payment_date',
    'payment_method',
    'status',
];

    public function lease()
    {
        return $this->belongsTo(Lease::class, 'lease_id', 'id');
    }
}
