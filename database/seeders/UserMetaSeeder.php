<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserMeta;

class UserMetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserMeta::factory()
        ->count(1)
        ->create();
    }
}
