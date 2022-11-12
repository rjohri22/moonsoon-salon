<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemVideoComment extends Model
{
    use HasFactory;

    protected $table="item_video_comments";

    protected $fillable=['item_video_id','user_id','comment'];

}
