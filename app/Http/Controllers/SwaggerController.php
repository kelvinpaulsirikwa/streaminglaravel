<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Rashid Backend API Documentation",
 *     description="Laravel API Documentation for Rashid Backend Application"
 * )
 */
class SwaggerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/test",
     *     summary="Test endpoint",
     *     description="A simple test endpoint for Swagger documentation",
     *     tags={"Test"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Hello World")
     *         )
     *     )
     * )
     */
    public function test()
    {
        return response()->json(['message' => 'Hello World']);
    }
}
