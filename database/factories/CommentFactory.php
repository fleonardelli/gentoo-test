<?php

namespace Database\Factories;

use App\Models\Post;
use App\Services\Abbreviator\AbbreviatorInterface;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\App;

/**
 * @extends Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    private AbbreviatorInterface $abbreviator;
    public function __construct()
    {
        $this->abbreviator = App::make(AbbreviatorInterface::class);
        parent::__construct();
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $content = $this->faker->sentence;
        return [
            'post_id' => Post::factory()->create(),
            'content' => $content,
            'abbreviation' => $this->abbreviator->generateAbbreviation($content),
        ];
    }
}
