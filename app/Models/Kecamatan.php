<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'kodepos'];

    public function kelurahans()
{
    return $this->hasMany(Kelurahan::class);
}

}
