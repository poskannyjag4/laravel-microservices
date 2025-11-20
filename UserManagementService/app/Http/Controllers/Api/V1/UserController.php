<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UserGetrequest;
use App\Repositories\V1\UserRepository;
use Illuminate\Http\Request;


class UserController extends Controller
{

    function __construct(
        UserRepository $repository,
    )
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index(UserGetrequest $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
