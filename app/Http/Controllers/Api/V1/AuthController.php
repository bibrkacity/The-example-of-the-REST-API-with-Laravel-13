<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ApiException;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use App\Http\Controllers\Api\ApiController;

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
    public function login(Request $request): JsonResponse
    {

        $email = trim($request->input('email'));
        $password = trim($request->input('password'));

        $query = User::query()
            ->where('email', $email);
        $user = $query->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw new ApiException('Invalid login or password', ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('start');

        return new JsonResponse(data: [
            'token' => $token->plainTextToken,
        ], status: ResponseAlias::HTTP_OK, json: false);

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

        $user = $request->user();

        return new JsonResponse(data: ['data' => $user->toArray()], status: ResponseAlias::HTTP_OK, json: false);

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
            new OA\Response(response: ResponseAlias::HTTP_OK, description: 'Message Ok'),
        ]
    )]
    public function logout(): JsonResponse
    {

        $user = auth()->user();
        $user->tokens()->delete(); //Все устройства

        // $user->currentAccessToken()->delete(); //Только текущее устройство
        return new JsonResponse(data: ['data' => ['message' => 'Ok']], status: ResponseAlias::HTTP_OK, json: false);
    }
}
