<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin  = User::where('email', 'admin@blog.com')->first();
        $editor = User::where('email', 'editor@blog.com')->first();

        $posts = [
            ['Laravel Service Layer Architecture',     $admin,  'published', 45,  12],
            ['Optimizing SQL Queries with Indexes',    $admin,  'published', 120, 8],
            ['Understanding N+1 Problem',              $editor, 'published', 67,  15],
            ['Redis Caching in Laravel',               $admin,  'published', 89,  5],
            ['Laravel Policies and Gates',             $editor, 'published', 34,  9],
            ['Database Migrations Best Practices',     $admin,  'published', 201, 3],
            ['API Resources in Laravel',               $editor, 'published', 55,  11],
            ['Bootstrap 5 Tips and Tricks',            $admin,  'published', 78,  7],
            ['Event-Driven Architecture in Laravel',   $editor, 'published', 43,  14],
            ['Writing Clean PHP Code',                 $admin,  'published', 91,  6],
            ['Laravel Queues for Background Jobs',     $editor, 'published', 62,  4],
            ['Docker for Laravel Development',         $admin,  'published', 38,  2],
            ['PHP 8.2 New Features',                   $editor, 'draft',     0,   0],
            ['Upcoming Laravel Features',              $admin,  'draft',     0,   0],
            ['Scheduled Post Example',                 $editor, 'scheduled', 0,   0],
        ];

        // Ensure the storage directory exists
        $storagePath = storage_path('app/public/posts');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        foreach ($posts as [$title, $user, $status, $views, $comments]) {
            $imageName = null;
            if ($status === 'published') {
                // Download a placeholder image and save it locally
                $imageName = 'posts/' . Str::random(10) . '.jpg';
                try {
                    $imageData = file_get_contents('https://picsum.photos/seed/' . Str::slug($title) . '/800/600');
                    if ($imageData) {
                        file_put_contents(storage_path('app/public/' . $imageName), $imageData);
                    } else {
                        $imageName = null; 
                    }
                } catch (\Exception $e) {
                    $imageName = null;
                }
            }

            // Create post WITHOUT counters (they are not in $fillable).
            $post = Post::create([
                'user_id'      => $user->id,
                'title'        => $title,
                'slug'         => Str::slug($title),
                'body'         => "This is the full body content for the post titled \"{$title}\". "
                    . "It covers all the important aspects of the topic in detail with practical examples and code snippets. "
                    . "Laravel's Service Layer and Repository patterns help keep the code clean and maintainable.",
                'image'        => $imageName,
                'status'       => $status,
                'published_at' => $status === 'published' ? now()->subDays(rand(1, 60)) : null,
                'publish_at'   => $status === 'scheduled' ? now()->addDay() : null,
            ]);

            // Set counter columns directly — bypasses $fillable intentionally for seeding only.
            DB::table('posts')
                ->where('id', $post->id)
                ->update([
                    'views_count'    => $views,
                    'comments_count' => $comments,
                ]);
        }
    }
}
