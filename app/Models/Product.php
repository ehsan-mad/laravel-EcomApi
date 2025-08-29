<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'short_des', 'price', 'discount', 'discount_price', 'image', 'star', 'stock', 'remark', 'category_id', 'brand_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function productDetail()
    {
        return $this->hasOne(ProductDetail::class);
    }

    public function productSliders()
    {
        return $this->hasMany(ProductSlider::class);
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function productWishes()
    {
        return $this->hasMany(ProductWish::class);
    }

    public function productCarts()
    {
        return $this->hasMany(ProductCart::class);
    }

    public function invoiceProducts()
    {
        return $this->hasMany(InvoiceProduct::class);
    }
}
