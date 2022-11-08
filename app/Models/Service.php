<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UploaderTrait;

class Service extends Model
{
    use HasFactory;
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
    
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
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
}
