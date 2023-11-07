<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
         UserTableSeeder::class,
         GatewayTableSeeder::class,
         OptiontableSeeder::class,
         CategorySeeder::class,
         MenuSeeder::class,
         PlanSeeder::class,
         PostSeeder::class,
         PostmetaSeeder::class,
         PostCategorySeeder::class,
        ]); 
    }
}
