<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarketplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $markets = [
            ['name' => 'Mumbai', 'country_id' => 1], // Assuming Mumbai is in India (country_id 1)
            ['name' => 'Delhi', 'country_id' => 1],
            ['name' => 'Dubai', 'country_id' => 2], // Assuming Dubai is in UAE (country_id 2)
            ['name' => 'Abu Dhabi', 'country_id' => 2],
            ['name' => 'Karachi', 'country_id' => 3], // Assuming Karachi is in Pakistan (country_id 3)
            ['name' => 'Lahore', 'country_id' => 3],
            // Add more cities as needed
        ];

        foreach ($markets as $market) {
            DB::table("marketplaces")->insert($market);
        }

        // Output a message to the console
        $this->command->info('Markets seeded successfully.');
    }
}
