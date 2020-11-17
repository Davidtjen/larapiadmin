<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Tjen Admin API Documentation",
     *      description="Tjen Admin API Description",
     *      @OA\Contact(
     *          email="etiennelobato@gmail.com"
     *      ),
     * )
     * 
     *  @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Admin API server"
     *  )
     *
     * @OA\SecurityScheme(
     *      securityScheme="bearerAuth",
     *      type="http",
     *      scheme="bearer"
     * )
     * 
     */
}