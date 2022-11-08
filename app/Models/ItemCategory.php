<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getSubCategory(){
        return $this->hasMany(ItemSubCategory::class, 'item_category_id')->whereStatus('active')->select('id', 'name', 'category_type');
    }
    public function media()
    {
        return $this->morphOne('App\Models\Media', 'table');
    }
    
    public function getItems()
    {
        return $this->hasMany(Item::class, 'item_category_id');
    }
}
