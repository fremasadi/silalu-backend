<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traffic extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'traffic';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'longitude',
        'latitude',
        'kecamatan_id',

    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'longitude' => 'decimal:8',
        'latitude' => 'decimal:8',
    ];

    public function reports()
{
    return $this->hasMany(TrafficReport::class);
}
public function kecamatan()
{
    return $this->belongsTo(Kecamatan::class);
}


}