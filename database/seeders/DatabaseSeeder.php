<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Item;
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

        Item::create(['item_type'=>'Service']);
        Item::create(['item_type'=>'Maintenance']);

        \App\Models\Customer::factory(5)->create();
    }
}
