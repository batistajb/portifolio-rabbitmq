<?php


namespace App\Services;

use App\Models\User;
use App\Repositories\FollowRepository;
use App\Repositories\UserRepository;
use App\Services\Contracts\FollowServiceContract;
use Exception;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class to Services of Follow
 *
 * @package App\Services
 */
class FollowService implements FollowServiceContract
{

    private FollowRepository $repositoryContract;
    private UserRepository $userRepository;

    public function __construct (
        FollowRepository $repositoryContract,
        UserRepository $userRepository,
    ){
        $this->repositoryContract = $repositoryContract;
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function follow(array $data): array
    {
        $followRecover = $this->repositoryContract->followRecover($data);
        if (isset($followRecover)) { //verify if already follow
            return [
                "message" => __('message.already_follow'),
                "code"    => 400
            ];
        }
        if ($data['user_uuid'] == $data['user_uuid_follow']) { //verify if follow yourself
            return [
                "message" => __('message.selfie_not_follow'),
                "code" => 400
            ];
        }
        $this->repositoryContract->store($data);
        cache()->forget('follows_'.$data['user_uuid']); //force update list of follows
        cache()->forget('followers_'.$data['user_uuid']); //force update list of followers

        return [
            "message" => __('message.follow'),
            "code" => 200
        ];
    }

    /**
     * @throws Exception
     */
    public function unfollow(array $data): array
    {
        $followRecover = $this->repositoryContract->followRecover($data);
        if (isset($followRecover)) { //verify if follow
            $followRecover->delete();
            cache()->forget('follows_'.$data['user_uuid']); //force update list of follows
            cache()->forget('followers_'.$data['user_uuid']); //force update list of followers
            return [
                "message" => __('message.unfollow'),
                "code"    => 400
            ];
        }

        return [
            "message" => __('message.model_not_found'),
            "code" => 200
        ];
    }

    public function follows(string $userUuid): Collection|array
    {
        return cache()->remember('follows_'.$userUuid,15000, function () use ($userUuid) {
            return $this->userRepository->follows($userUuid);
        });
    }

    public function followers(string $userUuid): Collection|array
    {
        return cache()->remember('followers_'.$userUuid,15000, function () use ($userUuid) {
            return $this->userRepository->followers($userUuid);
        });
    }
}
