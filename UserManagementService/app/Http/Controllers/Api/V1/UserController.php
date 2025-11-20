<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserGetrequest;
use App\Http\Requests\V1\UserPostrequest;
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

    /**
     * Display a listing of the resource.
     */
    public function index(UserGetrequest $request): AnonymousResourceCollection
    {
        return UserResource::collection($this->repository->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserPostrequest $request): JsonResponse|UserResource
    {
        $userData = $request->validated();
        try {
            return new UserResource($this->repository->create($userData));
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

    /**
     * Display the specified resource.
     */
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

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id): JsonResponse|UserResource
    {

        $userData = $request->validated();
        try {
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
