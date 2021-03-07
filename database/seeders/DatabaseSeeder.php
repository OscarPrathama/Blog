<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // User::factory()->count(2)->hasPosts(2)->create();

        // User::factory(10)->count(50)->hasPosts(1)->create();

        // User::factory()
        //     ->has(Post::factory()->count(3))
        //     ->create();

        $this->call([
            UserSeeder::class,
            PostSeeder::class,
            PostMetaSeeder::class,
            UserMetaSeeder::class,
        ]);
    }
}
