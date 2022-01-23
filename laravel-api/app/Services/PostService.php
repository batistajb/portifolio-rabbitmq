<?php


namespace App\Services;

use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use App\Services\Contracts\PostServiceContract;
use Exception;

/**
 * Class to Services of Post
 *
 * @package App\Services
 */
class PostService implements PostServiceContract
{

    private PostRepository $repositoryContract;

    public function __construct(PostRepository $repositoryContract)
    {
        $this->repositoryContract = $repositoryContract;
    }

    /**
     * Get all posts and remember in cache
     *
     * @param array $params
     * @return object
     * @throws Exception
     */
    public function listAll(array $params): object
    {
        if ($params)
        {
            return $this->repositoryContract->getByFilter($params);
        }
        return cache()->remember('get_all_posts' ,'1500', function () use ($params) {
            return $this->repositoryContract->all();
        });
    }

    /**
     * Get post base uuid and remember in cache
     *
     * @param string $uuid
     * @return object
     * @throws Exception
     */
    public function getByUUid(string $uuid): object
    {
        cache()->increment($uuid . '_post_count_views');
        return cache()->remember('get_post_' . $uuid,'3000000' ,function () use ($uuid){
            return  $this->repositoryContract->getByUuid($uuid);
        });
    }

    /**
     * Store post and remove list of posts in cache to force update when new request
     *
     * @param array $data
     * @return object
     * @throws Exception
     */
    public function create(array $data)
    {
        $this->clearCache($data['user_id']);
        return $this->repositoryContract->store($data);
    }

    /**
     * Store post and remove list of posts in cache to force update when new request
     *
     * @param array $data
     * @return object
     * @throws Exception
     */
    public function getCountPostsToday(string $userUuid)
    {
        return $this->repositoryContract->getCountPostsToday($userUuid);
    }

    /**
     * Update post and remove list of posts and post key in cache to force update when new request
     *
     * @param array $data
     * @param string $uuid
     * @return object
     * @throws Exception
     */
    public function edit(array $data, string $uuid): object
    {
        $this->clearCache($data['user_id'], $uuid);
        return cache()->remember('get_post_' . $uuid,'3000000' ,function () use ($data, $uuid){
            return $this->repositoryContract->updateByUuid($data, $uuid);
        });
    }

    /**
     * Delete post and remove list of posts and post key in cache to force update when new request
     *
     * @throws Exception
     */
    public function delete(string $uuid): bool
    {
        $post = $this->repositoryContract->getByUuid($uuid);
        if ($post) {
            $this->clearCache($post->user_id, $uuid);
            return $post->delete();
        }
        return false;
    }

    /**
     * Save increment like in cache to process when run job
     *
     * @param string $post_id
     * @return int|null
     */
    public function likePost(string $post_id): int|null
    {
        $post = $this->repositoryContract->getByUuid($post_id);
        if ($post)
            cache()->increment($post_id . '_post_count_likes');
        return cache()->get($post_id . '_post_count_likes');
    }

    /**
     * Save decrement like in cache to process when run job
     *
     * @param Post $post
     * @return int
     */
    public function deslikePost(Post $post): int
    {
        cache()->decrement($post->uuid . '_post_count_likes');
        return cache()->get($post->uuid . '_post_count_likes');
    }

    public function postsUserFollows(User $user): object
    {
        return cache()->remember($user->uuid .'_posts_follows','30000',function () use($user) {
            return $user->postsUserFollows;
        });
    }

    public function postsUserFollowers(User $user): object
    {
        return cache()->remember($user->uuid .'_posts_followers','30000',function () use($user) {
            return $user->postsUserFollowers;
        });
    }

    public function myPosts(User $user): object
    {
        return cache()->remember($user->uuid .'_my_posts','30000',function () use($user) {
            return $user->myPosts;
        });
    }

    private function clearCache(string $userUuid = '', string $postUuid = ''): void
    {
        cache()->forget($userUuid .'_posts_follows');
        cache()->forget($userUuid .'_posts_followers');
        cache()->forget($userUuid .'_my_posts');
        cache()->forget('get_post_' . $postUuid);
        cache()->forget('get_all_posts');
    }
}
