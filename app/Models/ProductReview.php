<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'description', 'rating', 'product_id', 'customer_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function customerProfile()
    {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
}
