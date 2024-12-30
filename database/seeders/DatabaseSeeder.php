<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Business;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'firstname' => 'admin',
            'middlename' => '',
            'lastname' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '123',
        ]);

        User::factory()->create([
            'firstname' => 'user',
            'middlename' => '',
            'lastname' => 'user',
            'role' => 'user',
            'email' => 'user@gmail.com',
            'password' => '123',
        ]);


        $categories = [
            ['name' => 'Printing', 'description' => 'Services related to printing, copying, and other graphic design services.'],
            ['name' => 'Eatery', 'description' => 'Restaurants, cafes, and other food-related businesses.'],
            ['name' => 'Supermarket', 'description' => 'Stores selling a wide variety of food and household goods.'],
            ['name' => 'Bakery', 'description' => 'Shops that specialize in baking and selling bread, cakes, pastries, and other baked goods.'],
            ['name' => 'Pharmacy', 'description' => 'Stores selling prescription medications and health products.'],
            ['name' => 'Bookstore', 'description' => 'Retail businesses selling books and related products.'],
            ['name' => 'Restaurant', 'description' => 'Establishments that prepare and serve food and beverages to customers.'],
        ];

        // Insert categories with descriptions into the database
        foreach ($categories as $category) {
            Category::create($category);
        }

        
        Business::create([
            'name' => "McDonald's San Franz",
            'description' => 'American fast food restaurant chain',
            'latitude' => 8.504056,
            'longitude' => 125.977010,
            'address' => 'Brgy 5, San Francisco, Agusan del Sur',
            'email' => 'mcdo@gmail.com',
            'owner' => 'Me Myself and I',
            'contact' => '09092384523',
            'category_id' => 7
        ]);

    }
}
