<?php

namespace App\Http\Controllers\Api\V1;

use App\Dtos\V1\UserRequestDto;
use App\Dtos\V1\UserUpdateRequestDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserGetrequest;
use App\Http\Requests\V1\UserPostRequest;
use App\Http\Requests\V1\UserUpdateRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\V1\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Prettus\Validator\Exceptions\ValidatorException;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function index(UserGetrequest $request): AnonymousResourceCollection
    {
        return UserResource::collection($this->userService->getPaginatedUsers());
    }

    /**
     * @throws ValidatorException
     */
    public function store(UserPostRequest $request): UserResource
    {
        $userData = UserRequestDto::from($request->validated());
        $user = $this->userService->createUser($userData);

        return new UserResource($user);

    }

    public function show(string $id): JsonResponse|UserResource
    {
        return new UserResource($this->userService->getUser($id));
    }

    public function update(UserUpdateRequest $request, int $id): JsonResponse|UserResource
    {
            $userData = UserRequestDto::from($request->validated());
            return new UserResource($this->userService->updateUser($userData, $id));
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            return response()->json($this->repository->delete($id), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Пользователя с id {$id} не существует',
            ], 404);
        }
    }
}
