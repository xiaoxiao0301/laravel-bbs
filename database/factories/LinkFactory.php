<?php

namespace Database\Factories;

use App\Models\Link;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Link::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'link' => $this->faker->url,
            'created_at' => $this->faker->date("Y-m-d H:i:s", 'now'),
            'updated_at' => $this->faker->date("Y-m-d H:i:s", 'now'),
        ];
    }
}
