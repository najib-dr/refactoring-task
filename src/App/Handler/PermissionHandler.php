<?php

declare(strict_types=1);

namespace App\Handler;

use App\Provider\TokenDataProvider;
use Fig\Http\Message\StatusCodeInterface;
use ProgPhil1337\SimpleReactApp\HTTP\Response\JSONResponse;
use ProgPhil1337\SimpleReactApp\HTTP\Response\ResponseInterface;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\Attribute\Route;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\Handler\HandlerInterface;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\HttpMethod;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\RouteParameters;
use Psr\Http\Message\ServerRequestInterface;

#[Route(httpMethod: HttpMethod::GET, uri: '/has_permission/{token}')]
class PermissionHandler implements HandlerInterface
{
    private const MSG_PERMISSION_DENIED = 'Permission denied';
    private const MSG_PERMISSION_GRANTED = 'Permission granted';
    private const MSG_TOKEN_MISSING = 'Token param is missing';

    public function __construct(
        private readonly TokenDataProvider $dataProvider,
    ) {
    }

    public function __invoke(ServerRequestInterface $serverRequest, RouteParameters $parameters): ResponseInterface
    {
        $tokenParam = $parameters->get('token', 'no_token');

        if ($tokenParam === 'no_token') {
            return $this->jsonResponse(self::MSG_TOKEN_MISSING, StatusCodeInterface::STATUS_BAD_REQUEST, false);
        }

        $token = $this->getToken($tokenParam);

        if ($token === null || !$this->hasReadPermission($token)) {
            return $this->jsonResponse(self::MSG_PERMISSION_DENIED, StatusCodeInterface::STATUS_FORBIDDEN, false);
        }

        return $this->jsonResponse(self::MSG_PERMISSION_GRANTED, StatusCodeInterface::STATUS_OK, true);
    }

    private function getToken(string $tokenParam): ?array
    {
        return array_find(
            $this->dataProvider->getTokens(),
            fn($token) => $token['token'] === $tokenParam,
        );
    }

    private function hasReadPermission(array $token): bool
    {
        return in_array('read', $token['permissions']);
    }

    private function jsonResponse(string $message, int $status, bool $permission): JSONResponse
    {
        return new JSONResponse(['permission' => $permission, 'message' => $message], $status);
    }
}
