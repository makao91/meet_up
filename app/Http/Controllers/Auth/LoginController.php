<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\OpenApi\Tags;
use OpenApi\Attributes as OAT;
use App\Services\LoginUserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Auth\LoginResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginController extends Controller
{
    #[OAT\Post(
        path: '/api/login',
        operationId: 'user.auth.login',
        summary: 'Login user',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(ref: '#/components/schemas/Requests\Auth\LoginRequest')
        ),
        tags: [Tags::AUTH],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'success',
                content: new OAT\JsonContent(properties: [
                    new OAT\Property(
                        property: 'data',
                        ref: '#/components/schemas/Resources\Auth\LoginResource',
                        type: 'object'
                    ),
                ])
            ),
            new OAT\Response(ref: '#/components/responses/unauthenticated', response: 401),
            new OAT\Response(ref: '#/components/responses/method_not_allowed', response: 405),
            new OAT\Response(ref: '#/components/responses/validation_error', response: 422),
        ]
    )]
    public function login(
        LoginRequest $request,
        LoginUserService $login_user_service,
    ): JsonResource {
        $login_response = $login_user_service->login($request);

        $user_details = $login_user_service->getUserDetails($login_response->getUserId());

        return new LoginResource([
            'login_response' => $login_response,
            'user_details' => $user_details,
        ]);
    }
}
