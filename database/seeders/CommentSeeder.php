<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $posts = Post::published()->get();

        $sampleComments = [
            ['name' => 'Alice Johnson', 'body' => 'Great article! Very informative and well-structured.'],
            ['name' => 'Bob Smith', 'body' => 'I learned a lot from this. Thanks for sharing!'],
            ['name' => 'Carol White', 'body' => 'Could you provide more examples? I would love to see more use cases.'],
            ['name' => 'David Brown', 'body' => 'This is exactly what I was looking for. Bookmarked!'],
            ['name' => 'Eve Davis', 'body' => 'Excellent explanation. The code examples are very clear.'],
        ];

        foreach ($posts as $index => $post) {
            $commentCount = $post->comments_count;
            for ($i = 0; $i < $commentCount && $i < count($sampleComments); $i++) {
                Comment::create([
                    'post_id' => $post->id,
                    'name' => $sampleComments[$i % count($sampleComments)]['name'],
                    'body' => $sampleComments[$i % count($sampleComments)]['body'],
                    'ip_address' => "192.168.1.{$i}",
                ]);
            }
        }
    }
}
