<?php

namespace Bedard\Saas\Tests\Unit\Classes;

use Bedard\Saas\Classes\ApiController;
use Bedard\Saas\Tests\PluginTestCase;

class ApiControllerTest extends PluginTestCase
{
    public function test_extending_the_api_controller_with_middleware()
    {
        ApiController::extend(function ($controller) {
            $controller->middleware(TestMiddleware::class);
        });

        $response = $this->get('/api/bedard/saas/products');

        $this->assertEquals('Hello from the test middleware!', $response->getContent());
    }
}

class TestMiddleware
{
    public function handle($request, $next)
    {
        $response = $next($request);
        $response->setContent('Hello from the test middleware!');

        return $response;
    }
}