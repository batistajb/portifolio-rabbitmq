<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Exception;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{

    private PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        $params = request()->all();
        return response()->json($this->postService->listAll($params));
    }

    public function getPost(string $uuid): JsonResponse
    {
        return response()->json($this->postService->getByUUid($uuid));
    }

    public function store(PostStoreRequest $data): JsonResponse
    {
        try {
            $count = $this->postService->getCountPostsToday($data['user_id']);
            if ($count <= 5){
                return response()->json([ "data" => $this->postService->create($data->all())]);
            }
            return response()->json([ "error" => "User post more 5 posts today" ],500);
        } catch (Exception $exception) {
            return response()->json([ "error" => $exception->getMessage() ],500);
        }
    }

    public function update(PostUpdateRequest $data, Post $post): JsonResponse
    {
        try {
            return response()->json([ "data" => $this->postService->edit($data->all(), $post->uuid) ]);
        } catch (Exception $exception) {
            return response()->json([ "error" => $exception->getMessage() ],500);
        }
    }

    public function delete(Post $post): JsonResponse
    {
        try {
            return response()->json([ "data" => $this->postService->delete($post->uuid)]);
        } catch (Exception $exception) {
            return response()->json([ "error" => $exception->getMessage() ],500);
        }
    }

    public function likePost(string $post_id): JsonResponse
    {
        return response()->json($this->postService->likePost($post_id));
    }

    public function deslikePost(Post $post): JsonResponse
    {
        return response()->json($this->postService->deslikePost($post));
    }

    public function follows(User $user)
    {
          try {
            return response()->json([ "data" => $this->postService->postsUserFollows($user)]);
        } catch (Exception $exception) {
            return response()->json([ "error" => $exception->getMessage() ],500);
        }

    }

    public function followers(User $user): JsonResponse
    {
          try {
            return response()->json([ "data" => $this->postService->postsUserFollowers($user)]);
        } catch (Exception $exception) {
            return response()->json([ "error" => $exception->getMessage() ],500);
        }

    }

    public function myPosts(User $user): JsonResponse
    {
          try {
            return response()->json([ "data" => $this->postService->myPosts($user)]);
        } catch (Exception $exception) {
            return response()->json([ "error" => $exception->getMessage() ],500);
        }

    }
}
