<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'menu_id',
        'courier_id',
        'amount',
        'price',
        'delivery_date',
        'delivery_status',
        'custom_name',
        'shape',
        'color',
        'topping'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menus()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }
}