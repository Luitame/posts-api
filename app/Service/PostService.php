<?php

namespace App\Service;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PostService
{
    private string $baseUri;

    private User $user;
    private Post $post;

    public function __construct(User $user, Post $post)
    {
        $this->user = $user;
        $this->post = $post;

        $this->baseUri = env('SCALABLE_PATH_API_URL', '');
    }

    public function getScalablePathContent(): void
    {
        if (!$this->post->first()) {
            $this->getUsers();
            $this->getPosts();
        }
    }

    public function getUsers(): void
    {
        $users = Http::get($this->baseUri . 'test-users')
            ->json();

        foreach ($users as $user) {
            $this->user->create([
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'username' => $user['username'],
                'password' => Hash::make(Str::uuid()),
            ]);
        }
    }

    public function getPosts(): void
    {
        $posts = Http::get($this->baseUri . 'test-posts')
            ->json();

        foreach ($posts as $post) {
            $this->post->create([
                'id' => $post['id'],
                'title' => $post['title'],
                'body' => $post['body'],
                'user_id' => $post['userId'],
            ]);
        }
    }
}
