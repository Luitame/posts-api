<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class PostController extends Controller
{
    const BASE_URI = 'https://www.scalablepath.com/api/test/';

    public function index()
    {
        if (count(User::get()) == 0) {
            $users = Http::get(self::BASE_URI . 'test-users')->json();

            foreach ($users as $user) {
                User::create([
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'username' => $user['username'],
                    'password' => Hash::make(Str::uuid()),
                ]);
            }

            $posts = Http::get(self::BASE_URI . 'test-posts')->json();

            foreach ($posts as $post) {
                Post::create([
                    'id' => $post['id'],
                    'title' => $post['title'],
                    'body' => $post['body'],
                    'user_id' => $post['userId'],
                ]);
            }
        }

        return PostResource::collection(Post::all());
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response('', HttpResponse::HTTP_NO_CONTENT);
    }
}
