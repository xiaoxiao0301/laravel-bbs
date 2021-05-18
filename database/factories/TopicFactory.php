<?php

namespace Database\Factories;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

class TopicFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Topic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $sentence = $this->faker->sentence();
        // 随机取一个月以内的时间
        $updated_at = $this->faker->dateTimeThisMonth();
        // 传参为生成最大时间不超过，因为创建时间需永远比更改时间要早
        $created_at = $this->faker->dateTimeThisMonth($updated_at);

        return [
            'title' => $sentence,
            'body' => $this->faker->text(),
            'excerpt' => $sentence,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ];
    }
}
