<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\OpenApi\Tags;
use OpenApi\Attributes as OAT;
use Illuminate\Http\JsonResponse;
use App\Services\LoginUserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TokenRequest;

class LogoutController extends Controller
{
    #[OAT\Post(
        path: '/api/logout',
        operationId: 'user.auth.logout',
        summary: 'Logout user',
        tags: [Tags::AUTH],
        responses: [
            new OAT\Response(ref: '#/components/responses/no_content', response: 204),
            new OAT\Response(ref: '#/components/responses/unauthenticated', response: 403),
            new OAT\Response(ref: '#/components/responses/method_not_allowed', response: 405),
        ]
    )]
    public function logout(
        TokenRequest $request,
        LoginUserService $login_user_service
    ): JsonResponse {
        $login_user_service->logout($request->getToken());

        return new JsonResponse(null, 204);
    }
}
