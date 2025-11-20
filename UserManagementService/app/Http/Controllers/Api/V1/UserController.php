<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserGetrequest;
use App\Http\Requests\V1\UserPostrequest;
use App\Http\Resources\V1\UserResource;
use App\Repositories\V1\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Prettus\Validator\Exceptions\ValidatorException;

class UserController extends Controller
{

    function __construct(
        protected UserRepository $repository,
    )
    {

    }
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
        $userData =$request->validated();
        try{
            return new UserResource($this->repository->create($userData));
        } catch (ValidatorException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 429);
        }
        catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
