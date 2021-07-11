<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecParam extends Model
{
    use HasFactory;
    protected $table = 'spec_param';
    protected $fillable = [
        'category_id',
        'group_id',
        'name',
        'numeric',
        'unit',
        'generic',
        'searching',
        'segments'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
