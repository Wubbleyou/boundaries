<?php

namespace Wubbleyou\Boundaries\BoundaryRules;

use Illuminate\Routing\Route;
use Tests\TestCase;

class MiddlewareRule extends BoundaryRule {
    public string $name = 'MiddlewareRule';

    public function __construct(public array $middleware) {}

    public function handle(Route $route, TestCase $test, string $routeName): array
    {
        $errors = [];
        $currentMiddleware = $route->gatherMiddleware();
        $routeName = $route->getName() ?? $route->getActionName();

        foreach ($this->middleware as $expectedMiddleware) {
            if (!in_array($expectedMiddleware, $currentMiddleware, true)) {
                $errors[] = $this->getName() . " - {$routeName} should have middleware [{$expectedMiddleware}] but does not [" . implode(',', $currentMiddleware) . ']';
            }
        }

        return $errors;
    }
}