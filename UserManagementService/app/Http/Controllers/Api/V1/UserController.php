<?php

namespace App\Http\Controllers\Api\V1;

use App\Dtos\V1\UserRequestDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserGetrequest;
use App\Http\Requests\V1\UserPostRequest;
use App\Http\Requests\V1\UserUpdateRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\V1\UserService;
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

    public function show(int $id): JsonResponse|UserResource
    {
        return new UserResource($this->userService->getUser($id));
    }

    public function update(UserUpdateRequest $request, int $id): JsonResponse|UserResource
    {
        $userData = UserRequestDto::from($request->validated());

        return new UserResource($this->userService->updateUser($userData, $id));
    }

    public function destroy(int $id): JsonResponse
    {
        return response()->json($this->userService->deleteUser($id), 200);
    }
}
