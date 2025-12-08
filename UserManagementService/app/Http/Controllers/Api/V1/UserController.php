<?php

namespace App\Http\Controllers\Api\V1;

use App\Dtos\V1\UserPostRequestDto;
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
        $userData = UserPostRequestDto::from($request->validated());
        $user = $this->userService->createUser($userData);

        return new UserResource($user);

    }

    public function show(string $id): JsonResponse|UserResource
    {
        return new UserResource($this->userService->getUser($id));
    }

    public function update(UserUpdateRequest $request, string $id): JsonResponse|UserResource
    {
        try {
            $userData = $request->validated();
            $this->repository->update($userData, $id);

            return new UserResource($this->repository->find($id));
        } catch (ValidatorException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Пользователя с id {$id} не существует',
            ], 404);
        }

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
