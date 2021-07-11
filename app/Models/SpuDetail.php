<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpuDetail extends Model
{
    use HasFactory;
    protected $table = 'spu_detail';
    protected $fillable = [
        'spu_id',
        'description',
        'special_spec',
        'generic_spec',
        'packing_list',
        'after_sevice'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
