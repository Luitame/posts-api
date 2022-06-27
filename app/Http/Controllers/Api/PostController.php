<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Service\PostService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class PostController extends Controller
{
    const CACHE_TTL = 60 * 60 * 24;

    private PostService $postService;
    private Post $post;

    public function __construct(PostService $postService, Post $post)
    {
        $this->postService = $postService;
        $this->post = $post;
    }

    public function index(): AnonymousResourceCollection
    {
        $this->postService->getScalablePathContent();

        $cachedPosts = Cache::remember('posts', self::CACHE_TTL, fn() => $this->post->all());

        return PostResource::collection($cachedPosts);
    }

    public function destroy(Post $post): Response|Application|ResponseFactory
    {
        $post->delete();

        return response('', HttpResponse::HTTP_NO_CONTENT);
    }
}
