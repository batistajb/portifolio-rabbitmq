<?php


namespace App\Services\Contracts;

use App\Models\Post;
use App\Models\User;

/**
 * Interface PostServiceContract
 *
 * @package App\Services\Contracts
 */
interface PostServiceContract
{
    /**
     * Get one post
     *
     * @param array $params
     * @return object
     */
    public function listAll(array $params): object;

    /**
     * Get one post
     *
     * @param Post $post
     * @return int
     */
    public function deslikePost(Post $post): int;

    /**
     * Get one post
     *
     * @param string $post_id
     * @return int|null
     */
    public function likePost(string $post_id): int|null;

    /**
     * Get one post
     *
     * @param string $uuid
     * @return object
     */
    public function getByUUid(string $uuid): object;

    /**
     * Store new post
     *
     * @param array $data
     * @return object
     */
    public function getCountPostsToday(string $userUuid);

    /**
     * Store new post
     *
     * @param array $data
     * @return object
     */
    public function create(array $data);

    /**
     * Edit one post
     *
     * @param array $data
     * @param string $uuid
     * @return object
     */
    public function edit(array $data, string $uuid): object;

    /**
     * Delete one post
     *
     * @param string $uuid
     * @return bool
     */
    public function delete(string $uuid): bool;

    public function postsUserFollows(User $user): object;

    public function postsUserFollowers(User $user): object;

    public function myPosts(User $user): object;
}
