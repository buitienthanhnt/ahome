<?php

namespace Database\Factories;

use App\Models\Paper;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaperFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var \App\Models\Paper
     */
    protected $model = Paper::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "title" => "default from factory",
            "image_path" => ""
        ];
    }
}
