<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = [
            'mobiles_tablets' => 'Mobiles & Tablets',
            'sale_property' => 'Sale Property',
            'classifieds' => 'Classifieds',
            'garden_furniture' => 'Garden Furniture',
            'Motors' => 'Motors',
        ];

        foreach ($titles as $slug => $title) {
            // Randomly select a market and country
            $market = DB::table('marketplaces')->inRandomOrder()->first();
            $country = DB::table('countries')->find($market->country_id);

            DB::table("categories")->insert([
                'title' => $title,
                'slug' => Str::slug($slug),
                'summary' => "Summary for $slug category",
                'photo' => "path/to/photo_$slug.jpg", // Consider using storage for the actual project
                'status' => 'active',
                'is_parent' => true,
                'parent_id' => null,
                'added_by' => 1, // Replace with the actual user ID who adds this category
                'market_id' => $market->id,
                'country_id' => $country->id,
            ]);
        }

        // Output a message to the console
        $this->command->info('Categories seeded successfully.');
    }
}
