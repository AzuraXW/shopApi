<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'menu';

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function children () {
        return $this->hasMany(Menu::class, 'pid', 'id');
    }

    public function roles () {
        return $this->belongsToMany(Role::class, MenuRole::class, 'mid', 'rid');
    }
}
