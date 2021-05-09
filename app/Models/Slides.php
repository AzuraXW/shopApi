<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slides extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'img', 'url', 'status', 'seq'];
    protected $appends = ['img_url'];

    public function getImgUrlAttribute () {
        return oss_url($this->img);
    }
}
