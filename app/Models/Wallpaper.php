<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LiveCategory;

class Wallpaper extends Model
{
    use HasFactory;

    public $table = 'wallpaper';

    public function category()
    {
        return $this->hasOne('App\Models\Category', "id",'category_id');
    }

    public function livecategory()
    {
        return $this->hasOne('App\Models\LiveCategory', "id",'category_id');
    }
}
