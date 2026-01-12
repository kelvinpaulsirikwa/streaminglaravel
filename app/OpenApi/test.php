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
 *                 @OA\Property(property="message", type="string", example="Hello World")
 *             )
 *         )
 *     )
 * )
 */
