<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name',
                            'price',
                            'categories',
                            'image'];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
