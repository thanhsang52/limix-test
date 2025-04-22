<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Blog Post API",
 *     description="API for managing blog posts",
 *     @OA\Contact(
 *         email="support@example.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost/api",
 *     description="Local API server"
 * )
 *
 * @OA\PathItem(path="/api")
 */
class SwaggerController
{
    // This class is just for OpenAPI annotations, no methods needed
}
