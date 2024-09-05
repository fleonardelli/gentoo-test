<?php

namespace Database\Seeders;

use App\Services\WordCombinatorService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    public function __construct(
        private readonly WordCombinatorService $wordCombinationService
    ) {
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = DB::table('posts')->pluck('id');


        $randomWords = "Cool,Strange,Funny,Laughing,Nice,Awesome,Great,Horrible,Beautiful,PHP,Vegeta,Italy,Joost";
        $wordArray = array_map('strtolower', explode(',', $randomWords));

        $combinations = $this->wordCombinationService->generateCombinations($wordArray);

        $totalComments = 8191;
        if (count($combinations) < $totalComments) {
            $this->command->error('Not enough combinations generated.');
            return;
        }

        DB::transaction(function () use ($posts, $combinations, $totalComments) {
            $batchSize = 1000;
            $comments = [];
            $insertedComments = 0;

            foreach ($combinations as $content) {
                if ($insertedComments >= $totalComments) {
                    break;
                }

                $postId = $posts->random();
                $abbreviation = $this->wordCombinationService->generateAbbreviation($content);
                $comments[] = [
                    'post_id' => $postId,
                    'content' => $content,
                    'abbreviation' => $abbreviation,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $insertedComments++;

                // Insert in batches
                if (count($comments) >= $batchSize || $insertedComments >= $totalComments) {
                    DB::table('comments')->insert($comments);
                    $comments = [];
                }
            }
        });
    }
}
