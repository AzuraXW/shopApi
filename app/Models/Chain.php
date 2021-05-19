<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chain extends Model
{
    use HasFactory;

    protected $table = 'chain';

    public function children () {
        return $this->hasMany(Chain::class, 'pid', 'id');
    }
}
