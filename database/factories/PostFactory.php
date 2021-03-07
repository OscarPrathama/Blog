<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence();
        $status = ['1', '0'];
        return [
            'post_title' => $title,
            'user_id' => User::all()->random()->id,
            'post_slug' => Str::slug($title),
            'post_type' => $this->faker->randomElement(['post', 'product', 'page']),
            'post_content' => $this->faker->paragraph(),
            'post_status' => $status[rand(0, 1)],
        ];
    }
}
