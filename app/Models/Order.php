<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id',
                            'order_date', 
                            'total_price', 
                            'payment_status',
                            'payment_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }
}