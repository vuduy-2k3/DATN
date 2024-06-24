<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleInformation extends Model
{
    use HasFactory;

    protected $fillable = ['fullName', 'phone', 'IDCard', 'licensePlate', 'area_id', 'numberLocation', 'status'];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
