<?php

namespace Database\Factories;

use App\Models\UserMeta;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserMetaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserMeta::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $images = array('hashirama.jpg', 'tobirama.jpg', 'madara.jpg', 'zetsu.jpg', 'uchiha-itachi.jpg');
        return [
            'user_id' => User::all()->random()->id,
            'key' => 'image',
            'value' => '2021/02/'.$this->faker->randomElement($images),
        ];
    }
}
