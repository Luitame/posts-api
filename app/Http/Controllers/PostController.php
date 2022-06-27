<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Service\PostService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class PostController extends Controller
{
    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(): AnonymousResourceCollection
    {
        $this->postService->getScalablePathContent();

        return PostResource::collection(Post::all());
    }

    public function destroy(Post $post): Response|Application|ResponseFactory
    {
        $post->delete();

        return response('', HttpResponse::HTTP_NO_CONTENT);
    }
}
