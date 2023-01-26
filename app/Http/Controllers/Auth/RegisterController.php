<?php

namespace App\Http\Controllers\Auth;

use App\Http\OpenApi\Tags;
use OpenApi\Attributes as OAT;
use App\Services\RegisterService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\ConfirmUserEmailRequest;

class RegisterController extends Controller
{
    #[OAT\Post(
        path: '/api/register',
        operationId: 'user.auth.register',
        summary: 'Register user',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(ref: '#/components/schemas/Requests\Auth\RegisterUserRequest')
        ),
        tags: [Tags::AUTH],
        responses: [
            new OAT\Response(ref: '#/components/responses/no_content', response: 201),
            new OAT\Response(ref: '#/components/responses/method_not_allowed', response: 405),
            new OAT\Response(ref: '#/components/responses/validation_error', response: 422),
        ]
    )]
    public function register(
        RegisterUserRequest $request,
        RegisterService $register_service,
    ): JsonResponse {
        $user = $register_service->register($request);
        $register_service->sendVerifyEmailAfterRegistrationEmail(
            $user->id,
            sha1($user->email)
        );

        return new JsonResponse(null, 201);
    }

    #[OAT\Post(
        path: '/api/register/confirm-email',
        operationId: 'user.auth.register.confirm-email',
        summary: 'Confirm user email address',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(ref: '#/components/schemas/Requests\Auth\ConfirmUserEmailRequest')
        ),
        tags: [Tags::AUTH],
        responses: [
            new OAT\Response(ref: '#/components/responses/no_content', response: 204),
            new OAT\Response(ref: '#/components/responses/bad_request', response: 400),
            new OAT\Response(ref: '#/components/responses/method_not_allowed', response: 405),
            new OAT\Response(ref: '#/components/responses/validation_error', response: 422),
        ]
    )]
    public function confirmEmail(
        ConfirmUserEmailRequest $request,
        RegisterService $register_service
    ): JsonResponse {
        $register_service->confirmEmail($request);

        return new JsonResponse(null, 204);
    }
}
