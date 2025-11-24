<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\V1\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    function __construct(
        protected CategoryRepository $repository,
    )
    {

    }


}
