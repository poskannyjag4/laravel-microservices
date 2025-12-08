<?php

namespace App\Services\V1;

use App\Dtos\V1\UserDto;
use App\Dtos\V1\UserRequestDto;
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
    public function createUser(UserRequestDto $data): UserDto
    {
        $user = $this->userRepository->create([
            'name' => $data->name,
            'email' => $data->email,
        ]);

        UserCreated::dispatch($user);

        return UserDto::from($user);
    }

    public function getUser(int $id): UserDto
    {
        return UserDto::from($this->userRepository->find($id));
    }

    public function updateUser(UserRequestDto $data, int $id): UserDto
    {
        $user = $this->userRepository->update([
            'name' => $data->name,
            'email' => $data->email
        ], $id);

        return UserDto::from($user);
    }
}
