<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    protected $fillable = ['name',
                            'phone'];

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
