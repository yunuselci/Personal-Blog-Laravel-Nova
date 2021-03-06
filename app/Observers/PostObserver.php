<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PostObserver
{
    public function saving(Post $post): void
    {
        if (blank($post->slug)) {
            foreach ($post->translations as $translation) {
                $post->translateOrNew($translation->locale)->slug = Str::slug($translation->title);
            }
        }
        foreach ($post->translations as $translation) {
            if (
                $post->translateOrNew($translation->locale)->isDirty('title')
                && $post->translateOrNew($translation->locale)->isClean('slug')
            ) {
                $post->translateOrNew($translation->locale)->slug = Str::slug($translation->title);
            }
        }
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        foreach ($post->translations as $translation) {
            $secret = User::pluck('dev_to_secret')[0];
            // Disables the post on dev.to

            Http::withHeaders([
                'api-key' => $secret,
                'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.96 Safari/537.36',
            ])->put('https://dev.to/api/articles/'.$post->translateOrNew($translation->locale)->dev_to_article_id, [
                'article' => [
                    'title' => $post->translateOrNew($translation->locale)->title,
                    'published' => false,
                    'body_markdown' => $post->translateOrNew($translation->locale)->description,
                ],
            ]);
        }
    }
}
