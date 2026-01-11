<?php

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Rashid Backend API Documentation",
 *         description="Laravel API Documentation for Rashid Backend Application",
 *         @OA\Contact(
 *             email="admin@rashid.com"
 *         ),
 *         @OA\License(
 *             name="Apache 2.0",
 *             url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *         )
 *     ),
 *     @OA\Server(
 *         url=L5_SWAGGER_CONST_HOST,
 *         description="Rashid Backend API Server"
 *     ),
 *     @OA\PathItem(path="/api"),
 *     @OA\PathItem(path="/api/user/google-login"),
 *     @OA\PathItem(path="/api/user/subscriptions"),
 *     @OA\PathItem(path="/api/user/chat/start/{superstarId}"),
 *     @OA\PathItem(path="/api/user/chat/conversations"),
 *     @OA\PathItem(path="/api/user/chat/unread-count"),
 *     @OA\PathItem(path="/api/user/chat/messages/{conversationId}"),
 *     @OA\PathItem(path="/api/user/chat/send/{conversationId}"),
 *     @OA\PathItem(path="/api/user/chat/message/{messageId}"),
 *     @OA\PathItem(path="/api/user/payments/process"),
 *     @OA\PathItem(path="/api/user/payments/history"),
 *     @OA\PathItem(path="/api/user/payments/{paymentId}"),
 *     @OA\PathItem(path="/api/user/payment-history/user"),
 *     @OA\PathItem(path="/api/user/payment-history/superstar/{superstarId}"),
 *     @OA\PathItem(path="/api/user/payment-history/transaction/{transactionReference}"),
 *     @OA\PathItem(path="/api/superstar/login"),
 *     @OA\PathItem(path="/api/superstar/logout"),
 *     @OA\PathItem(path="/api/superstar/me"),
 *     @OA\PathItem(path="/api/superstar/profile"),
 *     @OA\PathItem(path="/api/superstar/change-password"),
 *     @OA\PathItem(path="/api/superstar/payments/history"),
 *     @OA\PathItem(path="/api/superstar/payments/system-revenue"),
 *     @OA\PathItem(path="/api/superstar/payments/user/{userId}")
 * )
 */
