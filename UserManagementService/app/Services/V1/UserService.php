<?php

namespace App\Services\V1;

use App\Repositories\V1\UserRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{

    function __construct(
        private UserRepository $userRepository
    )
    {

    }

    /**
     * @return LengthAwarePaginator
     */
    public function getPaginatedUsers(): LengthAwarePaginator
    {
        return $this->userRepository->paginate(10);
    }
}
