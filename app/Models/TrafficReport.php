<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrafficReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'traffic_id',
        'masalah',
        'foto',
        'status'
    ];

    public function traffic()
    {
        return $this->belongsTo(Traffic::class);
    }
}
