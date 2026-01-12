<?php

/**
 * @OA\PathItem(
 *     path="/api/test",
 *     @OA\Get(
 *         summary="Test endpoint",
 *         description="A simple test endpoint for Swagger documentation",
 *         tags={"Test"},
 *         @OA\Response(
 *             response=200,
 *             description="Successful response",
 *             @OA\JsonContent(
 *                 @OA\Property(property="message", type="string", example="Hello World"),
 *                 @OA\Property(property="success", type="boolean", example=true),
 *                 @OA\Property(property="data", type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Test")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
