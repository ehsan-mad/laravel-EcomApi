<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'total', 'vat', 'payable', 'cus_details', 'ship_details', 'tran_id', 'val_id', 'delivery_status', 'payment_status', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoiceProducts()
    {
        return $this->hasMany(InvoiceProduct::class);
    }
}
