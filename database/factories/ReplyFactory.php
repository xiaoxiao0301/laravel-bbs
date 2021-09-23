<?php

namespace Database\Factories;

use App\Models\Reply;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReplyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reply::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $time = $this->faker->dateTimeThisMonth();

        return [
            'content' => $this->faker->sentence(10),
            'created_at' => $time,
            'updated_at' => $time,
        ];
    }
}
