<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $countries = [
            ['code' => 'PK', 'name' => 'Pakistan'],
            ['code' => 'IN', 'name' => 'India'],
            ['code' => 'AE', 'name' => 'United Arab Emirates'],
            // Add more countries as needed
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}
