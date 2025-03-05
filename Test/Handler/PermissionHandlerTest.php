<?php

declare(strict_types=1);

namespace Test\Handler;

use App\Handler\PermissionHandler;
use App\Provider\TokenDataProvider;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use ProgPhil1337\SimpleReactApp\HTTP\Response\JSONResponse;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\RouteParameters;
use Psr\Http\Message\ServerRequestInterface;

class PermissionHandlerTest extends TestCase
{
    private PermissionHandler $unit;

    public function setUp(): void
    {
        $this->unit = new PermissionHandler(new TokenDataProvider());
    }

    /**
     * @dataProvider tokenProvider
     */
    public function testPermissionHandler(string $tokenParam, int $expectedStatus, array $expectedResponse): void
    {
        $serverRequest = $this->createMock(ServerRequestInterface::class);
        $parameters = $this->createMock(RouteParameters::class);
        $parameters->method('get')->willReturn($tokenParam);

        $actualResponse = $this->unit->__invoke($serverRequest, $parameters);

        $this->assertInstanceOf(JSONResponse::class, $actualResponse);
        $this->assertEquals($expectedStatus, $actualResponse->getCode());
        $this->assertEquals($expectedResponse, json_decode($actualResponse->getContent(), true));
    }

    public static function tokenProvider(): iterable
    {
        yield 'missing token' => [
            'tokenParam' => 'no_token',
            'expectedStatus' => StatusCodeInterface::STATUS_BAD_REQUEST,
            'expectedResponse' => ['permission' => false, 'message' => 'Token param is missing'],
        ];

        yield 'invalid token' => [
            'tokenParam' => 'invalid_token',
            'expectedStatus' => StatusCodeInterface::STATUS_FORBIDDEN,
            'expectedResponse' => ['permission' => false, 'message' => 'Permission denied'],
        ];

        yield 'permission denied' => [
            'tokenParam' => 'tokenWriteOnly',
            'expectedStatus' => StatusCodeInterface::STATUS_FORBIDDEN,
            'expectedResponse' => ['permission' => false, 'message' => 'Permission denied'],
        ];

        yield 'permission granted' => [
            'tokenParam' => 'token1234',
            'expectedStatus' => StatusCodeInterface::STATUS_OK,
            'expectedResponse' => ['permission' => true, 'message' => 'Permission granted'],
        ];
    }
}
