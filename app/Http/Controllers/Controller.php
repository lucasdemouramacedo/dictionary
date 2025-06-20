<?php

namespace App\Http\Controllers;

/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     in="header",
 *     name="Authorization",
 *     description="Token JWT no formato: Bearer {token}"
 * )
 */
abstract class Controller
{
    //
}
