<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemVideo extends Model
{
    use HasFactory;

    protected $table="item_videos";

    protected $fillable=['item_id','video_category','video_file_name'];
    
}
