<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Auth\Login;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Responses\LoginResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends ApiController
{
    #[OA\Post(
        path: '/login',
        description: 'Authorization and return API-token',
        summary: 'Authorization and return API-token',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/x-www-form-urlencoded',
                schema: new OA\Schema(
                    required: [
                        'email',
                        'password',
                    ],
                    properties: [
                        new OA\Property(property: 'email', description: 'E-mail login', type: 'string', default: ''),
                        new OA\Property(property: 'password', description: 'Password  for login', type: 'string', default: ''),
                    ]
                )
            )
        ),
        tags: ['Auth'],
        responses: [
            new OA\Response(response: ResponseAlias::HTTP_OK, description: 'API-token'),
        ]
    )]
    public function login(LoginRequest $request, Login $action): LoginResponse
    {
        return $action->handle($request);
    }

    #[OA\Get(
        path: '/auth/user',
        description: 'Info about current user',
        summary: 'User info',
        security: [
            ['bearerAuth' => []],
        ],
        tags: ['Auth'],
        responses: [
            new OA\Response(response: ResponseAlias::HTTP_OK, description: 'Current user'),
        ]
    )]
    public function getUser(Request $request): JsonResponse
    {
        return new JsonResponse(data: ['data' => $request->user()->toArray()], status: ResponseAlias::HTTP_OK, json: false);
    }

    #[OA\Get(
        path: '/auth/logout',
        description: 'Revoking current token',
        summary: 'Revoking current token',
        security: [
            ['bearerAuth' => []],
        ],
        tags: ['Auth'],
        responses: [
            new OA\Response(response: ResponseAlias::HTTP_NO_CONTENT, description: 'Successfully logout'),
        ]
    )]
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return new JsonResponse(data: null, status: ResponseAlias::HTTP_NO_CONTENT, json: false);
    }
}
