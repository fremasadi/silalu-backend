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
        'status',
        'confirmed_by',
        'bukti_konfirmasi',
        'created_by', // tambahkan ini
        'deskripsi', // tambahkan ini

        
    ];

    public function traffic()
    {
        return $this->belongsTo(Traffic::class);
    }

    public function confirmedBy()
{
    return $this->belongsTo(User::class, 'confirmed_by');
}

public function confirmedUser()
{
    return $this->belongsTo(User::class, 'confirmed_by');
}
public function createdBy()
{
    return $this->belongsTo(User::class, 'created_by');
}


}
