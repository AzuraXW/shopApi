<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecGroup extends Model
{
    use HasFactory;
    protected $table = 'spec_group';
    protected $fillable = ['category_id', 'name'];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
