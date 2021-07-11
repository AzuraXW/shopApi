<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CateogryBrand;

class Brand extends Model
{
    use HasFactory;
    protected $table = 'brand';
    protected $fillable = [
        'name',
        'image',
        'letter'
    ];

    public function CategoryBrand () {
        return $this->hasMany(CateogryBrand::class, 'brand_id', 'id');
    }
}
