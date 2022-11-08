<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSubCategory extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function parentCategory()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }

    public function getProductCategory()
    {
        return $this->hasMany(ProductCategory::class, 'item_sub_category_id')->whereStatus('active')->select('id', 'name');
    }
    
    public function media()
    {
        return $this->morphOne('App\Models\Media', 'table');
    }
    
    public function getItems()
    {
        return $this->hasMany(Item::class, 'item_sub_category_id');
    }
}
