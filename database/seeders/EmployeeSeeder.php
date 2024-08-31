<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employees')->insert([
            [
                'first_name' => 'Nathalia',
                'last_name' => 'Benitez',
                'email' => 'natalia@opcion.com',
                'time_zone_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Bianca',
                'last_name' => 'Salinas',
                'email' => 'bianca@opcion.com',
                'time_zone_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Diosnel',
                'last_name' => 'Velazquez',
                'email' => 'diosnel@opcion.com',
                'time_zone_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
