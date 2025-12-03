<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserGetrequest;
use App\Http\Requests\V1\UserPostRequest;
use App\Http\Requests\V1\UserUpdateRequest;
use App\Http\Resources\V1\UserResource;
use App\Repositories\V1\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Prettus\Validator\Exceptions\ValidatorException;

class UserController extends Controller
{
    public function __construct(
        protected UserRepository $repository,
    ) {}

    public function index(UserGetrequest $request): AnonymousResourceCollection
    {
        return UserResource::collection($this->repository->paginate(10));
    }

    public function store(UserPostRequest $request): JsonResponse|UserResource
    {
        try {
            $userData = $request->validated();
            $user = $this->repository->create($userData);
            UserCreated::dispatch($user);

            return new UserResource($user);
        } catch (ValidatorException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id): JsonResponse|UserResource
    {
        try {
            return new UserResource($this->repository->find($id));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Пользователя с id {$id} не существует',
            ], 404);
        }

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
