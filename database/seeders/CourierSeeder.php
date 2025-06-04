<?php

namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    public function run(): void
    {
        Courier::create([
            'name' => 'Ari',
            'phone' => '0812-1111-2222',
        ]);

        Courier::create([
            'name' => 'Ilham',
            'phone' => '0821-3333-4444',
        ]);

        Courier::create([
            'name' => 'Ilham',
            'phone' => '0812-1111-2222',
        ]);

        Courier::create([
            'name' => 'Eka',
            'phone' => '0857-5555-6666',
        ]);

        Courier::create([
            'name' => 'Rudi',
            'phone' => '0857-1234-5678',
        ]);
    }
}
