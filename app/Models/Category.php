<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'pid', 'level', 'group'];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function children () {
        return $this->hasMany(Category::class, 'pid', 'id');
    }
}
