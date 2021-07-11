<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
    use HasFactory;
    protected $table = 'sku';
    protected $fillable = [
        'spu_id',
        'title',
        'images',
        'stock',
        'price',
        'indexes',
        'own_spec',
        'enable'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
