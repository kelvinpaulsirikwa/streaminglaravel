<?php

/**
 * @OA\OpenApi(
 *   @OA\Info(
 *     title="Rashid Backend API Documentation",
 *     version="1.0.0",
 *     description="Complete API Documentation for Rashid Backend Application",
 *     @OA\Contact(
 *       name="Rashid Backend Team"
 *     )
 *   ),
 *   @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Rashid Backend API Server"
 *   )
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="sanctum",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="JWT"
 * )
 *
 * @OA\PathItem(
 *   path="/api/test",
 *   @OA\Get(
 *     summary="Test endpoint",
 *     description="A simple test endpoint for Swagger documentation",
 *     tags={"Test"},
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Hello World"),
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Test")
 *             )
 *         )
 *     )
 *   )
 * )
 */
