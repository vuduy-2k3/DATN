<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Ticket extends Model
{
    protected $fillable = [
        'area_id', 'licensePlate', 'numberLocation', 'is_exported'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
