<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function media()
    {
        return $this->morphOne('App\Models\Media', 'table');
    }

    public function discountType()
    {
        return $this->belongsTo(DiscountType::class, 'amount_type');
    }

    // Get Slider Offer
    public function scopeGetSlider($query)
    {
        return $query->where('is_slider', 1);
    }
    
    public function scopeGetActive($query)
    {
        return $query->where('status', 'active');
    }
}
