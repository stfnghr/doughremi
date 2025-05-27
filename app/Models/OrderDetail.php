<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = ['order_id',
                            'menu_id',
                            'amount',
                            'price',
                            'delivery_date',
                            'delivery_status'];

    public function orders()
    {
        return $this->belongsTo(Order::class);
    }

    public function menus()
    {
        return $this->belongsTo(Menu::class);
    }

    public function couriers()
    {
        return $this->belongsTo(Courier::class);
    }
}
