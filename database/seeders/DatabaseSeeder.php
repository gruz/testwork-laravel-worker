<?php

namespace Database\Seeders;

use App\Jobs\CheckLink;
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

        // \App\Models\User::factory(10)->create();
        \App\Models\Link::factory(10)->create()->each(function ($model) {
            // Simulate jobs addes at the same time for the same Link record
            for ($i = 0; $i < rand(2, 6); $i++) {
                CheckLink::dispatch($model);
            }
        });
    }
}
