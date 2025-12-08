<?php

namespace App\Services\V1;

use App\Dtos\V1\UserPostRequestDto;
use App\Events\UserCreated;
use App\Models\User;
use App\Repositories\V1\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Prettus\Validator\Exceptions\ValidatorException;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    /**
     * @return LengthAwarePaginator<int, User>
     */
    public function getPaginatedUsers(): LengthAwarePaginator
    {

        return $this->userRepository->paginate(10);
    }

    /**
     * @throws ValidatorException
     */
    public function createUser(UserPostRequestDto $data): User
    {
        $user = $this->userRepository->create([
            'name' => $data->name,
            'email' => $data->email,
        ]);

        UserCreated::dispatch($user);

        return $user;
    }
}
