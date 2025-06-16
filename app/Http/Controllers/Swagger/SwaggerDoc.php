<?php

namespace App\Http\Controllers\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Dictionary API",
 *     version="1.0.0",
 *     description="Documentação da API de dicionário",
 *     @OA\Contact(
 *         email="lucasdemouramacedo@gmail.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 */
class SwaggerDoc
{
    //
}
