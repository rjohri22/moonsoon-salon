<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getSubCategory()
    {
        return $this->belongsTo(ItemSubCategory::class, 'item_sub_category_id');
    }
    public function getItems()
    {
        return $this->hasMany(Item::class, 'product_category_id');
    }
    public function media()
    {
        return $this->morphOne('App\Models\Media', 'table');
    }

    public function scopeGetActive($query)
    {
        return $query->where('status', 'active');
    }
   

    // Get Slider Offer
    public function scopeGetTrendingCategory($query)
    {
        // return $query->where('is_trending',1);
        return $query;
    }
}
