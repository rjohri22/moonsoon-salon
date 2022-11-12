<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UploaderTrait;


class Item extends Model
{
    use HasFactory, SoftDeletes, UploaderTrait;
    protected $guarded = [];

    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }

    public function itemSubCategory()
    {
        return $this->belongsTo(ItemSubCategory::class, 'item_sub_category_id');
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function itemImages()
    {
        return $this->hasMany(Media::class, 'table_id')->where('table_type', 'App\Models\Item');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function media()
    {
        return $this->morphOne('App\Models\Media', 'table');
    }

    public function scopeGetActive($query)
    {
        return $query->where('status', 'active');
    }

    public function medias()
    {
        return $this->morphMany('App\Models\Media', 'table');
    }

    public function discountType(){
        return $this->belongsTo(DiscountType::class, 'discount_type');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            foreach ($model->medias as $media) {
                $storageName = $media->file_name;
                self::deleteFile('item/' . $storageName);
                $media->delete();
            }
        });
    }


    
    public function videos()
    {
        return $this->hasMany(ItemVideo::class, 'item_id');
    }
    
}
