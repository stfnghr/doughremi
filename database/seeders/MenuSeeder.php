<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Sweet Pick
        Menu::create([
            'name' => 'Chocolate Chip',
            'price' => 10000,
            'categories' => 'Sweet Pick',
            'image' => 'chocochip_cookie.png'
        ]);

        Menu::create([
            'name' => 'Double Chocolate',
            'price' => 10000,
            'categories' => 'Sweet Pick',
            'image' => 'chocolate_cookie.png'
        ]);
        
        Menu::create([
            'name' => 'Vanilla Bean',
            'price' => 10000,
            'categories' => 'Sweet Pick',
            'image' => 'vanilla_cookie.png'
        ]);
        
        Menu::create([
            'name' => 'Strawberry Cream',
            'price' => 10000,
            'categories' => 'Sweet Pick',
            'image' => 'strawberry_cookie.png'
        ]);
        
        Menu::create([
            'name' => 'Matcha Green Tea',
            'price' => 10000,
            'categories' => 'Sweet Pick',
            'image' => 'matcha_cookie.png'
        ]);
        
        Menu::create([
            'name' => 'Salted Caramel',
            'price' => 10000,
            'categories' => 'Sweet Pick',
            'image' => 'saltedcaramel_cookie.png'
        ]);
        
        Menu::create([
            'name' => 'Lotus Biscoff',
            'price' => 10000,
            'categories' => 'Sweet Pick',
            'image' => 'biscoff_cookie.png'
        ]);
        
        // Joy Box
        Menu::create([
            'name' => 'Christmas Box',
            'price' => 250000,
            'categories' => 'Joy Box',
            'image' => 'christmas.png'
        ]);

        Menu::create([
            'name' => 'Eid Box',
            'price' => 250000,
            'categories' => 'Joy Box',
            'image' => 'eid.png'
        ]);
        
        Menu::create([
            'name' => 'New Year Box',
            'price' => 250000,
            'categories' => 'Joy Box',
            'image' => 'new_year.png'
        ]);
        
        Menu::create([
            'name' => 'Lunar Box',
            'price' => 250000,
            'categories' => 'Joy Box',
            'image' => 'lunar.png'
        ]);
        
        Menu::create([
            'name' => 'Valentine Box',
            'price' => 250000,
            'categories' => 'Joy Box',
            'image' => 'valentine.png'
        ]);
        
        Menu::create([
            'name' => 'Easter Box',
            'price' => 250000,
            'categories' => 'Joy Box',
            'image' => 'easter.png'
        ]);
        
        Menu::create([
            'name' => 'Halloween Box',
            'price' => 250000,
            'categories' => 'Joy Box',
            'image' => 'halloween.png'
        ]);
    }
}
