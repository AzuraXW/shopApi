<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spu extends Model
{
    use HasFactory;
    protected $table = 'spu';
    protected $fillable = [
        'name',
        'sub_title',
        'cid',
        'brand_id',
        'saleable'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function detail () {
        return $this->hasOne(SpuDetail::class, 'spu_id', 'id');
    }


}
