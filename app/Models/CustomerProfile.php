<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'cus_name', 'cus_state', 'cus_city', 'cus_postcode', 'cus_country', 'cus_phone', 'cus_address',
        'ship_name', 'ship_state', 'ship_address', 'ship_city', 'ship_postcode', 'ship_country', 'ship_phone',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class, 'customer_id');
    }
}
