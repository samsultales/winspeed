<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
* @OA\Info(
*      version="1.0.0",
*      title="Dokumentasi API",
*      description="Lorem Ipsum",
*      @OA\Contact(
*          email="ogya.surabaya@gmail.com"
*      ),
*      @OA\License(
*          name="Apache 2.0",
*          url="http://www.apache.org/licenses/LICENSE-2.0.html"
*      )
* )
*
* @OA\Server(
*      url=L5_SWAGGER_CONST_HOST,
*      description="Demo Integrasi API Server Laravel"
* )
*
* @OA\SecurityScheme(
*      securityScheme="bearerAuth",
*      type="http",
*      scheme="bearer"
* )
*/
abstract class Controller extends BaseController
{
    //
    use AuthorizesRequests, ValidatesRequests;
}
