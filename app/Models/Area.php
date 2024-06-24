<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'floor_id', 'total', 'priority'];

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function vehicleInformation()
    {
        return $this->hasMany(VehicleInformation::class);
    }
}
